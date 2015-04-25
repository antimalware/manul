<?php

ob_start();
require_once('Archiver.inc.php');
require_once('FileInfo.inc.php');
ob_end_clean();

class Healer
{
    public function __construct()
    {
        global $projectTmpDir;

        $timeString = date('Y_m_d_H_i', $_SERVER['REQUEST_TIME']);
        $this->quarantineFilepath = $projectTmpDir . '/quarantine.' . $timeString . '.zip';
        $this->quarantineFilepathFilepath = $projectTmpDir . '/malware_quarantine_filepath.tmp.txt';
        $this->backupFilepath = $projectTmpDir . '/manul_deleted_files_backup.' . $timeString . '.zip';

        $this->log = '';

        $this->webRootDir = $_SERVER['DOCUMENT_ROOT'];

        if (file_exists($this->quarantineFilepath)) {
            $this->log .= sprintf(PS_DELETE_ARCHIVE, $this->quarantineFilepath);
            unlink($this->quarantineFilepath);
        }

        $this->archiver = null;
    }

    private function getQuarantineFilename()
    {
        return $this->quarantineFilepath;
    }

    private function parseXmlRecipe($xmlRecipe)
    {
        $dom = null;
        try {
            $dom = new DOMDocument('1.0', 'utf-8');
            $dom->formatOutput = true;
            $dom->loadXML($xmlRecipe);
        } catch (Exception $e) {
            die(sprintf(PS_ERR_EXCEPTION_OCCURED, $e->getMessage()));
        }
        if (!$dom) {
            die(sprintf(PS_ERR_EXCEPTION_OCCURED, ''));
        }
        return $dom;
    }

    private function quarantineFile($filename)
    {

        if (!is_file($filename)) {
            $this->log .= '<div class="err">' . sprintf(PS_ERR_QUARANTINE_NOT_EXISTS, $filename) . '</div>';
            return false;
        }

        $fileinfo = new FileInfo($filename);
        $fileHash = $fileinfo->md5;

        $this->archiver->addFile($filename, $fileHash);
        $metaFilename = $fileHash . '.meta';

        $this->archiver->createFile($metaFilename, (string)$fileinfo);

        return true;
    }

    private function deleteFile($filename)
    {
        if (!is_file($filename)) {
            $this->log .= '<div class="err">' . sprintf(PS_ERR_DELETE_NOT_EXISTS, $filename) . '</div>';
            return false;
        }

        return unlink($filename);
    }

    public function deleteDir($dirname)
    {
        if (!is_dir($dirname) || is_link($dirname)) return unlink($dirname);
        foreach (scandir($dirname) as $file) {
            if ($file === '.' || $file === '..') continue;
            if (!$this->deleteDir($dirname . DIRECTORY_SEPARATOR . $file)) {
                chmod($dirname . DIRECTORY_SEPARATOR . $file, 0777);
                $this->deleteDir($dirname . DIRECTORY_SEPARATOR . $file);
            };
        }

        return rmdir($dirname);
    }

    public function prepareList($xmlRecipe, &$quarantineList, &$deleteList)
    {
        $recipe = $this->parseXmlRecipe($xmlRecipe);

        $quarantineFiles = $recipe->getElementsByTagName('quarantine');
        $deleteFiles = $recipe->getElementsByTagName('delete');

        foreach ($quarantineFiles as $quarantineFileNode) {
            $filename = trim(str_replace('../', '', $quarantineFileNode->nodeValue));
            $quarantineList[] = $filename;
        }

        foreach ($deleteFiles as $deleteFileNode) {
            $filename = trim(str_replace('../', '', $deleteFileNode->nodeValue));
            $deleteList[] = $filename;
        }
    }

    public function executeXmlRecipe($deleteFiles, $quarantineFiles, &$numQuarantined)
    {
        //Put suspicious file in quarantine archive
        $this->archiver = new Archiver($this->quarantineFilepath, 'a');

        $numQuarantined = 0;

        foreach ($quarantineFiles as $filename) {
            $absolutePath = $this->webRootDir . substr($filename, 1);
            if ($this->quarantineFile($absolutePath)) {
                $this->log .= sprintf(PS_WAS_QUARANTINED, $filename) . '<br/>';
                $numQuarantined++;
            }
        }

        $this->archiver->close();

        //Put malicious files to backup archive and delete them      
        foreach ($deleteFiles as $filename) {
            $this->archiver = new Archiver($this->backupFilepath, 'a');

            $absolutePath = $this->webRootDir . substr($filename, 1);

            $this->quarantineFile($absolutePath);

            $this->archiver->close();

            if ($this->deleteFile($absolutePath)) {
                $this->log .= sprintf(PS_WAS_DELETED, $filename) . '<br/>';
            }
        }

        if (file_exists($this->quarantineFilepath)) {
            file_put_contents2($this->quarantineFilepathFilepath, $this->quarantineFilepath);
        }

        return $this->log;
    }
}

<?php

require_once('Archiver.inc.php');
require_once('FileInfo.inc.php');

class Healer
{

    function __construct()
    {
        global $projectTmpDir;

        $timeString = date('Y_m_d_H_i', $_SERVER['REQUEST_TIME']);
        $this->quarantineFilename = $projectTmpDir . '/quarantine.' . $timeString . '.zip';
        $this->backupFilename = $projectTmpDir . '/manul_deleted_files_backup.' . $timeString . '.zip';

        $this->log = "";

        $this->webRootDir = $_SERVER['DOCUMENT_ROOT'];

        if (file_exists($this->quarantineFilename) && (!isset($_COOKIE['quarantine_file']))) {
            $this->log .= sprintf(PS_DELETE_ARCHIVE, $this->quarantineFilename);
            unlink($this->quarantineFilename);
        }

        $this->archiver = null;
    }

    function getQuarantineFilename()
    {
        return $this->quarantineFilename;
    }

    function parseXmlRecipe($xmlRecipe)
    {
        $dom = NULL;
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

    function quarantineFile($filename)
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

    function deleteFile($filename)
    {
        if (!is_file($filename)) {
            $this->log .= '<div class="err">' . sprintf(PS_ERR_DELETE_NOT_EXISTS, $filename) . '</div>';
            return false;
        }

        return unlink($filename);
    }

    function deleteDir($dirname)
    {
        if (!is_dir($dirname) || is_link($dirname)) return unlink($dirname);
        foreach (scandir($dirname) as $file) {
            if ($file == '.' || $file == '..') continue;
            if (!$this->deleteDir($dirname . DIRECTORY_SEPARATOR . $file)) {
                chmod($dirname . DIRECTORY_SEPARATOR . $file, 0777);
                $this->deleteDir($dirname . DIRECTORY_SEPARATOR . $file);
            };
        }

        return rmdir($dirname);
    }

    function prepareList($xmlRecipe, &$quarantineList, &$deleteList)
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

    function executeXmlRecipe($deleteFiles, $quarantineFiles, &$numQuarantined)
    {

        //Put suspicious file in quarantine archive
        $this->archiver = new Archiver($this->quarantineFilename, 'a');

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
            $this->archiver = new Archiver($this->backupFilename, "a");

            $absolutePath = $this->webRootDir . substr($filename, 1);

            $this->quarantineFile($absolutePath);

            $this->archiver->close();

            if ($this->deleteFile($absolutePath)) {
                $this->log .= sprintf(PS_WAS_DELETED, $filename) . '<br/>';
            }
        }


        if (file_exists($this->quarantineFilename)) {
            setcookie('quarantine_file', $this->quarantineFilename, time() + 86400, '/', $_SERVER['HTTP_HOST'], false, true);
            $_COOKIE['quarantine_file'] = $this->quarantineFilename;
        }

        return $this->log;
    }
}

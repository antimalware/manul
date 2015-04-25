<?php

ob_start();
require_once('Archiver.inc.php');
require_once('FileInfo.inc.php');
ob_end_clean();

class Quarantiner
{

    function __construct($defaultFilename = null)
    {
        global $projectTmpDir;
        $this->quarantineList = array();

        if (!$defaultFilename) {
            $timeString = date('Y_m_d_H_i', $_SERVER['REQUEST_TIME']);
            $this->quarantineFilename = $projectTmpDir . '/quarantine.' . $timeString . '.zip';
        } else {
            $this->quarantineFilename = $defaultFilename;
        }

        $this->web_root_dir = $_SERVER['DOCUMENT_ROOT'];

        if (file_exists($this->quarantineFilename)) {
            unlink($this->quarantineFilename);
        }
    }

    function add($filename)
    {
        if (file_exists($filename)) {
            $this->quarantineList[] = $filename;
            return true;
        }

        return false;
    }

    function getArchive()
    {
        $this->archiver = new Archiver($this->quarantineFilename, 'a');

        foreach ($this->quarantineList as $filename) {
            $fileinfo = new FileInfo($filename);
            $fileHash = $fileinfo->md5;
            $this->archiver->addFile($filename, $fileHash);
            $metaFilename = $fileHash . '.meta';

            $this->archiver->createFile($metaFilename, (string)$fileinfo);
        }

        $this->archiver->close();

        return $this->quarantineFilename;
    }

}

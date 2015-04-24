<?php

ob_start();
require_once('Archiver.inc.php');
require_once('Auth.inc.php');
ob_end_clean();

class DownloadController
{
    function __construct()
    {
        $authenticator = new Auth();
        if (!$authenticator->auth()) {
            header('HTTP/1.0 403 Forbidden');
            die();
        }
    }

    function streamFileContent($filename, $needToDelete = false)
    {
        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: private', false);
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename=' . basename($filename) . ';');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($filename));
        readfile($filename);

        if ($needToDelete) {
            unlink($filename);
        }
    }

    function getPackedArchive()
    {
        global $projectTmpDir;

        $logFilename = $projectTmpDir . '/scan_log.xml';
        $packedLogFilename = $logFilename . '.zip';

        if (!is_file($logFilename)) {
            die(PS_ERR_NO_DOWNLOAD_LOG_FILE);
        }

        $xml_data = file_get_contents($logFilename);
        $archiver = new Archiver($packedLogFilename, 'a');
        $archiver->createFile(basename($logFilename), $xml_data);

        $quarantineFilepathFilepath = $projectTmpDir . '/malware_quarantine_filepath.tmp.txt';
        if (file_exists($quarantineFilepathFilepath)) {
            $quarantineFilepath = file_get_contents($quarantineFilepathFilepath);
            $archiver->addFile($quarantineFilepath, basename($quarantineFilepath));
            unlink($quarantineFilepathFilepath);
        }

        $archiver->close();

        $this->streamFileContent($packedLogFilename, true);
        unlink($logFilename);
    }

    function getQuarantine()
    {
        $quarantineFilename = $_COOKIE['quarantine_file'];
        if (!is_file($quarantineFilename)) {
            die(PS_ERR_NO_QUARANTINE_FILE);
        }

        $this->streamFileContent($quarantineFilename, true);
        unset($_COOKIE['quarantine_file']);
        setcookie('quarantine_file', -1, -1, '/', $_SERVER['HTTP_HOST'], false, true);
        exit;
    }

    function start()
    {
        switch ($_GET['f']) {
            case 'report':
                $this->getPackedArchive();
                break;
            case 'quarantine':
                $this->getQuarantine();
                break;
        }
    }
}

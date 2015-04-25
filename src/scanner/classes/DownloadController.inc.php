<?php

ob_start();
require_once('Archiver.inc.php');
require_once('Auth.inc.php');
ob_end_clean();

class DownloadController
{
    private function streamFileContent($filename, $needToDelete = false)
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

    private function getPackedArchive()
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

    private function getQuarantine()
    {
        global $projectTmpDir;

        $quarantineFilepathFilepath = $projectTmpDir . '/malware_quarantine_filepath.tmp.txt';
        if (file_exists($quarantineFilepathFilepath)) {
            $quarantineFilepath = file_get_contents($quarantineFilepathFilepath);
            $this->streamFileContent($quarantineFilepath, true);
            unlink($quarantineFilepathFilepath);
        }
    }

    private function startDownload()
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

    public function start()
    {
        $authenticator = new Auth();
        if ($authenticator->auth()) {
            $this->startDownload();
        }
    }

}


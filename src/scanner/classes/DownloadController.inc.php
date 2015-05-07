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
        $packedLogFilename = XML_LOG_FILEPATH . '.zip';

        if (!is_file(XML_LOG_FILEPATH)) {
            die(PS_ERR_NO_DOWNLOAD_LOG_FILE);
        }

        $xml_data = file_get_contents(XML_LOG_FILEPATH);
        $archiver = new Archiver($packedLogFilename, 'a');
        $archiver->createFile(basename(XML_LOG_FILEPATH), $xml_data);

        if (file_exists(TMP_QUARANTINE_FILEPATH_FILEPATH)) {
            $quarantineFilepath = file_get_contents(TMP_QUARANTINE_FILEPATH_FILEPATH);
            $archiver->addFile($quarantineFilepath, basename($quarantineFilepath));
            unlink(TMP_QUARANTINE_FILEPATH_FILEPATH);
        }

        $archiver->close();

        $this->streamFileContent($packedLogFilename, true);
        unlink(XML_LOG_FILEPATH);
    }

    private function getQuarantine()
    {
        if (file_exists(TMP_QUARANTINE_FILEPATH_FILEPATH)) {
            $quarantineFilepath = file_get_contents(TMP_QUARANTINE_FILEPATH_FILEPATH);
            $this->streamFileContent($quarantineFilepath, true);
            unlink(TMP_QUARANTINE_FILEPATH_FILEPATH);
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


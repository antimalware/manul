<?php

ob_start();
require_once('Localization.inc.php');
require_once('FileInfo.inc.php');
require_once('WebServerEnvInfo.inc.php');
require_once('View.inc.php');
require_once('FileList.inc.php');
require_once('Archiver.inc.php');
require_once('Auth.inc.php');
require_once('CmsVersionDetector.inc.php');
require_once('MalwareDetector.inc.php');
require_once('Writer.inc.php');
ob_end_clean();

class ScannerController
{

    private function templateOutput($interval)
    {
        $view = new View();
        define('PS_REQUEST_DELAY', $interval);
        $view->display('scanner.tpl');
    }

    private function removeTempFiles()
    {
        global $projectTmpDir;
        @unlink($projectTmpDir . '/scan_log.xml');
        array_map('unlink', glob($projectTmpDir . '/*.manul.tmp.txt'));
        array_map('unlink', glob($projectTmpDir . '/*.manul.tmp'));
    }

    private function getXMLReport()
    {
        $fileScanner = new FileList();

        $dom = new DOMDocument('1.0', 'utf-8');

        // create report container
        $websiteInfoNode = $dom->createElement('website_info');
        $dom->appendChild($websiteInfoNode);

        // gather server info and append it to the report
        $wsEnv = new WebServerEnvInfo();
        $wsEnvNode = $wsEnv->getXMLNode();
        $dom->documentElement->appendChild($dom->importNode($wsEnvNode, true));


        // gather cms list and append it to the report
        $cmsDetector = new CmsVersionDetector($_SERVER['DOCUMENT_ROOT']);
        $cmsListNode = $cmsDetector->getXMLNode();
        $dom->documentElement->appendChild($dom->importNode($cmsListNode, true));

        // retrieve list of files and append it to the report
        $tmpXmlDoc = new DOMDocument();
        $tmpXmlDoc->loadXML('<files>' . $fileScanner->getXMLFilelist() . '</files>');

        $dom->documentElement->appendChild($dom->importNode($tmpXmlDoc->documentElement, true));

        return $dom->saveXML();
    }

    public function start()
    {
        global $projectTmpDir, $php_errormsg;

        $authenticator = new Auth();
        if ($authenticator->auth()) {

            ob_start();

            if (!empty($_GET['a'])) {
                $action = $_GET['a'];
                $interval = empty($_GET['delay']) ? 5 : (int)$_GET['delay'];

                $fileScanner = new FileList();
                $fileScanner->setInterval($interval);

                if ($action === 'cleanUp') {

                    $this->removeTempFiles();
                    print(json_encode(array('type' => 'cleanUp', 'status' => 'ok', 'phpError' => $php_errormsg)));

                } else if ($action === 'getFileList') {

                    echo $fileScanner->performScanning();

                } else if ($action === 'getSignatureScanResult') {

                    $this->detector = new MalwareDetector();
                    $this->detector->setRequestDelay($interval);
                    print $this->detector->malwareScanRound();

                } else if ($action === 'getWebsiteLog') {
                    //REPORTING
                    $xmlLog = $this->getXMLReport();
                    $logFilename = $projectTmpDir . '/scan_log.xml';
                    file_put_contents2($logFilename, $xmlLog);

                    print json_encode(array('type' => 'getWebsiteLog', 'status' => 'ok', 'phpError' => $php_errormsg));
                }
            } else {
                //GENERATE INTERFACE
                $fileScanner = new FileList();
                define('PS_ARCHIVE_DOWNLOAD_URL', $_SERVER['PHP_SELF'] . '?controller=download&f=report');
                $this->templateOutput($fileScanner->getInterval());
            }
        }
    }


}
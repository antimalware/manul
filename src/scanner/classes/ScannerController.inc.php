<?php

ob_start();
require_once('Localization.inc.php');
require_once("FileInfo.inc.php");
require_once("WebServerEnvInfo.inc.php");
require_once("View.inc.php");
require_once("FileList.inc.php");
require_once("Archiver.inc.php");
require_once("Auth.inc.php");
require_once("CmsVersionDetector.inc.php");
require_once("MalwareDetector.inc.php");
require_once("Writer.inc.php");
ob_end_clean();

class ScannerController {

	function templateOutput($interval) {
		   $view = new View();
           define('PS_REQUEST_DELAY', $interval);
		   $view->display("scanner.tpl");
	}

    function removeTempFiles() {
        global $project_tmp_dir;         
        @unlink($project_tmp_dir . '/scan_log.xml');
        array_map('unlink', glob($project_tmp_dir . "/*.manul.tmp.txt"));
        array_map('unlink', glob($project_tmp_dir . "/*.manul.tmp"));
    }

    function getXMLReport() {
		$file_scanner = new FileList();

        $dom = new DOMDocument('1.0', 'utf-8');

		// create report container
		$website_info_node = $dom->createElement('website_info');
		$dom->appendChild($website_info_node);

		// gather server info and append it to the report
		$ws_env = new WebServerEnvInfo();
		$ws_env_node = $ws_env->getXMLNode();
		$dom->documentElement->appendChild($dom->importNode($ws_env_node, true));


		// gather cms list and append it to the report
        $cmsDetector = new CmsVersionDetector($_SERVER['DOCUMENT_ROOT']);
		$cmsListNode = $cmsDetector->getXMLNode();
		$dom->documentElement->appendChild($dom->importNode($cmsListNode, true));

		// retrieve list of files and append it to the report
		$tmp_xml_doc = new DOMDocument();
		$tmp_xml_doc->loadXML('<files>' . $file_scanner->getXMLFilelist() . '</files>');

        $dom->documentElement->appendChild($dom->importNode($tmp_xml_doc->documentElement, true));

        return $dom->saveXML();
    }

	function start() { 

        global $project_tmp_dir, $php_errormsg;         
        
		$authenticator = new Auth();

		if ($authenticator->auth()) {

			ob_start();

			if ( !empty($_GET['a']) )
			{	
				$action = $_GET['a'];
                $interval = empty($_GET['delay']) ? 5 : (int)$_GET['delay'];
               
				$fileScanner = new FileList();
                $fileScanner->setInterval($interval);                

  				if ($action == 'cleanUp') {	 

                    $this->removeTempFiles();                       
                    print(json_encode(array('type' => 'cleanUp', 'status' => 'ok', 'phpError' => $php_errormsg)));
                    
                } else if ($action == 'getFileList') {	                   					

                    echo $fileScanner->performScanning();     					
                               
				} else if ($action == 'getSignatureScanResult') {

                    $this->detector = new MalwareDetector();
                    $this->detector->setRequestDelay($interval);
                    print $this->detector->malwareScanRound();

				} else if ($action == 'getWebsiteLog') {
         				//REPORTING
                        $xml_log = $this->getXMLReport();                         
                        $log_filename = $project_tmp_dir . '/scan_log.xml';
     			        file_put_contents2($log_filename, $xml_log);      

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
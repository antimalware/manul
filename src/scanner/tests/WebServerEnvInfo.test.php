<?php

require_once('config.php');
$classname_filename = str_replace('test', 'inc', basename(__FILE__));
require_once($project_classes_dir . '/' . $classname_filename);

class WebServerEnvInfoTest extends PHPUnit_Framework_TestCase {
    public function __construct() {
        $_SERVER["SCRIPT_FILENAME"] = __FILE__;
        $_SERVER["DOCUMENT_ROOT"] = dirname(__FILE__);
        $_SERVER["HTTP_HOST"] = "testhost.com";
        $_SERVER["SERVER_ADMIN"] = "admin@testhost.com";;
        $_SERVER["REQUEST_TIME"] = 0;
        $_SERVER["SERVER_ADDR"] = '8.8.8.8';
        $_SERVER["SERVER_SOFTWARE"] = 'nginx/1.2.1';
        $_SERVER["GATEWAY_INTERFACE"] = 'CGI/1.1';
        $_SERVER["SERVER_SIGNATURE"] = '';

        $this->siteInfo = new WebServerEnvInfo();
    }

    public function testXML() {


        $dom = new DOMDocument("1.0", "utf-8");
        $dom->formatOutput = true;
 
        $website_node = $this->siteInfo->getXMLNode();      
        $main_node = $dom->createElement("website_info");


        $main_node->appendChild($dom->importNode($website_node, true));
        $dom->appendChild($main_node);
        $time_node = $dom->getElementsByTagName('time')->item(0); 
        $time = $time_node->nodeValue;

        $this->assertTrue((bool)preg_match('/^\d{4}\.\d{2}\.\d{2} \d{2}:\d{2}:\d{2}$/', $time));        
        $time_node->nodeValue = 0;

        $this->assertEquals($dom->saveXML(), file_get_contents('test_cases/webServerEnvInfo.xml'));

    }
}

?>
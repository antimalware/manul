<?php

require_once('config.php');
$classname_filename = str_replace('test', 'inc', basename(__FILE__));
require_once($project_classes_dir . '/' . $classname_filename);

class FileInfoTest extends PHPUnit_Framework_TestCase {
    public function __construct() {
        global $project_root_dir; 
        $_SERVER['DOCUMENT_ROOT'] = dirname($project_root_dir);
 
        $test_filename = dirname(__FILE__) . '/test_file.txt';
        if (is_file($test_filename)) {
            unlink($test_filename);
        }     
        file_put_contents($test_filename, 'Test data.');
        $this->fileinfo = new FileInfo($test_filename);         
        unlink($test_filename);
        
    }

    public function testTime() {
        $this->assertTrue($this->fileinfo->ctime > 1384254284 && $this->fileinfo->ctime < 1484254284);
        $this->assertTrue($this->fileinfo->mtime > 1384254284 && $this->fileinfo->mtime < 1484254284);    
        $this->assertTrue($this->fileinfo->mtime >= $this->fileinfo->ctime);    
    }

    public function testGetInfoByName() {
        $this->fileinfo->ctime = 0;
        $this->fileinfo->mtime = 0;

        $correct_result = "./pat/tests/test_file.txt;10;0;0;1000;33204;93a5e159;64353b9c;56dd8a439abf97fda051f88f09f00d65;caa0bdc08266c1ef51d49bba17cef09b";

        $this->assertEquals((string)$this->fileinfo, $correct_result);
    }

    public function testXML() {
        $this->fileinfo->ctime = 0;
        $this->fileinfo->mtime = 0;

        $dom = new DOMDocument("1.0", "utf-8");
        $dom->formatOutput = true;
        $files_node = $dom->createElement("files");
        $node = $this->fileinfo->getXMLNode();
        $files_node->appendChild($dom->importNode($node, true));
        $dom->appendChild($files_node);
        $this->assertEquals($dom->saveXML(), file_get_contents('test_cases/FileInfo.xml'));

    }
}

?>
<?php

require_once('config.php');
$classname_filename = str_replace('test', 'inc', basename(__FILE__));
require_once($project_classes_dir . '/' . $classname_filename);

class AjaxScannerTest extends PHPUnit_Framework_TestCase {
    public function __construct() {
        global $project_root_dir; 
        $_SERVER['DOCUMENT_ROOT'] = dirname($project_root_dir);

        $log_filename = 'log.tmp';
        $this->scanner = new AjaxScanner($log_filename);

    } 


function rrmdir($dir) {
   if (is_dir($dir)) {
     $objects = scandir($dir);
     foreach ($objects as $object) {
       if ($object != "." && $object != "..") {
         if (filetype($dir . "/" . $object) == "dir") $this->rrmdir($dir."/".$object); else unlink($dir."/".$object);
       }
     }
     reset($objects);
     rmdir($dir);
   }
 }

    function testPerformScanning() {

        $correct_result = file_get_contents('test_cases/AjaxScanner.xml');      

        //POST requests:
        //a=prep&s=.
        //a=scan&s=/home/www/badcode.tk/web_root/images%0A/home/www/badcode.tk/web_root/includes%0A/home/www/badcode.tk/web_root/bin%0A/home/www/badcode.tk/web_root/media%0A/home/www/badcode.tk/web_root/libraries%0A/home/www/badcode.tk/web_root/cli%0A/home/www/badcode.tk/web_root/layouts%0A/home/www/badcode.tk/web_root/language%0A/home/www/badcode.tk/web_root/plugins%0A/home/www/badcode.tk/web_root/templates%0A/home/www/badcode.tk/web_root/tmp%0A/home/www/badcode.tk/web_root/php_antimalware_tool%0A/home/www/badcode.tk/web_root/modules%0A/home/www/badcode.tk/web_root/administrator%0A/home/www/badcode.tk/web_root/logs%0A/home/www/badcode.tk/web_root/fake_navigator%0A/home/www/badcode.tk/web_root/cache%0A/home/www/badcode.tk/web_root/components
        //...
        //a=scan&s=/home/www/badcode.tk/web_root/components/com_users/views%0A/home/www/badcode.tk/web_root/components/com_users/controllers%0A/home/www/badcode.tk/web_root/components/com_users/helpers%0A/home/www/badcode.tk/web_root/components/com_users/models%0A/home/www/badcode.tk/web_root/components/com_wrapper%0A/home/www/badcode.tk/web_root/components/com_ajax%0A/home/www/badcode.tk/web_root/components/com_banners%0A/home/www/badcode.tk/web_root/components/com_content%0A/home/www/badcode.tk/web_root/components/com_media%0A/home/www/badcode.tk/web_root/components/com_newsfeeds
        //a=finalize&s=
        
        if (is_dir('test_dir1')) {
            $this->rrmdir('test_dir1');
        }
        
        mkdir('test_dir1');
        mkdir('test_dir1/test_dir2');
        file_put_contents('test_dir1/test_file1.php', 'asdasdas');
        file_put_contents('test_dir1/test_dir2/test_file2.php', 'as2dasdas');
        if (is_file('ajax_scnnr_tbnj.tmp')) {
            unlink('ajax_scnnr_tbnj.tmp');
        }
        
        ob_start();         
        
        $_POST['s'] = dirname(__FILE__) . '/test_dir1';
        $this->scanner->setUp();
	    $result = $this->scanner->performScanning();                 
        $actual_result = $this->scanner->getXMLFilelist();
        $this->scanner->cleanUp();

        $stdout = ob_get_clean(); 

        $this->assertEquals($result, "DIRS" . PHP_EOL . "3" . PHP_EOL);
 
        $tmp_xml_doc = new DOMDocument();
        $tmp_xml_doc->loadXML('<files>' . $actual_result . '</files>');
          
        foreach ($tmp_xml_doc->getElementsByTagName('mtime') as $mtime_node) {            
            $file_node = $mtime_node->parentNode; 
            $ctime_node = $mtime_node->previousSibling->previousSibling; 
            $mtime = (int)$mtime_node->nodeValue;
            $ctime = (int)$ctime_node->nodeValue;

            $this->assertTrue($ctime <= $mtime);
            $this->assertTrue($mtime > 1384340049 && $mtime < 1484341149);
            $this->assertTrue($ctime > 1384320049 && $ctime < 1484341149);
            
            $mtime_node->nodeValue = '0';
            $ctime_node->nodeValue = '0';

        }

        $actual_result = $tmp_xml_doc->saveXML();
        
        $this->rrmdir('test_dir1');

        $this->assertEquals($correct_result, $actual_result); 
        
    }
  
}

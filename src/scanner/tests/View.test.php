<?php

require_once('config.php');
$classname_filename = str_replace('test', 'inc', basename(__FILE__));
require_once($project_classes_dir . '/' . $classname_filename);

class ViewTest extends PHPUnit_Framework_TestCase {


    public function testView() {
       global $project_root_dir;
       $view = new View();
       $view->setTemplateDirAbsolutePath($project_root_dir . '/tests/test_cases/');
       $view->set("title", "File Scanner");
       $view->set("path_to_xml", '/somepath/somexml.xml');


       ob_clean();
       ob_start();
       $view->display("Test.tpl");
       $stdout = ob_get_clean(); 

       $corrrect_html = file_get_contents('test_cases/View.scanner.html'); 

       $this->assertEquals(strlen($stdout), 3512);

    }

}

?>
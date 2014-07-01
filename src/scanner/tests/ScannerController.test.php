<?php

require_once('config.php');

$classname_filename = str_replace('test', 'inc', basename(__FILE__));
require_once($project_classes_dir . '/' . $classname_filename);

class ScannerControllerTest extends PHPUnit_Framework_TestCase {

    public function testFirstRun() {

       $_SERVER['REQUEST_URI'] = "";
       
       global $project_root_dir;
       $password_file = $project_root_dir . '/config.php';
       if (is_file($password_file)) 
       {
           rename($password_file, $password_file . '_'); 
       } 

       ob_start();
       $scannerController = new ScannerController();
       $scannerController->start();
       $stdout = ob_get_clean(); 

       $this->assertTrue((bool)strpos($stdout, 'password'), 'Password form was not found!');

       if (is_file($password_file . '_')) 
       {
           rename($password_file . '_', $password_file); 
       } 

       
    }

    public function testUsualRun() {

       global $project_root_dir;
       $password_file = $project_root_dir . '/config.php';
       if (!is_file($password_file)) {
           file_put_contents($password_file, '<?php $password_hash = \'7dda5591172f219097d70c215a2e5b1e5238fe491a12dee7200876e5bcb8d6ac\';');
       }
       $_COOKIE['antimalware_password_hash'] = '7dda5591172f219097d70c215a2e5b1e5238fe491a12dee7200876e5bcb8d6ac';
       ob_start();
       $_SERVER['REQUEST_URI'] = "";
       
       $scannerController = new ScannerController();
       $scannerController->start();
       $stdout = ob_get_clean(); 

       
       $this->assertTrue((bool)strpos($stdout, 'Start Scanning'), 'Start scan button was not found!');
    }

}

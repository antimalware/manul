<?php

require_once('config.php');

$classname_filename = str_replace('test', 'inc', basename(__FILE__));
require_once($project_classes_dir . '/' . $classname_filename);

class ExecutorControllerTest extends PHPUnit_Framework_TestCase {

    function createConfig() {
        global $project_root_dir;
        $password_file = $project_root_dir . '/config.php';
        if (!is_file($password_file)) {
            file_put_contents($password_file, '<?php $password_hash = \'7dda5591172f219097d70c215a2e5b1e5238fe491a12dee7200876e5bcb8d6ac\';');
        }
    }

    function deleteConfig() {
        global $project_root_dir;
        $password_file = $project_root_dir . '/config.php';
        if (is_file($password_file)) {
            unlink($password_file);
        }
    }

    public function testFirstRun() {
       
       global $project_root_dir;
       $password_file = $project_root_dir . '/config.php';

       ob_start();
       $_SERVER['REQUEST_URI'] = "";

       $executorController = new ExecutorController();
       $executorController->start();
       $stdout = ob_get_clean(); 

       $this->assertTrue((bool)strpos($stdout, 'password'), 'Password form was not found!');
       
    }
    
    public function t2estAuthForm() {

       $this->createConfig();
        
       ob_start();
       $_SERVER['REQUEST_URI'] = "";

       $executorController = new ExecutorController();
       $executorController->start();
       $stdout = ob_get_clean(); 

       $this->assertTrue((bool)strpos($stdout, 'form-signin'), "Auth form was not found");

       $this->deleteConfig();
    }
                                 
    public function testUsualRun() {

       $this->createConfig();

       $_COOKIE["antimalware_password_hash"] = '7dda5591172f219097d70c215a2e5b1e5238fe491a12dee7200876e5bcb8d6ac';

       ob_start();
       $_SERVER['REQUEST_URI'] = "";

       $executorController = new ExecutorController();

       $executorController->start();

       $stdout = ob_get_clean(); 

       $this->assertTrue((bool)strpos($stdout, 'Execute'), 'Execute button was not found!');

       $this->deleteConfig();
    } 

}

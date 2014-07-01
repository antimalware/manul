<?php

ob_start();

require_once('config.php');

$classname_filename = str_replace('test', 'inc', basename(__FILE__));
require_once($project_classes_dir . '/' . $classname_filename);

class InitializerTest extends PHPUnit_Framework_TestCase {
    public function __construct() {
        $this->initializer = new Initializer();
    }
    
	public function testInitialize() {  
        ob_end_clean();
        $this->initializer->config_path = 'config.tmp.php'; 
        $this->initializer->setExecutorPassword('aaaaaaA@');                             
        $_POST['executor_password'] = 'aaaaaaA@';
        $this->assertTrue($this->initializer->initialize());
        $_POST['executor_password'] = 'aaaaaA@';
        $this->assertEquals($this->initializer->initialize(), 'Error: Password is too short.');
        $_POST['executor_password'] = 'aaaaaaa@';
        $this->assertEquals($this->initializer->initialize(), "Error: Password doesn't contain at least three groups of symbols: uppercase, lowercase, digits, special.");
         
        unlink($this->initializer->config_path);
        $this->assertFalse($this->initializer->isInitialized());
 
	}

}

?>

<?php

ob_start();

require_once('config.php');

$classname_filename = str_replace('test', 'inc', basename(__FILE__));
require_once($project_classes_dir . '/' . $classname_filename);

class AuthTest extends PHPUnit_Framework_TestCase {
    public function __construct() {
        $_SERVER['REQUEST_URI'] = "";
        $this->authenticator = new Auth();
    }
    
	public function testInitialize() {  
        
        
        $this->authenticator->configPath = 'config.tmp.php'; 
        $this->authenticator->setPassword('aaaaaaA@');                             
        $_POST['password'] = 'aaaaaaA@';
        $this->assertTrue($this->authenticator->auth());
        $_POST['password'] = 'aaaaaA@';
        #'Error: Password is too short.'
        $this->assertEquals($this->authenticator->auth(), false);
        $_POST['password'] = 'aaaaaaa@';
        #, "Error: Password doesn't contain at least three groups of symbols: uppercase, lowercase, digits, special."
        $this->assertEquals($this->authenticator->auth(), false);

         
        unlink($this->authenticator->configPath);
        $this->assertFalse($this->authenticator->auth());
        
        ob_end_clean();
	}

}

?>

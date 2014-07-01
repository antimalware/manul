<?php

require_once('config.php');
$classname_filename = str_replace('test', 'inc', basename(__FILE__));
require_once($project_classes_dir . '/' . $classname_filename);

    class HealerTest extends PHPUnit_Framework_TestCase {
    public function __construct() {
        global $project_root_dir; 
        $_SERVER['DOCUMENT_ROOT'] = dirname($project_root_dir);

        $this->healer = new Healer();
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

    public function testExecuteXmlRecipe() {

       
        if (is_dir('test_dir1')) {
            $this->rrmdir('test_dir1');
        }

        mkdir('test_dir1');
        mkdir('test_dir1/test_dir2');
        file_put_contents('test_dir1/test_file1.php', 'asdasdas');
        file_put_contents('test_dir1/test_dir2/test_file2.php', 'as2dasdas');

        $recipe = file_get_contents('test_cases/Healer.Recipe.xml');
        
        $result = $this->healer->executeXmlRecipe($recipe);

        $quarantine_filename = $this->healer->getQuarantineFilename();
     
        //Test successful removal
        $this->assertFalse(is_file('test_dir1/test_file1.php'));
        $this->assertFalse(is_file('test_dir1/test_dir2/test_file2.php'));
   
        //Test correct quarantine archive 
        $this->assertTrue(is_file($quarantine_filename));

        $quarantine_size = filesize($quarantine_filename);
        $this->assertTrue($quarantine_size > 600 && $quarantine_size < 725); 

        $this->rrmdir('test_dir1');

        unlink($quarantine_filename);
    }
}

?>


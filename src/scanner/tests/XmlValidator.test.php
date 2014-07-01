<?php

require_once('config.php');
$classname_filename = str_replace('test', 'inc', basename(__FILE__));
require_once($project_classes_dir . '/' . $classname_filename);

class XmlValidatorTest extends PHPUnit_Framework_TestCase {
    
    public function testValidate() {
        global $project_root_dir;

        $xml_str = file_get_contents('test_cases/Healer.Recipe.xml');
        $schema_path = $project_root_dir . '/static/xsd/recipe.xsd';
        
        $validator = new XmlValidator();

        $result = $validator->validate($xml_str, $schema_path);
        
        $this->assertTrue($result);             
    }
}

?>
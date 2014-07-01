<?php

ob_start();
require_once("Localization.inc.php");
require_once("FileInfo.inc.php");
require_once("Healer.inc.php");
require_once("Template.inc.php");
require_once("View.inc.php");
require_once("XmlValidator.inc.php");
require_once("Auth.inc.php");
ob_end_clean();

session_start();

class ExecutorController {

	function startExecutor() {

                $view = new View();
                $healer = new Healer();

                //////////////////////////////////////////////////////////////////////////////////////////
		if (!empty($_POST) && !empty($_POST["recipe"])) 
		{
               	    $xml_recipe = $_POST["recipe"];
               	    $validator = new XmlValidator();
                    global $project_root_dir;

                    if(get_magic_quotes_gpc()) $xml_recipe = stripslashes($xml_recipe);

               	    if (!$validator->validate($xml_recipe, $project_root_dir . "/static/xsd/recipe.xsd")) {
               		die(PS_ERR_BROKEN_XML_FILE);
               	    }

                    $execute_list = '';
                    $itemTemplate = new Template("executor_item.tpl");

                    $quarantine_files = array();
                    $delete_files = array();
                    $healer->prepareList($xml_recipe, $quarantine_files, $delete_files);
    		    for ($i = 0; $i < count($delete_files); $i++) {
                        $itemTemplate->prepare();
                        $itemTemplate->set('PREFIX', 'd');
                        $itemTemplate->set('NUM', $i);
                        $itemTemplate->set('ACTION', PS_RECIPE_ACTION_DEL);
                        $itemTemplate->set('FILENAME', $this->getShortFilename($delete_files[$i]));
                        $itemTemplate->set('FILENAME_B64', base64_encode($delete_files[$i]));
                        $execute_list .= $itemTemplate->get();

                    }    

    		    for ($i = 0; $i < count($quarantine_files); $i++) {
                        $itemTemplate->prepare();
                        $itemTemplate->set('PREFIX', 'q');
                        $itemTemplate->set('NUM', $i);
                        $itemTemplate->set('ACTION', PS_RECIPE_ACTION_QUARANTINE);
                        $itemTemplate->set('FILENAME', $this->getShortFilename($quarantine_files[$i]));
                        $itemTemplate->set('FILENAME_B64', base64_encode($quarantine_files[$i]));
                        $execute_list .= $itemTemplate->get();
                    }

    		    define('PS_EXECUTE_LIST', $execute_list);
    		    define('PS_EXECUTE_TOTAL_D', count($delete_files));
    		    define('PS_EXECUTE_TOTAL_Q', count($quarantine_files));

    		    $view->display("executor_changes.tpl");
                } 
                 //////////////////////////////////////////////////////////////////////////////////////////
                 else if (isset($_POST["a"]) && ($_POST["a"] == 'apply')) {
                      $delete_total = (int)$_POST['total_d'];
                      $quarantine_total = (int)$_POST['total_q'];

                      $delete_files = array();
                      $quarantine_files = array();

                      for ($i = 0; $i < $delete_total; $i++) {
                        if ($_POST["d_" . $i] == 'on') {
                           $delete_files[] = base64_decode($_POST["fn_d_" . $i]);
                        }
                      }

                      for ($i = 0; $i < $quarantine_total; $i++) {
                        if ($_POST["q_" . $i] == 'on') {
                           $quarantine_files[] = base64_decode($_POST["fn_q_" . $i]);
                        }
                      }

                      $num_quarantined = 0;
                      define('PS_EXECUTOR_LOG', $healer->executeXmlRecipe($delete_files, $quarantine_files, $num_quarantined));

                      if (is_file($_SESSION['quarantine_file']) && $num_quarantined) {
                         $quarantine_filename = $_SERVER['PHP_SELF'] . '?controller=download&f=quarantine';
                      } else {
                         $quarantine_filename = "";
                      }

                      define('PS_QUARANTINE_URL', $quarantine_filename);

	              $view->display("executor_done.tpl");
                 } else if (isset($_REQUEST["a"]) && ($_REQUEST["a"] == 'selfDelete')) {

                     global $project_root_dir, $project_tmp_dir;                     
                     if ($project_tmp_dir == sys_get_temp_dir()) {
                         @unlink($project_tmp_dir . '/scan_log.xml');
                         array_map('unlink', glob($project_tmp_dir . "/*.pat.tmp.txt"));
                         array_map('unlink', glob($project_tmp_dir . "/*.pat.tmp"));        
                         array_map('unlink', glob($project_tmp_dir . "/config.php"));        
                     }

                     $deleteResult = $healer->deleteDir($project_root_dir);

                     if ($deleteResult) {
                         print(json_encode(array("result" => "ok")));
                     } else {
                         print(json_encode(array("result" => "error", "details" => $deleteResult)));
                     }
                 }
                 //////////////////////////////////////////////////////////////////////////////////////////
		 else {
                      $view->display("executor.tpl");
		 }

	}

	function start() {
		$authenticator = new Auth();

		if ($authenticator->auth()) {
			$this->startExecutor();
		}
	}

	function getShortFileName($in_name) {
	   define('MAX_PRINTABLE_FILENAME_LEN', 70);
	   $out_name = $in_name;

	   if (strlen($out_name) > MAX_PRINTABLE_FILENAME_LEN) {
              $out_name = substr($out_name, 0, MAX_PRINTABLE_FILENAME_LEN / 2) . 
                          '...' . 
                          substr($out_name, strlen($out_name) - MAX_PRINTABLE_FILENAME_LEN, MAX_PRINTABLE_FILENAME_LEN / 2);
           }
  
	   return $out_name;

        }

}                       
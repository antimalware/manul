<?php
require_once('Localization.inc.php');
require_once("View.inc.php");

ini_set('track_errors', 1); 
error_reporting(0);

$critical_err = false;
$php_version_ok = true;
$zip_ok = true;
$dom_ok = true;
$perm_ok = true;
$php_root_readable = true;

if (!is_readable($_SERVER['DOCUMENT_ROOT'])) {
    $php_root_readable = false;
    $critical_err = true;
}
     
if (version_compare(phpversion(), '5.2.0', '<')) {
    $php_version_ok = false;
    $critical_err = true;
}
     
$zipModuleVersion = phpversion('zip');
if (empty($zipModuleVersion)) {
    $zip_ok = false;
    $critical_err = true;
} 

$domDocumentVersion = phpversion('dom');
if (empty($domDocumentVersion)) {
    $dom_ok = false;
    $critical_err = true;
} 

$project_root_dir =  dirname(dirname(__FILE__));

$project_tmp_dir = $project_root_dir . '/tmp';
if (!is_writable($project_tmp_dir)) {
   try {
       @chmod($project_tmp_dir, 0770);
   } catch (Exception $e) {
   }
   if (!is_writable($project_tmp_dir)) {
      $project_tmp_dir = $project_root_dir;
      if (!is_writable($project_tmp_dir)) {
          $project_tmp_dir = sys_get_temp_dir();
          if (!is_writable($project_tmp_dir)) {
    		$perm_ok = false;
		$critical_err = true;
          }
      }
   }
}

if ($critical_err)
 {
   $view = new View();
   define('PS_CHECKER_ROOT_READABLE_STATUS', $php_root_readable ? PS_CHECKER_PASSED : PS_CHECKER_FAILED);
   define('PS_CHECKER_PHPVERSION_STATUS', $php_version_ok ? PS_CHECKER_PASSED : PS_CHECKER_FAILED);
   define('PS_CHECKER_ZIP_STATUS', $zip_ok ? PS_CHECKER_PASSED : PS_CHECKER_FAILED);
   define('PS_CHECKER_DOM_STATUS', $dom_ok ? PS_CHECKER_PASSED : PS_CHECKER_FAILED);
   define('PS_CHECKER_PERM_STATUS', $perm_ok ? PS_CHECKER_PASSED : PS_CHECKER_FAILED);

   define('PS_CHECKER_ROOT_READABLE_STYLE', $php_root_readable ? 'label_green' : 'label_red');
   define('PS_CHECKER_PHPVERSION_STYLE', $php_version_ok ? 'label_green' : 'label_red');
   define('PS_CHECKER_ZIP_STYLE', $zip_ok ? 'label_green' : 'label_red');
   define('PS_CHECKER_DOM_STYLE', $dom_ok ? 'label_green' : 'label_red');
   define('PS_CHECKER_PERM_STYLE', $perm_ok ? 'label_green' : 'label_red');

   define('PS_CHECKER_ROOT_READABLE_FIX', $php_root_readable ? 'none' : 'block');
   define('PS_CHECKER_PHPVERSION_FIX', $php_version_ok ? 'none' : 'block');
   define('PS_CHECKER_ZIP_FIX', $zip_ok ? 'none' : 'block');
   define('PS_CHECKER_DOM_FIX', $dom_ok ? 'none' : 'block');
   define('PS_CHECKER_PERM_FIX', $perm_ok ? 'none' : 'block');

   define('PS_CHECKER_ROOT_READABLE_LINK', $lang_domain . '/manul/index.xml');
   define('PS_CHECKER_PHPVERSION_LINK', $lang_domain . '/manul/index.xml');
   define('PS_CHECKER_ZIP_LINK', $lang_domain . '/manul/index.xml');
   define('PS_CHECKER_DOM_LINK', $lang_domain . '/manul/index.xml');
   define('PS_CHECKER_PERM_LINK', $lang_domain . '/manul/index.xml');

   $view->display("start_check.tpl");
   die();
}


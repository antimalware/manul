<?php

ob_start();
require_once('Localization.inc.php');
require_once('View.inc.php');
ob_end_clean();

ini_set('track_errors', 1);
error_reporting(0);

$isCriticalErr = false;
$isPhpVersionOk = true;
$isZipOk = true;
$isDomOk = true;
$isPermOk = true;
$isPhpRootReadable = true;

if (!is_readable($_SERVER['DOCUMENT_ROOT'])) {
    $isPhpRootReadable = false;
    $isCriticalErr = true;
}

if (version_compare(phpversion(), '5.2.0', '<')) {
    $isPhpVersionOk = false;
    $isCriticalErr = true;
}

$zipModuleVersion = phpversion('zip');
if (empty($zipModuleVersion)) {
    $isZipOk = false;
    $isCriticalErr = true;
}

$domDocumentVersion = phpversion('dom');
if (empty($domDocumentVersion)) {
    $isDomOk = false;
    $isCriticalErr = true;
}

$projectRootDir = dirname(dirname(__FILE__));

$projectTmpDir = $projectRootDir . '/tmp';
if (!is_writable($projectTmpDir)) {
    try {
        @chmod($projectTmpDir, 0770);
    } catch (Exception $e) {
    }
    if (!is_writable($projectTmpDir)) {
        $projectTmpDir = $projectRootDir;
        if (!is_writable($projectTmpDir)) {
            $projectTmpDir = sys_get_temp_dir();
            if (!is_writable($projectTmpDir)) {
                $isPermOk = false;
                $isCriticalErr = true;
            }
        }
    }
}

if ($isCriticalErr) {
    $view = new View();
    define('PS_CHECKER_ROOT_READABLE_STATUS', $isPhpRootReadable ? PS_CHECKER_PASSED : PS_CHECKER_FAILED);
    define('PS_CHECKER_PHPVERSION_STATUS', $isPhpVersionOk ? PS_CHECKER_PASSED : PS_CHECKER_FAILED);
    define('PS_CHECKER_ZIP_STATUS', $isZipOk ? PS_CHECKER_PASSED : PS_CHECKER_FAILED);
    define('PS_CHECKER_DOM_STATUS', $isDomOk ? PS_CHECKER_PASSED : PS_CHECKER_FAILED);
    define('PS_CHECKER_PERM_STATUS', $isPermOk ? PS_CHECKER_PASSED : PS_CHECKER_FAILED);

    define('PS_CHECKER_ROOT_READABLE_STYLE', $isPhpRootReadable ? 'label_green' : 'label_red');
    define('PS_CHECKER_PHPVERSION_STYLE', $isPhpVersionOk ? 'label_green' : 'label_red');
    define('PS_CHECKER_ZIP_STYLE', $isZipOk ? 'label_green' : 'label_red');
    define('PS_CHECKER_DOM_STYLE', $isDomOk ? 'label_green' : 'label_red');
    define('PS_CHECKER_PERM_STYLE', $isPermOk ? 'label_green' : 'label_red');

    define('PS_CHECKER_ROOT_READABLE_FIX', $isPhpRootReadable ? 'none' : 'block');
    define('PS_CHECKER_PHPVERSION_FIX', $isPhpVersionOk ? 'none' : 'block');
    define('PS_CHECKER_ZIP_FIX', $isZipOk ? 'none' : 'block');
    define('PS_CHECKER_DOM_FIX', $isDomOk ? 'none' : 'block');
    define('PS_CHECKER_PERM_FIX', $isPermOk ? 'none' : 'block');

    define('PS_CHECKER_ROOT_READABLE_LINK', $langDomain . '/manul/index.xml');
    define('PS_CHECKER_PHPVERSION_LINK', $langDomain . '/manul/index.xml');
    define('PS_CHECKER_ZIP_LINK', $langDomain . '/manul/index.xml');
    define('PS_CHECKER_DOM_LINK', $langDomain . '/manul/index.xml');
    define('PS_CHECKER_PERM_LINK', $langDomain . '/manul/index.xml');

    $view->display('start_check.tpl');
    die();
}

function escapedHexToHex($escaped)
{
    return chr(hexdec($escaped[1]));
}

function escapedOctDec($escaped)
{
    return chr(octdec($escaped[1]));
}


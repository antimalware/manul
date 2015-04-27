<?php
#Error handling, general checks and global variables
require_once('classes/Initialization.inc.php');

$controllerName = empty($_REQUEST['controller']) ? 'scanner' : $_REQUEST['controller'];
$controller = null;

if ($controllerName === 'scanner') {
    require_once('classes/ScannerController.inc.php');
    $controller = new ScannerController();
} elseif ($controllerName === 'executor') {
    require_once('classes/ExecutorController.inc.php');
    $controller = new ExecutorController();
} elseif ($controllerName === 'download') {
    require_once('classes/DownloadController.inc.php');
    $controller = new DownloadController();
}

if ($controller) {
    $controller->start();
}

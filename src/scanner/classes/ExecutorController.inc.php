<?php

ob_start();
require_once('Localization.inc.php');
require_once('FileInfo.inc.php');
require_once('Healer.inc.php');
require_once('Template.inc.php');
require_once('View.inc.php');
require_once('XmlValidator.inc.php');
require_once('Auth.inc.php');
ob_end_clean();

class ExecutorController
{

    private function startExecutor()
    {
        $view = new View();
        $healer = new Healer();

        if (!empty($_POST) && !empty($_POST['recipe'])) {
            $xmlRecipe = $_POST['recipe'];
            $validator = new XmlValidator();
            global $projectRootDir;

            if (get_magic_quotes_gpc()) $xmlRecipe = stripslashes($xmlRecipe);

            //TODO: implement proper XXE prevention or switch to JSON instead
            if (strpos(strtoupper($xmlRecipe), '<!ENTITY') !== false) {
                die('XXE detected');
            }

            if (!$validator->validate($xmlRecipe, $projectRootDir . '/static/xsd/recipe.xsd')) {
                die(PS_ERR_BROKEN_XML_FILE);
            }

            $executeList = '';
            $itemTemplate = new Template('executor_item.tpl');

            $quarantineFiles = array();
            $deleteFiles = array();
            $healer->prepareList($xmlRecipe, $quarantineFiles, $deleteFiles);
            for ($i = 0; $i < count($deleteFiles); $i++) {
                $itemTemplate->prepare();
                $itemTemplate->set('PREFIX', 'd');
                $itemTemplate->set('NUM', $i);
                $itemTemplate->set('ACTION', PS_RECIPE_ACTION_DEL);
                $itemTemplate->set('FILENAME', $this->getShortFilename($deleteFiles[$i]));
                $itemTemplate->set('FILENAME_B64', base64_encode($deleteFiles[$i]));
                $executeList .= $itemTemplate->get();
            }

            for ($i = 0; $i < count($quarantineFiles); $i++) {
                $itemTemplate->prepare();
                $itemTemplate->set('PREFIX', 'q');
                $itemTemplate->set('NUM', $i);
                $itemTemplate->set('ACTION', PS_RECIPE_ACTION_QUARANTINE);
                $itemTemplate->set('FILENAME', $this->getShortFilename($quarantineFiles[$i]));
                $itemTemplate->set('FILENAME_B64', base64_encode($quarantineFiles[$i]));
                $executeList .= $itemTemplate->get();
            }

            define('PS_EXECUTE_LIST', $executeList);
            define('PS_EXECUTE_TOTAL_D', count($deleteFiles));
            define('PS_EXECUTE_TOTAL_Q', count($quarantineFiles));

            $view->display('executor_changes.tpl');
        } else if (isset($_POST['a']) && ($_POST['a'] === 'apply')) {
            $deleteTotal = (int)$_POST['total_d'];
            $quarantineTotal = (int)$_POST['total_q'];

            $deleteFiles = array();
            $quarantineFiles = array();

            for ($i = 0; $i < $deleteTotal; $i++) {
                if (!empty($_POST['d_' . $i]) && $_POST['d_' . $i] === 'on') {
                    $deleteFiles[] = base64_decode($_POST['fn_d_' . $i]);
                }
            }

            for ($i = 0; $i < $quarantineTotal; $i++) {
                if (!empty($_POST['q_' . $i]) && $_POST['q_' . $i] === 'on') {
                    $quarantineFiles[] = base64_decode($_POST['fn_q_' . $i]);
                }
            }

            $numQuarantined = 0;
            define('PS_EXECUTOR_LOG', $healer->executeXmlRecipe($deleteFiles, $quarantineFiles, $numQuarantined));

            $quarantineUrl = $_SERVER['PHP_SELF'] . '?controller=download&f=quarantine';
            define('PS_QUARANTINE_URL', $quarantineUrl);

            $view->display('executor_done.tpl');

        } else if (isset($_REQUEST['a']) && ($_REQUEST['a'] == 'selfDelete')) {

            global $projectRootDir, $projectTmpDir;
            if ($projectTmpDir == sys_get_temp_dir()) {
                @unlink($projectTmpDir . '/scan_log.xml');
                array_map('unlink', glob($projectTmpDir . '/*.manul.tmp.txt'));
                array_map('unlink', glob($projectTmpDir . '/*.manul.tmp'));
                array_map('unlink', glob($projectTmpDir . '/config.php'));
            }

            $deleteResult = $healer->deleteDir($projectRootDir);

            if ($deleteResult) {
                print(json_encode(array('result' => 'ok')));
            } else {
                print(json_encode(array('result' => 'error', 'details' => $deleteResult)));
            }
        } else {
            $view->display('executor.tpl');
        }
    }

    private function getShortFileName($in_name)
    {
        define('MAX_PRINTABLE_FILENAME_LEN', 70);
        $outName = $in_name;

        if (strlen($outName) > MAX_PRINTABLE_FILENAME_LEN) {
            $outName = substr($outName, 0, MAX_PRINTABLE_FILENAME_LEN / 2) .
                '...' .
                substr($outName, strlen($outName) - MAX_PRINTABLE_FILENAME_LEN, MAX_PRINTABLE_FILENAME_LEN / 2);
        }

        return $outName;
    }

    public function start()
    {
        $authenticator = new Auth();
        if ($authenticator->auth()) {
            $this->startExecutor();
        }
    }
}


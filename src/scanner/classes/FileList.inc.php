<?php

ob_start();
require_once('XmlValidator.inc.php');
require_once('FileInfo.inc.php');
require_once('Writer.inc.php');
ob_end_clean();

class FileList
{
    function __construct()
    {
        global $projectTmpDir;

        $this->DIRLIST_TMP_FILENAME = $projectTmpDir . '/dirlist.manul.tmp.txt';
        $this->FILELIST_OFFSET_FILENAME = $projectTmpDir . '/queue_offset.manul.tmp.txt';

        $this->logFilename = $projectTmpDir . '/scan_log.xml';
        $this->AJAX_HEADER_DIRS = 'DIRS';
        $this->AJAX_HEADER_ERROR = 'ERR';
        $this->AJAX_TMP_FILE = $projectTmpDir . '/ajax_scnnr_tbnj.manul.tmp';
        $this->MAX_EXECUTION_DURATION = 5;
        $this->TYPE_ANY = 0;
        $this->TYPE_FOLDER = 1;
        $this->TYPE_FILE = 2;
        $this->ACTION_SKIP = 0;
        $this->ACTION_PROCESS = 1;

        $this->scanSkipPathWildcard = array(); // wildcards to exclude from scanning
        $this->filesFound = 0;
        $this->filesNode = null;
        $this->xmlResult = '';

        $this->SCRIPT_START = time();

        $this->dom = new DOMDocument('1.0', 'utf-8');
        $this->dom->formatOutput = true;
        $this->filesNode = $this->dom->createElement('files');
        $this->dom->appendChild($this->filesNode);
        $this->homedir = dirname(dirname(__FILE__));

        #For creating temprorary queue for further antimalware/whitelist scan
        $this->GENERATE_FILE_QUEUE = true;
        $this->tmpQueueFilename = $projectTmpDir . '/scan_queue.manul.tmp.txt';

    }

    private function throwTimeout()
    {
        echo $this->AJAX_HEADER_ERROR . "\n";
        echo 'File listing timeout. Try to increase an interval in settings.' . "\n";
        exit;
    }

    private function fileExecutor($filePath, $type, $actionType)
    {
        if ($actionType === $this->ACTION_PROCESS) {
            $fileinfo = new FileInfo($filePath);
            $fileinfoNode = $fileinfo->getXMLNode();

            if ($this->GENERATE_FILE_QUEUE && is_file($fileinfo->absoluteName)) {
                $queue_entry = $fileinfo->absoluteName . ' ' . $fileinfo->md5 . PHP_EOL;
                file_put_contents2($this->tmpQueueFilename, $queue_entry, 'a');
            }

            $this->dom->documentElement->appendChild($this->dom->importNode($fileinfoNode, true));
            $this->filesFound++;
        } else if ($actionType === $this->ACTION_SKIP) {
            // TODO: Do something with skipped item
            // fputs($file_handle, "* SKIPPED *************************************** " . $file_path);
        }
    }

    public function getXMLFilelist()
    {
        $result = implode('', file($this->AJAX_TMP_FILE));
        $this->cleanUp();

        return $result;
    }

    private function finalizeRound()
    {

        global $php_errormsg;

        if ($fHandle = fopen($this->AJAX_TMP_FILE, 'a')) {

            $nodeList = $this->filesNode->childNodes;
            $num = $nodeList->length;

            for ($i = 0; $i < $num; $i++) {
                fputs($fHandle, $this->dom->saveXML($nodeList->item($i)));
            }
            fclose($fHandle);

            $response['meta'] = array('type' => 'getFileList', 'status' => 'inProcess', 'phpError' => $php_errormsg);
            $response['data'] = array();
            $report = json_encode($response);
            return $report;

        } else {

            ob_end_clean();
            // output result for ajax processing
            $response['meta'] = array('type' => 'error', 'phpError' => $php_errormsg);
            $response['data'] = array('Cannot write to file ' . $this->logFilename);
            $report = json_encode($response);
            return $report;
        }
    }

    private function cleanUp()
    {
        @unlink($this->DIRLIST_TMP_FILENAME);
        @unlink($this->FILELIST_OFFSET_FILENAME);
        @unlink($this->AJAX_TMP_FILE);
    }

    function setUp()
    {
    }

    public function performScanning()
    {
        global $php_errormsg;

        $dirs = '.';

        if (file_exists($this->DIRLIST_TMP_FILENAME))
            $dirs = file_get_contents($this->DIRLIST_TMP_FILENAME);

        $dirList = explode("\n", $dirs);
        $startTime = time();

        while (true) {
            $dirList = array_merge($this->folderWalker(array_shift($dirList), $this->filesFound), $dirList);
            $currentTime = time();
            if (($currentTime - $startTime >= $this->MAX_EXECUTION_DURATION) || (count($dirList) < 1)) break;
        }

        $result = $this->finalizeRound();


        if (!$this->filesFound) {

            $response['meta'] = array('type' => 'getFileList', 'status' => 'finished', 'phpError' => $php_errormsg);
            $response['data'] = array();
            $report = json_encode($response);
            return $report;
        }

        file_put_contents2($this->DIRLIST_TMP_FILENAME, implode("\n", $dirList));

        return $result;
    }

    public function getInterval()
    {
        return $this->MAX_EXECUTION_DURATION;
    }

    public function setInterval($val)
    {
        $this->MAX_EXECUTION_DURATION = $val;
    }

    private function folderWalker($path, &$files_found)
    {
        if ($path === '.')
            $path = $_SERVER['DOCUMENT_ROOT'];

        $dirList = array();

        if ($currentDir = opendir($path)) {
            while ($file = readdir($currentDir)) {
                if ($file === '.' || $file === '..' || is_link($path) || $file === basename($this->homedir)) continue;
                $name = $file;
                $file = $path . '/' . $file;
                // skip path entries from the list
                foreach ($this->scanSkipPathWildcard as $item) {
                    if (preg_match('|' . $item . '|i', $file, $found)) {
                        foreach ($this->scan_iterator_func as $callback) {
                            $callback($file, TYPE_ANY, ACTION_SKIP);
                        }
                        continue;
                    }
                }
                $fileType = $this->TYPE_FILE;
                if (is_dir($file)) {
                    $dirList[] = $file;
                    $fileType = $this->TYPE_FOLDER;
                }
                $this->fileExecutor($file, $fileType, $this->ACTION_PROCESS);

            }
            closedir($currentDir);
        }

        if (!is_file($this->tmpQueueFilename) && count($dirList) === 0) {
            $response['meta'] = array('type' => 'getFileList', 'status' => 'finished', 'phpError' => PS_ERR_NO_FILES_IN_WEB_ROOT);
            $report = json_encode($response);
            die($report);
        }

        return $dirList;
    }
} // End of class


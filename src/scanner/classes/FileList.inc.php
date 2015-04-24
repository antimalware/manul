<?php

require_once("XmlValidator.inc.php");
require_once("FileInfo.inc.php");
require_once("Writer.inc.php");

class FileList
{
    /** @var string */
    public $DIRLIST_TMP_FILENAME;
    /** @var string */
    public $FILELIST_OFFSET_FILENAME;
    /** @var string */
    public $log_filename;
    /** @var string */
    public $AJAX_HEADER_DIRS;
    /** @var string */
    public $AJAX_HEADER_ERROR;
    /** @var int */
    public $MAX_EXECUTION_DURATION;
    /** @var int */
    public $TYPE_ANY;
    /** @var int */
    public $TYPE_FOLDER;
    /** @var int */
    public $TYPE_FILE;
    /** @var int */
    public $ACTION_SKIP;
    /** @var int */
    public $ACTION_PROCESS;
    /** @var array */
    public $scan_skip_path_wildcard;
    /** @var int */
    public $files_found;
    /** @var DOMElement */
    public $files_node;
    /** @var string */
    public $xmlResult;
    /** @var int */
    public $SCRIPT_START;
    /** @var DOMDocument */
    public $dom;
    /** @var string */
    public $homedir;
    /** @var bool */
    public $GENERATE_FILE_QUEUE;
    /** @var string */
    public $tmp_queue_filename;
    public $scan_iterator_func;

    function __construct()
    {
        global $project_tmp_dir;

        $this->DIRLIST_TMP_FILENAME = $project_tmp_dir . '/dirlist.manul.tmp.txt';
        $this->FILELIST_OFFSET_FILENAME = $project_tmp_dir . '/queue_offset.manul.tmp.txt';

        $this->log_filename = $project_tmp_dir . '/scan_log.xml';
        $this->AJAX_HEADER_DIRS = 'DIRS';
        $this->AJAX_HEADER_ERROR = 'ERR';
        $this->AJAX_TMP_FILE = $project_tmp_dir . '/ajax_scnnr_tbnj.manul.tmp';
        $this->MAX_EXECUTION_DURATION = 5;
        $this->TYPE_ANY = 0;
        $this->TYPE_FOLDER = 1;
        $this->TYPE_FILE = 2;
        $this->ACTION_SKIP = 0;
        $this->ACTION_PROCESS = 1;

        $this->scan_skip_path_wildcard = array(); // wildcards to exclude from scanning
        $this->files_found = 0;
        $this->files_node = null;
        $this->xmlResult = '';

        $this->SCRIPT_START = time();

        $this->dom = new DOMDocument("1.0", "utf-8");
        $this->dom->formatOutput = true;
        $this->files_node = $this->dom->createElement("files");
        $this->dom->appendChild($this->files_node);
        $this->homedir = dirname(dirname(__FILE__));

        #For creating temprorary queue for further antimalware/whitelist scan
        $this->GENERATE_FILE_QUEUE = true;
        $this->tmp_queue_filename = $project_tmp_dir . '/scan_queue.manul.tmp.txt';

    }

    function throwTimeout()
    {
        echo $this->AJAX_HEADER_ERROR . "\n";
        echo "File listing timeout. Try to increase an interval in settings.\n";

        exit;
    }

    function fileExecutor($file_path, $type, $action_type)
    {

        if ($action_type == $this->ACTION_PROCESS) {
            $fileinfo = new FileInfo($file_path);
            $fileinfo_node = $fileinfo->getXMLNode();

            if ($this->GENERATE_FILE_QUEUE && is_file($fileinfo->absolute_name)) {
                $queue_entry = $fileinfo->absolute_name . ' ' . $fileinfo->md5 . PHP_EOL;
                file_put_contents2($this->tmp_queue_filename, $queue_entry, 'a');
            }

            $this->dom->documentElement->appendChild($this->dom->importNode($fileinfo_node, true));
            $this->files_found++;
        } else if ($action_type == $this->ACTION_SKIP) {
            // TODO: Do something with skipped item
            // fputs($file_handle, "* SKIPPED *************************************** " . $file_path);
        }
    }

    function getXMLFilelist()
    {
        $result = implode('', file($this->AJAX_TMP_FILE));
        $this->cleanUp();

        return $result;
    }

    function finalizeRound()
    {

        global $php_errormsg;

        if ($fHandle = fopen($this->AJAX_TMP_FILE, "a")) {

            $nodeList = $this->files_node->childNodes;
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
            $response['data'] = array("Cannot write to file " . $this->log_filename);
            $report = json_encode($response);
            return $report;
        }

    }

    function cleanUp()
    {
        @unlink($this->DIRLIST_TMP_FILENAME);
        @unlink($this->FILELIST_OFFSET_FILENAME);
        @unlink($this->AJAX_TMP_FILE);
    }

    function setUp()
    {
    }

    function performScanning()
    {
        global $php_errormsg;

        $dirs = ".";

        if (file_exists($this->DIRLIST_TMP_FILENAME))
            $dirs = file_get_contents($this->DIRLIST_TMP_FILENAME);

        $dir_list = explode("\n", $dirs);
        $time_of_start = time();

        while (true) {
            $dir_list = array_merge($this->folderWalker(array_shift($dir_list), $this->files_found), $dir_list);
            $current_time = time();
            if (($current_time - $time_of_start >= $this->MAX_EXECUTION_DURATION) || (count($dir_list) < 1)) {
                break;
            }
        }

        $result = $this->finalizeRound();

        if (!$this->files_found) {
            $response['meta'] = array('type' => 'getFileList', 'status' => 'finished', 'phpError' => $php_errormsg);
            $response['data'] = array();
            $report = json_encode($response);
            return $report;
        }

        file_put_contents2($this->DIRLIST_TMP_FILENAME, implode("\n", $dir_list));

        return $result;
    }

    function getInterval()
    {
        return $this->MAX_EXECUTION_DURATION;
    }

    function setInterval($val)
    {
        $this->MAX_EXECUTION_DURATION = $val;
    }

    function folderWalker($path, &$files_found)
    {
        if ($path === ".") {
            $path = $_SERVER['DOCUMENT_ROOT'];
        }

        $dir_list = array();

        if ($current_dir = opendir($path)) {
            while ($file = readdir($current_dir)) {
                if ($file == '.' || $file == '..' || is_link($path) || $file == basename($this->homedir)) {
                    continue;
                }
                $name = $file;
                $file = $path . '/' . $file;
                // skip path entries from the list
                foreach ($this->scan_skip_path_wildcard as $item) {
                    if (preg_match('|' . $item . '|i', $file, $found)) {
                        foreach ($this->scan_iterator_func as $callback) {
                            $callback($file, $this->TYPE_ANY, $this->ACTION_SKIP);
                        }
                        continue;
                    }
                }
                $file_type = $this->TYPE_FILE;
                if (is_dir($file)) {
                    $dir_list[] = $file;
                    $file_type = $this->TYPE_FOLDER;
                }
                $this->fileExecutor($file, $file_type, $this->ACTION_PROCESS);

            }
            closedir($current_dir);
        }

        if (!is_file($this->tmp_queue_filename) && count($dir_list) == 0) {
            $response['meta'] = array(
                'type' => 'getFileList',
                'status' => 'finished',
                'phpError' => PS_ERR_NO_FILES_IN_WEB_ROOT,
            );
            $report = json_encode($response);
            die($report);
        }

        return $dir_list;
    }
}
<?php

require_once("Archiver.inc.php");
require_once("FileInfo.inc.php");

class Healer
{
    /** @var string */
    public $quarantine_filename;
    /** @var string */
    public $backup_filename;
    /** @var string */
    public $log;
    /** @var string */
    public $web_root_dir;
    /** @var Archiver */
    public $archiver;

    function __construct()
    {
        global $project_tmp_dir;

        $time_string = date("Y_m_d_H_i", $_SERVER["REQUEST_TIME"]);
        $this->quarantine_filename = $project_tmp_dir . '/' . "quarantine." . $time_string . ".zip";
        $this->backup_filename = $project_tmp_dir . '/' . "manul_deleted_files_backup." . $time_string . ".zip";

        $this->log = "";

        $this->web_root_dir = $_SERVER['DOCUMENT_ROOT'];

        if (file_exists($this->quarantine_filename) && (!isset($_COOKIE['quarantine_file']))) {
            $this->log .= sprintf(PS_DELETE_ARCHIVE, $this->quarantine_filename);
            unlink($this->quarantine_filename);
        }

        $this->archiver = null;
    }

    function getQuarantineFilename()
    {
        return $this->quarantine_filename;
    }

    function parseXmlRecipe($xml_recipe)
    {
        $dom = null;
        try {
            $dom = new DOMDocument("1.0", "utf-8");
            $dom->formatOutput = true;
            $dom->loadXML($xml_recipe);
        } catch (Exception $e) {
            die(sprintf(PS_ERR_EXCEPTION_OCCURED, $e->getMessage()));
        }
        if (!$dom) {
            die(sprintf(PS_ERR_EXCEPTION_OCCURED, ''));
        }

        return $dom;
    }

    function quarantineFile($filename)
    {
        if (!is_file($filename)) {
            $this->log .= '<div class="err">' . sprintf(PS_ERR_QUARANTINE_NOT_EXISTS, $filename) . '</div>';
            return false;
        }

        $fileinfo = new FileInfo($filename);
        $file_hash = $fileinfo->md5;

        $this->archiver->addFile($filename, $file_hash);
        $meta_filename = $file_hash . ".meta";

        $this->archiver->createFile($meta_filename, (string)$fileinfo);

        return true;
    }

    function deleteFile($filename)
    {
        if (!is_file($filename)) {
            $this->log .= '<div class="err">' . sprintf(PS_ERR_DELETE_NOT_EXISTS, $filename) . '</div>';
            return false;
        }

        return unlink($filename);
    }

    function deleteDir($dirname)
    {

        if (!is_dir($dirname) || is_link($dirname)) return unlink($dirname);
        foreach (scandir($dirname) as $file) {
            if ($file == '.' || $file == '..') continue;
            if (!$this->deleteDir($dirname . DIRECTORY_SEPARATOR . $file)) {
                chmod($dirname . DIRECTORY_SEPARATOR . $file, 0777);
                $this->deleteDir($dirname . DIRECTORY_SEPARATOR . $file);
            };
        }

        return rmdir($dirname);
    }

    function prepareList($xml_recipe, &$quarantine_list, &$delete_list)
    {
        $recipe = $this->parseXmlRecipe($xml_recipe);

        $quarantine_files = $recipe->getElementsByTagName("quarantine");
        $delete_files = $recipe->getElementsByTagName("delete");

        foreach ($quarantine_files as $quarantine_file_node) {
            $filename = trim(str_replace('../', '', $quarantine_file_node->nodeValue));
            $quarantine_list[] = $filename;
        }

        foreach ($delete_files as $delete_file_node) {
            $filename = trim(str_replace('../', '', $delete_file_node->nodeValue));
            $delete_list[] = $filename;
        }
    }

    function executeXmlRecipe($delete_files, $quarantine_files, &$num_quarantined)
    {
        //Put suspicious file in quarantine archive
        $this->archiver = new Archiver($this->quarantine_filename, "a");

        $num_quarantined = 0;

        foreach ($quarantine_files as $filename) {
            $absolute_path = $this->web_root_dir . substr($filename, 1);
            if ($this->quarantineFile($absolute_path)) {
                $this->log .= sprintf(PS_WAS_QUARANTINED, $filename) . '<br/>';
                $num_quarantined++;
            }
        }

        $this->archiver->close();

        //Put malicious files to backup archive and delete them      
        foreach ($delete_files as $filename) {
            $this->archiver = new Archiver($this->backup_filename, "a");

            $absolute_path = $this->web_root_dir . substr($filename, 1);

            $this->quarantineFile($absolute_path);

            $this->archiver->close();

            if ($this->deleteFile($absolute_path)) {
                $this->log .= sprintf(PS_WAS_DELETED, $filename) . '<br/>';
            }
        }

        if (file_exists($this->quarantine_filename)) {
            setcookie('quarantine_file', $this->quarantine_filename, time() + 86400, '/', $_SERVER['HTTP_HOST'], false, true);
            $_COOKIE['quarantine_file'] = $this->quarantine_filename;
        }

        return $this->log;
    }
}

<?php

require_once("Archiver.inc.php");
require_once("FileInfo.inc.php");

class Quarantiner {

    function __construct($default_filename = null) {

        global $project_tmp_dir;
        $this->quarantine_list = array();

        if (!$default_filename) {
           $time_string = date("Y_m_d_H_i", $_SERVER["REQUEST_TIME"]);
           $this->quarantine_filename = $project_tmp_dir . '/' . "quarantine." . $time_string . ".zip";
        } else {
           $this->quarantine_filename = $default_filename;
        }    

        $this->web_root_dir = $_SERVER['DOCUMENT_ROOT'];

        if (file_exists($this->quarantine_filename)) {
            unlink($this->quarantine_filename);
        }

    }

    function add($filename) {
       if (file_exists($filename)) {
          $this->quarantine_list[] = $filename;
          return true;
       } 

       return false;
    }

    function getArchive() {
       $this->archiver = new Archiver($this->quarantine_filename, "a");

       foreach ($this->quarantine_list as $filename) {
          $fileinfo = new FileInfo($filename);
          $file_hash = $fileinfo->md5;
          $this->archiver->addFile($filename, $file_hash);
          $meta_filename = $file_hash . ".meta";

          $this->archiver->createFile($meta_filename, (string)$fileinfo);
       }

       $this->archiver->close();

       return $this->quarantine_filename;
    }

}

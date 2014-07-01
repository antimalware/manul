<?php

class FileInfo {
    public function __construct($file_path) {

        $this->web_root_dir = $_SERVER['DOCUMENT_ROOT'];
        $this->getInfoByName($file_path);
    }

    public function getInfoByName($file_path) {

        $this->MAX_FILE_SIZE_FOR_HASHING = 1024 * 1024;
 
        $this->absolute_name = $file_path;
        $this->name = str_replace($this->web_root_dir, '.', $file_path);
        $this->ctime = 0;
        $this->mtime = 0;            
        $this->owner = '-';
        $this->group = '-';
        $this->access = 0;
        $this->size = -1;
        $this->md5 = '-';

        if (file_exists($file_path)) {    
            $this->ctime = filectime($file_path);
            $this->mtime = filemtime($file_path);            

            $owner = fileowner($file_path);
            $ownerInfo = function_exists('posix_getpwuid') ? posix_getpwuid($owner) : array('name' => $owner);
            $this->owner = $ownerInfo['name'];

            $group = filegroup($file_path);
            $groupInfo = function_exists('posix_getgrgid') ? posix_getgrgid($group) : array('name' => $group);
            $this->group = $groupInfo['name'];

            $this->access = substr(sprintf('%o', fileperms($file_path)), -4);
            
            if (is_file($file_path)) {
                $this->size = filesize($file_path);
               
                if ($this->size <= $this->MAX_FILE_SIZE_FOR_HASHING) {
                    $this->md5 = hash_file('md5', $file_path);                   
                }                   
            }
       	}  

        return true;
    }

    public function getXMLNode() {
        $dom = new DOMDocument("1.0", "utf-8");
        $fileinfo_node = $dom->createElement("file");
        $fileinfo_node->appendChild($dom->createElement("path", $this->name));
        $fileinfo_node->appendChild($dom->createElement("size", $this->size));
        $fileinfo_node->appendChild($dom->createElement("ctime", $this->ctime));
        $fileinfo_node->appendChild($dom->createElement("mtime", $this->mtime));
        $fileinfo_node->appendChild($dom->createElement("owner", $this->owner));
        $fileinfo_node->appendChild($dom->createElement("group", $this->group));
        $fileinfo_node->appendChild($dom->createElement("access", $this->access));
        $fileinfo_node->appendChild($dom->createElement("md5", $this->md5));
        $dom->appendChild($fileinfo_node);
        return $dom->getElementsByTagName("file")->item(0);
    }

    public function __toString() {
        $data = array($this->name, $this->size, $this->ctime, $this->mtime, $this->owner, $this->group, $this->access, $this->md5);
        return implode(';', $data);
    }
}



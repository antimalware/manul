<?php

class FileInfo
{
    public function __construct($filePath)
    {
        $this->web_root_dir = $_SERVER['DOCUMENT_ROOT'];
        $this->getInfoByName($filePath);
    }

    public function getInfoByName($filePath)
    {
        $this->MAX_FILE_SIZE_FOR_HASHING = 1024 * 1024;

        $this->absoluteName = $filePath;
        $this->name = str_replace($this->web_root_dir, '.', $filePath);
        $this->ctime = 0;
        $this->mtime = 0;
        $this->owner = '-';
        $this->group = '-';
        $this->access = 0;
        $this->size = -1;
        $this->md5 = '-';

        if (file_exists($filePath)) {
            $this->ctime = filectime($filePath);
            $this->mtime = filemtime($filePath);

            $owner = fileowner($filePath);
            $ownerInfo = function_exists('posix_getpwuid') ? posix_getpwuid($owner) : array('name' => $owner);
            $this->owner = $ownerInfo['name'];

            $group = filegroup($filePath);
            $groupInfo = function_exists('posix_getgrgid') ? posix_getgrgid($group) : array('name' => $group);
            $this->group = $groupInfo['name'];

            $this->access = substr(sprintf('%o', fileperms($filePath)), -4);

            if (is_file($filePath)) {
                $this->size = filesize($filePath);

                if ($this->size <= $this->MAX_FILE_SIZE_FOR_HASHING) {
                    $this->md5 = hash_file('md5', $filePath);
                }
            }
        }

        return true;
    }

    public function getXMLNode()
    {
        $dom = new DOMDocument('1.0', 'utf-8');
        $fileinfoNode = $dom->createElement('file');
        $fileinfoNode->appendChild($dom->createElement('path', $this->name));
        $fileinfoNode->appendChild($dom->createElement('size', $this->size));
        $fileinfoNode->appendChild($dom->createElement('ctime', $this->ctime));
        $fileinfoNode->appendChild($dom->createElement('mtime', $this->mtime));
        $fileinfoNode->appendChild($dom->createElement('owner', $this->owner));
        $fileinfoNode->appendChild($dom->createElement('group', $this->group));
        $fileinfoNode->appendChild($dom->createElement('access', $this->access));
        $fileinfoNode->appendChild($dom->createElement('md5', $this->md5));
        $dom->appendChild($fileinfoNode);
        return $dom->getElementsByTagName('file')->item(0);
    }

    public function __toString()
    {
        $data = array($this->name, $this->size, $this->ctime, $this->mtime, $this->owner, $this->group, $this->access, $this->md5);
        return implode(';', $data);
    }
}



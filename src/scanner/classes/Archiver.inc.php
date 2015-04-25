<?php
#ZipArchive requies PHP above 5.2
class Archiver
{
    private $filename = '';
    private $mode = '';
    private $archive = null;

    public function __construct($filename, $mode = 'r')
    {
        $this->filename = $filename;
        $this->mode = $mode;
        $result = false;
        $archive = new ZipArchive;
        if ($this->mode === 'r') {
            $result = $archive->open($this->filename, ZipArchive::OPEN);
            if (!$result) die(PS_ERR_ARCHIVE_OPENING);
        } else if ($this->mode === 'w' || $this->mode === 'a') {
            $result = $archive->open($this->filename, ZipArchive::CREATE);
            if (!$result) die(sprintf(PS_ERR_ARCHIVE_CREATION, $this->filename));
        } else {
            die(PS_ERR_WRONG_ARCHIVE_MODE);
        }
        $this->archive = $archive;
    }

    public function addFile($filename, $targetFilename = null)
    {
        if ($this->mode === 'r') die(PS_ERR_ARCHIVE_WRITE_INCORRECT_MODE);
        if (!$targetFilename) {
            $this->archive->addFile($filename);
        } else {
            $this->archive->addFile($filename, $targetFilename);
        }
    }

    public function createFile($filename, $str)
    {
        $this->archive->addFromString($filename, $str);
    }

    public function close()
    {
        $this->archive->close();
    }
}


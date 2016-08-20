<?php

ob_start();
require_once('Tskv.inc.php');
ob_end_clean();

//class which represents a detection of a file to maintain malware detection queue 
class SignatureDetect
{
    function __construct($filePath, $id, $severity, $signature, $position, $detectedContent) 
    { 

        $this->filePath = $filePath;    
        $this->id = $id;    
        $this->severity = $severity;
        $this->signature = $signature;
        $this->position = $position;
        $this->detectedContent = $detectedContent;
    }

    function toString() 
    {
        $elements = array('filePath' => $this->filePath,
                          'severity' => $this->severity, 
                         );
        if ($this->severity) {
            $elements['id'] = $this->id;
            $elements['signature'] = $this->signature;
            $elements['position'] = $this->position;
            $elements['detectedContent'] = $this->detectedContent;
        }
        return toTskv($elements);
    }

    function fromString($tskvString) 
    {
         $keyValuePairs = fromTskv($tskvString);
         $this->filePath = $keyValuePairs['filePath'];    
         $this->severity = $keyValuePairs['severity']; 
         if ($this->severity) {   
             $this->id = $keyValuePairs['id'];    
             $this->signature = $keyValuePairs['signature'];
             $this->position = $keyValuePairs['position'];
             $this->detectedContent = $keyValuePairs['detectedContent'];
         }
    }
    
}

<?php

ob_start();
require_once('Tskv.inc.php');
ob_end_clean();


class SignatureDetect
{
    function __construct($filePath, $isDetected, $id, $severity, $signature, $position, $detectedContent) 
    { 

        $this->filePath = $filePath;    
        $this->isDetected = $isDetected;    
        $this->id = $id;    
        $this->severity = $severity;
        $this->signature = $signature;
        $this->position = $position;
        $this->detectedContent = $detectedContent;
    }

    function toString() 
    {
        $elements = array('filePath' => $this->filePath,
                          'isDetected' => $this->isDetected, 
                         );
        if ($this->isDetected == 1) {
            $elements['id'] = $this->id;
            $elements['severity'] = $this->severity;
            $elements['signature'] = base64_encode($this->signature);
            $elements['position'] = $this->position;
            $elements['detectedContent'] = $this->detectedContent;
        }
        return toTskv($elements);
    }

    function fromString($tskvString) 
    {
         $keyValuePairs = fromTskv($tskvString);
         $this->filePath = $keyValuePairs['filePath'];    
         $this->isDetected = $keyValuePairs['isDetected']; 
         if ($this->isDetected == 1) {   
             $this->id = $keyValuePairs['id'];    
             $this->severity = $keyValuePairs['severity'];
             $this->signature = base64_decode($keyValuePairs['signature']);
             $this->position = $keyValuePairs['position'];
             $this->detectedContent = $keyValuePairs['detectedContent'];
         }
    }
    
}

<?php

#Workaround for hostings where file_put_contents is disabled
function file_put_contents2($filename, $data, $mode = 'w')
{
    $file = fopen($filename, $mode);
    $bytes = fwrite($file, $data);
    fclose($file);
    return $bytes;
}


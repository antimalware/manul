<?php

//dump assisiative array to tab separated key-value string
function toTskv($dict)
{
    $keyValuePairs = array();
    foreach($dict as $key => $value) {
        array_push($keyValuePairs, "$key=$value");
    }
    return join("\t", $keyValuePairs);
}

//parse tab separated key-value string to assisiative array
function fromTskv($tskvString)
{
    $dict = array();
    foreach(explode("\t", $tskvString) as $keyValuePair) {
        $keyValue = explode("=", $keyValuePair, 2);
        $dict[$keyValue[0]] = $keyValue[1];
    }
    return $dict;
}

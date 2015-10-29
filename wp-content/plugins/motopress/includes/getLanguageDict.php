<?php
require_once 'settings.php';

function getLanguageDict() {
    global $motopressSettings;
    
    $file = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' .
        DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR . $motopressSettings['lang'];
    if (!file_exists($file))
        $file = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' .
            DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR . 'en.json';
    
    $contents = json_decode(file_get_contents($file));
    return $contents->lang;
}
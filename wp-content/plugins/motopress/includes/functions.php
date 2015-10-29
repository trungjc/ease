<?php
require_once 'settings.php';

function setError($message = '') {
    global $motopressSettings;
    header('HTTP/1.1 500 Internal Server Error');
    header('Content-Type: application/json');
    die(json_encode(array(
        'debug' => $motopressSettings ? $motopressSettings['debug'] : false,
        'message' => $message
    )));
}
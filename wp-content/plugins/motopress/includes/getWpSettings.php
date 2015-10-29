<?php
function motopressGetWpSettings() {
    require_once 'verifyNonce.php';
    require_once 'settings.php';
    require_once 'access.php';

    global $motopressSettings;
    echo json_encode($motopressSettings);
    exit;
}
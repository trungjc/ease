<?php
function motopressGetStaticBlock() {
    require_once 'verifyNonce.php';
    require_once 'access.php';

    if(isset($_POST['staticFile']) && !empty($_POST['staticFile'])) {
        $staticFile = $_POST['staticFile'];
        $file = explode('.php', $staticFile);
        $name = $file[0];
        get_template_part($name);
    }
    exit;
}
<?php
function motopressSave() {
    $data = $_POST['data'];
    $page = $_POST['page'];
    require_once 'SaveTemplate.php';
    new SaveTemplate($page, $data);
    exit;
}
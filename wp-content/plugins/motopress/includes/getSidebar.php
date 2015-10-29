<?php
function motopressGetSidebar() {
    require_once 'verifyNonce.php';
    require_once 'access.php';

    if (isset($_POST['id'], $_POST['type']) && !empty($_POST['id']) && !empty($_POST['id'])) {
        $id = $_POST['id'];
        $type = $_POST['type'];

        if ($type == 'dynamic') {
            dynamic_sidebar($id);
        } elseif ($type == 'static') {
            if ($id == 'sidebar.php') {
                get_sidebar();
            } else {
                preg_match('/^sidebar-(.+)\.php$/is', $id, $matches);
                if (!empty($matches[1])) {
                    get_sidebar($matches[1]);
                }
            }
        }
    }
    exit;
}
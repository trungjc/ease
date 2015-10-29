<?php
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    require_once ABSPATH . WPINC . '/pluggable.php';
    $currentUser = wp_get_current_user();
    if (!is_user_logged_in() or !in_array('administrator', $currentUser->roles) or !current_user_can('edit_themes')) {
        require_once 'functions.php';
        require_once 'getLanguageDict.php';
        global $lang;
        $lang = getLanguageDict();
        setError($lang->permissionDenied);
    }
}
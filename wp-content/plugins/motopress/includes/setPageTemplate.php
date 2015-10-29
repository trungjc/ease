<?php
function motopressSetPageTemplate() {
    require_once 'verifyNonce.php';
    require_once 'access.php';
    require_once 'functions.php';
    require_once 'getLanguageDict.php';
    
    $pageId = $_POST['pageId'];
    $template = $_POST['template'];
    
    $lang = getLanguageDict();

    if (!$pageId or !$template) {
        setError($lang->setPageTemplateError);
    }

    if (!update_post_meta($pageId, '_wp_page_template', $template)) {
        setError($lang->setPageTemplateError);
    }
    exit;
}
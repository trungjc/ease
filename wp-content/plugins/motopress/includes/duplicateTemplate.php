<?php

function motopressDuplicateTemplate() {
    require_once 'verifyNonce.php';
    require_once 'settings.php';
    require_once 'access.php';
    require_once ABSPATH.'/wp-admin/includes/theme.php';
    require_once 'InitTemplate.php';
    require_once 'functions.php';
    require_once 'getLanguageDict.php';

    $pageId = $_POST['pageId'];
    $templateToDuplicate = $_POST['templateToDuplicate'];
    $newTemplateName = trim($_POST['newTemplateName']);

    global $motopressSettings;
    $lang = getLanguageDict();
    $errors = array();

    $templateDir = $motopressSettings['theme_root'] . '/' . $motopressSettings['current_theme'] . '/';

    if ($pageId && $templateToDuplicate && $newTemplateName) {
        $newTemplateFile = uniqid('page-') . '.php';

        if (!preg_match('/^[^\*\/]{1,30}$/is', $newTemplateName)) {
            setError($lang->validationName);
        }

        // If template with new name already exists
        $pageTemplates = get_page_templates();
        foreach ($pageTemplates as $name => $file) {
            if (strcasecmp($newTemplateName, $name) == 0) {
                setError($lang->duplicateErrorTemplateExists);
                //$errors[] = 'Template `'. $name .'` already exists.';
                break;
            }
        }

        if (file_exists($templateDir . $templateToDuplicate)) {
            $oldTemplateFile = file_get_contents($templateDir . $templateToDuplicate);

            $oldTemplateFile = InitTemplate::removePhpComment($oldTemplateFile);
            $oldTemplateFile = InitTemplate::removeEmptyPhp($oldTemplateFile);
            $oldTemplateFile = InitTemplate::reinit($oldTemplateFile, $newTemplateFile, 'main');
            $oldTemplateFile = InitTemplate::setAnnotations($oldTemplateFile, array('template_name' => 'Template Name: '.$newTemplateName));
            if (!file_put_contents($templateDir . $newTemplateFile, $oldTemplateFile)) {
                setError($lang->duplicateError);
                //$errors[] = 'Error on: file_put_contents("'.$templateDir . $newTemplateFile.'", $oldTemplateFile)';
            }
            if (!update_post_meta($pageId, '_wp_page_template', $newTemplateFile)) {
                unlink($templateDir . $newTemplateFile);
                setError($lang->duplicateError);
                //$errors[] = 'Error on: update_post_meta('.$pageId.', "_wp_page_template", "'.$newTemplateFile.'")';
            }
            $request = array(
                'value' => $newTemplateFile,
                'name' => $newTemplateName
            );
            echo json_encode($request);
        } else {
            $errors[] = strtr($lang->duplicateErrorTemplateNotExist, array(
                '%template%' => $templateDir . $templateToDuplicate
            ));
        }
    } else {
        $errors[] = $lang->duplicateError;
    }

    if (!empty($errors)) {
        if ($motopressSettings['debug']) {
            setError($errors);
        } else {
            setError($lang->duplicateError);
        }
    }
    exit;
}

<?php
function motopressGetLoop() {
    require_once 'verifyNonce.php';
    require_once 'settings.php';
    require_once 'access.php';
    require_once 'Requirements.php';
    require_once 'SaveTemplate.php';
    require_once 'functions.php';
    require_once 'getLanguageDict.php';

    if (
        (isset($_POST['link']) && !empty($_POST['link'])) &&
        (isset($_POST['page']) && !empty($_POST['page'])) &&
        (isset($_POST['data']) && !empty($_POST['data']))
    ) {
        $link = $_POST['link'];
        $page = $_POST['page'];
        $data = stripslashes($_POST['data']);

        global $motopressSettings;
        $lang = getLanguageDict();
        $errors = array();

        $pageWithExt = explode('.php', $page);
        $pageName = $pageWithExt[0];

        $copy = copy(
            $motopressSettings['theme_root'].'/'.$motopressSettings['current_theme'].'/'.$page,
            $motopressSettings['theme_root'].'/'.$motopressSettings['current_theme'].'/'.$pageName.'-original.php'
        );
        if ($copy) {
            preg_match_all('/data-motopress-wrapper-file=[\'"]wrapper\/([^\'"]+)[\'"]/is', $data, $wrapperMatches);

            if (!empty($wrapperMatches[1])) {
                $wrappers = $wrapperMatches[1];
                foreach ($wrappers as $wrapper) {
                    $wrapperWithExt = explode('.php', $wrapper);
                    $wrapperName = $wrapperWithExt[0];
                    $wrapperCopy = copy(
                        $motopressSettings['theme_wrapper_root'].'/'.$wrapper,
                        $motopressSettings['theme_wrapper_root'].'/'.$wrapperName.'-original.php'
                    );
                    if (!$wrapperCopy) {
                        $errors[] = strtr($lang->copyError, array(
                            '%source%' => $motopressSettings['theme_wrapper_root'].'/'.$wrapper,
                            '%dest%' => $motopressSettings['theme_wrapper_root'].'/'.$wrapperName.'-original.php'
                        ));
                    }
                }
                new SaveTemplate($page, $data);

                $newHtml = false;
                $requirements = new Requirements();
                if ($requirements->getCurl()) {
                    $ch = curl_init();
                    $options = array(
                        CURLOPT_URL => $link,
                        CURLOPT_RETURNTRANSFER => true
                    );
                    curl_setopt_array($ch, $options);
                    $newHtml = curl_exec($ch);
                    curl_close($ch);
                } else {
                    $newHtml = file_get_contents($link);
                }

                if (!$newHtml) {
                    $errors[] = strtr($lang->openError, array('%name%' => $link));
                }

                foreach ($wrappers as $wrapper) {
                    if (!unlink($motopressSettings['theme_wrapper_root'].'/'.$wrapper)) {
                        $errors[] = 'Could not remove file ' . $wrapper;
                    }
                    $wrapperWithExt = explode('.php', $wrapper);
                    $wrapperName = $wrapperWithExt[0];
                    $wrapperRename = rename(
                        $motopressSettings['theme_wrapper_root'].'/'.$wrapperName.'-original.php',
                        $motopressSettings['theme_wrapper_root'].'/'.$wrapper
                    );
                    if (!$wrapperRename) {
                        $errors[] = strtr($lang->renameError, array(
                            '%source%' => $motopressSettings['theme_wrapper_root'].'/'.$wrapperName.'-original.php',
                            '%dest%' => $motopressSettings['theme_wrapper_root'].'/'.$wrapper
                        ));
                    }
                }
            }

            if (!unlink($motopressSettings['theme_root'].'/'.$motopressSettings['current_theme'].'/'.$page)) {
                $errors[] = strtr($lang->removeError, array(
                    '%name%' => $motopressSettings['theme_root'].'/'.$motopressSettings['current_theme'].'/'.$page
                ));
            }
            $rename = rename(
                $motopressSettings['theme_root'].'/'.$motopressSettings['current_theme'].'/'.$pageName.'-original.php',
                $motopressSettings['theme_root'].'/'.$motopressSettings['current_theme'].'/'.$page
            );
            if (!$rename) {
                $errors[] = strtr($lang->renameError, array(
                    '%source%' => $motopressSettings['theme_root'].'/'.$motopressSettings['current_theme'].'/'.$pageName.'-original.php',
                    '%dest%' => $motopressSettings['theme_root'].'/'.$motopressSettings['current_theme'].'/'.$page
                ));
            }
        } else {
            $errors[] = strtr($lang->copyError, array(
                '%source%' => $motopressSettings['theme_root'].'/'.$motopressSettings['current_theme'].'/'.$page,
                '%dest%' => $motopressSettings['theme_root'].'/'.$motopressSettings['current_theme'].'/'.$pageName.'-original.php'
            ));
        }

        if (empty($errors)) {
            echo $newHtml;
        } else {
            if ($motopressSettings['debug']) {
                print_r($errors);
            } else {
                setError($lang->loopError);
            }
        }
    }
    exit;
}
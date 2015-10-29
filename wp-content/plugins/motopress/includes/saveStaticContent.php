<?php
function motopressSaveStaticContent() {
    require_once 'verifyNonce.php';
    require_once 'settings.php';
    require_once 'access.php';
    require_once 'functions.php';
    require_once 'getLanguageDict.php';

    $lang = getLanguageDict();

    if (
        isset($_POST['staticName']) && !empty($_POST['staticName']) && preg_match('/^[^\*\/]{1,30}$/is', $_POST['staticName']) &&
        isset($_POST['staticFile']) && !empty($_POST['staticFile']) &&
        isset($_POST['staticContent'])
    ) {
        $staticName = trim($_POST['staticName']);
        $staticName = htmlspecialchars($staticName);
        $staticFile = $_POST['staticFile'];
        $staticContent = $_POST['staticContent'];

        global $motopressSettings;
        $errors = array();

        $file = explode('/', $staticFile);
        if (count($file) == 2) {
            $fileName = $file[1];
            $fileWithExt = explode('.php', $staticFile);
            $name = $fileWithExt[0];

            $filePath = $motopressSettings['theme_static_root'] . '/' . $fileName;

            $pattern = '/\*\s*Static\s+Name:\s*([^\*]+)\s*\*/is';
            $replacement = '* Static Name: ' . $staticName . ' *';

            $content = stripslashes($staticContent);

            if (file_exists($filePath)) {
                $content = preg_replace($pattern, $replacement, $content, 1);
            } else {
                file_put_contents($filePath, '');
                $content = '<?php /' . $replacement . "/ ?>\n" . $content;
            }

            $search = array('[php]', '[/php]');
            $replace = array('<?php', '?>');
            $content = str_replace($search, $replace, $content);

            if (!is_writable($motopressSettings['theme_static_root'])) {
                if (@!chmod($motopressSettings['theme_static_root'], 0777)) {
                    $errors[] = strtr($lang->changePermissionsError, array('%name%' => $motopressSettings['theme_static_root']));
                }
            }

            if (is_writable($motopressSettings['theme_static_root'])) {
                if (!is_writable($filePath)) {
                    if (@!chmod($filePath, 0777)) {
                        $errors[] = strtr($lang->changePermissionsError, array('%name%' => $filePath));
                    }
                }

                if (is_writable($filePath)) {
                $write = file_put_contents($filePath, htmlspecialchars_decode($content));
                    if ($write) {
                        get_template_part($name);
                    } else {
                        $errors[] = strtr($lang->writeError, array('%name%' => $filePath));
                    }
                } else {
                    $errors[] = strtr($lang->notWritable, array('%name%' => $filePath));
                }
            } else {
                $errors[] = strtr($lang->notWritable, array('%name%' => $motopressSettings['theme_static_root']));
            }
        }

        if (!empty($errors)) {
            if ($motopressSettings['debug']) {
                print_r($errors);
            } else {
                setError($lang->saveStaticContentError);
            }
        }
    } else {
        setError($lang->saveStaticContentError);
    }
    exit;
}

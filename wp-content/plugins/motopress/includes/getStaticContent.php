<?php
function motopressGetStaticContent() {
    require_once 'verifyNonce.php';
    require_once 'settings.php';
    require_once 'access.php';

    if(isset($_POST['staticFile']) && !empty($_POST['staticFile'])) {
        global $motopressSettings;

        $staticFile = $_POST['staticFile'];
        $file = explode('/', $staticFile);
        if(count($file) == 2) {
            $fileName = $file[1];

            $content = file_get_contents($motopressSettings['theme_static_root'] . '/' . $fileName);

            $search = array('<?php', '<?', '?>');
            $replace = array('[php]', '[php]', '[/php]');
            $content = str_replace($search, $replace, $content);

            echo $content;
        }
    }
    exit;
}
<?php
function motopressReset() {
    require_once 'verifyNonce.php';
    require_once 'settings.php';
    require_once 'access.php';
    require_once 'Requirements.php';

    global $motopressSettings;
    $templateDir = $motopressSettings['theme_root'] . '/' . get_current_theme() . '/';
    $resetDir = WP_CONTENT_DIR . '/motopress-theme-reset/' . get_current_theme() . '/';

    foreach (glob($resetDir . '*.php') as $file) {
        if (file_exists($templateDir.basename($file))) {
            file_put_contents($templateDir.basename($file), file_get_contents($file));
        }
    }
    foreach (glob($resetDir . 'wrapper/' . '*.php') as $file) {
        file_put_contents($templateDir.'wrapper/'.basename($file), file_get_contents($file));
    }
    foreach (glob($resetDir . 'loop/' . '*.php') as $file) {
        file_put_contents($templateDir.'loop/'.basename($file), file_get_contents($file));
    }
    foreach (glob($resetDir . 'static/' . '*.php') as $file) {
        file_put_contents($templateDir.'static/'.basename($file), file_get_contents($file));
    }
    exit;
}
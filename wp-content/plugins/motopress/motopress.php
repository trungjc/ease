<?php
/*
Plugin Name: MotoPress Layout Editor
Plugin URI: http://www.getmotopress.com/
Description: MotoPress Layout Editor is the efficient, intuitive and easy to use plugin that allows you easily change the overall appearance of your website by editing your sites layout.
Version: 2.1
Author: MotoPress
Author URI: http://www.getmotopress.com/
License: GPLv2
*/

add_action('wp_head', 'setMotopressFlag');

function setMotopressFlag() {
?>
<script type="text/javascript">
    var motoPressPlugin = <?php echo (isset($_GET['motopress']) && $_GET['motopress'] == 1) ? 'true' : 'false'; ?>;
</script>
<?php
}

if (!is_admin()) {
    return;
}

require_once 'includes/Requirements.php';
require_once 'visualEditor.php';
require_once 'motopressOptions.php';
require_once 'includes/settings.php';
require_once 'includes/Flash.php';
require_once 'includes/getLanguageDict.php';

add_action('admin_init', 'motopressInit');
add_action('admin_menu', 'motopressMenu');
add_action('admin_bar_menu', 'motopressAdminBarMenu', 999);

function motopressInit() {
    global $motopressSettings;

    wp_register_style('style', plugin_dir_url(__FILE__) . 'includes/css/style.css', null, $motopressSettings['plugin_version']);
    wp_register_script('detectBrowser', plugin_dir_url(__FILE__).'mp/detectBrowser/detectBrowser.js', null, $motopressSettings['plugin_version']);
}

function motopressMenu() {
    global $lang;
    $lang = getLanguageDict();

    $mainPage = add_menu_page('MotoPress', 'MotoPress', 'read', 'motopress', 'motopress', plugin_dir_url(__FILE__) . 'images/menu-icon.png', null);

    global $requirements;
    $requirements = new Requirements();

    global $isSupportedTheme;
    $isSupportedTheme = isSupportedTheme();

    global $isjQueryVer;
    $isjQueryVer = checkjQueryVer();

    if ($isSupportedTheme && $isjQueryVer) {
        add_submenu_page('motopress', $lang->visualEditor, $lang->visualEditor, 'read', 'motopress_visual_editor', 'motopressVisualEditor');
    }

    $optionsPage = add_submenu_page('motopress', $lang->motopressOptions, $lang->motopressOptions, 'manage_options', 'motopress_options', 'motopressOptions');

    add_action('admin_print_styles-' . $mainPage, 'motopressAdminStylesAndScripts' );
    add_action('admin_print_styles-' . $optionsPage, 'motopressAdminStylesAndScripts' );
}

function motopressAdminBarMenu($wp_admin_bar) {
    if (isset($_GET['page']) && $_GET['page'] === 'motopress_visual_editor') {
        $wp_admin_bar->remove_node('view-site');

        $parent = 'site-name';
        $target = '_blank';
        $menu = array(
            array(
                'id' => 'motopress-dashboard',
                'title' => __('Dashboard'),
                'parent' => $parent,
                'href' => admin_url(),
                'meta' => array(
                    'target' => $target
                )
            ),
            array(
                'id' => 'motopress-pages',
                'title' => __('Pages'),
                'parent' => $parent,
                'href' => admin_url('edit.php?post_type=page'),
                'meta' => array(
                    'target' => $target
                )
            ),
            array(
                'id' => 'motopress-menus',
                'title' => __('Menus'),
                'parent' => $parent,
                'href' => admin_url('nav-menus.php'),
                'meta' => array(
                    'target' => $target
                )
            ),
            array(
                'id' => 'motopress-widgets',
                'title' => __('Widgets'),
                'parent' => $parent,
                'href' => admin_url('widgets.php'),
                'meta' => array(
                    'target' => $target
                )
            )
        );

        foreach ($menu as $item) {
            $wp_admin_bar->add_node($item);
        }
    }
}

function motopressAdminStylesAndScripts() {
    wp_enqueue_style('style');
    wp_enqueue_script('detectBrowser');
}

function motopress() {
    showWelcomeScreen();
}

function showWelcomeScreen() {
    global $requirements;
    global $motopressSettings;
    global $lang;
    echo '<div class="motopress-title-page">';
    echo '<img id="motopress-logo" src="'.plugin_dir_url(__FILE__).'images/logo-large.png?ver='.$motopressSettings['plugin_version'].'" />';
    echo '<p id="motopress-description">' . $lang->motopressDescription . '</p>';

    global $isSupportedTheme;
    global $isjQueryVer;

    $link = $_SERVER['PHP_SELF'].'?page=motopress_visual_editor';
    $disabledClass = '';
    if (!$isSupportedTheme || !$isjQueryVer) {
        $link = 'javascript:void(0);';
        $disabledClass = 'disabled';
    }
    echo '<center><a href="'.$link.'" class="btn-wide '.$disabledClass.'" id="motopress-visual-editor-btn">' . $lang->visualEditor . '</a></center>';

    if (!$isSupportedTheme) {
        echo '<p><div class="alert alert-info" id="motopress-theme-support-msg">'.$lang->themeNotSupported.'</div></p>';
    }

    if (!$isjQueryVer) {
        echo '<p><div class="alert alert-info" id="motopress-jquery-support-msg">'.strtr($lang->jQueryVerNotSupported, array('%minjQueryVer%' => $requirements->getMinjQueryVer(), '%minjQueryUIVer%' => $requirements->getMinjQueryUIVer())).'</div></p>';
    }

    echo '<p><div class="alert alert-info" id="motopress-browser-support-msg" style="display: none;">'.$lang->browserNotSupported.'</div></p>';
}

function motopressInstall() {
    add_option('motopress-language', 'en.json');
}
register_activation_hook(__FILE__, 'motopressInstall');

function isSupportedTheme() {
    global $motopressSettings;

    $stylePath = $motopressSettings['theme_root'] . '/' . $motopressSettings['current_theme'] . '/style.css';
    $styleContent = file_get_contents($stylePath);
    $themeMotopressVer = getThemeMotopressVer($styleContent);

    if ((int)$motopressSettings['plugin_version'] == (int)$themeMotopressVer) {
        return true;
    }
    return false;
}

function checkjQueryVer() {
    global $requirements;
    global $wp_scripts;
    
    $jQueryVer = $wp_scripts->registered['jquery']->ver;
    $jQueryUIVer = $wp_scripts->registered['jquery-ui-core']->ver;

    return (version_compare($jQueryVer, $requirements->getMinjQueryVer(), '>=') && version_compare($jQueryUIVer, $requirements->getMinjQueryUIVer(), '>=')) ? true : false;
}

function getThemeMotopressVer($content) {
    if (isset($content) && !empty($content)) {
        $pattern = '/\s*motopress\s+version\s*:\s*([^\n]+)\s*/is';
        preg_match($pattern, $content, $matches);
        if (!empty($matches[1])) {
            $version = trim($matches[1]);
            return $version;
        }
    }
    return false;
}
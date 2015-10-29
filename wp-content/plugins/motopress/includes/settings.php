<?php
    require_once ABSPATH . 'wp-admin/includes/plugin.php';

    $motopressSettings = array();
    $motopressSettings['debug'] = false;
    $motopressSettings['admin_url'] = get_admin_url();
    $motopressSettings['plugin_root'] = WP_PLUGIN_DIR;
    $motopressSettings['plugin_root_url'] = WP_PLUGIN_URL;
    $motopressSettings['plugin_name'] = 'motopress';
    $pluginData = get_plugin_data($motopressSettings['plugin_root'].'/'.$motopressSettings['plugin_name'].'/'.$motopressSettings['plugin_name'].'.php', false, false);
    $motopressSettings['plugin_version'] = $pluginData['Version'];

    $theme = wp_get_theme();
    $motopressSettings['theme_root'] = $theme->get_theme_root();
    $motopressSettings['theme_root_url'] = get_theme_root_uri();
    $motopressSettings['current_theme'] = $theme->get_stylesheet();
    $motopressSettings['parent_theme'] = ($theme->parent()) ? $theme->parent()->get_stylesheet() : $theme->get_stylesheet();

    $motopressSettings['theme_static_root'] = $motopressSettings['theme_root'] . '/' . $motopressSettings['current_theme'] . '/static';
    $motopressSettings['theme_static_root_url'] = $motopressSettings['theme_root_url'] . '/' . $motopressSettings['current_theme'] . '/static';

    $motopressSettings['theme_wrapper_root'] = $motopressSettings['theme_root'] . '/' . $motopressSettings['current_theme'] . '/wrapper';
    $motopressSettings['theme_wrapper_root_url'] = $motopressSettings['theme_root_url'] . '/' . $motopressSettings['current_theme'] . '/wrapper';

    $motopressSettings['theme_loop_root'] = $motopressSettings['theme_root'] . '/' . $motopressSettings['current_theme'] . '/loop';
    $motopressSettings['parent_theme_loop_root'] = $motopressSettings['theme_root'] . '/' . $motopressSettings['parent_theme'] . '/loop';
    $motopressSettings['theme_loop_root_url'] = $motopressSettings['theme_root_url'] . '/'.$motopressSettings['current_theme'] . '/loop';

    $motopressSettings['lang'] = get_option('motopress-language') ? get_option('motopress-language') : 'en.json';

    $motopressSettings['load_scripts_url'] = $motopressSettings['admin_url'] . 'load-scripts.php?c=0&load=jquery-ui-core,jquery-ui-widget,jquery-ui-mouse,jquery-ui-position,jquery-ui-draggable,jquery-ui-droppable,jquery-ui-resizable';
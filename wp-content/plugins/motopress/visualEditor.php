<?php

require_once 'includes/InitTemplate.php';
require_once 'includes/settings.php';
require_once 'includes/getLanguageDict.php';

function motopressVisualEditor() {
    global $motopressSettings;
    $lang = getLanguageDict();

    wp_register_style('style', plugin_dir_url(__FILE__) . 'includes/css/style.css', null, $motopressSettings['plugin_version']);
    wp_enqueue_style('style');

    $copyErrors = copyParentFiles();
    if (!empty($copyErrors)) {
        Flash::setFlash($copyErrors, 'error');
    } else {
        $initTemplate = new InitTemplate();
        $initErrors = $initTemplate->identify();
        if (!empty($initErrors)) {
            Flash::setFlash($initErrors, 'error');
        } else {
?>
            <div class="navbar motopress-navbar">
                <div class="navbar-inner">
                    <div id="motopress-logo">
                        <img src="<?php echo $motopressSettings['plugin_root_url'].'/'.$motopressSettings['plugin_name'].'/images/logo.png?ver='.$motopressSettings['plugin_version']; ?>" />
                    </div>
                    <div id="motopress-editor-group">
                        <div class="pull-left navbar-form motopress-navbar">
                            <ul class="nav">
                                <li>
                                    <span><?php echo $lang->page ?>&nbsp;</span>
                                    <?php require_once 'includes/showPageList.php'; ?>
                                </li>
                                <li id="motopress-page-templates-wrapper">
                                    <!--<span id="motopress-page-templates-wrapper">-->
                                        <span><?php echo $lang->template ?>&nbsp;</span>
                                        <?php require_once 'includes/showTemplateList.php'; ?>
                                    <!--</span>-->
                                    <button class="btn-default" id="motopress-duplicate-template" data-toggle="modal" data-target="#motopress-duplicate-modal"><?php echo $lang->duplicate?></button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div id="motopress-preview-group" style="display: none">
                        <button class="btn-default pull-left active" data-toggle="button" id="motopress-show-hide-blocks">
                            <i class="icon-eye-open"></i>&nbsp;<span><?php echo $lang->showHiddenBlocks; ?></span>
                        </button>
                    </div>

                    <div class="pull-right">
                        <ul class="nav pull-right">
                            <li>
                                <button class="btn-blue" id="motopress-save"><?php echo $lang->save; ?></button>
                                <!--<button class="btn-red" id="motopress-reset"><?php //echo $lang->reset; ?></button>-->
                                <button class="btn-default" id="motopress-visit-site"><?php echo $lang->visitSite; ?></button>
                            </li>
                            <li class="dropdown" id="screenViews">
                                <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
                                    <div id="currentViewMode" class="screen-views-icon screen-views-editor-icon"></div>
                                    <b class="caret"></b>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="javascript:void(0);" id="defaultScreenView"><div class="screen-views-icon screen-views-desktop-icon"></div></a><hr/></li>
                                    <li><a href="javascript:void(0);" id="tabletScreenView"><div class="screen-views-icon screen-views-tablet-icon"></div></a><hr/></li>
                                    <li><a href="javascript:void(0);" id="phoneScreenView"><div class="screen-views-icon screen-views-phone-icon"></div></a><hr/></li>
                                    <li><a href="javascript:void(0);" id="editorView"><div class="screen-views-icon screen-views-editor-icon"></div></a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div id="motopress-flash"></div>

            <div id="motopress-iframe-wrapper">
                <iframe id="motopress-iframe"></iframe>
            </div>

            <div id="motopress-preview-iframe-wrapper">
                <iframe id="motopress-preview-iframe"></iframe>
            </div>

            <!-- Welcome -->
            <div id="motopress-welcome-modal" class="modal hide fade" role="dialog" aria-labelledby="welcomeModalLabel" aria-hidden="true">
                <div class="modal-header">
                    <div class="motopress-close motopress-icon-remove" data-dismiss="modal" aria-hidden="true"></div>
                    <p id="welcomeModalLabel"><?php echo $lang->welcomeToMotopressTitle; ?></p>
                </div>
                <div class="modal-body">
                    <?php echo $lang->welcomeToMotopressMessage; ?>
                </div>
                <div class="modal-footer">
                    <button class="btn-default" data-dismiss="modal" aria-hidden="true"><?php echo $lang->close; ?></button>
                </div>
            </div>

            <!-- Dublicate -->
            <div id="motopress-duplicate-modal" class="modal hide fade" role="dialog" aria-labelledby="duplicateModalLabel" aria-hidden="true">
                <div class="modal-header">
                    <div class="motopress-close motopress-icon-remove" data-dismiss="modal" aria-hidden="true"></div>
                    <p id="duplicateModalLabel"><?php echo $lang->newTemplateName; ?></p>
                </div>
                <div class="modal-body">
                    <input type="text" id="motopress-new-template-name" placeholder="<?php echo $lang->newTemplateName; ?>" required maxlength="30">
                </div>
                <div class="modal-footer">
                    <button class="btn-blue" id="motopress-duplicate-template-create"><?php echo $lang->create; ?></button>
                    <button class="btn-default" data-dismiss="modal" aria-hidden="true"><?php echo $lang->cancel; ?></button>
                </div>
            </div>

            <!-- Static editor -->
            <div id="motopress-static-editor-modal" class="modal hide fade" role="dialog" aria-labelledby="staticModalLabel" aria-hidden="true">
                <div class="modal-header">
                    <div class="motopress-close motopress-icon-remove" data-dismiss="modal" aria-hidden="true"></div>
                    <p id="staticModalLabel"><?php echo $lang->editContent; ?></p>
                </div>
                <div class="modal-body">
                    <label for="motopress-static-name" class="blockName-label"><?php echo $lang->staticName; ?>:*</label>
                    <input id="motopress-static-name" type="text" placeholder="<?php echo $lang->staticName; ?>" required maxlength="30">
                    <div id="motopress-static-editor-wrapper">
                        <?php
                            if (isset($_COOKIE['wp-settings-1'])) {
                                $_COOKIE['wp-settings-1'] = preg_replace('/editor=(tinymce|html)/is', 'editor=html', $_COOKIE['wp-settings-1'], 1);
                            }
                            wp_editor('', 'motopress-static-content', array('remove_linebreaks' => 'false', 'schema' => 'html5'));
                        ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="motopress-save-static-content" class="btn-blue"><?php echo $lang->save; ?></button>
                    <button class="btn-default" data-dismiss="modal" aria-hidden="true"><?php echo $lang->cancel; ?></button>
                </div>
            </div>

            <!-- Confirm -->
            <div id="motopress-confirm-modal" class="modal hide fade" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true"></div>

            <!-- Preload -->
            <div id="motopress-preload"></div>

            <script type="text/javascript">
                var steal = { production: 'mp/production.js?ver=<?php echo $motopressSettings['plugin_version']; ?>' };
            </script>
<?php

            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? 'https' : 'http';
            $wpIncludesUrl = str_replace($protocol.'://'.$_SERVER['HTTP_HOST'], '', includes_url());
            wp_enqueue_script('steal', plugins_url('steal/steal.production.js?mp', __FILE__), null, null);
            wp_localize_script('steal', 'motopress',
                array(
                    'ajaxUrl' => admin_url('admin-ajax.php'),
                    'wpJQueryUrl' => $wpIncludesUrl . 'js/jquery/',
                    'wpCssUrl' => $wpIncludesUrl . 'css/',
                    'pluginVersion' => $motopressSettings['plugin_version'],
                    'pluginVersionParam' => '?ver=' . $motopressSettings['plugin_version'],
                    'nonces' => array(
                        'motopress_get_wp_settings' => wp_create_nonce('wp_ajax_motopress_get_wp_settings'),
                        'motopress_get_list' => wp_create_nonce('wp_ajax_motopress_get_list'),
                        'motopress_get_loop' => wp_create_nonce('wp_ajax_motopress_get_loop'),
                        'motopress_save' => wp_create_nonce('wp_ajax_motopress_save'),
                        'motopress_reset' => wp_create_nonce('wp_ajax_motopress_reset'),
                        'motopress_set_page_template' => wp_create_nonce('wp_ajax_motopress_set_page_template'),
                        'motopress_duplicate_template' => wp_create_nonce('wp_ajax_motopress_duplicate_template'),
                        'motopress_save_static_content' => wp_create_nonce('wp_ajax_motopress_save_static_content'),
                        'motopress_get_static_content' => wp_create_nonce('wp_ajax_motopress_get_static_content'),
                        'motopress_get_static_block' => wp_create_nonce('wp_ajax_motopress_get_static_block'),
                        'motopress_get_sidebar' => wp_create_nonce('wp_ajax_motopress_get_sidebar'),
                        'motopress_get_wrapper' => wp_create_nonce('wp_ajax_motopress_get_wrapper')
                    )
                )
            );
        }
    }
}

function copyParentFiles() {
    global $motopressSettings;
    global $isSupportedTheme;
    global $theme;

    $errors = array();

    if ($isSupportedTheme) {
        $parent = $theme->parent();
        if ($parent) { //if child theme
            $skip = array('.', '..');

            $parentPath = $motopressSettings['theme_root'] . '/' . $parent->get_stylesheet();
            $parentFiles = array_diff(scandir($parentPath), $skip);

            $childPath = $motopressSettings['theme_root'] . '/' . $motopressSettings['current_theme'];
            $childFiles = array_diff(scandir($childPath), $skip);

            $skippedFiles = array('comments', 'filterable-portfolio-loop', 'footer', 'functions', 'header', 'options', 'searchform', 'sidebar', 'slider', 'title');
            $patternTemplates = '/^(?!^' . implode('|^', $skippedFiles) . ').+\.php$/is';

            $parentTemplates = preg_grep($patternTemplates, $parentFiles);
            $childTemplates = preg_grep($patternTemplates, $childFiles);

            $diffTemplates = array_diff($parentTemplates, $childTemplates);
            if (!empty($diffTemplates)) {
                copyFiles($diffTemplates, $parentPath, $childPath, $errors);
            }

            //wrapper
            $parentWrapperPath = $motopressSettings['theme_root'] . '/' . $parent->get_stylesheet() . '/wrapper';
            $childWrapperPath = $motopressSettings['theme_wrapper_root'];

            $diffWrapperFiles = getDiffFiles($parentWrapperPath, $childWrapperPath, $skip, $errors);
            if (!empty($diffWrapperFiles)) {
                copyFiles($diffWrapperFiles, $parentWrapperPath, $childWrapperPath, $errors);
            }

            //static
            $parentStaticPath = $motopressSettings['theme_root'] . '/' . $parent->get_stylesheet() . '/static';
            $childStaticPath = $motopressSettings['theme_static_root'];

            $diffStaticFiles = getDiffFiles($parentStaticPath, $childStaticPath, $skip, $errors);
            if (!empty($diffStaticFiles)) {
                copyFiles($diffStaticFiles, $parentStaticPath, $childStaticPath, $errors);
            }

            //loop
            /*
            $parentLoopPath = $motopressSettings['theme_root'] . '/' . $parent->get_stylesheet() . '/loop';
            $childLoopPath = $motopressSettings['theme_loop_root'];

            $diffLoopFiles = getDiffFiles($parentLoopPath, $childLoopPath, $skip, $errors);
            if (!empty($diffLoopFiles)) {
                copyFiles($diffLoopFiles, $parentLoopPath, $childLoopPath, $errors);
            }
            */

            //woo
            $parentWooPath = $motopressSettings['theme_root'] . '/' . $parent->get_stylesheet() . '/woocommerce';
            $childWooPath = $motopressSettings['theme_root'] . '/' . $motopressSettings['current_theme'] . '/woocommerce';

            $diffWooFiles = getDiffFiles($parentWooPath, $childWooPath, $skip, $errors);
            if (!empty($diffWooFiles)) {
                copyFiles($diffWooFiles, $parentWooPath, $childWooPath, $errors);
            }
        }
    }
    return $errors;
}

function getDiffFiles($parentPath, $childPath, $skip, &$errors) {
    global $lang;

    if (file_exists($parentPath)) {
        $parentFiles = array_diff(scandir($parentPath), $skip);
        if (!file_exists($childPath)) {
            if (@mkdir($childPath)) {
                if (!is_writable($childPath)) {
                    if (@!chmod($childPath, 0777)) {
                        $errors[] = strtr($lang->changePermissionsError, array('%name%' => $childPath));
                    }
                }
            } else {
                $errors[] = strtr($lang->createError, array('%name%' => $childPath));
                return array();
            }
        }
        $childFiles = array_diff(scandir($childPath), $skip);

        $pattern = '/.+\.php$/is';

        $parentFiles = preg_grep($pattern, $parentFiles);
        $childFiles = preg_grep($pattern, $childFiles);

        $diffFiles = array_diff($parentFiles, $childFiles);

        return $diffFiles;
    }
}

function copyFiles($files, $parentPath, $childPath, &$errors) {
    global $lang;

    if (is_writable($childPath)) {
        foreach ($files as $file) {
            $source = $parentPath . '/' . $file;
            $dest = $childPath . '/' . $file;
            $copy = @copy($source, $dest);
            if ($copy) {
                if (!is_writable($dest)) {
                    if (@!chmod($dest, 0777)) {
                        $errors[] = strtr($lang->changePermissionsError, array('%name%' => $dest));
                    }
                }
            } else {
                $errors[] = strtr($lang->copyError, array('%source%' => $source, '%dest%' => $dest));
            }
        }
    } else {
        $errors[] = strtr($lang->notWritable, array('%name%' => $childPath));
    }
}

//ajax handlers
require_once $motopressSettings['plugin_root'].'/'.$motopressSettings['plugin_name'].'/includes/getWpSettings.php';
add_action('wp_ajax_motopress_get_wp_settings', 'motopressGetWpSettings');

require_once $motopressSettings['plugin_root'].'/'.$motopressSettings['plugin_name'].'/includes/getList.php';
add_action('wp_ajax_motopress_get_list', 'motopressGetList');

require_once $motopressSettings['plugin_root'].'/'.$motopressSettings['plugin_name'].'/includes/getLoop.php';
add_action('wp_ajax_motopress_get_loop', 'motopressGetLoop');

require_once $motopressSettings['plugin_root'].'/'.$motopressSettings['plugin_name'].'/includes/save.php';
add_action('wp_ajax_motopress_save', 'motopressSave');

require_once $motopressSettings['plugin_root'].'/'.$motopressSettings['plugin_name'].'/includes/reset.php';
add_action('wp_ajax_motopress_reset', 'motopressReset');

require_once $motopressSettings['plugin_root'].'/'.$motopressSettings['plugin_name'].'/includes/setPageTemplate.php';
add_action('wp_ajax_motopress_set_page_template', 'motopressSetPageTemplate');

require_once $motopressSettings['plugin_root'].'/'.$motopressSettings['plugin_name'].'/includes/duplicateTemplate.php';
add_action('wp_ajax_motopress_duplicate_template', 'motopressDuplicateTemplate');

require_once $motopressSettings['plugin_root'].'/'.$motopressSettings['plugin_name'].'/includes/saveStaticContent.php';
add_action('wp_ajax_motopress_save_static_content', 'motopressSaveStaticContent');

require_once $motopressSettings['plugin_root'].'/'.$motopressSettings['plugin_name'].'/includes/getStaticContent.php';
add_action('wp_ajax_motopress_get_static_content', 'motopressGetStaticContent');

require_once $motopressSettings['plugin_root'].'/'.$motopressSettings['plugin_name'].'/includes/getStaticBlock.php';
add_action('wp_ajax_motopress_get_static_block', 'motopressGetStaticBlock');

require_once $motopressSettings['plugin_root'].'/'.$motopressSettings['plugin_name'].'/includes/getSidebar.php';
add_action('wp_ajax_motopress_get_sidebar', 'motopressGetSidebar');

require_once $motopressSettings['plugin_root'].'/'.$motopressSettings['plugin_name'].'/includes/getWrapper.php';
add_action('wp_ajax_motopress_get_wrapper', 'motopressGetWrapper');

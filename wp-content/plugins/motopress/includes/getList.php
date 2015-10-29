<?php
function motopressGetList() {
    require_once 'verifyNonce.php';
    require_once 'settings.php';
    require_once 'access.php';

    $list = array();

    $list['sidebar_list'] = getSidebarList();
    $list['header_list'] = getHeaderList();
    $list['footer_list'] = getFooterList();
    $list['static_list'] = getStaticList();
    $list['loop_list'] = getLoopList();

    echo json_encode($list);
    exit;
}

function getSidebarList() {
    global $motopressSettings;
    global $wp_registered_sidebars;

    $sidebarList = array();

    //dynamic
    foreach ($wp_registered_sidebars as $sidebar) {
        $sidebarList[$sidebar['id']] = array('name' => $sidebar['name'], 'type' => 'dynamic');
    }

    //static
    $sidebarRegExp = '/^sidebar(-.+)*\.php$/is';

    $childPath = $motopressSettings['theme_root'] . '/' . $motopressSettings['current_theme'];
    $childFiles = array_diff(scandir($childPath), array('.', '..'));
    $childSidebars = preg_grep($sidebarRegExp, $childFiles);
    if (!empty($childSidebars)) {
        foreach ($childSidebars as $childSidebar) {
            $childContent = file_get_contents($childPath . '/' . $childSidebar);
            $childName = getAnnotationName($childContent, 'sidebar');
            if ($childName) {
                $sidebarList[$childSidebar] = array('name' => $childName, 'type' => 'static');
            }
        }
    }

    $parentPath = $motopressSettings['theme_root'] . '/' . $motopressSettings['parent_theme'];
    $parentFiles = array_diff(scandir($parentPath), array('.', '..'));
    $parentSidebars = preg_grep($sidebarRegExp, $parentFiles);
    if (!empty($parentSidebars)) {
        foreach ($parentSidebars as $parentSidebar) {
            if (!array_key_exists($parentSidebar, $sidebarList)) {
                $parentContent = file_get_contents($parentPath . '/' . $parentSidebar);
                $parentName = getAnnotationName($parentContent, 'sidebar');
                if ($parentName) {
                    $sidebarList[$parentSidebar] = array('name' => $parentName, 'type' => 'static');
                }
            }
        }
    }

    uasort($sidebarList, 'cmp');
    return $sidebarList;
}

function getHeaderList() {
    global $motopressSettings;
    $dir = $motopressSettings['theme_wrapper_root'];
    $headerList = array();

    if (is_dir($dir)) {
        $wrapperFiles = array_diff(scandir($dir), array('.', '..'));

        if (!empty($wrapperFiles)) {
            $headerFiles = preg_grep('/header.*\.php$/is', $wrapperFiles);
            foreach ($headerFiles as $file) {
                $content = file_get_contents($dir . '/' . $file);
                $name = getAnnotationName($content, 'wrapper');
                if ($name) {
                    $headerList[$file] = $name;
                }
            }
        }
    }
    asort($headerList);
    return $headerList;
}

function getFooterList() {
    global $motopressSettings;
    $dir = $motopressSettings['theme_wrapper_root'];
    $footerList = array();

    if (is_dir($dir)) {
        $wrapperFiles = array_diff(scandir($dir), array('.', '..'));

        if (!empty($wrapperFiles)) {
            $footerFiles = preg_grep('/footer.*\.php$/is', $wrapperFiles);
            foreach ($footerFiles as $file) {
                $content = file_get_contents($dir . '/' . $file);
                $name = getAnnotationName($content, 'wrapper');
                if ($name) {
                    $footerList[$file] = $name;
                }
            }
        }
    }
    asort($footerList);
    return $footerList;
}

function getStaticList() {
    global $motopressSettings;
    $dir = $motopressSettings['theme_static_root'];
    $staticList = array();

    if (is_dir($dir)) {
        $staticFiles = array_diff(scandir($dir), array('.', '..'));

        if (!empty($staticFiles)) {
            foreach ($staticFiles as $file) {
                $content = file_get_contents($dir . '/' . $file);
                $name = getAnnotationName($content, 'static');
                if ($name) {
                    $staticList[$file] = $name;
                }
            }
        }
    }
    asort($staticList);
    return $staticList;
}

function getLoopList() {
    global $motopressSettings;
    $dir = $motopressSettings['theme_loop_root'];
    $parentDir = $motopressSettings['parent_theme_loop_root'];
    $loopList = array();
    $loopFiles = array();

    if (is_dir($dir)) {
        $loopFiles = array_diff(scandir($dir), array('.', '..'));
        if (!empty($loopFiles)) {
            foreach ($loopFiles as $file) {
                $content = file_get_contents($dir . '/' . $file);
                $name = getAnnotationName($content, 'loop');
                if ($name) $loopList[$file] = $name;
            }
        }
    }
    if (is_dir($parentDir) and $parentDir != $dir) {
        $parentLoopFiles = array_diff(scandir($parentDir), array('.', '..'));
        if (!empty($parentLoopFiles)) {
            foreach ($parentLoopFiles as $file) {
                if (in_array($file, $loopFiles)) continue;
                $content = file_get_contents($parentDir . '/' . $file);
                $name = getAnnotationName($content, 'loop');
                if ($name and !in_array($name, $loopList)) $loopList[$file] = $name;
            }
        }
    }

    asort($loopList);
    return $loopList;
}

function getAnnotationName($content, $type) {
    if (isset($content, $type) && !empty($content) && !empty($type)) {
        $pattern = '/\*\s*' . $type . '\s+name:\s*([^\*]+)\s*\*/is';
        preg_match($pattern, $content, $matches);
        if (!empty($matches[1])) {
            $name = trim($matches[1]);
            return $name;
        }
    }
    return false;
}

function cmp($a, $b) {
    return strcmp($a['name'], $b['name']);
}
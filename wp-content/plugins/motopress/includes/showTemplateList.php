<?php
$pageTemplates = get_page_templates();
$woo = array_search('woocommerce.php', $pageTemplates);
if ($woo) unset($pageTemplates[$woo]);

$defaultPageTemplate = get_page_template();
if ($defaultPageTemplate) {
    $pageTemplates['Default Page'] = 'page.php';
}
ksort($pageTemplates);

echo '<select id="motopress-page-templates">';
foreach ($pageTemplates as $name => $file) {
    echo '<option value="' . $file . '">' . $name . '</option>';
}
echo '</select>';
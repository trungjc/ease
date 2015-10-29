<?php
function motopressOptions() {
    global $motopressSettings;
    global $lang;

    if (isset($_POST['language']) && !empty($_POST['language'])) {
        $language = $_POST['language'];
        update_option('motopress-language', $language);
        $motopressSettings['lang'] = $language;
        $lang = getLanguageDict();
    }
    echo '<div class="wrap">';
    echo '<h2>'.$lang->motopressOptions.'</h2>';
    echo '<form actoin="" method="POST">';
    echo '<table class="form-table"><tbody>';
    echo '<tr>';
    echo '<th scope="row"><label for="language">'.$lang->language.'</label></th>';
    echo '<td><select class="motopress-language" name="language" id="language">';

    $curLang = get_option('motopress-language');

    $languageFileList = glob(plugin_dir_path(__FILE__) . 'lang/*.json');
    foreach ($languageFileList as $path) {
        $file = basename($path);
        $fileContents = file_get_contents($path);
        $fileContentsJSON = json_decode($fileContents);
        $languageName = $fileContentsJSON->{'name'};
        $selected = ($file == $curLang) ? ' selected' : '';
        echo '<option value="'.$file.'"'.$selected.'>' . $languageName . '</option>';
    }
    echo '</select></td>';
    echo '</tr>';
    echo '</tbody></table>';
    echo '<p class="submit"><input type="submit" class="button-primary" value="'.$lang->save.'" /></p>';
    echo '</form>';
    echo '</div>';
}
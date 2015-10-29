<?php
require_once 'settings.php';

class InitTemplate {
    const MOTOPRESS_SINGLE_QUOTE = 'motopress_single_quote';
    const MOTOPRESS_DOUBLE_QUOTE = 'motopress_double_quote';
    const MOTOPRESS_LT = 'motopress_lt';
    const MOTOPRESS_RT = 'motopress_rt';

    private $templateDir = null;
    private $themeFileList = array();

    function __construct() {
        global $motopressSettings;
        $this->templateDir = $motopressSettings['theme_root'] . '/' . $motopressSettings['current_theme'] . '/';

        $this->getThemeFileList();
    }

    private function getThemeFileList() {
        $this->themeFileList['main'] = glob($this->templateDir . '*.php');
        //$this->themeFileList['loop'] = glob($this->templateDir . 'loop/*.php');
        $this->themeFileList['wrapper'] = glob($this->templateDir . 'wrapper/*.php');
        $this->themeFileList['woocommerce'] = glob($this->templateDir . 'woocommerce/*.php');

        foreach ($this->themeFileList['main'] as &$file) $file = basename($file);
        $this->themeFileList['main'] = preg_grep('/^(?>(?!^PIE\.php|^functions\.php|^options\.php|^header\.php|^footer\.php|^comments\.php|^slider\.php|^filterable-portfolio-loop\.php).)*$/i', $this->themeFileList['main']);
        foreach ($this->themeFileList['main'] as &$file) $file = $this->templateDir . $file;
    }

    public function identify() {
        $lang = getLanguageDict();
        $errors = array();
        $xpath = null;
        $fileContent = '';
        $dom = new DOMDocument('1.0', 'utf-8');
        libxml_use_internal_errors(true);

        foreach ($this->themeFileList as $type => $list) {
                foreach ($list as $file) {
                if (is_writable($file)) {
                    $isIdentified = true;
                    $fileContent = file_get_contents($file);
                    if(!empty($fileContent)) {
                        $fileContent = self::preFix($fileContent, $type);
                        $dom->loadHTML($fileContent);
                        libxml_clear_errors();
                        $xpath = new DOMXpath($dom);

                        foreach ($xpath->query('//div[contains(@class, "span") and @data-motopress-wrapper-type]') as $el) {
                            if (!$el->hasAttribute('data-motopress-id') or !$el->getAttribute('data-motopress-id')) {
                                $el->setAttribute('data-motopress-id', uniqid());
                                $isIdentified = false;
                            }
                        }
                        foreach ($xpath->query('//div[contains(@class, "span") and not(@data-motopress-wrapper-type)] | //div[contains(@class, "row")] | //div[contains(@class, "motopress-inactive")]') as $el) {
                                if (!$el->hasAttribute('data-motopress-id') or !$el->getAttribute('data-motopress-id')) {
                                    $el->setAttribute('data-motopress-id', uniqid());
                                    $isIdentified = false;
                                }
                                if (!$el->hasAttribute('data-motopress-file') or !$el->getAttribute('data-motopress-file')) {
                                    if ($type == 'main') {
                                        $el->setAttribute('data-motopress-file', basename($file));
                                    } else {
                                        $el->setAttribute('data-motopress-file', $type . '/' . basename($file));
                                    }
                                    $isIdentified = false;
                                }
    //                        }
                        }

                        if (!$isIdentified) {
                            $fileContent = preg_replace('~<(?:!DOCTYPE|/?(?:html|body))[^>]*>\s*~i', '', $dom->saveHTML());
                            $fileContent = self::postFix($fileContent);
                            $fileContent = self::removeEmptyLines($fileContent);
                            file_put_contents($file, $fileContent);
                        }
                    }
                } else {
                    $errors[] = strtr($lang->notWritable, array('%name%' => $file));
                }
            }
        }
        return $errors;
    }

    static function reinit($fileContent, $fileName, $type) {
        $xpath = null;
        $dom = new DOMDocument('1.0', 'utf-8');
        libxml_use_internal_errors(true);

        if(!empty($fileContent)) {
            $fileContent = self::preFix($fileContent);
            $dom->loadHTML($fileContent);
            libxml_clear_errors();
            $xpath = new DOMXpath($dom);

            foreach ($xpath->query('//div[contains(@class, "span") and @data-motopress-wrapper-type]') as $el) {
                $el->setAttribute('data-motopress-id', uniqid());
            }
            foreach ($xpath->query('//div[contains(@class, "span")] | //div[contains(@class, "row")] | //div[contains(@class, "motopress-inactive")]') as $el) {
//            foreach ($xpath->query('//div[contains(@class, "span") and not(@data-motopress-wrapper-type)] | //div[contains(@class, "row")]') as $el) {
                $el->setAttribute('data-motopress-id', uniqid());
                if (!$el->hasAttribute('data-motopress-wrapper-type')) {
                    if ($type == 'main') {
                        $el->setAttribute('data-motopress-file', basename($fileName));
                    } else {
                        $el->setAttribute('data-motopress-file', $type . '/' . basename($fileName));
                    }
                } else if ($el->getAttribute('data-motopress-wrapper-type') == 'content') {
                    if ($type == 'main') $el->setAttribute('data-motopress-wrapper-file', basename($fileName));
                }
            }

            $fileContent = preg_replace('~<(?:!DOCTYPE|/?(?:html|body))[^>]*>\s*~i', '', $dom->saveHTML());
            $fileContent = self::postFix($fileContent);
        }

        return $fileContent;
    }

    static function preFix($fileContent, $fileType = null) {
        // Get `Template Name`
        $annotationsArr = self::getAnnotations($fileContent);

        // Set `Default Loop` if NULL (in wrapper file)
        /*if ($fileType == 'wrapper' and is_null($annotationsArr['default_loop'])) {
            $loopMatches = null;
            preg_match('/data-motopress-loop-file=[\'"]loop\/([^\'"]+)[\'"]/is', $fileContent, $loopMatches);
            if (!empty($loopMatches[1])) {
                $annotationsArr['default_loop'] = 'Default Loop: loop/' . $loopMatches[1];
            }
        }*/

        // Remove php comments /*...*/
        $fileContent = self::removePhpComment($fileContent);

        // Remove empty php tags
        $fileContent = self::removeEmptyPhp($fileContent);

        // Set `Template Name`
        $fileContent = self::setAnnotations($fileContent, $annotationsArr);

        // Replace
        $outputPhp = null;
        preg_match_all('/(\<\?php|\<\?)(.+?)(\?\>)/s', $fileContent, $outputPhp);

        foreach ($outputPhp[2] as $index => $str) {
            // Remove php if empty
            if (!trim($str)) {
                $fileContent = str_replace($outputPhp[0][$index], '', $fileContent);
                continue;
            }

            // Replace attributes
            if (strlen($str) >= 11) {
                $trimStr = trim($str);
                $phpSubStr = substr($trimStr, 0 , 11);
                if ($phpSubStr == 'post_class(') {
                    $newStr = 'temp="[temp[' . $outputPhp[0][$index] . ']temp]"';
                    $fileContent = str_replace($outputPhp[0][$index], $newStr, $fileContent);
                }
            }

            // Replace symbols
            $oldStr = $str;
            $str = str_replace(
                array("'", '"', '<', '>'),
                array(
                    self::MOTOPRESS_SINGLE_QUOTE,
                    self::MOTOPRESS_DOUBLE_QUOTE,
                    self::MOTOPRESS_LT,
                    self::MOTOPRESS_RT
                ),
                $str
            );
            $fileContent = str_replace($oldStr, $str, $fileContent);
        }

        return $fileContent;
    }

    static function postFix($fileContent) {
        $fileContent = htmlspecialchars_decode($fileContent);
        $outputPhp = null;
        preg_match_all('/(\<\?[php]?)(.+?)(\?\>)/s', $fileContent, $outputPhp);
        foreach ($outputPhp[2] as $str) {
            $oldStr = $str;
            $str = str_replace(
                array(
                    self::MOTOPRESS_SINGLE_QUOTE,
                    self::MOTOPRESS_DOUBLE_QUOTE,
                    self::MOTOPRESS_LT,
                    self::MOTOPRESS_RT
                ),
                array("'", '"', '<', '>'),
                $str
            );
            $fileContent = str_replace($oldStr, $str, $fileContent);
        }
        $fileContent = preg_replace('/(temp="\[temp\[)(.*?)(\]temp\]")/s', '${2}', $fileContent);
        $fileContent = urldecode($fileContent);
        return $fileContent;
    }

    static function getAnnotations($fileContent) {
        $annotations = array(
            'template_name' => 'Template Name',
            'wrapper_name' => 'Wrapper Name',
            'default_loop' => 'Default Loop'
        );
        foreach ($annotations as $type => $name) {
            $templateName = null;
            preg_match_all('/'.$name.':(.*)/', $fileContent, $templateName);
            if (count($templateName[0])) {
                $annotations[$type] = trim($templateName[0][0]);
                $endPos = strpos($annotations[$type], "*/");
                if ($endPos) $annotations[$type] = trim(substr($annotations[$type], 0, $endPos));
            } else {
                $annotations[$type] = null;
            }
        }
        return $annotations;
    }

    static function setAnnotations($fileContent, $annotationsArr) {
        $flag = false;
        $annotation = '<?php'. PHP_EOL .'/**' . PHP_EOL;
        foreach ($annotationsArr as $value) {
            if ($value) {
                $flag = true;
                $annotation .= '* ' . $value . PHP_EOL;
            }
        }
        $annotation .= '*/'. PHP_EOL .'?>' . PHP_EOL;
        if ($flag) $fileContent = $annotation . $fileContent;
        return $fileContent;
    }

    static function removePhpComment($fileContent) {
        return preg_replace('/\/\*.+?\*\//s', '', $fileContent);
    }

    static function removeEmptyPhp($fileContent) {
        $outputPhp = null;
        preg_match_all('/(\<\?php|\<\?)(.*?)(\?\>)/s', $fileContent, $outputPhp);

        foreach ($outputPhp[2] as $index => $str) {
            if (!trim($str)) {
                $fileContent = str_replace($outputPhp[0][$index], '', $fileContent);
                continue;
            }
        }
        return $fileContent;
    }

    static function removeEmptyLines($text) {
        return preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $text);
    }
}

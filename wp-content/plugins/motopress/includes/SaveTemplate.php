<?php
require_once 'verifyNonce.php';
require_once 'settings.php';
require_once 'access.php';
require_once 'functions.php';
require_once 'InitTemplate.php';

class SaveTemplate {
    private $page = null;
    private $data = null;

    private $templateDir = null;
    private $oldDoms = array();
    private $newDoms = array();
    private $dom = null;
    private $xpath = null;
    private $contentDom = null;
    private $contentXpath = null;

    function __construct($page, $data) {
        if (!$page or !$data) setError();
        $data = stripslashes($data);
        if ($page == 'default') $page = 'page.php';
        $this->page = trim($page);
        $this->data = trim($data);
        global $motopressSettings;
        $this->templateDir = $motopressSettings['theme_root'] . '/' . $motopressSettings['current_theme'] . '/';

        libxml_use_internal_errors(true);

        $this->mainInit();
        $this->initOldDocs();
        $this->initNewDocs();
        $this->preFixDataFile();
        $this->replaceHtmlWithPhp();
        $this->postFixDataFile();
        $this->save();

        libxml_clear_errors();
    }

    private function mainInit() {
        $this->dom = new DOMDocument('1.0', 'utf-8');
        if (!$this->dom->loadHTML($this->data)) setError();
        $this->xpath = new DOMXpath($this->dom);

        $this->contentDom = new DOMDocument('1.0', 'utf-8');
        $this->contentXpath = new DOMXpath($this->contentDom);
    }

    private function initOldDocs() {
        $this->oldDoms[$this->page]['dom'] = new DOMDocument('1.0', 'utf-8');
        $content = file_get_contents($this->templateDir . $this->page);
        $this->oldDoms[$this->page]['annotations'] = InitTemplate::getAnnotations($content);
        $content = InitTemplate::preFix($content);
        if (!$this->oldDoms[$this->page]['dom']->loadHTML($content)) setError();
        $this->oldDoms[$this->page]['xpath'] = new DOMXpath($this->oldDoms[$this->page]['dom']);

        $pageArr = array();
        foreach ($this->xpath->query('//div[@data-motopress-wrapper-type]') as $el) {
            $file = $el->getAttribute('data-motopress-wrapper-file');
            if ($file and $file != $this->page and file_exists($this->templateDir . $file)) {
                if (!in_array($file, $pageArr)) $pageArr[] = $file;
            }
        }
        foreach ($this->oldDoms[$this->page]['xpath']->query('//div[@data-motopress-wrapper-type]') as $el) {
            $file = $el->getAttribute('data-motopress-wrapper-file');
            if ($file and $file != $this->page and file_exists($this->templateDir . $file)) {
                if (!in_array($file, $pageArr)) $pageArr[] = $file;
            }
        }

        foreach ($pageArr as $file) {
            $this->oldDoms[$file]['dom'] = new DOMDocument('1.0', 'utf-8');
            $content = file_get_contents($this->templateDir . $file);
            $this->oldDoms[$file]['annotations'] = InitTemplate::getAnnotations($content);
            $content = InitTemplate::preFix($content);
            if (!$this->oldDoms[$file]['dom']->loadHTML($content)) setError();
            $this->oldDoms[$file]['xpath'] = new DOMXpath($this->oldDoms[$file]['dom']);
        }
    }

    private function initNewDocs() {
        $this->newDoms[$this->page]['dom'] = new DOMDocument('1.0', 'utf-8');
        if (!$this->newDoms[$this->page]['dom']->loadHTML($this->data)) setError();
        $this->newDoms[$this->page]['xpath'] = new DOMXpath($this->newDoms[$this->page]['dom']);

        $newNodes = $this->xpath->query('//div[contains(@class, "span") and @data-motopress-wrapper-type and @data-motopress-wrapper-file="'.$this->page.'"]');
        if ($newNodes->length) {
            $newNode = $this->contentDom->importNode($newNodes->item(0), true);
            $this->contentDom->appendChild($newNode);
            $this->contentXpath = new DOMXpath($this->contentDom);
        }

        foreach ($this->newDoms[$this->page]['xpath']->query('//div[contains(@class, "span") and @data-motopress-wrapper-type]') as $el) {
            $id = $el->getAttribute('data-motopress-id');
            $file = $el->getAttribute('data-motopress-wrapper-file');
            $type = $el->getAttribute('data-motopress-wrapper-type');

            if (!$id) $id = uniqid();

            if ($file and !$el->hasAttribute('data-motopress-new')) {
                if ($file != $this->page) {
                    $this->newDoms[$file]['dom'] = new DOMDocument('1.0', 'utf-8');
                    $newNodes = $this->newDoms[$this->page]['xpath']->query('//div[@data-motopress-id="'.$id.'"]/div[contains(@class, "row")]');
                    for ($i = 0; $i < $newNodes->length; $i++) {
                        $newNode = $this->newDoms[$file]['dom']->importNode($newNodes->item($i), true);
                        $this->newDoms[$file]['dom']->appendChild($newNode);
                    }
                    $this->newDoms[$file]['xpath'] = new DOMXpath($this->newDoms[$file]['dom']);
                }
            } else if ($type == 'content') {
                $el->setAttribute('data-motopress-id' , $id);
                $el->setAttribute('data-motopress-wrapper-file' , $this->page);
            } else {
                $el->setAttribute('data-motopress-id' , $id);
                if (!$file) {
                    $file = uniqid('wrapper/wrapper-') . '.php';
                    $el->setAttribute('data-motopress-wrapper-file' , $file);
//                    $el->setAttribute('data-motopress-new' , 1);
                }

                $this->newDoms[$file]['dom'] = new DOMDocument('1.0', 'utf-8');
                $newNodes = $this->newDoms[$this->page]['xpath']->query('//div[@data-motopress-id="'.$id.'"]/div[contains(@class, "row")]');
                for ($i = 0; $i < $newNodes->length; $i++) {
                    $newNode = $this->newDoms[$file]['dom']->importNode($newNodes->item($i), true);
                    $this->newDoms[$file]['dom']->appendChild($newNode);
                }
                $this->newDoms[$file]['xpath'] = new DOMXpath($this->newDoms[$file]['dom']);
            }
        }
        
        // Remove header and footer wrappers from template
        $headerNode = $this->newDoms[$this->page]['xpath']->query('//*[contains(@class, "motopress-wrapper") and contains(@class, "header")]');
        $footerNode = $this->newDoms[$this->page]['xpath']->query('//*[contains(@class, "motopress-wrapper") and contains(@class, "footer")]');
        if ($headerNode->length) {
            $headerNode = $headerNode->item(0);
            $headerNode->parentNode->removeChild($headerNode);
        }
        if ($footerNode->length) {
            $footerNode = $footerNode->item(0);
            $footerNode->parentNode->removeChild($footerNode);
        }
        
        // Remove HTML comments from template
        foreach ($this->newDoms[$this->page]['xpath']->query('//comment()') as $comment) {
            $comment->parentNode->removeChild($comment);
        }
    }

    private function preFixDataFile() {
        foreach ($this->newDoms as $file => $newDom) {
            foreach ($newDom['xpath']->query('//div[contains(@class, "span") and not(@data-motopress-wrapper-type)] | //div[contains(@class, "row")] | //div[contains(@class, "motopress-inactive")]') as $el) {
                if (!$el->hasAttribute('data-motopress-id')) {
                    $el->setAttribute('data-motopress-id', uniqid());
                }
                if (!$el->hasAttribute('data-motopress-file')) {
                    $el->setAttribute('data-motopress-file', $file);
                }
            }
        }
    }

    private function postFixDataFile() {
        foreach ($this->newDoms as $file => $newDom) {
            foreach ($newDom['xpath']->query('//div[contains(@class, "span") and not(@data-motopress-wrapper-type)] | //div[contains(@class, "row")] | //div[contains(@class, "motopress-inactive")]') as $el) {
                if (!$el->hasAttribute('data-motopress-id')) {
                    $el->setAttribute('data-motopress-id', uniqid());
                }
                $el->setAttribute('data-motopress-file', $file);
            }
        }
    }

    private function replaceHtmlWithPhp() {
        foreach ($this->newDoms as $file => $newDom) {
            // if template file
            if ($file == $this->page) {
                $layoutWrappers = $newDom['xpath']->query('//div[contains(@class, "span") and @data-motopress-wrapper-type]');
                for ($i = 0; $i < $layoutWrappers->length; $i++) {
                    $id = $layoutWrappers->item($i)->getAttribute('data-motopress-id');
                    $wrapperFile = $layoutWrappers->item($i)->getAttribute('data-motopress-wrapper-file');
                    $isNew = $layoutWrappers->item($i)->hasAttribute('data-motopress-new');

                    if ($id) {
                        // if not content wrapper
                        if ($wrapperFile != $this->page) {
                            if ($isNew) {
                                $layoutWrappers->item($i)->removeAttribute('data-motopress-new');
                                $this->replaceNewNode($newDom, $layoutWrappers->item($i), $wrapperFile);
                            } else {
                                $this->replaceNode($newDom, $layoutWrappers->item($i), 'span', $file, $id);
                            }
                        // if content wrapper
                        } else {
                            $this->replaceDeepestNodes($newDom, 'row');
                            $this->replaceDeepestNodes($newDom, 'span');
                        }
                    }
                }
                // For not bootstrap blocks
                $this->replaceNodesByClass($newDom, 'motopress-inactive');
                
            // if not template file
            } else {
                $this->replaceDeepestNodes($newDom, 'span');
            }
        }
    }

    private function save() {
        foreach ($this->newDoms as $file => &$newDom) {
            $fileContent = $newDom['dom']->saveHTML();
            if (!$fileContent) setError();
            $fileContent = InitTemplate::postFix($fileContent);
            $fileContent = preg_replace('~<(?:!DOCTYPE|/?(?:html|body))[^>]*>\s*~i', '', $fileContent);
            if ($file == $this->page) {
                $fileContent = '<?php get_header(); ?>' . PHP_EOL . $fileContent . PHP_EOL . '<?php get_footer(); ?>';
            }
            $fileContent = InitTemplate::removeEmptyLines($fileContent);
            $fileContent = InitTemplate::setAnnotations($fileContent, $this->oldDoms[$file]['annotations']);
            if (!file_put_contents($this->templateDir.$file, $fileContent)) setError();
        }
    }

//--------------------------------------------------------------------------

    private function replaceDeepestNodes($newDom, $nodeClass) {
        $childNodeClass = null;
        if ($nodeClass == 'span') $childNodeClass = 'row';
        else $childNodeClass = 'span';

        $deeps = $newDom['xpath']->query('//div[contains(@class, "'.$nodeClass.'") and not(./div[contains(@class, "'.$childNodeClass.'")]) and @data-motopress-id]');
        for ($j = 0; $j < $deeps->length; $j++) {
            $nodeType = $deeps->item($j)->getAttribute('data-motopress-type');
            $nodeId = $deeps->item($j)->getAttribute('data-motopress-id');
            $nodeFile = $deeps->item($j)->getAttribute('data-motopress-file');

            switch ($nodeType) {
                case 'dynamic-sidebar':
//                    $this->createDynamicSidebar($newDom, $deeps->item($j));
                    $this->createSidebar($newDom, $deeps->item($j), 'dynamic');
                    break;
                
                case 'static-sidebar':
                    $this->createSidebar($newDom, $deeps->item($j), 'static');
                    break;

                case 'static':
                    $this->createTemplatePart($newDom, $deeps->item($j), 'static');
                    break;

                case 'loop':
                    $this->createTemplatePart($newDom, $deeps->item($j), 'loop');
                    break;

                default:
                    $this->replaceNode($newDom, $deeps->item($j), $nodeClass, $nodeFile, $nodeId);
                    break;
            }
        }
    }

    private function replaceNodesByClass($newDom, $nodeClass) {
        $deeps = $newDom['xpath']->query('//div[contains(@class, "'.$nodeClass.'") and @data-motopress-id]');
        for ($j = 0; $j < $deeps->length; $j++) {
            $nodeType = $deeps->item($j)->getAttribute('data-motopress-type');
            $nodeId = $deeps->item($j)->getAttribute('data-motopress-id');
            $nodeFile = $deeps->item($j)->getAttribute('data-motopress-file');
            switch ($nodeType) {
                case 'static':
                    $this->createTemplatePart($newDom, $deeps->item($j), 'static');
                    break;
                default:
                    $this->replaceNode($newDom, $deeps->item($j), $nodeClass, $nodeFile, $nodeId);
                    break;
            }
        }
    }

    private function replaceNewNode($newDom, $wrapper, $wrapperFile) {
        $nodeInnerHtml = $this->createWrapperCall($wrapperFile);
        if ($nodeInnerHtml) {
            $replaceNode = $newDom['dom']->createElement('div');
            if ($wrapper->hasAttributes()) {
                foreach ($wrapper->attributes as $attr) {
                    $replaceNode->setAttribute($attr->nodeName, $attr->nodeValue);
                }
            }
            $wrapper->parentNode->replaceChild($replaceNode, $wrapper);
            $frag = $newDom['dom']->createCDATASection($nodeInnerHtml);
            $replaceNode->appendChild($frag);
        }
    }

    private function replaceNode($newDom, $curDeeps, $nodeClass, $nodeFile, $nodeId) {
        $nodeInnerHtml = null;
        if ($this->oldDoms[$nodeFile]) {
            $newNode = $this->oldDoms[$nodeFile]['xpath']->query('//div[contains(@class, "'.$nodeClass.'") and @data-motopress-id="'.$nodeId.'"]');
            if ($newNode->length) {
                $nodeInnerHtml = $this->innerHTML($newNode->item(0));
            }
        }
        if ($nodeInnerHtml) {
            $replaceNode = $newDom['dom']->createElement('div');
            if ($curDeeps->hasAttributes()) {
                foreach ($curDeeps->attributes as $attr) {
                    $replaceNode->setAttribute($attr->nodeName, $attr->nodeValue);
                }
            }
            $curDeeps->parentNode->replaceChild($replaceNode, $curDeeps);
            $frag = $newDom['dom']->createCDATASection($nodeInnerHtml);
            $replaceNode->appendChild($frag);
        }
    }

    private function createWrapperCall($file) {
        $file = str_replace('.php', '', $file);
        return '<?php get_template_part("'.$file.'"); ?>';
    }

    private function createSidebar($newDom, $curDeeps, $type) {
        $sidebarId = $curDeeps->getAttribute('data-motopress-sidebar-id');
        if ($type == 'dynamic' and (!$sidebarId or $sidebarId == 'default')) return false;
        $replaceNode = $newDom['dom']->createElement('div');
        if ($curDeeps->hasAttributes()) {
            foreach ($curDeeps->attributes as $attr) {
                $replaceNode->setAttribute($attr->nodeName, $attr->nodeValue);
            }
        }
        $curDeeps->parentNode->replaceChild($replaceNode, $curDeeps);
        if ($type == 'dynamic') {
            $nodeInnerHtml = '<?php dynamic_sidebar("'.$sidebarId.'"); ?>';
        } else {
            if ($sidebarId) {
                $nodeInnerHtml = '<?php get_sidebar("'.$sidebarId.'"); ?>';
            } else {
                $nodeInnerHtml = '<?php get_sidebar(); ?>';
            }
        }
        $frag = $newDom['dom']->createCDATASection($nodeInnerHtml);
        $replaceNode->appendChild($frag);
    }

    private function createTemplatePart($newDom, $curDeeps, $type) {
        $partFile = $curDeeps->getAttribute('data-motopress-'.$type.'-file');
        if (!$partFile) {
            $partFile = uniqid($type.'/'.$type.'-') . '.php';
            file_put_contents($this->templateDir . $partFile, '');
            chmod($this->templateDir . $partFile, 0777);
        }

        $replaceNode = $newDom['dom']->createElement('div');
        if ($curDeeps->hasAttributes()) {
            foreach ($curDeeps->attributes as $attr) {
                $replaceNode->setAttribute($attr->nodeName, $attr->nodeValue);
            }
        }
        $curDeeps->parentNode->replaceChild($replaceNode, $curDeeps);
        $nodeInnerHtml = '<?php get_template_part("'.str_replace('.php', '', $partFile).'"); ?>';
        $frag = $newDom['dom']->createCDATASection($nodeInnerHtml);
        $replaceNode->appendChild($frag);
    }

    private function innerHTML($el) {
        $doc = new DOMDocument();
        $doc->appendChild($doc->importNode($el, true));
        $html = trim($doc->saveHTML());
        $tag = $el->nodeName;
        return preg_replace('@^<' . $tag . '[^>]*>|</' . $tag . '>$@', '', $html);
    }
}
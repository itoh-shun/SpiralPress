<?php

namespace SpiralPress\App\Library;

class CodexToHtml {
    
    private $templates = null;
    private $beautify = false;

    function __construct($templates, $beautify) {
        $this->templates = $templates;
        $this->beautify = $beautify;
    }

    public function render($blocks) {
        $result = [];

        foreach ($blocks as $block) {
            if (array_key_exists($block['type'], $this->templates)) {
                $template = $this->templates[$block['type']];
                $data = $block['data'];
                ksort($data);
                $tunes = $block['tunes'];
                $html = call_user_func_array($template, $data);
                $result[] = "<div class='{$tunes["textVariant"]}'>{$html}</div>";
            }
        }

        $html = implode($result);
        
        return $html;
    }
    
}
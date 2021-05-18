<?php
namespace Modules\Core\TemplateLibraries;

use Xcart\App\Template\TemplateLibrary;

class TextHighlightLibrary extends TemplateLibrary
{
    /**
     * @name words_highlight
     * @kind modifier
     * @return string
     * @throws \Exception
     */
    public static function wordsHighlight($str, $words, $tag = 'em', $class = '')
    {
        preg_match_all('~\w+~', $words, $m);
        if(!$m) {
            return $str;
        }
        $re = '~\\b(' . implode('|', $m[0]) . ')\\b~i';
        return preg_replace($re, "<$tag class='{$class}'>$0</{$tag}>", $str);
    }
}
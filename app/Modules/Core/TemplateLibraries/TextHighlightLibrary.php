<?php
namespace Modules\Core\TemplateLibraries;


use Xcart\App\Template\TemplateLibrary;

class TextHighlightLibrary extends TemplateLibrary
{
    /**
     * @name text_highlight
     * @kind modifier
     * @return string
     */
    public static function textHighlight($str, $search, $tag = 'em')
    {

        $founden = self::searchSubstring($str, trim($search));

        $str = substr($str, strlen($founden));
        $founden = str_replace('{content}', $founden, self::parseTag($tag));

        return $founden . $str;
    }


    /**
     * @name words_highlight
     * @kind modifier
     * @return string
     */
    public static function wordsHighlight($str, $search, $tag = 'em')
    {

        if (is_string($search)) {

        }

        $founden = self::searchSubstring($str, trim($search));

        $str = substr($str, strlen($founden));
        $founden = str_replace('{content}', $founden, self::parseTag($tag));

        return $founden . $str;
    }

    private static function searchSubstring($str, $search)
    {
        if (strpos($str, $search) === false) {
            return self::searchSubstring($str, substr($search, 0, -1));
        }
        else {
            return $search;
        }
    }

    private static function parseTag($str, $content = '')
    {
        $id = '';
        $property = [];
        $classes = [];

        preg_match_all('/\[([\w-]+(=(.*?)|))\]/', $str, $t_property);
        if (!empty($t_property[1])) {
            $property = $t_property[1];

            $str = preg_replace('/(\[([\w-]+(=(.*?)|))\])/', '', $str);
        }

        preg_match_all('/\.([\w-]+)/', $str, $t_classes);
        if (!empty($t_classes[1])) {
            $classes = $t_classes[1];

            $str = preg_replace('/(\.([\w-]+))/', '', $str);
        }

        preg_match('/\#([\w-]+)/', $str, $t_id);
        if (!empty($t_id[1])) {
            $id = $t_id[1];

            $str = preg_replace('/(\#([\w-]+))/', '', $str);
        }

        $tag = "<{$str}";
        if ($id) {
            $tag .= " id='{$id}'";
        }
        if ($classes) {
            $tag .= " class='".implode(' ', $classes)."'";
        }
        if ($property) {
            $tag .= implode(' ', $property);
        }
        $tag .= ">{content}</{$str}>";

        return $tag;
    }
}
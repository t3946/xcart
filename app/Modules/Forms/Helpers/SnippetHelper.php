<?php


namespace Modules\Forms\Helpers;


use Modules\Forms\Models\SnippetModel;

class SnippetHelper
{
    public static function getSnippets($value, $params): array
    {
        $result = [];

        if (preg_match_all('/{{(.*)}}/', $value, $matches) && $matches[1]) {
            /** @var SnippetModel $snippet */
            foreach (SnippetModel::objects()->filter(['code__in' => $matches[1]]) as $snippet) {
                $result["{{{$snippet->code}}}"] = $snippet->render($params);
            }
        }
        return $result;
    }

    public static function render($value, $params)
    {
        $snippets = self::getSnippets($value, $params);
        return str_replace(array_keys($snippets), array_values($snippets), $value);
    }
}
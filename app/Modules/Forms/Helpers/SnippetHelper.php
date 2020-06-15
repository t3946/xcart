<?php


namespace Modules\Forms\Helpers;


use Modules\Forms\Models\SnippetModel;

class SnippetHelper
{
    public static function getSnippets($params): array
    {
        $result = [];
        /** @var SnippetModel $snippet */
        foreach (SnippetModel::objects() as $snippet)
        {
            $result["{{{$snippet->code}}}"] = $snippet->render($params);
        }
        return $result;
    }

    public static function render($value, $params)
    {
        $snippets = self::getSnippets($params);
        return str_replace(array_keys($snippets),array_values($snippets), $value);
    }
}
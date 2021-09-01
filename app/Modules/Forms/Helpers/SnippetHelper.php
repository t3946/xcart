<?php


namespace Modules\Forms\Helpers;


use Modules\Forms\Models\SnippetModel;
use Xcart\App\Main\Xcart;

class SnippetHelper
{
    public static function parseSnippetCodes(string $value): array
    {
        preg_match_all('/{{(.*)}}/U', $value, $matches);
        return $matches[1] ?? [];
    }

    public static function getSnippets(string $value, array $params): array
    {
        $result = [];

        if ($codes = self::parseSnippetCodes($value)) {
            foreach (SnippetModel::objects()->filter(['code__in' => $codes]) as $snippet) {
                $result["{{{$snippet->code}}}"] = $snippet->render($params);
            }
        }
        return $result;
    }

    public static function renderSnippets(string $value, array $snippets)
    {
        return str_replace(array_keys($snippets), array_values($snippets), $value);
    }

    public static function render(string $value, array $params)
    {
        $params = array_merge([
            'site' => Xcart::app()->getModule('Sites')->getSite(),
            'user' => Xcart::app()->user,
        ], $params);
        return self::renderSnippets($value, self::getSnippets($value, $params));
    }
}
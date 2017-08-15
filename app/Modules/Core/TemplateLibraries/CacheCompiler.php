<?php
namespace Modules\Core\TemplateLibraries;

use Fenom\Tag;
use Fenom\Tokenizer;
use Modules\Sites\SitesModule;
use Xcart\App\Helpers\ClassNames;
use Xcart\App\Main\Xcart;

class CacheCompiler
{
    use ClassNames;

    private static $_keys = [];

    public static function blockCacheOpen(Tokenizer $tokens, Tag $tag)
    {
        $time = null;
        $key = '';
        $forDomain = true;
        $params = $tag->tpl->parseParams($tokens);

        if (empty($params['key'])) {
            throw new \RuntimeException("Invalid block cache key");
        }

        if (isset(self::$_keys[$params['key']])) {
            throw new \RuntimeException("Cache key already used");
        }

        $key = str_replace("'",'',$params['key']);

        self::$_keys[$key] = true;

        if (!empty($params['time'])) {
            if (is_integer($params['time'])) {
                $time = $params['time'];
            }
            if (is_string($params['time'])) {
                $time = strtotime($params['time']);
            }
        }

        if (isset($params['forDomain'])) {
            $forDomain = (bool)$params['forDomain'];
        }

        if ($forDomain) {
            /** @var SitesModule $module */
            $module = Xcart::app()->getModule('Sites');
            $key .= ':site='.$module->getSite()->storefrontid;
            $key = "'{$key}'";
        }

        $tag['key'] = $key;
        $tag['time'] = $time ?: 'null';

        return '';
    }

    public static function blockCacheClose(Tokenizer $tokens, Tag $tag)
    {
        return '
if (!$output = \Xcart\App\Main\Xcart::app()->cache->get('. $tag['key'] .')) {
    ob_start();
    ?>'. $tag->getContent() .'<?php
    $output = ob_get_clean();
    \Xcart\App\Main\Xcart::app()->cache->set('. $tag['key'] .', $output, ' . $tag['time'] . ');
}
echo $output;
';
    }

}
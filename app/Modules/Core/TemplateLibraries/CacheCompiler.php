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

    private static $instance;
    private static $_keys = [];


    public static function getInstance()
    {
        return self::$instance ?: self::$instance = new static();
    }

    public static function blockCacheOpen(Tokenizer $tokens, Tag $tag)
    {
        $params = $tag->tpl->parseParams($tokens);

        if (empty($params['key'])) {
            throw new \RuntimeException("Invalid block cache key");
        }

        $time = null;
        $forDomain = true;
        $key = $params['key'];

        if (isset($params['forDomain'])) {
            $forDomain = (bool)$params['forDomain'];
        }

        //@TODO: Multiple block run
//        if (isset(self::$_keys[$key])) {
//            throw new \RuntimeException("Cache key already used");
//        }

        if (!empty($params['time'])) {
            if (is_integer($params['time'])) {
                $time = $params['time'];
            }
            if (is_string($params['time'])) {
                $time = strtotime($params['time']);
            }
        }

        $tag['key'] = $key;
        $tag['time'] = $time ?: 'null';
        $tag['forDomain'] = $forDomain ? 'true' : 'false';

        self::$_keys[$key] = true;

        return '';
    }

    public static function blockCacheClose(Tokenizer $tokens, Tag $tag)
    {
        $code = /** @lang PHP */'
<?php
$app = \Xcart\App\Main\Xcart::app();
$key = '.$tag['key'].';
if ('.$tag['forDomain'].' && $module = $app->getModule("Sites"))
{
    $key .= ":site" .$module->getSite()->storefrontid;
}

if (!$output = $app->cache->get($key)) {
    ob_start();
    ?>'. $tag->getContent() .'<?php
    $app->cache->set('. $tag['key'] .', $output = ob_get_clean(), ' . $tag['time'] . ');
}
echo $output; ?>
';
//        d($code);

        $tag->replaceContent($code);
        return;
    }
}
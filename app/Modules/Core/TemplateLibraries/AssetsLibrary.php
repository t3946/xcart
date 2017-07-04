<?php
namespace Modules\Core\TemplateLibraries;


use Xcart\App\Helpers\Paths;
use Xcart\App\Template\TemplateLibrary;

class AssetsLibrary extends TemplateLibrary
{
    public static $assets = [
        'js' => [],
        'css' => [],
        'unknown' => [],
    ];

    /**
     * @kind block
     * @name set_asset_block
     * @return void
     */
    public static function setAsset(array $params = [], $data)
    {
        $type = 'unknown';
        if (!empty($params['type']) && key_exists($params['type'], self::$assets)) {
            $type = $params['type'];
        }

        if (!empty($params['key'])) {
            self::$assets[$type][$params['key']] = $data;
        }
        else {
            self::$assets[$type][] = $data;
        }
    }

    /**
     * @kind function
     * @name get_assets
     * @return string
     */
    public static function getAssets(array $params = [])
    {
        $type = 'unknown';
        if (!empty($params['type']) && key_exists($params['type'], self::$assets)) {
            $type = $params['type'];
        }

        return implode('', self::$assets[$type]);
    }
}
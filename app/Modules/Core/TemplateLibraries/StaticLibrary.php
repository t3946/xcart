<?php
namespace Modules\Core\TemplateLibraries;


use Xcart\App\Helpers\Paths;
use Xcart\App\Template\TemplateLibrary;

class StaticLibrary extends TemplateLibrary
{
    protected static function getFrontendVersionsDir()
    {
        return Paths::get('www.static.frontend.versions');
    }

    protected static function getBackendVersionsDir()
    {
        return Paths::get('www.static.backend.versions');
    }

    protected static function getVersionFromContent($content)
    {
        $space = strpos($content, ' ');
        if ($space !== false) {
            return substr($content, 0, $space);
        }
        return null;
    }

    protected static function getVersion($file, $default = 1)
    {
        if (is_file($file) && ($content = file_get_contents($file)) && ($version = self::getVersionFromContent($content))) {
            return $version;
        }
        return $default;
    }

    /**
     * @kind function
     * @name frontend_css_version
     * @return int|void
     */
    public static function getFrontendCssVersion()
    {
        return self::getVersion(self::getFrontendVersionsDir() . DIRECTORY_SEPARATOR . 'css.yml');
    }

    /**
     * @kind function
     * @name frontend_js_version
     * @return int|void
     */
    public static function getFrontendJsVersion()
    {
        return self::getVersion(self::getFrontendVersionsDir() . DIRECTORY_SEPARATOR . 'js.yml');
    }

    /**
     * @kind function
     * @name backend_css_version
     * @return int|void
     */
    public static function getBackendCssVersion()
    {
        return self::getVersion(self::getBackendVersionsDir() . DIRECTORY_SEPARATOR . 'css.yml');
    }

    /**
     * @kind function
     * @name backend_js_version
     * @return int|void
     */
    public static function getBackendJsVersion()
    {
        return self::getVersion(self::getBackendVersionsDir() . DIRECTORY_SEPARATOR . 'js.yml');
    }
}
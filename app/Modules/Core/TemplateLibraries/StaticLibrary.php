<?php
namespace Modules\Core\TemplateLibraries;


use Xcart\App\Helpers\Paths;
use Xcart\App\Template\TemplateLibrary;

class StaticLibrary extends TemplateLibrary
{
    static $frontend = [];
    static $backend = [];

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
        $result = [];

        $rows = preg_split("/\n/", $content);
        foreach ($rows as $row) {
            $tmp = explode('  ', $row);
            if (count($tmp) == 2) {
                $result[trim($tmp[0])] = strtolower(trim($tmp[1], ". \t\n\r\0\x0B"));
            }
        }

        return $result;
    }

    protected static function getVersions($file)
    {
        if (is_file($file) && ($content = file_get_contents($file)) && ($versions = self::getVersionFromContent($content))) {
            return $versions;
        }
        return [];
    }

    protected static function initFrontend()
    {
        if (empty(self::$frontend))
        {
            $dir = self::getFrontendVersionsDir() . DIRECTORY_SEPARATOR;

            self::$frontend = array_merge(self::$frontend, self::getVersions($dir . 'css.yml'));
            self::$frontend = array_merge(self::$frontend, self::getVersions($dir . 'js.yml'));
            self::$frontend = array_merge(self::$frontend, self::getVersions($dir . 'vendor_js.yml'));
        }
    }

    protected static function initBackend()
    {
        if (empty(self::$frontend))
        {
            $dir = self::getBackendVersionsDir() . DIRECTORY_SEPARATOR;

            self::$frontend = array_merge(self::$frontend, self::getVersions($dir . 'css.yml'));
            self::$frontend = array_merge(self::$frontend, self::getVersions($dir . 'js.yml'));
        }
    }

    /**
     * @kind function
     * @name frontend_version
     * @return int
     */
    public static function getFrontendVersion($params)
    {
        $resource = $params['resource'];

        self::initFrontend();
        $resource = strtolower($resource);

        foreach (self::$frontend as $version => $file) {
            if (strpos($file, $resource) !== false) {
                return $version;
            }
        }

        return 1;
    }

    /**
     * @kind function
     * @name inline
     * @return string
     */
    public static function getInline($params)
    {
        $resource = $params['file'];

        return file_get_contents($resource);
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
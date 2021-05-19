<?php

namespace Modules\Translate\Classes;

/**
 * https://www.i18next.com/
 */
class I18nextManager
{
    /**
     * convert *.po file in i18next plugin format
     * @param string $locale language code of covert po file
     */
    public static function convert(string $locale): void
    {
        // define convert command
        $module = $_SERVER[ 'DOCUMENT_ROOT' ] . '/static/node_modules/i18next-conv/bin/';
        define('FORMAT_TRANSLATE_COMMAND', "node $module -l ru -s %s -t %s");

        // determine convert source and target
        $lang_dir = $_SERVER[ 'DOCUMENT_ROOT' ] . '/../app/Modules/Translate/lang';
        $source = "$lang_dir/$locale.po";
        $target = "$lang_dir/$locale.i18next.json";

        // convert
        $command = sprintf(FORMAT_TRANSLATE_COMMAND, $source, $target);
        exec($command);
    }

    /**
     * get i18next translates file content
     * @param string $locale -- lang code as "ru", "en", "de" etc.
     * @param bool $minify -- remove spaces and break lines from result
     * @return string json content
     */
    public static function getTranslates(string $locale, bool $minify = true): string
    {
        $path = $_SERVER[ 'DOCUMENT_ROOT' ] . "/../app/Modules/Translate/lang/$locale.i18next.json";

        if (!file_exists($path)) {
            self::convert($locale);
        }

        $json = file_get_contents($path);

        if ($minify) {
            $json = json_encode(json_decode($json));
        }

        return $json;
    }
}
<?php

namespace Modules\Translate\Classes;

use Symfony\Component\Translation\Loader\PoFileLoader;
use Symfony\Component\Translation\Translator;
use Throwable;
use Xcart\App\Main\Xcart;

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
        $file_loader = new PoFileLoader();
        $translator = new Translator($locale);
        $translator->addLoader('po', $file_loader);
        $resource_path = Xcart::app()->getModule('Translate')->getPath() . "/lang/{$locale}.po";
        $translator->addResource('po', $resource_path, $locale, 'messages');
        try {
            $catalogue = $translator->getCatalogue();
            $language = $catalogue->all()['messages'];
        } catch (Throwable $exception) {
            $language = [];
        }

        return json_encode($language, true);
    }
}
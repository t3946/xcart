<?php

namespace Modules\Translate\Classes;

use Modules\Translate\Interfaces\I18nextManagerInterface;
use Symfony\Component\Translation\Loader\PoFileLoader;
use Symfony\Component\Translation\Translator;
use Throwable;
use Xcart\App\Exceptions\UnknownPropertyException;
use Xcart\App\Main\Xcart;

/**
 * https://www.i18next.com/
 */
class I18nextManager implements I18nextManagerInterface
{

    /**
     * convert *.po file in i18next plugin format
     * @param string $locale language code of covert po file
     */
    public static function convert(string $locale): void
    {
        // define convert command
        $module = $_SERVER['DOCUMENT_ROOT'] . '/static/node_modules/i18next-conv/bin/';
        define('FORMAT_TRANSLATE_COMMAND', "node $module -l ru -s %s -t %s");

        // determine convert source and target
        $lang_dir = $_SERVER['DOCUMENT_ROOT'] . '/../app/Modules/Translate/lang';
        $source = "$lang_dir/$locale.po";
        $target = "$lang_dir/$locale.i18next.json";

        // convert
        $command = sprintf(FORMAT_TRANSLATE_COMMAND, $source, $target);
        exec($command);
    }

    /**
     * get i18next translates file content
     * @param string $locale -- lang code as "ru", "en", "de" etc.
     * @return string json content
     * @throws UnknownPropertyException
     */
    public static function getTranslates(string $locale): string
    {
        $file_loader = new PoFileLoader();
        $translator = new Translator($locale);
        $translator->addLoader('po', $file_loader);
        $resource_path = Xcart::app()->getModule('Translate')->getPath() . "/lang/$locale.po";
        $translator->addResource('po', $resource_path, $locale, 'messages');
        try {
            $catalogue = $translator->getCatalogue();
            $language = [];
            foreach ($catalogue->all()['messages'] as $key => $value) {
                $ar_lang = explode('|', $key);
                if (count($ar_lang) > 1 && !empty($value)) {
                    $ar_lang_key = array_combine(self::LANG_SETTINGS[$locale], explode('|', $value));
                    foreach ($ar_lang_key as $type => $message) {
                        $language["$ar_lang[0]_$type"] = $message;
                    }
                } else {
                    $language[$key] = $value;
                }
            }
        } catch (Throwable $exception) {
            $language = [];
        }
        return json_encode($language, JSON_FORCE_OBJECT);
    }
}
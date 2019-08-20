<?php

namespace Xcart\App\Translate;

use Symfony\Component\Translation\Loader\PoFileLoader;
use Symfony\Component\Translation\Translator;
use Xcart\App\Main\Xcart;

/**
 * Class Translate DUMMY
 *
 * @package Xcart\App\Translate
 */
class Translate
{
    private static $_self;
    private $translator;

    public function __construct()
    {
        $this->translator = new Translator('ru');
        $this->translator->addLoader('po', new PoFileLoader());
        $this->translator->addResource('po', Xcart::app()->getModule('Translate')->getPath().'/lang/ru_RU.po', 'ru', 'messages');
    }

    /**
     * @return $this
     */
    public static function getInstance()
    {
        if (!static::$_self) {
            static::$_self = new static;
        }

        return static::$_self;
    }

    public function t($dict, $text, $params = [])
    {
        return $this->stringReplacement($text, $params);
    }


    public function stringReplacement($str, array $params = [])
    {
        if ($params) {
            //$str = $this->translator->trans($str, $params);
        }

        $str_o =  $this->translator->trans($str, $params, 'messages', 'ru');
        if ($str_o === '') {
            $str_o = $str;
        }
        return $str_o;
    }

}
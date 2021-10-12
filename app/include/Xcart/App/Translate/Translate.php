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
        $site = Xcart::app()->getModule('Sites')->getSite();
        $l = $site->lang->lang_code ?? 'en';
        $this->translator = new Translator($l);
        $this->translator->addLoader('po', new PoFileLoader());
        $this->translator->addResource('po', Xcart::app()->getModule('Translate')->getPath()."/lang/{$l}.po", $l, 'messages');
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
        $site = Xcart::app()->getModule('Sites')->getSite();

        $str_o =  $this->translator->trans($str, $params, 'messages', $site->lang->lang_code ?? 'en');

        if ($str_o === '') {
            $str_o = $str;
        }

        return $str_o;
    }
}
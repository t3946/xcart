<?php

namespace Modules\Translate\Interfaces;

interface I18nextManagerInterface
{
    const ONE_SETTINGS = 'one';
    const FEW_SETTINGS = 'few';
    const MANY_SETTINGS = 'many';
    const OTHER_SETTINGS = 'other';

    const LANG_SETTINGS = [
        'ru' => [
            self::ONE_SETTINGS,
            self::FEW_SETTINGS,
            self::MANY_SETTINGS
        ],
        'en' => [
            self::ONE_SETTINGS,
            self::OTHER_SETTINGS
        ]
    ];

    public static function convert(string $locale): void;

    public static function getTranslates(string $locale): string;
}
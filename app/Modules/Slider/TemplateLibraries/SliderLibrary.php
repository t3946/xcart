<?php

namespace Modules\Slider\TemplateLibraries;


use Xcart\App\Template\TemplateLibrary;
use Xcart\App\Traits\RenderTrait;

class SliderLibrary extends TemplateLibrary
{
    use RenderTrait;

    /**
     * @kind function
     * @name renderSlider
     * @return string
     */
    public static function renderSlider($params)
    {
        if ($params) {
            $slider = self::getSliderData(is_array($params) ? current($params) : $params);

            static::renderTemplate($slider['template'], [
                'slides' => $slider['data'],
            ]);
        }
    }


    /**
     * @kind function
     * @name getSlider
     * @return array
     */
    public static function getData($params) :? array
    {
        if ($params) {
            return self::getSliderData(is_array($params) ? current($params) : $params);
        }
    }

    private static function getSliderData(string $code) :? array
    {
        switch ($code) {
            case 'promo-sly-slider':
                return [
                    'name' => 'promo-slider',
                    'template' => 'slider/promo_sly_slider.tpl',
                    'data' => self::getSliderDataByStore($code),
                ];
        }

        return null;
    }

    private static function getSliderDataByStore($code)
    {
        return [];
    }
}
<?php

namespace Modules\Slider\TemplateLibraries;


use Modules\Sites\Models\SiteModel;
use Xcart\App\Main\Xcart;
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

            return static::renderTemplate($slider['template'], [
                'slides' => $slider['data'],
                'slider_name' => $slider['name'],
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
        /** @var SiteModel $site */
        $site = Xcart::app()->getModule('Sites')->getSite();
        $code = strtolower($site->code);

        return [
            [
                'title' => 'Everyday unbeatable',
                'description' => 'LOW PRICES up to 50% off',
                'image' => "/static/frontend/dist/images/slider/{$code}/promo.jpg",
            ],
        ];
    }
}
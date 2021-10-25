<?php

namespace Modules\Slider\TemplateLibraries;


use Modules\Goods\Helpers\PromotionalProductsHelper;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Helpers\Paths;
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
    public static function renderSlider($params): string
    {
        if ($params) {

            $slider = self::getSliderData(is_array($params) ? current($params) : $params);

            return static::renderTemplate($slider['template'], [
                'slides' => $slider['data'],
                'slider_name' => $slider['name'],
            ]);
        }
        return '';
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
        return [];
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
        $site = Xcart::app()->getModule('Sites')->getSite();
        $site_code = strtolower($site->code);
        $img = "/static/frontend/dist/images/slider/{$site_code}/promo.jpg";
        if (file_exists(Paths::get('www').$img)) {
            return [
                [
                    'title' => 'Everyday unbeatable',
                    'description' => 'LOW PRICES up to 50% off',
                    'image' => $img,
                ]
            ];
        }
        $models = PromotionalProductsHelper::getSliderProduct();
        foreach ($models as $model) {
            $res[] = [
                'title' => 'Everyday unbeatable',
                'description' => 'LOW PRICES up to 50% off',
                'image' => PromotionalProductsHelper::getSliderImage($model),
                'link' => $model->getAbsoluteUrl(true)
            ];
        }
        return $res ?? [];
    }
}
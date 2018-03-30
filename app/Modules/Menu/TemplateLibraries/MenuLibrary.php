<?php

namespace Modules\Menu\TemplateLibraries;

use Mindy\QueryBuilder\Expression;
use Mindy\QueryBuilder\Q\QOr;
use Modules\Goods\Models\CategoryModel;
use Modules\Goods\Models\ProductModel;
use Xcart\App\Main\Xcart;
use Xcart\App\Template\TemplateLibrary;
use Xcart\App\Traits\RenderTrait;

class MenuLibrary extends TemplateLibrary
{
    use RenderTrait;

    public static $template = 'menu/menu.tpl';

    /**
     * @kind function
     * @name get_menu
     * @return string
     */
    public static function getMenu($params)
    {
        if (empty($params['code'])) {
            return '';
        }

        $code = $params['code'];
        $template = self::$template;

        if (!empty($params['template'])) {
            $template = $params['template'];
        }

        if ($items = self::getData($code)) {
            return self::renderTemplate($template, [
                'items' => $items,
            ]);
        }

        return '';
    }

    /**
     * @kind accessorFunction
     * @name get_menu_items
     * @return array
     */
    public static function getMenuItems($code)
    {
        if (!$code) {
            return [];
        }

        return self::getData($code);
    }

    public static function getData($code)
    {
        if ($code == 'main-menu') {
            return [
                [
                    'url' => '/',
                    'name' => 'Shipping & Delivery',
                    'class' => '',
                    'items' => [],
                ],
                [
                    'url' => '/',
                    'name' => 'Purchase Orders',
                    'class' => '',
                    'items' => [],
                ],
                [
                    'url' => '/',
                    'name' => 'About Us',
                    'class' => '',
                    'items' => [],
                ],
                [
                    'url' => '/',
                    'name' => 'Contact Us',
                    'class' => '',
                    'items' => [],
                ],
                [
                    'url' => '/',
                    'name' => 'Testimonials',
                    'class' => '',
                    'items' => [],
                ],
            ];
        }
        else if ('footer-menu') {
            return [
                [
                    'url' => '/terms-of-use',
                    'name' => 'Terms of use',
                    'items' => []
                ],
                [
                    'url' => '/privacy-policy',
                    'name' => 'Privacy policy',
                    'items' => [],
                ],
                [
                    'url' => '/retail-trust',
                    'name' => 'Retail Trust Terms & Conditions',
                    'items' => [],
                ],
            ];
        }
        return [];
    }
}
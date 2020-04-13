<?php

namespace Modules\Menu\TemplateLibraries;

use Modules\Cart\Components\XCart;
use Modules\Menu\MenuModule;
use Modules\Pages\Helpers\PageHelper;
use Modules\Pages\Models\Page;
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
        if ($params) {
            $template = self::$template;

            if (!is_array($params)) {
                $code = $params;
            } else {
                if (!empty($params['code'])) {
                    $code = $params['code'];
                }

                if (!empty($params['template'])) {
                    $template = $params['template'];
                }
            }

            if ($items = self::getData($code)) {
                return self::renderTemplate($template, [
                    'items' => $items,
                ]);
            }
        }

        return '';
    }

    /**
     * @kind accessorFunction
     * @name get_menu_items
     * @return array
     */
    public static function getMenuItems($params)
    {
        if ($params) {

            return self::getData(is_array($params) ? current($params) : $params);
        }

        return [];
    }

    public static function getData($code)
    {

        switch ($code) {
            case 'main-menu':
                $pages = ['/shipping-delivery', '/purchase-orders', '/about-us'];
                $site_model = \Xcart\App\Main\Xcart::app()->getModule('Sites')->getSite();
                $pages = array_filter(array_map(static function ($p) use($site_model) {
                    if ($model = PageHelper::getPage($p)) {
                        return [
                            'url' => "/{$model->url}",
                            'name' => $model->name,
                            'class' => $model->url === 'purchase-orders' && $site_model->code === 'HC' ? 'stop-corona' : '',
                            'items' => [],
                        ];
                    }
                    return null;
                }, $pages));
                $pages = array_merge($pages, [[
                    'url' => \Xcart\App\Main\Xcart::app()->router->url('main:contact_us_form'),
                    'name' => MenuModule::t('Contact Us'),
                    'class' => '',
                    'items' => [],
                ]]);
                return $pages;

            case 'pages-menu':
                return [
                    [
                        'name' => MenuModule::t('Shopping'),
                        'items' => [
                            [
                                'name' => MenuModule::t('Shipping & Delivery'),
                                'url' => '/shipping-delivery',
                                'items' => [],
                            ],
                            [
                                'name' => MenuModule::t('Our Price Guarantee'),
                                'url' => '/our-price-guarantee',
                                'items' => [],
                            ],
                            [
                                'name' => MenuModule::t('Sales Taxes'),
                                'url' => '/sales-taxes',
                                'items' => [],
                            ],
                        ],
                    ],
                    [
                        'name' => MenuModule::t('Payments'),
                        'items' => [
                            [
                                'name' => MenuModule::t('Safe & Secure Shopping'),
                                'url' => '/safe-and-secure-shopping',
                                'items' => [],
                            ],
                            [
                                'name' => MenuModule::t('Purchase Orders'),
                                'url' => '/purchase-orders',
                                'items' => [],
                            ],
                            /*[
                                'name' => MenuModule::t('Bill Me Later'),
                                'url' => '/bill-me-later',
                                'items' => [],
                            ],*/
                            [
                                'name' => MenuModule::t('Combating eCommerce Fraud'),
                                'url' => '/ecomerce-fraud',
                                'items' => [],
                            ],
                        ],
                    ],
                    [
                        'name' => MenuModule::t('Orders'),
                        'items' => [
                            [
                                'name' => MenuModule::t('Order Communication'),
                                'url' => '/order-communication',
                                'items' => [],
                            ],
                            [
                                'name' => MenuModule::t('Retrieve Order'),
                                'url' => '/retrieve-orders',
                                'items' => [],
                            ],
//                            [
//                                'name' => 'RMA Request',
//                                'url' => 'up',
//                                'items' => [],
//                            ],
                        ],
                    ],
                    [
                        'name' => MenuModule::t('Connect'),
                        'items' => [
                            [
                                'name' => MenuModule::t('Contact Us'),
                                'url' => \Xcart\App\Main\Xcart::app()->router->url('main:contact_us_form'),
                                'items' => [],
                            ],
                            [
                                'name' => MenuModule::t('About Us'),
                                'url' => '/about-us',
                                'items' => [],
                            ],
                            /*[
                                'name' => MenuModule::t('Community'),
                                'url' => '/community',
                                'items' => [],
                            ],*/
                        ],
                    ],
                    [
                        'name' => MenuModule::t('Policies'),
                        'items' => [
                            [
                                'name' => MenuModule::t('Backorder Policy'),
                                'url' => '/backorder-policy',
                                'items' => [],
                            ],
                            [
                                'name' => MenuModule::t('Return Policy'),
                                'url' => '/return-policy',
                                'items' => [],
                            ],
                            [
                                'name' => MenuModule::t('Terms of Use'),
                                'url' => '/terms-of-use',
                                'items' => [],
                            ],
                            [
                                'name' => MenuModule::t('Privacy Policy'),
                                'url' => '/privacy-policy',
                                'items' => [],
                            ],
                        ],
                    ],
                ];
            case 'footer-menu':
                return [
                    [
                        'url' => '/terms-of-use',
                        'name' => MenuModule::t('Terms of Use'),
                        'items' => []
                    ],
                    [
                        'url' => '/privacy-policy',
                        'name' => MenuModule::t('Privacy Policy'),
                        'items' => [],
                    ],
                    /*[
                        'url' => '/retail-trust-terms-and-conditions',
                        'name' => MenuModule::t('Retail Trust Terms & Conditions'),
                        'items' => [],
                    ],*/
                ];
        }

        return [];
    }
}
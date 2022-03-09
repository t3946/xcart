<?php

namespace Modules\Menu\TemplateLibraries;

use Modules\Menu\MenuModule;
use Modules\Pages\Helpers\PageHelper;
use Modules\Sites\Models\SiteMenuModel;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Main\Xcart;
use Xcart\App\Template\TemplateLibrary;
use Xcart\App\Traits\RenderTrait;

class MenuLibrary extends TemplateLibrary
{
    use RenderTrait;

    public static string $template = 'menu/menu.tpl';

    private static function getItemsMenu(string $name_menu): array
    {
        $items = [];
        /** @var SiteModel $site */
        $site = Xcart::app()->getModule('Sites')->getSite();
        $menu_parent = $site->menu_list->get(['menu__name' => "{$name_menu} {$site->lang->lang_code}"]);
        if ($menu_parent) {
            $footer_item = SiteMenuModel::objects()->filter(['root' => $menu_parent->menu->root])->asTree()->all();
            if (!empty($footer_item)) {
                $items = array_map(static function ($item) {
                    $ar_item = [
                        'name' => $item['name'],
                        'items' => array_map(static fn($item_child) => [
                                'name' => $item_child['name'],
                                'items' => [],
                                'url' => "/{$item_child['url']}" ?? '/'
                            ], $item['items']) ?? []
                    ];
                    if (!empty($item['url'])) {
                        $ar_item['url'] = $item['url'];
                    }
                    return $ar_item;
                }, $footer_item[0]['items']);
            }
        }
        return $items;
    }

    /**
     * @kind function
     * @name get_menu
     * @return string
     */
    public static function getMenu($params): string
    {
        if ($params) {
            $template = self::$template;
            $items = self::getItemsMenu($params['code']);
            if (!empty($items)) {
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
    public static function getMenuItems($params): array
    {
        if ($params) {
            return self::getItemsMenu($params);
        }
        return [];
    }


    public static function getData($code)
    {

        switch ($code) {
            case 'main-menu':
                $pages = ['/shipping-delivery', '/purchase-orders', '/about-us'];
                $pages = array_filter(array_map(static function ($p) {
                    if ($model = PageHelper::getPage($p)) {
                        return [
                            'url' => "/{$model->url}",
                            'name' => $model->name,
                            'class' => '',
                            'items' => [],
                        ];
                    }
                    return null;
                }, $pages));
                return array_merge($pages, [[
                    'url' => \Xcart\App\Main\Xcart::app()->router->url('main:contact_us_form'),
                    'name' => MenuModule::t('Contact Us'),
                    'class' => '',
                    'items' => [],
                ]]);

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
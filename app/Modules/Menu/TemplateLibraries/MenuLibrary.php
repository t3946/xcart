<?php

namespace Modules\Menu\TemplateLibraries;

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
            }
            else {
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
            case 'pages-menu':
                return [
                    [
                        'name' => 'Shopping',
                        'items' => [
                            [
                                'name' => 'Shipping & Delivery',
                                'url' => 'shipping-delivery',
                                'items' => [],
                            ],
                            [
                                'name' => 'Our Price Guarantee',
                                'url' => 'our-price-guarantee',
                                'items' => [],
                            ],
                            [
                                'name' => 'Sales Taxes',
                                'url' => 'Sales-taxes',
                                'items' => [],
                            ],
                        ],
                    ],
                    [
                        'name' => 'Payments',
                        'items' => [
                            [
                                'name' => 'Safe & Secure Shopping',
                                'url' => 'up',
                                'items' => [],
                            ],
                            [
                                'name' => 'Purchase Orders',
                                'url' => 'up',
                                'items' => [],
                            ],
                            [
                                'name' => 'Bill Me Later',
                                'url' => 'up',
                                'items' => [],
                            ],
                            [
                                'name' => 'Combating eCommerce Fraud',
                                'url' => 'up',
                                'items' => [],
                            ],
                        ],
                    ],
                    [
                        'name' => 'Orders',
                        'items' => [
                            [
                                'name' => 'Order Communication',
                                'url' => 'up',
                                'items' => [],
                            ],
                            [
                                'name' => 'Retrieve Order',
                                'url' => 'up',
                                'items' => [],
                            ],
                            [
                                'name' => 'RMA Request',
                                'url' => 'up',
                                'items' => [],
                            ],
                        ],
                    ],
                    [
                        'name' => 'Connect',
                        'items' => [
                            [
                                'name' => 'Contact Us',
                                'url' => 'up',
                                'items' => [],
                            ],
                            [
                                'name' => 'About Us',
                                'url' => 'up',
                                'items' => [],
                            ],
                            [
                                'name' => 'Community',
                                'url' => 'up',
                                'items' => [],
                            ],
                            [
                                'name' => 'Link to Us',
                                'url' => 'up',
                                'items' => [],
                            ],
                        ],
                    ],
                    [
                        'name' => 'Policies',
                        'items' => [
                            [
                                'name' => 'Backorder Policy',
                                'url' => 'up',
                                'items' => [],
                            ],
                            [
                                'name' => 'Return Policy',
                                'url' => 'up',
                                'items' => [],
                            ],
                            [
                                'name' => 'Term of Use',
                                'url' => 'up',
                                'items' => [],
                            ],
                            [
                                'name' => 'Privacy Policy',
                                'url' => 'up',
                                'items' => [],
                            ],
                        ],
                    ],
                ];
            case 'footer-menu':
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
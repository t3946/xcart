<?php

namespace Modules\Menu\TemplateLibraries;

use Modules\Cart\Components\XCart;
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
                        'url' => '/shipping-delivery',
                        'name' => 'Shipping & Delivery',
                        'class' => '',
                        'items' => [],
                    ],
                    [
                        'url' => '/purchase-orders',
                        'name' => 'Purchase Orders',
                        'class' => '',
                        'items' => [],
                    ],
                    [
                        'url' => '/about-us',
                        'name' => 'About Us',
                        'class' => '',
                        'items' => [],
                    ],
                    [
                        'url' => \Xcart\App\Main\Xcart::app()->router->url('main:contact_us_form'),
                        'name' => 'Contact Us',
                        'class' => '',
                        'items' => [],
                    ],
                    [
                        'url' => '/testimonials',
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
                                'url' => '/shipping-delivery',
                                'items' => [],
                            ],
                            [
                                'name' => 'Our Price Guarantee',
                                'url' => '/our-price-guarantee',
                                'items' => [],
                            ],
                            [
                                'name' => 'Sales Taxes',
                                'url' => '/sales-taxes',
                                'items' => [],
                            ],
                        ],
                    ],
                    [
                        'name' => 'Payments',
                        'items' => [
                            [
                                'name' => 'Safe & Secure Shopping',
                                'url' => '/safe-and-secure-shopping',
                                'items' => [],
                            ],
                            [
                                'name' => 'Purchase Orders',
                                'url' => '/purchase-orders',
                                'items' => [],
                            ],
                            [
                                'name' => 'Bill Me Later',
                                'url' => '/bill-me-later',
                                'items' => [],
                            ],
                            [
                                'name' => 'Combating eCommerce Fraud',
                                'url' => '/ecomerce-fraud',
                                'items' => [],
                            ],
                        ],
                    ],
                    [
                        'name' => 'Orders',
                        'items' => [
                            [
                                'name' => 'Order Communication',
                                'url' => '/order-communication',
                                'items' => [],
                            ],
                            [
                                'name' => 'Retrieve Order',
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
                        'name' => 'Connect',
                        'items' => [
                            [
                                'name' => 'Contact Us',
                                'url' => '/contactus/',
                                'items' => [],
                            ],
                            [
                                'name' => 'About Us',
                                'url' => '/about-us',
                                'items' => [],
                            ],
                            [
                                'name' => 'Community',
                                'url' => '/community',
                                'items' => [],
                            ],
//                            [
//                                'name' => 'Link to Us',
//                                'url' => 'up',
//                                'items' => [],
//                            ],
                        ],
                    ],
                    [
                        'name' => 'Policies',
                        'items' => [
                            [
                                'name' => 'Backorder Policy',
                                'url' => '/backorder-policy',
                                'items' => [],
                            ],
                            [
                                'name' => 'Return Policy',
                                'url' => '/return-policy',
                                'items' => [],
                            ],
                            [
                                'name' => 'Term of Use',
                                'url' => '/terms-of-use',
                                'items' => [],
                            ],
                            [
                                'name' => 'Privacy Policy',
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
                        'name' => 'Terms of use',
                        'items' => []
                    ],
                    [
                        'url' => '/privacy-policy',
                        'name' => 'Privacy policy',
                        'items' => [],
                    ],
                    [
                        'url' => '/retail-trust-terms-and-conditions',
                        'name' => 'Retail Trust Terms & Conditions',
                        'items' => [],
                    ],
                ];
        }

        return [];
    }
}
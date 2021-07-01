<?php
/**
 * Выводит меню
 */

namespace Modules\Core\Helpers;

use Modules\Core\Models\LanguageModel;
use Modules\Main\Helpers\WorkingTimeHelper;
use Modules\Sites\Helpers\StorageHelper;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Helpers\Paths;
use Xcart\App\Main\Xcart;
use Xcart\App\Module\Module;
use Modules\Dashboard\Stores\OrderSearchStore;

class AdminHelper
{
    private static function routesData()
    {
        $routes_map = [];
        $routes = Xcart::app()->router->getRoutes();

        foreach ($routes as $route) {
            $routes_map[$route[3]] = $route[1];
        }

        StorageHelper::push($routes_map, null, 'routes');
    }

    /**
     * через пробел добавляет SF(store fronts) суффикс к строке
     * @param string $str
     * @return string
     */
    private static function sfPostfix($str)
    {
        return $str . ' ' . LanguageModel::translate('lbl_sf');
    }

    /**
     * построить данные для меню сайд бара
     */
    private static function menuData()
    {
        $new_menu = [];

        if (!Xcart::app()->getUser()->login) {
            StorageHelper::push([], null, 'sidebarMenu');
            return;
        }

        $modules = Xcart::app()->getModulesConfig();

        //новое меню
        foreach ($modules as $name => $config) {
            if (isset($config['class'])) {
                /** @var Module $class */
                $class = $config['class'];
                $moduleMenu = $class::getAdminMenu();
                if ($moduleMenu) {
                    $new_menu[] = [
                        'name' => $class::getVerboseName(),
                        'key' => $name,
                        'class' => $config['class'],
                        'items' => $moduleMenu
                    ];
                }
            }
        }

        //старое меню
        $user = Xcart::app()->user;
        if (!$user->hasRoles(['vrs', 'vrv'])) {
            $old_menu = [
                [
                    "name" => "Management",
                    "links" => [
                        [
                            'name' => (string)LanguageModel::translate('lbl_users'),
                            'route' => '/admin/users.php',
                        ],
                        [
                            'name' => self::sfPostfix(LanguageModel::translate('lbl_categories')),
                            'route' => '/admin/categories.php',
                        ],
                        [
                            'name' => 'Classification',
                            'route' => '/admin/classification.php',
                        ],
                        [
                            'name' => self::sfPostfix('Empty categories'),
                            'route' => '/admin/empty_categories.php',
                        ],
                        [
                            'name' => (string)LanguageModel::translate('lbl_manufacturers'),
                            'route' => '/admin/manufacturers.php?word=num',
                        ],
                        [
                            'name' => (string)LanguageModel::translate('lbl_brands'),
                            'route' => '/admin/brands.php?word=num',
                        ],
                        [
                            'name' => self::sfPostfix(LanguageModel::translate('lbl_admin_menu_products')),
                            'route' => '/admin/search.php',
                        ],
                        [
                            'name' => 'Amazon verification',
                            'route' => '/admin/az_operators.php',
                        ],
                        [
                            'name' => 'Checks deposited',
                            'route' => '/admin/checks_deposited.php',
                        ],
                        [
                            'name' => 'Product questions',
                            'route' => '/admin/product_question_search.php?mode=search&status=all&from_dashboard=Y',
                        ],
                        [
                            'name' => 'Reconciliation',
                            'route' => '/admin/reconciliation.php',
                        ],
                        [
                            'name' => "Reports",
                            'route' => '/admin/reports.php',
                        ],
                        [
                            "name" => self::sfPostfix(LanguageModel::translate("lbl_shipping_methods")),
                            "route" => "/admin/shipping.php",
                        ],
                        [
                            "name" => self::sfPostfix(LanguageModel::translate("lbl_tracking_links")),
                            "route" => "/admin/track_links.php",
                        ],
                        [
                            "name" => self::sfPostfix(LanguageModel::translate("lbl_countries")),
                            "route" => "/admin/countries.php",
                        ],
                        [
                            "name" => self::sfPostfix(LanguageModel::translate("lbl_states")),
                            "route" => "/admin/states.php",
                        ],
                        [
                            "name" => "Taxes",
                            "route" => "/admin/taxes.php",
                        ],
                    ],
                ],
                [
                    "name" => "Administration",
                    "links" => [
                        [
                            "name" => self::sfPostfix(LanguageModel::translate("lbl_cidev_best_search_filter")),
                            "route" => "/admin/cidev_admin_filters.php",
                        ],
                        [
                            "name" => self::sfPostfix(LanguageModel::translate("lbl_general_settings")),
                            "route" => "/admin/configuration.php",
                        ],
                        [
                            "name" => (string)LanguageModel::translate("lbl_languages"),
                            "route" => "/admin/languages.php",
                        ],
                        [
                            "name" => (string)LanguageModel::translate("lbl_membership_levels"),
                            "route" => "/admin/memberships.php",
                        ],
                        [
                            "name" => (string)LanguageModel::translate("lbl_modules"),
                            "route" => "/admin/modules.php",
                        ],
                        [
                            "name" => (string)LanguageModel::translate("lbl_payment_methods"),
                            "route" => "/admin/payment_methods.php",
                        ]
                    ],
                ],
                [
                    "name" => "Obsolete",
                    "links" => [
                        [
                            "name" => self::sfPostfix(LanguageModel::translate('lbl_seed_categories')),
                            "route" => "/admin/seed_categories.php",
                        ],
                        [
                            "name" => (string)LanguageModel::translate("lbl_order_reports"),
                            "route" => "/admin/order_reports.php",
                        ],
                        [
                            "name" => (string)LanguageModel::translate("lbl_grandfathered_products"),
                            "route" => "/admin/grandfathered_products.php",
                        ],
                        [
                            "name" => "Shipping quotes log",
                            "route" => "/admin/shipping_quotes_log.php?mode=search",
                        ],
                        [
                            "name" => "A/B testing",
                            "route" => "/admin/ab_testing.php",
                        ],
                        [
                            "name" => "Backprocess logs",
                            "route" => "/admin/backprocess_logs.php",
                        ],
                        [
                            "name" => (string)LanguageModel::translate("lbl_statistics"),
                            "route" => "/admin/statistics.php",
                        ],
                        [
                            "name" => (string)LanguageModel::translate("lbl_update_inventory"),
                            "route" => "/admin/inv_update_ex.php",
                        ],
                        [
                            "name" => self::sfPostfix('Articles'),
                            "route" => "/admin/categories.php?mode=info",
                        ],
                        [
                            "name" => (string)LanguageModel::translate("lbl_credit_card_types"),
                            "route" => "/admin/card_types.php",
                        ],
                        [
                            "name" => (string)LanguageModel::translate("lbl_static_pages"),
                            "route" => "/admin/pages.php",
                        ],
                        [
                            "name" => (string)LanguageModel::translate("lbl_db_backup_restore"),
                            "route" => "/admin/db_backup.php",
                        ],
                        [
                            "name" => (string)LanguageModel::translate("lbl_edit_templates"),
                            "route" => "/admin/file_edit.php",
                        ],
                        [
                            "name" => self::sfPostfix(LanguageModel::translate('lbl_files')),
                            "route" => "/admin/file_manage.php",
                        ],
                        [
                            "name" => "GEO import",
                            "route" => "/admin/geo_import.php",
                        ],
                        [
                            "name" => (string)LanguageModel::translate("lbl_html_catalog"),
                            "route" => "/admin/html_catalog.php",
                        ],
                        [
                            "name" => (string)LanguageModel::translate("lbl_images_location"),
                            "route" => "/admin/images_location.php",
                        ],
                        [
                            "name" => (string)LanguageModel::translate("lbl_import_export"),
                            "route" => "/admin/import.php",
                        ],
                        [
                            "name" => (string)LanguageModel::translate("lbl_patch_upgrade"),
                            "route" => "/admin/patch.php",
                        ],
                        [
                            "name" => self::sfPostfix(LanguageModel::translate('lbl_cidev_search_by_filter')),
                            "route" => "/admin/cidev_admin_add_filter_to_products.php",
                        ],
                        [
                            "name" => self::sfPostfix(LanguageModel::translate('lbl_speed_bar')),
                            "route" => "/admin/speed_bar.php",
                        ],
                        [
                            "name" => self::sfPostfix("Summary"),
                            "route" => "/admin/general.php",
                        ],
                        [
                            "name" => (string)LanguageModel::translate("lbl_titles"),
                            "route" => "/admin/titles.php",
                        ],
                        [
                            "name" => (string)LanguageModel::translate("lbl_webmaster_mode"),
                            "route" => "/admin/editor_mode.php",
                        ],
                    ],
                ],
            ];
        } else {
            $old_menu = [
                [
                    "name" => "Management",
                    "links" => [
                        [
                            'name' => (string)LanguageModel::translate('lbl_manufacturers'),
                            'route' => '/admin/manufacturers.php?word=num',
                        ],
                    ],
                ]
            ];
        }

        StorageHelper::push([
            "new" => $new_menu,
            "old" => $old_menu ?? null,
        ], null, 'sidebarMenu');
    }

    /**
     * построить данные для шапки сайта
     */
    private static function hatData()
    {
        $data = [];

        //time
        $est_time = date_create('now', timezone_open('EST'));
        $time = [
            [
                "caption" => "CA time",
                "time" => date_create('now', timezone_open('America/Los_Angeles'))->format('H:i'),
                "title" => "California state time",
            ],
            [
                "caption" => "EST time",
                "time" => $est_time->format('H:i'),
                "title" => "Est time",
            ],
            [
                "caption" => "NY time",
                "time" => date_create('now', timezone_open('America/New_York'))->format('H:i'),
                "title" => "New York state time",
            ],
        ];

        $data["time"] = $time;

        //date
        $data["date"] = $est_time->format('F j, Y');

        //holiday
        $holiday = WorkingTimeHelper::getNextHoliday(date_create('now', timezone_open('EST')));
        $days = $holiday->getDaysUntil();
        $data["holiday"] = sprintf("%d day%s until %s", $days, $days > 1 ? 's' : '', (string)$holiday);

        //user
        $data["user"] = Xcart::app()->getUser()->login;

        //site info
        $site = Xcart::app()->getModule('Sites')->getSelectedSite();
        $site_code = strtolower($site->code);
        $data["site"] = [
            "logoUrl" => Paths::get('dist') . "/images/logos/sites/$site_code/logo.svg",
            "name" => $site->getName(),
        ];

        //sites
        $site_list = SiteModel::objects()->exclude(['status' => 'D'])->order(['orderby'])->all();

        foreach ($site_list as $site) {
            switch ($site->code) {
                case 'AT':
                case 'DS':
                    $icon = "dummy.svg";
                    break;
                case 'RD':
                    $icon = "go-freddy.svg";
                    break;
                default:
                    $icon = str_replace(' ', '-', strtolower($site->getName())) . ".svg";
            }

            $data["sites"][] = [
                "name" => $site->getName(),
                "id" => (int)$site->storefrontid,
                "icon" => $icon,
                "code" => $site->code,
            ];
        }

        //quick links
        $data["quickLinks"] = [
            [
                "title" => "Product questions",
                "route" => "/admin/product_question_search.php?mode=search&status=all&from_dashboard=Y"
            ],
            [
                "title" => "Call recordings",
                "route" => "/admin/list/PBX/PBXAdmin"
            ],
            [
                "title" => "Order reports",
                "route" => "/admin/reports"
            ],
            [
                "title" => "Reconciliation / AP & AR",
                "route" => "/admin/reconciliation.php"
            ],
            [
                "title" => "Checks deposited",
                "route" => "/admin/checks_deposited.php"
            ],
            [
                "title" => "Reports",
                "route" => "/admin/reports.php"
            ],
        ];
        StorageHelper::push($data, null, 'hat');
    }

    /**
     * данные об авторизованном пользователе
     */
    private static function userData()
    {
        $user = Xcart::app()->getUser();

        StorageHelper::push($user->id ? $user->getAttributes() : null, null, 'user');
    }

    /**
     * данные по текущему сайту
     */
    private static function siteData()
    {
        $site = Xcart::app()->getModule('Sites')->getSelectedSite();

        StorageHelper::push($site->getAttributes(), null, 'site');
    }


    /**
     * данные по текущему сайту
     */
    private static function appData()
    {
        $data = [
            'manualString' => OrderSearchStore::CONST_MANUAL_STRING,
            'flash' => Xcart::app()->flash->getMessages(),
            'tinymce' => [
                "editorIndex" => Xcart::app()->router->url('editor:index'),
                "editorChanged" => Xcart::app()->router->url('editor:changed'),
            ]
        ];

        StorageHelper::push($data, null, 'app');
    }

    /**
     * Сформировать массивы из данных, которые используются на всех страницах,
     * для последующей передачи на frontend
     */
    public static function buildCommonData()
    {
        self::routesData();
        self::menuData();
        self::hatData();
        self::userData();
        self::siteData();
        self::appData();
    }
}

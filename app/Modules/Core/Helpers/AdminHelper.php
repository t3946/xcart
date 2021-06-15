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

class AdminHelper
{
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
    private static function buildMenuData()
    {
        $menu = [];
        $modules = Xcart::app()->getModulesConfig();

        //новое меню
        foreach ($modules as $name => $config) {
            if (isset($config['class'])) {
                /** @var Module $class */
                $class = $config['class'];
                $moduleMenu = $class::getAdminMenu();
                if ($moduleMenu) {
                    $menu[] = [
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
        }

        StorageHelper::push([
            "new" => $menu,
            "old" => $old_menu ?? null,
        ], null, 'sidebarMenu');
    }

    /**
     * построить данные для шапки сайта
    */
    private static function buildHatData() {
        $data = [];

        //time
        $est_time = date_create('now', timezone_open('EST'));
        $time = [
            [
                "caption" => "CA time",
                "time" => date_create('now', timezone_open('America/Los_Angeles'))->format('H:i'),
            ],
            [
                "caption" => "EST time",
                "time" => $est_time->format('H:i'),
            ],
            [
                "caption" => "NY time",
                "time" => date_create('now', timezone_open('America/New_York'))->format('H:i'),
            ],
        ];

        $data["time"] = $time;

        //time
        $data["date"] = $est_time->format('F j, Y');

        //holiday
        $holiday = WorkingTimeHelper::getNextHoliday(date_create('now', timezone_open('EST')));
        $days = $holiday->getDaysUntil();
        $data["holiday"] = sprintf("%d day%s until %s", $days, $days > 1 ? 's' : '', (string)$holiday);

        //user
        $data["user"] = Xcart::app()->getUser()->login;

        //site logo
        $site_code = strtolower(Xcart::app()->getModule('Sites')->getSelectedSite()->code);
        $data["logoUrl"] = Paths::get('dist') . "/images/logos/sites/$site_code/logo.svg";

        StorageHelper::push($data, null, 'hat');
    }

    /**
     * Сформировать массивы из данных, которые используются на всех страницах,
     * для последующей передачи на frontend
    */
    public static function buildCommonData() {
        self::buildMenuData();
        self::buildHatData();
    }
}

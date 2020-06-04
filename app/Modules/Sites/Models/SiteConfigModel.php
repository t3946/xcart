<?php
namespace Modules\Sites\Models;

use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Fields\TextField;
use Xcart\App\Orm\Model;

class SiteConfigModel extends Model
{
    public const SITE_CONFIG_PARAMS = [
        'company_name' => 10,
        'company_website' => 20,
        'cidev_top_header_code' => 32,
        'cidev_header_code' => 37,
        'customer_service_working_time' => 38,
        'local_phone' => 34,
        'fax_number' => 35,
//        'search_products_unique_id_checkbox' => 46,
//        'cidev_tracking_code' => 100,
//        'cidev_main_page_code' => 34,
        'cidev_footer_code' => 35,
//        'pop_up_code' => 36,
//        'pop_up_in' => 37,
        'cidev_yandex_code_number' => 39,
//        'skip_generating_googlebase_feed' => 47,
//        'common_header_code' => 48,
//        'product_advantages_code' => 49,
        'Enable_CDN' => 210,
        'CDN_domain' => 215,
//        'Enable_Mobile_skin' => 250,
        'Google_Trusted_Store_ID' => 280,
        'Enable_surf_stats' => 380,
//        'Enable_desktop_notifications_on_product_page' => 660,
        'Preferred_served_country' => 480,
        'Preferred_language' => 580,
        'cidev_ga_code_number' => 39,
//        'cidev_google_adwords' => 41,
        /*'cidev_keywords' => 21,
        'config_title_meta_tag' => 22,
        'cidev_description' => 23,
        'config_keywords_meta_tag' => 25,*/
//        'sf_top_image_favicon' => 29,
//        'sf_top_image' => 30,
        'opt_order_prefix' => 40,
//        'search_products_unique_id' => 45,
//        'transfer_to_gcs_if_sku_search_null' => 47,
        'newsletter_email' => 50,
        'start_year' => 60,
//        'brands_columns' => 64,
//        'storefront_columns' => 67,
//        'show_seed_cats' => 70,
        'search_all_website_show' => 80,
        'shop_closed' => 90,
        'shop_closed_method'    => 95,
//        'new_shipping_calculation' => 990,
//        'enable_https' => 2000,
        'currency' => 3000,
        'flat_shipping_enabled' => 4000,
        'show_full_state_country' => 5000,
    ];

    public static function tableName()
    {
        return 'xcart_storefronts_config';
    }

    public static function getFields()
    {
        return [
            'site' => [
                'field' => 'storefrontid',
                'class' => ForeignField::class,
                'modelClass' => SiteModel::class,
                'link' => ['storefrontid' => 'storefrontid'],
                'null' => false,
                'default' => 0,
                'primary' => true
            ],

            'name' => [
                'class' => CharField::className(),
                'null' => false,
                'default' => '',
                'primary' => true
            ],
            'comment' => [
                'class' => CharField::className(),
                'null' => false,
                'default' => '',
            ],
            'category' => [
                'class' => CharField::className(),
                'null' => false,
                'length' => 32,
                'default' => '',
            ],
            'type' => [
                'class' => CharField::className(),
                'null' => false,
                'default' => 'text',
                'choices' => [
                    'numeric' => 'numeric',
                    'text' => 'text',
                    'textarea' => 'textarea',
                    'checkbox' => 'checkbox',
                    'separator' => 'separator',
                    'selector' => 'selector',
                    'multiselector' => 'multiselector',
                ],
            ],

            'validation' => [
                'class' => CharField::className(),
                'null' => false,
                'default' => '',
            ],

            'orderby' => [
                'class' => IntField::className(),
                'null' => false,
                'default' => 0,
            ],
            'value' => [
                'class' => TextField::className(),
                'null' => false,
                'default' => ''
            ],
            'defvalue' => [
                'class' => TextField::className(),
                'null' => false,
                'default' => ''
            ],
            'variants' => [
                'class' => TextField::className(),
                'null' => false,
                'default' => ''
            ],

        ];
    }

    public function __toString()
    {
        return $this->value;
    }
}
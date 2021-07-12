<?php
namespace Modules\Sites\Models;

use Modules\Brand\Models\BrandStorefrontModel;
use Modules\Core\Components\GlobalConfig;
use Modules\Translate\Models\LanguageModel;
use Modules\Pages\Models\Page;
use Xcart\App\Helpers\Text;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignCharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Fields\HasToOneField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Fields\ManyToManyField;
use Xcart\App\Orm\Manager;
use Xcart\App\Orm\Model;

/**
 * Class SiteModel
 *
 * @package Modules\Sites\Models
 *
 * @property int|null storefrontid
 *
 * @property null|Manager favicons
 * @property string code
 * @property Manager|TaxModel[] taxes
 */
class SiteModel extends Model
{
    private $_config = [];
    private $_globalConfig = [];
    private $currency;


    public function __toString()
    {
        $str = '';
        $attr = [];

        if ($this->pk === null) {
            return 'Site';
        }

        if (!$this->showInLists()) {
            $attr[] = 'Hidden';
        }
        if (!$this->isWork()) {
            $attr[] = 'Closed';
        }

        if ($attr) {
            $str = implode(', ', $attr);
            $str = " ({$str})";
        }

        return "[{$this->code}] {$this->getName()}{$str}";
    }

    public static function tableName() : string
    {
        return 'xcart_storefronts';
    }

    public static function getFields() :array
    {
        return [

            'images' => [
                'class' => HasManyField::class,
                'modelClass' => ImageSModel::class,
                'link' => ['storefrontid' => 'id'],
            ],
            'favicons' => [
                'class' => HasManyField::class,
                'modelClass' => ImageFModel::class,
                'link' => ['storefrontid' => 'id'],
            ],
            'config' => [
                'class' => HasManyField::class,
                'modelClass' => SiteConfigModel::class,
                'link' => ['storefrontid' => 'storefrontid'],
            ],
            'list_config' => [
                'field' => 'code',
                'class' => ForeignCharField::class,
                'modelClass' => ListConfigModel::class,
                'link' => ['code' => 'sf_code'],
            ],
            'storefrontid' => [
                'class' => AutoField::class,
            ],
            'code' => [
                'class' => CharField::class,
                'length' => 10,
                'null' => false,
                'default' => '',
            ],
            'domain' => [
                'class' => CharField::class,
                'null' => false,
            ],
            'prefix' => [
                'class' => CharField::class,
                'null' => false,
                'default' => '',
            ],

            'orderby' => [
                'class' => IntField::class,
                'null' => false,
                'default' => 10
            ],
            'static_page' => [
                'class' => HasManyField::class,
                'modelClass' => Page::class,
                'link' => ['storefrontid' => 'storefront_id'],
            ],
            'marketplaces' => [
                'class' => HasManyField::class,
                'modelClass' => SiteMarketplaceModel::class,
                'link' => ['storefrontid' => 'storefront_id']
            ],
            'short_name' => [
                'class' => CharField::class,
                'default' => '',
            ],
            'corporates' => [
                'class' => ManyToManyField::class,
                'modelClass' => CorporateModel::class,
                'through' => CorporateStorefrontsModel::class,
            ],
            'taxes' => [
                'class' => ManyToManyField::class,
                'modelClass' => TaxModel::class,
                'through' => SiteTaxModel::class,
            ],
            'payment_methods' => [
                'class' => ManyToManyField::class,
                'modelClass' => PaymentMethodModel::class,
                'through' => SitePaymentMethodModel::class,
            ],
            'status' => [
                'class' => CharField::class,
                'null' => false,
                'default' => 'D',
                'choices' => [
                    'Y' => 'Enabled',
                    'E' => 'Service',
                    'D' => 'Disabled'
                ],
            ],
            'company_name' => [
                'class' => CharField::class,
                'null' => false,
                'default' => null,
            ],
            'opt_shop_closed' => [
                'class' => BooleanField::class,
                'null' => false,
                'default' => false,
            ],
            'shop_closed_method' => [
                'class' => CharField::class,
                'null' => false,
                'default' => '',
                'choices' => [
                    '',
                    1 => 'show closed storefront banner',
                    2 => 'redirect to storefront home page',
                    3 => 'keep all visits on and show suggested links to other storefronts',
                    4 =>'try to redirect visit to proper product on other storefronts',
                ],
                'verboseName' => 'Behavior of processing visits'
            ],
            'company_website' => [
                'class' => CharField::class,
                'null' => false,
                'default' => null,
            ],
            'cidev_top_header_code' => [
                'class' => CharField::class,
                'null' => false,
                'default' => null,
            ],
            'local_phone' => [
                'class' => CharField::class,
                'null' => false,
                'default' => null,
            ],
            'fax_number' => [
                'class' => CharField::class,
                'null' => false,
                'default' => null,
            ],
            'cidev_header_code' => [
                'class' => CharField::class,
                'null' => false,
                'default' => null,
            ],
            'customer_service_working_time' => [
                'class' => CharField::class,
                'null' => false,
                'default' => null,
            ],
            'opt_order_prefix' => [
                'class' => CharField::class,
                'null' => false,
                'default' => null,
            ],
            'newsletter_email' => [
                'class' => CharField::class,
                'null' => false,
                'default' => null,
            ],
            'start_year' => [
                'class' => IntField::class,
                'length' => 4,
                'default' => null,
            ],
            'search_all_website_show' => [
                'class' => BooleanField::class,
                'null' => false,
                'default' => false,
            ],
            'Enable_CDN' => [
              'class' => BooleanField::class,
              'null' => false,
              'default' => false,
            ],
            'CDN_domain' => [
                'class' => CharField::class,
                'null' => false,
                'default' => null,
            ],
            'Google_Trusted_Store_ID' => [
                'class' => CharField::class,
                'null' => false,
                'default' => null,
            ],
            'Enable_surf_stats' => [
                'class' => BooleanField::class,
                'null' => false,
                'default' => false,
            ],
            'Preferred_served_country' => [
                'class' => CharField::class,
                'null' => false,
                'default' => null,
            ],
            'flat_shipping_enabled' => [
                'class' => BooleanField::class,
                'null' => false,
                'default' => false,
            ],
            'lang_id' => [
                'class' => ForeignField::class,
                'modelClass' => LanguageModel::class,
                'link' => ['lang_id' => 'lang_id'],
            ],

            /*
            'cidev_top_header_code',
            'local_phone', +
            'fax_number', +
            'cidev_footer_code',
            'cidev_header_code',
            'customer_service_working_time',
            'cidev_ga_code_number',
            'cidev_yandex_code_number',
            'opt_order_prefix',
            'newsletter_email',
            'start_year', +
            'search_all_website_show',
            'shop_closed_method',
            'shop_closed',
            'Enable_CDN',
            'CDN_domain', +
            'Enable_surf_stats',
            'Preferred_served_country',
            'Preferred_language',
            'currency',*/
        ];
    }

    public function getConfig()
    {
        if (!$this->_config) {

            $config = $this->config->cache(60)->valuesList(['name', 'value']);
            foreach ($config as $item) {
                $this->_config[$item['name']] = $item['value'];
            }
        }

        return $this->_config;
    }

    public function getGlobalConfig()
    {
        if (!$this->_globalConfig) {
            $this->_globalConfig = GlobalConfig::getInstance()->getAllData();
        }
        return $this->_globalConfig;
    }

    public function getBaseDomain()
    {
        $domain = strtolower($this->domain);
        
        if (strpos($domain, 'www.') !== false)
        {
            return str_replace('www.', '', $domain);
        }

        return $domain;
    }

    public function isWork()
    {
        if ($config = $this->getConfig()) {
            return (empty($config['shop_closed']) || $config['shop_closed'] === 'N');
        }

        return ($this->status !== 'D');
    }

    public function showInLists()
    {
        if ($this->isWork()) {
            if ($config = $this->getConfig()) {
                return (empty($config['search_all_website_show']) || $config['search_all_website_show'] === 'Y');
            }
        }

        return false;
    }

    public static function getAllEnabled()
    {
        $models = static::objects()->all();
        $models = array_filter($models , function($model){ return $model->isWork(); });

        return $models;
    }

    public function getAbsoluteUrl()
    {
        return $this->getHttpOrHttps()  . $this->domain;
    }

    public function getHttpOrHttps()
    {
        return  'https://';
    }

    public function getCompanyName()
    {
        $config = $this->getConfig();
        return !empty($config['company_name']) ? $config['company_name'] : $this->getName();
    }

    public function getName()
    {
        $name = $this->getBaseDomain();

        $config = $this->getConfig();

        if (!empty($config['company_name'])) {
            $name = $config['company_name'];

            if (strpos($name, '.') !== false ) {
                $name = substr($name, 0 , strpos($name, '.'));
            }

            $name = Text::camelCaseToUnderscores($name);
            $name = str_replace('_', ' ', ucfirst($name));
            $name = ucwords($name);
        }

        return $name;
    }

    public function getFrontendName()
    {
        /** @var ListConfigModel $config */
        if ($config = $this->list_config) {
            return $config->getName();
        }

        return $this->getName();
    }

    public function getOrderPrefix(): string
    {
        return $this->code. '-';
    }

    public function getCurrency():? CurrencyModel
    {
        if ($this->currency === null) {
            $this->currency = CurrencyModel::objects()->get(['currency_id' => $this->getConfig()['currency'] ?? 1]);
        }
        return $this->currency;
    }

}
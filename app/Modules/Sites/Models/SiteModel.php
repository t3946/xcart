<?php
namespace Modules\Sites\Models;

use Doctrine\DBAL\Types\Types;
use Exception;
use Modules\Core\Components\GlobalConfig;
use Modules\Core\Models\CountryModel;
use Modules\Goods\Models\CategoryModel;
use Modules\Goods\Models\ProductModel;
use Modules\Goods\Models\ProductStorefrontModel;
use Modules\Translate\Models\LanguageModel;
use Modules\Pages\Models\Page;
use Xcart\App\Helpers\Text;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignCharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Fields\ImageField;
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
 * @property CurrencyModel currency
 * @property CategoryModel base_category
 * @property string $domain
 * @property LanguageModel $lang
 * @property Manager|ProductModel[] products
 * @property bool $search_all_website_show
 * @property null|Manager|SitesMenuModel[] menu_list
 * @property ImageField $logo
 * @property ImageField $logo_mobile
 * @property SiteConfigModel[]|Manager $config
 * @property bool $shop_closed
 * @property ListConfigModel $list_config
 * @property string $company_name
 * @property string $cidev_top_header_code
 * @property CountryModel country_model
 * @property CorporateModel|null corporation
 */
class SiteModel extends Model
{
    private array $_config = [];
    private array $_globalConfig = [];


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
            $str = " ($str)";
        }

        return "[$this->code] {$this->getName()}$str";
    }

    public static function tableName(): string
    {
        return 'xcart_storefronts';
    }

    public static function getFields(): array
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
            'company_name' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Company name'
            ],
            'shop_closed' => [
                'class' => BooleanField::class,
                'null' => true,
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
                    4 => 'try to redirect visit to proper product on other storefronts',
                ],
                'verboseName' => 'Behavior of processing visits'
            ],
            'company_website' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Company website'
            ],
            'cidev_top_header_code' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Toll free customer service phone'
            ],
            'local_phone' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Local phone'
            ],
            'fax_number' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Fax number'
            ],
            'cidev_header_code' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Search string text',
            ],
            'customer_service_working_time' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Working time'
            ],
            'opt_order_prefix' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Order prefix'
            ],
            'newsletter_email' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Reply-To newsletter email address'
            ],
            'start_year' => [
                'class' => IntField::class,
                'length' => 4,
                'null' => true,
                'default' => null,
                'verboseName' => 'Year when the store started its operation'
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
                'null' => true,
                'default' => null,
                'verboseName' => 'CDN domain'
            ],
            'base_category' => [
                'field' => 'base_category_id',
                'class' => ForeignField::class,
                'modelClass' => CategoryModel::class,
                'link' => ['base_category_id' => 'categoryid'],
                'null' => true,
                'default' => null,
                'verboseName' => 'Base category',
            ],
            'Enable_surf_stats' => [
                'class' => BooleanField::class,
                'null' => false,
                'default' => false,
            ],
            'country_model' => [
                'field' => 'country',
                'class' => ForeignField::class,
                'modelClass' => CountryModel::class,
                'link' => ['country' => 'code'],
                'null' => true,
                'default' => null,
                'sqlType' => Types::STRING,
                'verboseName' => 'Preferred served country',
            ],
            'currency' => [
                'field' => 'currency_id',
                'class' => ForeignField::class,
                'modelClass' => CurrencyModel::class,
                'link' => ['currency_id' => 'currency_id'],
            ],
            'flat_shipping_enabled' => [
                'class' => BooleanField::class,
                'null' => false,
                'default' => false,
            ],
            'show_full_state_country' => [
                'class' => BooleanField::class,
                'null' => false,
                'default' => false,
            ],
            'lang' => [
                'field' => 'lang_id',
                'class' => ForeignField::class,
                'modelClass' => LanguageModel::class,
                'null' => true,
                'default' => null,
                'link' => ['lang_id' => 'lang_id'],
                'verboseName' => 'Preferred language'
            ],
            'corporation' => [
                'field' => 'corporation_id',
                'class' => ForeignField::class,
                'modelClass' => CorporateModel::class,
                'null' => true,
                'default' => null,
                'link' => ['corporation_id' => 'id'],
                'verboseName' => 'Corporation'
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
            'products' => [
                'class' => ManyToManyField::class,
                'modelClass' => ProductModel::class,
                'through' => ProductStorefrontModel::class,
            ],
            'logo' => [
                'class' => ImageField::class,
                'adapterName' => 'www',
                'uploadTo' => '/images/logo/',
                'null' => true,
            ],
            'logo_mobile' => [
                'class' => ImageField::class,
                'adapterName' => 'www',
                'uploadTo' => '/images/logo/',
                'null' => true,
                'verboseName' => 'Mobile logo'
            ],
            'file_edit_image_favicon' => [
                'class' => ImageField::class,
                'adapterName' => 'www',
                'uploadTo' => 'images/favicons/',
                'null' => true,
            ],
            'addresses' => [
                'class' => HasManyField::class,
                'link' => ['storefrontid' => 'site_id'],
                'modelClass' => SitesAddressesModel::class
            ],
            'menu_list' => [
                'class' => HasManyField::class,
                'link' => ['storefrontid' => 'site_id'],
                'modelClass' => SitesMenuModel::class,
            ],
            'socials' => [
                'class' => HasManyField::class,
                'link' => ['storefrontid' => 'site_id'],
                'modelClass' => SiteSocialsModel::class
            ],
            'dimension_weight' => [
                'field' => 'dimension_weight_id',
                'class' => ForeignField::class,
                'modelClass' => DimensionModel::class,
                'null' => true,
                'default' => null,
                'link' => ['dimension_weight_id' => 'dimension_id'],
                'verboseName' => 'Dimension weight'
            ],
            'dimension_size' => [
                'field' => 'dimension_size_id',
                'class' => ForeignField::class,
                'modelClass' => DimensionModel::class,
                'null' => true,
                'default' => null,
                'link' => ['dimension_size_id' => 'dimension_id'],
                'verboseName' => 'Dimension size'
            ]
        ];
    }

    public function getLogo(): string
    {
        return $this->logo->getValue() ?? '';
    }

    public function getMobileLogo(): string
    {
        return $this->logo_mobile->getValue() ?? $this->getLogo();
    }

    /**
     * @return array
     * @throws Exception
     * @deprecated use SiteModel properties
     */
    public function getConfig(): array
    {
        if (!$this->_config) {

            $config = $this->config->cache(60)->valuesList(['name', 'value']);
            foreach ($config as $item) {
                $this->_config[$item['name']] = $item['value'];
            }
            $this->_config = array_merge($this->_config, $this->getAttributes());
        }

        return $this->_config;
    }

    public function getGlobalConfig(): array
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

    public function isWork(): bool
    {
        return !$this->shop_closed;
    }

    public function showInLists(): bool
    {
        if ($this->isWork()) {
            return $this->search_all_website_show;
        }

        return false;
    }

    /**
     * @return SiteModel[]
     */
    public static function getAllEnabled(): array
    {
        return array_filter(static::objects()->all() , static fn(SiteModel $model) => $model->isWork());
    }

    public function getAbsoluteUrl(): string
    {
        return $this->getHttpOrHttps()  . $this->domain;
    }

    public function getHttpOrHttps(): string
    {
        return  'https://';
    }

    public function getCompanyName()
    {
        return $this->company_name ?: $this->getName();
    }

    public function getName()
    {
        $name = $this->getBaseDomain();

        if ($this->company_name) {
            $name = $this->company_name;

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
        if ($config = $this->list_config) {
            return $config->getName();
        }

        return $this->getName();
    }

    public function getOrderPrefix(): string
    {
        return $this->code. '-';
    }

    public function getCurrency(): CurrencyModel
    {
        return $this->currency;
    }

}
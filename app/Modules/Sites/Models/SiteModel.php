<?php
namespace Modules\Sites\Models;

use Modules\Core\Components\GlobalConfig;
use Modules\Pages\Models\Page;
use Xcart\App\Helpers\Text;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignCharField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

/**
 * Class SiteModel
 *
 * @package Modules\Sites\Models
 *
 * @property int|null storefrontid
 *
 * @property null|\Xcart\App\Orm\Manager favicons
 */
class SiteModel extends Model
{
    private $_config = [];
    private $_globalConfig = [];


    public function __toString()
    {
        $str = '';
        $attr = [];
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
            'short_name' => CharField::class
        ];
    }

    public function getConfig()
    {
        if (!$this->_config) {

            $config = $this->config->valuesList(['name', 'value']);
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
            return (empty($config['shop_closed']) || $config['shop_closed'] == 'N');
        }

        return ($this->status != 'D');
    }

    public function showInLists()
    {
        if ($this->isWork()) {
            if ($config = $this->getConfig()) {
                return (empty($config['search_all_website_show']) || $config['search_all_website_show'] == 'Y');
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
        return (($this->getConfig()['https_enabled'] == "Y")? 'https' : 'http') . '://';
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

    public function getOrderPrefix()
    {
        return $this->code."-";
    }

}
<?php
namespace Modules\Sites\Models;

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

    public static function tableName()
    {
        return 'xcart_storefronts';
    }

    public static function getFields()
    {
        return [
            'images' => [
                'class' => HasManyField::className(),
                'modelClass' => ImageSModel::className(),
                'link' => ['storefrontid' => 'id'],
            ],

            'favicons' => [
                'class' => HasManyField::className(),
                'modelClass' => ImageFModel::className(),
                'link' => ['storefrontid' => 'id'],
            ],
            'config' => [
                'class' => HasManyField::className(),
                'modelClass' => SiteConfigModel::className(),
                'link' => ['storefrontid' => 'storefrontid'],
            ],

            'list_config' => [
                'field' => 'code',
                'class' => ForeignCharField::className(),
                'modelClass' => ListConfigModel::className(),
                'link' => ['code' => 'sf_code'],
            ],

            'storefrontid' => [
                'class' => AutoField::className(),
            ],
            'code' => [
                'class' => CharField::className(),
                'length' => 10,
                'null' => false,
                'default' => '',
            ],
            'domain' => [
                'class' => CharField::className(),
                'null' => false,
            ],
            'prefix' => [
                'class' => CharField::className(),
                'null' => false,
                'default' => '',
            ],
            'status' => [
                'class' => CharField::className(),
                'null' => false,
                'default' => 'D',
                'choices' => [
                    'Y' => 'Enabled',
                    'E' => 'Service',
                    'D' => 'Disabled'
                ],
            ],
            'orderby' => [
                'class' => IntField::className(),
                'null' => false,
                'default' => 10
            ],
            'static_page' => [
                'class' => HasManyField::className(),
                'modelClass' => Page::className(),
                'link' => ['storefrontid' => 'storefront_id'],
            ],
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

}
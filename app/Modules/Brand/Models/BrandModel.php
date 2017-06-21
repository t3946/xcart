<?php
namespace Modules\Brand\Models;

use Xcart\App\Components\Breadcrumbs;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\AutoMetaModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\IntField;

/**
 * @property mixed brandid
 */
class BrandModel extends AutoMetaModel
{
    public static function tableName()
    {
        return 'xcart_brands';
    }

    public static function getFields()
    {
        return [
            'brandid' => [
                'class' => AutoField::className(),
                'primary' => true,
                'null' => false,
            ],
            'descr' => [
                'class' => CharField::className(),
                'null' => false,
                'default' => ''
            ],
            'meta_descr' => [
                'class' => CharField::className(),
                'null' => false,
                'default' => ''
            ],
            'disclaimer_text' => [
                'class' => CharField::className(),
                'null' => false,
                'default' => ''
            ],
        ];
    }

    public function getImage()
    {
        return ImageBModel::objects()->limit(1)->get(['id' => $this->brandid]);
    }

    public function getBreadcrumbs()
    {
        $bread = new Breadcrumbs();

        $bread->add('Brands');
        $bread->add($this->brand, $this->getAbsoluteUrl());
        return $bread;
    }

    public function getAbsoluteUrl()
    {
        if ($this->brandid)
        {
            return Xcart::app()->router->url('brand:view:old', ['id' => $this->brandid, 'slug' => 'TEMP']);
        }

        return false;
    }
}
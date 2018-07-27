<?php


namespace Modules\Goods\Models;

use Modules\Sites\Models\SiteModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanCharField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\OneToOneField;
use Xcart\App\Orm\Fields\UnixTimestampField;
use Xcart\App\Orm\Model;

class NotifyStockModel extends Model
{
    public static function tableName()
    {
        return 'xcart_notify_when_in_stock';
    }

    public static function getFields()
    {
        return [
            'id' => AutoField::class,

            'product' => [
                'class' => OneToOneField::class,
                'modelClass' => ProductModel::class,
                'link' => ['productid' => 'productid'],
            ],

            'email' => [
                'class' => CharField::class,
                'null' => false,
                'default' => ''
            ],

            'date' => [
                'class' => UnixTimestampField::class,
                'autoNowAdd' => true,
            ],

            'sent' => [
                'class' => BooleanCharField::class,
                'null' => false,
                'default' => false,
            ],

            'site' => [
                'class' => OneToOneField::class,
                'modelClass' => SiteModel::class,
                'link' => ['storefrontid' => 'storefrontid']
            ],
        ];
    }
}
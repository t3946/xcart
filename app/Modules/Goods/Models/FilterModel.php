<?php
namespace Modules\Goods\Models;

use Modules\Sites\Models\SiteModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class FilterModel extends Model
{
    public static function tableName()
    {
        return 'xcart_cidev_filters';
    }

    public static function getFields()
    {
        return [
            'f_id' => [
                'class' => AutoField::class,
                'primary' => true,
                'null' => false,
            ],
            'f_name' => [
                'class' => CharField::class,
                'null' => false,
                'default' => ''
            ],
            'f_order_by' => [
                'class' => IntField::class,
                'null' => false,
                'default' => 10
            ],
            'f_active' => [
                'class' => CharField::class,
                'null' => false,
                'default' => 'Y'
            ],
            'storefront' => [
                'field' => 'storefrontid',
                'class' => ForeignField::class,
                'modelClass' => SiteModel::class,
                'link' => ['storefrontid' => 'storefrontid'],
                'null' => false,
                'default' => 0
            ],
        ];
    }
}
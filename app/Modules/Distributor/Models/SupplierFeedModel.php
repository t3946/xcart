<?php
namespace Modules\Distributor\Models;

use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\SerializeField;
use Xcart\App\Orm\Model;

class SupplierFeedModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_supplier_feeds';
    }

    public static function getFields()
    {
        return [
            'feed_id' => [
                'class' => AutoField::className(),
                'primary' => true,
                'null' => false,
            ],
            'last_feed_fields' => [
                'class' => SerializeField::className(),
                'null' => false,
                'default' => ''
            ],
            'distributor' => [
                'field' => 'manufacturerid',
                'class' => ForeignField::className(),
                'modelClass' => DistributorModel::className(),
                'link' => ['manufacturerid' => 'manufacturerid'],
                'null' => false,
            ],
        ];
    }
}
<?php


namespace Modules\Order\Models\RMA;


use Modules\Order\Models\OrderModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Fields\ManyToManyField;
use Xcart\App\Orm\Fields\UnixTimestampField;
use Xcart\App\Orm\Manager;
use Xcart\App\Orm\Model;

/**
 * @property ImagesModel[]|Manager $images
 */
class RMAModel extends Model
{
    public static function tableName()
    {
        return 'xcart_rmas';
    }

    public static function getFields()
    {
        return [
            'rma_id' => AutoField::class,
            'order' => [
                'field' => 'orderid',
                'class' => ForeignField::class,
                'modelClass' => OrderModel::class,
                'link' => ['orderid' => 'orderid'],
            ],
            'zipcode' => [
                'class' => CharField::class,
                'default' => '',
                'null' => false
            ],
            'email' => [
                'class' => CharField::class,
                'default' => '',
                'null' => false
            ],
            'explanation' => [
                'class' => CharField::class,
                'default' => '',
                'null' => false
            ],
            'date' => [
                'class' => UnixTimestampField::class,
                'autoNowAdd' => true,
            ],
            'rma_number' => [
                'class' => IntField::class
            ],
            'order_email' => [
                'class' => CharField::class,
                'default' => '',
                'null' => false
            ],
            'images' => [
                'class' => ManyToManyField::class,
                'modelClass' => ImagesModel::class,
                'through' => RMAImagesModel::class,
            ],
            'status_model' => [
                'field' => 'status',
                'class' => ForeignField::class,
                'modelClass' => RMAStatusModel::class,
                'link' => ['status' => 'code']
            ]
        ];
    }
}
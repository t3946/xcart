<?php


namespace Modules\Faxes\Models;


use Modules\Order\Models\OrderModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\BooleanField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\DateTimeField;
use Xcart\App\Orm\Fields\FileField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class FaxModel extends Model
{
    public static function getFields()
    {
        return [
            'id' => AutoField::class,
            'fax_id' => [
                'class' => IntField::class,
                'null' => false
            ],
            'fax_date' => [
                'class' => DateTimeField::class,
                'null' => false
            ],
            'fax_from' => [
                'class' => CharField::class,
                'null' => false
            ],
            'fax_to' => [
                'class' => CharField::class,
                'null' => false
            ],
            'filename' => [
                'class' => CharField::class,
                'null' => false
            ],
            'is_active' => [
                'class' => BooleanField::class,
                'default' => true,
            ],
            'pagecount' => [
                'class' => IntField::class,
                'null' => true,
                'default' => null,
            ],
            'path' => [
                'class' => FileField::class,
                'adapterName' => 'www',
                'uploadTo' => 'files/faxes/%Y%m',
                'maxSize' => '100M',
            ],
            'order' => [
                'class' => ForeignField::class,
                'modelClass' => OrderModel::class,
                'link' => ['order_id' => 'orderid']
            ]
        ];
    }
}
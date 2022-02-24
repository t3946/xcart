<?php


namespace Modules\Forms\Models;


use Modules\Distributor\Models\DistributorModel;
use Modules\Order\Models\OrderModel;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

class EmailEntityModel extends Model
{
    public static function getFields()
    {
        return [
            'dx' => [
                'field' => 'entity_id',
                'class' => ForeignField::class,
                'modelClass' => DistributorModel::class,
                'link' => ['entity_id' => 'manufacturerid'],
                'primary' => true,
            ],
            'order' => [

                'field' => 'entity_id',
                'class' => ForeignField::class,
                'modelClass' => OrderModel::class,
                'link' => ['entity_id' => 'orderid'],
                'primary' => true,
            ],
            'model' => [
                'class' => CharField::class,
                'primary' => true,
            ],
            'email' => [
                'field' => 'email_id',
                'class' => ForeignField::class,
                'modelClass' => EmailModel::class,
                'link' => ['email_id' => 'id'],
                'primary' => true,
            ],
        ];
    }
}
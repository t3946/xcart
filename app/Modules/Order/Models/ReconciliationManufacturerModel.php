<?php


namespace Modules\Order\Models;


use Modules\Distributor\Models\DistributorModel;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

class ReconciliationManufacturerModel extends Model
{
    public static function tableName()
    {
        return 'xcart_reconciliation_manufacturers';
    }

    public static function getFields()
    {
        return [
            'reconciliation' => [
                'field' => 'reconciliation_id',
                'class' => ForeignField::class,
                'modelClass' => ReconciliationModel::class,
                'link' => ['reconciliation_id' => 'id'],
                'primary' => true,
                'null' => false,
            ],
            'distributor' => [
                'field' => 'manufacturer_id',
                'class' => ForeignField::class,
                'modelClass' => DistributorModel::class,
                'link' => ['manufacturer_id' => 'manufacturerid'],
                'primary' => true,
                'null' => false,
            ],
        ];
    }
}
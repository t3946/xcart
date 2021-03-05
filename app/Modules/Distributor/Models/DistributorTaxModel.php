<?php


namespace Modules\Distributor\Models;


use Modules\Sites\Models\TaxModel;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

class DistributorTaxModel extends Model
{
    public static function tableName()
    {
        return 'xcart_distributor_taxes';
    }

    public static function getFields()
    {
        return [
            'distributor' => [
                'field' => 'distributor_id',
                'class' => ForeignField::class,
                'modelClass' => DistributorModel::class,
                'link' => ['distributor_id' => 'manufacturerid']
            ],
            'tax' => [
                'field' => 'tax_id',
                'class' => ForeignField::class,
                'modelClass' => TaxModel::class,
                'link' => ['tax_id' => 'taxid']
            ]
        ];
    }
}
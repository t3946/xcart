<?php


namespace Modules\Sites\Models;


use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

class CorporateTaxesModel extends Model
{
    public static function getFields()
    {
        return [
            'tax' => [
                'class' => ForeignField::class,
                'modelClass' => TaxModel::class,
                'link' => ['tax_id' => 'taxid']
            ],
            'corporate' => [
                'class' => ForeignField::class,
                'modelClass' => CorporateModel::class,
                'link' => ['corporate_id' => 'id']
            ]
        ];
    }
}
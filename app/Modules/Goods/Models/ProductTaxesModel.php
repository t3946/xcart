<?php

namespace Modules\Goods\Models;

use Modules\Sites\Models\TaxModel;
use Xcart\App\Orm\AutoMetaTrait;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Model;

class ProductTaxesModel extends Model
{
    use AutoMetaTrait;

    public static function tableName()
    {
        return 'xcart_product_taxes';
    }

    public static function getFields()
    {
        return [
            'product' => [
                'field' => 'productid',
                'class' => ForeignField::class,
                'modelClass' => ProductModel::class,
                'link' => ['productid' => 'productid'],
            ],
            'tax' => [
                'field' => 'taxid',
                'class' => ForeignField::class,
                'modelClass' => TaxModel::class,
                'link' => ['taxid' => 'taxid'],
            ]
        ];
    }
}
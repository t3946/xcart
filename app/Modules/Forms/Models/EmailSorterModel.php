<?php


namespace Modules\Forms\Models;


use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Model;

class EmailSorterModel extends Model
{
    public static function getFields()
    {
        return [
            'id' => AutoField::class,
            'entity' => [
                'class' => CharField::class,
                'choices' => [
                    'dx' => 'Distributor',
                    'order' => 'Order',
                ]
            ],
            'filter_field' => [
                'class' => CharField::class,
                //'choices' => EmailModel::getFields()
            ],
            'condition' => [
                'class' => CharField::class,
            ],
            'value' => [
                'class' => CharField::class,
            ]
        ];
    }
}
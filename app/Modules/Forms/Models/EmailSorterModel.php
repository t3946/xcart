<?php


namespace Modules\Forms\Models;


use Modules\Distributor\Models\DistributorModel;
use Modules\Order\Models\OrderModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\Field;
use Xcart\App\Orm\Fields\IntField;
use Xcart\App\Orm\Model;

class EmailSorterModel extends Model
{
    public static function getFields()
    {
        return [
            'id' => AutoField::class,
            'type' => [
                'class' => CharField::class,
                'verboseName' => "Type",
                'choices' => [
                    'inbox' => 'Inbox',
                    'sent' => 'Sent',
                ],
                'null' => false,
                'default' => 'inbox'
            ],

            'filter_field' => [
                'class' => CharField::class,
                'choices' => EmailModel::getFieldsName(),
                'verboseName' => 'Email Field',
            ],
            'cond' => [
                'class' => CharField::class,
                'choices' => [
                    'contains' => 'contains',
                    //'equal' => 'equal',
                    'regexp' => 'regexp',
                    'related' => 'entity related',
                ],
                'verboseName' => 'Condition',
            ],
            'value' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
            ],
            'entity' => [
                'class' => CharField::class,
                'choices' => [
                    DistributorModel::class => 'Distributor',
                    OrderModel::class => 'Order',
                ],
                'verboseName' => 'Destination Entity',
            ],
            'target' => [
                'class' => IntField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Target',
            ],
            'related_value' => [
                'class' => CharField::class,
                'null' => true,
                'default' => null,
                'verboseName' => 'Related field',
            ],

        ];
    }
}
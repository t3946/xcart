<?php


namespace Modules\Forms\Models;


use Modules\Distributor\Models\DistributorModel;
use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\Field;
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
                ],
                'verboseName' => 'Destination Entity',
            ],
            'filter_field' => [
                'class' => CharField::class,
                'choices' => function(){
                    $res = [];
                    $model = new EmailModel;
                    /** @var Field $f */
                    foreach ($model->getFieldsInit() as $f) {
                        if ($f->getVerboseName()) {
                            $res[$f->getName()] = $f->getVerboseName();
                        }
                    }
                    sort($res);
                    return $res;
                },
                'verboseName' => 'Email Field',
            ],
            'condition' => [
                'class' => CharField::class,
                'choices' => [
                    'contains' => 'contains',
                    'equal' => 'equal',
                    'regexp' => 'regexp',
                    'related' => 'entity related',
                ]
            ],
            'value' => [
                'class' => CharField::class,
            ],
            'related_value' => [
                'class' => CharField::class,
            ],

        ];
    }
}
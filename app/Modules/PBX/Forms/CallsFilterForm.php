<?php

namespace Modules\PBX\Forms;

use Mindy\QueryBuilder\Expression;
use Modules\User\Models\UserModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\DateTimeField;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\Fields\NumberField;
use Xcart\App\Form\Fields\Select2Field;
use Xcart\App\Form\Form;

class CallsFilterForm extends Form
{
    public function getFields()
    {
        return [

            'direction' => [
                'class' => Select2Field::class,
                'multiple' => true,
                'label' => 'Direction',
                'choices' => [
                    'extension' => 'Choose type',
                    'in' => 'Inbound',
                    'out' => 'Outbound',
                    'lost' => 'Missed call',
                    'vm' => 'Voicemail',
                ],
            ],

            'order' => [
                'class' => CharField::class,
                'label' => 'Order',
                'attributes' => [
                    'pattern' => '\d+',
                    'placeholder' => 'Numbers only'
                ]
            ],

            'e164' => [
                'class' => NumberField::class,
                'label' => 'Party Tel #',
                'attributes' => [
                    'pattern' => '\d+',
                    'placeholder' => 'Numbers only'
                ]
            ],

            'date_from' => [
                'class' => DateTimeField::class,
                'label' => 'Date from',
                'attributes' => [
                    'placeholder' => 'mm/dd/YY'
                ]
            ],

            'date_to' => [
                'class' => DateTimeField::class,
                'label' => 'Date to',
                'attributes' => [
                    'placeholder' => 'mm/dd/YY'
                ]
            ],

            'account' => [
                'class' => Select2Field::class,
                'label' => 'Operator',
                'multiple' => true,
                'choices' => function() {

                    $op = [];
                    $filter = [
                        'usertype' => 'A',
                        'status' => 'Y',
                        new Expression("trim(pbx_extension) != '' ")
                    ];

                    $operators = UserModel::objects()
                                          ->filter($filter)
                                          ->all();

                    foreach ($operators as $operator){
                        $op[$operator->id] = $operator->firstname;
                    }

                    return $op;
                }
            ],

        ];
    }
}
<?php

namespace Modules\PBX\Forms;

use Mindy\QueryBuilder\Expression;
use Modules\User\Models\UserModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\DateRangeField;
use Xcart\App\Form\Fields\Select2Field;
use Xcart\App\Form\Form;

class CallsFilterForm extends Form
{
    public function getFields()
    {
        return [
            'date_from' => [
                'class' => DateRangeField::class,
                'label' => 'Date range',
                'range' => true,
                'html' => [
                    'style' => 'width: 300px'
                ],
            ],
            'direction' => [
                'class' => Select2Field::class,
                'multiple' => true,
                'label' => 'Direction',
                'choices' => [
                    'in' => 'Inbound',
                    'out' => 'Outbound',
                    'lost' => 'Missed call',
                    'vm' => 'Voicemail',
                ],
                'html' => [
                    'style' => 'width: 300px'
                ]
            ],
            'orders' => [
                'class' => CharField::class,
                'label' => 'Order #',
                'html' => [
                    'style' => 'width: 300px'
                ],
            ],
            'e164' => [
                'class' => CharField::class,
                'label' => 'Party Tel #',
                'html' => [
                    'style' => 'width: 300px'
                ],
            ],
            'user' => [
                'class' => Select2Field::class,
                'label' => 'Operator',
                'multiple' => true,
                'html' => [
                    'style' => 'width: 300px'
                ],
                'choices' => function () {
                    $op = [];
                    $filter = [
                        'usertype' => 'A',
                        'status' => 'Y',
                        new Expression("trim(pbx_extension) != '' ")
                    ];

                    $operators = UserModel::objects()
                        ->filter($filter)
                        ->all();

                    foreach ($operators as $operator) {
                        $op[$operator->id] = $operator->firstname;
                    }

                    return $op;
                }
            ],
        ];
    }
}
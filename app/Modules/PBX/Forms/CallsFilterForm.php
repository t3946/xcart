<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 12/19/2017
 * Time: 2:05 PM
 */

namespace Modules\PBX\Forms;

use Mindy\QueryBuilder\Expression;
use Modules\PBX\Helpers\PBXHelper;
use Modules\User\Models\UserModel;
use Xcart\App\Form\BaseForm;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\DateTimeField;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\Fields\NumberField;

class CallsFilterForm extends BaseForm
{
    public function getFields()
    {
        return [
            'direction' => [
                'class' => DropDownField::className(),
                'label' => 'Direction',
                'multiple' => true,
                'choices' => [
                    'in' => 'Inbound',
                    'out' => 'Outbound',
                    'lost' => 'Miss call',
                    'vm' => 'Voice mail',
                ],
            ],

            'order' => [
                'class' => CharField::className(),
                'label' => 'Order',
                'attributes' => [
                    'pattern' => '\d+',
                    'placeholder' => 'Numbers only'
                ]
            ],

            'e164' => [
                'class' => NumberField::className(),
                'label' => 'Party Tel #',
                'attributes' => [
                    'pattern' => '\d+',
                    'placeholder' => 'Numbers only'
                ]
            ],

            'date_from' => [
                'class' => DateTimeField::className(),
                'label' => 'Date from',
                'attributes' => [
                    'placeholder' => 'mm/dd/YY'
                ]
            ],

            'date_to' => [
                'class' => DateTimeField::className(),
                'label' => 'Date to',
                'attributes' => [
                    'placeholder' => 'mm/dd/YY'
                ]
            ],

            'operator' => [
                'class' => DropDownField::className(),
                'label' => 'Operator',
                'multiple' => true,
                'choices' => function() {

                    $op = [];
                    $filter = [
                        'usertype' => 'A',
                        'status' => 'Y',
                        //            'login__isnt' => 'sergey2',
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
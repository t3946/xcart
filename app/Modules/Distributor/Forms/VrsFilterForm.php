<?php

namespace Modules\Distributor\Forms;

use Modules\Distributor\Models\VrsModel;
use Modules\Sites\Models\SiteModel;
use Modules\User\Models\UserModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\DateField;
use Xcart\App\Form\Fields\DateRangeField;
use Xcart\App\Form\Fields\Select2Field;
use Xcart\App\Form\Form;

class VrsFilterForm extends Form
{
    public function getFields()
    {
        return [
            'company' => [
                'class' => CharField::class,
                'label' => 'Company',
                'html' => [
                    'style' => 'width: 300px',
                ],
            ],
            'sf' => [
                'class' => Select2Field::class,
                'label' => 'SF',
                'multiple' => true,
                'html' => [
                    'style' => 'width: 300px',
                ],
                'choices' => function () {
                    $options = [];
                    $sites = SiteModel::objects()->all();
                    foreach ($sites as $site) {
                        $options[$site->storefrontid] = "[$site->code] $site->company_name";
                    }

                    return $options;
                }
            ],
            'status' => [
                'class' => Select2Field::class,
                'label' => 'Status',
                'multiple' => true,
                'html' => [
                    'style' => 'width: 300px',
                ],
                'choices' => VrsModel::STATUS_CHOICES,
            ],
            'user' => [
                'label' => 'Added by',
                'multiple' => true,
                'class' => Select2Field::class,
                'html' => [
                    'style' => 'width: 300px',
                ],
                'choices' => function () {
                    $op = [];
                    $filter = [
                        'usertype' => 'A',
                        'status' => 'Y',
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
            'date' => [
                'label' => 'Date',
                'class' => DateRangeField::class,
                'html' => [
                    'data-range' => true,
                    'class' => 'datepicker-here',
                    'data-toggle-selected' => false,
                    'data-multiple-dates-separator' => ' - ',
                    //'date-format' => 'yyyy',
                    'data-language' => 'en',
                    'data-clear-button' => '1',
                    'autocomplete' => 'off',
                    'style' => 'width: 300px',

                ],
            ]
        ];
    }
}
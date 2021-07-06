<?php

namespace Modules\Distributor\Forms;

use Modules\Distributor\Admin\DistributorAdmin;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\QueryBuilder\Expression;
use Modules\User\Models\UserModel;
use Xcart\App\Form\Fields\Select2Field;
use Xcart\App\Form\Form;

class DxFilterForm extends Form
{
    public function getFields()
    {
        $word_range = range('A', 'Z');
        return [
            'manufacturer_code' => [
                'class' => CharField::class,
                'label' => 'Dx name',
                'html' => [
                    'style' => 'width: 300px',
                ],
            ],
            'd_search_keyphrase_for_reconciliation' => [
                'class' => CharField::class,
                'label' => 'Reconciliation keyphrase',
                'html' => [
                    'style' => 'width: 300px',
                ],
            ],
            'sites' => [
                'class' => Select2Field::class,
                'label' => 'Main SF',
                'html' => [
                    'style' => 'width: 300px',
                    'class' => 'select2-field',
                ],
                'multiple' => true,
                'choices' => function () {
                    foreach (SiteModel::objects()->order(['code']) as $site) {
                        $sites[$site->pk] = (string)$site;
                    }
                    return $sites ?? [];
                }
            ],
            'provider_model' => [
                'class' => Select2Field::class,
                'label' => 'VRS',
                'html' => [
                    'style' => 'width: 300px',
                    'class' => 'select2-field',
                ],
                'multiple' => true,
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
                        $op[$operator->login] = $operator->firstname;
                    }

                    return $op;
                },
            ],
            'letter' => [
                'class' => Select2Field::class,
                'label' => 'Alphabetic order',
                'choices' => array_merge(['' => '',], array_combine($word_range, $word_range))
            ],
            'avail' => [
                'class' => Select2Field::class,
                'label' => 'Active',
                'choices' => [
                    '' => '',
                    'Y' => 'Y',
                    'N' => 'N',
                ]
            ]
        ];
    }
}
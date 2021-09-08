<?php

namespace Modules\Goods\Forms;

use Modules\Distributor\Admin\DistributorAdmin;
use Modules\Distributor\Models\DistributorModel;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\QueryBuilder\Expression;
use Modules\User\Models\UserModel;
use Xcart\App\Form\Fields\Select2Field;
use Xcart\App\Form\Form;

class ProductFilterForm extends Form
{
    public function getFields()
    {
        return [
            'product' => [
                'class' => CharField::class,
                'label' => 'Product name',
                'html' => [
                    'style' => 'width: 300px',
                ],
            ],
            'productcode' => [
              'class' => CharField::class,
              'label' => 'Product code',
              'html' => [
                  'style' => 'width: 300px'
              ]
            ],
			'forsale' => [
				'class' => Select2Field::class,
				'choices' => [
					'Y' => 'Sale',
					'N' => 'Not sale',
				],
                'html' => [
                    'style' => 'width: 300px'
                ]
			],
            'distributor' => [
                'class' => Select2Field::class,
                'choices' => function () {
                    $options = [];
                    $dist_list = DistributorModel::objects()->order(['manufacturer'])->all();
                    /** @var DistributorModel $dist */
                    foreach ($dist_list as $dist) {
                        $options[$dist->manufacturerid] = $dist->manufacturer;
                    }

                    return $options;
                },
                'multiple' => true,
                'html' => [
                    'style' => 'width: 300px'
                ]
            ]
        ];
    }
}
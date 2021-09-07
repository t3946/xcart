<?php

namespace Modules\Goods\Forms;

use Modules\Distributor\Admin\DistributorAdmin;
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
			'forsale' => [
				'class' => DropDownField::class,
				'choices' => [
					'Y' => 'Available for sale',
					'N' => 'Disabled',
				],
			]
        ];
    }
}
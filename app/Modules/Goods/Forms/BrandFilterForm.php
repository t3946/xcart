<?php
namespace Modules\Goods\Forms;

use Modules\Distributor\Models\DistributorModel;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\Select2Field;
use Xcart\App\Form\Form;

class BrandFilterForm extends Form
{
    public function getFields()
    {
        return [
            'brand' => [
                'class' => CharField::class,
                'label' => 'Brand',
                'html' => [
                    'style' => 'width: 300px',
                ],
            ],
            'manufacture' => [
                'class' => Select2Field::class,
                'label' => 'Distributors',
                'html' => [
                    'style' => 'width: 300px'
                ],
                'choices' => function () {
                    $options = [];
                    $distr = DistributorModel::objects()->order(['manufacturer'])->all();
                    foreach ($distr as $dist) {
                        $options[$dist->manufacturerid] = $dist->manufacturer;
                    }

                    return $options;
                },
                'multiple' => true,
            ],
        ];
    }
}
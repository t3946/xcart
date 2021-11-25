<?php
namespace Modules\Goods\Forms\FilterForms;
use Modules\Distributor\Models\DistributorModel;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Form\Fields\Select2Field;
use Xcart\App\Form\Form;
use Xcart\App\Main\Xcart;

class CategoryFilterForm extends Form
{
    public function getFields(): array
    {
        /** @var SiteModel $site */
        $site = Xcart::app()->getModule('Sites')->getSite();
        return [
            'site' => [
                'class' => Select2Field::class,
                'choices' => function () {
                    /** @var DistributorModel $dist */
                    foreach (SiteModel::getAllEnabled() as $site) {
                        $options[$site->pk] = (string)$site;
                    }

                    return $options ?? [];
                },
                'value' => $site->pk,
                'multiple' => true,
                'html' => [
                    'style' => 'width: 300px'
                ]
            ],
        ];
    }

}
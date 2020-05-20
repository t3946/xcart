<?php


namespace Modules\Admin\Forms\Dx;


use Xcart\App\Form\Fields\CharField;

class DistributorProductQuestionsForm extends DistributorForm
{
    public function getFieldsets()
    {
        return [[
            'd_product_catalog',
        ]];
    }

    public function getFields()
    {
        return [

        ];
    }
}
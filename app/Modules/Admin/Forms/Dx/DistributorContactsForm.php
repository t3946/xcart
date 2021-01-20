<?php


namespace Modules\Admin\Forms\Dx;


use Modules\Distributor\Models\DistributorContactsModel;
use Xcart\App\Form\Fields\Select2Field;
use Xcart\App\Form\ModelForm;

class DistributorContactsForm extends ModelForm
{
    public array $exclude = ['distributor'];

    public function getModel()
    {
        return new DistributorContactsModel();
    }

    public function getFields()
    {
        return [
            'utility' => [
                'class' => Select2Field::class,
                'multiple' => true,
                'html' => ['style' => 'width:100%'],
            ]
        ];
    }

    public function getName()
    {
        return '';
    }

}
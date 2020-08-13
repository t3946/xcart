<?php


namespace Modules\Sites\Forms\Corporates;


use Modules\Sites\Admin\ShareHolderAdmin;
use Modules\Sites\Models\ShareHolderModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\ListViewField;
use Xcart\App\Form\ModelForm;

class ShareholderForm extends ModelForm
{
    public function getFieldsets()
    {
        return [[
            'name',
            'shares',
            'percent',
        ]];
    }

    public function getModel()
    {
        return new ShareHolderModel;
    }


    public function getName()
    {
        return 'Shareholder';
    }
}
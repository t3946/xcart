<?php


namespace Modules\Order\Forms;


use Modules\Forms\Models\TemplateCategoryModel;
use Modules\Order\Models\VoidedReasonModel;
use Xcart\App\Form\ModelForm;

class VoidedReasonForm extends ModelForm
{
    public array $exclude = ['pos'];

    public function getModel()
    {
        return new VoidedReasonModel();
    }

    public function getName()
    {
        return 'Voided reason';
    }
}
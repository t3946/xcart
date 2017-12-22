<?php
namespace Modules\Core\Forms;

use Modules\Core\Models\CronModel;
use Xcart\App\Form\ModelForm;

class CronForm extends ModelForm
{
    public function getModel()
    {
        return new CronModel();
    }
}
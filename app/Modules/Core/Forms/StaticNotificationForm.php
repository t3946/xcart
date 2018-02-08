<?php
namespace Modules\Core\Forms;

use Modules\Core\Fields\ColorPickerField;
use Modules\Core\Models\StaticNotificationModel;
use Modules\Editor\Fields\EditorField;
use Xcart\App\Form\Fields\DateTimeField;
use Xcart\App\Form\ModelForm;

class StaticNotificationForm extends ModelForm
{
    public function getModel()
    {
        return new StaticNotificationModel();
    }

    public function getFields()
    {
        return [
            'bg_color' => [
                'class' => ColorPickerField::className(),
            ],
            'text_color' => [
                'class' => ColorPickerField::className(),
            ],
            'start_at' => [
                'class' => DateTimeField::className(),
            ],
            'end_at' => [
                'class' => DateTimeField::className(),
            ],
            'description' => [
                'class' => EditorField::className(),
            ]
        ];
    }

}
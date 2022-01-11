<?php

namespace Modules\Dashboard\Forms;


use DateTime;
use Modules\Mail\Models\MailboxForwardingModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\FileField;
use Xcart\App\Form\Fields\ImageField;
use Xcart\App\Form\Fields\Select2Field;
use Xcart\App\Form\ModelForm;

class MailboxForwardingForm extends ModelForm
{
    public array $exclude = ['date', 'unique_id', 'source'];

    public function getModel(): MailboxForwardingModel
    {
        return new MailboxForwardingModel();
    }

    public function getFields(): array
    {
        return [
            'image_path' => [
                'class' => ImageField::class
            ],
            'status' => [
                'class' => CharField::class,
                'html' => [
                    'style' => 'width: 300px'
                ]
            ],
            'type' => [
                'class' => Select2Field::class,
                'html' => [
                    'style' => 'width: 300px'
                ]
            ],
            'file' => FileField::class
        ];
    }

    /**
     * @param MailboxForwardingModel $instance
     */
    public function beforeInstanceSave($instance)
    {
        if ($instance->getIsNewRecord()) {
            $instance->date = (new DateTime())->format('Y-m-d');
            $instance->source = 'manual';
        }
    }
}
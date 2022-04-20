<?php

namespace Modules\Order\Forms;

use Modules\Editor\Fields\EditorField;
use Modules\Order\Models\OrderStatusNotificationModel;
use Xcart\App\Form\Fields\CharField;
use Xcart\App\Form\Fields\CheckboxField;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\ModelForm;

class OrderStatusNotificationForm extends ModelForm
{
    public function getModel(): OrderStatusNotificationModel
    {
        return new OrderStatusNotificationModel();
    }

    public function getFields(): array
    {
        return [
            'status' => [
                'class' => DropDownField::class,
            ],
            'customer_subject' => [
                'class' => CharField::class,
            ],
            'copy_subject' => [
                'class' => CharField::class,
            ],
            'email_body' => [
                'class' => EditorField::class,
                'html' => [
                    'class' => 'tinymce-field',
                ],
            ],
            'enabled' => [
                'class' => CheckboxField::class,
            ],
            'customer_attach_pdf_invoice' => [
                'class' => CheckboxField::class,
            ],
            'admin_attach_pdf_invoice' => [
                'class' => CheckboxField::class,
            ],
            'lang' => [
                'class' => DropDownField::class,
                'html' => [
                    'style' => 'width: 100%'
                ]
            ]
        ];
    }
}
<?php

namespace Modules\Order\Forms\FilterForms;

use Modules\Order\Models\OrderStatusModel;
use Modules\Translate\Models\LanguageModel;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\Fields\Select2Field;
use Xcart\App\Form\Form;

class OrderStatusNotificationFilterForm extends Form
{
    public function getFields(): array
    {
        return [
            'lang' => [
                'class' => Select2Field::class,
                'html' => [
                    'style' => 'width: 300px'
                ],
                'multiple' => true,
                'choices' => function () {
                    $ar_lang = LanguageModel::objects()->all();
                    foreach ($ar_lang as $lang_model) {
                        $options[$lang_model->pk] = (string)$lang_model;
                    }
                    return $options ?? [];
                },
            ],
            'status' => [
                'class' => DropDownField::class,
                'html' => [
                    'style' => 'width: 300px'
                ],
                'choices' => function (): array {
                    $statuses = OrderStatusModel::objects()->order(['name'])->all();
                    $options[] = '';
                    foreach ($statuses as $status) {
                        $options[$status->code] = (string)$status;
                    }
                    return $options ?? [];
                },
            ],
        ];
    }
}
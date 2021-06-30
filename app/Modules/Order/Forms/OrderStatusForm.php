<?php


namespace Modules\Order\Forms;


use Modules\Order\Models\OrderStatusAvailabilityModel;
use Modules\Order\Models\OrderStatusModel;
use Xcart\App\Form\Fields\Select2Field;
use Xcart\App\Form\Fields\TextAreaField;
use Xcart\App\Form\ModelForm;

class OrderStatusForm extends ModelForm
{
    public array $exclude = ['code', 'orderby'];

    public function getModel()
    {
        return new OrderStatusModel();
    }

    public function getFields()
    {
        /** @var OrderStatusModel $model */
        $model = $this->getInstance();
        return [
            'description' => [
                'class' => TextAreaField::class,
            ],
            'availability_statuses' => [
                'class' => Select2Field::class,
                'choices' => function () use ($model) {
                    $result = [];
                    $manager = OrderStatusModel::objects()->filter(['type' => $model->type]);
                    $manager->exclude(['status_id__in' => [$model->status_id]]);
                    /** @var OrderStatusModel $status */
                    foreach ($manager->order(['type', 'orderby']) as $status) {
                        $result[$status->pk] = "{$status->type}: {$status}";
                    }
                    return $result;
                },
                'selected' => OrderStatusAvailabilityModel::objects()->filter(['source_status_id' => $model->status_id])->valuesList(['destination_status_id'], true),
                'html' => [
                    'class' => 'select2-field',
                    'style' => 'width:100%',
                ],
                'multiple' => true,
            ],
        ];
    }

    public function getName()
    {
        return '';
    }
}
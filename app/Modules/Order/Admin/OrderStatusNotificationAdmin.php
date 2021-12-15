<?php

namespace Modules\Order\Admin;

use Modules\Admin\Contrib\Admin;
use Modules\Order\Forms\FilterForms\OrderStatusNotificationFilterForm;
use Modules\Order\Forms\OrderStatusNotificationForm;
use Modules\Order\Models\OrderStatusNotificationModel;
use Xcart\App\Form\Form;
use Xcart\App\Form\ModelForm;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Model;

class OrderStatusNotificationAdmin extends Admin
{
    public static bool $public = false;
    public string $createTemplate = 'admin/forms/_create.tpl';
    public string $updateTemplate = 'admin/forms/_update.tpl';
    public function getForm(): ?ModelForm
    {
        return new OrderStatusNotificationForm();
    }

    public function getModel(): OrderStatusNotificationModel
    {
        return new OrderStatusNotificationModel();
    }

    public function getListColumns(): array
    {
        return [
            'customer_subject',
            'code',
            'enabled',
            'lang'
        ];
    }

    public function getItemProperty(Model $item, $property)
    {
        switch ($property) {
            case 'lang':
                return (string)$item->lang;
        }
        return parent::getItemProperty($item, $property);
    }

    public static function getName(): string
    {
        return 'Order Status';
    }

    public function getListItemActions(): array
    {
        return [
            'update',
        ];
    }

    public function getFilterForm(): ?Form
    {
        return new OrderStatusNotificationFilterForm();
    }

    public function isAjaxCreate(): bool
    {
        return true;
    }

    public function isAjaxUpdate(): bool
    {
        return true;
    }
}
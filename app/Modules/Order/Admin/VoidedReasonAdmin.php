<?php


namespace Modules\Order\Admin;


use Modules\Admin\Contrib\NestedAdmin;
use Modules\Order\Forms\VoidedReasonForm;
use Modules\Order\Models\VoidedReasonModel;

class VoidedReasonAdmin extends NestedAdmin
{
    public ?string $sort = 'pos';

    public function getListColumns(): array
    {
        return [
            'name',
        ];
    }

    public function getForm(): VoidedReasonForm
    {
        return new VoidedReasonForm();
    }

    public function getModel()
    {
        return new VoidedReasonModel();
    }

    public static function getName()
    {
        return 'Voided reasons';
    }

    public function getListItemActions()
    {
        return [
            'update',
        ];
    }

    public function isAjaxUpdate(): bool
    {
        return true;
    }

    public function isAjaxCreate(): bool
    {
        return true;
    }
}
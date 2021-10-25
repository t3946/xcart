<?php

namespace Modules\Sites\Admin;

use Modules\Admin\Contrib\Admin;
use Modules\Admin\Traits\AdminTrait;
use Modules\Sites\Forms\AddressForm;
use Modules\Sites\Models\AddressModel;
use Xcart\App\Orm\Model;

class AddressAdmin extends Admin
{
    use AdminTrait;

    public function getForm(): AddressForm
    {
        $form = new AddressForm();
        $form->admin = $this;
        return $form;
    }

    public function getListColumns(): array
    {
        return [
            'name',
            'company',
            'address_info',
        ];
    }

    public function getItemProperty(Model $item, $property)
    {
        switch ($property) {
            case 'address_info':
                return "$item->address, $item->address_state, $item->country";
                break;
        }

        return parent::getItemProperty($item, $property);
    }

    public static function getName(): string
    {
        return 'Address list';
    }

    public function getModel(): AddressModel
    {
        return new AddressModel();
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
<?php

namespace Modules\Sites\Admin;

use Modules\Admin\Contrib\ListViewAdmin;
use Modules\Sites\Forms\SiteAddressForm;
use Modules\Sites\Models\AddressModel;
use Modules\Sites\Models\SitesAddressesModel;
use Xcart\App\Orm\Model;

class SiteAddressesAdmin extends ListViewAdmin
{
    public ?string $ownerField = 'site_id';
    public static bool $public = false;

    public function getListColumns(): array
    {
        return ['full_address'];
    }

    public function getItemProperty(Model $item, $property)
    {
        switch ($property) {
            case 'full_address':
                return "{$item->address->name}, {$item->address->address}";
        }

        return parent::getItemProperty($item, $property);
    }

    public function getModel(): SitesAddressesModel
    {
        return new SitesAddressesModel();
    }

    public function getForm(): SiteAddressForm
    {
        $form = new SiteAddressForm();
        $form->admin = $this;
        return $form;
    }

    public function getAllUrl(): string
    {
        if ($this->ownerPk->id) {
            return (new SitesAdmin)->getUpdateUrl($this->ownerPk->id);
        }
        if ($this->ownerPk && is_numeric($this->ownerPk)) {
            return (new SitesAdmin)->getUpdateUrl($this->ownerPk);
        }

        return parent::getAllUrl();

    }
}
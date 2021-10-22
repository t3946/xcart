<?php

namespace Modules\Sites\Admin;

use Modules\Admin\Contrib\ListViewAdmin;
use Modules\Sites\Forms\SiteSocialForm;
use Modules\Sites\Models\SiteSocialsModel;
use Xcart\App\Orm\Model;

class SiteSocialsAdmin extends ListViewAdmin
{
    public ?string $ownerField = 'site_id';
    public static bool $public = false;

    public function getForm(): SiteSocialForm
    {
        $form = new SiteSocialForm();
        $form->admin = $this;
        return $form;
    }

    public function getListColumns(): array
    {
        return ['type', 'url'];
    }

    public function getItemProperty(Model $item, $property)
    {
        switch ($property) {
            case 'type':
            case 'url':
                return "{$item->social->$property}";
        }

        return parent::getItemProperty($item, $property);
    }

    public function getModel(): SiteSocialsModel
    {
        return new SiteSocialsModel();
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
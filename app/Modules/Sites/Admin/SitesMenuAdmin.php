<?php
namespace Modules\Sites\Admin;
use Modules\Admin\Contrib\ListViewAdmin;
use Modules\Sites\Forms\SitesMenuForm;
use Modules\Sites\Models\SiteMenuModel;
use Modules\Sites\Models\SitesMenuModel;
use Xcart\App\Orm\Model;

class SitesMenuAdmin extends ListViewAdmin
{
    public ?string $ownerField = 'site_id';
    public static bool $public = false;

    public function getForm(): SitesMenuForm
    {
        $form = new SitesMenuForm();
        $form->admin = $this;
        return $form;
    }

    public function getListColumns(): array
    {
        return ['menu'];
    }

    public function getSuggestionColumns(): array
    {
        return [
            'address' => [
                'class' => SiteMenuModel::class,
                'columns' => [
                    'name'
                ],
            ],
        ];
    }

    public function getItemProperty(Model $item, $property)
    {
        switch ($property) {
            case 'menu':
                return "{$item->menu->name}";
        }

        return parent::getItemProperty($item, $property);
    }

    public static function getItemName(): string
    {
        return 'Menu';
    }

    public function getModel(): SitesMenuModel
    {
        return new SitesMenuModel();
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
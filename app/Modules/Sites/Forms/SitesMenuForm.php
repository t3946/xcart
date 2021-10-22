<?php

namespace Modules\Sites\Forms;

use Modules\Forms\Models\TemplateModel;
use Modules\Sites\Models\SiteMenuModel;
use Modules\Sites\Models\SitesMenuModel;
use Xcart\App\Form\Fields\DropDownField;
use Xcart\App\Form\ModelForm;

class SitesMenuForm extends ModelForm {
    public array $exclude = ['site'];

    public function getFields(): array
    {
        return [
            'menu' => [
                'class' => DropDownField::class,
                'choices' => static function (): array {
                    foreach (SiteMenuModel::objects()->filter(['parent_id__isnull' => true]) as $menu_model) {
                        $list[$menu_model->menu_id] = $menu_model->name;
                    }
                    return $list ?? [];
                },
            ],
        ];
    }

    public function getModel(): SitesMenuModel
    {
        return new SitesMenuModel();
    }
}
<?php

namespace Modules\Sites\Admin;

use Modules\Admin\Contrib\Admin;
use Modules\Sites\Forms\SocialForm;
use Modules\Sites\Models\SocialModel;
use Xcart\App\Orm\Model;

class SocialsAdmin extends Admin
{

    public function getForm() : SocialForm
    {
        $form = new SocialForm();
        $form->admin = $this;
        return $form;
    }

    public function getListColumns(): array
    {
        return [
            'type',
            'logo_path',
            'url',
        ];
    }

    public function getItemProperty(Model $item, $property)
    {
        switch ($property) {
            case 'logo_path':
                return "<div style='text-align: center'><img src=\"{$item->getLogoPath()}\" title=\"{$item}\" width='60' alt='Logo $item->type'/></div>";
        }

        return parent::getItemProperty($item, $property);
    }

    public function getModel(): SocialModel
    {
        return new SocialModel();
    }

    public static function getName(): string
    {
        return 'Socials networks';
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
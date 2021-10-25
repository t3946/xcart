<?php

namespace Modules\Core\Admin;

use Modules\Admin\Contrib\Admin;
use Modules\Core\Models\GlobalConfigModel;
use Xcart\App\Form\ModelForm;

class GeneralSettingsAdmin extends Admin
{
    public string $allTemplate = 'admin/general_settings.tpl';

    public function getForm(): ?ModelForm
    {
        return null;
    }

    public function getModel()
    {
        return new GlobalConfigModel();
    }

    public static function getName()
    {
        return 'General Settings';
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
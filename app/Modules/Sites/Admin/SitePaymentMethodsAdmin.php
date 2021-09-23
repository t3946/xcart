<?php

namespace Modules\Sites\Admin;

use Modules\Admin\Contrib\Admin;
use Modules\Admin\Traits\AdminTrait;
use Modules\Sites\Forms\SitePaymentMethodForm;
use Modules\Sites\Models\PaymentMethodModel;
use Xcart\App\Orm\Model;

class SitePaymentMethodsAdmin extends Admin
{
    public ?string $sort = 'position';

    use AdminTrait;

    public function getListColumns()
    {
        return [
            'name',
            'logo',
            'is_active',
        ];
    }

    public function getForm()
    {
        return new SitePaymentMethodForm();
    }

    public function getModel()
    {
        return new PaymentMethodModel();
    }

    public static function getName()
    {
        return 'Payment methods';
    }

    public function getItemProperty( Model $item, $property )
    {
        if ($property === 'logo') {
            $src = $item->getAttribute('logo');
            $title = $item->getAttribute('name');
            return "<img src=\"/$src\" alt=\"$title logo\" title=\"$title\" height=\"60\" />";
        }

        return parent::getItemProperty($item, $property );
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

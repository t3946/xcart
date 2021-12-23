<?php

namespace Modules\Cart\Admin;

use Modules\Admin\Contrib\Admin;
use Modules\Cart\Models\CartModel;
use Xcart\App\Form\ModelForm;

class ShoppingCartAdmin extends Admin
{
    public string $allTemplate = 'admin/shopping_cart.tpl';

    public function getForm(): ?ModelForm
    {
        return null;
    }

    public function getModel(): CartModel
    {
        return new CartModel();
    }

    public static function getName(): string
    {
        return 'Shopping cart';
    }
}
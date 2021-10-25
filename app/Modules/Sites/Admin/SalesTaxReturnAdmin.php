<?php


namespace Modules\Sites\Admin;



use Modules\Sites\Forms\Corporates\SalesTaxReturnForm;

class SalesTaxReturnAdmin extends TaxReturnAdmin
{
    public static bool $public = false;
    public function getForm(): SalesTaxReturnForm
    {
        return new SalesTaxReturnForm;
    }
}
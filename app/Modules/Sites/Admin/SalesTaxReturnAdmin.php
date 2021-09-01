<?php


namespace Modules\Sites\Admin;



use Modules\Sites\Forms\Corporates\SalesTaxReturnForm;

class SalesTaxReturnAdmin extends TaxReturnAdmin
{
    public static $public = false;
    public function getForm()
    {
        return new SalesTaxReturnForm;
    }
}
<?php


namespace Modules\Sites\Admin;


use Modules\Admin\Contrib\ListViewAdmin;
use Modules\Sites\Forms\Corporates\TaxReturnForm;
use Modules\Sites\Forms\Corporates\VatTaxReturnForm;
use Modules\Sites\Models\TaxReturnModel;

class VatTaxReturnAdmin extends TaxReturnAdmin
{
    public static bool $public = false;

    public function getForm(): VatTaxReturnForm
    {
        return new VatTaxReturnForm;
    }
}
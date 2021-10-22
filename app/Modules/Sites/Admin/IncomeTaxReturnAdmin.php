<?php


namespace Modules\Sites\Admin;


use Modules\Sites\Forms\Corporates\IncomeTaxReturnForm;

class IncomeTaxReturnAdmin extends TaxReturnAdmin
{
    public static bool $public = false;
    public function getForm(): IncomeTaxReturnForm
    {
        return new IncomeTaxReturnForm;
    }
}
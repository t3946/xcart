<?php


namespace Modules\Sites\Admin;


use Modules\Sites\Forms\Corporates\IncomeTaxReturnForm;

class IncomeTaxReturnAdmin extends TaxReturnAdmin
{
    public static $public = false;
    public function getForm()
    {
        return new IncomeTaxReturnForm;
    }
}
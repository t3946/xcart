<?php


namespace Modules\Sites\Admin;


use Modules\Sites\Forms\Corporates\IncomeTaxReturnForm;

class IncomeTaxReturnAdmin extends TaxReturnAdmin
{
    public function getForm()
    {
        return new IncomeTaxReturnForm;
    }
}
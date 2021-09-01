<?php


namespace Modules\Faxes\Admin;


use Modules\Admin\Contrib\Admin;
use Modules\Faxes\Forms\FaxForm;
use Modules\Faxes\Models\FaxModel;

class FaxAdmin extends Admin
{

    public function getForm()
    {
        return new FaxForm;
    }

    public function getModel()
    {
        return new FaxModel;
    }
}
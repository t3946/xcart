<?php


namespace Modules\Sites\Admin;


use Modules\Admin\Contrib\Admin;
use Modules\Sites\Forms\Corporates\CorporatesForm;
use Modules\Sites\Models\CorporateModel;

class CorporatesAdmin extends Admin
{
    public function getListColumns()
    {
        return ['name',];
    }

    public function getForm()
    {
        return new CorporatesForm;
    }

    public static function getName()
    {
        return 'Corporates';
    }

    public function getModel()
    {
        return new CorporateModel;
    }
}
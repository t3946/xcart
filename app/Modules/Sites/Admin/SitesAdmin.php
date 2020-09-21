<?php


namespace Modules\Sites\Admin;


use Modules\Admin\Contrib\Admin;

class SitesAdmin extends Admin
{
    public static $public = false;
    public function getForm()
    {

    }

    public static function getName()
    {
        return 'Storefronts';
    }
}
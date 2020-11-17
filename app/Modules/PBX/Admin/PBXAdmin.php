<?php


namespace Modules\PBX\Admin;


use Modules\Admin\Contrib\Admin;
use Modules\PBX\Models\PbxAnveoCallModel;

class PBXAdmin extends Admin
{
    public function getListColumns()
    {
        return [
            'orders',
            'e164',
            'cname',
            'direction',
            'account',
            'start_at',
            'duration',
            'audio',
        ];
    }

    public function getForm()
    {
    }

    public function getModel()
    {
        return new PbxAnveoCallModel;
    }

    public static function getName()
    {
        return 'Call recordings';
    }

}
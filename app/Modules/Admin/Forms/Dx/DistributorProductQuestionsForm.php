<?php


namespace Modules\Admin\Forms\Dx;


use Modules\Admin\Admin\DxContactAdmin;
use Modules\Distributor\Models\DistributorModel;

class DistributorProductQuestionsForm extends DistributorForm
{

    public function getFieldsets()
    {
        return [[

        ]];
    }

    public function getFields()
    {
        return [
        ];
    }


    public function render($template = null, array $fields = [], $extra = null)
    {
        $admin = new DxContactAdmin();
        $admin->columnDefaultTemplate = 'admin/list/columns/default.tpl';
        $admin->dxModel = $this->getInstance();
        $admin->sort = '';
        $admin->editable = false;
        $admin->section = 16;
        return $admin->render('admin/distributor/form/list/_list.tpl', [
            'objects' => $admin->getQuerySet()->filter(['pq' => 'Y']),
            'columns' => $admin->buildListColumns(),
        ]);
    }
}
<?php

namespace Modules\Admin\Admin;

use DateTime;
use Modules\Admin\Forms\Dx\DistributorForm;
use Modules\Distributor\Models\DistributorModel;
use Modules\Goods\Admin\ProductAdmin;
use Modules\Goods\Models\ProductImageModel;
use Modules\Goods\Models\ProductModel;
use Xcart\App\Orm\Model;

class DxProductsAdmin extends ProductAdmin
{
    public DistributorModel $dxModel;

    public string $allTemplate = 'admin/distributor/dx_52.tpl';

    public function getListColumns() :  array
    {
        return [
            'image',
            'productcode',
            'mpn',
            'product',
            'add_date',
            'forsale',
        ];
    }

    public function getListGroupActions()
    {
        return [];
    }


    public function getQuerySet()
    {
        return parent::getQuerySet()->filter([
            'manufacturerid' => $this->dxModel->pk,
            'forsale' => 'Y'
        ])->order(['-pk']);
    }

    public static function getName() : string
    {
        return 'Active products';
    }

    public function renderInternal($view, $params)
    {
        $params = array_merge($params, [
            'distributorModel' => $this->dxModel ?? null,
            'section' => $this->section,
        ]);

        if (($this->dxModel ?? null) && empty($params['form'])) {
            $form = new DistributorForm();
            $form->setInstance($this->dxModel);
            $params['form'] = $form;
        }
        parent::renderInternal($view, $params);
    }

}
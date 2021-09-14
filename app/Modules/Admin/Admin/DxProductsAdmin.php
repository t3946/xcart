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

    public $allTemplate = 'admin/distributor/dx_52.tpl';

    public function getListColumns()
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

    public static function getName()
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

    public function getItemProperty(Model $item, $property)
    {
        /** @var ProductModel $item */
        if ($property === 'image') {
            return ($image = $item->getMainImage())
                ? "<div style='text-align: center'><img src=\"{$image->getCdnURL(ProductImageModel::IMAGE_SIZE_THUMB)}\" width='60' /></div>"
                : '';
        }
        if ($property === 'mpn') {
            return "<a href={$item->getDistributorUrl()} target='_blank'>{$item->getMpn()}</a>";
        }

        if ($property === 'product') {
            $len = mb_strlen($item->$property);
            $name = ($len > 70) ? mb_substr($item->$property, 0, 70) . '...' : $item->$property;
            return "<a target='_blank' href='{$item->getAbsoluteUrl(true)}'>{$name}</a>";
        }

        if ($property === 'forsale') {
            return $item->forsale === 'Y' ? 'Active' : 'Inactive';
        }
        if ($property === 'add_date') {
            return (new DateTime())->setTimestamp($item->add_date)->format('d-M-Y');
        }

        return parent::getItemProperty($item, $property);
    }

}
<?php

namespace Modules\Admin\Admin;

use DateTime;
use Modules\Admin\Forms\Dx\DistributorForm;
use Modules\Distributor\Models\DistributorModel;
use Modules\Goods\Admin\ProductAdmin;
use Modules\Goods\Models\ProductModel;
use Xcart\App\Orm\Model;

class DxProductsAdmin extends ProductAdmin
{
    public DistributorModel $dxModel;

    public $allTemplate = 'admin/distributor/dx_52.tpl';


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
        /** @var ProductModel $image */
        if ($property === 'image') {
            return ($image = $item->getMainImage())
                ? "<div style='text-align: center'><img src=\"/{$image->getCdnURL(174)}\" title=\"{$item}\" width='60' /></div>"
                : '';
        }
        if ($property === 'forsale') {
            return $item->forsale === 'Y' ? 'Active' : 'Inactive';
        }
        if ($property === 'add_date') {
            return (new DateTime())->setTimestamp($item->add_date)->format('d-M-Y H:i:s');
        }

        return parent::getItemProperty($item, $property);
    }

}
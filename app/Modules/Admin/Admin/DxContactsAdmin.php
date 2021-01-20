<?php


namespace Modules\Admin\Admin;


use Modules\Admin\Contrib\Admin;
use Modules\Admin\Forms\Dx\DistributorContactsForm;
use Modules\Distributor\Models\DistributorContactsModel;
use Modules\Distributor\Models\DistributorModel;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Model;

class DxContactsAdmin extends Admin
{
    public DistributorModel $dxModel;

    public $allTemplate = 'admin/distributor/dx_3.tpl';
    public ?string $sort = 'position';

    public function getForm()
    {
        return new DistributorContactsForm();
    }

    public function getModel()
    {
        return new DistributorContactsModel();
    }

    public function getQuerySet()
    {
        if ($this->dxModel) {
            return parent::getQuerySet()->filter(['manufacturerid' => $this->dxModel->manufacturerid]);
        }
        return parent::getQuerySet();
    }

    public function getListColumns()
    {
        return [
            'contact_name',
            'distributor_field_name',
            'email',
            'phone',
            'ext',
            'fax',
            'utility'
        ];
    }

    public function isAjaxCreate(): bool
    {
        return true;
    }

    public function isAjaxUpdate(): bool
    {
        return true;
    }

    public function renderInternal($view, $params)
    {
        parent::renderInternal($view, array_merge($params, [
            'distributorModel' => $this->dxModel ?? null,
            'section' => $this->section,
        ]));
    }

    public function getSortUrl()
    {
        return Xcart::app()->router->url('admin:dx_contact_sort', [
            'mid' => $this->dxModel->pk
        ]);
    }

    public function getItemProperty(Model $item, $property)
    {
        if ($property === 'utility') {
            return implode("", array_map(static fn($u) => "<div style='text-align: center; padding: 0 5px; margin-right: 2px; margin-top: 3px; background-color: #e4e4e4; border: 1px solid #aaa; border-radius: 4px;'>{$u}</div>",
                $item->utility->all()));
        }
        return parent::getItemProperty($item, $property);
    }
}
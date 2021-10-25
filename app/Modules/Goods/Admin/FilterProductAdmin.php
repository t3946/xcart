<?php

namespace Modules\Goods\Admin;

use Modules\Admin\Contrib\ListViewAdmin;
use Modules\Goods\Forms\ProductFilterValueForm;
use Modules\Goods\Models\FilterValueModel;
use Modules\Goods\Models\ProductModel;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Model;

class FilterProductAdmin extends ListViewAdmin
{
    public $ownerModel = ProductModel::class;
    public string $owner_model_field = 'filter_values';
    public ?string $ownerField = 'fv_id';
    public string $related_field = 'fv_id';
    public string $through_field = 'productid';

    public function getForm() : ProductFilterValueForm
    {
        $form = new ProductFilterValueForm();
        $form->admin = $this;
        return $form;
    }

    public function getListColumns(): array
    {
        return [
            'filter',
            'fv_name',
        ];
    }

    public function getModel()
    {
        return new FilterValueModel();
    }

    public function getItemProperty(Model $item, $property)
    {
        switch ($property) {
            case 'filter':
                return $item->$property ? $item->$property->f_name : 'None';
        }
        return parent::getItemProperty($item, $property);
    }

    public function getUpdateUrl($pk = null): string
    {
        return Xcart::app()->router->url('admin:update_owned', [
            'module' => static::getModuleName(),
            'admin' => static::classNameShort(),
            'pk' => $pk ?? $this->getModelPk(),
            'owner' => $this->ownerPk,
        ]);
    }
}
<?php

namespace Modules\Goods\Admin;


use Modules\Admin\Contrib\ListViewAdmin;
use Modules\Goods\Forms\ProductOptionsAdminForm;
use Modules\Goods\Models\OptionNewModel;
use Modules\Goods\Models\ProductOptionModel;
use Xcart\App\Form\ModelForm;
use Xcart\App\Orm\Model;

class ProductOptionsAdmin extends ListViewAdmin
{
    public ?string $ownerField = 'product_id';
    public ?string $sort = 'position';

    public function getExcludedColumns() : array
    {
        return ['product', 'variants'];
    }

    /**
     * @return ModelForm
     */
    public function getForm() : ProductOptionsAdminForm
    {
        return new ProductOptionsAdminForm();
    }

    public function getModel()
    {
        return new ProductOptionModel();
    }

    public function getListColumns() : array
    {
        return ['(string)'];
    }

    public static function getItemName()
    {
        return 'Option';
    }

    public function getAvailableListColumns()
    {
        return array_merge(parent::getAvailableListColumns(),[
            'var' => [
                'title' => 'VARIANTS',
                'template' => $this->columnDefaultTemplate,
                'order' => 'position'
            ]
        ]);
    }

    public function getItemProperty(Model $item, $property)
    {
        return nl2br(implode("\n", $item->variants->order(['position'])->all()));
    }

    public function getSuggestionColumns()
    {
        return [
            'option' => [
                'class' => OptionNewModel::class,
                'columns' => [
                    'title', 'pk'
                ],
                'filter' => [
                ]
            ],
        ];
    }

    public function getAllUrl()
    {
        if ($this->ownerPk->id) {
            return (new ProductAdmin)->getUpdateUrl($this->ownerPk->id);
        }
        if ($this->ownerPk && is_numeric($this->ownerPk)) {
            return (new ProductAdmin)->getUpdateUrl($this->ownerPk);
        }

        return parent::getAllUrl();
    }

}
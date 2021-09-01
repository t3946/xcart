<?php

namespace Modules\Goods\Admin;


use Modules\Admin\Contrib\Admin;
use Modules\Goods\Forms\OptionAdminForm;
use Modules\Goods\Models\OptionNewModel;
use Xcart\App\Form\ModelForm;
use Xcart\App\Orm\Model;

class OptionAdmin extends Admin
{
    /**
     * @return ModelForm
     */
    public function getForm()
    {
        return new OptionAdminForm();
    }

    public function getModel()
    {
        return new OptionNewModel();
    }

    public static function getName()
    {
        return 'Options';
    }

    public function getAvailableListColumns()
    {
        return [
            'id' => [
                'title' => 'ID',
                'template' => $this->columnDefaultTemplate,
                'order' => 'id'
            ],
            'title' => [
                'title' => 'Option name',
                'template' => $this->columnDefaultTemplate,
                'order' => 'title'
            ],
            'values' => [
                'title' => 'Option variants',
                'template' => $this->columnDefaultTemplate,
                'order' => 'value'
            ],
        ];
    }

    public function getExcludedColumns()
    {
        return ['variants', '(string)'];
    }

    public function getItemProperty(Model $item, $property)
    {
        if ($property === 'values') {
            return nl2br(implode("\n", $item->variants->all()));
        }

        return parent::getItemProperty($item, $property);

    }

    public function getListColumns()
    {
        return ['id', 'title', 'values'];
    }

}
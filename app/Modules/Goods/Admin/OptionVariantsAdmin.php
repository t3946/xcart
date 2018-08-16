<?php

namespace Modules\Goods\Admin;


use Modules\Admin\Contrib\ListViewAdmin;
use Modules\Goods\Forms\OptionVariantsAdminForm;
use Modules\Goods\Models\OptionVariantModel;
use Xcart\App\Form\ModelForm;

class OptionVariantsAdmin extends ListViewAdmin
{
    public $ownerField = 'option';

    /**
     * @return ModelForm
     */
    public function getForm()
    {
        return new OptionVariantsAdminForm();
    }

    public function getModel()
    {
        return new OptionVariantModel();
    }

    public static function getName()
    {
        return 'Variants';
    }

    public function getAvailableListColumns()
    {
        return [
            'id' => [
                'title' => 'ID',
                'template' => $this->columnDefaultTemplate,
                'order' => 'id'
            ],
            'name' => [
                'title' => 'Variant',
                'template' => $this->columnDefaultTemplate,
                'order' => 'name'
            ],
            'value' => [
                'title' => 'Value',
                'template' => $this->columnDefaultTemplate,
                'order' => 'value'
            ]
        ];
    }


    public function getListColumns()
    {
        return ['id', 'name', 'value'];
    }

    public function getAllUrl()
    {
        if ($this->ownerPk->id) {
            return (new OptionAdmin)->getUpdateUrl($this->ownerPk->id);
        }
        if ($this->ownerPk && is_numeric($this->ownerPk)) {
            return (new OptionAdmin)->getUpdateUrl($this->ownerPk);
        }

        return parent::getAllUrl();

    }
}
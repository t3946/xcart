<?php
namespace Xcart\App\Form\Fields;

use Modules\Admin\Contrib\ListViewAdmin;
use Xcart\App\Orm\Fields\RelatedField;
use Xcart\App\Orm\Model;

class ListViewField extends Field
{
    /**
     * @deprecated
     * @var string
     */
    public $inputTemplate = null;
    public $listTemplate = 'forms/field/list_view/list.tpl';
    public $rowTemplate = 'forms/field/list_view/row.tpl';
    public $emptyTemplate = 'forms/field/list_view/empty.tpl';
    public $fieldType = 'list_view';
    public $fieldTemplate = 'forms/field/list_view/field.tpl';

    /** @var \Modules\Admin\Contrib\Admin|null  */
    public $adminClass = null;

    public $defaultOrder = [];

    /**
     * @var array
     *
     *  Example:
     *  [
     *      'name',
     *      'code',
     *      'items' => [
     *          'title' => 'Items count',
     *          'template' => 'forms/field/list_view/calculate.tpl',
     *      ],
     *  ]
     */
    public $columns = [];


    public function setValue($value)
    {
        return null;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return null;
    }

    public function getRenderValue()
    {
        /** @var Model $model */
        $model = $this->getForm()->getInstance();
        $field = $model->getField($this->getName());

        if ($field instanceof RelatedField) {
            /** @var  RelatedField $field */
            $manager = $field->getManager();

            return $manager->order($this->defaultOrder)->all();
        }
        return [];
    }

    public function renderInput()
    {
        /** @var Model $model */
        $model = $this->getForm()->getInstance();
        if ($model->getIsNewRecord()) {
            return $this->innerRender($this->emptyTemplate, []);
        }

        /** @var ListViewAdmin $admin */
        $admin = new $this->adminClass();
        $admin->ownerPk = $this->getForm()->getInstance()->pk;
        $admin->ownerField = $this->getName();
        $qs = $admin->getQuerySet();
        $qs = $admin->fixSort($qs);

        return $this->innerRender($this->listTemplate, [
            'field' => $this,
            'html' => $this->buildAttributesInput(),
            'id' => $this->getHtmlId(),
            'objects' => $this->getRenderValue(),
            'name' => $this->getHtmlName(),
            'columns' => $admin->buildListColumns(),
            'admin' => $admin,
            'canSort' => $admin->getCanSort($qs)
        ]);
    }
}
<?php

namespace Modules\Forms\Admin;

use Modules\Admin\Contrib\Admin;
use Modules\Distributor\Models\DistributorModel;
use Modules\Forms\Forms\EmailSorterForm;
use Modules\Forms\Models\EmailModel;
use Modules\Forms\Models\EmailSorterModel;
use Modules\Order\Models\OrderModel;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Model;


class EmailSorterAdmin extends Admin
{

    public function getListColumns()
    {
        return ['type', 'filter_field', 'cond', 'value', 'entity', 'target', 'related_value'];
    }

    public function getSearchColumns()
    {
        return [];
    }


    public function getAvailableListColumns()
    {
        return [
            'type' => [
                'title' => 'Type',
                'template' => $this->columnDefaultTemplate,
            ],
            'filter_field' => [
                'title' => 'Field',
                'template' => $this->columnDefaultTemplate,
            ],
            'cond' => [
                'title' => 'Condition',
                'template' => $this->columnDefaultTemplate,
            ],
            'value' => [
                'title' => 'Value',
                'template' => $this->columnDefaultTemplate,
            ],
            'entity' => [
                'title' => 'Entity',
                'template' => $this->columnDefaultTemplate,
            ],
            'target' => [
                'title' => 'Target',
                'template' => $this->columnDefaultTemplate,
            ],
            'related_value' => [
                'title' => 'Related field',
                'template' => $this->columnDefaultTemplate,
            ],
        ];
    }

    public function getItemProperty(Model $item, $property)
    {
        if ($property === 'entity') {
            return $item->getField($property)->toText();
        }
        if ($property === 'cond') {
            return $item->getField($property)->toText();
        }
        if ($property === 'filter_field') {
            $email = new EmailModel;
            return $email->getField($item->getField($property)->getValue())->getVerboseName();
        }
        if ($property === 'target') {
            if ($id = $item->getField($property)->getValue()) {
                $class = $item->getField('entity')->getValue();
                /** @var Model $model */
                $model = new $class;
                return (string) $model::objects()->get([$model::getPrimaryKeyName() => $id]);
            }
        }

        return parent::getItemProperty($item, $property);
    }

    public function getForm()
    {
        return new EmailSorterForm();
    }

    public function getModel()
    {
        return new EmailSorterModel();
    }

    public static function getName()
    {
        return 'Automatic Email Sorter';
    }

    public function getListGroupActions()
    {
        return ['add'];
    }

    public function getSuggestionColumns()
    {
        return [
            'distributor' => [
                'class' => DistributorModel::class,
                'columns' => [
                    'manufacturer',
                    'code',
                ],
            ],
            'order' => [
                'class' => OrderModel::class,
                'columns' => [
                    'orderid'
                ],
            ],
        ];
    }

    public function getSuggestionUrl($entity)
    {
        /** @var Model $class */
        $class = new $entity;
        $entity = strtolower(rtrim($class::getShortName(), 'Model'));

        if ($this->checkSuggestionEntity($entity)) {
            return Xcart::app()->router->url('admin:suggestion', [
                'module' => static::getModuleName(),
                'admin' => static::classNameShort(),
                'entity' => $entity,
            ]);
        }

        return null;
    }
}


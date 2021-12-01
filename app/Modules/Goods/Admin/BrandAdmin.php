<?php

namespace Modules\Goods\Admin;

use Modules\Admin\Contrib\Admin;
use Modules\Brand\Models\BrandModel;
use Modules\Goods\Forms\BrandFilterForm;
use Modules\Goods\Forms\BrandForm;
use Xcart\App\Form\Form;
use Xcart\App\Orm\Model;
use Xcart\App\Orm\QuerySet;

class BrandAdmin extends Admin
{
    public ?string $order = 'brand';

    public function getForm(): BrandForm
    {
        return new BrandForm();
    }

    public function getListColumns(): array
    {
        return [
            'brand',
            'image',
            'products',
            'avail'
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

    public function getModel(): Model
    {
        return new BrandModel();
    }

    public function getItemProperty(Model $item, $property)
    {
        switch ($property) {
            case 'products':
                return "<a target='_blank' href='{$item->getAbsoluteUrl(true)}'>{$item->products->count()}</a>";
            case 'image':
                return (!is_null($item->getImage()))
                    ? "<div style='text-align: center'>
                            <img src=\"/{$item->getImage()}\" title=\"{$property}\" width='60' />
                       </div>"
                    : '';

        }
        return parent::getItemProperty($item, $property);
    }

    public function getFilterForm(): ?Form
    {
        return new BrandFilterForm();
    }

    public function handleFilter($qs, $form)
    {
        $filter = parent::handleFilter($qs, $form);
        foreach ($form->getAttributes() as $key => $value) {
            if ($value) {
                switch ($key) {
                    case 'manufacture':
                        $params_filter = 'products__manufacturerid';
                        if (is_array($value)) {
                            $params_filter .= '__in';
                        }
                        $filter->distinct()->filter([$params_filter => $value]);
                        break;
                }
            }
        }
        return $filter;
    }
}
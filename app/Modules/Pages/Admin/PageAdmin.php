<?php

namespace Modules\Pages\Admin;

use Modules\Admin\Contrib\Admin;
use Modules\Pages\Forms\PagesForm;
use Modules\Pages\Models\Page;
use Xcart\App\Orm\Model;

/**
 * Class PageAdmin
 * @package Modules\Pages
 */
class PageAdmin extends Admin
{

    public function getListColumns()
    {
        return [
            'name',
            'url',
            'is_published',
        ];
    }

    public function getSearchColumns()
    {
        return ['name'];
    }

    public function getAvailableListColumns()
    {
        return [
            'id' => [
                'title' => 'ID',
                'template' => $this->columnDefaultTemplate,
                'order' => 'id'
            ],
        ];
    }

    public function getForm()
    {
        return new PagesForm();
    }

    public function getModel()
    {
        return new Page();
    }

    public static function getName()
    {
        return 'Pages';
    }

    public function getItemProperty(Model $item, $property)
    {
        if ($property === 'sites') {
            return nl2br(implode("\n", $item->sites->all()));
        }
        if ($property === 'language') {
            return (string) $item->language;
        }

        return parent::getItemProperty($item, $property);

    }

}


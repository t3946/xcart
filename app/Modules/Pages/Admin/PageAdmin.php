<?php

namespace Modules\Pages\Admin;

use Modules\Admin\Contrib\Admin;
use Modules\Admin\Contrib\NestedAdmin;
use Modules\Pages\Forms\PagesForm;
use Modules\Pages\Models\Page;
use Modules\Pages\PagesModule;
use Xcart\App\Orm\Model;

/**
 * Class PageAdmin
 * @package Modules\Pages
 */
class PageAdmin extends Admin
{
//    public $linkColumn = 'name';

    public function getListColumns()
    {
        return [];
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

        return parent::getItemProperty($item, $property);

    }

}


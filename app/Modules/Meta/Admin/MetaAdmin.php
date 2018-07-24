<?php

namespace Modules\Meta\Admin;

use Modules\Admin\Contrib\Admin;
use Modules\Meta\Forms\MetaForm;
use Modules\Meta\MetaModule;
use Modules\Meta\Models\Meta;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Model;

class MetaAdmin extends Admin
{
    public function getSearchColumns()
    {
        return ['url', 'title', 'description', 'keywords'];
    }

    public function getColumns()
    {
        $columns = ['title', 'url'];
        if (Xcart::app()->getModule('Meta')->onSite) {
            $columns[] = 'site';
        }
        return $columns;
    }

    public function getItemProperty(Model $item, $property)
    {
        if ($property === 'site') {
            return nl2br($item->site->code);
        }

        return parent::getItemProperty($item, $property);

    }

    public function getModel()
    {
        return new Meta;
    }

    public function getForm()
    {
        return new MetaForm;
    }

    public static function getName()
    {
        return MetaModule::t('Meta information');
    }
}

<?php
namespace Modules\Meta\Admin;

use Modules\Admin\Contrib\Admin;
use Modules\Meta\Forms\MetaTemplateForm;
use Modules\Meta\MetaModule;
use Modules\Meta\Models\MetaTemplate;

class MetaTemplateAdmin extends Admin
{
    public function getSearchColumns()
    {
        return ['code', 'title', 'description', 'keywords'];
    }

    public function getModel()
    {
        return new MetaTemplate;
    }

    public function getForm()
    {
        return new MetaTemplateForm();
    }

    public static function getName()
    {
        return MetaModule::t('Meta templates');
    }
}
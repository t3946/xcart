<?php
namespace Modules\Meta\Admin;

use Modules\Admin\Contrib\Admin;
use Modules\Meta\Forms\MetaTemplateForm;
use Modules\Meta\Models\MetaTemplate;

class MetaTemplateAdmin extends Admin
{
    public function getListColumns() : array
    {
        return [
            'code',
        ];
    }

    public function getSearchColumns()
    {
        return ['code', 'title', 'description'];
    }

    public function getModel()
    {
        return new MetaTemplate;
    }

    public function getForm(): MetaTemplateForm
    {
        return new MetaTemplateForm();
    }

    public static function getName()
    {
        return 'Meta templates';
    }
}
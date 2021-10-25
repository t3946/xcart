<?php

namespace Modules\Pages\Admin;

use Modules\Admin\Contrib\Admin;
use Modules\Pages\Forms\InfoBlockForm;
use Modules\Pages\Models\InfoBlock;
use Modules\Pages\PagesModule;
use Xcart\App\Form\ModelForm;
use Xcart\App\Orm\Model;

class InfoBlockAdmin extends Admin
{
    public function getListColumns(): array
    {
        return [
            'language',
            'name',
            'tag',
        ];
    }

    public function getSearchColumns()
    {
        return ['name', 'tag'];
    }

    public function getForm(): ModelForm
    {
        return new InfoBlockForm();
    }

    /**
     * @return Model
     */
    public function getModel()
    {
        return new InfoBlock();
    }

    public static function getName()
    {
        return 'Text blocks';
    }

    public static function getItemName()
    {
        return 'Text block';
    }

    public function getItemProperty(Model $item, $property)
    {
        if ($property === 'language') {
            return (string)$item->language;
        }

        return parent::getItemProperty($item, $property);
    }
}
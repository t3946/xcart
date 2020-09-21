<?php


namespace Modules\Sites\Admin;


use Modules\Admin\Contrib\Admin;
use Modules\Sites\Forms\Corporates\CorporatesForm;
use Modules\Sites\Models\CorporateModel;
use Xcart\App\Main\Xcart;

class CorporatesAdmin extends Admin
{
    public function getListColumns()
    {
        return ['name', 'country', 'state'];
    }

    public function getAvailableListColumns()
    {
        return [
            'name' => [
                'title' => 'Name',
                'template' => $this->columnDefaultTemplate,
                'order' => 'name'
            ],
        ];
    }

    public function getForm()
    {
        if ($this->section) {
            if ($sections = CorporatesForm::getSections()) {
                foreach ($sections as $section) {
                    if (isset($section[$this->section]) && $section[$this->section]['form']) {
                        return new $section[$this->section]['form'];
                    }
                }
            }
        }
        return new CorporatesForm;
    }

    public static function getName()
    {
        return 'Corporations';
    }

    public function getModel()
    {
        return new CorporateModel;
    }

    public function getUpdateUrl($pk = null)
    {
        $query = [];

        $sections = CorporatesForm::getSections()[0];

        return Xcart::app()->router->url('admin:update_section', [
            'module' => static::getModuleName(),
            'admin' => static::classNameShort(),
            'pk' => $pk ?: $this->getModelPk(),
            'section' => $this->section ?? key($sections)
        ], $query);
    }
}
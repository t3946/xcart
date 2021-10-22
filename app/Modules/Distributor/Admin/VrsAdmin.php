<?php

namespace Modules\Distributor\Admin;

use Modules\Admin\Contrib\Admin;
use Modules\Distributor\Forms\VrsFilterForm;
use Modules\Distributor\Forms\VrsForm;
use Modules\Distributor\Models\VrsModel;
use Xcart\App\Form\Form;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Model;

class VrsAdmin extends Admin
{
    public function getListColumns(): array
    {
        return [
            'sf',
            'company',
            'last_action',
            'status',
            'date',
            'telephone',
            'login',
            'password',
            'created_at'
        ];
    }

    public function getForm(): VrsForm
    {
        return new VrsForm();
    }

    public function getFilterForm(): ?Form
    {
        return new VrsFilterForm();
    }

    public function getAvailableListColumns(): array
    {
        return [
            'sf' => [
                'title' => 'SF',
            ],
            'company' => [
                'title' => 'Company',
            ],
            'last_action' => [
                'title' => 'Last action',
                'template' => $this->columnDefaultTemplate,
            ],
            'status' => [
                'title' => "Status",
            ],
            'date' => [
                'title' => 'Date',
            ],
            'created_at' => [
                'ADDED ON',
            ],
        ];
    }

    public function getItemProperty(Model $item, $property)
    {
        switch ($property) {
            case 'company':
                return "<a href='{$item->getWebSiteUrl()}'>{$item->$property}</a>";
        }
        return parent::getItemProperty($item, $property);
    }

    public function getModel(): Model
    {
        return new VrsModel();
    }

    public static function getName(): string
    {
        return 'VRS Team';
    }

    public function isAjaxCreate(): bool
    {
        return true;
    }

    public function isAjaxUpdate(): bool
    {
        return true;
    }

    /**
     * @throws \Exception
     */
    public function getQuerySet()
    {
        if (Xcart::app()->user->hasRoles(['vrs', 'vrv'])) {
            return parent::getQuerySet()->filter(['user_id' => Xcart::app()->user]);
        }
        return parent::getQuerySet();
    }
}

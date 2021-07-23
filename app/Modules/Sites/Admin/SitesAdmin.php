<?php

namespace Modules\Sites\Admin;

use Modules\Admin\Contrib\Admin;
use Modules\Admin\Traits\AdminTrait;
use Modules\Goods\Models\CategoryModel;
use Modules\Sites\Forms\SiteForm;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Main\Xcart;

class SitesAdmin extends Admin
{
    public ?string $sort = 'orderby';

    use AdminTrait;

    public function getListColumns()
    {
        return [
            'code',
            'domain',
            'status',
        ];
    }

    public function getForm()
    {
        return new SiteForm();
    }

    public function getModel()
    {
        return new SiteModel();
    }

    public static function getName()
    {
        return 'Sites';
    }

    public function isAjaxUpdate(): bool
    {
        return true;
    }

    public function isAjaxCreate(): bool
    {
        return true;
    }

    public function getSuggestionColumns()
    {
        return [
            'category' => [
                'class' => CategoryModel::class,
                'columns' => [
                    'category', 'pk'
                ],
                'filter' => [
                    'avail' => 'Y'
                ]
            ],
        ];
    }

    public function handleSuggestion($entity, $search)
    {
        $qs = parent::handleSuggestion($entity, $search);

        if (($site = Xcart::app()->request->get->get('site')) !== null) {
            $qs->filter(['site__storefrontid' => $site]);
        }
        return $qs;
    }
}

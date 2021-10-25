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

    public function getListColumns(): array
    {
        return [
            'code',
            'domain',
            'status',
        ];
    }

    public function getForm(): SiteForm
    {
        return new SiteForm();
    }

    public function getModel(): SiteModel
    {
        return new SiteModel();
    }

    public static function getName(): string
    {
        return 'Sites';
    }

    public function isAjaxUpdate(): bool
    {
        return false;
    }

    public function isAjaxCreate(): bool
    {
        return false;
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
        $site = Xcart::app()->request->get->get('site');
        if ($site !== null && $site !== '') {
            $qs->filter([
                'site__storefrontid' => $site,
                'level' => 1
            ]);
        }
        return $qs;
    }

    public function getListItemActions(): array
    {
        return [
            'update',
        ];
    }
}

<?php


namespace Modules\Distributor\Admin;


use Modules\Admin\Contrib\Admin;
use Modules\Distributor\Models\DistributorModel;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Orm\Model;

class DistributorAdmin extends Admin
{
    public ?string $order = '-created_at';

    public function getListColumns()
    {
        return [
            'manufacturer',
            'code',
            'sites',
            'products',
            'active_products',
            'feed',
            'feed_source',
            'provider',
            'created_at',
            'avail',
        ];
    }

    public function getAvailableListColumns()
    {
        return [
            'manufacturer' => [
                'title' => 'DX Company Name',
            ],
            'code' => [
                'title' => 'DX Prefix',
            ],
            'sites' => [
                'title' => 'Main SF',
            ],
            'products' => [
                'title' => 'All SKUs',
            ],
            'active_products' => [
                'title' => 'Active SKUs',
            ],
            'feed' => [
                'title' => 'Feed',
            ],
            'feed_source' => [
                'title' => 'Feed source',
            ],
            'provider' => [
                'title' => 'Added by',
            ],
            'created_at' => [
                'title' => 'Added on',
            ],
            'avail' => [
                'title' => 'Active',
            ],
        ];
    }

    public function getItemProperty(Model $item, $property)
    {
        switch ($property) {
            case 'manufacturer' :
                return "<a href='{$item->getAdminUrl()}'>{$item}</a>";
            case 'sites' :
                return implode(
                    '',
                    array_map(
                        static fn(SiteModel $i) => "<div><a target='_blank' href='{$i->getAbsoluteUrl(true)}'>{$i}</a></div>",
                        $item->$property->all()
                    )
                );
            case 'products' :
                return $item->products->count();
            case 'active_products' :
                return $item->products_active->count();
            case 'feed' :
                return 10;
            case 'feed_source' :
                return 130;
        }
        return parent::getItemProperty($item, $property);
    }

    public function getForm()
    {
    }

    public function getModel()
    {
        return new DistributorModel();
    }

    public function isAjaxUpdate(): bool
    {
        return true;
    }

    public function isAjaxCreate(): bool
    {
        return true;
    }

    public function getUpdateUrl($pk = null)
    {
    }

}
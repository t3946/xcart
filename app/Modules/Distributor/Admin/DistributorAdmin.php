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
                return "<a href='{$item->getAdminUrl()}'>$item</a>";
            case 'sites' :
                return implode(
                    '',
                    array_map(
                        static fn(SiteModel $i) => "<div><a target='_blank' href='{$i->getAbsoluteUrl()}'>$i</a></div>",
                        $item->$property->all()
                    )
                );
            case 'products' :
                return $item->products->count();
            case 'active_products' :
                return $item->products_active->count();
            case 'feed' :
                $i_count = $item->feed_I_E->count();
                $p_count = $item->feed_P_E->count();
                $value = '';
                if ($i_count) {
                    $value .= "I($i_count)";
                }
                if ($p_count) {
                    $value .= "P($p_count)";
                }
                return $value;
            case 'feed_source' :
                $value = '';
                foreach ($item->feeds as $feed) {
                    $field_source = $feed->getField('feed_source');
                    $feed_date = $feed->getField('feed_source_date')->getValue();
                    $date = $feed_date ? "({$feed_date->format('Y-m-d')})" : '';
                    $value .= "{$field_source->toText()} $date<br/>";
                }
                return $value;
            case 'created_at' :
                return $item->getField('created_at')->getValue()->format('Y-m-d');
            case 'provider' :
                return $item->provider_model . "<br/> ({$item->provider})";
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
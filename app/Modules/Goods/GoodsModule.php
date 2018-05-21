<?php
namespace Modules\Goods;

use Modules\Distributor\Models\DistributorModel;
use Xcart\App\Main\Xcart;
use Xcart\App\Module\Module;
use Xcart\App\Traits\RenderTrait;

class GoodsModule extends Module
{
    use RenderTrait;

    public static function onApplicationRun()
    {
        $template = Xcart::app()->template->getRenderer();

        $template->addAccessorSmart('get_warehouse', self::class. '::getWarehouse', $template::ACCESSOR_CALL);
        $template->addBlockFunction('p_label', function($params, $html){

            $params['text'] = $html;

            return self::renderTemplate('product/messages/_p_label.tpl', $params);
        });
    }

    public static function getWarehouse($id)
    {
        return DistributorModel::objects()->get(['pk' => $id]);
    }

    public static function getAdminMenu(): array
    {
        $user = Xcart::app()->user;
        $router = Xcart::app()->router;

        $items = [[
            'icon' => 'fa fa-list',
            'name' => 'Group products',
            'route' => $router->url('product:group_products'),
        ]];

        if ($user && $user->getIsSuperuser()) {
            $items[] = [
                'icon' => 'fa fa-object-group',
                'name' => 'Grouping products',
                'route' => $router->url('product:group_list'),
            ];
        }

        return $items;
    }
}
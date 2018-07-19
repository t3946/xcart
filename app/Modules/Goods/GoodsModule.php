<?php

namespace Modules\Goods;

use Modules\Distributor\Models\DistributorModel;
use Modules\Goods\Admin\ProductAdmin;
use Xcart\App\Main\Xcart;
use Xcart\App\Module\Module;
use Xcart\App\Traits\RenderTrait;

class GoodsModule extends Module
{
    use RenderTrait;

    public static function onApplicationRun()
    {
        $template = Xcart::app()->template->getRenderer();

        $template->addAccessorSmart('get_warehouse', self::class . '::getWarehouse', $template::ACCESSOR_CALL);

        $template->addBlockFunction('p_label', function ($params, $html) {

            $params['text'] = $html;

            return self::renderTemplate('product/messages/_p_label.tpl', $params);
        });

        $template->addModifier('createByLine', function ($name, $date, $manager = false): string {

            if (empty($name)) {
                return '';
            }

            if (!$manager) {
                $byLine = 'asked by ' . $name;
            } else {
                $byLine = 'answered by ' . $name . ' (Staff)';
            }

            if (!empty($date)) {
                $byLine .= ' on ' . static::createTextDate($date);
            }

            return $byLine;
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
            'name' => 'Products',
            'route' => $router->url('admin:list',[
                'module' => static::getModuleName(),
                'admin' => ProductAdmin::classNameShort()
            ]),
        ], [
            'icon' => 'fa fa-object-group',
            'name' => 'Group products',
            'route' => $router->url('product:group_products'),
            'items' => ($user && $user->getIsSuperuser()) ? [
                [
                    'icon' => 'fa fa-pencil-square-o',
                    'name' => 'Grouping products',
                    'route' => $router->url('product:group_list'),
                ],
            ] : false,
        ]];

        return $items;
    }

    /**
     * Format day: 'Oct 07, 2015'
     * @param $date integer timestump
     * @return string
     */
    private static function createTextDate($date): string
    {
        $dateTimeObj = (new \DateTime())->setTimestamp($date);
        return $dateTimeObj->format('M d, Y');
    }
}
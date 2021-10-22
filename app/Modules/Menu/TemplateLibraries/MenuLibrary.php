<?php

namespace Modules\Menu\TemplateLibraries;

use Modules\Sites\Models\SiteMenuModel;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Main\Xcart;
use Xcart\App\Template\TemplateLibrary;
use Xcart\App\Traits\RenderTrait;

class MenuLibrary extends TemplateLibrary
{
    use RenderTrait;

    public static string $template = 'menu/menu.tpl';

    private static function getItemsMenu(string $name_menu): array
    {
        $items = [];
        /** @var SiteModel $site */
        $site = Xcart::app()->getModule('Sites')->getSite();
        $menu_parent = $site->menu_list->get(['menu__name' => "{$name_menu} {$site->lang->lang_code}"]);
        $footer_item = SiteMenuModel::objects()->filter(['root' => $menu_parent->menu->root])->asTree()->all();
        if (!empty($footer_item)) {
            $items = array_map(static function ($item) {
                $ar_item = [
                    'name' => $item['name'],
                    'items' => array_map(static fn($item_child) => [
                            'name' => $item_child['name'],
                            'items' => [],
                            'url' => "/{$item_child['url']}" ?? '/'
                        ], $item['items']) ?? []
                ];
                if (!empty($item['url'])) {
                    $ar_item['url'] = $item['url'];
                }
                return $ar_item;
            }, $footer_item[0]['items']);
        }
        return $items;
    }

    /**
     * @kind function
     * @name get_menu
     * @return string
     */
    public static function getMenu($params): string
    {
        if ($params) {
            $template = self::$template;
            $items = self::getItemsMenu($params['code']);
            if (!empty($items)) {
                return self::renderTemplate($template, [
                    'items' => $items,
                ]);
            }
        }
        return '';
    }

    /**
     * @kind accessorFunction
     * @name get_menu_items
     * @return array
     */
    public static function getMenuItems($params): array
    {
        if ($params) {
            return self::getItemsMenu($params);
        }
        return [];
    }
}
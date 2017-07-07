<?php

namespace Modules\Menu\TemplateLibraries;

use Mindy\QueryBuilder\Expression;
use Mindy\QueryBuilder\Q\QOr;
use Modules\Product\Models\CategoryModel;
use Modules\Product\Models\ProductModel;
use Xcart\App\Main\Xcart;
use Xcart\App\Template\TemplateLibrary;

class MenuLibrary extends TemplateLibrary
{
    /**
     * @kind accessorFunction
     * @name getMenu
     * @return array
     */
    public static function getMenu($code)
    {
        if ($code == 'main-menu') {
            return [
                [
                    'link' => '/',
                    'name' => 'Shipping & Delivery',
                    'class' => '',
                ],
                [
                    'link' => '/',
                    'name' => 'Purchase Orders',
                    'class' => '',
                ],
                [
                    'link' => '/',
                    'name' => 'About Us',
                    'class' => '',
                ],
                [
                    'link' => '/',
                    'name' => 'Contact Us',
                    'class' => '',
                ],
                [
                    'link' => '/',
                    'name' => 'Testimonials',
                    'class' => '',
                ],
            ];
        }

        return [];
    }


    private static $root_categories;
    /**
     * @kind accessorFunction
     * @name getCategoryMenu
     * @return array
     */
    public static function getCategoryMenu()
    {

        if (!self::$root_categories) {
            /** @var \Modules\Sites\SitesModule $module */
            $module = Xcart::app()->getModule('Sites');

            $qs = CategoryModel::objects()
                               ->filter([
                                            new QOr(['parentid__isnull' => true, 'parentid' => 0]),
                                            'storefrontid' => $module->getSite()->pk,
                                            'avail' => 'Y',
                                        ])
                               ->order(['order_by']);

            $ta = $qs->getTableAlias();

            $pcountSql = ProductModel::objects()
                                     ->with(['categories'])
                                     ->filter([
                                                  'forsale' => 'Y',
                                                  'categories__lft__gte' => new Expression("{{category}}.lft"),
                                                  'categories__rgt__lte' => new Expression("{{category}}.rgt"),
                                                  'categories__root' => new Expression("{{category}}.root"),
                                              ])
                                     ->countSql();

            $pcountSql = str_replace($ta, 'cp', $pcountSql);
            $pcountSql = str_replace("{{category}}", $ta, $pcountSql);

            $qs->group(['categoryid']);
            $qs->select([
                            'pcount' => $pcountSql,
                            '*',
                        ]);

            $qs->having(['pcount__gt' => 0]);

            self::$root_categories = $qs->cache(300)->all();
        }

        return self::$root_categories;
    }

    const MAX_POINTS_IN_COLUMN = 26;
    const MAX_POINTS = 26 * 3;
    const LVL2_POINT = 4;
    const LVL3_POINT = 1;

    /**
     * @kind accessorFunction
     * @name getDepartmentSubmenu
     * @return array
     */
    public static function getDepartmentSubmenu(CategoryModel $category ,$has_banner = false)
    {
        $collection = $category->getSubcategories(true, 2);
//        $collection = [];

        // Trees mapped
        $items = [];
        $trees = [];
        if (count($collection) > 0) {
            // Node Stack. Used to help building the hierarchy
            $stack = [];
            foreach ($collection as $key => $item) {
                $items[$key]['name'] = $item->category;
                $items[$key]['level'] = $item->level;
                $items[$key]['link'] = $item->getAbsoluteUrl();
                $items[$key]['items'] = [];

                // Number of stack items
                $l = count($stack);
                // Check if we're dealing with different levels
                while ($l > 0 && $stack[$l - 1]['level'] >= $items[$key]['level']) {
                    array_pop($stack);
                    --$l;
                }
                // Stack is empty (we are inspecting the root)
                if ($l == 0) {
                    // Assigning the root node
                    $i = count($trees);
                    $trees[$i] = $items[$key];
                    $stack[] = &$trees[$i];
                } else {
                    // Add node to parent
                    $i = count($stack[$l - 1]['items']);
                    $stack[$l - 1]['items'][$i] = $items[$key];
                    $stack[] = &$stack[$l - 1]['items'][$i];
                }
            }
        }

        $menu = $trees;


        ///// MENU BALANCE BASE CODE

        $points = 0;

        $tmp_menu = $menu;
        $menu = [];
        $lvl2_count = count($tmp_menu);
        $lvl3_count = 0;

        $max_show_lvl3 = 100;
        $max_points = self::MAX_POINTS - (($has_banner) ? ceil(self::MAX_POINTS_IN_COLUMN / 3 * 2) : 0 );

        foreach ($tmp_menu as $key => $item) {
            $lvl3_count += count($item['items']);
            $menu[ $key ] = [
                'name' => $item['name'],
                'link' => $item['link'],
                'more_items' => false,
                'items' => [],
            ];

            if (($points += self::LVL2_POINT) >= $max_points) {
                break;
            }
        }

        if ($lvl3_count > 0)
        {
            if (($lvl3_count * self::LVL3_POINT) > $max_points) {
                $max_show_lvl3 = floor($lvl3_count / $lvl2_count);
            }

            if ($points < $max_points) {
                foreach ($tmp_menu as $key => $item) {

                    if (!empty($item['items'])) {

                        if (count($item['items']) > $max_show_lvl3 || (count($item['items']) * self::LVL3_POINT + $points) > $max_points) {
                            $menu[ $key ]['more_items'] = true;
                        }

                        $show = count($item['items']) > $max_show_lvl3 ? $max_show_lvl3 : count($item['items']);
                        
                        for ($i = 0; $i < $show; $i++ )
                        {
                            $menu[ $key ]['items'][] = $item['items'][$i];
                            if (($points += self::LVL3_POINT) >= $max_points) {
                                break;
                            }
                        }
                    }
                }
            }
        }

        return ['menu' => $menu, 'columns' => ceil($points / self::MAX_POINTS_IN_COLUMN)];
    }


}
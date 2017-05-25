<?php

namespace Modules\Menu\TemplateLibraries;

use Mindy\QueryBuilder\Q\QOr;
use Modules\Product\Models\CategoryModel;
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
//        $menu = [
//            [
//                'name' => 'Accessories',
//                'image' => "/static/frontend/demo_images/category/icons/accessories.svg",
//            ],
//            [
//                'name' => 'Adhesives and Fasteners',
//                'image' => "/static/frontend/demo_images/category/icons/adhesives_fasteners.svg",
//            ],
//            [
//                'name' => 'Airbrushing',
//                'image' => "/static/frontend/demo_images/category/icons/airbrushing.svg",
//            ],
//            [
//                'name' => 'Easels',
//                'image' => "/static/frontend/demo_images/category/icons/easels.svg",
//            ],
//            [
//                'name' => 'Brushes',
//                'image' => "/static/frontend/demo_images/category/icons/artist_brush.svg",
//            ],
//            [
//                'name' => 'Crafts',
//                'image' => "/static/frontend/demo_images/category/icons/arts_crafts.svg",
//            ],
//            [
//                'name' => 'Drafting and Architecture',
//                'image' => "/static/frontend/demo_images/category/icons/draftind_architecture.svg",
//            ],
//            [
//                'name' => 'Drawing and Illustration',
//                'image' => "/static/frontend/demo_images/category/icons/drawing_illustration.svg",
//            ],
//            [
//                'name' => 'Books and DVDs',
//                'image' => "/static/frontend/demo_images/category/icons/books_dvd.svg",
//            ],
//            [
//                'name' => 'Ceramics and Pottery',
//                'image' => "/static/frontend/demo_images/category/icons/ceramics_pottery.svg",
//            ],
//            [
//                'name' => 'Cleaning Supplies for Craft Mishaps',
//                'image' => "/static/frontend/demo_images/category/icons/cleaning_supplies.svg",
//            ],
//            [
//                'name' => 'Cutting Tools',
//                'image' => "/static/frontend/demo_images/category/icons/cutting_tools.svg",
//            ],
//            [
//                'name' => 'Educational and Instructional Materials',
//                'image' => "/static/frontend/demo_images/category/icons/educational.svg",
//            ],
//            [
//                'name' => 'Framing',
//                'image' => "/static/frontend/demo_images/category/icons/framing.svg",
//            ],
//            [
//                'name' => 'Furniture for Artists',
//                'image' => "/static/frontend/demo_images/category/icons/furniture_for_artist.svg",
//            ],
//            [
//                'name' => 'Papers and Boards',
//                'image' => "/static/frontend/demo_images/category/icons/paper_boards.svg",
//            ],
//            [
//                'name' => 'Printmaking',
//                'image' => "/static/frontend/demo_images/category/icons/printmaking.svg",
//            ],
//            [
//                'name' => 'Safety and Health for Artists',
//                'image' => "/static/frontend/demo_images/category/icons/safety_health_for_artist.svg",
//            ],
//            [
//                'name' => 'Storage and Organizers',
//                'image' => "/static/frontend/demo_images/category/icons/storage_organizers.svg",
//            ],
//            [
//                'name' => 'Transporting and Carrying Art Materials',
//                'image' => "/static/frontend/demo_images/category/icons/transporting.svg",
//            ],
//            [
//                'name' => 'Miscellaneous',
//                'image' => "/static/frontend/demo_images/category/icons/uncategorized.svg",
//            ],
//        ];
//
//        shuffle($menu);
//        $max = rand(3, count($menu));
//        $nmenu = [];
//
//        for ($i = 0; $i < $max; $i++) {
//            $nmenu[] = $menu[ $i ];
//        }

        if (!self::$root_categories) {
            /** @var \Modules\Sites\SitesModule $module */
            $module = Xcart::app()->getModule('Sites');

            self::$root_categories = CategoryModel::objects()
                                                    ->filter([
                                                        new QOr(['parentid__isnull' => true, 'parentid' => 0]),
                                                        'storefrontid' => $module->getSite()->pk,
                                                        'avail' => 'Y'
                                                     ])
                                                    ->order(['order_by'])
                                                    ->all();
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
        $collection = $category->getSubcategories(false, 2);

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
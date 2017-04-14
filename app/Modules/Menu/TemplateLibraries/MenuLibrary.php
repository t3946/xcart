<?php

namespace Modules\Menu\TemplateLibraries;

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
                    'name' => 'Safe & Secure Shopping',
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

    /**
     * @kind accessorFunction
     * @name getCategoryMenu
     * @return array
     */
    public static function getCategoryMenu()
    {
        $menu = [
            [
                'name' => 'Accessories',
                'image' => "/static/frontend/demo_images/category/icons/accessories.svg",
            ],
            [
                'name' => 'Adhesives and Fasteners',
                'image' => "/static/frontend/demo_images/category/icons/adhesives_fasteners.svg",
            ],
            [
                'name' => 'Airbrushing',
                'image' => "/static/frontend/demo_images/category/icons/airbrushing.svg",
            ],
            [
                'name' => 'Easels',
                'image' => "/static/frontend/demo_images/category/icons/easels.svg",
            ],
            [
                'name' => 'Brushes',
                'image' => "/static/frontend/demo_images/category/icons/artist_brush.svg",
            ],
            [
                'name' => 'Crafts',
                'image' => "/static/frontend/demo_images/category/icons/arts_crafts.svg",
            ],
            [
                'name' => 'Drafting and Architecture',
                'image' => "/static/frontend/demo_images/category/icons/draftind_architecture.svg",
            ],
            [
                'name' => 'Drawing and Illustration',
                'image' => "/static/frontend/demo_images/category/icons/drawing_illustration.svg",
            ],
            [
                'name' => 'Books and DVDs',
                'image' => "/static/frontend/demo_images/category/icons/books_dvd.svg",
            ],
            [
                'name' => 'Ceramics and Pottery',
                'image' => "/static/frontend/demo_images/category/icons/ceramics_pottery.svg",
            ],
            [
                'name' => 'Cleaning Supplies for Craft Mishaps',
                'image' => "/static/frontend/demo_images/category/icons/cleaning_supplies.svg",
            ],
            [
                'name' => 'Cutting Tools',
                'image' => "/static/frontend/demo_images/category/icons/cutting_tools.svg",
            ],
            [
                'name' => 'Educational and Instructional Materials',
                'image' => "/static/frontend/demo_images/category/icons/educational.svg",
            ],
            [
                'name' => 'Framing',
                'image' => "/static/frontend/demo_images/category/icons/framing.svg",
            ],
            [
                'name' => 'Furniture for Artists',
                'image' => "/static/frontend/demo_images/category/icons/furniture_for_artist.svg",
            ],
            [
                'name' => 'Papers and Boards',
                'image' => "/static/frontend/demo_images/category/icons/paper_boards.svg",
            ],
            [
                'name' => 'Printmaking',
                'image' => "/static/frontend/demo_images/category/icons/printmaking.svg",
            ],
            [
                'name' => 'Safety and Health for Artists',
                'image' => "/static/frontend/demo_images/category/icons/safety_health_for_artist.svg",
            ],
            [
                'name' => 'Storage and Organizers',
                'image' => "/static/frontend/demo_images/category/icons/storage_organizers.svg",
            ],
            [
                'name' => 'Transporting and Carrying Art Materials',
                'image' => "/static/frontend/demo_images/category/icons/transporting.svg",
            ],
            [
                'name' => 'Miscellaneous',
                'image' => "/static/frontend/demo_images/category/icons/uncategorized.svg",
            ],
        ];

        shuffle($menu);
        $max = rand(3, count($menu));
        $nmenu = [];

        for($i = 0; $i < $max; $i++)
        {
            $nmenu[] = $menu[$i];
        }
        return $nmenu;
    }

    /**
     * @kind accessorFunction
     * @name getRandomSubmenu
     * @return array
     */
    public static function getRandomSubmenu()
    {
        $lvl3 = [
            'Brushes by Medium or Technique',
            'Brushes by Hair or Fiber',
            'Brushes by Name or Shape',
            'Scholastic Brushes',
            'Scholastic Brushes BlahBlahBlah',
        ];

        $lvl4 = [
            'Acrylic and Oil Brushes',
            'Brush Techniques Demonstration',
            'Paper',
            'Ceramic and Glazing Brushes',
            'Decorative and Miniature Brushes',
            'Encaustic Brushes',
            'Faux Finishing Brushes and Tools',
            'Gilding Brushes',
            'Lettering Brushes',
            'Multi-Purpose and Utility Brushes',
            'Mural and Fresco Brushes',
            'Oriental and Sumi Brushes',
            'Paint Rollers',
            'Painting and Palette Knives',
            'Stencil Brushes',
            'Striping Brushes',
            'Varnish and Gesso Brushes',
            'Watercolor Brushes',
            'Badger Brushes',
            'Bristle Brushes',
            'Sable/Kolinsky Brushes',
            'Squirrel Brushes',
            'Synthetic Brushes',
            'Angular',
            'Bright',
            'Fan',
            'Filbert',
            'Flat',
            'Hake',
            'Highliner',
            'Mop',
            'Mottler',
            'One Stroke',
            'Oval Wash',
            'Black Bristle',
            'Camel/Pony',
            'Colored Synthetic',
            'Foam and Sponge Brushes',
            'Golden Synthetic',
            'Scholastic Sable',
            'White Bristle',
            'White Synthetic',
            'Brushes',
        ];

        $menu = [];

        $cnt1 = rand(1, 10);
        for ($i = 0; $i < $cnt1; $i++) {
            $name = array_rand($lvl3);
            $menu[ $i ] = [
                'name' => $lvl3[ $name ],
                'link' => '#',
                'items' => [],
            ];

            $cnt2 = rand(0, 20);
            for ($x = 0; $x < $cnt2; $x++) {
                $name = array_rand($lvl4);
                $menu[ $i ]['items'][] = [
                    'name' => $lvl4[ $name ],
                    'link' => '#',
                ];
            }
        }

        return $menu;
    }
}
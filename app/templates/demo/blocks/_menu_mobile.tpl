{set $menu = [
    [
        'name' => 'Accessories'
        'image' => "/static/frontend/dist/images/home/1280/subdepartments/accessories.svg",
    ],
    [
        'name' => 'Adhesives and Fasteners'
        'image' => "/static/frontend/dist/images/home/1280/subdepartments/adhesives_fasteners.svg",
    ],
    [
        'name' => 'Airbrushing'
        'image' => "/static/frontend/dist/images/home/1280/subdepartments/airbrushing.svg",
    ],
    [
        'name' => 'Easels'
        'image' => "/static/frontend/dist/images/home/1280/subdepartments/easels.svg",
    ],
    [
        'name' => 'Brushes'
        'image' => "/static/frontend/dist/images/home/1280/subdepartments/artist_brush.svg",
    ],
    [
        'name' => 'Crafts'
        'image' => "/static/frontend/dist/images/home/1280/subdepartments/arts_crafts.svg",
    ],
    [
        'name' => 'Drafting and Architecture'
        'image' => "/static/frontend/dist/images/home/1280/subdepartments/draftind_architecture.svg",
    ],
    [
        'name' => 'Drawing and Illustration'
        'image' => "/static/frontend/dist/images/home/1280/subdepartments/drawing_illustration.svg",
    ],
    [
        'name' => 'Books and DVDs'
        'image' => "/static/frontend/dist/images/home/1280/subdepartments/books_dvd.svg",
    ],
    [
        'name' => 'Ceramics and Pottery'
        'image' => "/static/frontend/dist/images/home/1280/subdepartments/ceramics_pottery.svg",
    ],
    [
        'name' => 'Cleaning Supplies for Craft Mishaps'
        'image' => "/static/frontend/dist/images/home/1280/subdepartments/cleaning_supplies.svg",
    ],
    [
        'name' => 'Cutting Tools'
        'image' => "/static/frontend/dist/images/home/1280/subdepartments/cutting_tools.svg",
    ],
    [
        'name' => 'Educational and Instructional Materials'
        'image' => "/static/frontend/dist/images/home/1280/subdepartments/educational.svg",
    ],
    [
        'name' => 'Framing'
        'image' => "/static/frontend/dist/images/home/1280/subdepartments/framing.svg",
    ],
    [
        'name' => 'Furniture for Artists'
        'image' => "/static/frontend/dist/images/home/1280/subdepartments/furniture_for_artist.svg",
    ],
    [
        'name' => 'Papers and Boards'
        'image' => "/static/frontend/dist/images/home/1280/subdepartments/paper_boards.svg",
    ],
    [
        'name' => 'Printmaking'
        'image' => "/static/frontend/dist/images/home/1280/subdepartments/printmaking.svg",
    ],
    [
        'name' => 'Safety and Health for Artists'
        'image' => "/static/frontend/dist/images/home/1280/subdepartments/safety_health_for_artist.svg",
    ],
    [
        'name' => 'Storage and Organizers'
        'image' => "/static/frontend/dist/images/home/1280/subdepartments/storage_organizers.svg",
    ],
    [
        'name' => 'Transporting and Carrying Art Materials'
        'image' => "/static/frontend/dist/images/home/1280/subdepartments/transporting.svg",
    ],
    [
        'name' => 'Miscellaneous'
        'image' => "/static/frontend/dist/images/home/1280/subdepartments/uncategorized.svg",
    ],
]}

<div class="menu-title">
    <h3>Departments</h3>
</div>
<ul class="accordion" data-accordion data-allow-all-closed="true" data-multi-expand="true">
    {foreach $menu as $item}
        <li class="accordion-item" data-accordion-item>
            <a class="accordion-title">
                <div class="row">
                    <div class="columns small-1">
                        <img src="{$item.image}" alt="{$item.name}">
                    </div>
                    <div class="columns small-11">
                        <span>{$item.name}</span>
                    </div>
                </div>
            </a>

            <div class="accordion-content" data-tab-content>
                {include "demo/blocks/_submenu_mobile.tpl"}
            </div>
        </li>
    {/foreach}
</ul>
<div class="filter-block">
    <div class="block-title">
        Departments
    </div>

    {set $sdep = ['Brushes', 'Drawing and Illustration', 'Crafts', 'Furniture for Artists', 'Cleaning Supplies for Craft Mishaps', 'Airbrushing', 'Adhesives and Fasteners']}

    <ul class="accordion" data-accordion data-allow-all-closed="true" data-multi-expand="true">
        {foreach $sdep as $item}
            <li class="accordion-item" data-accordion-item>
                <a class="accordion-title">
                    <span>{$item}</span>
                </a>

                <div class="accordion-content" data-tab-content>
                    {include "demo/blocks/_search_departments_list.tpl"}
                </div>
            </li>
        {/foreach}
    </ul>

</div>
<div class="menu-title">
    <h3>Departments</h3>
</div>
<ul class="accordion" data-accordion data-allow-all-closed="true" data-multi-expand="true">
    {foreach $.getCategoryMenu() as $item}
        <li class="accordion-item" data-accordion-item>
            <a class="accordion-title">
                <div class="row">
                    <div class="columns small-2 medium-1">
                        <img src="{$item.image}" alt="{$item.name}">
                    </div>
                    <div class="columns small-10 medium-11">
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
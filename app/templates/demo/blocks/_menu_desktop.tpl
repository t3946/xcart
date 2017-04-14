{set $catMenu = $.getCategoryMenu()}

<section id="hidden_category_menu" class="category-menu-list-wrapper hide" data-toggler="hide">
    <div class="row">
        <div class="columns large-12">
            <section class="category-menu-list-container">
                <div class="bg-container">
                    <div class="bg-color"></div>
                </div>
                <div class="category-menu-list">
                    <ul class="no-bullet">

                        {foreach $catMenu as $item index=$index}
                            <li class="category-menu-item" data-hover-toggle="top-csm-{$index}">
                                <div class="item-container">
                                    <a href="#">
                                        <div class="row">
                                            <div class="column large-2">
                                                <div class="item-image">
                                                    <img src="{$item.image}" alt="{$item.name}">
                                                </div>
                                            </div>
                                            <div class="column large-10 no-left-padding">
                                                        <span class="item-name">
                                                            {$item.name}
                                                        </span>
                                            </div>
                                        </div>
                                    </a>

                                </div>
                                <div class="submenu-container">
                                    {include "demo/blocks/_submenu_desktop.tpl" menu_name=$item.name}
                                </div>
                            </li>
                            {if $index == 11} {break} {/if}
                        {/foreach}

                    </ul>

                    {*{if $catMenu|count > 12}*}
                        <div class="view-all-container">
                            <a href="#" class="view-all">View all departments</a>
                        </div>
                    {*{/if}*}
                </div>


            </section>
        </div>
    </div>


</section>

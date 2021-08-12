<nav id="hidden_category_menu" class="category-menu-list-wrapper hide m-0" data-toggler="hide">
    <div class="category-menu-list-row">
        <section class="category-menu-list-container">
            <div class="category-menu-list">
                <ul class="no-bullet">
                    {foreach $.getCategoryMenu() as $category index=$index}
                        {set $has_banner = false}
                        {set $submenu = $.getDepartmentSubmenu($category, $has_banner)}

                        <li class="category-menu-item {if $submenu|count > 0}has-child{/if}"
                            data-hover-toggle="top-csm-{$index}">
                            <div class="item-container">
                                <a href="{$category->getAbsoluteUrl()}">
                                    <div class="column no-left-padding">
                                        <span class="item-name">
                                            {$category->category}
                                        </span>
                                    </div>
                                </a>
                            </div>

                            {include "_parts/_submenu_desktop.tpl" menu_name=$category->category model=$category has_banner=$has_banner menus=$submenu}
                        </li>
                        {if $index == 11} {break} {/if}
                    {/foreach}
                </ul>

                <div class="view-all-container">
                    <a href="{url 'catalog:list'}" class="view-all">{t 'View all departments'}</a>
                </div>
            </div>
        </section>
    </div>
</nav>

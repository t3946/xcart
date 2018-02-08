<div class="menu-title">
    <h3>Departments</h3>
</div>
<ul class="accordion" data-accordion data-allow-all-closed="true" data-multi-expand="true">
    {foreach $.getCategoryMenu() as $category}
        {if $category->active_product_count > 0}
            {set $subcats = $category->getSubcategories()}
            {set $has_childs = ($subcats|count > 0)}

            <li class="accordion-item" {if $has_childs }data-accordion-item{/if}>
                <a class="accordion-title" {if !$has_childs}href="{$category->getAbsoluteUrl()}" {/if}>
                    <div class="row">
                        <div class="columns small-2 medium-1">
                            {*<img src="{$category->image}" alt="{$category->category}">*}
                        </div>
                        <div class="columns small-10 medium-11">
                            <span>{$category->category}</span>
                        </div>
                    </div>
                </a>
                {if $has_childs}
                    <div class="accordion-content" data-tab-content>
                        {include "_parts/_submenu_mobile.tpl" items=$subcats}
                    </div>
                {/if}
            </li>
        {/if}
    {/foreach}
</ul>
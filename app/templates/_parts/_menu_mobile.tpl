<div class="menu-title">
    <h3>Departments</h3>
</div>
<ul class="accordion" data-accordion data-allow-all-closed="true" data-multi-expand="true">
    {foreach $.getCategoryMenu() as $category}
        <li class="accordion-item" data-accordion-item>
            <a class="accordion-title">
                <div class="row">
                    <div class="columns small-2 medium-1">
                        {*<img src="{$category->image}" alt="{$category->category}">*}
                    </div>
                    <div class="columns small-10 medium-11">
                        <span>{$category->category}</span>
                    </div>
                </div>
            </a>

            <div class="accordion-content" data-tab-content>
                {include "_parts/_submenu_mobile.tpl" items=$category->getSubcategories()}
            </div>
        </li>
    {/foreach}
</ul>
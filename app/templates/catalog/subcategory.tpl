{extends  "catalog/base.tpl"}

{block 'content'}
    <div class="default-content-page page-departments-container">
    <div class="page-departments">
        {* Меню категорий *}
        <div class="all-departments-menu-container container">
            <div class="row all-departments-menu row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-6">
                {foreach $categories as $category}
                    <a href="#id{$category->categoryid}" class="item-title column link-id{$category->categoryid}">
                        <span class="title"><span>{$category->category}</span></span>
                    </a>
                {/foreach}
            </div>
            {* Разделитель *}
            <div class="row all-departments-menu-hr">
                <div class="col-12">
                    <div class="hr"></div>
                </div>
            </div>
        </div>

        {* Список меню категорий *}
        <div class="sections container">
            {foreach $categories as $categoryKey => $category}
                <section id="id{$category->categoryid}" class="departments-submenu-container">
                    {* Заголовок категории *}
                    <div class="row">
                        <div class="col-12">
                            <a href="{$category->getAbsoluteUrl()}" class="departments-submenu-title">
                                <span class="title">{$category->category}</span>
                            </a>
                        </div>
                    </div>

                    {* Меню подкатегорий категории *}
                    <div class="row departments-submenu-items row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4">
                        {foreach $category->getActiveChildren()->all() as $subCategory}
                            <div class="group-items column">
                                <div class="items-title"><a
                                            href="{$subCategory->getAbsoluteUrl()}">{$subCategory->category}</a></div>
                                <div class="items-list">
                                    {foreach $subCategory->getActiveChildren()->all() as $subSubCategory}
                                        <div class="item-link">
                                            <a href="{$subSubCategory->getAbsoluteUrl()}">{$subSubCategory->category}</a>
                                        </div>
                                    {/foreach}
                                </div>
                            </div>
                        {/foreach}
                    </div>

                    {* Разделитель *}
                    {if ($showSlider || ($categoryKey < (count($categories) - 1)))}
                    <div class="row hr-container">
                        <div class="col-12">
                            <div class="hr"></div>
                        </div>
                    </div>
                    {/if}
                </section>
            {/foreach}
        </div>
    </div>
    </div>
{/block}
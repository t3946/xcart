{extends  "catalog/base.tpl"}

{block 'content'}
    <div class="default-content-page page-departments-container">
    <div class="page-departments">

        {* Меню категорий *}
        <div class="all-departments-menu-container">
            <div class="row all-departments-menu  small-up-1 medium-up-2 ml-up-3 large-up-6">
                {foreach $categories as $category}
                    <a href="#id{$category->categoryid}" class="item-title column link-id{$category->categoryid}">
                        {*<span class="image-container">*}
                            {*<img class="image"*}
                                 {*src="/static/frontend/demo_images/demo_images_new/icon{(rand(0,1)) ? 1 : 2}.svg"*}
                                 {*alt="">*}
                        {*</span>*}
                        <span class="title"><span>{$category->category}</span></span>
                    </a>
                {/foreach}
            </div>
            {* Разделитель *}
            <div class="row all-departments-menu-hr">
                <div class="column small-12">
                    <div class="hr"></div>
                </div>
            </div>
        </div>

        {* Список меню категорий *}
        <div class="sections">
            {foreach $categories as $categoryKey => $category}
                <section id="id{$category->categoryid}" class="departments-submenu-container">
                    {*<a name="id{$category->categoryid}"></a>*}
                    {* Заголовок категории *}
                    <div class="row">
                        <div class="column small-12">
                            <a href="{$category->getAbsoluteUrl()}" class="departments-submenu-title">
                                {*<span class="image-container">*}
                                    {*<img class="image"*}
                                         {*src="/static/frontend/demo_images/demo_images_new/icon{(rand(0,1)) ? 1 : 2}.svg"*}
                                         {*alt="">*}
                                {*</span>*}
                                <span class="title">{$category->category}</span>
                            </a>
                        </div>
                    </div>

                    {* Меню подкатегорий категории *}
                    <div class="row departments-submenu-items small-up-1 medium-up-2 ml-up-3 large-up-4">
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
                        <div class="column small-12">
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
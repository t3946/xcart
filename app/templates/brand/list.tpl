{extends  "base.tpl"}


{block "content"}
    <div class="default-content-page page-departments-container">
        <div class="page-departments">

            {* Меню категорий *}
            <div class="all-departments-menu-container">
                <div class="row all-departments-menu  small-up-1 medium-up-2 ml-up-3 large-up-6">
                    {foreach $brands as $brand}
                        <a href="#id{$brand->brandid}" class="item-title column link-id{$brand->brandid}">
                            {*<div class="image-container">*}
                                {*<img class="image"*}
                                     {*src="/static/frontend/demo_images/demo_images_new/icon{(rand(0,1)) ? 1 : 2}.svg"*}
                                     {*alt="">*}
                            {*</div>*}
                            <div class="title"><span>{$brand->brand}</span></div>
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

            {* Список меню брендов *}
            <div class="sections">
                {foreach $brands as $brandKey => $brand}
                    <section id="id{$brand->brandid}" class="departments-submenu-container">
                        {* Заголовок категории *}
                        <div class="row">
                            <div class="column small-12">
                                <a href="{$brand->getAbsoluteUrl()}" class="departments-submenu-title">
                                {*<span class="image-container">*}
                                    {*<img class="image"*}
                                         {*src="/static/frontend/demo_images/demo_images_new/icon{(rand(0,1)) ? 1 : 2}.svg"*}
                                         {*alt="">*}
                                {*</span>*}
                                    <span class="title">{$brand->brand}</span>
                                </a>
                            </div>
                        </div>

                        {* Меню подбрендов брендов категории *}
                        <div class="row departments-submenu-items small-up-1 medium-up-2 ml-up-3 large-up-4">
                            {foreach $brand->getActiveChildren()->all() as $subBrand}
                                <div class="group-items column">
                                    <div class="items-title"><a
                                                href="{$subBrand->getAbsoluteUrl()}">{$subBrand->brand}</a></div>
                                    <div class="items-list">
                                        {foreach $subBrand->getActiveChildren()->all() as $subSubBrand}
                                            <div class="item-link">
                                                <a href="{$subSubBrand->getAbsoluteUrl()}">{$subSubBrand->brand}</a>
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

{block 'after-content'}

    <div class="row">
        <div class="small-12 column slider-viewed">
            {set $link}{url 'catalog:viewed'}{/set}
            {include 'slider/base_product_slider.tpl' title="You recently viewed items" link=$link hide=true hide_link=true}
        </div>
    </div>

    {*<div class="row">*}
    {*<div class="small-12 column">*}
    {*<div id="scrollToTop">*}
    {*<img class="image"*}
    {*src="/static/frontend/demo_images/demo_images_new/up.svg"*}
    {*alt="">*}
    {*<span>UP</span>*}
    {*</div>*}
    {*</div>*}
    {*</div>*}

{/block}

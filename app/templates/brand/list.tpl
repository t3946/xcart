{extends  "base.tpl"}


{block "content"}
    <div class="default-content-page page-departments-container brands-list-container container">
        <div class="page-departments">

            {* Меню категорий *}
            <div class="all-departments-menu-container">
                <div class="all-departments-menu">
                    {foreach $brands as $letter => $brand}
                        <a href="#id{$letter}" class="item-title column link-id{$letter}">
                            <span class="title">{$letter}</span>
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
                {foreach $brands as $letter => $brands}
                    <section id="id{$letter}" class="departments-submenu-container">
                        {* Заголовок категории *}
                        <div class="row">
                            <div class="column small-12">
                                <div class="departments-submenu-title">
                                {*<span class="image-container">*}
                                    {*<img class="image"*}
                                         {*src="/static/frontend/demo_images/demo_images_new/icon{(rand(0,1)) ? 1 : 2}.svg"*}
                                         {*alt="">*}
                                {*</span>*}
                                    <span class="title">{$letter}</span>
                                </div>
                            </div>
                        </div>

                        {* Меню подбрендов брендов категории *}
                        <div class="row departments-submenu-items">
                            {foreach $brands as $brand}
                                <div class="group-items col-12 col-sm-6 col-md-4 col-lg-3">
                                    <div class="items-title">
                                        <a href="{$brand->getAbsoluteUrl()}">{$brand->brand}</a>
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
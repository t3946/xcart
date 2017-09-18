{extends  $.request->getIsAjax() ? "ajax.tpl" : "base.tpl"}

{block "before-content"}
    {if !$.request->getIsAjax()}
    <div class="row">
        <div class="columns large-12">
            {insert "base/_breadcrumbs.tpl"}
        </div>
    </div>
    {/if}
{/block}

{block "content"}
<section class="product-page page">

    <section class="title hide-for-large">
        <div class="row">
            <div class="column large-12">
                <h1>{$model->getFrontendName()} </h1>
            </div>
        </div>
    </section>

    <section class="images_prices">
        <div class="row">
            <div class="column small-12 large-5 block__image">
                images
            </div>
            <div class="column small-12 large-7 block__title_price">
                <div class="title show-for-large">
                    <h1>{$model->getFrontendName()} </h1>
                </div>

                <div class="notifications">
                    notifications
                </div>

                <div class="prices">
                    table prices
                </div>
            </div>
        </div>
    </section>


    <section class="info_tabs">
        {*<div class="row">*}
            {*<div class="column small-12">*}

                <ul class="tabs" data-responsive-accordion-tabs="tabs small-accordion large-tabs" data-allow-all-closed="true" id="product_tabs">
                    <li class="tabs-title is-active">
                      <a href="#description" aria-selected="true">Description</a>
                    </li>

                    <li class="tabs-title">
                      <a href="#brand" aria-selected="false">Brand</a>
                    </li>

                    {foreach $tabs as $tab}
                        <li class="tabs-title">
                          <a href="#{$tab.code}" aria-selected="false">{$tab.name}</a>
                        </li>
                    {/foreach}

                    <li class="tabs-title">
                      <a href="#questions" aria-selected="false">Product questions</a>
                    </li>
                </ul>


                <div class="tabs-content" data-tabs-content="product_tabs">

                    <div class="tabs-panel is-active" id="description">
                        {include 'product/tabs/_description.tpl' model=$model}
                    </div>

                    <div class="tabs-panel" id="brand">
                        {include 'product/tabs/brand.tpl' model=$model}
                    </div>

                    <div class="tabs-panel" id="questions">
                        Product questions
                    </div>

                    {foreach $tabs as $tab}
                        <div class="tabs-panel" id="{$tab.code}">
                            {raw $tab.content}
                        </div>
                    {/foreach}
                </div>

            {*</div>*}
        {*</div>*}
    </section>

    <section class="descriptions"></section>

    <section class="groupped-products">groupped products</section>
</section>
{/block}

{block 'after-content'}
    {*{include "demo/blocks/sliders/_recently_viewed.tpl"}*}
{/block}
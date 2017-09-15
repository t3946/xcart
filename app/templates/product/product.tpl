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

    <section class="title">
        <div class="row">
            <div class="column large-12">
                <h1>{$model->product} </h1>
            </div>
        </div>
    </section>

    <section class="images">images</section>
    <section class="title">title</section>
    <section class="prices">prices</section>

    <section class="descriptions"></section>

    <section class="groupped-products">groupped products</section>
</section>
{/block}

{block 'after-content'}
    {*{include "demo/blocks/sliders/_recently_viewed.tpl"}*}
{/block}
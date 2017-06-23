{extends  "catalog/base.tpl"}

{if !$.request->getIsAjax()}
    {block "catalog-filter"}

    {/block}

    {block "content-top"}
        <div class="row">
            <div class="columns large-2 show-for-large">

            </div>
            <div class="columns large-10">
                <h1 class="title">Showing result for "{$model}"</h1>
            </div>
        </div>
    {/block}


    {block 'after-content'}
        {*{include "demo/blocks/sliders/_recently_viewed.tpl"}*}
    {/block}
{/if}
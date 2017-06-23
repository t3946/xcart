{extends  "base.tpl"}

{block "content"}
    <div class="row">
            <div class="columns large-12">
                <h1 class="title">Showing result for "{$model}"</h1>
                <article>
                    We try to search your query across all storefront products, but nothing found.
Please specify your request...

                </article>
            </div>
        </div>
{/block}


{block 'after-content'}
    {*{include "demo/blocks/sliders/_recently_viewed.tpl"}*}
{/block}
{extends  "catalog/base.tpl"}

{if !$.request->getIsAjax()}
    {*{block "catalog-filter"}*}

    {*{/block}*}

    {block "content-top"}
        <div class="row">
            <div class="columns large-2 show-for-large">

            </div>
            <div class="columns large-10">
                <div class="search-header">
                    {if $searched}
                        <h1 class="title">Showing result for "<span class="highlight">{$q}</span>"</h1>
                    {else}
                        <h1 class="title">Your search "<span class="bad">{$q_original}</span>" did NOT match any products</h1>
                        <h2 class="subtitle">Showing results for "<span class="highlight">{$q}</span>"</h2>
                    {/if}

                    {if $suggestion}
                        Related searches:
                        {foreach $suggestion as $suggest}
                            <a href="{$.app->router->url('catalog:search', [], ['q' => $suggest])}" class="related">
                                {raw $suggest|text_highlight:$q:'span.bold.founded'}
                            </a>
                        {/foreach}
                    {/if}

                </div>
            </div>
        </div>
    {/block}

{/if}
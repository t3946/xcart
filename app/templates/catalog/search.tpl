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
                        <h1 class="title">{t 'Showing result for'} "<span class="highlight">{$q|escape}</span>"</h1>
                    {else}
                        <h1 class="title">{t 'Your search'} "<span class="bad">{$q_original|escape}</span>" {t 'did NOT match any products'}</h1>
                        <h2 class="subtitle">{t 'Showing results for'} "<span class="highlight">{$q|escape}</span>"</h2>
                    {/if}

                    {if $suggestion && $suggestion['phrase_suggestions'] }
                        {t 'Related searches'}:
                        {foreach $suggestion['phrase_suggestions'] as $suggest}
                            {* не надо делать перенос строки перед закрытием тега - появляется лишний пробел *}
                            <a href="{$.app->router->url('catalog:search', [], ['q' => $suggest])}" class="related">
                                {raw $suggest|text_highlight:$q:'span.bold.founded'}</a>
                        {/foreach}
                    {/if}

                </div>
            </div>
        </div>
    {/block}

{/if}
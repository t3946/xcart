{if !$.request->getIsAjax() }
<div class="front-endless-pager">
{/if}
    {if $view->hasNextPage()}
        <a href="{$view->getUrl($view->getPage() + 1, true)}"
           class="show-more button yellow-white waves waves-orange"
           data-text-loading="Loading ..."
           data-text-default="Load more"
           itemscope itemprop="relatedLink/pagination"
           itemtype="http://schema.org/URL">
            <span class="text">Load more</span>
        </a>
    {/if}
{if !$.request->getIsAjax() }
</div>
{/if}
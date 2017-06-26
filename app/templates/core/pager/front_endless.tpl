<section class="front-endless-pager">
    {if $view->hasNextPage()}
        <a href="{$view->getUrl($view->getPage() + 1, true)}" class="show-more button yellow-white" data-text-loading="Loading ..." itemscope itemprop="relatedLink\pagination" itemtype="http://schema.org/URL">
            <span class="text">Load more</span>
        </a>
    {/if}
</section>
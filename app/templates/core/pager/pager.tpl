<section class="pager-container row">
    <section class="total columns large-2">Total: {$this->total}</section>

    <ul class="pager columns large-8 no-bullet">
        {if $this->getPagesCount() > 1}
        <li class="prev">
            {if $this->hasPrevPage()}
                <a href="{$this->getUrl($this->currentPage - 1)}">&larr;</a>
            {else}
                <span class="prev">&larr;</span>
            {/if}
        </li>

        {if $this->hasPrevPage()}
            {foreach $this->iterPrevPage() as $page }
                <li>
                    <a href="{$this->getUrl($page)}">{$page}</a>
                </li>
            {/foreach}
        {/if}

        <li>
            <span class="current">{$this->currentPage}</span>
        </li>

        {if $this->hasNextPage()}
            {foreach $this->iterNextPage() as $page}
                <li>
                    <a href="{$this->getUrl($page)}">{$page}</a>
                </li>
            {/foreach}
        {/if}

        <li class="next">
            {if $this->hasNextPage()}
                <a href="{$this->getUrl($this->currentPage + 1)}">&rarr;</a>
            {else}
                <span class="next">&rarr;</span>
            {/if}
        </li>
        {/if}
    </ul>

    <section class="page-size columns large-2">
        {include "core/pager/sizes.tpl"}
    </section>
</section>
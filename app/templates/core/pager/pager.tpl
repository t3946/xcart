<section class="pager-container">
    <section class="row">
        <section class="total columns large-2">Total: {$view->getTotal()}</section>

        <ul class="pager columns large-8 no-bullet">
            {if $view->getPagesCount() > 1}
                <li class="prev">
                    {if $view->hasPrevPage()}
                        <a href="{$view->getUrl($pager->getPage() - 1)}">&larr;</a>
                    {else}
                        <span class="prev">&larr;</span>
                    {/if}
                </li>

                {if $view->hasPrevPage()}
                    {foreach $view->iterPrevPage() as $page }
                        <li>
                            <a href="{$view->getUrl($page)}">{$page}</a>
                        </li>
                    {/foreach}
                {/if}

                <li>
                    <span class="current">{$pager->getPage()}</span>
                </li>

                {if $view->hasNextPage()}
                    {foreach $view->iterNextPage() as $page}
                        <li>
                            <a href="{$view->getUrl($page)}">{$page}</a>
                        </li>
                    {/foreach}
                {/if}

                <li class="next">
                    {if $view->hasNextPage()}
                        <a href="{$view->getUrl($view->getPage() + 1)}">&rarr;</a>
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
</section>
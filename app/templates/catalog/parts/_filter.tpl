{if $filters}
<div class="filter-block">
    <div class="block-title">
        Filter by
    </div>

    <ul class="accordion" data-accordion data-allow-all-closed="true" data-multi-expand="true">
        {foreach $filters as $item}
            <li class="accordion-item" data-accordion-item>
                <a class="accordion-title">
                    <span>{$item.name}</span>
                </a>

                <div class="accordion-content" data-tab-content>
                {if $item.type == 'price'}
                    {include 'catalog/parts/filters/price.tpl' values=$item.values key=$item.key}
                {elseif $item.type == 'list'}
                    {include 'catalog/parts/filters/list.tpl' values=$item.values key=$item.key}
                {/if}
                </div>
            </li>
        {/foreach}
    </ul>

</div>
{/if}
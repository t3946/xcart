{if $filters}
    {set $show = false}
    {foreach $filters as $item}

        {if $item.type == 'price'}
            {if $item.values.prices.min != $item.values.selected.min || $item.values.prices.max != $item.values.selected.max}
                {set $show = true}
            {/if}
        {elseif $item.type == 'list'}
            {foreach $item.values as $val}
                {if $val.checked}
                    {set $show = true}
                {/if}
            {/foreach}
        {/if}

    {/foreach}

    {if $show}
    <section class="filter_reset">
        <div class="row small-up-1 medium-up-2">
        {foreach $filters as $item}

            {if $item.type == 'price'}
                {if $item.values.prices.min != $item.values.selected.min || $item.values.prices.max != $item.values.selected.max}
                    <div class="column column-block fv-remove-{$val.value}">
                        <div class="filter_item" data-group="fv-group-{$item.key}">
                            Price from {$item.values.selected.min} to {$item.values.selected.max}
                        </div>
                    </div>
                {/if}
            {elseif $item.type == 'list'}
                {foreach $item.values as $val}
                    {if $val.checked}
                    <div class="column column-block fv-remove-{$val.value}">
                        <div class="filter_item" data-group="fv-group-{$val.value}" data-fv-val="{$val.value}">
                            {$val.name}
                            <span class="filter_group">
                                ({$item.name})
                            </span>
                        </div>

                    </div>
                    {/if}
                {/foreach}
            {/if}

        {/foreach}
        </div>
    </section>
    {/if}
{/if}
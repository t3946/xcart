{if $filters}
<section class="filter_reset">
    <div class="row small-up-1 medium-up-2">
    {foreach $filters as $item}

        {if $item.type == 'price'}
            {* ХЗ, что-то должно быть, только хз че *}
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
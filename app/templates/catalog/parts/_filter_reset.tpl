{if $filters}
<section class="filter_reset">
    <div>
    {foreach $filters as $item}

        <div class="row small-up-1 medium-up-2">

        {if $item.type == 'price'}
            {* ХЗ, что-то должно быть, только хз че *}
        {elseif $item.type == 'list'}
            {foreach $item.values as $val}
                {if $val.checked}
                <div class="column column-block filter_item fv-remove-{$val.value}" data-group="fv-group-{$val.value}" data-fv-val="{$val.value}">
                    {$val.name}
                    <span class="filter_group">
                        ({$item.name})
                    </span>
                </div>
                {/if}
            {/foreach}
        {/if}
        </div>


    {/foreach}
    </div>
</section>
{/if}
{if $item->product_options}
    <div class="item-data">
        <div class="title-selected">Selected options:</div>
        {foreach $item->product_options as $name => $option}
            <div class="value"><span class="name">{$name}</span>: {$option}</div>
        {/foreach}
    </div>
{/if}

{if $order_detail->product_options}
    <div>
        <b>Options:</b>
        {foreach $order_detail->getOptions() as $name => $value}
            {$name}: {$value}
        {/foreach}
    </div>
{/if}
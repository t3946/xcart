{if $order_detail->product_options}
    <div>
        <b>Options:</b>
        {foreach $order_detail->product_options as $o_name => $o_value}
            {$o_name}: {$o_value}
        {/foreach}
    </div>
{/if}
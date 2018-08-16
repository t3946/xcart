{if $item->product_options}
    <div class="item-data">
        {include '_parts/_options.tpl' options=$item->product_options}
    </div>
{/if}
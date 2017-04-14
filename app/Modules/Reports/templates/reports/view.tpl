{extends 'reports/layouts/report_layout.tpl'}

{block 'heading'}
    <h1 align="center">Order report</h1>
{/block}

{block 'content'}
    <div class="row">
        {include 'reports/order/orders_list.tpl' orders=$models}
    </div>
{/block}
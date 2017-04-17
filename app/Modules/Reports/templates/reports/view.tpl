{extends 'reports/layouts/report_layout.tpl'}

{block 'heading'}
    <h1 align="center">Order report</h1>
{/block}

{block 'content'}
    {include 'reports/order/orders_list.tpl' orders=$models}
{/block}

{block 'after-content'}
    <div class="report-row">
        <div class="">
            {raw $pager}
        </div>
    </div>
{/block}
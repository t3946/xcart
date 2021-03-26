{extends "admin/base.tpl"}

{block 'heading'}
    <h1>Welcome to administration area</h1>
{/block}

{block 'main_block'}
    {smarty_admin_block name='Orders info'}
        <p>
            Below is information about the new orders placed by your customers this month/this week/today or since your
            last log in to the administrator area.
        </p>
        {include 'admin/home/_stats.tpl'}
        <br/>
        <br/>
        <b>Average daily S3 Stores sales:</b> {$average_daily_sales|site_currency}
        <b>Authorized outstanding:</b> {$authorized_outstanding|site_currency} ({$authorized_outstanding_count})
        <br/>
        <br/>
        {include 'admin/home/_last_orders.tpl'}

    {/smarty_admin_block}
{/block}
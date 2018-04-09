{extends 'layout/thank_for_order.tpl'}
{block 'head'}
    <link href="/static/frontend/production/thankyoufororder.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet">
{/block}
{block 'content'}
    <div class="content">

        <div class="thanks main_tips">
            <span class="thanks main_tips">Thank you for your purchase.</span>
            <span class="tips_text">
                <p>If you are satisfied woith our customer service team,<br>
                    feel free to leave a tip. The payment will go straight<br>
                    to customer service from your behalf.
                </p>
            </span>
            <form id="send" method="post" action="{url 'user:tips_log'}" enctype="multipart/form-data">
                <input type="hidden" name="order" value="{$order_id}">
                <input type="hidden" name="hash" value="{$hash}">
                {foreach $tips as $key => $value}
                    <input type="submit" class="send" name="cash" value="$ {$value}">
                {/foreach}
            </form>
            <a href="/"><span class="p_s">No, Thanks</span></a>
        </div>
        <div class="freddie">
            <img class="freddie" src="/static/frontend/production/freddy.png">
        </div>
        <div style="clear: both;"></div>
    </div>


{/block}
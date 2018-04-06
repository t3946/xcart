{extends 'layout/thank_for_order.tpl'}
{block 'head'}
    <link href="/static/frontend/production/thankyoufororder.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet">
{/block}
{block 'content'}
    <div class="content">

        <div class="thanks">
            <span class="thanks">You can contribute any amount for a tip.</span>
            <span class="tips_text">
                <p>We will send payment to the <br>
                    operator on your behalf
                </p>
            </span>
            <form id="send" method="post" action="{url 'user:tips_log'}" enctype="multipart/form-data">
                <input type="hidden" name="e" value="{$order_id}">
                <input type="hidden" name="v" value="{$hash}">
                <div class="amount"><input class="amount" step="any" max="{$capture_amount}" min="0" type="number" name="cash" value="{$tips}"><div class="symbol">$</div></div>
                <button class="send_button" form="send"><img src="/static/frontend/production/send_button.png"></button>
            </form>
            <span class="p_s">*From your account will not be written off any money</span>
        </div>
        <div class="freddie">
            <img class="freddie" src="/static/frontend/production/freddy.png">
        </div>
        <div style="clear: both;"></div>
    </div>


{/block}
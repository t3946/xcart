{extends 'layout/thank_for_order.tpl'}
{block 'head'}
    <link href="/static/frontend/production/thankyoufororder.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet">
{/block}
{block 'content'}
    <div class="content">

        <div class="thanks">
            <span class="thanks">Thanks for the tip!</span>
            <span class="tips_text"><p>We will send payment to the <br>
                    operator on your behalf</p></span>
            <form id="send" action="{url 'user:tips_log'}">
                <input type="hidden" name="e" value="{$order_id}">
                <input type="hidden" name="v" value="{$tips}">
                <input type="number" name="v" value="{$tips}">
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
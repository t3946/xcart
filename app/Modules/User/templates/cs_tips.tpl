{extends 'layout/thank_for_order.tpl'}

{block 'content'}
    <div class="content">

        <div class="thanks">
            <span class="thanks">Thanks for the tip!</span>
            <span class="tips_text"><p>We will send payment to the <br>
                    operator on your behalf</p></span>
            <form id="send" action="">
                <input type="hidden" name="e" value="">
                <input type="hidden" name="v" value="">
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
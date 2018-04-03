{extends 'layout/thank_for_order.tpl'}

{block 'content'}
    <div class="content">
        <div>
        <div class="thanks">
            <span class="thanks">Thanks for the tip!</span>
            <span class="tips_text"><p>We will send payment to the <br>
                    operator on your behalf</p></span>
            <form action="">
                <input type="hidden" name="e" value="">
                <input type="hidden" name="v" value="">
                <input   type="submit" value="SEND">
            </form>
        </div>
            <img class="freddie" src="/static/frontend/production/freddy.png">
        </div>
    </div>

{/block}
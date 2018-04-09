{extends 'layout/thank_for_order.tpl'}
{block 'head'}
    <link href="/static/frontend/production/thankyoufororder.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet">
{/block}
{block 'content'}
    <div class="content">

        <div class="thanks tips_done">
            <div class="text_block">
                <span class="thanks tips_done">
                    Thank you for your tip!
                </span>
                    <p class="thanks">
                        We appreciate your support. <br>
                        We are looking forward to seeing you again.
                    </p>
            </div>
        </div>
        <div class="freddie">
            <img class="freddie_good" src="/static/frontend/production/freddie_good.png">
        </div>
        <div style="clear: both;"></div>
    </div>


{/block}
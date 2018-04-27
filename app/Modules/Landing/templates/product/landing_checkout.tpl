{extends 'layout/landing_page.tpl'}

{block 'seo'}
    <title>Order | Wick Candle Maker by We R Memory Keepers | Artist Supply Source</title>
{/block}

{block 'content'}
    <div class="form-block">
        <div class="row">
            <div class="column small-12 text-center">
                <div class="form">
                    <h1>Your order</h1>
                    <div class="hr"></div>

                    <div class="info">
                        We’re sorry it's out of stock right now. <br>
                        You can get it right away when available! <br>
                        We will notice you ASAP. <br>
                        No advance payment needed.
                    </div>

                    <form action="" method="post">
                        {$form->render()}


                        <div class="buttons">
                            <button class="button red">
                                PRE-ORDER
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
{/block}

{extends 'base.tpl'}

{block 'preloader'}{/block}

{block "content"}
    <section class="page pages receipt-confirmation bg-dark-blue" style="
    padding-top: 5em; padding-bottom: 5em; background-image: url(/static/frontend/dist/images/page-bg/dark-page-bg-origin.jpg)">
            <div class="vertical-middle">
                <div class="row w1280 align-middle">
                    <div class="column small-12 medium-9 medium-order-1 align-self-middle">
                        <h1 style="color: white; text-align: center;">{$h1}</h1>
                    </div>
                    <div class="column small-12 medium-3 freddy">
                        <img alt="Freddy" src="/static/frontend/dist/images/freddy.png">
                    </div>
                </div>
            </div>
    </section>
{/block}
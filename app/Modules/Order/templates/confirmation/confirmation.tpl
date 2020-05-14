{extends 'base.tpl'}

{block 'preloader'}{/block}

{block "content"}
    <section class="page pages receipt-confirmation bg-dark-blue" style="
    padding-top: 5em; padding-bottom: 5em; background-image: url(/static/frontend/dist/images/page-bg/dark-page-bg-origin.jpg)">

        {if $model}
            <div class="vertical-middle">
                <div class="row w1280 align-middle">
                    <div class="column small-12 medium-9 medium-order-1 align-self-middle">
                        <h1 style="color: white;">{$h1}</h1>
                        <p style="color: white;">{$content}</p>
                        <p style="color: white;">Would you like to leave us a message regarding your order?</p>
                        <form method="post">
                            <textarea required autocomplete="off" name="message" style="width: 100%; height: 10em;"></textarea>
                            <div class="row align-center submit-button-container button-row default-form">
                                <div class="column no-padding small-12">
                                    <div class="buttons text-center button-row">
                                        <button class="button submit-button" type="submit">Send</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="column small-12 medium-3 freddy">
                        <img alt="Freddy" src="/static/frontend/dist/images/freddy.png">
                    </div>

                </div>
            </div>
        {else}
            <div class="onlyFreddy vertical-middle">
                <div class="row w1280 align-middle align-center">
                    <div class="column show-for-medium medium-2 vertical-middle">
                        &nbsp;
                    </div>
                    <div class="column small-12 medium-3 freddy text-center">
                        <img alt="Freddy" src="/static/frontend/dist/images/freddy.png">
                    </div>
                    <div class="column show-for-medium medium-2 vertical-middle">
                        &nbsp;
                    </div>
                </div>
            </div>
        {/if}
    </section>
{/block}
{extends 'layout/product_amp.tpl'}

{block 'head'}
    {ignore}
        <style amp-boilerplate>body

            {-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both} @-webkit-keyframes -amp-start

            {from{visibility:hidden}to{visibility:visible}} @-moz-keyframes -amp-start

            {from{visibility:hidden}to{visibility:visible}} @-ms-keyframes -amp-start

            {from{visibility:hidden}to{visibility:visible}} @-o-keyframes -amp-start

            {from{visibility:hidden}to{visibility:visible}} @keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style>
        <noscript>
            <style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style>
        </noscript>
    {/ignore}
    <script async src="https://cdn.ampproject.org/v0.js"></script>
    <script async custom-element="amp-bind" src="https://cdn.ampproject.org/v0/amp-bind-0.1.js"></script>
    <script async custom-element="amp-carousel" src="https://cdn.ampproject.org/v0/amp-carousel-0.1.js"></script>
    <script async custom-element="amp-form" src="https://cdn.ampproject.org/v0/amp-form-0.1.js"></script>
    <script async custom-element="amp-selector" src="https://cdn.ampproject.org/v0/amp-selector-0.1.js"></script>
    <script async custom-template="amp-mustache" src="https://cdn.ampproject.org/v0/amp-mustache-0.1.js"></script>
    <script async custom-element="amp-youtube" src="https://cdn.ampproject.org/v0/amp-youtube-0.1.js"></script>
    <script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>
    <style amp-custom>
        {include "product/amp_style.css"}
    </style>
{/block}

{block 'content'}
    {set $site = $.getSite}
    <script type="application/ld+json">{$helper->getDataJsonSchema()}</script>
    <script type="application/ld+json">{$helper->getDataJsonBread($categories)}</script>

    <amp-analytics type="googleanalytics">
        <script type="application/json">
            {
                "vars": {
                    "account": "{$model->getUniqueIdSf()}"
                },
                "triggers": {
                    "default pageview": {
                        "on": "visible",
                        "request": "pageview",
                        "vars": {
                            "title": "{$model->getFrontendName()}"
                        }
                    }
                }
            }
        </script>
    </amp-analytics>
    <header id="header" class="mdl-color--black mdl-color-text--white">
        <div class="container" >
            <a href="//{$site->domain}">
                <amp-img class="amp_logo" src="{$helper->getLogoImage()}" ></amp-img>
            </a>
        </div>
        <hr class="between">
    </header>
    <div class="container main_content" >
        <a class="title" href="{$model->getAbsoluteUrl()}"><h5 class="title">{$model->product}</h5></a>


        <amp-carousel type="slides" layout="fixed-height" height=250 id="carousel"
                      on="slideChange:AMP.setState({ignore}{selected: {slide: event.index}}{/ignore})">
            <!-- Update the `src` of each <amp-img> when the `selected.sku` variable changes. -->
            {foreach $model->getJsonImages() as $image}
                <amp-img width=200 height=250 src="{$image}"></amp-img>
            {/foreach}
        </amp-carousel>


        <!-- The <span> element corresponding to the current displayed slide
             will have the 'current' CSS class. -->
        <p class="dots">
            {set $images = ($model->getJsonImages())}
            {if $images|count > 1}
                {foreach $model->getImages() as $image index=$index first=$first}
                    <span [class]="selected.slide == {$index} ? 'current' : ''" {if $first} class="current"{/if}></span>
                {/foreach}
            {/if}
        </p>

        <form method="get" action="{$model->getAbsoluteUrl()}" target="_top">

            <div class="options price">
                <h6>SKU :
                    <span><b>{$model->productcode}</b><span>
                </h6>
            </div>


            <div class="options price">
                <h6>PRICE :
                    <!-- Display the price of the selected shirt in the selected size if available.
                         Otherwise, display the placeholder text '---'. -->
                    <span>USD <b><span class="mdl-color-text--red">{$model->getFrontendPrice()}</span></b><span>
                </h6>
            </div>

            <div class="options purchase">
                <!-- Disable the “ADD TO CART” button when:
                     1. There is no selected size, OR
                     2. The available sizes for the selected SKU haven’t been fetched yet
                -->
                <input type="submit" value="PLACE AN ORDER"
                       class="mdl-button mdl-button--raised mdl-button--accent">
            </div>


        </form>

        <div class="description">
            {$model->getFrontendDescription()}
        </div>

        {*<amp-youtube
                data-videoid="AjvbqVlxYOs"
                layout="responsive"
                width="240"
                height="120">
            <div fallback>
                <p>The video could not be loaded.</p>
            </div>
        </amp-youtube>*}

        <footer>
            <table class="about">
                <tr>
                    <td class="about">
                        <h4>Telephone Customer Service</h4>
                    </td>
                    <td class="about">
                        <h4>Web Orders</h4>
                    </td>
                </tr>
                <tr>
                    <td class="about">
                        <p>
                            Mon-Fri: 9 a.m. to 5 p.m. EST<br>
                            <a class="telephon" href="tel:1-800-929-2431">Toll Free: 1-800-929-2431</a><br>
                            Tel: (616) 259-5711<br>
                            Fax: (813) 944-4516
                        </p>
                    </td>
                    <td class="about">
                        <p>24 hours a day, 7 days a week<br>
                            Email Support</p>
                    </td>
                </tr>
            </table>

        </footer>
    </div>
{/block}
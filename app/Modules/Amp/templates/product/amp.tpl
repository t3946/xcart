{extends 'layout/product_amp.tpl'}

{block 'head'}
    <script async src="https://cdn.ampproject.org/v0.js"></script>
    <script async custom-element="amp-bind" src="https://cdn.ampproject.org/v0/amp-bind-0.1.js"></script>
    <script async custom-element="amp-carousel" src="https://cdn.ampproject.org/v0/amp-carousel-0.1.js"></script>
    <script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>
    <script async custom-element="amp-social-share" src="https://cdn.ampproject.org/v0/amp-social-share-0.1.js"></script>
    <link rel="canonical" href="{$model->getAbsoluteUrl(true)}">
    {if $model->isNeedForm()}
    <script async custom-element="amp-form" src="https://cdn.ampproject.org/v0/amp-form-0.1.js"></script>
    {/if}
    {if $model->isDescrHasIframe()}
    <script async custom-element="amp-iframe" src="https://cdn.ampproject.org/v0/amp-iframe-0.1.js"></script>
    {/if}
    <meta name="amp-google-client-id-api" content="googleanalytics">
    <style amp-custom>
        {include "product/amp_style.css"}
    </style>
    {ignore}
        <style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>
    {/ignore}
{/block}

{block 'content'}
    {set $site = $.getSite}
    <script type="application/ld+json">{$helper->getDataJsonSchema()}</script>
    <script type="application/ld+json">{$helper->getDataJsonBread($categories)}</script>



    <header id="header" class="mdl-color--black mdl-color-text--white">
        <div class="container" >
            <a href="//{$site->domain}">
                <amp-img class="amp_logo" layout="fixed-height" height="62" src="{$helper->getLogoImage()}" ></amp-img>
            </a>
        </div>
        <hr class="between">
    </header>

    <div class="container main_content" >
        <a class="title" href="{$model->getAbsoluteUrl()}"><h5 id="original_url"  class="title">{$model->getFrontendName()}</h5></a>

        <amp-carousel class="slide_images" type="slides" layout="fixed-height" height=250 id="carousel"
                      on="slideChange:AMP.setState({ignore}{selected: {slide: event.index}}{/ignore})">
            <!-- Update the `src` of each <amp-img> when the `selected.sku` variable changes. -->
            {foreach $model->getJsonImages() as $image}
                <amp-img width=200 height=250 src="{$image}"></amp-img>
            {/foreach}
        </amp-carousel>


        <!-- The <span> element corresponding to the current displayed slide
             will have the 'current' CSS class. -->
        <p class="dots" >
            {set $images = ($model->getJsonImages())}
            {if $images|count > 1}
                {foreach $images as $image index=$index first=$first}
                    <span [class]="selected.slide == {$index} ? 'current' : ''" {if $first} class="current"{/if}></span>
                {/foreach}
            {/if}
        </p>

        <div class="social">
            <amp-social-share type="facebook"
                              data-param-app_id="254325784911610" width="30" height="30"></amp-social-share>
            <amp-social-share type="twitter" width="30" height="30"></amp-social-share>
            <amp-social-share type="pinterest"
                              data-param-media="https://ampbyexample.com/img/amp.jpg" width="30" height="30"></amp-social-share>
            <amp-social-share type="whatsapp" width="30" height="30"></amp-social-share>
            <amp-social-share type="email" width="30" height="30"></amp-social-share>
        </div>

        {if $model->isGroupRoot()}
            <h6 id="sku">SKU:
                <span>{$model->productcode}<span>
            </h6>
            {if $model->getFrontendPrice() > 0}
                {if $model->getFrontendPrice() != $model->getFrontendPrice(2)}
                    <h6><b>PRICE RANGE:</b>
                        <span ><b>US$ <span class="mdl-color-text--red">{$model->getFrontendPrice()|number_format:2} - US$ {$model->getFrontendPrice(2)|number_format:2}</span></b></span>
                    </h6>

                {/if}
            {/if}
            <a class="button_redirect" id="group_prod" href="{$model->getAbsoluteUrl()}">
                <b>FULL PRODUCT LINE</b>
            </a>

            <br><br>

            {else}
                {if !$model->isOutOfStock()}
                    <form action="{url 'cart:add' uniqueId=$model->productid}" method="get" target="_top">
                        <div class="options price">
                            <h6 id="sku">SKU:
                                <span>{$model->productcode}<span>
                            </h6>
                        </div>
                            <p><b>In Stock</b></p>
                        <div class="options price">
                            <h6><b>PRICE:</b>
                                <!-- Display the price of the selected shirt in the selected size if available.
                                     Otherwise, display the placeholder text '---'. -->
                                <span ><b>US$ <span class="mdl-color-text--red">{$model->getFrontendPrice()|number_format:2}</span></b><span>
                            </h6>
                        </div>

                        <div class="options purchase">
                            <!-- Disable the “ADD TO CART” button when:
                                 1. There is no selected size, OR
                                 2. The available sizes for the selected SKU haven’t been fetched yet
                            -->

                                <button id="place_order" class="mdl-button mdl-button--raised mdl-button--accent add_to_cart" >
                                    Add to cart
                                </button>



                        </div>
                    </form>

                {else}
                    <div class="similar">
                        <div class="options price">
                            <h6 id="sku">SKU:
                                <span>{$model->productcode}<span>
                            </h6>
                        </div>

                        <p><b>Out Of Stock</b></p>
                        <div class="options price">

                            <h6><b>PRICE:</b>
                                <!-- Display the price of the selected shirt in the selected size if available.
                                     Otherwise, display the placeholder text '---'. -->
                                <span ><b>US$ <span class="mdl-color-text--red">{$model->getFrontendPrice()|number_format:2}</span></b></span>
                            </h6>


                        </div>
                        <a class="button_redirect" id="similar_prod" href="{$helper->getLastChildCategoryUrl()}">
                            <b>FIND SIMILAR PRODUCTS</b>
                        </a>

                    </div>
                {/if}
        {/if}


        <div class="description">
            {$model->getAmpFrontendDescription()|trim|nl2br}
        </div>

        {if $model->isGroupChild()}
            <br>
                <a class="button_redirect_2" id="group_prod_2" href="{$model->parent->getAbsoluteUrl()}">
                    <b>See other product variations</b>
                </a>
            <br>
        {/if}

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
            <hr class="between" id="hr_footer">
            <div class="about">
                <div class="about_child">
                    <h4>
                        Telephone Customer Service
                    </h4>
                    <p class="about_text">
                        Mon-Fri: 9 a.m. to 5 p.m. EST<br>
                        <a class="telephon" href="tel:1-800-929-2431">Toll Free: 1-800-929-2431</a><br>
                        Tel: (616) 259-5711<br>
                        Fax: 1-800-929-2835
                    </p>
                </div>
                <div>
                    <h4>
                        Web Orders
                    </h4>
                    <p class="about_text">
                        24 hours a day, 7 days a week<br><br>
                        <b>Email Support</b><br>
                        <a class="telephon" href="/help.php?section=contactus&mode=update" >Contact us</a>
                    </p>
                </div>
                <div>
                    <h4>
                        USA Address
                    </h4>
                    <p class="about_text">
                        S3 Stores, Inc.<br>
                        2885 Sanford Ave SW #12717<br>
                        Grandville, MI 49418<br>
                        USA
                    </p>
                </div>
                <div>
                    <h4>
                        Canadian Address
                    </h4>
                    <p class="about_text">
                        S3 Stores, Inc.<br>
                        27 Joseph St.<br>
                        Chatham, Ontario N7L 3G4<br>
                        Canada</p>
                    </p>
                </div>
            </div>

            </table>

        </footer>
    </div>

    <amp-analytics type="googleanalytics" id="analytics1">
        <script type="application/json">
            {
                "vars": {
                    "account": "{$ga_account}"
                },
                "triggers": {
                    "default pageview":
                    {
                        "on": "visible",
                        "request": "pageview",
                        "vars": {
                            "title": "{$model->getFrontendName()|escape}"
                        }
                    },

                    "trackClickOnSlider" :
                    {
                        "on": "click",
                        "selector": "#carousel",
                        "request": "event",
                        "vars": {
                            "eventCategory": "images-scroll",
                            "eventAction": "slider-click"
                        }
                    },

                    "trackClickOnOrder" :
                    {
                        "on": "click",
                        "selector": "#place_order",
                        "request": "event",
                        "vars": {
                            "eventCategory": "add-to-cart",
                            "eventAction": "button-click"
                        }
                    },

                    "trackClickOnButton" :
                    {
                        "on": "click",
                        "selector": "#similar_prod",
                        "request": "event",
                        "vars": {
                            "eventCategory": "find-similar-products",
                            "eventAction": "button-click"
                        }
                    },

                    "trackClickOnButtonGroup" :
                    {
                        "on": "click",
                        "selector": "#group_prod",
                        "request": "event",
                        "vars": {
                            "eventCategory": "full-product-line",
                            "eventAction": "button-click"
                        }
                    },

                    "trackClickOnPage" :
                    {
                        "on": "click",
                        "selector": "#main",
                        "request": "event",
                        "vars": {
                            "eventCategory": "page",
                            "eventAction": "page-click"
                        }
                    }
                }
            }
        </script>
    </amp-analytics>

{/block}
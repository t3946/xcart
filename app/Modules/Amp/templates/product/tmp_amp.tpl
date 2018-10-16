
<!doctype html>
<html ⚡ lang="en">
<head>
    <meta charset="utf-8">
    {block 'seo'}
        {meta controller=$this!:null}
    {/block}

    {add $site = $.getSite()}

    <link rel="canonical" href="{$model->getAbsoluteUrl(true)}">
    <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
    <style amp-boilerplate>
        body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}
    </style>
    <noscript>
        <style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style>
    </noscript>
    <script async src="https://cdn.ampproject.org/v0.js"></script>

    <script async custom-element="amp-bind" src="https://cdn.ampproject.org/v0/amp-bind-0.1.js"></script>
    <script async custom-element="amp-carousel" src="https://cdn.ampproject.org/v0/amp-carousel-0.1.js"></script>
    <script async custom-element="amp-form" src="https://cdn.ampproject.org/v0/amp-form-0.1.js"></script>
    <script async custom-element="amp-selector" src="https://cdn.ampproject.org/v0/amp-selector-0.1.js"></script>
    <script async custom-template="amp-mustache" src="https://cdn.ampproject.org/v0/amp-mustache-0.1.js"></script>
    <script async custom-element="amp-youtube" src="https://cdn.ampproject.org/v0/amp-youtube-0.1.js"></script>
    <script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>


</head>

<body>

<script type="application/ld+json">
{
  "@context": "http://schema.org/",
  "@type": "Product",
  "name": "{$model->product}",
  "image": [
    {$model->getJsonImages}
  ],

  "description": "{$model->getFulldescr()}",
  "mpn": "{$model->getMPN()}",
  "brand": {
    "@type": "Thing",
    "name": "{$model->brand->brand}"
  },
  "offers": {
    "@type": "Offer",
    "priceCurrency": "USD",
    "price": "{$model->getPrice()}",
    "itemCondition": "http://schema.org/NewCondition",
    {if ($model->avail > 0)}
        "availability": "http://schema.org/InStock",
    {else}
        "availability": "http://schema.org/OutOfStock",
    {/if}
    "seller": {
      "@type": "Organization",
      "name": "S3Stores, Inc."
    }
  }
}
</script>

<script type="application/ld+json">
{
  "@context": "http://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [{
    "@type": "ListItem",
    "position": 1,
    "item": {
      "@id": "https://www.artistsupplysource.com/category/37460/easels/",
      "name": "Easels"    }
  },{
    "@type": "ListItem",
    "position": 2,
    "item": {
      "@id": "https://www.artistsupplysource.com/category/50370/studio-easels/",
      "name": "Studio Easels"
    }
  }]
}
</script>

<amp-analytics type="googleanalytics">
    <script type="application/json">
        {
            "vars": {
                "account": "{$model->getSfCode()}"
            },
            "triggers": {
                "default pageview": {
                    "on": "visible",
                    "request": "pageview",
                }
            }
        }
    </script>
</amp-analytics>


<header id="header" class="mdl-color--black mdl-color-text--white">


    <a href="{$.getSite()->domain)}">
        {add $names = $.getSite()->list_config->getName()|explode:" "}
        {foreach $names as $n}
            <span class="{cycle ["mdl-color-text--red", "mdl-color-text--white"]}">
                {$n}
            </span>
        {/foreach}
    </a>
    <amp-img class="search" src="./img/ic_search_white_24dp_1x.png" width=24 height=24></amp-img>
</header>

<div id="container">
    <a href="{$model->getAbsoluteUrl(true)}"><h5 class="title">{$model->product}</h5></a>
    //<h6 class="rating"><span class="mdl-color-text--red">★★★★☆</span> <span class="mdl-color-text--grey">(14)</span></h6>


    <amp-carousel type="slides" layout="fixed-height" height=250 id="carousel"
                  on="slideChange:AMP.setState({selected: {slide: event.index}})">
        <!-- Update the `src` of each <amp-img> when the `selected.sku` variable changes. -->
        {foreach $model->getImages() as $image}
            <amp-img width=200 height=250 src="{$image->getURL()}"></amp-img>
        {/foreach}

    <!-- The <span> element corresponding to the current displayed slide
         will have the 'current' CSS class. -->
    <p class="dots">
        {set $images = ($model->getImages()}
        {if $images|count > 1}
            {foreach $model->getImages() as $image index=$index first=$first}
                <span [class]="selected.slide == {$index} ? 'current' : ''" {if $first} class="current"{/if}></span>
            {/foreach}
        {/if}

    </p>

    <form method="get" action="{$model->getAbsoluteUrl(true)}" target="_top">

        <div class="options price">
            <h6>SKU :
                <span><b>{$model->productcode}</b><span>
            </h6>
        </div>


        <div class="options price">
            <h6>PRICE :
                <!-- Display the price of the selected shirt in the selected size if available.
                     Otherwise, display the placeholder text '---'. -->
                <span>USD <b><span class="mdl-color-text--red">${$model->getPrice()}</span></b><span>
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

    <p class="description"><B>About {$model->product}</b><BR>
        {$model->getFulldescr()}
    </p>

   {* <amp-youtube
            data-videoid="AjvbqVlxYOs"
            layout="responsive"
            width="240"
            height="120">
        <div fallback>
            <p>The video could not be loaded.</p>
        </div>
    </amp-youtube>*}



    <p class="amp-footer">
        <b>Web Orders</b><br>
        24 hours a day, 7 days a week<br>
        Email Support
        <br><br><br>
        <b>Contact Us:</b><br>

        Telephone Customer Service<br>
        Mon-Fri: 9 a.m. to 5 p.m. EST<br>
        Toll Free: 1-800-929-2431<br>
        Tel: (616) 259-5711<br>
        Fax: 1-800-929-2835<br>
        <br><br>


        <b>USA Address</b><br>
        S3 Stores, Inc.<br>
        2885 Sanford Ave SW #12717<br>
        Grandville, MI 49418<br>
        USA<br>
        <br><br>
        <b>Canadian Address</b><br>
        S3 Stores, Inc.<br>
        27 Joseph St.<br>
        Chatham, Ontario N7L 3G4<br>
        Canada<br>
</div>


</body>
</html>
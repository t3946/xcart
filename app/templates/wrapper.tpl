<!doctype html>
<html lang="en" class="no-js {if $.detector->isMobile()} mobile {/if}{if $.detector->isTablet()} tablet {/if}" itemscope itemtype="http://schema.org/WebSite">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0" />
    <meta name="format-detection" content="telephone=no">
    <meta name="format-detection" content="date=no">
    <meta name="format-detection" content="address=no">
    <meta name="format-detection" content="email=no">

    <link rel="dns-prefetch" href="https://www.google-analytics.com">
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">

    {*<link rel="manifest" href="/manifest.json">*}

    <meta name="apple-touch-fullscreen" content="yes" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    {*<meta name="apple-mobile-web-app-title" content="S3 Stores">*}

    <meta name="mobile-web-app-capable" content="yes">
    <meta name="application-name" content="S3 Stores">

    {*<meta name="url" itemprop="url" content="https://s3stores.com/" >*}
    {*<meta name="name" itemprop='name' content="S3 Stores">*}
    <link rel="shortcut icon" href="/favicon.png" type="image/png" />

    <script type="text/javascript">
        window['app'] = {
            afterReady:[],
            options: {
                'session_key': '{$.sessionKey}',
                'urls': {
                    cart: {
                        add: '{url "cart:products:add"}',
                        get: '{url "cart:products:get"}',
                        set: '{url "cart:products:set"}',
                        del: '{url "cart:products:del"}',
                    }
                }
            }
        };
    </script>

    <script type="application/ld+json">
    {
      "@context": "http://schema.org",
      "@type": "WebSite",
      "url": "https://{$.getSite->domain}/",
      "potentialAction": {
        "@type": "SearchAction",
        "target": "https://{$.getSite->domain}/search?q={ignore}{search_term_string}{/ignore}",
        "query-input": "required name=search_term_string"
      }
    }
    </script>

    <style type="text/css">{inline file="static/frontend/dist/css/base.css"}</style>
    <link rel="stylesheet" href="/static/frontend/dist/css/styles.css?v={frontend_version resource='css/styles.css'}" media="all">

    {*<script src="/static/frontend/dist/js/vendors.js?v={frontend_version resource='vendors.js'}" defer></script>*}

    {block 'seo'}{meta controller=$this!:null}{/block}
    {block 'head'}{/block}

    {get_assets:raw type='css' position='head'}
    {get_assets:raw type='js' position='head'}
</head>
<body itemscope itemprop="mainEntity" {block 'schema_page_type'}itemtype="http://schema.org/WebPage"{/block} class="loading loading-active">

{filter|strip:true}
{autoescape true}
{block 'preloader'}
    <div class="loader-bg waves waves-dark">
        <div class="loader-wrapper">
            <div class="loader-spinner"></div>
            <div class="loader-container"></div>
        </div>
    </div>
{/block}

{block "wrapper"}
    {block "content"}{/block}
{/block}
{/autoescape}
{/filter}

    <script src="/static/frontend/dist/js/main.js?v={frontend_version resource="js/main.js"}" defer></script>

{block 'js'}{/block}

{get_assets:raw type='css'}
{get_assets:raw type='js'}

</body>
</html>
<!doctype html>
<html lang="en" class="no-js {if $.detector->isMobile()} mobile {/if}{if $.detector->isTablet()} tablet {/if}" itemscope itemtype="http://schema.org/WebSite">
<head>
    <meta charset="utf-8">

    {block 'seo'}{meta controller=$this!:null}{/block}

    {set $site = $.getSite}
    {set $config  = $site->getConfig()}

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0" />
    <meta name="format-detection" content="telephone=no">
    <meta name="format-detection" content="date=no">
    <meta name="format-detection" content="address=no">
    <meta name="format-detection" content="email=no">

    <link rel="dns-prefetch" href="{$site->getHttpOrHttps() ~ $config.CDN_domain}">
    <link rel="dns-prefetch" href="https://www.google-analytics.com">
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="{$site->getHttpOrHttps() ~ $config.CDN_domain}">
    <link rel="preconnect" href="https://www.google-analytics.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="prefetch" href="/static/frontend/dist/css/styles.css" as="style">
    <link rel="prefetch" href="/static/frontend/dist/js/main.js" as="script">

    {*<link rel="manifest" href="/manifest.json">*}

    <meta name="apple-touch-fullscreen" content="yes" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    {*<meta name="apple-mobile-web-app-title" content="S3 Stores">*}

    <meta name="mobile-web-app-capable" content="yes">
    <meta name="application-name" content="{$site->getName()}">

    {*<meta name="url" itemprop="url" content="{$.getSite->getAbsoluteUrl()}" >*}
    <meta name="name" itemprop='name' content="{$site->getName()}">

    <link rel="shortcut icon" href="/favicon.png" type="image/png" />

    <script type="text/javascript">
        window.app = {
            assets: {
                cssLoaded: false,
            },
            afterReady:[],
            assets: {
                'css': {
                    'styles.css': {
                        'loaded': false,
                    }
                }
            },
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
        window.parseUrl = function(href) { var a = document.createElement("a");a.href = href;return { 'href':href,'protocol': a.protocol,'host': a.host,'hostname': a.hostname,'port': a.port,'pathname': a.pathname,'hash': a.hash,'search': a.search,'origin': a.origin, 'document':a.pathname.split("/").pop(),};}
    </script>

    <script type="application/ld+json">
    {
      "@context": "http://schema.org",
      "@type": "WebSite",
      "url": "https://{$site->domain}/",
      "potentialAction": {
        "@type": "SearchAction",
        "target": "https://{$site->domain}/search?q={ignore}{search_term_string}{/ignore}",
        "query-input": "required name=search_term_string"
      }
    }
    </script>

    <style type="text/css">{inline file="static/frontend/dist/css/base.css"}</style>

    {*<script src="/static/frontend/dist/js/vendors.js?v={frontend_version resource='vendors.js'}" defer></script>*}

    {block 'head'}{/block}

    {set $gConfig = $site->getGlobalConfig()}

    {$gConfig.google_analitics_tracking_script
        |replace:'{{ga_account_nr}}':$config.cidev_ga_code_number
        |replace:'{{ga_ec_data}}':"ga('require', 'ec');"
        |replace:'{{ga_send}}':"ga('send', 'pageview');"
    }

    {get_assets:raw type='css' position='head'}
    {get_assets:raw type='js' position='head'}
</head>
<body itemscope itemprop="mainEntity" {block 'schema_page_type'}itemtype="http://schema.org/WebPage"{/block}
      {*class="loading loading-active"*}
>

{filter|strip:true}
{autoescape true}
{block 'preloader'}
    {*<div class="loader-bg waves waves-dark">*}
        {*<div class="loader-wrapper">*}
            {*<div class="loader-spinner"></div>*}
            {*<div class="loader-container"></div>*}
        {*</div>*}
    {*</div>*}
{/block}

{block "wrapper"}
    {block "content"}{/block}
{/block}
{/autoescape}
{/filter}

    {*<noscript class="styles_no_load" id="deferred-styles">*}
        {*<link rel="stylesheet" href="/static/frontend/dist/css/styles.css?v={frontend_version resource='css/styles.css'}" media="all">*}
    {*</noscript>*}



{block 'js'}{/block}

{get_assets:raw type='css'}
{get_assets:raw type='js'}


<script>
    (function(){
        var createStyleElement = function (href) {
            var h = document.getElementsByTagName('head')[0];
            var l = document.createElement('link');

            l.rel = 'stylesheet';
            l.href = href;
            l.onload = function(){
                var url = parseUrl(this.href);
                window.app.assets.css[url.document].loaded = true;
                document.dispatchEvent(new CustomEvent('cssLoad', { 'file': url.document }));
            };

            h.parentNode.insertBefore(l, h);
        };

        var cb = function() {
            setTimeout(function(){
                createStyleElement( "/static/frontend/dist/css/styles.css?v={frontend_version resource='css/styles.css'}");
            }, 40);

            window.LHCChatOptions = {
                opt: {
                    'widget_height':540,
                    'widget_width':300,
                    'popup_height':520,
                    'popup_width':500,
                    'domain':'{$site->domain}'
                }
            };

            var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true; po.defer = true;
            var referrer = (document.referrer) ? encodeURIComponent(document.referrer.substr(document.referrer.indexOf('://')+1)) : '';
            var location  = (document.location) ? encodeURIComponent(window.location.href.substring(window.location.protocol.length)) : '';
            po.src = '//livechat.s3stores.com/index.php/chat/getstatus/(click)/internal/(position)/bottom_left/(ma)/br/(check_operator_messages)/true/(top)/350/(units)/pixels/(leaveamessage)/true/(department)/2?r='+referrer+'&l='+location;
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
        };
        var raf = requestAnimationFrame || mozRequestAnimationFrame ||
            webkitRequestAnimationFrame || msRequestAnimationFrame;
        if (raf) raf(cb);
        else window.addEventListener('DOMContentLoaded', cb);
    })();
</script>

{render_flash:raw template='base/_flash.tpl'}

<script src="/static/frontend/dist/js/main.js?v={frontend_version resource="js/main.js"}" async></script>
</body>
</html>
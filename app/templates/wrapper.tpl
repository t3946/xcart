<!doctype html>
<html lang="en" class="no-js {if $.detector->isMobile()} mobile {/if}{if $.detector->isTablet()} tablet {/if}" itemscope itemtype="http://schema.org/WebSite">
<head>
    <meta charset="utf-8">

    {block 'seo'}{meta controller=$this!:null}{/block}

    {set $site = $.getSite}
    {set $config  = $site->getConfig()}

    {*<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0" />*}
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
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

    <link rel="preload" href="/static/frontend/dist/css/styles.css?v={frontend_version resource='css/styles.css'}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="/static/frontend/dist/js/main.js?v={frontend_version resource="js/main.js"}" as="script">

    {*<link rel="manifest" href="/manifest.json">*}

    <meta name="apple-touch-fullscreen" content="yes" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    {*<meta name="apple-mobile-web-app-title" content="S3 Stores">*}

    <meta name="mobile-web-app-capable" content="yes">
    <meta name="application-name" content="{$site->getName()}">

    {*<meta name="url" itemprop="url" content="{$.getSite->getAbsoluteUrl()}" >*}
    <meta name="name" content="{$site->getName()}">
    <meta itemprop='name' content="{$site->getName()}">

    {block 'noindex'}{/block}


    <link rel="shortcut icon" href="{$site->favicons->get()}" type="image/png" />

    <script>
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

    <style>{inline file="static/frontend/dist/css/base.css"}</style>

    {*<script src="/static/frontend/dist/js/vendors.js?v={frontend_version resource='vendors.js'}" defer></script>*}

    {block 'head'}{/block}

    {set $gConfig = $site->getGlobalConfig()}

    {$gConfig.google_analitics_tracking_script
        |replace:'{{ga_account_nr}}':$config.cidev_ga_code_number
        |replace:'{{ga_ec_data}}':""
        |replace:'{{ga_send}}':""
    }

    {get_assets:raw type='css' position='head'}
    {get_assets:raw type='js' position='head'}

    <script>
        /*! loadCSS. [c]2017 Filament Group, Inc. MIT License */
        /* This file is meant as a standalone workflow for
        - testing support for link[rel=preload]
        - enabling async CSS loading in browsers that do not support rel=preload
        - applying rel preload css once loaded, whether supported or not.
        */
        (function( w ){
            "use strict";
            // rel=preload support test
            if( !w.loadCSS ){
                w.loadCSS = function(){};
            }
            // define on the loadCSS obj
            var rp = loadCSS.relpreload = {};
            // rel=preload feature support test
            // runs once and returns a function for compat purposes
            rp.support = (function(){
                var ret;
                try {
                    ret = w.document.createElement( "link" ).relList.supports( "preload" );
                } catch (e) {
                    ret = false;
                }
                return function(){
                    return ret;
                };
            })();

            // if preload isn't supported, get an asynchronous load by using a non-matching media attribute
            // then change that media back to its intended value on load
            rp.bindMediaToggle = function( link ){
                // remember existing media attr for ultimate state, or default to 'all'
                var finalMedia = link.media || "all";

                function enableStylesheet(){
                    link.media = finalMedia;
                }

                // bind load handlers to enable media
                if( link.addEventListener ){
                    link.addEventListener( "load", enableStylesheet );
                } else if( link.attachEvent ){
                    link.attachEvent( "onload", enableStylesheet );
                }

                // Set rel and non-applicable media type to start an async request
                // note: timeout allows this to happen async to let rendering continue in IE
                setTimeout(function(){
                    link.rel = "stylesheet";
                    link.media = "only x";
                });
                // also enable media after 3 seconds,
                // which will catch very old browsers (android 2.x, old firefox) that don't support onload on link
                setTimeout( enableStylesheet, 3000 );
            };

            // loop through link elements in DOM
            rp.poly = function(){
                // double check this to prevent external calls from running
                if( rp.support() ){
                    return;
                }
                var links = w.document.getElementsByTagName( "link" );
                for( var i = 0; i < links.length; i++ ){
                    var link = links[ i ];
                    // qualify links to those with rel=preload and as=style attrs
                    if( link.rel === "preload" && link.getAttribute( "as" ) === "style" && !link.getAttribute( "data-loadcss" ) ){
                        // prevent rerunning on link
                        link.setAttribute( "data-loadcss", true );
                        // bind listeners to toggle media back
                        rp.bindMediaToggle( link );
                    }
                }
            };

            // if unsupported, run the polyfill
            if( !rp.support() ){
                // run once at least
                rp.poly();

                // rerun poly on an interval until onload
                var run = w.setInterval( rp.poly, 500 );
                if( w.addEventListener ){
                    w.addEventListener( "load", function(){
                        rp.poly();
                        w.clearInterval( run );
                    } );
                } else if( w.attachEvent ){
                    w.attachEvent( "onload", function(){
                        rp.poly();
                        w.clearInterval( run );
                    } );
                }
            }

            // commonjs
            if( typeof exports !== "undefined" ){
                exports.loadCSS = loadCSS;
            }
            else {
                w.loadCSS = loadCSS;
            }
        }( typeof global !== "undefined" ? global : this ) );
    </script>
    <script></script>

</head>
<body itemscope itemprop="mainEntity" {block 'schema_page_type'}itemtype="http://schema.org/WebPage"{/block}
      class="loading loading-active"
>

{filter|strip:true}
{autoescape true}
{block 'preloader'}
    {*<div class="loader-bg waves waves-dark">
        <div class="loader-wrapper">
            <div class="loader-spinner"></div>
            <div class="loader-container"></div>
        </div>
    </div>*}
{/block}

{block "wrapper"}
    {block "content"}{/block}
{/block}
{/autoescape}
{/filter}


{block 'js'}{/block}

{get_assets:raw type='css'}
{get_assets:raw type='js'}


<script>
    (function(){
        var createStyleElement = function (href) {
            var l = document.createElement('link');

            l.rel = 'stylesheet';
            l.href = href;
            l.onload = function(){
                var url = parseUrl(this.href);
                window.app.assets.css[url.document].loaded = true;
                document.dispatchEvent(new CustomEvent('cssLoad', { 'file': url.document }));
            };

            document.body.appendChild(l)

        };

        var createJsElement = function (href) {
            var l = document.createElement('script');
            l.async = true;
            l.src = href;

            document.body.appendChild(l)

        };

        var cb = function() {
            {*createStyleElement( "/static/frontend/dist/css/styles.css?v={frontend_version resource='css/styles.css'}");*}
        };
        var raf = requestAnimationFrame || mozRequestAnimationFrame ||
            webkitRequestAnimationFrame || msRequestAnimationFrame;
        if (raf) raf(cb);
        else window.addEventListener('load', setTimeout(cb, 0));

        window.addEventListener("load", function(event) {

            createJsElement("/static/frontend/dist/js/main.js?v={frontend_version resource="js/main.js"}");

            setTimeout(function() {

                window.LHCChatOptions = {
                    opt: {
                        'widget_height': 540,
                        'widget_width': 300,
                        'popup_height': 520,
                        'popup_width': 500,
                        'domain': '{$site->domain}'
                    }
                };

                var po = document.createElement('script');
                po.async = true;
                po.defer = true;
                var referrer = (document.referrer) ? encodeURIComponent(document.referrer.substr(document.referrer.indexOf('://') + 1)) : '';
                var location = (document.location) ? encodeURIComponent(window.location.href.substring(window.location.protocol.length)) : '';
                po.src = '//livechat.s3stores.com/index.php/chat/getstatus/(click)/internal/(position)/bottom_left/(ma)/br/(check_operator_messages)/true/(top)/350/(units)/pixels/(leaveamessage)/true/(department)/2?r=' + referrer + '&l=' + location;
                var s = document.getElementsByTagName('script')[0];
                s.parentNode.insertBefore(po, s);
            }, 10000);
        });

    })();


</script>

{render_flash:raw template='base/_flash.tpl'}

</body>
</html>
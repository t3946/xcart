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

    <meta name="google-site-verification" content="0nGAQ-4SOMyfb83Zrnubq1AmXaVEkC-ih7_k-qcQIZY" />
    <meta name="google-site-verification" content="AuklWLB6xWi7I6-ouZut2qhCVsiQY9iSdEbdr0ai1Xk" />
    <meta name="google-site-verification" content="cs4om2RUXeEExW3Y-4HdQo-pNMKOOUkhPcBD5jrTfMo" />
    <meta name="google-site-verification" content="4hZo58cQBieJn9BqwJ__lvOyXhfWvaDlgF-p6O1Z_CQ" />
    <meta name="google-site-verification" content="_qnB8hMsM7wJucHYDwgsUbtK8fswfjVoHuC16WhYgjw" />
    <meta name="google-site-verification" content="bgrrJ8qmWvyZUH5jbYy0OfDLpPKSwfDOhAt5vmbzMsU" />
    <meta name="google-site-verification" content="scxRoqb6NJEUXoP2ZVcY3Ms0aUfDnw16-fJK3ol6aro" />
    <meta name="google-site-verification" content="xcWrYOFK7y9SafTrdiieEqIC_QxfmkbtbuGHBV_e1Hw" />
    <meta name="google-site-verification" content="4lDc2zPFURHPxwYdEVAAtJu0Ry1rIs27D04eesHDeII" />
    <meta name="google-site-verification" content="rBY8vmRP2VWQytrEs_Q1dtkVOkuJBuriC5zTfiC9lXA" />
    <meta name="google-site-verification" content="B0eY_G0gxJFMdvZT8rFFTgD9Z1sJ218YqKyddY31blY" />
    <meta name="google-site-verification" content="uk8dkT24IvXNMuHbakvXRXmXxiMvVBY_LJiGXL8uqRI" />
    <meta name="google-site-verification" content="YtA_nw84whA0tzbff6Uh0SLx0-iTuh7R7ki_8lwiBZA" />
    <meta name="google-site-verification" content="rlZfF0AlX2zWYF6CYMam4oK3h4I4guvVJ1DwnRCR1HU" />
    <meta name="google-site-verification" content="TC3YNSITtar8XMbwtdunh4zl1utzGmn3dQYQRHsem6Y" />
    <meta name="google-site-verification" content="99yK2lcGwzRriOZeRMc2oXHn4C54_FpvrHO4zlvXe5Q" />
    <meta name="google-site-verification" content="BtT4lRJtO8hHIOkOYpP9ogdQbPHD1Yw3i7nrVAC4mIg" />
    <meta name="google-site-verification" content="NWPGDn5yQkUfZ2E__ubiUDp7q5a_bj1DaWMpMUYpZYo" />
    <meta name="google-site-verification" content="jIHuQB21tPeEQq6ahudvfrw1hqCj-wYipSizVTt4cBk" />
    <meta name="google-site-verification" content="HmmMFucZR8tlQV6E1qrbOB2iIkC6yS-2hmBt0Bdezco" />
    <meta name="google-site-verification" content="wnjBailm71m8Ofac-zDY3NNO7pMCwOPO9IedU6X7_04" />

    {block 'product_og'}{/block}

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

    {block 'css_preload'}
        <link rel="stylesheet" href="/static/frontend/dist/css/styles.css?v={frontend_version resource='css/styles.css'}" onload="var url = parseUrl(this.href);
              window.app.assets.css[url.document].loaded = true;
              document.dispatchEvent(new CustomEvent('cssLoad', { 'file': url.document }));">
    {/block}

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
            l.onload = function(){
                var url = parseUrl(this.href);
                document.dispatchEvent(new CustomEvent('cssLoad', { 'file': url.document }));
            };

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

            setTimeout(function() {
                var po = document.createElement('script');
                po.type = 'text/javascript';
                po.src = 'https://seal.godaddy.com/getSeal?sealID=RVzmFJZUlQZwVnp0XQOKJPtEwfIgrDGcXXE9L625dBokxJCuRu6qptcFSHmt';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);

                {if $config.cidev_yandex_code_number}
                (function (d, w, c) {
                    (w[c] = w[c] || []).push(function() {
                        try {
                            w.yaCounter49453150 = new Ya.Metrika2({
                                id:{$config.cidev_yandex_code_number},
                                clickmap:true,
                                trackLinks:true,
                                accurateTrackBounce:true,
                                webvisor:true,
                                trackHash:true
                            });
                        } catch(e) { }
                    });

                    var n = d.getElementsByTagName("script")[0],
                        s = d.createElement("script"),
                        f = function () { n.parentNode.insertBefore(s, n); };
                    s.type = "text/javascript";
                    s.async = true;
                    s.src = "https://mc.yandex.ru/metrika/tag.js";

                    if (w.opera == "[object Opera]") {
                        d.addEventListener("DOMContentLoaded", f, false);
                    } else { f(); }
                })(document, window, "yandex_metrika_callbacks2");
                {/if}

            },6000);
        });

    })();


</script>

{render_flash:raw template='base/_flash.tpl'}

<div id="containerUpDown" class="show-for-large"></div>
</body>
</html>
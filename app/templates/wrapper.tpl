<!doctype html>
<html lang="en" class="no-js {if $.detector->isMobile()} mobile {/if}{if $.detector->isTablet()} tablet {/if}">
<head>
    <meta charset="utf-8">

    {block 'seo'}{meta controller=$this!:null}{/block}

    {set $is_dev_mode = constant('APP_LOCAL') != false}
    {set $site = $.getSite}
    {set $config  = $site->getConfig()}
    {set $gConfig = $site->getGlobalConfig()}
    {set $site_currency = $site->getCurrency()}
    {set $uri = $is_dev_mode ? '' : $site->getHttpOrHttps() ~ $config.CDN_domain}
    {set $translates = $.call.Modules.Translate.Classes.I18nextManager::getTranslates($config[ 'Preferred_language' ])}

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta name="format-detection" content="date=no">
    <meta name="format-detection" content="address=no">
    <meta name="format-detection" content="email=no">

    <link rel="dns-prefetch" href="{$uri}">
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="{$uri}">
    <link rel="preconnect" href="https://fonts.googleapis.com/">


    <link rel="preload" href="{front_script}" as="script">

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
    <meta name="google-site-verification" content="RoyZgd9WiX7UEb7Q2xqEXUC_x8IvZftpxlwlDvLhkAs" />
    <meta name="google-site-verification" content="x0qur602j9-porhk8GqnfPrNtRKgGa7DgQl8nd5K2F8" />
    <meta name="google-site-verification" content="ec02bGHmiUmTOgCTDaKRCVsdOLXLM31YdpVpwkiEwzY" />

    {block 'product_og'}{/block}

    {block 'noindex'}{/block}

    <link rel="shortcut icon" href="{$site->favicons->get()}" type="image/png" />
<style>
    #lex-web-ui-iframe{
        display: none;
    }
</style>
    <script>
      /**
       * dataProvider нужен для централизованного хранения данных, необходимых странице в момент рендеринга
       */
      const dataProvider = {
        data: {  },

        get: function (key) {
            return this.data[key];
        },

        set: function(key, value) {
            return this.data[key] = value;
        },
      };

        window.app = {
            afterReady:[],
            assets: {
                'css': {
                    'styles.css': {
                        'loaded': false
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
                },
                'discount_minutes': {$.call.Modules.User.Helpers.DiscountHelper::getDiscountMinutes()},
                'order': {json_encode($order->attributes)},
                currency: {
                    currency: "{$site_currency->symbol}",
                    symbol_prefix: "{$site_currency->symbol_prefix}",
                    after: "{$site_currency->after}",
                },
                translates: {$translates},
            },
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


    {block 'head'}{/block}

    <!-- Google Tag Manager -->
    <script>
        (function(w,d,s,l,i){ w[l]=w[l]||[];w[l].push({ 'gtm.start': new Date().getTime(),event:'gtm.js' });
            var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;
            j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f); })(window,document,'script','dataLayer','GTM-TCNTJMM');
    </script>
    <!-- End Google Tag Manager -->

    {get_assets:raw type='css' position='head'}
    {get_assets:raw type='js' position='head'}

    {block 'css_preload'}
        {insert '_parts/_css_preload.tpl'}
    {/block}

</head>
<body itemscope itemprop="mainEntity" {block 'schema_page_type'}itemtype="http://schema.org/WebPage"{/block}
      class="loading loading-active"
>
<!-- Google Tag Manager (noscript) -->
<noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TCNTJMM" height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>
<!-- End Google Tag Manager (noscript) -->
{if $is_dev_mode}
    {*save spacing on development*}
    {autoescape true}
        {block 'preloader'}{/block}

        {block "wrapper"}
            {block "content"}{/block}
        {/block}
    {/autoescape}
{else}
    {*remove spacing on production*}
    {filter|strip:true}
        {autoescape true}
            {block 'preloader'}{/block}

            {block "wrapper"}
                {block "content"}{/block}
            {/block}
        {/autoescape}
    {/filter}
{/if}


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

        window.addEventListener("load", function(event) {

            createJsElement("{front_script}");

            setTimeout(function() {

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

                po = document.createElement('script');
                po.type = 'text/javascript';
                po.src = '//assets.pinterest.com/js/pinit.js';
                s = document.getElementsByTagName('script')[0];
                s.parentNode.insertBefore(po, s);
            },5100);
        });

    })();

</script>

{render_flash:raw template='base/_flash.tpl'}

<div id="containerUpDown" class="show-for-large" data-lng_up="{t 'UP'}" data-lng_down="{t 'DOWN'}"></div>

</body>
</html>
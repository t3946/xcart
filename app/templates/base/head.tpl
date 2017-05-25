<!doctype html>
<html lang="en" class="no-js {if $.detector->isMobile()} mobile {/if}{if $.detector->isTablet()} tablet {/if}">
<head itemscope itemtype="http://schema.org/WebSite">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    {*<meta name="viewport" content="width=device-width, initial-scale=1.0" />*}
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-touch-fullscreen" content="yes" />

    {set $site = $.getSite}
    {set $sconfig = $.getSiteConfig}

    {block 'seo'}
        <title itemprop='name'>{$sconfig.company_name.value}</title>
        <link rel="canonical" href="https://{$site.domain}/" itemprop="url">
    {/block}
    {block 'head'}{/block}
    <link rel="stylesheet" href="/static/frontend/dist/css/main.css?v={frontend_css_version}">
    <script src="/static/frontend/dist/js/vendors.js?v={frontend_js_version}" defer></script>
    <script src="/static/frontend/dist/js/main.js?v={frontend_js_version}" defer></script>

    <script type="application/ld+json">
    {
      "@context": "http://schema.org",
      "@type": "WebSite",
      "url": "https://{$site.domain}/",
      "potentialAction": {
        "@type": "SearchAction",
        "target": "https://{$site.domain}/search?q={ignore}{search_term_string}{/ignore}",
        "query-input": "required name=search_term_string"
      }
    }
    </script>
</head>
<body>

{filter|strip:true}
{block "wrapper"}
{/block}
{/filter}


{block 'js'}{/block}
</body>
</html>
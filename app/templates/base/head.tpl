<!doctype html>
<html lang="en" class="no-js {if $.detector->isMobile()} mobile {/if}{if $.detector->isTablet()} tablet {/if}" itemscope itemtype="http://schema.org/WebSite">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    {*<meta name="viewport" content="width=device-width, initial-scale=1.0" />*}
    <meta name="format-detection" content="telephone=no">
    <meta name="format-detection" content="date=no">
    <meta name="format-detection" content="address=no">
    <meta name="format-detection" content="email=no">

    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-touch-fullscreen" content="yes" />

    {set $site = $.getSite}
    {set $sconfig = $.getSiteConfig}

    <meta name="url" itemprop="url" content="https://{$site.domain}/" >
    <meta name="name" itemprop='name' content="{$sconfig.company_name.value}">

    {block 'seo'}
        <title>{$sconfig.company_name.value}</title>
        {*<link rel="canonical" href="https://{$site.domain}/" itemprop="url">*}
    {/block}

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
    <style type="text/css">{inline file="static/frontend/dist/css/base.css"}</style>

    <link rel="stylesheet" href="/static/frontend/dist/css/styles.css?v={frontend_version resource='css/styles.css'}" media="all" onload="if(media!='all')media='all'">
    <noscript><link rel="stylesheet" href="/static/frontend/dist/css/styles.css?v={frontend_version resource='css/styles.css'}"></noscript>
    <script src="/static/frontend/dist/js/vendors.js?v={frontend_version resource='js/vendors.js'}"></script>
    {block 'head'}{/block}
</head>
<body itemscope itemprop="mainEntity" {block 'schema_page_type'}itemtype="http://schema.org/WebPage"{/block} class="loading loading-active">

{filter|strip:true}
{autoescape true}
    <div class="loader-bg waves waves-orange">
        <div class="loader-wrapper">
            <div class="loader-spinner"></div>
            <div class="loader-container"></div>
        </div>
    </div>

{block "wrapper"}{/block}
{/autoescape}
{/filter}


<script src="/static/frontend/dist/js/main.js?v={frontend_version resource="js/main.js"}"></script>

{block 'js'}{/block}

{filter|unescape}
{get_assets type="css"}
{get_assets type="js"}
{get_assets}
{/filter}

</body>
</html>
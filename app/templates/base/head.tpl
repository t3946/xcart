<!doctype html>
<html lang="en" class="no-js">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-touch-fullscreen" content="yes" />
    <title>Title</title>
    {* Title, description, keywords *}
    {block 'seo'}{/block}
    {* Another head information *}
    {block 'head'}{/block}
    <link rel="stylesheet" href="/static/frontend/dist/css/main.css?v={frontend_css_version}">
</head>
<body>

{filter|strip:true}
{block "wrapper"}
{/block}
{/filter}

<script src="/static/frontend/dist/js/main.js?v={frontend_js_version}" defer></script>

{block 'js'}{/block}
</body>
</html>
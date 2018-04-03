<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <link href="/static/frontend/production/thankyoufororder.css" rel="stylesheet">
    {block 'head'}

    {/block}
</head>
<body>
<div class="header_line"></div><div class="header_line_2"></div>
{set $site = $.getSite}
<div class="header">
<img class="store_logo" src="{$site->images[0]}">

<hr class="between">
</div>
<div id="main">
    {block 'main'}
        <div class="main-content">
            {block 'content'}{/block}
            {block 'after-content'}{/block}
        </div>
    {/block}
</div>
<footer>
    <img class="footer_line" src="/static/frontend/production/line.png">
    <a href="www.s3stores.com">
        <img class="s3_logo" src="/static/frontend/production/logo_S3Stores.svg">
    </a>
</footer>
<div class="footer_line"></div><div class="footer_line_2"></div>

</body>
</html>
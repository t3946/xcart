<!doctype html>
<html>
<head>
    <meta charset="utf-8">

    {block 'head'}

    {/block}
</head>
<body>
<header>
    {set $site = $.getSite}
    <img src="{$site->images[0]}">
</header>
<div id="main">
    {block 'main'}
        <div class="main-content">
            {block 'content'}{/block}
            {block 'after-content'}{/block}
        </div>
    {/block}
</div>
<footer>
    <a href="www.s3stores.com">
        <img class="s3_logo" src="/static/frontend/production/logo_S3Stores.svg">
    </a>
</footer>
</body>
</html>
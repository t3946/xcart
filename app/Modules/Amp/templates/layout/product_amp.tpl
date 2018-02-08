<!doctype html>
<html ⚡ lang="en">
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
        {set $site = $.getSite}
        <link rel="icon" href="{$site->favicons->limit(1)->get()->getCdnURL()}" type="image/x-icon">
        {block 'seo'}{meta controller=$controller!:null canonical=$model->getAbsoluteUrl(true)}{/block}

{block 'head'}

{/block}
</head>
<body>
<div id="main">
    {block 'main'}
        <div class="main-content">
            {block 'content'}{/block}
            {block 'after-content'}{/block}
        </div>
    {/block}
</div>

</body>
</html>

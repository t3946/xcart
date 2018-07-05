<!doctype html>
<html ⚡ lang="en">
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
        {set $site = $.getSite}
        {set $favicon = $site->favicons->limit(1)->get()}
        {if $favicon}
        <link rel="icon" href="{$favicon}" type="image/x-icon">
        {/if}
        {block 'seo'}
                <title>{if $model->title_tag} {$model->title_tag} {else} {$model->getFrontendName()} {/if}</title>
        {/block}

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

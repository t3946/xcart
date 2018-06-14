{extends  $.request->getIsAjax() ? "ajax.tpl" : "cart/base.tpl"}
{block 'noindex'}<meta name="robots" content="noindex">{/block}
{block 'css_preload'}
    <link rel="stylesheet" href="/static/frontend/dist/css/styles.css?v={frontend_version resource='css/styles.css'}">
{/block}

{block 'after-content'}{/block}
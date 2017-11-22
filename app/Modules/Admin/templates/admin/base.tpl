{if $.request->get->has('popup')}
    {set $layout = 'admin/layout.tpl'}
{else}
    {set $layout = 'base/admin_layout.tpl'}
{/if}

{extends $layout}
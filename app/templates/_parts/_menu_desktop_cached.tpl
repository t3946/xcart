{if constant('APP_LOCAL')}
    {cache key = '_parts/_menu_desktop.tpl'}
    {insert "_parts/_menu_desktop.tpl"}
    {/cache}
{else}
    {insert "_parts/_menu_desktop.tpl"}
{/if}
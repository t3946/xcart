{*
$Id: pages.tpl 63 2012-10-30 11:56:13Z skot $
vim: set ts=2 sw=2 sts=2 et:
*}
{if $page_content ne ''}
  {if $config.General.parse_smarty_tags eq "Y"}
    {eval var=$page_content}
  {else}
    {$page_content|amp}
  {/if}
{/if}

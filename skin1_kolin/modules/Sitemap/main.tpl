{*
$Id$
vim: set ts=2 sw=2 sts=2 et:
*}
<link rel="stylesheet" href="{$SkinDir}/modules/Sitemap/main.css" />

{capture name=dialog}
  <div id="Sitemap">
    {foreach from=$config.Sitemap.items item="item"}
      {if $sitemap_items.$item ne false}
        {include file="modules/Sitemap/item_`$item`.tpl" section_name=$section_name items=$sitemap_items.$item}
      {/if}
    {foreachelse}
      {$lng.sitemap_noitems}
    {/foreach}
  </div>
{/capture}
{include file="dialog.tpl" title=$lng.sitemap_location content=$smarty.capture.dialog extra='width="100%"'}

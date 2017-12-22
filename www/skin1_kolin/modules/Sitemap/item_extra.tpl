{*
$Id$
vim: set ts=2 sw=2 sts=2 et:
*}
<ul id="Sitemap_Extra" class="sitemap_section">
  <li><h2>{$lng.sitemap_item_extra}</h2>
    <ul class="sitemap_item">
      {foreach from=$items item="item" key="num"}
	    <li><a class="sitemap_url" href="{$item.url}" title="{$item.name}">{$item.name}</a></li>
      {/foreach}
    </ul>
  </li>
</ul>
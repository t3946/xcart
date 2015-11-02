{*
$Id$
vim: set ts=2 sw=2 sts=2 et:
*}
<ul id="Sitemap_Manufacturers" class="sitemap_section">
  <li><h2>{$lng.sitemap_item_manufacturers}</h2>
    <ul class="sitemap_item">
      {foreach from=$items item="item" key="num"}
	    <li><a href="{$item.url}" title="{$item.name}">{$item.name}</a></li>
      {/foreach}
    </ul>
  </li>
</ul>
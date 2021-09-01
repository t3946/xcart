{*
$Id$
vim: set ts=2 sw=2 sts=2 et:
*}
<ul id="Sitemap_Categories" class="sitemap_section">
  <li><h2>{$lng.sitemap_item_categories}</h2>
    <ul class="sitemap_categories_sub">
      {foreach from=$items item="item" key="num"}
	    {include file="modules/Sitemap/item_categories_recurs.tpl" item=$item}
      {/foreach}
    </ul>
  </li>
</ul>
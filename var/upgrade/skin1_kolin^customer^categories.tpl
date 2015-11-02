{* $Id: categories.tpl,v 1.26 2005/11/17 06:55:37 max Exp $ *}
{capture name=menu}
{if $active_modules.Fancy_Categories ne ""}
{include file="modules/Fancy_Categories/categories.tpl" cat_start=0 cat_end=500}
{assign var="fc_cellpadding" value="0"}
{else}
{if $config.General.root_categories eq "Y"}
{foreach from=$categories item=c}
{if $c.order_by ge 0 && $c.order_by le 500}
<font class="CategoriesList"><a href="home.php?cat={$c.categoryid}" class="VertMenuItems">{if $c.is_bold eq "Y"}<b>{$c.category}</b>{else}{$c.category}{/if}</a></font><br />
{/if}
{/foreach}
{else} {foreach from=$subcategories item=c key=catid}
{if $c.order_by ge 0 && $c.order_by le 500}
<font class="CategoriesList"><a href="home.php?cat={$catid}" class="VertMenuItems">{if $c.is_bold eq "Y"}<b>{$c.category}</b>{else}{$c.category}{/if}</a></font><br />
{/if}
{/foreach}
{/if}
{/if}
{/capture}
{ include file="menu.tpl" menu_title=$lng.lbl_category_title menu_content=$smarty.capture.menu cellpadding=$fc_cellpadding}

{capture name=menu}
{if $active_modules.Fancy_Categories ne ""}
{include file="modules/Fancy_Categories/categories.tpl" cat_start=501 cat_end=50000}
{assign var="fc_cellpadding" value="0"}
{else}
{if $config.General.root_categories eq "Y"}
{foreach from=$categories item=c}
{if $c.order_by gt 500}
<font class="CategoriesList"><a href="home.php?cat={$c.categoryid}" class="VertMenuItems">{if $c.is_bold eq "Y"}<b>{$c.category}</b>{else}{$c.category}{/if}</a></font><br />
{/if}
{/foreach}
{else} {foreach from=$subcategories item=c key=catid}
{if $c.order_by gt 500}
<font class="CategoriesList"><a href="home.php?cat={$catid}" class="VertMenuItems">{$c.category}</a></font><br />
{/if}
{/foreach}
{/if}
{/if}
{/capture}
<br />
{ include file="menu.tpl" menu_title=$lng.lbl_information menu_content=$smarty.capture.menu cellpadding=$fc_cellpadding}

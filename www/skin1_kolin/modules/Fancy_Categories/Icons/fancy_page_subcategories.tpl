{* $Id: fancy_subcategories.tpl,v 1.2.2.4 2006/08/31 12:29:07 max Exp $ *}
{if $config.Fancy_Categories.fancy_js eq 'Y'}

{if $parentid ne ''}
<div id="{$fancy_cat_prefix}{$parentid}sub" class="CatSubMenu" style="position: absolute; visibility: hidden;">
<script type="text/javascript">
<!--
	page_categories[{$parentid}] = [];
-->
</script>
{/if}
<table cellspacing="0" cellpadding="0" class="CatMenuContainer">
{foreach from=$all_page_categories item=c key=catid}
{if $level > 0 || $c.parentid eq 0}
<tr>
	<td>

<div id="{$fancy_cat_prefix}{$catid}" onmouseover="javascript: fcShowMenu(this, {$catid});" onclick="javascript: self.location='home.php?cat={$catid}';" style="position: relative;" class="CatMenuItemOff"{if $config.Appearance.count_products eq "Y"} title="{$lng.lbl_products|escape}: {$c.product_count|default:0}{if $c.subcategory_count gt 0} {$lng.lbl_subcategories|escape}: {$c.subcategory_count}{/if}"{/if}>
<table cellspacing="0" cellpadding="0" class="FCItemTable" width="100%">
<tr>
{if $config.Fancy_Categories.icons_icons_in_categories >= $level+1}
	<td class="FCIconCell"><img src="{if $c.is_icon ne 'Y'}{$ImagesDir}/spacer.gif{elseif $c.icon_url ne ''}{$c.icon_url}{else}{$xcart_web_dir}/image.php?type=C&amp;id={$catid}{/if}" class="FCIcon" alt="" /></td>
{/if}
	<td width="100%" align="left"><a href="home.php?cat={$catid}" class="CatMenuInfoItem">{$c.category}</a></td>
{if $config.Fancy_Categories.icons_disable_subcat_triangle ne 'Y'}
	<td class="FCTriangleCell"><img src="{if $c.subcategory_count eq 0}{$ImagesDir}/spacer.gif{else}{$fc_skin_web_path}/tree_subdir_plus.gif{/if}" class="FCTriangle" alt="" /></td>
{/if}
</tr>
</table>
{if $level eq 0}
<script type="text/javascript">
<!--
	fcInitRootMenu({$catid}, {if $c.subcategory_count eq 0}false{else}true{/if});
-->
</script>
{elseif $parentid ne ''}
<script type="text/javascript">
<!--
	page_categories[{$parentid}][{$catid}] = {if $c.subcategory_count eq 0}false{else}true{/if};
-->
</script>
{/if}
</div>

	</td>
</tr>
{/if}
{/foreach}
</table>
{if $parentid ne ''}
</div>
{/if}

{foreach from=$page_categories item=c key=catid}
{if $config.Fancy_Categories.fancy_download ne 'Y' && $c.childs && $c.subcategory_count > 0}
{include file="`$fc_skin_path`/fancy_page_subcategories.tpl" categories=$c.childs parentid=$catid level=$level+1}
{/if}
{/foreach}

{else}

{foreach from=$page_categories item=c key=catid}
<div style="width: 100%;" class="CatMenuItemOff" onclick="javascript: self.location='home.php?cat={$catid}&amp;catexp={$catid}';"{if $config.Appearance.count_products eq "Y"} title="{$lng.lbl_products|escape}: {$c.product_count|default:0}{if $c.subcategory_count gt 0} {$lng.lbl_subcategories|escape}: {$c.subcategory_count}{/if}"{/if}>
<table cellspacing="0" cellpadding="0" class="FCItemTable" width="100%">
<tr>
{if $level > 0 && $parentid ne ''}
{math assign="indent" equation="x*12" x=$level}
	<td width="{$indent}"><img src="{$ImagesDir}/spacer.gif" width="{$indent}" height="1" alt="" /></td>
{/if}
{if $config.Fancy_Categories.icons_icons_in_categories >= $level+1}
    <td class="FCIconCell"><a href="home.php?cat={$catid}&amp;catexp={$catid}" class="CatMenuInfoItem"><img src="{if $c.is_icon ne 'Y'}{$ImagesDir}/spacer.gif{elseif $c.icon_url ne ''}{$c.icon_url}{else}{$xcart_web_dir}/image.php?type=C&amp;id={$catid}{/if}" class="FCIcon" alt="" /></a></td>
{/if}
    <td width="100%" align="left"><a href="home.php?cat={$catid}&amp;catexp={$catid}" id="nojs" class="CatMenuInfoItem" style="display: block; width: 100%;">{$c.category}</a></td>
{if $config.Fancy_Categories.icons_disable_subcat_triangle ne 'Y'}
    <td class="FCTriangleCell"><a href="home.php?cat={$cat}&amp;catexp={$catid}" class="CatMenuInfoItem"><img src="{if $c.subcategory_count eq 0}{$ImagesDir}/spacer.gif{elseif $c.expanded}{$fc_skin_web_path}/tree_subdir_minus.gif{else}{$fc_skin_web_path}/tree_subdir_plus.gif{/if}" class="FCTriangle" alt="" /></a></td>
{/if}
</tr>
</table>
</div>
{if $c.expanded && $c.subcategory_count ne 0}
{include file="`$fc_skin_path`/fancy_page_subcategories.tpl" page_categories=$c.childs parentid=$catid level=$level+1}
{/if}
{/foreach}

{/if}

{* $Id: fancy_subcategories.tpl,v 1.2.2.4 2006/08/31 12:29:07 max Exp $ *}
{if $config.Fancy_Categories.fancy_js eq 'Y'}

{if $parentid ne ''}
<div id="{$fancy_cat_prefix}{$parentid}sub" class="CatSubMenu" style="position: absolute; visibility: hidden;">
<script type="text/javascript">
<!--
	categories[{$parentid}] = [];
-->
</script>
{/if}
<table cellspacing="0" cellpadding="0" class="CatMenuContainer">
{foreach from=$categories item=c key=catid}
{if $level > 0 || $c.parentid eq 0}
<tr>
	<td>

<div id="{$fancy_cat_prefix}{$catid}" onmouseover="javascript: fcShowMenu(this, {$catid});" onclick="javascript: self.location='home.php?cat={$catid}'" style="position: relative;" class="CatMenuItemOff"{if $config.Appearance.count_products eq "Y"} title="{$lng.lbl_products|escape}: {$c.product_count|default:0}{if $c.subcategory_count gt 0} {$lng.lbl_subcategories|escape}: {$c.subcategory_count}{/if}"{/if}>
<table cellspacing="0" cellpadding="0" class="FCItemTable" width="100%">
<tr>
	<td><img id="{$fancy_cat_prefix}{$catid}l" src="{$fc_skin_web_path}/{$config.Fancy_Categories.candy_img_skin}/button_l_off.gif" alt="" /></td>
	<td id="{$fancy_cat_prefix}{$catid}m" width="100%" align="left" nowrap="nowrap" style="background-image: url({$fc_skin_web_path}/{$config.Fancy_Categories.candy_img_skin}/button_m_off.gif);"><a href="home.php?cat={$catid}" class="CatMenuItem">{if $config.Fancy_Categories.candy_truncate > 0}{$c.category|truncate:$config.Fancy_Categories.candy_truncate}{else}{$c.category}{/if}</a></td>
	<td><img id="{$fancy_cat_prefix}{$catid}r" src="{$fc_skin_web_path}/{$config.Fancy_Categories.candy_img_skin}/button_r_off.gif" alt="" /></td>
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
	categories[{$parentid}][{$catid}] = {if $c.subcategory_count eq 0}false{else}true{/if};
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

{foreach from=$categories item=c key=catid}
{if $config.Fancy_Categories.fancy_download ne 'Y' && $c.childs && $c.subcategory_count > 0}
{include file="`$fc_skin_path`/fancy_subcategories.tpl" categories=$c.childs parentid=$catid level=$level+1}
{/if}
{/foreach}

{else}

{foreach from=$categories item=c key=catid}
<div id="{$fancy_cat_prefix}{$catid}" style="width: 100%;" class="CatMenuItemOff" onclick="javascript: self.location='home.php?cat={$catid}&amp;catexp={$catid}';"{if $config.Appearance.count_products eq "Y"} title="{$lng.lbl_products|escape}: {$c.product_count|default:0}{if $c.subcategory_count gt 0} {$lng.lbl_subcategories|escape}: {$c.subcategory_count}{/if}"{/if}>
<table cellspacing="0" cellpadding="0" class="FCItemTable" width="100%">
<tr>
{if $level > 0 && $parentid ne ''}
{math assign="indent" equation="x*12" x=$level}
	<td width="{$indent}"><img src="{$ImagesDir}/spacer.gif" width="{$indent}" height="1" alt="" /></td>
{/if}
	<td><img src="{$fc_skin_web_path}/{$config.Fancy_Categories.candy_img_skin}/button_l_off.gif" alt="" /></td>
	<td width="100%" align="left" nowrap="nowrap" style="background-image: url({$fc_skin_web_path}/{$config.Fancy_Categories.candy_img_skin}/button_m_off.gif);"><a href="home.php?cat={$catid}&amp;catexp={$catid}" class="CatMenuItem">{if $config.Fancy_Categories.candy_truncate > 0}{$c.category|truncate:$config.Fancy_Categories.candy_truncate}{else}{$c.category}{/if}</a></td>
	<td><img src="{$fc_skin_web_path}/{$config.Fancy_Categories.candy_img_skin}/button_r_off.gif" alt="" /></td>
</tr>
</table>
</div>
{if $c.expanded && $c.subcategory_count ne 0}
{include file="`$fc_skin_path`/fancy_subcategories.tpl" categories=$c.childs parentid=$catid level=$level+1}
{/if}
{/foreach}

{/if}

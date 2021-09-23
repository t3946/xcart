{* $Id: fancy_subcategories.tpl,v 1.21.2.3 2006/11/14 07:05:21 max Exp $ *}
{if $parent && $config.Fancy_Categories.fancy_js eq "Y"}
<div id="{$fancy_cat_prefix}{$parent.categoryid}" style="display: {if $parent.expanded}show{else}none{/if};">
{/if}
{foreach from=$categories item=c key=catid}
<table cellpadding="0" cellspacing="0">
<tr>
{foreach from=$c.columns item=lchain}
	<td class="FCChain" style="background-image: url({$fc_img_skin}/{if $lchain}tree_subdir_line.gif{else}tree_subdir_blank.gif{/if});"><img src="{$ImagesDir}/spacer.gif" alt="" /></td>
{/foreach}
	<td class="FCExplorerBox" colspan="2"{if $config.Fancy_Categories.fancy_js eq "Y"} id="{$fancy_cat_prefix}{$catid}imgbg"{/if} style="background-image: url({$fc_img_skin}/tree_subdir_line{if $c.last}_tail{/if}{if $c.expanded && $c.subcategory_count > 0}_exp{/if}.gif);">
	<table cellpadding="0" cellspacing="0">{strip}
	<tr>
		<td width="9" style="padding-right: 1px;">
{if $config.Fancy_Categories.fancy_js eq "Y"}
{if $c.subcategory_count > 0}
<a href="javascript: void(0);" class="VertMenuItems" onclick="javascript: SwitchTreeItem({$catid}, {if $c.last}true{else}false{/if});" onfocus="javascript: this.blur();">
{/if}
{else}
<a href="home.php?{if $cat ne ''}cat={$cat}&{/if}catexp={$catid}" class="VertMenuItems">
{/if}
<img src="{$fc_img_skin}/tree_subdir_
{if $c.subcategory_count eq 0}
{if $c.last}line_end{else}empty{/if}
{else}
{if $c.expanded}minus{else}plus{/if}
{/if}
.gif"
{if $config.Fancy_Categories.fancy_js eq "Y"} id="{$fancy_cat_prefix}{$catid}img"{/if}
 alt="" />
{if $config.Fancy_Categories.fancy_js ne "Y" || $c.subcategory_count > 0}</a>{/if}
		</td>
		<td width="27" valign="top">
<a href="home.php?cat={$catid}" class="VertMenuItems"
{if $config.Fancy_Categories.explorer_count_products eq "Y"}
 title="{$lng.lbl_products|escape}: {$c.product_count|default:0}{if $c.subcategory_count gt 0} {$lng.lbl_subcategories|escape}: {$c.subcategory_count}{/if}"
{/if}
>
<img src="{$fc_img_skin}/tree_item
{if $c.product_count ne 0 && $config.Fancy_Categories.explorer_diff_categories eq "Y"}_data{/if}
{if $catexp eq $catid}_current{/if}
.gif" alt="" /></a>
		</td>
	</tr>
	{/strip}</table>
	</td>
	<td{if $config.Fancy_Categories.explorer_nowrap_categories eq "Y"} nowrap="nowrap"{/if} class="CategoriesList">{strip}
	<a href="home.php?cat={$catid}" class="VertMenuItems"
{if $config.Fancy_Categories.explorer_count_products eq "Y"}
 title="{$lng.lbl_products|escape}: {$c.product_count|default:0}
{if $c.subcategory_count gt "0"} {$lng.lbl_subcategories|escape}: {$c.subcategory_count}{/if}"
{/if}
{if $config.Fancy_Categories.explorer_nowrap_categories eq "Y"} style="white-space: nowrap;"{/if}
>
{$c.category|escape}
</a>
	{/strip}</td>
</tr>
</table>
{if ($c.expanded || $config.Fancy_Categories.fancy_js eq "Y") && $c.subcategory_count > 0}
{include file="`$fc_skin_path`/fancy_subcategories.tpl" parent=$c categories=$c.childs}
{/if}
{/foreach}
{if $parent && $config.Fancy_Categories.fancy_js eq "Y"}
</div>
{/if}

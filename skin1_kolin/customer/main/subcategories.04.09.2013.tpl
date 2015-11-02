{* $Id: subcategories.tpl,v 1.55.2.1 2006/06/27 08:20:37 svowl Exp $ *}
{if $active_modules.Bestsellers ne "" and $config.Bestsellers.bestsellers_menu ne "Y"}
<p />
{include file="modules/Bestsellers/bestsellers.tpl"}
{/if}
<p />
{if $active_modules.Special_Offers}
{include file="modules/Special_Offers/customer/category_offers_short_list.tpl"}
{/if}
{if $keyphrase eq ''}
    {assign var="capture_title" value=$current_category.category}
{elseif $current_seed_category neq ''}
    {assign var="capture_title" value=$current_seed_category}
{else}
    {assign var="capture_title" value=$keyphrase}
{/if}
{capture name=dialog}
{if ($navigation_page eq "")||($navigation_page eq "1")}<span class="SPItems-description">{$current_category.description}</span><p />{/if}
{assign var="tmp" value="0"}
{foreach from=$subcategories item=c key=catid}
{if $c.category}{assign var="tmp" value="1"}{/if}
{/foreach}
{if $subcategories}
{if $current_category.main_order_by gt 500}
<br />
{/if}
{if $current_category.main_order_by gt 500}
<table cellspacing="0" width="100%">
<tr>
<td class="DialogTitle" colspan="3" style="PADDING-LEFT: 0px">Further information:<br /><br /></td>
</tr>
</table>
{/if}
<table cellspacing="5" width="100%">
{math assign="hcol" equation="y+x" y=$qsubcats x=1}
{foreach from=$subcategories item=subcat}
<tr>
{if $tmp and $first_subcat ne "Y" and $current_category.main_order_by le 500}
{*	<td valign="top" rowspan="{$hcol}"><img src="{if $current_category.icon_url}{$current_category.icon_url}{else}{$xcart_web_dir}/image.php?id={$cat}&amp;type=C{/if}" alt="" /></td> *}
	<td valign="top" rowspan="{$hcol}">{if $current_category.icon_url}<img src="{$current_category.icon_url}" alt="" />{/if}</td>
{assign var="first_subcat" value="Y"}
{/if}
	<td class="SubcatTitle"{if $current_category.main_order_by gt 500} style="padding-left: 5px"{/if}><a href="home.php?cat={ $subcat.categoryid }"> <font class="{if ($subcat.parentid eq $cat && $subcat.is_bold eq 'Y') || ($subcat.parentid ne $cat && $subcat.add_is_bold eq 'Y')}ItemsList{else}ItemsList1{/if}"{if $current_category.main_order_by gt 500} face="Verdana"{/if}>{ $subcat.category|escape }</font></a><br /></td>
	<td class="SubcatInfo">{if $config.Appearance.count_products le "Y" &&  $current_category.main_order_by le 500}
	{if $subcat.product_count_global || $subcat.subcategory_count}
		{if $subcat.product_count_global}{ $subcat.product_count_global }&nbsp;{$lng.lbl_products}&nbsp;{$lng.lbl_in}{else}{$lng.lbl_subcat_no_products}&nbsp;{$lng.lbl_in}{/if}&nbsp;{if $subcat.subcategory_count}{ $subcat.subcategory_count }&nbsp;{$lng.lbl_categories|lower}{else}{$lng.lbl_this_category}{/if}
	{else}
		{$lng.lbl_empty_category}
	{/if}
{/if}</td>
</tr>
{/foreach}
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
</table>
{/if}
{if $tmp and $products ne "" and $current_category.main_order_by le 500}
<br clear="left" />
<hr size="1" noshade="noshade" />
{/if}
{if $products}
{if $sort_fields}
<div align="right">{include file="main/search_sort_by.tpl" sort_fields=$sort_fields selected=$search_prefilled.sort_field direction=$search_prefilled.sort_direction url="home.php?cat=`$cat`&amp;path=alt&amp;page=`$navigation_page`&amp;"}</div>
{/if}
{if $total_pages gt 2}
{if $current_category.main_order_by le 500}
<hr size="1" width="100%" />
{*
{ include file="customer/main/navigation.tpl" }
*}
<br />

{/if}
{else}
{if $current_category.main_order_by le 500}
<hr size="1" width="100%" />
{/if}
{/if}
{if $current_category.main_order_by le 500}
{include file="customer/main/products.tpl" products=$products}
{/if}
{/if}
{if $products eq "" and $tmp eq "0"}
{if $current_category.main_order_by le 500}
{$lng.txt_no_products_in_cat}
{/if}
{/if}
{/capture}
{include file="dialog.tpl" title=$capture_title content=$smarty.capture.dialog extra='width="100%"' use_h1="Y"}
{if $products eq ""}
{if $f_products ne ""}
{if $current_category.main_order_by le 500}
<p />
{include file="customer/main/featured.tpl"}
{/if}
{/if}
{/if}
{ include file="customer/main/navigation.tpl" }

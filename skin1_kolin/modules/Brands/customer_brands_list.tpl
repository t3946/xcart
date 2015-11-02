{* customer_brands_list.tpl, random *}
{capture name=dialog}

{if $cidev_brands_menu ne ""}

{assign var="first_letter" value=""}

{assign var="tmp_count_letter" value="0"}

{assign var=cell_counter value=1}


<table cellspacing="0" cellpadding="0" width="100%" style="margin-left: 13px;">
<tr>
<td style="vertical-align: top;">
{* <span class="ProductPrice">{$lng.lbl_brands}:</span><br /> *}

{section name=mid loop=$cidev_brands_menu}
{assign var=cell_counter value=$cell_counter+1}

{if $first_letter ne $cidev_brands_menu[mid].first_letter}
<a name="{$cidev_brands_menu[mid].first_letter}"></a>
{if $first_letter ne ""}<br />{/if}
<font style="font-size: 16px; font-weight: bold;">{$cidev_brands_menu[mid].first_letter}</font>
<br />
{assign var="first_letter" value=$cidev_brands_menu[mid].first_letter}
{/if}

{assign var=tmp_count_letter value=$tmp_count_letter+1}

{* {$tmp_count_letter} *}

<a href="/brands.php?brandid={$cidev_brands_menu[mid].brandid}" class="NavigationPath">{$cidev_brands_menu[mid].brand}</a>
{if $cell_counter gt $count_in_row}

{assign var="tmp_count_letter" value="0"}

{assign var=cell_counter value=0}
</td><td style="vertical-align: top;">
{else}
<br />
{/if}
{/section}
</td>
</tr>
</table>


{/if}

{*
{ include file="customer/main/navigation.tpl" }
<br />
<table cellspacing="5">
{foreach from=$brands item=v}
<tr>
	<td class="BrandsItem"><a href="brands.php?brandid={$v.brandid}"><font class="ItemsList">{$v.brand|escape}</font></a></td>
</tr>
{/foreach}
</table>
<br /><br />
{ include file="customer/main/navigation.tpl" }
*}

{/capture}
<p />
{include file="dialog.tpl" title=$lng.lbl_brands content=$smarty.capture.dialog extra='width="100%"'}

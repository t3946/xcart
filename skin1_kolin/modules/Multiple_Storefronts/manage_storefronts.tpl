{* $Id: multiple_storefronts.tpl,v 1.0 2010/11/26 13:15:34 kate Exp $ *}
{include file="page_title.tpl" title=$lng.lbl_storefronts_management}

{$lng.txt_storefronts_top_text}

<br /><br />

{capture name=dialog}

<table cellpadding="0" cellspacing="0" width="100%">
<tr>
    <td colspan="2">{$lng.lbl_sf_licenses_purchased|cat:":"}&nbsp;{$MAX_STOREFRONTS}</td>
</tr>
<tr>
    <td class="sf-active-licenses">{$lng.lbl_active_sf_licenses|cat:":"}&nbsp;{$active_licenses}</td>
</tr>
<tr>
    <td class="sf-avail-licenses">{$lng.lbl_avail_sf_licenses|cat:":"}&nbsp;{$avail_licenses}</td>
</tr>
</table>

<br />

<form method="post" action="multiple_storefronts.php" name="modifystorefrontsform">
<input type="hidden" name="mode" value="modify" />

{include file="main/check_all_row.tpl" style="line-height: 170%;" form="modifystorefrontsform" prefix="delete"}

<table cellpadding="2" cellspacing="2" width="100%">

<tr class="TableHead">
	<td width="1%">&nbsp;</td>
	<td width="5%">{$lng.lbl_pos}</td>
	<td>{$lng.lbl_prefix}</td>
	<td>{$lng.lbl_storefront_name}</td>
	{foreach from=$storefronts item=storefront}
	<td>{$storefront.config.opt_order_prefix}</td>
	{/foreach}
</tr>

{if $storefronts}
	{foreach from=$storefronts item=storefront}
	<tr{cycle values=", class='TableSubHead'"}>
		<td>{if $storefront.storefrontid ne '0'}<input type="checkbox" name="delete[{$storefront.storefrontid}]" value="Y" />{else}&nbsp;{/if}</td>
		<td><input type="text" size="4" name="update[{$storefront.storefrontid}][orderby]" value="{$storefront.orderby}" /></td>
		<td align="center"><a href="configuration.php?option=Multiple_Storefronts&amp;sf={$storefront.storefrontid}">{$storefront.config.opt_order_prefix}</a></td>
		<td><a href="http://{$storefront.domain}" target="_blank" title="">{$storefront.config.company_name|escape}</a></td>
		{foreach from=$storefronts item=v}
		<td>{if $v.storefrontid ne $storefront.storefrontid}<input type="checkbox" value="{$v.storefrontid}" name="sf_links[{$storefront.storefrontid}][{$v.storefrontid}]"{if $storefront.links[$v.storefrontid] eq 'Y'} checked="checked"{/if} />{else}&nbsp;{/if}</td>
		{/foreach}
	</tr>
	{/foreach}
	<tr>
		<td colspan="5">
            <br />
			<input type="submit" value="{$lng.lbl_update}" />&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="button" value="{$lng.lbl_delete}" onclick="javascript: if (checkMarks(this.form, new RegExp('delete\[[0-9]+\]', 'gi'))) submitForm(document.modifystorefrontsform, 'delete');" />
		</td>
	</tr>
{else}
<tr>
	<td colspan="5" class="mf_no_items">{$lng.lbl_mf_no_storefronts}</td>
</tr>
{/if}

</table>

</form>

{/capture}
{include file="dialog.tpl" title=$lng.lbl_modify_storefronts content=$smarty.capture.dialog extra='width="100%"'}

<br /><br />

{capture name=dialog}

{include file="main/include_js.tpl" src="main/popup_image_selection.js"}
<form method="post" action="multiple_storefronts.php" name="addstorefrontsform" enctype="multipart/form-data"{if $max_storefronts eq 'Y'} onsubmit="javascript: alert('{$lng.lbl_mf_max_domain_quantity_limit}'); return false;"{/if}>
<input type="hidden" name="mode" value="add" />

<table cellpadding="7" cellspacing="7" width="100%">
<tr>
    <td>{$lng.lbl_mf_new_main_domain|cat:":"}</td>
    <td><input type="text" name="new_main_domain" value="" size="30" /></td>
</tr>
<tr>
    <td colspan="2" class="mf_explanation">{$lng.lbl_mf_new_main_domain_txt}</td>
</tr>
<tr>
    <td>{$lng.lbl_mf_top_banner_image|cat:":"}</td>
    <td>
        <input type="button" value="{$lng.lbl_browse_|strip_tags:false|escape}" onclick="javascript: popup_image_selection('S', '', '');" />&nbsp;<span id="upload_fname"></span>
    </td>
</tr>
<tr>
    <td class="sf-avail-licenses" colspan="2">
        <input type="submit" value="{$lng.lbl_mf_create_storefront}" />&nbsp;&nbsp;&nbsp;{$lng.lbl_avail_sf_licenses|cat:":"}&nbsp;{$avail_licenses}
    </td>
</tr>
</table>
</form>

{/capture}
{include file="dialog.tpl" title=$lng.lbl_create_new_storefronts content=$smarty.capture.dialog extra='width="100%"'}

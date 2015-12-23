{* $Id: head_admin.tpl,v 1.10 2006/03/17 08:50:44 svowl Exp $ *}
<table cellpadding="0" cellspacing="0" width="100%">
<tr> 
	<td class="HeadLogo_admin" width="*">
<a href="{$http_location}/{if $usertype eq "P"}provider{else}admin{/if}/">
{if $current_storefront_info.storefrontid gte 0}
<img src="{$xcart_web_dir}/image.php?id={$current_storefront_info.storefrontid}&amp;type=S" alt="" />
{else}
<img src="{$ImagesDir}/admin_xlogo.gif" width="244" height="67" alt="" />
{/if}
</a></td>
{if $login ne ""}

	<td align="left" width="34%">
		<a style="padding-left: 35px;" href="{$catalogs.admin}/orders.php?page_name=dashboard"><img src="{$ImagesDir}/cc_dashbord.png" alt="" /></a>
	</td>

	<td align="right" width="33%">
		{include file="authbox_top.tpl"}
	{if $usertype eq "A"}
<br />
<font style="font-size: 10px; font-weight: bold; color: #000000;">Highly Customized X-cart Pro 4.1.6</font>
	{/if}
	</td>
	<td width="10"><img src="{$ImagesDir}/spacer.gif" width="10" height="1" alt="" /></td>
{/if}
</tr>
</table>
<table cellpadding="0" cellspacing="0" width="100%" border="0">
<tr> 
	<td colspan="4" class="HeadThinLine"><img src="{$ImagesDir}/spacer.gif" class="Spc" alt="" /></td>
</tr>


<tr> 
<td class="HeadLine" height="22" width="33%">
		{include file="main/search.tpl"}
</td>


<td width="34%" align="center" class="HeadLine">
{if $usertype eq 'A' && $login}
                <form method="post" action="orders.php" name="productsearchform">
                <input type="hidden" name="fast_search" value="Y" />
                <input type="hidden" name="mode" value="" />

<script type="text/javascript">
//<![CDATA[
{literal}

$(document).ready(function() {
        $('#select_searchstring_by').change(function() {
                var select_searchstring_by = $('#select_searchstring_by').val();
                        $('#searchstring').attr("name", "posted_data["+select_searchstring_by+"]");
        });
});

{/literal}
//]]>
</script>

                                <table cellpadding="0" cellspacing="0">
                                <tr>
                                        <td>
                                                <select name="select_searchstring_by" id ="select_searchstring_by">
                                                        <option value="orderid">Order # / Amazon order ID</option>
                                                        <option value="po_number">PO #</option>
                                                        <option value="s_zipcode">Zip code</option>
                                                </select>
                                        </td>
                                        <td>
                                                <input type="text" id="searchstring" name="posted_data[orderid]" size="18" value="" />
                                        </td>
                                        <td>
                                                <input type="submit" value="{$lng.lbl_search}" />
                                        </td>
                                </tr>
                                </table>
                </form>
{/if}
</td>


{*
<td class="HeadLine" align="right" height="22">
{if ($usertype eq "P" or $usertype eq "A") and $login and $all_languages_cnt gt 1}
<form action="{$smarty.server.REQUEST_URI|amp}" method="post" name="asl_form">
<table cellpadding="0" cellspacing="0">
<tr>
	<td><b>{$lng.lbl_current_language}:</b>&nbsp;</td>
	<td>
<input type="hidden" name="redirect" value="{$smarty.server.QUERY_STRING|amp}" />
<select name="asl" onchange="javascript: document.asl_form.submit()">
{section name=ai loop=$all_languages}
<option value="{$all_languages[ai].code}"{if $current_language eq $all_languages[ai].code} selected="selected"{/if}>{$all_languages[ai].language}</option>
{/section}
</select>
	</td>
</tr>
</table>
</form>
{else}
&nbsp;
{/if}
</td>
*}


<td class="HeadLine" align="right" height="22" width="33%">

    {if $active_modules.Multiple_Storefronts && $usertype eq "A" && $login && $current_membership_flag ne 'FS'}

        {if !($membership_code eq "ADMIN_CUSTOMER_SERVICE" || $membership_code eq "ADMIN_PRODUCT_MANAGER")}
<div style="float: right;">
                <input type="button" name="{$lng.lbl_sf_properties}" value="{$lng.lbl_sf_properties}" onclick="location.href='configuration.php?option=Multiple_Storefronts'">
</div>
        {/if}

    {else}
        &nbsp;
    {/if}

	{if $active_modules.Multiple_Storefronts && ($usertype eq 'A' && $current_membership_flag ne 'FS' || $usertype eq 'P') && $login}
<div style="float: right;">
	<form action="{$smarty.server.REQUEST_URI|amp}" method="post" name="storefrontsform">
	<input type="hidden" name="mode" value="change_storefront" />
		<select name="cur_sf" onchange="javascript: document.storefrontsform.submit();">
			<option value="0"{if $current_storefront eq '0'} selected="selected" disabled="disabled"{/if}>{*$main_storefront*}{$cidev_main_storefront_name}</option>
			{foreach from=$storefronts item=sf}
{*
				<option value="{$sf.storefrontid}"{if $current_storefront eq $sf.storefrontid} selected="selected"{/if}>{$sf.domain}</option>
*}
				{if $sf.storefrontid ne "0"} 
                                <option value="{$sf.storefrontid}"{if $current_storefront eq $sf.storefrontid} selected="selected" disabled="disabled" {/if}>{if $sf.storefront_name ne ""}{$sf.storefront_name}{else}{$sf.domain}{/if}</option>
				{/if}
			{/foreach}
		</select>
	</form>
</div>
	{else}
		&nbsp;
	{/if}

</td>



</tr>
<tr> 
	<td colspan="4" class="HeadThinLine"><img src="{$ImagesDir}/spacer.gif" class="Spc" alt="" /></td>
</tr>
{******** Remove this line to display how much products there are online ****
<tr>
{insert name="productsonline" assign="_productsonline"}
	<td colspan="4" class="NumberOfArticles" align="right">
{if $config.Appearance.show_in_stock eq "Y"}
{insert name="itemsonline" assign="_itemsonline"}
{$lng.lbl_products_and_items_online|substitute:"X":$_productsonline:"Y":$_itemsonline}
{else}
{$lng.lbl_products_online|substitute:"X":$_productsonline}
{/if}
&nbsp;
	</td>
</tr>
**** Remove this line to display how much products there are online ********}
<tr>
	<td colspan="4">&nbsp;</td>
</tr>
</table>

{* $Id: product_links.tpl,v 1.23.2.2 2006/07/11 08:39:35 svowl Exp $ *}
{if $active_modules.Upselling_Products ne ""}
{include file="main/include_js.tpl" src="main/popup_product.js"}

{$lng.txt_upselling_links_top_text}

<br /><br />

{capture name=dialog}
{if $config.General.display_all_products_on_1_page eq 'Y'}<div align="right"><a href="#main">{$lng.lbl_top}</a></div>{/if}

<form action="product_modify.php" name="upsales" method="post">

<input type="hidden" name="productid" value="{$product.productid}" />
<input type="hidden" name="selected_productid" value="" />
<input type="hidden" name="mode" value="upselling_links" />
<input type="hidden" name="geid" value="{$geid}" />

<table {if $geid ne ''}cellspacing="0" cellpadding="4"{else}cellspacing="1" cellpadding="2"{/if} width="100%">
{if $geid ne ''}
<tr>
    <td width="15" class="TableSubHead">&nbsp;</td>
    <td class="TableSubHead" colspan="5"><b>* {$lng.lbl_note}:</b> {$lng.txt_edit_product_group}</td>
</tr>
{/if}
<tr class="TableHead">
{if $geid ne ''}<td width="15" class="TableSubHead">&nbsp;</td>{/if}
	<td width="5%" class="DataTable">&nbsp;</td>
	<td width="5%" class="DataTable">{$lng.lbl_pos}</td>
	<td width="15%" class="DataTable">{$lng.lbl_sku}&nbsp;&nbsp;&nbsp;</td>
	<td width="70%">{$lng.lbl_product}</td>
	<td width="5%">{$lng.lbl_bl}BL&nbsp;&nbsp;&nbsp;</td>
</tr>

{if $product_links}

{section name=cat_num loop=$product_links}

<tr{cycle values=", class='TableSubHead'"}>
	{if $geid ne ''}<td  class="TableSubHead"><input type="checkbox" value="Y" name="fields[u_product][{$product_links[cat_num].productid}]" /></td>{/if}
	<td><input type="checkbox" value="Y" name="uids[{$product_links[cat_num].productid}]" /></td>
	<td class="DataTable"><input type="text" value="{$product_links[cat_num].orderby}" name="upselling[{$product_links[cat_num].productid}]" size="4" /></td>
	<td class="DataTable">{$product_links[cat_num].productcode}</td>
	<td class="DataTable"><a href="product.php?productid={$product_links[cat_num].productid}" class="ItemsList" target="_blank">{$product_links[cat_num].product|escape|truncate:100:"...":false}</a></td>
	<td class="DataTable" align="center"><input type="checkbox" value="Y" name="blids[{$product_links[cat_num].productid}]" {if $product_links[cat_num].bl eq "Y"}checked="checked" {/if}/></td>
</tr>
{/section}

{else}

<tr>
	{if $geid ne ''}<td width="15" class="TableSubHead">&nbsp;</td>{/if}
	<td colspan="5" align="center">{$lng.lbl_no_products}</td>
</tr>

{/if}

</table>

<table {if $geid ne ''}cellspacing="0" cellpadding="4"{else}cellspacing="1" cellpadding="2"{/if} width="100%">

<tr>
	{if $geid ne ''}<td width="15" class="TableSubHead">&nbsp;</td>{/if}
	<td>&nbsp;</td>
</tr>
<tr>
	{if $geid ne ''}<td width="15" class="TableSubHead">&nbsp;</td>{/if}
	<td colspan="5">{include file="main/subheader.tpl" title=$lng.lbl_add_new_link}</td>
</tr>

<tr>
	{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[new_u_product]" /></td>{/if}
	<td colspan="3">{$lng.lbl_product}: <input type="text" name="prod_name" size="40" style="width=50%" disabled="disabled" />
<input type="button" value="{$lng.lbl_browse_|strip_tags:false|escape}" onclick="javascript: popup_product('upsales.selected_productid', 'upsales.prod_name');" /><br />
{$lng.lbl_bidirectional_link}<input type="checkbox" checked="checked" name="bi_directional" />
	</td>

    <td valign="top" align="center"><br/>{$lng.lbl_or}</td>
    <td valign="top">
        {$lng.lbl_sku_skus}<br/>
        <input type="text" size="40" value="" name="selected_sku">
    </td>


</tr>
<tr>
	{if $geid ne ''}<td width="15" class="TableSubHead">&nbsp;</td>{/if}
	<td>&nbsp;</td>
</tr>
<tr>
	{if $geid ne ''}<td width="15" class="TableSubHead">&nbsp;</td>{/if}
	<td nowrap="nowrap" colspan="5"><input type="submit" value="{$lng.lbl_add_update|strip_tags:false|escape}" />&nbsp;&nbsp;&nbsp;
	<input type="button" value="{$lng.lbl_delete_selected|strip_tags:false|escape}" onclick="javascript: document.upsales.mode.value='del_upsale_link'; document.upsales.submit();" />
	</td>
</tr>
</table>
</form>

{/capture}
{include file="dialog.tpl" title=$lng.lbl_upselling_links content=$smarty.capture.dialog extra='width="100%"'}
{/if}

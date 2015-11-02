{* $Id: product_thumb_image.tpl,v 1.0 2011/01/06 09:41:45 kate Exp $ *}

{if $config.Appearance.show_thumbnails eq "Y"}
{$lng.txt_det_product_thumb_image_top_text}

<br /><br />

{capture name=dialog}

<form action="product_modify.php" method="post" name="modifythumbform" enctype="multipart/form-data">
<input type="hidden" name="productid" value="{$product.productid}" />
<input type="hidden" name="section" value="section_thumb" />
<input type="hidden" name="mode" value="thumb_image" />
<input type="hidden" name="geid" value="{$geid}" />
<input type="hidden" name="type" value="P" />

<table cellpadding="4" cellspacing="0" width="100%">

{if $config.Product_Page.cidev_show_products_image eq "Y"}
<tr> 
	{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[product_image]" id="field_product" /></td>{/if}
	<td class="ProductDetails" valign="top">
		<font class="FormButton">{$lng.lbl_product_image}</font><br />
		{$lng.lbl_uploaded_instead_thumb}<br />
		{$lng.lbl_max_width_px|replace:"N":$config.Appearance.max_width_prod_img}<br />
		{$lng.lbl_max_height_px|replace:"N":$config.Appearance.max_height_prod_img}
	</td>
	{if $product.is_image}{assign var="no_delete" value=""}{else}{assign var="no_delete" value="Y"}{/if}
	<td class="ProductDetails">
	{include file="main/edit_image.tpl" type="P" id=$product.productid delete_js="submitForm(this, 'delete_product_image');" button_name='no_button' idtag="edit_product_image" image=$product.image.P already_loaded=$product.is_image_P source="PD"}
	</td>
	<td style="vertical-align: middle;" id="gen_thumb_btn">
		{if $product.is_image}
			<input type="button" value=" {$lng.lbl_generate_thumbnail|strip_tags:false|escape} " onclick="javascript: submitForm(this, 'gen_thumb');" />
		{else}
			&nbsp;
		{/if}
	</td>
	<td width="20%">&nbsp;</td>
</tr>
{/if}

<tr> 
	{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[thumbnail]" id="field_thumb" /></td>{/if}
	<td class="ProductDetails" valign="top"><font class="FormButton">{$lng.lbl_thumbnail}</font><br />{$lng.lbl_thumbnail_msg|replace:"N":$config.Appearance.thumbnail_width}</td>
	{if $product.is_thumbnail}{assign var="no_delete" value=""}{else}{assign var="no_delete" value="Y"}{/if}
	<td class="ProductDetails">
	{include file="main/edit_image.tpl" type="T" id=$product.productid delete_js="submitForm(this, 'delete_thumbnail');" button_name='no_button' image=$product.image.T already_loaded=$product.is_image_T source="PD"}
	</td>
	<td colspan="2">&nbsp;</td>
</tr>

<tr>
    <td colspan="4"><input type="submit" value="{$lng.lbl_upload}" /></td>
</tr>
</table>

</form>

{/capture}
{include file="dialog.tpl" title=$lng.lbl_product_thumbnail content=$smarty.capture.dialog extra='width="100%"'}
{/if}

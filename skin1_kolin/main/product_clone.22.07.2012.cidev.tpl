{* $Id: product_clone.tpl,v 1.0 2011/03/31 16:57:45 kate Exp $ *}

{$lng.txt_product_clone_top_text}

<br /><br />

{capture name=dialog}

<table cellpadding="4" cellspacing="0" width="100%">
<tr> 
	<td class="ProductDetails" valign="top">
		<font class="FormButton">{$lng.lbl_clone_with}</font>
	</td>
	<td class="ProductDetails" valign="top">
		<font class="FormButton">{$lng.lbl_delete_product_click}</font>
	</td>
</tr>
<tr>
	<td>
		<form action="process_product.php" method="post" name="cloneproductform">
		<input type="hidden" name="productid" value="{$product.productid}" />
		<input type="hidden" name="section" value="section_clone" />
		<input type="hidden" name="mode" value="clone" />

		<ul class="checkboxes_list">
			<li><label><input type="checkbox" name="clone[upselling]" value="Y" />&nbsp;{$lng.lbl_related_products}</label></li>
			<li><label><input type="checkbox" name="clone[product_files]" value="Y" />&nbsp;{$lng.lbl_product_files}</label></li>
			<li><label><input type="checkbox" name="clone[detailed_images]" value="Y" />&nbsp;{$lng.lbl_detailed_images}</label></li>
			<li><label><input type="checkbox" name="clone[product_image]" value="Y" />&nbsp;{$lng.lbl_product_image}</label></li>
			<li><label><input type="checkbox" name="clone[thumbnail]" value="Y" />&nbsp;{$lng.lbl_thumbnail_image}</label></li>
		</ul>
		<input type="submit" value="{$lng.lbl_clone}" />

		</form>
	</td>
	<td valign="top">
		<form action="process_product.php" method="post" name="deleteform">
		<input type="hidden" name="productid" value="{$product.productid}" />
		<input type="hidden" name="section" value="section_clone" />
		<input type="hidden" name="mode" value="delete" />
		<input type="submit" value="{$lng.lbl_delete_this_product}" />
		</form>
	</td>
</tr>
</table>

{/capture}
{include file="dialog.tpl" title=$lng.lbl_product_clone content=$smarty.capture.dialog extra='width="100%"'}

<?php /* Smarty version 2.6.12, created on 2011-10-11 06:53:29
         compiled from main/product_clone.tpl */ ?>
<?php func_load_lang($this, "main/product_clone.tpl","txt_product_clone_top_text,lbl_clone_with,lbl_delete_product_click,lbl_related_products,lbl_product_files,lbl_detailed_images,lbl_product_image,lbl_thumbnail_image,lbl_clone,lbl_delete_this_product,lbl_product_clone"); ?>
<?php echo $this->_tpl_vars['lng']['txt_product_clone_top_text']; ?>


<br /><br />

<?php ob_start(); ?>

<table cellpadding="4" cellspacing="0" width="100%">
<tr> 
	<td class="ProductDetails" valign="top">
		<font class="FormButton"><?php echo $this->_tpl_vars['lng']['lbl_clone_with']; ?>
</font>
	</td>
	<td class="ProductDetails" valign="top">
		<font class="FormButton"><?php echo $this->_tpl_vars['lng']['lbl_delete_product_click']; ?>
</font>
	</td>
</tr>
<tr>
	<td>
		<form action="process_product.php" method="post" name="cloneproductform">
		<input type="hidden" name="productid" value="<?php echo $this->_tpl_vars['product']['productid']; ?>
" />
		<input type="hidden" name="section" value="section_clone" />
		<input type="hidden" name="mode" value="clone" />

		<ul class="checkboxes_list">
			<li><label><input type="checkbox" name="clone[upselling]" value="Y" />&nbsp;<?php echo $this->_tpl_vars['lng']['lbl_related_products']; ?>
</label></li>
			<li><label><input type="checkbox" name="clone[product_files]" value="Y" />&nbsp;<?php echo $this->_tpl_vars['lng']['lbl_product_files']; ?>
</label></li>
			<li><label><input type="checkbox" name="clone[detailed_images]" value="Y" />&nbsp;<?php echo $this->_tpl_vars['lng']['lbl_detailed_images']; ?>
</label></li>
			<li><label><input type="checkbox" name="clone[product_image]" value="Y" />&nbsp;<?php echo $this->_tpl_vars['lng']['lbl_product_image']; ?>
</label></li>
			<li><label><input type="checkbox" name="clone[thumbnail]" value="Y" />&nbsp;<?php echo $this->_tpl_vars['lng']['lbl_thumbnail_image']; ?>
</label></li>
		</ul>
		<input type="submit" value="<?php echo $this->_tpl_vars['lng']['lbl_clone']; ?>
" />

		</form>
	</td>
	<td valign="top">
		<form action="process_product.php" method="post" name="deleteform">
		<input type="hidden" name="productid" value="<?php echo $this->_tpl_vars['product']['productid']; ?>
" />
		<input type="hidden" name="section" value="section_clone" />
		<input type="hidden" name="mode" value="delete" />
		<input type="submit" value="<?php echo $this->_tpl_vars['lng']['lbl_delete_this_product']; ?>
" />
		</form>
	</td>
</tr>
</table>

<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_product_clone'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
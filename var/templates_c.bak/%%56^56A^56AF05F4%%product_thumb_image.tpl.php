<?php /* Smarty version 2.6.12, created on 2011-10-11 06:53:29
         compiled from main/product_thumb_image.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'replace', 'main/product_thumb_image.tpl', 23, false),array('modifier', 'strip_tags', 'main/product_thumb_image.tpl', 32, false),array('modifier', 'escape', 'main/product_thumb_image.tpl', 32, false),)), $this); ?>
<?php func_load_lang($this, "main/product_thumb_image.tpl","txt_det_product_thumb_image_top_text,lbl_product_image,lbl_uploaded_instead_thumb,lbl_max_width_px,lbl_max_height_px,lbl_generate_thumbnail,lbl_thumbnail,lbl_thumbnail_msg,lbl_upload,lbl_product_thumbnail"); ?>
<?php if ($this->_tpl_vars['config']['Appearance']['show_thumbnails'] == 'Y'):  echo $this->_tpl_vars['lng']['txt_det_product_thumb_image_top_text']; ?>


<br /><br />

<?php ob_start(); ?>

<form action="product_modify.php" method="post" name="modifythumbform" enctype="multipart/form-data">
<input type="hidden" name="productid" value="<?php echo $this->_tpl_vars['product']['productid']; ?>
" />
<input type="hidden" name="section" value="section_thumb" />
<input type="hidden" name="mode" value="thumb_image" />
<input type="hidden" name="geid" value="<?php echo $this->_tpl_vars['geid']; ?>
" />
<input type="hidden" name="type" value="P" />

<table cellpadding="4" cellspacing="0" width="100%">
<tr> 
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[product_image]" id="field_product" /></td><?php endif; ?>
	<td class="ProductDetails" valign="top">
		<font class="FormButton"><?php echo $this->_tpl_vars['lng']['lbl_product_image']; ?>
</font><br />
		<?php echo $this->_tpl_vars['lng']['lbl_uploaded_instead_thumb']; ?>
<br />
		<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_max_width_px'])) ? $this->_run_mod_handler('replace', true, $_tmp, 'N', $this->_tpl_vars['config']['Appearance']['max_width_prod_img']) : smarty_modifier_replace($_tmp, 'N', $this->_tpl_vars['config']['Appearance']['max_width_prod_img'])); ?>
<br />
		<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_max_height_px'])) ? $this->_run_mod_handler('replace', true, $_tmp, 'N', $this->_tpl_vars['config']['Appearance']['max_height_prod_img']) : smarty_modifier_replace($_tmp, 'N', $this->_tpl_vars['config']['Appearance']['max_height_prod_img'])); ?>

	</td>
	<?php if ($this->_tpl_vars['product']['is_image']):  $this->assign('no_delete', "");  else:  $this->assign('no_delete', 'Y');  endif; ?>
	<td class="ProductDetails">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/edit_image.tpl", 'smarty_include_vars' => array('type' => 'P','id' => $this->_tpl_vars['product']['productid'],'delete_js' => "submitForm(this, 'delete_product_image');",'button_name' => 'no_button','idtag' => 'edit_product_image','image' => $this->_tpl_vars['product']['image']['P'],'already_loaded' => $this->_tpl_vars['product']['is_image_P'],'source' => 'PD')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	</td>
	<td style="vertical-align: middle;" id="gen_thumb_btn">
		<?php if ($this->_tpl_vars['product']['is_image']): ?>
			<input type="button" value=" <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_generate_thumbnail'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 " onclick="javascript: submitForm(this, 'gen_thumb');" />
		<?php else: ?>
			&nbsp;
		<?php endif; ?>
	</td>
	<td width="20%">&nbsp;</td>
</tr>
<tr> 
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[thumbnail]" id="field_thumb" /></td><?php endif; ?>
	<td class="ProductDetails" valign="top"><font class="FormButton"><?php echo $this->_tpl_vars['lng']['lbl_thumbnail']; ?>
</font><br /><?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_thumbnail_msg'])) ? $this->_run_mod_handler('replace', true, $_tmp, 'N', $this->_tpl_vars['config']['Appearance']['thumbnail_width']) : smarty_modifier_replace($_tmp, 'N', $this->_tpl_vars['config']['Appearance']['thumbnail_width'])); ?>
</td>
	<?php if ($this->_tpl_vars['product']['is_thumbnail']):  $this->assign('no_delete', "");  else:  $this->assign('no_delete', 'Y');  endif; ?>
	<td class="ProductDetails">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/edit_image.tpl", 'smarty_include_vars' => array('type' => 'T','id' => $this->_tpl_vars['product']['productid'],'delete_js' => "submitForm(this, 'delete_thumbnail');",'button_name' => 'no_button','image' => $this->_tpl_vars['product']['image']['T'],'already_loaded' => $this->_tpl_vars['product']['is_image_T'],'source' => 'PD')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	</td>
	<td colspan="2">&nbsp;</td>
</tr>

<tr>
    <td colspan="4"><input type="submit" value="<?php echo $this->_tpl_vars['lng']['lbl_upload']; ?>
" /></td>
</tr>
</table>

</form>

<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_product_thumbnail'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
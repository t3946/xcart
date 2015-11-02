<?php /* Smarty version 2.6.12, created on 2011-10-11 06:53:29
         compiled from modules/Customer_Reviews/admin_reviews.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'modules/Customer_Reviews/admin_reviews.tpl', 34, false),array('modifier', 'default', 'modules/Customer_Reviews/admin_reviews.tpl', 37, false),array('modifier', 'strip_tags', 'modules/Customer_Reviews/admin_reviews.tpl', 65, false),array('modifier', 'escape', 'modules/Customer_Reviews/admin_reviews.tpl', 65, false),)), $this); ?>
<?php func_load_lang($this, "modules/Customer_Reviews/admin_reviews.tpl","txt_adm_reviews_top_text,lbl_top,txt_note,txt_edit_product_group,lbl_author,lbl_message,lbl_unknown,lbl_unknown,txt_no_reviews,lbl_add_new_review,lbl_add_update,lbl_delete_selected,lbl_edit_reviews"); ?><?php if ($this->_tpl_vars['active_modules']['Customer_Reviews'] != ""): ?>

<?php echo $this->_tpl_vars['lng']['txt_adm_reviews_top_text']; ?>


<br /><br />

<?php ob_start();  if ($this->_tpl_vars['config']['General']['display_all_products_on_1_page'] == 'Y'): ?><div align="right"><a href="#main"><?php echo $this->_tpl_vars['lng']['lbl_top']; ?>
</a></div><?php endif; ?>

<form action="product_modify.php" method="post" name="modifyreviews">
<input type="hidden" name="mode" value="update_reviews" />
<input type="hidden" name="productid" value="<?php echo $this->_tpl_vars['product']['productid']; ?>
" />
<input type="hidden" name="geid" value="<?php echo $this->_tpl_vars['geid']; ?>
" />

<table cellspacing="0" cellpadding="3" width="100%">
<?php if ($this->_tpl_vars['geid'] != ''): ?>
<tr>
	<td width="15" class="TableSubHead"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" width="15" height="1" border="0" /></td>
	<td class="TableSubHead" colspan="3"><b>* <?php echo $this->_tpl_vars['lng']['txt_note']; ?>
:</b> <?php echo $this->_tpl_vars['lng']['txt_edit_product_group']; ?>
</td>
</tr>
<?php endif; ?>

<tr class="TableHead">
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead">&nbsp;</td><?php endif; ?>
	<td width="15" class="DataTable">&nbsp;</td>
	<td width="30%" class="DataTable"><?php echo $this->_tpl_vars['lng']['lbl_author']; ?>
</td>
	<td width="70%"><?php echo $this->_tpl_vars['lng']['lbl_message']; ?>
</td>
</tr>

<?php if ($this->_tpl_vars['product_reviews']): ?>

<?php $_from = $this->_tpl_vars['product_reviews']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['r']):
?>
<tr valign="top"<?php echo smarty_function_cycle(array('values' => ', class="TableSubHead"'), $this);?>
>
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[review][<?php echo $this->_tpl_vars['r']['review_id']; ?>
]" /></td><?php endif; ?>
	<td width="15" class="DataTable"><input type="checkbox" value="Y" name="rids[<?php echo $this->_tpl_vars['r']['review_id']; ?>
]" /></td>
	<td class="DataTable"><input type="text" size="32" name="reviews[<?php echo $this->_tpl_vars['r']['review_id']; ?>
][email]" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['r']['email'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['lng']['lbl_unknown']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['lng']['lbl_unknown'])); ?>
" style="width:100%" /></td>
	<td width="65%"><textarea cols="40" rows="6" name="reviews[<?php echo $this->_tpl_vars['r']['review_id']; ?>
][message]" style="width: 100%"><?php echo $this->_tpl_vars['r']['message']; ?>
</textarea></td>
</tr>
<?php endforeach; endif; unset($_from); ?>

<?php else: ?>

<tr>
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead">&nbsp;</td><?php endif; ?>
	<td colspan="3" align="center"><?php echo $this->_tpl_vars['lng']['txt_no_reviews']; ?>
</td>
</tr>

<?php endif; ?>

<tr>
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead">&nbsp;</td><?php endif; ?>
	<td colspan="3"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_add_new_review'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>

<tr valign="top">
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[new_review]" /></td><?php endif; ?>
	<td>&nbsp;</td>
	<td><input type="text" size="32" name="review_new[email]" value="" /></td>
	<td colspan="2"><textarea cols="40" rows="6" name="review_new[message]" style="width: 100%"></textarea></td>
</tr>

<tr valign="top">
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead">&nbsp;</td><?php endif; ?>
	<td colspan="3"><input type="submit" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_add_update'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
<?php if ($this->_tpl_vars['product_reviews']): ?>
	&nbsp;&nbsp;&nbsp;
	<input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_delete_selected'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: document.modifyreviews.mode.value='review_delete'; document.modifyreviews.submit();" />
<?php endif; ?>
	</td>
</tr>

</table>
</form>

<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('content' => $this->_smarty_vars['capture']['dialog'],'title' => $this->_tpl_vars['lng']['lbl_edit_reviews'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
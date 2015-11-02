<?php /* Smarty version 2.6.12, created on 2011-10-11 05:54:16
         compiled from provider/main/categories.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'provider/main/categories.tpl', 23, false),array('modifier', 'strip_tags', 'provider/main/categories.tpl', 32, false),array('modifier', 'escape', 'provider/main/categories.tpl', 32, false),array('function', 'cycle', 'provider/main/categories.tpl', 70, false),)), $this); ?>
<?php func_load_lang($this, "provider/main/categories.tpl","lbl_categories_management,lbl_info_pages,txt_categories_management_top_text,lbl_current_category,lbl_root_level,lbl_root_level,txt_category_disabled,lbl_modify_category,lbl_modify,lbl_category_products,lbl_delete_category,lbl_delete,txt_list_of_subcategories,lbl_pos,lbl_subcat,lbl_categories_more,lbl_category_name,lbl_products,lbl_parent_categories,lbl_enabled,txt_not_available,txt_not_available,lbl_categories_more,lbl_categories_more,txt_not_available,txt_not_available,txt_not_available,lbl_yes,lbl_no,txt_no_categories,lbl_note,txt_categoryies_management_note,lbl_update,lbl_modify_selected,lbl_delete_selected,lbl_add_new_,lbl_categories,lbl_info_pages"); ?><?php if (( $GLOBALS['HTTP_GET_VARS']['mode'] != 'info' )):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "page_title.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_categories_management'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  else:  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "page_title.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_info_pages'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

<?php if (( $GLOBALS['HTTP_GET_VARS']['mode'] != 'info' )):  echo $this->_tpl_vars['lng']['txt_categories_management_top_text']; ?>


<br /><br />
<?php endif; ?>

<?php ob_start(); ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/main/location.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if ($this->_tpl_vars['cat']): ?>

<table width="100%">

<tr>
<td align="center" class="TopLabel"><?php echo $this->_tpl_vars['lng']['lbl_current_category']; ?>
: "<?php echo ((is_array($_tmp=@$this->_tpl_vars['current_category']['category'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['lng']['lbl_root_level']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['lng']['lbl_root_level'])); ?>
"
<?php if ($this->_tpl_vars['current_category']['avail'] == 'N'): ?>
<div class="ErrorMessage"><?php echo $this->_tpl_vars['lng']['txt_category_disabled']; ?>
</div>
<?php endif; ?>
</td>
</tr>

<tr>
<td align="right" class="SubmitBox">
<input type="button" value="<?php if (( $GLOBALS['HTTP_GET_VARS']['mode'] != 'info' )):  echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_modify_category'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp));  else:  echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_modify'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp));  endif; ?>" onclick="javascript: self.location='category_modify.php?cat=<?php echo $this->_tpl_vars['cat']; ?>
'" />
<?php if (( $GLOBALS['HTTP_GET_VARS']['mode'] != 'info' )): ?>
<input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_category_products'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: self.location='category_products.php?cat=<?php echo $this->_tpl_vars['cat']; ?>
'" />
<?php endif; ?>
<input type="button" value="<?php if (( $GLOBALS['HTTP_GET_VARS']['mode'] != 'info' )):  echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_delete_category'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp));  else:  echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_delete'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp));  endif; ?>" onclick="javascript: self.location='process_category.php?cat=<?php echo $this->_tpl_vars['cat']; ?>
&amp;mode=delete'" />
</td>
</tr>

</table>

<br /><br />

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['txt_list_of_subcategories'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php endif; ?>

<br />

<form action="process_category.php" method="post" name="processcategoryform">
<input type="hidden" name="cat_org" value="<?php echo ((is_array($_tmp=$GLOBALS['HTTP_GET_VARS']['cat'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" />

<table cellpadding="2" cellspacing="1" width="100%">

<tr class="TableHead">
	<td><?php echo $this->_tpl_vars['lng']['lbl_pos']; ?>
</td>
	<td align="center"><?php echo $this->_tpl_vars['lng']['lbl_subcat']; ?>
</td>
	<td align="center"><?php echo $this->_tpl_vars['lng']['lbl_categories_more']; ?>
</td>
	<td colspan="2"><?php echo $this->_tpl_vars['lng']['lbl_category_name']; ?>
</td>
	<td align="center"><?php echo $this->_tpl_vars['lng']['lbl_products']; ?>
*</td>
	<td align="center"><?php echo $this->_tpl_vars['lng']['lbl_parent_categories']; ?>
</td>
	<td align="center"><?php echo $this->_tpl_vars['lng']['lbl_enabled']; ?>
</td>
</tr>

<?php $this->assign('cat_selected', 0);  $_from = $this->_tpl_vars['subcategories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['catid'] => $this->_tpl_vars['c']):
?>

<?php if (( $GLOBALS['HTTP_GET_VARS']['mode'] == 'info' && $this->_tpl_vars['c']['order_by'] > 500 ) || ( $GLOBALS['HTTP_GET_VARS']['mode'] != 'info' && $this->_tpl_vars['c']['order_by'] <= 500 ) || ( $GLOBALS['HTTP_GET_VARS']['cat'] > 0 )): ?>

<tr<?php echo smarty_function_cycle(array('values' => ', class="TableSubHead"'), $this);?>
>
	<td width="1%"><input type="text" size="3" name="posted_data[<?php echo $this->_tpl_vars['catid']; ?>
][order_by]" maxlength="3" value="<?php if ($this->_tpl_vars['c']['parentid'] != $this->_tpl_vars['cat'] && $this->_tpl_vars['c']['add_order_by']):  echo $this->_tpl_vars['c']['add_order_by'];  else:  echo $this->_tpl_vars['c']['order_by'];  endif; ?>" /></td>
	<td align="center"><a href="categories.php?cat=<?php echo $this->_tpl_vars['catid'];  if ($GLOBALS['HTTP_GET_VARS']['mode'] == 'info'): ?>&mode=info<?php endif; ?>"><?php echo ((is_array($_tmp=@$this->_tpl_vars['c']['subcategory_count'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['lng']['txt_not_available']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['lng']['txt_not_available'])); ?>
</a></td>
	<td align="center"><a href="javascript: var el = document.getElementById('rcat_<?php echo $this->_tpl_vars['catid']; ?>
'); el.setAttribute('checked', 'checked'); submitForm(document.processcategoryform, 'update');" title="<?php echo $this->_tpl_vars['lng']['lbl_categories_more']; ?>
"><?php echo $this->_tpl_vars['lng']['lbl_categories_more']; ?>
</a></td>
	<td width="1%"><input type="radio" name="cat" id="rcat_<?php echo $this->_tpl_vars['catid']; ?>
" value="<?php echo $this->_tpl_vars['catid']; ?>
"<?php if ($this->_tpl_vars['cat_selected'] == 0): ?> checked="checked"<?php endif; ?> /></td>
	<td width="100"><input type="text" size="60" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['c']['category'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" name="posted_data[<?php echo $this->_tpl_vars['catid']; ?>
][category]" class="<?php if ($this->_tpl_vars['c']['avail'] == 'N'): ?>ItemsListDisabled<?php else: ?>ItemsListBold<?php endif; ?>" /></td>
	<td align="center">
<?php if ($this->_tpl_vars['c']['product_count'] == 0 && $this->_tpl_vars['c']['product_count_global'] == 0):  echo $this->_tpl_vars['lng']['txt_not_available']; ?>

<?php else: ?>
<a href="category_products.php?cat=<?php echo $this->_tpl_vars['catid']; ?>
"><?php echo ((is_array($_tmp=@$this->_tpl_vars['c']['product_count'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['lng']['txt_not_available']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['lng']['txt_not_available'])); ?>
</a> (<?php echo $this->_tpl_vars['c']['product_count_global']; ?>
)
<?php endif; ?>
	</td>
	<td align="center" nowrap="nowrap"><input type="text" size="5" name="posted_data[<?php echo $this->_tpl_vars['catid']; ?>
][parentid]" value="<?php echo $this->_tpl_vars['c']['parentid']; ?>
" />&nbsp;<input type="text" size="20" name="posted_data[<?php echo $this->_tpl_vars['catid']; ?>
][additional_parentid]" value="<?php echo $this->_tpl_vars['additional_parentid'][$this->_tpl_vars['catid']]['add_parentids']; ?>
" /></td>
	<td align="center">
	<select name="posted_data[<?php echo $this->_tpl_vars['catid']; ?>
][avail]">
		<option value="Y"<?php if ($this->_tpl_vars['c']['avail'] == 'Y'): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_yes']; ?>
</option>
		<option value="N"<?php if ($this->_tpl_vars['c']['avail'] == 'N'): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_no']; ?>
</option>
	</select>
	</td>
</tr>

<?php $this->assign('cat_selected', 1);  endif;  endforeach; else: ?>

<tr>
	<td colspan="6" align="center"><?php echo $this->_tpl_vars['lng']['txt_no_categories']; ?>
</td>
</tr>

<?php endif; unset($_from); ?>

<?php if ($this->_tpl_vars['subcategories']): ?>
<tr>
	<td colspan="6">
<b>*<?php echo $this->_tpl_vars['lng']['lbl_note']; ?>
:</b> <?php echo $this->_tpl_vars['lng']['txt_categoryies_management_note']; ?>

	</td>
</tr>
<tr>
	<td colspan="6" class="SubmitBox">
<input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_update'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: submitForm(this, 'apply');" />
<br /><br />
<input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_modify_selected'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: submitForm(this, 'update');" />
<input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_delete_selected'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: submitForm(this, 'delete');" />
	</td>
</tr>
<?php endif; ?>

<tr>
	<td colspan="6" class="SubmitBox"><input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_add_new_'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="self.location='category_modify.php?mode=add&amp;cat=<?php echo $this->_tpl_vars['cat']; ?>
'" /></td>
</tr>

</table>

<input type="hidden" name="mode" value="apply" />
</form>

<br />

<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean(); ?>

<?php if (( $GLOBALS['HTTP_GET_VARS']['mode'] != 'info' )):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_categories'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  else:  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_info_pages'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

<?php if (( $GLOBALS['HTTP_GET_VARS']['mode'] != 'info' )): ?>
<br /><br />

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/main/featured_products.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
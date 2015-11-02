<?php /* Smarty version 2.6.12, created on 2011-10-11 06:53:22
         compiled from provider/main/category_products.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'provider/main/category_products.tpl', 15, false),array('modifier', 'strip_tags', 'provider/main/category_products.tpl', 24, false),array('modifier', 'escape', 'provider/main/category_products.tpl', 24, false),array('modifier', 'substitute', 'provider/main/category_products.tpl', 41, false),)), $this); ?>
<?php func_load_lang($this, "provider/main/category_products.tpl","lbl_category_products,txt_category_products_top_text,lbl_current_category,lbl_root_level,lbl_root_level,txt_category_disabled,lbl_modify_category,lbl_delete_category,lbl_category_products,txt_N_results_found,txt_displaying_X_Y_results,txt_no_products_in_cat,lbl_delete_selected,txt_delete_products_warning,lbl_update,lbl_modify_selected,txt_operation_for_first_selected_only,lbl_preview_product,lbl_clone_product,lbl_generate_html_links,lbl_add_new_,lbl_products"); ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "page_title.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_category_products'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php echo $this->_tpl_vars['lng']['txt_category_products_top_text']; ?>


<br /><br />

<?php ob_start(); ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/main/location.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

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
<td align="right"><br />
<input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_modify_category'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: self.location='category_modify.php?cat=<?php echo $this->_tpl_vars['cat']; ?>
'" />
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_delete_category'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: self.location='process_category.php?cat=<?php echo $this->_tpl_vars['cat']; ?>
&amp;mode=delete'" />
</td>
</tr>

</table>

<br /><br />

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_category_products'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>


<!-- SEARCH RESULTS SUMMARY -->

<?php if ($this->_tpl_vars['mode'] == 'search'):  if ($this->_tpl_vars['total_items'] > '1'):  echo ((is_array($_tmp=$this->_tpl_vars['lng']['txt_N_results_found'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'items', $this->_tpl_vars['total_items']) : smarty_modifier_substitute($_tmp, 'items', $this->_tpl_vars['total_items'])); ?>
<br />
<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['txt_displaying_X_Y_results'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'first_item', $this->_tpl_vars['first_item'], 'last_item', $this->_tpl_vars['last_item']) : smarty_modifier_substitute($_tmp, 'first_item', $this->_tpl_vars['first_item'], 'last_item', $this->_tpl_vars['last_item'])); ?>

<?php elseif ($this->_tpl_vars['total_items'] == '0'): ?>
<br />
<div align="center"><?php echo $this->_tpl_vars['lng']['txt_no_products_in_cat']; ?>
</div>
<?php endif;  endif; ?>

<?php if ($this->_tpl_vars['products']): ?>

<!-- SEARCH RESULTS START -->

<br />

<?php if ($this->_tpl_vars['total_pages'] > 2):  $this->assign('navpage', $this->_tpl_vars['navigation_page']);  endif; ?>

<form action="process_product.php" method="post" name="processproductform">
<input type="hidden" name="section" value="category_products" />
<input type="hidden" name="mode" value="update" />
<input type="hidden" name="navpage" value="<?php echo $this->_tpl_vars['navpage']; ?>
" />
<input type="hidden" name="cat" value="<?php echo $this->_tpl_vars['cat']; ?>
" />

<table cellpadding="0" cellspacing="0" width="100%">

<tr>
<td>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/navigation.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/products.tpl", 'smarty_include_vars' => array('products' => $this->_tpl_vars['products'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<br />

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/navigation.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<br />

<input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_delete_selected'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: if (checkMarks(this.form, new RegExp('productids\[[0-9]+\]', 'gi'))) if (confirm('<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['txt_delete_products_warning'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)); ?>
')) submitForm(this, 'delete');" />
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="submit" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_update'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_modify_selected'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: if (checkMarks(this.form, new RegExp('productids\[[0-9]+\]', 'gi'))) { document.processproductform.action = 'product_modify.php'; submitForm(this, 'list'); }" />
<br /><br /><br />

<?php echo $this->_tpl_vars['lng']['txt_operation_for_first_selected_only']; ?>


<br /><br />

<input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_preview_product'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: if (checkMarks(this.form, new RegExp('productids\[[0-9]+\]', 'gi'))) submitForm(this, 'details');" />
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_clone_product'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: if (checkMarks(this.form, new RegExp('productids\[[0-9]+\]', 'gi'))) submitForm(this, 'clone');" />
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_generate_html_links'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: if (checkMarks(this.form, new RegExp('productids\[[0-9]+\]', 'gi'))) submitForm(this, 'links');" />

</td>
</tr>

</table>
</form>

<?php endif; ?>

<br />

<input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_add_new_'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: self.location = 'product_modify.php?categoryid=<?php echo $this->_tpl_vars['cat']; ?>
';" />

<br />

<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_products'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

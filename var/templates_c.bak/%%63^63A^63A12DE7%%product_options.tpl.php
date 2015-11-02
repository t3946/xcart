<?php /* Smarty version 2.6.12, created on 2011-10-11 06:53:29
         compiled from modules/Product_Options/product_options.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'replace', 'modules/Product_Options/product_options.tpl', 17, false),array('modifier', 'formatprice', 'modules/Product_Options/product_options.tpl', 100, false),array('modifier', 'default', 'modules/Product_Options/product_options.tpl', 101, false),array('modifier', 'strip_tags', 'modules/Product_Options/product_options.tpl', 123, false),array('modifier', 'escape', 'modules/Product_Options/product_options.tpl', 123, false),array('function', 'cycle', 'modules/Product_Options/product_options.tpl', 87, false),)), $this); ?>
<?php func_load_lang($this, "modules/Product_Options/product_options.tpl","lbl_top,txt_product_options_list_note,txt_variant_alert,txt_delete_variant_alert,lbl_check_all,lbl_uncheck_all,lbl_note,txt_edit_product_group,lbl_option_class,lbl_option_type,lbl_orderby,lbl_availability,lbl_options_list,lbl_modificator,lbl_text_field,lbl_variant,lbl_options_list_empty,lbl_product_options_list_empty,lbl_update,lbl_delete_selected,lbl_add_new_,lbl_product_option_groups,lbl_top,txt_product_option_exceptions_note,lbl_warning,txt_default_options_failure_note,lbl_note,txt_edit_product_group,lbl_options_combination,lbl_exceptions_list_empty,lbl_delete_selected,lbl_add_exception,lbl_select_one_bracket,lbl_add_exception,lbl_product_option_exceptions,lbl_top,txt_product_options_js_note,lbl_note,txt_edit_product_group,lbl_update,lbl_validation_script_javascript"); ?><?php if ($this->_tpl_vars['active_modules']['Product_Options'] != ""):  if ($this->_tpl_vars['script_name'] == ''):  $this->assign('script_name', "product_modify.php");  endif; ?>

<a name="top"></a>
<?php ob_start();  if ($this->_tpl_vars['config']['General']['display_all_products_on_1_page'] == 'Y'): ?><div align="right"><a href="#main"><?php echo $this->_tpl_vars['lng']['lbl_top']; ?>
</a></div><?php endif;  echo $this->_tpl_vars['lng']['txt_product_options_list_note']; ?>
<br />
<br />

<?php if ($this->_tpl_vars['product_options'] != ''): ?>
<script type="text/javascript" language="JavaScript 1.2">
<!--
checkboxes_form = 'productoptionsform';
checkboxes = new Array(<?php $_from = $this->_tpl_vars['product_options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
 if ($this->_tpl_vars['k'] > 0): ?>,<?php endif; ?>'to_delete[<?php echo $this->_tpl_vars['v']['classid']; ?>
]'<?php endforeach; endif; unset($_from); ?>);

var v_alert = "<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['txt_variant_alert'])) ? $this->_run_mod_handler('replace', true, $_tmp, '"', '\"') : smarty_modifier_replace($_tmp, '"', '\"')))) ? $this->_run_mod_handler('replace', true, $_tmp, "\n", "") : smarty_modifier_replace($_tmp, "\n", "")); ?>
";
var v_del_alert = "<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['txt_delete_variant_alert'])) ? $this->_run_mod_handler('replace', true, $_tmp, '"', '\"') : smarty_modifier_replace($_tmp, '"', '\"')))) ? $this->_run_mod_handler('replace', true, $_tmp, "\n", "") : smarty_modifier_replace($_tmp, "\n", "")); ?>
";
var del_variants = [];
var disabled_variants = [];
<?php $_from = $this->_tpl_vars['product_options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
 if ($this->_tpl_vars['v']['is_modifier'] == ''): ?>
del_variants[<?php echo $this->_tpl_vars['v']['classid']; ?>
] = true;
<?php if ($this->_tpl_vars['v']['avail'] != 'Y'): ?>
disabled_variants[<?php echo $this->_tpl_vars['v']['classid']; ?>
] = true;
<?php endif;  endif;  endforeach; endif; unset($_from); ?>

<?php echo '
function variant_alert(obj, id) {
	if(!obj)
		return false
	if(!obj.checked && !disabled_variants[id])
		return confirm(v_alert);
	return true;
}

function variant_del_alert() {
	if (del_variants.length == 0)
		return true;

	for (var x in del_variants) {
		if (isNaN(x))
			continue;
		var n = document.productoptionsform.elements[\'to_delete[\'+x+\']\'];
		if (n && n.checked)
			return confirm(v_del_alert);
	}
	return true;
}

'; ?>

-->
</script>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/include_js.tpl", 'smarty_include_vars' => array('src' => "change_all_checkboxes.js")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<div style="line-height:170%"><a href="javascript:change_all(true);"><?php echo $this->_tpl_vars['lng']['lbl_check_all']; ?>
</a> / <a href="javascript:change_all(false);"><?php echo $this->_tpl_vars['lng']['lbl_uncheck_all']; ?>
</a></div>
<?php endif; ?>

<form action="<?php echo $this->_tpl_vars['script_name']; ?>
" method="post" name="productoptionsform">
<input type="hidden" name="section" value="options" />
<input type="hidden" name="mode" value="product_options_modify" />
<input type="hidden" name="productid" value="<?php echo $this->_tpl_vars['product']['productid']; ?>
" />
<input type="hidden" name="geid" value="<?php echo $this->_tpl_vars['geid']; ?>
" />

<table <?php if ($this->_tpl_vars['geid'] != ''): ?>cellspacing="0" cellpadding="4"<?php else: ?>cellspacing="1" cellpadding="2"<?php endif; ?> width="100%">

<?php if ($this->_tpl_vars['geid'] != ''): ?>
<tr>
    <td width="15" class="TableSubHead">&nbsp;</td>
    <td class="TableSubHead" colspan="7"><b>* <?php echo $this->_tpl_vars['lng']['lbl_note']; ?>
:</b> <?php echo $this->_tpl_vars['lng']['txt_edit_product_group']; ?>
</td>
</tr>
<?php endif; ?>

<tr class="TableHead"> 
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead">&nbsp;</td><?php endif; ?>
	<td width="10" class="DataTable">&nbsp;</td>
	<td class="DataTable">#</td>
	<td nowrap="nowrap" class="DataTable"><?php echo $this->_tpl_vars['lng']['lbl_option_class']; ?>
</td>
	<td nowrap="nowrap" class="DataTable"><?php echo $this->_tpl_vars['lng']['lbl_option_type']; ?>
</td>
	<td class="DataTable"><?php echo $this->_tpl_vars['lng']['lbl_orderby']; ?>
</td>
	<td class="DataTable"><?php echo $this->_tpl_vars['lng']['lbl_availability']; ?>
</td>
	<td width="70%"><?php echo $this->_tpl_vars['lng']['lbl_options_list']; ?>
</td>
</tr>
<?php $_from = $this->_tpl_vars['product_options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
<tr<?php echo smarty_function_cycle(array('name' => 'classes','values' => ', class="TableSubHead"'), $this);?>
>
<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead" valign="top"><input type="checkbox" value="Y" name="fields[classes][<?php echo $this->_tpl_vars['v']['classid']; ?>
]" /></td><?php endif; ?>
	<td valign="top" class="DataTable"><input type="checkbox" name="to_delete[<?php echo $this->_tpl_vars['v']['classid']; ?>
]" value="Y" /></td>
	<td valign="top" class="DataTable"><?php echo $this->_tpl_vars['v']['classid']; ?>
</td>
	<td valign="top" class="DataTable"><a href="<?php echo $this->_tpl_vars['script_name']; ?>
?productid=<?php echo $this->_tpl_vars['product']['productid']; ?>
&amp;classid=<?php echo $this->_tpl_vars['v']['classid']; ?>
&amp;section=options<?php echo $this->_tpl_vars['redirect_geid']; ?>
#modify_class"><?php echo $this->_tpl_vars['v']['class']; ?>
</a></td>
	<td valign="top" class="DataTable"><?php if ($this->_tpl_vars['v']['is_modifier'] == 'Y'):  echo $this->_tpl_vars['lng']['lbl_modificator'];  elseif ($this->_tpl_vars['v']['is_modifier'] == 'T'):  echo $this->_tpl_vars['lng']['lbl_text_field'];  else:  echo $this->_tpl_vars['lng']['lbl_variant'];  endif; ?></td>
	<td valign="top" class="DataTable"><input type="text" name="po_classes[<?php echo $this->_tpl_vars['v']['classid']; ?>
][orderby]" size="5" maxlength="11" value="<?php echo $this->_tpl_vars['v']['orderby']; ?>
" /></td>
	<td align="center" valign="top"><input type="checkbox" name="po_classes[<?php echo $this->_tpl_vars['v']['classid']; ?>
][avail]" value="Y"<?php if ($this->_tpl_vars['v']['avail'] == 'Y'): ?> checked="checked"<?php endif;  if ($this->_tpl_vars['v']['is_modifier'] == ''): ?> onclick="javascript: return variant_alert(this, <?php echo $this->_tpl_vars['v']['classid']; ?>
);"<?php endif; ?> /></td>
	<td valign="top"><table cellspacing="0" cellpadding="2">
	<?php $_from = $this->_tpl_vars['v']['options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['o']):
?>
	<tr>
		<td><?php if ($this->_tpl_vars['o']['avail'] != 'Y'): ?><font color="red"><?php endif;  echo $this->_tpl_vars['o']['option_name'];  if ($this->_tpl_vars['o']['avail'] != 'Y'): ?></font><?php endif; ?></td>
	<?php if ($this->_tpl_vars['v']['is_modifier'] == 'Y' && $this->_tpl_vars['o']['price_modifier'] != 0): ?>
		<td><?php echo ((is_array($_tmp=$this->_tpl_vars['o']['price_modifier'])) ? $this->_run_mod_handler('formatprice', true, $_tmp) : smarty_modifier_formatprice($_tmp)); ?>
</td>
		<td><?php if (((is_array($_tmp=@$this->_tpl_vars['o']['modifier_type'])) ? $this->_run_mod_handler('default', true, $_tmp, "$") : smarty_modifier_default($_tmp, "$")) == '$'):  echo $this->_tpl_vars['config']['General']['currency_symbol'];  else: ?>%<?php endif; ?></td>
	<?php endif; ?>
	</tr>
	<?php endforeach; else: ?>
	<tr>
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td>&nbsp;</td><?php endif; ?>
	<td colspan="<?php if ($this->_tpl_vars['v']['is_modifier'] == 'Y'): ?>3<?php else: ?>1<?php endif; ?>"><?php echo $this->_tpl_vars['lng']['lbl_options_list_empty']; ?>
</td>
	</tr>
	<?php endif; unset($_from); ?></table>
	</td>
</tr>
<?php endforeach; else: ?>
<tr>
<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead">&nbsp;</td><?php endif; ?>
	<td align="center" colspan="7"><?php echo $this->_tpl_vars['lng']['lbl_product_options_list_empty']; ?>
</td>
</tr>
<?php endif; unset($_from); ?>
</table>
<?php if ($this->_tpl_vars['product_options'] != ''): ?>
<br />
<table width="100%">
<tr>
	<td align="left"><input type="submit" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_update'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />&nbsp;&nbsp;&nbsp;<input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_delete_selected'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: if(variant_del_alert()) { document.productoptionsform.mode.value='product_options_delete'; document.productoptionsform.submit(); }" /></td>
	<td align="right"><input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_add_new_'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: self.location='<?php echo $this->_tpl_vars['script_name']; ?>
?submode=product_options_add&amp;productid=<?php echo $this->_tpl_vars['product']['productid']; ?>
&amp;section=options<?php echo $this->_tpl_vars['redirect_geid']; ?>
';" /></td>
</tr>
</table>
<br />
<?php endif; ?>
</form>
<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_product_option_groups'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<br />

<?php if ($this->_tpl_vars['product_options'] != ''): ?>
<br />

<a name="exceptions"></a>
<?php ob_start();  if ($this->_tpl_vars['config']['General']['display_all_products_on_1_page'] == 'Y'): ?><div align="right"><a href="#main"><?php echo $this->_tpl_vars['lng']['lbl_top']; ?>
</a></div><?php endif;  echo $this->_tpl_vars['lng']['txt_product_option_exceptions_note']; ?>
<br />
<br />

<?php if ($this->_tpl_vars['def_options_failure']): ?>
<div class="ErrorMessage"><?php echo $this->_tpl_vars['lng']['lbl_warning']; ?>
: <?php echo $this->_tpl_vars['lng']['txt_default_options_failure_note']; ?>
</div>
<br />
<?php endif; ?>

<form action="<?php echo $this->_tpl_vars['script_name']; ?>
" method="post" name="exceptionform">
<input type="hidden" name="section" value="options" />
<input type="hidden" name="mode" value="product_options_ex_add" />
<input type="hidden" name="productid" value="<?php echo $this->_tpl_vars['product']['productid']; ?>
" />
<input type="hidden" name="geid" value="<?php echo $this->_tpl_vars['geid']; ?>
" />

<table width="100%" cellspacing="0" cellpadding="3">
<?php if ($this->_tpl_vars['geid'] != ''): ?>
<tr>
    <td width="15" class="TableSubHead">&nbsp;</td>
    <td class="TableSubHead" colspan="2"><b>* <?php echo $this->_tpl_vars['lng']['lbl_note']; ?>
:</b> <?php echo $this->_tpl_vars['lng']['txt_edit_product_group']; ?>
</td>
</tr>
<?php endif; ?>
<tr class="TableHead">
    <?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead">&nbsp;</td><?php endif; ?>
	<td width="10" class="DataTable">&nbsp;</td>
	<td><?php echo $this->_tpl_vars['lng']['lbl_options_combination']; ?>
</td>
</tr>
<?php $_from = $this->_tpl_vars['product_options_ex']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['o']):
?>
<tr<?php echo smarty_function_cycle(array('name' => 'exceptions','values' => ', class="TableSubHead"'), $this);?>
>
    <?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead" rowspan=""><input type="checkbox" value="Y" name="fields[exceptions][<?php echo $this->_tpl_vars['o']['exceptionid']; ?>
]" /></td><?php endif; ?>
	<td width="10" class="DataTable"><input type="checkbox" name="to_delete[<?php echo $this->_tpl_vars['k']; ?>
]" /></td>
	<td><?php $_from = $this->_tpl_vars['o']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
		<span style="white-space: nowrap;"><?php echo $this->_tpl_vars['v']['class']; ?>
:&nbsp;
		<?php $_from = $this->_tpl_vars['product_options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['c']):
?>
		<?php if ($this->_tpl_vars['c']['classid'] == $this->_tpl_vars['v']['classid']): ?>
			<?php $_from = $this->_tpl_vars['c']['options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['o']):
?>
			<?php if ($this->_tpl_vars['o']['optionid'] == $this->_tpl_vars['v']['optionid']):  echo $this->_tpl_vars['o']['option_name'];  endif; ?>
			<?php endforeach; endif; unset($_from); ?>
		<?php endif; ?>
		<?php endforeach; endif; unset($_from); ?>
		</span>&nbsp;&nbsp;
	<?php endforeach; endif; unset($_from); ?></td>
</tr>
<?php endforeach; else: ?>
<tr>
    <?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead">&nbsp;</td><?php endif; ?>
	<td colspan="2" align="center"><?php echo $this->_tpl_vars['lng']['lbl_exceptions_list_empty']; ?>
</td>
</tr>
<?php endif; unset($_from); ?>
<tr>
    <?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead">&nbsp;</td><?php endif; ?>
	<td>&nbsp;</td>
</tr>
<?php if ($this->_tpl_vars['product_options_ex'] != ''): ?>
<tr>
    <?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead">&nbsp;</td><?php endif; ?>
	<td>&nbsp;</td>
    <td><input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_delete_selected'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: document.exceptionform.mode.value='product_options_ex_delete'; document.exceptionform.submit();" /></td>
</tr>
<?php endif; ?>
<tr>
    <?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead">&nbsp;</td><?php endif; ?>
	<td>&nbsp;</td>
</tr>
<tr>
    <?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead">&nbsp;</td><?php endif; ?>
    <td class="TopLabel" colspan="2"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_add_exception'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>
<tr>
    <?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[new_exception]" /></td><?php endif; ?>
	<td colspan="2"><table>
	<?php $_from = $this->_tpl_vars['product_options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
	<?php if ($this->_tpl_vars['v']['options'] != ''): ?>
	<tr>
		<td><?php echo $this->_tpl_vars['v']['class']; ?>
:</td>
		<td><select name="new_exception[<?php echo $this->_tpl_vars['v']['classid']; ?>
]">
		<option value=""><?php echo $this->_tpl_vars['lng']['lbl_select_one_bracket']; ?>
</option>
		<?php $_from = $this->_tpl_vars['v']['options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['o']):
?>
		<option value='<?php echo $this->_tpl_vars['o']['optionid']; ?>
'><?php echo $this->_tpl_vars['o']['option_name']; ?>
</option>
		<?php endforeach; endif; unset($_from); ?>
		</select></td>
	</tr>
	<?php endif; ?>
	<?php endforeach; endif; unset($_from); ?>
	</table>
	</td>
</tr>
<tr>
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead">&nbsp;</td><?php endif; ?>
	<td colspan="2" class="TopLabel"><input type="submit" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_add_exception'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td>
</tr>
</table>
</form>
<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_product_option_exceptions'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<br />

<a name="js_code"></a>
<?php ob_start();  if ($this->_tpl_vars['config']['General']['display_all_products_on_1_page'] == 'Y'): ?><div align="right"><a href="#main"><?php echo $this->_tpl_vars['lng']['lbl_top']; ?>
</a></div><?php endif;  echo $this->_tpl_vars['lng']['txt_product_options_js_note']; ?>
<br />
<br />

<form action="<?php echo $this->_tpl_vars['script_name']; ?>
" method="post" name="validateform">
<input type="hidden" name="section" value="options" />
<input type="hidden" name="mode" value="product_options_js_update" />
<input type="hidden" name="productid" value="<?php echo $this->_tpl_vars['product']['productid']; ?>
" />
<input type="hidden" name="geid" value="<?php echo $this->_tpl_vars['geid']; ?>
" />
<table cellspacing="0" cellpadding="0" width="100%">
<?php if ($this->_tpl_vars['geid'] != ''): ?>
<tr>
    <td width="15" class="TableSubHead"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" width="15" height="1" border="0" /></td>
    <td class="TableSubHead"><b>* <?php echo $this->_tpl_vars['lng']['lbl_note']; ?>
:</b> <?php echo $this->_tpl_vars['lng']['txt_edit_product_group']; ?>
</td>
</tr>
<?php endif; ?>
<tr>
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[js]" /></td><?php endif; ?>
	<td><textarea name="js_code" cols="60" rows="15"><?php echo $this->_tpl_vars['product_options_js']; ?>
</textarea></td>
</tr>
<tr>
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead">&nbsp;</td><?php endif; ?>
	<td>&nbsp;</td>
</tr>
<tr>
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead">&nbsp;</td><?php endif; ?>
	<td><input type="submit" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_update'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td>
</tr>
</table>
</form>
<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  if ($this->_tpl_vars['config']['Product_Page']['show_js_valid_script'] == 'Y'):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_validation_script_javascript'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

<?php endif;  endif; ?>
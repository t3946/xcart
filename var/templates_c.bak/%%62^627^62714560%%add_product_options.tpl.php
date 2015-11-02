<?php /* Smarty version 2.6.12, created on 2011-10-11 06:53:29
         compiled from modules/Product_Options/add_product_options.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'replace', 'modules/Product_Options/add_product_options.tpl', 6, false),array('modifier', 'formatprice', 'modules/Product_Options/add_product_options.tpl', 136, false),array('modifier', 'strip_tags', 'modules/Product_Options/add_product_options.tpl', 145, false),array('modifier', 'escape', 'modules/Product_Options/add_product_options.tpl', 145, false),array('function', 'cycle', 'modules/Product_Options/add_product_options.tpl', 130, false),)), $this); ?>
<?php func_load_lang($this, "modules/Product_Options/add_product_options.tpl","lbl_option_group_name,lbl_option_text,lbl_top,lbl_back_to_option_groups_list,lbl_add_option,lbl_note,txt_edit_product_group,lbl_option_group_name,txt_option_group_name_note,lbl_option_text,txt_option_group_comment_note,lbl_option_group_type,lbl_modificator,lbl_variant,lbl_text_field,lbl_orderby,lbl_availability,lbl_enabled,lbl_disabled,lbl_options_list,txt_options_list_note,lbl_option_value,lbl_orderby,lbl_availability,lbl_option_surcharge,lbl_absolute,lbl_percent,lbl_delete_selected,lbl_add_option_value,lbl_absolute,lbl_percent,txt_text_field_note,lbl_add_option_group,lbl_update_option_group,lbl_add_option,lbl_update_option"); ?><?php if ($this->_tpl_vars['active_modules']['Product_Options'] != ""): ?>
<script type="text/javascript">
<!--
var requiredFieldsPO = new Array();
requiredFieldsPO[0] = new Array('add_class', '<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_option_group_name'])) ? $this->_run_mod_handler('replace', true, $_tmp, "'", "\'") : smarty_modifier_replace($_tmp, "'", "\'")); ?>
');
requiredFieldsPO[1] = new Array('add_classtext', '<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_option_text'])) ? $this->_run_mod_handler('replace', true, $_tmp, "'", "\'") : smarty_modifier_replace($_tmp, "'", "\'")); ?>
');

<?php echo '
function visibleTA(obj) {
	var objTA = document.getElementById(\'product_options_list\');
	if (!obj || !objTA)
		return false;

	objTA.disabled = (obj.options[obj.selectedIndex].value == \'T\');
}
'; ?>

-->
</script>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "check_required_fields_js.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  if ($this->_tpl_vars['script_name'] == ''):  $this->assign('script_name', "product_modify.php");  endif; ?>

<?php ob_start();  if ($this->_tpl_vars['config']['General']['display_all_products_on_1_page'] == 'Y'): ?><div align="right"><a href="#main"><?php echo $this->_tpl_vars['lng']['lbl_top']; ?>
</a></div><?php endif;  if ($this->_tpl_vars['product_options'] != ''): ?>
<table cellspacing="0" cellpadding="0">
<tr>
	<td class="ButtonsRow">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/button.tpl", 'smarty_include_vars' => array('href' => "product_modify.php?mode=return&section=options&productid=".($this->_tpl_vars['product']['productid']).($this->_tpl_vars['redirect_geid']),'button_title' => $this->_tpl_vars['lng']['lbl_back_to_option_groups_list'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	</td>
<?php if ($this->_tpl_vars['product_option'] != ''): ?>
	<td>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/button.tpl", 'smarty_include_vars' => array('href' => "product_modify.php?submode=product_options_add&section=options&productid=".($this->_tpl_vars['product']['productid']).($this->_tpl_vars['redirect_geid']),'button_title' => $this->_tpl_vars['lng']['lbl_add_option'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	</td>
<?php endif; ?>
</tr>
</table>
<?php endif; ?>
<form action="<?php echo $this->_tpl_vars['script_name']; ?>
" method="post" name="optionform" onsubmit="javascript: return checkRequired(requiredFieldsPO);">
<input type="hidden" name="section" value="options" />
<input type="hidden" name="mode" value="product_options_add" />
<input type="hidden" name="classid" value="<?php echo $this->_tpl_vars['product_option']['classid']; ?>
" />
<input type="hidden" name="productid" value="<?php echo $this->_tpl_vars['product']['productid']; ?>
" />
<input type="hidden" name="geid" value="<?php echo $this->_tpl_vars['geid']; ?>
" />

<?php if ($this->_tpl_vars['product_option'] != ''): ?>
<div align="right">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/language_selector.tpl", 'smarty_include_vars' => array('script' => ($this->_tpl_vars['script_name'])."?productid=".($this->_tpl_vars['product']['productid'])."&section=options&classid=".($this->_tpl_vars['product_option']['classid'])."&")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>
<?php endif; ?>

<table width="100%" cellspacing="0" cellpadding="3">
<?php if ($this->_tpl_vars['geid'] != ''): ?>
<tr>
    <td width="15" class="TableSubHead">&nbsp;</td>
    <td class="TableSubHead" colspan="7"><b>* <?php echo $this->_tpl_vars['lng']['lbl_note']; ?>
:</b> <?php echo $this->_tpl_vars['lng']['txt_edit_product_group']; ?>
</td>
</tr>
<?php endif; ?>
<tr>
<?php if ($this->_tpl_vars['geid'] != ''):  if ($this->_tpl_vars['product_option'] != ''): ?>
<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[class]" /></td>
<?php else: ?>
<td width="15" class="TableSubHead" rowspan="13"><input type="checkbox" value="Y" name="fields[new_class]" /></td>
<?php endif;  endif; ?>
	<td><b><?php echo $this->_tpl_vars['lng']['lbl_option_group_name']; ?>
:</b></td>
	<td><font class="Star">*</font></td>
	<td><input type="text" size="50" maxlength="128" id="add_class" name="add[class]" value="<?php echo $this->_tpl_vars['product_option']['class']; ?>
" /></td>
</tr>
<tr>
	<?php if ($this->_tpl_vars['geid'] != '' && $this->_tpl_vars['product_option'] != ''): ?><td width="15" class="TableSubHead">&nbsp;</td><?php endif; ?>
	<td colspan="3" class="DataField"><?php echo $this->_tpl_vars['lng']['txt_option_group_name_note']; ?>
</td>
</tr>
<tr>
	<?php if ($this->_tpl_vars['geid'] != '' && $this->_tpl_vars['product_option'] != ''): ?><td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[classtext]" /></td><?php endif; ?>
    <td><b><?php echo $this->_tpl_vars['lng']['lbl_option_text']; ?>
:</b></td>
	<td><font class="Star">*</font></td>
    <td><input type="text" size="50" maxlength="255" id="add_classtext" name="add[classtext]" value="<?php echo $this->_tpl_vars['product_option']['classtext']; ?>
" /></td> 
</tr>
<tr>
	<?php if ($this->_tpl_vars['geid'] != '' && $this->_tpl_vars['product_option'] != ''): ?><td width="15" class="TableSubHead">&nbsp;</td><?php endif; ?>
	<td colspan="3" class="DataField"><?php echo $this->_tpl_vars['lng']['txt_option_group_comment_note']; ?>
</td>
</tr>
<tr>
	<?php if ($this->_tpl_vars['geid'] != '' && $this->_tpl_vars['product_option'] != ''): ?><td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[is_modifier]" /></td><?php endif; ?>
    <td class="DataField"><b><?php echo $this->_tpl_vars['lng']['lbl_option_group_type']; ?>
:</b></td>
	<td>&nbsp;</td>
    <td class="DataField"><select name="add[is_modifier]"<?php if ($this->_tpl_vars['product_option'] == ''): ?> onchange="javascript: visibleTA(this);"<?php endif; ?>>
	<option value='Y'<?php if ($this->_tpl_vars['product_option']['is_modifier'] == 'Y'): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_modificator']; ?>
</option>
	<option value=''<?php if ($this->_tpl_vars['product_option']['is_modifier'] == '' && $this->_tpl_vars['product_option']['classid'] > 0): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_variant']; ?>
</option>
	<option value='T'<?php if ($this->_tpl_vars['product_option']['is_modifier'] == 'T' && $this->_tpl_vars['product_option']['classid'] > 0): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_text_field']; ?>
</option>
	</select></td> 
</tr>
<tr>
	<?php if ($this->_tpl_vars['geid'] != '' && $this->_tpl_vars['product_option'] != ''): ?><td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[orderby]" /></td><?php endif; ?>
    <td class="DataField"><b><?php echo $this->_tpl_vars['lng']['lbl_orderby']; ?>
:</b></td>
	<td>&nbsp;</td>
    <td class="DataField"><input type="text" size="5" maxlength="11" name="add[orderby]" value="<?php echo $this->_tpl_vars['product_option']['orderby']; ?>
" /></td> 
</tr>
<tr>
	<?php if ($this->_tpl_vars['geid'] != '' && $this->_tpl_vars['product_option'] != ''): ?><td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[avail]" /></td><?php endif; ?>
    <td class="DataField"><b><?php echo $this->_tpl_vars['lng']['lbl_availability']; ?>
:</b></td>
	<td>&nbsp;</td>
    <td class="DataField"><select name="add[avail]">
		<option value="Y"<?php if ($this->_tpl_vars['product_option']['avail'] == 'Y' || $this->_tpl_vars['product_option']['classid'] == ''): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_enabled']; ?>
</option>
		<option value=""<?php if ($this->_tpl_vars['product_option']['avail'] != 'Y' && $this->_tpl_vars['product_option'] != ''): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_disabled']; ?>
</option>
	</select></td>
</tr>
<tr>
	<?php if ($this->_tpl_vars['geid'] != '' && $this->_tpl_vars['product_option'] != ''): ?><td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[options]" /></td><?php endif; ?>
    <td valign="top"><b><?php echo $this->_tpl_vars['lng']['lbl_options_list']; ?>
:</b><?php if ($this->_tpl_vars['product_option'] == ''): ?><br /><?php echo $this->_tpl_vars['lng']['txt_options_list_note'];  endif; ?></td>
	<td>&nbsp;</td>
    <td valign="top">
	<?php if ($this->_tpl_vars['product_option'] == ''): ?> 
	<textarea name="list" cols="60" rows="10" id="product_options_list"></textarea>
	<?php elseif ($this->_tpl_vars['product_option']['is_modifier'] != 'T'): ?>
	<table>
	<tr class="TableHead">
		<td width="10">&nbsp;</td>
		<td><?php echo $this->_tpl_vars['lng']['lbl_option_value']; ?>
</td>
		<td><?php echo $this->_tpl_vars['lng']['lbl_orderby']; ?>
</td>
		<td><?php echo $this->_tpl_vars['lng']['lbl_availability']; ?>
</td>
<?php if ($this->_tpl_vars['product_option']['is_modifier'] == 'Y'): ?>
		<td nowrap="nowrap" colspan="2"><?php echo $this->_tpl_vars['lng']['lbl_option_surcharge']; ?>
</td>
<?php endif; ?>
	</tr>
	<?php if ($this->_tpl_vars['product_option']['options'] != ''): ?>
	<?php $_from = $this->_tpl_vars['product_option']['options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['o']):
?>
	<tr<?php echo smarty_function_cycle(array('name' => 'options','values' => ', class="TableSubHead"'), $this);?>
>
		<td><input type="checkbox" name="to_delete[<?php echo $this->_tpl_vars['o']['optionid']; ?>
]" value="Y" /></td>
		<td><input type="text" name="list[<?php echo $this->_tpl_vars['o']['optionid']; ?>
][option_name]" value="<?php echo $this->_tpl_vars['o']['option_name']; ?>
" /></td>
		<td><input type="text" name="list[<?php echo $this->_tpl_vars['o']['optionid']; ?>
][orderby]" size="5" maxlength="11" value="<?php echo $this->_tpl_vars['o']['orderby']; ?>
" /></td>
		<td align="center"><input type="checkbox" name="list[<?php echo $this->_tpl_vars['o']['optionid']; ?>
][avail]" value="Y"<?php if ($this->_tpl_vars['o']['avail'] == 'Y'): ?> checked="checked"<?php endif; ?> /></td>
<?php if ($this->_tpl_vars['product_option']['is_modifier'] == 'Y'): ?>
		<td><input type="text" name="list[<?php echo $this->_tpl_vars['o']['optionid']; ?>
][price_modifier]" size="5" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['o']['price_modifier'])) ? $this->_run_mod_handler('formatprice', true, $_tmp) : smarty_modifier_formatprice($_tmp)); ?>
" /></td>
		<td><select name="list[<?php echo $this->_tpl_vars['o']['optionid']; ?>
][modifier_type]">
		<option value="$"<?php if ($this->_tpl_vars['o']['modifier_type'] == '$'): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_absolute']; ?>
</option>
		<option value="%"<?php if ($this->_tpl_vars['o']['modifier_type'] == '%'): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_percent']; ?>
</option>
		</select></td>
<?php endif; ?>
	</tr>
	<?php endforeach; endif; unset($_from); ?>
	<tr>
		<td colspan="<?php if ($this->_tpl_vars['product_option']['is_modifier'] == 'Y'): ?>6<?php else: ?>4<?php endif; ?>"><input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_delete_selected'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: document.optionform.mode.value='product_option_delete'; document.optionform.submit();" /></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<?php endif; ?>
	<tr>
		<td class="TopLabel" colspan="<?php if ($this->_tpl_vars['product_option']['is_modifier'] == 'Y'): ?>6<?php else: ?>4<?php endif; ?>"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_add_option_value'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
	</tr>
	<tr>
		<td id="popt_box_1">&nbsp;</td>
		<td id="popt_box_2"><input type="text" name="new_list[option_name][0]" /></td>
		<td id="popt_box_3"><input type="text" name="new_list[orderby][0]" size="5" maxlength="11" /></td>
		<td align="center" id="popt_box_1"><input type="checkbox" name="new_list[avail][0]" value="Y" checked="checked" /></td>
<?php if ($this->_tpl_vars['product_option']['is_modifier'] == 'Y'): ?>
		<td id="popt_box_4"><input type="text" name="new_list[price_modifier][0]" size="5" value="<?php echo $this->_tpl_vars['zero']; ?>
" /></td>
		<td id="popt_box_5"><select name="new_list[modifier_type][0]">
		<option value="$" selected="selected"><?php echo $this->_tpl_vars['lng']['lbl_absolute']; ?>
</option>
		<option value="%"><?php echo $this->_tpl_vars['lng']['lbl_percent']; ?>
</option>
		</select></td>
<?php endif; ?>
		<td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/multirow_add.tpl", 'smarty_include_vars' => array('mark' => 'popt','is_lined' => true)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
	</tr>
	</table>
	<?php elseif ($this->_tpl_vars['product_option']['is_modifier'] == 'T'): ?>
	<font color="red"><?php echo $this->_tpl_vars['lng']['txt_text_field_note']; ?>
</font>
	<?php endif; ?>
	</td>
</tr>
</table>
<br />
<br />
<input type="submit" value="<?php if ($this->_tpl_vars['product_option'] == ''):  echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_add_option_group'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp));  else:  echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_update_option_group'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp));  endif; ?>" />
</form>
<?php if ($this->_tpl_vars['product_option'] == ''):  $this->assign('dialog_title', $this->_tpl_vars['lng']['lbl_add_option']);  else:  $this->assign('dialog_title', $this->_tpl_vars['lng']['lbl_update_option']);  endif;  $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['dialog_title'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php endif; ?>
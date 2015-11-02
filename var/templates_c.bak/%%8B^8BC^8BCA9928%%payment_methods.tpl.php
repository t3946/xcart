<?php /* Smarty version 2.6.12, created on 2011-10-11 07:03:54
         compiled from admin/main/payment_methods.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'admin/main/payment_methods.tpl', 35, false),array('modifier', 'escape', 'admin/main/payment_methods.tpl', 44, false),array('modifier', 'default', 'admin/main/payment_methods.tpl', 49, false),array('modifier', 'formatprice', 'admin/main/payment_methods.tpl', 49, false),array('modifier', 'substitute', 'admin/main/payment_methods.tpl', 108, false),array('modifier', 'strip_tags', 'admin/main/payment_methods.tpl', 126, false),)), $this); ?>
<?php func_load_lang($this, "admin/main/payment_methods.tpl","lbl_payment_methods,txt_payment_methods_top_text,lbl_methods,lbl_special_instructions,lbl_protocol,lbl_membership,lbl_check,lbl_pos,lbl_acc_proc,lbl_acc_percent,lbl_acc_per_trans,lbl_cod_extra_charge,lbl_cash_on_delivery_method,lbl_credit_card_processor,lbl_check_processor,lbl_ps_processor,lbl_configure,lbl_delete,txt_cc_processor_requirements_failed,txt_cc_processor_in_text_mode,lbl_update,txt_af_payment_method_note,lbl_payment_methods"); ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "page_title.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_payment_methods'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php echo $this->_tpl_vars['lng']['txt_payment_methods_top_text']; ?>


<br /><br />

<?php ob_start(); ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/language_selector.tpl", 'smarty_include_vars' => array('script' => "payment_methods.php?")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/check_all_row.tpl", 'smarty_include_vars' => array('style' => "line-height: 170%;",'form' => 'pmform','prefix' => "posted_data.+active")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<form action="payment_methods.php" method="post" name="pmform">
<input type="hidden" name="mode" value="update" />

<table cellpadding="5" cellspacing="1" width="100%">

<tr class="TableHead">
	<td>&nbsp;</td>
	<td width="25%"><?php echo $this->_tpl_vars['lng']['lbl_methods']; ?>
</td>
	<td width="15%" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_special_instructions']; ?>
</td>
	<td width="10%"><?php echo $this->_tpl_vars['lng']['lbl_protocol']; ?>
</td>
	<td width="5%"><?php echo $this->_tpl_vars['lng']['lbl_membership']; ?>
</td>
<?php if ($this->_tpl_vars['active_modules']['Anti_Fraud']): ?>
	<td width="10%" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_check']; ?>
*</td>
<?php endif; ?>
	<td width="5%"><?php echo $this->_tpl_vars['lng']['lbl_pos']; ?>
</td>
	<td width="10%"><?php echo $this->_tpl_vars['lng']['lbl_acc_proc']; ?>
</td>
	<td width="10%"><?php echo $this->_tpl_vars['lng']['lbl_acc_percent']; ?>
</td>
	<td width="10%"><?php echo $this->_tpl_vars['lng']['lbl_acc_per_trans']; ?>
</td>
</tr>

<?php unset($this->_sections['method']);
$this->_sections['method']['name'] = 'method';
$this->_sections['method']['loop'] = is_array($_loop=$this->_tpl_vars['payment_methods']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['method']['show'] = true;
$this->_sections['method']['max'] = $this->_sections['method']['loop'];
$this->_sections['method']['step'] = 1;
$this->_sections['method']['start'] = $this->_sections['method']['step'] > 0 ? 0 : $this->_sections['method']['loop']-1;
if ($this->_sections['method']['show']) {
    $this->_sections['method']['total'] = $this->_sections['method']['loop'];
    if ($this->_sections['method']['total'] == 0)
        $this->_sections['method']['show'] = false;
} else
    $this->_sections['method']['total'] = 0;
if ($this->_sections['method']['show']):

            for ($this->_sections['method']['index'] = $this->_sections['method']['start'], $this->_sections['method']['iteration'] = 1;
                 $this->_sections['method']['iteration'] <= $this->_sections['method']['total'];
                 $this->_sections['method']['index'] += $this->_sections['method']['step'], $this->_sections['method']['iteration']++):
$this->_sections['method']['rownum'] = $this->_sections['method']['iteration'];
$this->_sections['method']['index_prev'] = $this->_sections['method']['index'] - $this->_sections['method']['step'];
$this->_sections['method']['index_next'] = $this->_sections['method']['index'] + $this->_sections['method']['step'];
$this->_sections['method']['first']      = ($this->_sections['method']['iteration'] == 1);
$this->_sections['method']['last']       = ($this->_sections['method']['iteration'] == $this->_sections['method']['total']);
 echo smarty_function_cycle(array('values' => ', class="TableSubHead"','assign' => 'trcolor'), $this);?>


<?php if ($this->_tpl_vars['payment_methods'][$this->_sections['method']['index']]['disable_checkbox'] == 'Y'): ?><input type="hidden" name="posted_data[<?php echo $this->_tpl_vars['payment_methods'][$this->_sections['method']['index']]['paymentid']; ?>
][active]" value="Y" /><?php endif; ?>

<tr<?php echo $this->_tpl_vars['trcolor']; ?>
>
	<td valign="top"<?php if ($this->_tpl_vars['payment_methods'][$this->_sections['method']['index']]['module_name'] != ""): ?> rowspan="2"<?php endif; ?>>
	<input type="checkbox" name="posted_data[<?php echo $this->_tpl_vars['payment_methods'][$this->_sections['method']['index']]['paymentid']; ?>
][active]" value="Y"<?php if ($this->_tpl_vars['payment_methods'][$this->_sections['method']['index']]['active'] == 'Y'): ?> checked="checked"<?php endif;  if ($this->_tpl_vars['payment_methods'][$this->_sections['method']['index']]['disable_checkbox'] == 'Y'): ?> disabled="disabled"<?php endif; ?> />
	</td>
	<td valign="top">
	<input type="text" size="30" name="posted_data[<?php echo $this->_tpl_vars['payment_methods'][$this->_sections['method']['index']]['paymentid']; ?>
][payment_method]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['payment_methods'][$this->_sections['method']['index']]['payment_method'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" />
<br />
<table cellpadding="1" cellspacing="0">
<tr>
	<td class="FormButton"><?php echo $this->_tpl_vars['lng']['lbl_cod_extra_charge']; ?>
:</td>
	<td><input type="text" size="8" name="posted_data[<?php echo $this->_tpl_vars['payment_methods'][$this->_sections['method']['index']]['paymentid']; ?>
][surcharge]" value="<?php echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['payment_methods'][$this->_sections['method']['index']]['surcharge'])) ? $this->_run_mod_handler('default', true, $_tmp, '0') : smarty_modifier_default($_tmp, '0')))) ? $this->_run_mod_handler('formatprice', true, $_tmp) : smarty_modifier_formatprice($_tmp)); ?>
" /></td>
	<td>
	<select name="posted_data[<?php echo $this->_tpl_vars['payment_methods'][$this->_sections['method']['index']]['paymentid']; ?>
][surcharge_type]">
		<option value="%"<?php if ($this->_tpl_vars['payment_methods'][$this->_sections['method']['index']]['surcharge_type'] == "%"): ?> selected="selected"<?php endif; ?>>%</option>
		<option value="$"<?php if ($this->_tpl_vars['payment_methods'][$this->_sections['method']['index']]['surcharge_type'] == "$"): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['config']['General']['currency_symbol']; ?>
</option>
	</select>
	</td>
</tr>
</table>
<?php if ($this->_tpl_vars['payment_methods'][$this->_sections['method']['index']]['processor_file'] == ""): ?>
<table cellpadding="1" cellspacing="0">
<tr>
	<td><input type="checkbox" id="is_cod_<?php echo $this->_tpl_vars['payment_methods'][$this->_sections['method']['index']]['paymentid']; ?>
" name="posted_data[<?php echo $this->_tpl_vars['payment_methods'][$this->_sections['method']['index']]['paymentid']; ?>
][is_cod]" value="Y"<?php if ($this->_tpl_vars['payment_methods'][$this->_sections['method']['index']]['is_cod'] == 'Y'): ?> checked="checked"<?php endif; ?> /></td>
	<td class="FormButton"><label for="is_cod_<?php echo $this->_tpl_vars['payment_methods'][$this->_sections['method']['index']]['paymentid']; ?>
"><?php echo $this->_tpl_vars['lng']['lbl_cash_on_delivery_method']; ?>
</label></td>
</tr>
</table>
<?php endif; ?>
	</td>
	<td valign="top" nowrap="nowrap">
	<textarea name="posted_data[<?php echo $this->_tpl_vars['payment_methods'][$this->_sections['method']['index']]['paymentid']; ?>
][payment_details]" cols="30" rows="3"><?php echo ((is_array($_tmp=$this->_tpl_vars['payment_methods'][$this->_sections['method']['index']]['payment_details'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</textarea>
	</td>
	<td valign="top">
	<select name="posted_data[<?php echo $this->_tpl_vars['payment_methods'][$this->_sections['method']['index']]['paymentid']; ?>
][protocol]" style="width:100%">
		<option value="http"<?php if ($this->_tpl_vars['payment_methods'][$this->_sections['method']['index']]['protocol'] == 'http'): ?> selected="selected"<?php endif; ?>>HTTP</option>
		<option value="https"<?php if ($this->_tpl_vars['payment_methods'][$this->_sections['method']['index']]['protocol'] == 'https'): ?> selected="selected"<?php endif; ?>>HTTPS</option>
	</select>
	</td>
	<td valign="top"<?php if ($this->_tpl_vars['payment_methods'][$this->_sections['method']['index']]['module_name'] != ""): ?> rowspan="2"<?php endif; ?>>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/membership_selector.tpl", 'smarty_include_vars' => array('field' => "posted_data[".($this->_tpl_vars['payment_methods'][$this->_sections['method']['index']]['paymentid'])."][membershipids][]",'data' => $this->_tpl_vars['payment_methods'][$this->_sections['method']['index']],'is_short' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	</td>
<?php if ($this->_tpl_vars['active_modules']['Anti_Fraud']): ?>
	<td valign="top"<?php if ($this->_tpl_vars['payment_methods'][$this->_sections['method']['index']]['module_name'] != ""): ?> rowspan="2"<?php endif; ?>>
	<input type="checkbox" name="posted_data[<?php echo $this->_tpl_vars['payment_methods'][$this->_sections['method']['index']]['paymentid']; ?>
][af_check]" value="Y"<?php if ($this->_tpl_vars['payment_methods'][$this->_sections['method']['index']]['af_check'] == 'Y'): ?> checked="checked"<?php endif; ?> />
	</td>
<?php endif; ?>
	<td valign="top"<?php if ($this->_tpl_vars['payment_methods'][$this->_sections['method']['index']]['module_name'] != ""): ?> rowspan="2"<?php endif; ?>>
	<input type="text" size="3" maxlength="5" name="posted_data[<?php echo $this->_tpl_vars['payment_methods'][$this->_sections['method']['index']]['paymentid']; ?>
][orderby]" value="<?php echo $this->_tpl_vars['payment_methods'][$this->_sections['method']['index']]['orderby']; ?>
" />
	</td>
	<td valign="top"<?php if ($this->_tpl_vars['payment_methods'][$this->_sections['method']['index']]['module_name'] != ""): ?> rowspan="2"<?php endif; ?>>
	<input type="checkbox" name="posted_data[<?php echo $this->_tpl_vars['payment_methods'][$this->_sections['method']['index']]['paymentid']; ?>
][acc_proc]" value="Y"<?php if ($this->_tpl_vars['payment_methods'][$this->_sections['method']['index']]['acc_proc'] == 'Y'): ?> checked="checked"<?php endif; ?> />
	</td>
	<td valign="top"<?php if ($this->_tpl_vars['payment_methods'][$this->_sections['method']['index']]['module_name'] != ""): ?> rowspan="2"<?php endif; ?>>
	<input type="text" size="4" name="posted_data[<?php echo $this->_tpl_vars['payment_methods'][$this->_sections['method']['index']]['paymentid']; ?>
][acc_percent]" value="<?php echo $this->_tpl_vars['payment_methods'][$this->_sections['method']['index']]['acc_percent']; ?>
" />
	</td>
	<td valign="top"<?php if ($this->_tpl_vars['payment_methods'][$this->_sections['method']['index']]['module_name'] != ""): ?> rowspan="2"<?php endif; ?>>
	<input type="text" size="4" name="posted_data[<?php echo $this->_tpl_vars['payment_methods'][$this->_sections['method']['index']]['paymentid']; ?>
][acc_per_trans]" value="<?php echo $this->_tpl_vars['payment_methods'][$this->_sections['method']['index']]['acc_per_trans']; ?>
" />
	</td>
</tr>

<?php if ($this->_tpl_vars['payment_methods'][$this->_sections['method']['index']]['module_name'] != ""): ?>
<tr<?php echo $this->_tpl_vars['trcolor']; ?>
>
	<td colspan="3" valign="bottom">
<?php if ($this->_tpl_vars['payment_methods'][$this->_sections['method']['index']]['type'] == 'C'):  echo $this->_tpl_vars['lng']['lbl_credit_card_processor'];  elseif ($this->_tpl_vars['payment_methods'][$this->_sections['method']['index']]['type'] == 'H'):  echo $this->_tpl_vars['lng']['lbl_check_processor'];  else:  $this->assign('type', 'ps');  echo $this->_tpl_vars['lng']['lbl_ps_processor'];  endif; ?> <b><?php echo $this->_tpl_vars['payment_methods'][$this->_sections['method']['index']]['module_name']; ?>
</b>:
<a href="cc_processing.php?mode=update&amp;cc_processor=<?php echo $this->_tpl_vars['payment_methods'][$this->_sections['method']['index']]['processor']; ?>
"><?php echo $this->_tpl_vars['lng']['lbl_configure']; ?>
</a> | <a href="cc_processing.php?mode=delete&amp;paymentid=<?php echo $this->_tpl_vars['payment_methods'][$this->_sections['method']['index']]['paymentid']; ?>
"><?php echo $this->_tpl_vars['lng']['lbl_delete']; ?>
</a>
<?php if ($this->_tpl_vars['payment_methods'][$this->_sections['method']['index']]['is_down'] || $this->_tpl_vars['payment_methods'][$this->_sections['method']['index']]['in_testmode']): ?>
<table cellpadding="2">
<?php if ($this->_tpl_vars['payment_methods'][$this->_sections['method']['index']]['is_down']): ?>
<tr>
	<td><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/log_type_Warning.gif" alt="" /></td>
	<td><font class="AdminSmallMessage"><?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['txt_cc_processor_requirements_failed'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'processor', $this->_tpl_vars['payment_methods'][$this->_sections['method']['index']]['module_name']) : smarty_modifier_substitute($_tmp, 'processor', $this->_tpl_vars['payment_methods'][$this->_sections['method']['index']]['module_name'])); ?>
</font></td>
</tr>
<?php endif;  if ($this->_tpl_vars['payment_methods'][$this->_sections['method']['index']]['in_testmode']): ?>
<tr>
	<td><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/log_type_Warning.gif" alt="" /></td>
	<td><font class="AdminSmallMessage"><?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['txt_cc_processor_in_text_mode'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'processor', $this->_tpl_vars['payment_methods'][$this->_sections['method']['index']]['module_name']) : smarty_modifier_substitute($_tmp, 'processor', $this->_tpl_vars['payment_methods'][$this->_sections['method']['index']]['module_name'])); ?>
</font></td>
</tr>
<?php endif; ?>
</table>
<?php endif; ?>	</td>
</tr>
<?php endif; ?>

<?php endfor; endif; ?>

<tr>
	<td align="center" colspan="<?php if ($this->_tpl_vars['active_modules']['Anti_Fraud']): ?>10<?php else: ?>9<?php endif; ?>" class="SubmitBox"><input type="submit" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_update'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td>
</tr>

<?php if ($this->_tpl_vars['active_modules']['Anti_Fraud']): ?>
<tr>
	<td colspan="7">&nbsp;</td>
</tr>
<tr>
	<td colspan="7">*) <?php echo $this->_tpl_vars['lng']['txt_af_payment_method_note']; ?>
</td>
</tr>
<?php endif; ?>

</table>
</form>

<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_payment_methods'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<br /><br /><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/main/cc_processing.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
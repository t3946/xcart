<?php /* Smarty version 2.6.12, created on 2011-10-11 07:03:55
         compiled from admin/main/cc_processing.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'substitute', 'admin/main/cc_processing.tpl', 62, false),)), $this); ?>
<?php func_load_lang($this, "admin/main/cc_processing.tpl","txt_cc_ach_processing_top_text,txt_credit_card_processor_note,lbl_payment_gateways,lbl_select,lbl_credit_card_processor,lbl_check_processor,lbl_direct_debit_processor,lbl_ps_processor,lbl_add,txt_subscription_processor_note,lbl_subscription_processor,lbl_manual_processing,txt_cc_processor_requirements_failed,txt_cc_processor_in_text_mode,lbl_payment_gateways"); ?>
<?php echo $this->_tpl_vars['lng']['txt_cc_ach_processing_top_text']; ?>


<br /><br />

<?php ob_start(); ?>
<form action="cc_processing.php" method="get" name="myform">
<input type="hidden" name="mode" value="add" />
<input type="hidden" name="subscribe" value="" />

<table cellpadding="2" cellspacing="1" width="100%">

<tr><td colspan="3"><?php echo $this->_tpl_vars['lng']['txt_credit_card_processor_note']; ?>
</td></tr>

<tr>
	<td nowrap="nowrap"><b><?php echo $this->_tpl_vars['lng']['lbl_payment_gateways']; ?>
</b></td>
	<td align="center" width="100%">
	<select name="processor" style="width: 100%;">
<?php $this->assign('type', ""); ?>
		<option value=""><?php echo $this->_tpl_vars['lng']['lbl_select']; ?>
...</option>
<?php unset($this->_sections['module']);
$this->_sections['module']['name'] = 'module';
$this->_sections['module']['loop'] = is_array($_loop=$this->_tpl_vars['cc_modules']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['module']['show'] = true;
$this->_sections['module']['max'] = $this->_sections['module']['loop'];
$this->_sections['module']['step'] = 1;
$this->_sections['module']['start'] = $this->_sections['module']['step'] > 0 ? 0 : $this->_sections['module']['loop']-1;
if ($this->_sections['module']['show']) {
    $this->_sections['module']['total'] = $this->_sections['module']['loop'];
    if ($this->_sections['module']['total'] == 0)
        $this->_sections['module']['show'] = false;
} else
    $this->_sections['module']['total'] = 0;
if ($this->_sections['module']['show']):

            for ($this->_sections['module']['index'] = $this->_sections['module']['start'], $this->_sections['module']['iteration'] = 1;
                 $this->_sections['module']['iteration'] <= $this->_sections['module']['total'];
                 $this->_sections['module']['index'] += $this->_sections['module']['step'], $this->_sections['module']['iteration']++):
$this->_sections['module']['rownum'] = $this->_sections['module']['iteration'];
$this->_sections['module']['index_prev'] = $this->_sections['module']['index'] - $this->_sections['module']['step'];
$this->_sections['module']['index_next'] = $this->_sections['module']['index'] + $this->_sections['module']['step'];
$this->_sections['module']['first']      = ($this->_sections['module']['iteration'] == 1);
$this->_sections['module']['last']       = ($this->_sections['module']['iteration'] == $this->_sections['module']['total']);
 if ($this->_tpl_vars['cc_modules'][$this->_sections['module']['index']]['type'] == 'C' && $this->_tpl_vars['cc_modules'][$this->_sections['module']['index']]['type'] != $this->_tpl_vars['type']):  if ($this->_tpl_vars['type'] != ""): ?></optgroup><?php endif;  $this->assign('type', 'C'); ?>
		<optgroup label="--- <?php echo $this->_tpl_vars['lng']['lbl_credit_card_processor']; ?>
 ---">
<?php elseif ($this->_tpl_vars['cc_modules'][$this->_sections['module']['index']]['type'] == 'H' && $this->_tpl_vars['cc_modules'][$this->_sections['module']['index']]['type'] != $this->_tpl_vars['type']):  if ($this->_tpl_vars['type'] != ""): ?></optgroup><?php endif;  $this->assign('type', 'H'); ?>
		<optgroup label="--- <?php echo $this->_tpl_vars['lng']['lbl_check_processor']; ?>
 ---">
<?php elseif ($this->_tpl_vars['cc_modules'][$this->_sections['module']['index']]['type'] == 'D' && $this->_tpl_vars['cc_modules'][$this->_sections['module']['index']]['type'] != $this->_tpl_vars['type']):  if ($this->_tpl_vars['type'] != ""): ?></optgroup><?php endif;  $this->assign('type', 'D'); ?>
		<optgroup label="--- <?php echo $this->_tpl_vars['lng']['lbl_direct_debit_processor']; ?>
 ---">
<?php elseif ($this->_tpl_vars['cc_modules'][$this->_sections['module']['index']]['type'] == 'P' && $this->_tpl_vars['cc_modules'][$this->_sections['module']['index']]['type'] != $this->_tpl_vars['type']):  if ($this->_tpl_vars['type'] != ""): ?></optgroup><?php endif;  $this->assign('type', 'P'); ?>
		<optgroup label="--- <?php echo $this->_tpl_vars['lng']['lbl_ps_processor']; ?>
 ---">
<?php endif; ?>
		<option value="<?php echo $this->_tpl_vars['cc_modules'][$this->_sections['module']['index']]['processor']; ?>
"><?php echo $this->_tpl_vars['cc_modules'][$this->_sections['module']['index']]['module_name']; ?>
</option>
<?php endfor; endif; ?>
		</optgroup>
	</select>
</td>
	<td nowrap="nowrap"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/button.tpl", 'smarty_include_vars' => array('button_title' => $this->_tpl_vars['lng']['lbl_add'],'href' => "javascript: if (document.myform.processor.selectedIndex > 0) document.myform.submit();",'js_to_href' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>

<?php if ($this->_tpl_vars['active_modules']['Subscriptions'] != ""): ?>

<tr><td colspan="3"><br /><br /><?php echo $this->_tpl_vars['lng']['txt_subscription_processor_note']; ?>
</td></tr>

<tr>
	<td nowrap="nowrap"><b><?php echo $this->_tpl_vars['lng']['lbl_subscription_processor']; ?>
</b></td>
	<td align="center">
	<select name="cc_processor" style="width:100%">
		<option value=""<?php if ($this->_tpl_vars['config']['active_subscriptions_processor'] == ""): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_manual_processing']; ?>
</option>
<?php unset($this->_sections['module']);
$this->_sections['module']['name'] = 'module';
$this->_sections['module']['loop'] = is_array($_loop=$this->_tpl_vars['sb_modules']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['module']['show'] = true;
$this->_sections['module']['max'] = $this->_sections['module']['loop'];
$this->_sections['module']['step'] = 1;
$this->_sections['module']['start'] = $this->_sections['module']['step'] > 0 ? 0 : $this->_sections['module']['loop']-1;
if ($this->_sections['module']['show']) {
    $this->_sections['module']['total'] = $this->_sections['module']['loop'];
    if ($this->_sections['module']['total'] == 0)
        $this->_sections['module']['show'] = false;
} else
    $this->_sections['module']['total'] = 0;
if ($this->_sections['module']['show']):

            for ($this->_sections['module']['index'] = $this->_sections['module']['start'], $this->_sections['module']['iteration'] = 1;
                 $this->_sections['module']['iteration'] <= $this->_sections['module']['total'];
                 $this->_sections['module']['index'] += $this->_sections['module']['step'], $this->_sections['module']['iteration']++):
$this->_sections['module']['rownum'] = $this->_sections['module']['iteration'];
$this->_sections['module']['index_prev'] = $this->_sections['module']['index'] - $this->_sections['module']['step'];
$this->_sections['module']['index_next'] = $this->_sections['module']['index'] + $this->_sections['module']['step'];
$this->_sections['module']['first']      = ($this->_sections['module']['iteration'] == 1);
$this->_sections['module']['last']       = ($this->_sections['module']['iteration'] == $this->_sections['module']['total']);
?>
		<option value="<?php echo $this->_tpl_vars['sb_modules'][$this->_sections['module']['index']]['processor']; ?>
"<?php if ($this->_tpl_vars['config']['active_subscriptions_processor'] == $this->_tpl_vars['sb_modules'][$this->_sections['module']['index']]['processor']): ?> selected="selected"<?php $this->assign('active_subscriptions_processor', $this->_tpl_vars['sb_modules'][$this->_sections['module']['index']]['module_name']);  endif; ?>><?php echo $this->_tpl_vars['sb_modules'][$this->_sections['module']['index']]['module_name']; ?>
</option>
<?php endfor; endif; ?>
	</select>
	</td>
	<td nowrap="nowrap"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/continue.tpl", 'smarty_include_vars' => array('href' => "javascript: document.myform.mode.value='update'; document.myform.subscribe.value='yes'; document.myform.submit();",'js_to_href' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>
<?php if ($this->_tpl_vars['active_sb']['status'] != '1' || $this->_tpl_vars['active_sb']['in_testmode']): ?>
<tr><td colspan="3">
<?php if ($this->_tpl_vars['active_sb']['status'] != '1'): ?><font class="AdminSmallMessage"><?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['txt_cc_processor_requirements_failed'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'processor', $this->_tpl_vars['active_subscriptions_processor']) : smarty_modifier_substitute($_tmp, 'processor', $this->_tpl_vars['active_subscriptions_processor'])); ?>
</font><?php endif;  if ($this->_tpl_vars['active_sb']['in_testmode']): ?><font class="AdminSmallMessage"><?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['txt_cc_processor_in_text_mode'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'processor', $this->_tpl_vars['active_subscriptions_processor']) : smarty_modifier_substitute($_tmp, 'processor', $this->_tpl_vars['active_subscriptions_processor'])); ?>
</font><?php endif; ?>
</td></tr>
<?php endif; ?>

<?php endif; ?>

</table>
</form>

<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_payment_gateways'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
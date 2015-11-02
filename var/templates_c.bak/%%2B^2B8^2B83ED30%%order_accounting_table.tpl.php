<?php /* Smarty version 2.6.12, created on 2011-10-11 06:30:30
         compiled from main/order_accounting_table.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'price_format', 'main/order_accounting_table.tpl', 78, false),array('modifier', 'date_format', 'main/order_accounting_table.tpl', 182, false),array('function', 'cycle', 'main/order_accounting_table.tpl', 124, false),)), $this); ?>
<?php func_load_lang($this, "main/order_accounting_table.tpl","lbl_status,lbl_net,lbl_processor,lbl_net,lbl_cost_to_us,lbl_shipping,lbl_ref_to_cust,lbl_ref_to_us,lbl_profit,lbl_profit,lbl_distr,lbl_customer,lbl_gst_in,lbl_payment,lbl_gst_in,lbl_gst_out,lbl_gst_out,lbl_gst_out,lbl_gst_in,lbl_gst_in,lbl_margin,lbl_pst_in,lbl_date,lbl_pst_in,lbl_pst_out,lbl_pst_out,lbl_pst_out,lbl_pst_in,lbl_pst_in,lbl_gross,lbl_time,lbl_gross,lbl_cost_to_us,lbl_shipping,lbl_ref_to_cust,lbl_ref_to_us,lbl_profit,lbl_report_word,lbl_totals_word"); ?><?php if ($this->_tpl_vars['cycle_state'] == ""): ?>
<table cellpadding="3" cellspacing="1" class="OrderSheet">
<?php endif;  if ($this->_tpl_vars['cycle_state'] == "" || $this->_tpl_vars['cycle_state'] == 'first'): ?>
<tr class="TableHead TableHeadAccounting">
  <?php if ($this->_tpl_vars['static'] == 'Y' || $this->_tpl_vars['static'] == 'O'): ?><td width="5"></td><?php endif; ?>
  <td width="5%">#</td>
  <td width="9%"><?php echo $this->_tpl_vars['lng']['lbl_status']; ?>
</td>
  <td width="9%"><?php echo $this->_tpl_vars['lng']['lbl_net']; ?>
</td>
  <td width="9%"><?php echo $this->_tpl_vars['lng']['lbl_processor']; ?>
</td>
  <td width="9%"><?php echo $this->_tpl_vars['lng']['lbl_net']; ?>
</td>
  <td width="9%"><?php echo $this->_tpl_vars['lng']['lbl_cost_to_us']; ?>
</td>
  <td width="9%"><?php echo $this->_tpl_vars['lng']['lbl_shipping']; ?>
</td>
  <td width="9%"><?php echo $this->_tpl_vars['lng']['lbl_ref_to_cust']; ?>
</td>
  <td width="9%"><?php echo $this->_tpl_vars['lng']['lbl_ref_to_us']; ?>
</td>
  <td width="9%"><?php echo $this->_tpl_vars['lng']['lbl_profit']; ?>
</td>
  <td width="9%"><?php echo $this->_tpl_vars['lng']['lbl_profit']; ?>
</td>
</tr>
<tr class="TableHead TableHeadAccounting TableHeadLight">
  <?php if ($this->_tpl_vars['static'] == 'Y' || $this->_tpl_vars['static'] == 'O'): ?><td width="5"></td><?php endif; ?>
  <td><?php echo $this->_tpl_vars['lng']['lbl_distr']; ?>
</td>
  <td><?php echo $this->_tpl_vars['lng']['lbl_customer']; ?>
</td>
  <td><?php echo $this->_tpl_vars['lng']['lbl_gst_in']; ?>
</td>
  <td><?php echo $this->_tpl_vars['lng']['lbl_payment']; ?>
</td>
  <td><?php echo $this->_tpl_vars['lng']['lbl_gst_in']; ?>
</td>
  <td><?php echo $this->_tpl_vars['lng']['lbl_gst_out']; ?>
</td>
  <td><?php echo $this->_tpl_vars['lng']['lbl_gst_out']; ?>
</td>
  <td><?php echo $this->_tpl_vars['lng']['lbl_gst_out']; ?>
</td>
  <td><?php echo $this->_tpl_vars['lng']['lbl_gst_in']; ?>
</td>
  <td><?php echo $this->_tpl_vars['lng']['lbl_gst_in']; ?>
</td>
  <td><strong><?php echo $this->_tpl_vars['lng']['lbl_margin']; ?>
</strong></td>
</tr>
<tr class="TableHead TableHeadAccounting TableHeadLight">
  <?php if ($this->_tpl_vars['static'] == 'Y' || $this->_tpl_vars['static'] == 'O'): ?><td width="5"></td><?php endif; ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><?php echo $this->_tpl_vars['lng']['lbl_pst_in']; ?>
</td>
  <td><?php echo $this->_tpl_vars['lng']['lbl_date']; ?>
</td>
  <td><?php echo $this->_tpl_vars['lng']['lbl_pst_in']; ?>
</td>
  <td><?php echo $this->_tpl_vars['lng']['lbl_pst_out']; ?>
</td>
  <td><?php echo $this->_tpl_vars['lng']['lbl_pst_out']; ?>
</td>
  <td><?php echo $this->_tpl_vars['lng']['lbl_pst_out']; ?>
</td>
  <td><?php echo $this->_tpl_vars['lng']['lbl_pst_in']; ?>
</td>
  <td><?php echo $this->_tpl_vars['lng']['lbl_pst_in']; ?>
</td>
  <td>&nbsp;</td>
</tr>
<tr class="TableHead TableHeadAccounting TableHeadLight">
  <?php if ($this->_tpl_vars['static'] == 'Y' || $this->_tpl_vars['static'] == 'O'): ?><td width="5"></td><?php endif; ?>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td><?php echo $this->_tpl_vars['lng']['lbl_gross']; ?>
</td>
  <td><?php echo $this->_tpl_vars['lng']['lbl_time']; ?>
</td>
  <td><?php echo $this->_tpl_vars['lng']['lbl_gross']; ?>
</td>
  <td><?php echo $this->_tpl_vars['lng']['lbl_cost_to_us']; ?>
</td>
  <td><?php echo $this->_tpl_vars['lng']['lbl_shipping']; ?>
</td>
  <td><?php echo $this->_tpl_vars['lng']['lbl_ref_to_cust']; ?>
</td>
  <td><?php echo $this->_tpl_vars['lng']['lbl_ref_to_us']; ?>
</td>
  <td><?php echo $this->_tpl_vars['lng']['lbl_profit']; ?>
</td>
  <td>&nbsp;</td>
</tr>
<?php if ($this->_tpl_vars['static'] == 'R'): ?>
<tr class="OrderSheetCell OrderSheetFirst">
<td colspan="11">&nbsp;</td>
</tr>
<tr class="OrderSheetCell OrderSheetFirst" style="font-weight: bold;">
  <td></td>
  <td></td>
  <td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency2.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['data']['total']['net'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
  <td></td>
  <?php unset($this->_sections['acc']);
$this->_sections['acc']['loop'] = is_array($_loop=5) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['acc']['name'] = 'acc';
$this->_sections['acc']['show'] = true;
$this->_sections['acc']['max'] = $this->_sections['acc']['loop'];
$this->_sections['acc']['step'] = 1;
$this->_sections['acc']['start'] = $this->_sections['acc']['step'] > 0 ? 0 : $this->_sections['acc']['loop']-1;
if ($this->_sections['acc']['show']) {
    $this->_sections['acc']['total'] = $this->_sections['acc']['loop'];
    if ($this->_sections['acc']['total'] == 0)
        $this->_sections['acc']['show'] = false;
} else
    $this->_sections['acc']['total'] = 0;
if ($this->_sections['acc']['show']):

            for ($this->_sections['acc']['index'] = $this->_sections['acc']['start'], $this->_sections['acc']['iteration'] = 1;
                 $this->_sections['acc']['iteration'] <= $this->_sections['acc']['total'];
                 $this->_sections['acc']['index'] += $this->_sections['acc']['step'], $this->_sections['acc']['iteration']++):
$this->_sections['acc']['rownum'] = $this->_sections['acc']['iteration'];
$this->_sections['acc']['index_prev'] = $this->_sections['acc']['index'] - $this->_sections['acc']['step'];
$this->_sections['acc']['index_next'] = $this->_sections['acc']['index'] + $this->_sections['acc']['step'];
$this->_sections['acc']['first']      = ($this->_sections['acc']['iteration'] == 1);
$this->_sections['acc']['last']       = ($this->_sections['acc']['iteration'] == $this->_sections['acc']['total']);
?>
  <td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency2.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['data']['total_accounting'][$this->_sections['acc']['index']]['net'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
  <?php endfor; endif; ?>
  <td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency2.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['data']['total_accounting'][5]['net'],'show_minus_brackets' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
  <td><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['total_margin'])) ? $this->_run_mod_handler('price_format', true, $_tmp) : price_format($_tmp)); ?>
%</td>
</tr>
<tr class="OrderSheetCell">
  <td></td>
  <td><strong><?php echo $this->_tpl_vars['lng']['lbl_report_word']; ?>
</strong></td>
  <td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency2.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['data']['total']['gst'],'hide_zero' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
  <td></td>
  <?php unset($this->_sections['acc']);
$this->_sections['acc']['loop'] = is_array($_loop=5) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['acc']['name'] = 'acc';
$this->_sections['acc']['show'] = true;
$this->_sections['acc']['max'] = $this->_sections['acc']['loop'];
$this->_sections['acc']['step'] = 1;
$this->_sections['acc']['start'] = $this->_sections['acc']['step'] > 0 ? 0 : $this->_sections['acc']['loop']-1;
if ($this->_sections['acc']['show']) {
    $this->_sections['acc']['total'] = $this->_sections['acc']['loop'];
    if ($this->_sections['acc']['total'] == 0)
        $this->_sections['acc']['show'] = false;
} else
    $this->_sections['acc']['total'] = 0;
if ($this->_sections['acc']['show']):

            for ($this->_sections['acc']['index'] = $this->_sections['acc']['start'], $this->_sections['acc']['iteration'] = 1;
                 $this->_sections['acc']['iteration'] <= $this->_sections['acc']['total'];
                 $this->_sections['acc']['index'] += $this->_sections['acc']['step'], $this->_sections['acc']['iteration']++):
$this->_sections['acc']['rownum'] = $this->_sections['acc']['iteration'];
$this->_sections['acc']['index_prev'] = $this->_sections['acc']['index'] - $this->_sections['acc']['step'];
$this->_sections['acc']['index_next'] = $this->_sections['acc']['index'] + $this->_sections['acc']['step'];
$this->_sections['acc']['first']      = ($this->_sections['acc']['iteration'] == 1);
$this->_sections['acc']['last']       = ($this->_sections['acc']['iteration'] == $this->_sections['acc']['total']);
?>
  <td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency2.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['data']['total_accounting'][$this->_sections['acc']['index']]['gst'],'hide_zero' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
  <?php endfor; endif; ?>
  <td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency2.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['data']['total_accounting'][5]['gst'],'show_minus_brackets' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
  <td></td>
</tr>
<tr class="OrderSheetCell">
  <td></td>
  <td><strong><?php echo $this->_tpl_vars['lng']['lbl_totals_word']; ?>
:</strong></td>
  <td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency2.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['data']['total']['pst'],'hide_zero' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
  <td></td>
  <?php unset($this->_sections['acc']);
$this->_sections['acc']['loop'] = is_array($_loop=5) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['acc']['name'] = 'acc';
$this->_sections['acc']['show'] = true;
$this->_sections['acc']['max'] = $this->_sections['acc']['loop'];
$this->_sections['acc']['step'] = 1;
$this->_sections['acc']['start'] = $this->_sections['acc']['step'] > 0 ? 0 : $this->_sections['acc']['loop']-1;
if ($this->_sections['acc']['show']) {
    $this->_sections['acc']['total'] = $this->_sections['acc']['loop'];
    if ($this->_sections['acc']['total'] == 0)
        $this->_sections['acc']['show'] = false;
} else
    $this->_sections['acc']['total'] = 0;
if ($this->_sections['acc']['show']):

            for ($this->_sections['acc']['index'] = $this->_sections['acc']['start'], $this->_sections['acc']['iteration'] = 1;
                 $this->_sections['acc']['iteration'] <= $this->_sections['acc']['total'];
                 $this->_sections['acc']['index'] += $this->_sections['acc']['step'], $this->_sections['acc']['iteration']++):
$this->_sections['acc']['rownum'] = $this->_sections['acc']['iteration'];
$this->_sections['acc']['index_prev'] = $this->_sections['acc']['index'] - $this->_sections['acc']['step'];
$this->_sections['acc']['index_next'] = $this->_sections['acc']['index'] + $this->_sections['acc']['step'];
$this->_sections['acc']['first']      = ($this->_sections['acc']['iteration'] == 1);
$this->_sections['acc']['last']       = ($this->_sections['acc']['iteration'] == $this->_sections['acc']['total']);
?>
  <td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency2.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['data']['total_accounting'][$this->_sections['acc']['index']]['pst'],'hide_zero' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
  <?php endfor; endif; ?>
  <td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency2.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['data']['total_accounting'][5]['pst'],'show_minus_brackets' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
  <td></td>
</tr>
<tr class="OrderSheetCell">
  <td></td>
  <td></td>
  <td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency2.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['data']['total']['gross'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
  <td></td>
  <?php unset($this->_sections['acc']);
$this->_sections['acc']['loop'] = is_array($_loop=5) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['acc']['name'] = 'acc';
$this->_sections['acc']['show'] = true;
$this->_sections['acc']['max'] = $this->_sections['acc']['loop'];
$this->_sections['acc']['step'] = 1;
$this->_sections['acc']['start'] = $this->_sections['acc']['step'] > 0 ? 0 : $this->_sections['acc']['loop']-1;
if ($this->_sections['acc']['show']) {
    $this->_sections['acc']['total'] = $this->_sections['acc']['loop'];
    if ($this->_sections['acc']['total'] == 0)
        $this->_sections['acc']['show'] = false;
} else
    $this->_sections['acc']['total'] = 0;
if ($this->_sections['acc']['show']):

            for ($this->_sections['acc']['index'] = $this->_sections['acc']['start'], $this->_sections['acc']['iteration'] = 1;
                 $this->_sections['acc']['iteration'] <= $this->_sections['acc']['total'];
                 $this->_sections['acc']['index'] += $this->_sections['acc']['step'], $this->_sections['acc']['iteration']++):
$this->_sections['acc']['rownum'] = $this->_sections['acc']['iteration'];
$this->_sections['acc']['index_prev'] = $this->_sections['acc']['index'] - $this->_sections['acc']['step'];
$this->_sections['acc']['index_next'] = $this->_sections['acc']['index'] + $this->_sections['acc']['step'];
$this->_sections['acc']['first']      = ($this->_sections['acc']['iteration'] == 1);
$this->_sections['acc']['last']       = ($this->_sections['acc']['iteration'] == $this->_sections['acc']['total']);
?>
  <td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency2.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['data']['total_accounting'][$this->_sections['acc']['index']]['gross'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
  <?php endfor; endif; ?>
  <td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency2.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['data']['total_accounting'][5]['gross'],'show_minus_brackets' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
  <td></td>
</tr>
<tr class="OrderSheetCell OrderSheetFirst">
<td colspan="11">&nbsp;</td>
</tr>
<?php endif;  endif;  $_from = $this->_tpl_vars['order']['shipping_groups']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['groups'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['groups']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['m_id'] => $this->_tpl_vars['v']):
        $this->_foreach['groups']['iteration']++;
 if (( $this->_tpl_vars['v']['status'] == 'P' || $this->_tpl_vars['v']['status'] == 'C' || $this->_tpl_vars['v']['status'] == 'S' || $this->_tpl_vars['v']['status'] == 'R' ) && $this->_tpl_vars['v']['acc_paymentid'] != 0):  $this->assign('show_accounting', true);  else:  $this->assign('show_accounting', false);  endif;  echo smarty_function_cycle(array('values' => ", OrderSheetDark",'assign' => 'cycle_class'), $this);?>

<tr class="OrderSheetCell<?php echo $this->_tpl_vars['cycle_class'];  if ($this->_tpl_vars['v']['profit_margin'] < 0): ?> OrderSheetRed<?php else: ?> OrderSheetGreen<?php endif;  if (($this->_foreach['groups']['iteration'] <= 1)): ?> OrderSheetFirst<?php endif; ?>" style="font-weight: bold;">
  <?php if ($this->_tpl_vars['static'] == 'Y' || $this->_tpl_vars['static'] == 'O'): ?><td width="5"><?php if (($this->_foreach['groups']['iteration'] <= 1)): ?><input type="checkbox" name="orderids[<?php echo $this->_tpl_vars['order']['orderid']; ?>
]" /><?php endif; ?></td><?php endif; ?>
  <td><?php if ($this->_tpl_vars['static'] == 'Y' || $this->_tpl_vars['static'] == 'O'): ?><a href="order.php?orderid=<?php echo $this->_tpl_vars['order']['orderid']; ?>
"><?php endif;  echo $this->_tpl_vars['order']['order_prefix'];  echo $this->_tpl_vars['order']['orderid'];  if ($this->_tpl_vars['static']): ?></a><?php endif; ?></td>
  <td nowrap="nowrap"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/order_status.tpl", 'smarty_include_vars' => array('status' => $this->_tpl_vars['v']['status'],'mode' => 'static')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
  <td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency2.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['v']['total']['net'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
  <td>
  <?php if (! $this->_tpl_vars['static'] || $this->_tpl_vars['static'] == 'O'): ?>
  <select name="groups<?php if ($this->_tpl_vars['static'] == 'O'): ?>[<?php echo $this->_tpl_vars['order']['orderid']; ?>
]<?php endif; ?>[<?php echo $this->_tpl_vars['m_id']; ?>
][paymentid]">
  <option value="0"<?php if ($this->_tpl_vars['v']['acc_paymentid'] == 0): ?> selected="selected"<?php endif; ?>></option>
  <?php $_from = $this->_tpl_vars['all_processors']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['pid'] => $this->_tpl_vars['ps']):
?>
  <option value="<?php echo $this->_tpl_vars['pid']; ?>
"<?php if ($this->_tpl_vars['pid'] == $this->_tpl_vars['v']['acc_paymentid']): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['ps']['payment_method']; ?>
</option>
  <?php endforeach; endif; unset($_from); ?>
  </select>
  <?php else: ?>
  <?php $_from = $this->_tpl_vars['all_processors']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['pid'] => $this->_tpl_vars['ps']):
?>
  <?php if ($this->_tpl_vars['pid'] == $this->_tpl_vars['v']['acc_paymentid']):  echo $this->_tpl_vars['ps']['payment_method'];  endif; ?>
  <?php endforeach; endif; unset($_from); ?>
  <?php endif; ?>
  </td>
  <?php if ($this->_tpl_vars['show_accounting']): ?>
  <?php unset($this->_sections['acc']);
$this->_sections['acc']['loop'] = is_array($_loop=5) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['acc']['name'] = 'acc';
$this->_sections['acc']['show'] = true;
$this->_sections['acc']['max'] = $this->_sections['acc']['loop'];
$this->_sections['acc']['step'] = 1;
$this->_sections['acc']['start'] = $this->_sections['acc']['step'] > 0 ? 0 : $this->_sections['acc']['loop']-1;
if ($this->_sections['acc']['show']) {
    $this->_sections['acc']['total'] = $this->_sections['acc']['loop'];
    if ($this->_sections['acc']['total'] == 0)
        $this->_sections['acc']['show'] = false;
} else
    $this->_sections['acc']['total'] = 0;
if ($this->_sections['acc']['show']):

            for ($this->_sections['acc']['index'] = $this->_sections['acc']['start'], $this->_sections['acc']['iteration'] = 1;
                 $this->_sections['acc']['iteration'] <= $this->_sections['acc']['total'];
                 $this->_sections['acc']['index'] += $this->_sections['acc']['step'], $this->_sections['acc']['iteration']++):
$this->_sections['acc']['rownum'] = $this->_sections['acc']['iteration'];
$this->_sections['acc']['index_prev'] = $this->_sections['acc']['index'] - $this->_sections['acc']['step'];
$this->_sections['acc']['index_next'] = $this->_sections['acc']['index'] + $this->_sections['acc']['step'];
$this->_sections['acc']['first']      = ($this->_sections['acc']['iteration'] == 1);
$this->_sections['acc']['last']       = ($this->_sections['acc']['iteration'] == $this->_sections['acc']['total']);
?>
  <td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency2.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['v']['accounting'][$this->_sections['acc']['index']]['net'],'hide_zero' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
  <?php endfor; endif; ?>
  <td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency2.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['v']['accounting'][5]['net'],'show_minus_brackets' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
  <td><?php echo ((is_array($_tmp=$this->_tpl_vars['v']['profit_margin'])) ? $this->_run_mod_handler('price_format', true, $_tmp) : price_format($_tmp)); ?>
%</td>
  <?php else: ?>
  <?php unset($this->_sections['empty_cells']);
$this->_sections['empty_cells']['loop'] = is_array($_loop=7) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['empty_cells']['name'] = 'empty_cells';
$this->_sections['empty_cells']['show'] = true;
$this->_sections['empty_cells']['max'] = $this->_sections['empty_cells']['loop'];
$this->_sections['empty_cells']['step'] = 1;
$this->_sections['empty_cells']['start'] = $this->_sections['empty_cells']['step'] > 0 ? 0 : $this->_sections['empty_cells']['loop']-1;
if ($this->_sections['empty_cells']['show']) {
    $this->_sections['empty_cells']['total'] = $this->_sections['empty_cells']['loop'];
    if ($this->_sections['empty_cells']['total'] == 0)
        $this->_sections['empty_cells']['show'] = false;
} else
    $this->_sections['empty_cells']['total'] = 0;
if ($this->_sections['empty_cells']['show']):

            for ($this->_sections['empty_cells']['index'] = $this->_sections['empty_cells']['start'], $this->_sections['empty_cells']['iteration'] = 1;
                 $this->_sections['empty_cells']['iteration'] <= $this->_sections['empty_cells']['total'];
                 $this->_sections['empty_cells']['index'] += $this->_sections['empty_cells']['step'], $this->_sections['empty_cells']['iteration']++):
$this->_sections['empty_cells']['rownum'] = $this->_sections['empty_cells']['iteration'];
$this->_sections['empty_cells']['index_prev'] = $this->_sections['empty_cells']['index'] - $this->_sections['empty_cells']['step'];
$this->_sections['empty_cells']['index_next'] = $this->_sections['empty_cells']['index'] + $this->_sections['empty_cells']['step'];
$this->_sections['empty_cells']['first']      = ($this->_sections['empty_cells']['iteration'] == 1);
$this->_sections['empty_cells']['last']       = ($this->_sections['empty_cells']['iteration'] == $this->_sections['empty_cells']['total']);
?><td></td><?php endfor; endif; ?>
  <?php endif; ?>
</tr>
<tr class="OrderSheetCell<?php echo $this->_tpl_vars['cycle_class']; ?>
">
  <?php if ($this->_tpl_vars['static'] == 'Y' || $this->_tpl_vars['static'] == 'O'): ?><td width="5"></td><?php endif; ?>
  <td><?php if ($this->_tpl_vars['static'] == 'Y' || $this->_tpl_vars['static'] == 'O'): ?><a href="order.php?orderid=<?php echo $this->_tpl_vars['order']['orderid']; ?>
"><?php endif;  echo $this->_tpl_vars['v']['code'];  if ($this->_tpl_vars['static']): ?></a><?php endif; ?></td>
  <td><?php echo $this->_tpl_vars['order']['firstname']; ?>
</td>
  <td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency2.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['v']['total']['gst'],'hide_zero' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
  <td><?php echo $this->_tpl_vars['order']['payment_method']; ?>
</td>
  <?php if ($this->_tpl_vars['show_accounting']): ?>
  <td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency2.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['v']['accounting'][0]['gst'],'hide_zero' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
  <?php unset($this->_sections['acc']);
$this->_sections['acc']['start'] = (int)1;
$this->_sections['acc']['loop'] = is_array($_loop=5) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['acc']['name'] = 'acc';
$this->_sections['acc']['show'] = true;
$this->_sections['acc']['max'] = $this->_sections['acc']['loop'];
$this->_sections['acc']['step'] = 1;
if ($this->_sections['acc']['start'] < 0)
    $this->_sections['acc']['start'] = max($this->_sections['acc']['step'] > 0 ? 0 : -1, $this->_sections['acc']['loop'] + $this->_sections['acc']['start']);
else
    $this->_sections['acc']['start'] = min($this->_sections['acc']['start'], $this->_sections['acc']['step'] > 0 ? $this->_sections['acc']['loop'] : $this->_sections['acc']['loop']-1);
if ($this->_sections['acc']['show']) {
    $this->_sections['acc']['total'] = min(ceil(($this->_sections['acc']['step'] > 0 ? $this->_sections['acc']['loop'] - $this->_sections['acc']['start'] : $this->_sections['acc']['start']+1)/abs($this->_sections['acc']['step'])), $this->_sections['acc']['max']);
    if ($this->_sections['acc']['total'] == 0)
        $this->_sections['acc']['show'] = false;
} else
    $this->_sections['acc']['total'] = 0;
if ($this->_sections['acc']['show']):

            for ($this->_sections['acc']['index'] = $this->_sections['acc']['start'], $this->_sections['acc']['iteration'] = 1;
                 $this->_sections['acc']['iteration'] <= $this->_sections['acc']['total'];
                 $this->_sections['acc']['index'] += $this->_sections['acc']['step'], $this->_sections['acc']['iteration']++):
$this->_sections['acc']['rownum'] = $this->_sections['acc']['iteration'];
$this->_sections['acc']['index_prev'] = $this->_sections['acc']['index'] - $this->_sections['acc']['step'];
$this->_sections['acc']['index_next'] = $this->_sections['acc']['index'] + $this->_sections['acc']['step'];
$this->_sections['acc']['first']      = ($this->_sections['acc']['iteration'] == 1);
$this->_sections['acc']['last']       = ($this->_sections['acc']['iteration'] == $this->_sections['acc']['total']);
?>
  <td>
  <?php if (! $this->_tpl_vars['static'] || $this->_tpl_vars['static'] == 'O'): ?>
  <input name="groups<?php if ($this->_tpl_vars['static'] == 'O'): ?>[<?php echo $this->_tpl_vars['order']['orderid']; ?>
]<?php endif; ?>[<?php echo $this->_tpl_vars['m_id']; ?>
][acc][<?php echo $this->_sections['acc']['index']; ?>
][gst]" size="8" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['v']['accounting'][$this->_sections['acc']['index']]['gst'])) ? $this->_run_mod_handler('price_format', true, $_tmp) : price_format($_tmp)); ?>
" />
  <?php else: ?>
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency2.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['v']['accounting'][$this->_sections['acc']['index']]['gst'],'hide_zero' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <?php endif; ?>
  </td>
  <?php endfor; endif; ?>
  <td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency2.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['v']['accounting'][5]['gst'],'hide_zero' => 'Y','show_minus_brackets' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
  <td></td>
  <?php else: ?>
  <?php unset($this->_sections['empty_cells']);
$this->_sections['empty_cells']['loop'] = is_array($_loop=7) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['empty_cells']['name'] = 'empty_cells';
$this->_sections['empty_cells']['show'] = true;
$this->_sections['empty_cells']['max'] = $this->_sections['empty_cells']['loop'];
$this->_sections['empty_cells']['step'] = 1;
$this->_sections['empty_cells']['start'] = $this->_sections['empty_cells']['step'] > 0 ? 0 : $this->_sections['empty_cells']['loop']-1;
if ($this->_sections['empty_cells']['show']) {
    $this->_sections['empty_cells']['total'] = $this->_sections['empty_cells']['loop'];
    if ($this->_sections['empty_cells']['total'] == 0)
        $this->_sections['empty_cells']['show'] = false;
} else
    $this->_sections['empty_cells']['total'] = 0;
if ($this->_sections['empty_cells']['show']):

            for ($this->_sections['empty_cells']['index'] = $this->_sections['empty_cells']['start'], $this->_sections['empty_cells']['iteration'] = 1;
                 $this->_sections['empty_cells']['iteration'] <= $this->_sections['empty_cells']['total'];
                 $this->_sections['empty_cells']['index'] += $this->_sections['empty_cells']['step'], $this->_sections['empty_cells']['iteration']++):
$this->_sections['empty_cells']['rownum'] = $this->_sections['empty_cells']['iteration'];
$this->_sections['empty_cells']['index_prev'] = $this->_sections['empty_cells']['index'] - $this->_sections['empty_cells']['step'];
$this->_sections['empty_cells']['index_next'] = $this->_sections['empty_cells']['index'] + $this->_sections['empty_cells']['step'];
$this->_sections['empty_cells']['first']      = ($this->_sections['empty_cells']['iteration'] == 1);
$this->_sections['empty_cells']['last']       = ($this->_sections['empty_cells']['iteration'] == $this->_sections['empty_cells']['total']);
?><td></td><?php endfor; endif; ?>
  <?php endif; ?>
</tr>
<tr class="OrderSheetCell<?php echo $this->_tpl_vars['cycle_class']; ?>
">
  <?php if ($this->_tpl_vars['static'] == 'Y' || $this->_tpl_vars['static'] == 'O'): ?><td width="5"></td><?php endif; ?>
  <td>&nbsp;</td>
  <td><?php echo $this->_tpl_vars['order']['lastname']; ?>
</td>
  <td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency2.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['v']['total']['pst'],'hide_zero' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
  <td><?php echo ((is_array($_tmp=$this->_tpl_vars['order']['date'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d-%b-%G") : smarty_modifier_date_format($_tmp, "%d-%b-%G")); ?>
</td>
  <?php if ($this->_tpl_vars['show_accounting']): ?>
  <td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency2.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['v']['accounting'][0]['pst'],'hide_zero' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
  <?php unset($this->_sections['acc']);
$this->_sections['acc']['start'] = (int)1;
$this->_sections['acc']['loop'] = is_array($_loop=5) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['acc']['name'] = 'acc';
$this->_sections['acc']['show'] = true;
$this->_sections['acc']['max'] = $this->_sections['acc']['loop'];
$this->_sections['acc']['step'] = 1;
if ($this->_sections['acc']['start'] < 0)
    $this->_sections['acc']['start'] = max($this->_sections['acc']['step'] > 0 ? 0 : -1, $this->_sections['acc']['loop'] + $this->_sections['acc']['start']);
else
    $this->_sections['acc']['start'] = min($this->_sections['acc']['start'], $this->_sections['acc']['step'] > 0 ? $this->_sections['acc']['loop'] : $this->_sections['acc']['loop']-1);
if ($this->_sections['acc']['show']) {
    $this->_sections['acc']['total'] = min(ceil(($this->_sections['acc']['step'] > 0 ? $this->_sections['acc']['loop'] - $this->_sections['acc']['start'] : $this->_sections['acc']['start']+1)/abs($this->_sections['acc']['step'])), $this->_sections['acc']['max']);
    if ($this->_sections['acc']['total'] == 0)
        $this->_sections['acc']['show'] = false;
} else
    $this->_sections['acc']['total'] = 0;
if ($this->_sections['acc']['show']):

            for ($this->_sections['acc']['index'] = $this->_sections['acc']['start'], $this->_sections['acc']['iteration'] = 1;
                 $this->_sections['acc']['iteration'] <= $this->_sections['acc']['total'];
                 $this->_sections['acc']['index'] += $this->_sections['acc']['step'], $this->_sections['acc']['iteration']++):
$this->_sections['acc']['rownum'] = $this->_sections['acc']['iteration'];
$this->_sections['acc']['index_prev'] = $this->_sections['acc']['index'] - $this->_sections['acc']['step'];
$this->_sections['acc']['index_next'] = $this->_sections['acc']['index'] + $this->_sections['acc']['step'];
$this->_sections['acc']['first']      = ($this->_sections['acc']['iteration'] == 1);
$this->_sections['acc']['last']       = ($this->_sections['acc']['iteration'] == $this->_sections['acc']['total']);
?>
  <td>
  <?php if (! $this->_tpl_vars['static'] || $this->_tpl_vars['static'] == 'O'): ?>
  <input name="groups<?php if ($this->_tpl_vars['static'] == 'O'): ?>[<?php echo $this->_tpl_vars['order']['orderid']; ?>
]<?php endif; ?>[<?php echo $this->_tpl_vars['m_id']; ?>
][acc][<?php echo $this->_sections['acc']['index']; ?>
][pst]" size="8" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['v']['accounting'][$this->_sections['acc']['index']]['pst'])) ? $this->_run_mod_handler('price_format', true, $_tmp) : price_format($_tmp)); ?>
" />
  <?php else: ?>
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency2.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['v']['accounting'][$this->_sections['acc']['index']]['pst'],'hide_zero' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <?php endif; ?>
  </td>
  <?php endfor; endif; ?>
  <td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency2.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['v']['accounting'][5]['pst'],'hide_zero' => 'Y','show_minus_brackets' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
  <td></td>
  <?php else: ?>
  <?php unset($this->_sections['empty_cells']);
$this->_sections['empty_cells']['loop'] = is_array($_loop=7) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['empty_cells']['name'] = 'empty_cells';
$this->_sections['empty_cells']['show'] = true;
$this->_sections['empty_cells']['max'] = $this->_sections['empty_cells']['loop'];
$this->_sections['empty_cells']['step'] = 1;
$this->_sections['empty_cells']['start'] = $this->_sections['empty_cells']['step'] > 0 ? 0 : $this->_sections['empty_cells']['loop']-1;
if ($this->_sections['empty_cells']['show']) {
    $this->_sections['empty_cells']['total'] = $this->_sections['empty_cells']['loop'];
    if ($this->_sections['empty_cells']['total'] == 0)
        $this->_sections['empty_cells']['show'] = false;
} else
    $this->_sections['empty_cells']['total'] = 0;
if ($this->_sections['empty_cells']['show']):

            for ($this->_sections['empty_cells']['index'] = $this->_sections['empty_cells']['start'], $this->_sections['empty_cells']['iteration'] = 1;
                 $this->_sections['empty_cells']['iteration'] <= $this->_sections['empty_cells']['total'];
                 $this->_sections['empty_cells']['index'] += $this->_sections['empty_cells']['step'], $this->_sections['empty_cells']['iteration']++):
$this->_sections['empty_cells']['rownum'] = $this->_sections['empty_cells']['iteration'];
$this->_sections['empty_cells']['index_prev'] = $this->_sections['empty_cells']['index'] - $this->_sections['empty_cells']['step'];
$this->_sections['empty_cells']['index_next'] = $this->_sections['empty_cells']['index'] + $this->_sections['empty_cells']['step'];
$this->_sections['empty_cells']['first']      = ($this->_sections['empty_cells']['iteration'] == 1);
$this->_sections['empty_cells']['last']       = ($this->_sections['empty_cells']['iteration'] == $this->_sections['empty_cells']['total']);
?><td></td><?php endfor; endif; ?>
  <?php endif; ?>
</tr>
<tr class="OrderSheetCell<?php echo $this->_tpl_vars['cycle_class']; ?>
 OrderSheetLast">
  <?php if ($this->_tpl_vars['static'] == 'Y' || $this->_tpl_vars['static'] == 'O'): ?><td width="5"></td><?php endif; ?>
  <td>&nbsp;</td>
  <td><?php echo $this->_tpl_vars['order']['s_countryname']; ?>
</td>
  <td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency2.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['v']['total']['gross'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
  <td><?php echo ((is_array($_tmp=$this->_tpl_vars['order']['date'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%T") : smarty_modifier_date_format($_tmp, "%T")); ?>
</td>
  <?php if ($this->_tpl_vars['show_accounting']): ?>
  <td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency2.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['v']['accounting'][0]['gross'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
  <?php unset($this->_sections['acc']);
$this->_sections['acc']['start'] = (int)1;
$this->_sections['acc']['loop'] = is_array($_loop=5) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['acc']['name'] = 'acc';
$this->_sections['acc']['show'] = true;
$this->_sections['acc']['max'] = $this->_sections['acc']['loop'];
$this->_sections['acc']['step'] = 1;
if ($this->_sections['acc']['start'] < 0)
    $this->_sections['acc']['start'] = max($this->_sections['acc']['step'] > 0 ? 0 : -1, $this->_sections['acc']['loop'] + $this->_sections['acc']['start']);
else
    $this->_sections['acc']['start'] = min($this->_sections['acc']['start'], $this->_sections['acc']['step'] > 0 ? $this->_sections['acc']['loop'] : $this->_sections['acc']['loop']-1);
if ($this->_sections['acc']['show']) {
    $this->_sections['acc']['total'] = min(ceil(($this->_sections['acc']['step'] > 0 ? $this->_sections['acc']['loop'] - $this->_sections['acc']['start'] : $this->_sections['acc']['start']+1)/abs($this->_sections['acc']['step'])), $this->_sections['acc']['max']);
    if ($this->_sections['acc']['total'] == 0)
        $this->_sections['acc']['show'] = false;
} else
    $this->_sections['acc']['total'] = 0;
if ($this->_sections['acc']['show']):

            for ($this->_sections['acc']['index'] = $this->_sections['acc']['start'], $this->_sections['acc']['iteration'] = 1;
                 $this->_sections['acc']['iteration'] <= $this->_sections['acc']['total'];
                 $this->_sections['acc']['index'] += $this->_sections['acc']['step'], $this->_sections['acc']['iteration']++):
$this->_sections['acc']['rownum'] = $this->_sections['acc']['iteration'];
$this->_sections['acc']['index_prev'] = $this->_sections['acc']['index'] - $this->_sections['acc']['step'];
$this->_sections['acc']['index_next'] = $this->_sections['acc']['index'] + $this->_sections['acc']['step'];
$this->_sections['acc']['first']      = ($this->_sections['acc']['iteration'] == 1);
$this->_sections['acc']['last']       = ($this->_sections['acc']['iteration'] == $this->_sections['acc']['total']);
?>
  <td>
  <?php if (! $this->_tpl_vars['static'] || $this->_tpl_vars['static'] == 'O'): ?>
  <input name="groups<?php if ($this->_tpl_vars['static'] == 'O'): ?>[<?php echo $this->_tpl_vars['order']['orderid']; ?>
]<?php endif; ?>[<?php echo $this->_tpl_vars['m_id']; ?>
][acc][<?php echo $this->_sections['acc']['index']; ?>
][gross]" size="8" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['v']['accounting'][$this->_sections['acc']['index']]['gross'])) ? $this->_run_mod_handler('price_format', true, $_tmp) : price_format($_tmp)); ?>
" />
  <?php else: ?>
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency2.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['v']['accounting'][$this->_sections['acc']['index']]['gross'],'hide_zero' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <?php endif; ?>
  </td>
  <?php endfor; endif; ?>
  <td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency2.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['v']['accounting'][5]['gross'],'show_minus_brackets' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
  <td></td>
  <?php else: ?>
  <?php unset($this->_sections['empty_cells']);
$this->_sections['empty_cells']['loop'] = is_array($_loop=7) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['empty_cells']['name'] = 'empty_cells';
$this->_sections['empty_cells']['show'] = true;
$this->_sections['empty_cells']['max'] = $this->_sections['empty_cells']['loop'];
$this->_sections['empty_cells']['step'] = 1;
$this->_sections['empty_cells']['start'] = $this->_sections['empty_cells']['step'] > 0 ? 0 : $this->_sections['empty_cells']['loop']-1;
if ($this->_sections['empty_cells']['show']) {
    $this->_sections['empty_cells']['total'] = $this->_sections['empty_cells']['loop'];
    if ($this->_sections['empty_cells']['total'] == 0)
        $this->_sections['empty_cells']['show'] = false;
} else
    $this->_sections['empty_cells']['total'] = 0;
if ($this->_sections['empty_cells']['show']):

            for ($this->_sections['empty_cells']['index'] = $this->_sections['empty_cells']['start'], $this->_sections['empty_cells']['iteration'] = 1;
                 $this->_sections['empty_cells']['iteration'] <= $this->_sections['empty_cells']['total'];
                 $this->_sections['empty_cells']['index'] += $this->_sections['empty_cells']['step'], $this->_sections['empty_cells']['iteration']++):
$this->_sections['empty_cells']['rownum'] = $this->_sections['empty_cells']['iteration'];
$this->_sections['empty_cells']['index_prev'] = $this->_sections['empty_cells']['index'] - $this->_sections['empty_cells']['step'];
$this->_sections['empty_cells']['index_next'] = $this->_sections['empty_cells']['index'] + $this->_sections['empty_cells']['step'];
$this->_sections['empty_cells']['first']      = ($this->_sections['empty_cells']['iteration'] == 1);
$this->_sections['empty_cells']['last']       = ($this->_sections['empty_cells']['iteration'] == $this->_sections['empty_cells']['total']);
?><td></td><?php endfor; endif; ?>
  <?php endif; ?>
</tr>
<?php endforeach; endif; unset($_from);  if ($this->_tpl_vars['cycle_state'] == ""): ?>
</table>
<?php endif; ?>
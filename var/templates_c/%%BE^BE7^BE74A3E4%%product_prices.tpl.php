<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:05
         compiled from customer/main/product_prices.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'customer/main/product_prices.tpl', 1, false),)), $this); ?>
<?php func_load_lang($this, "customer/main/product_prices.tpl","lbl_price"); ?><?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "customer/main/product_prices.tpl"), $this); endif;  if ($this->_tpl_vars['product_wholesale'] != ""): ?>
<TABLE align="center" border="0" cellpadding="2" cellspacing="0" <?php if ($this->_tpl_vars['mobile_skin'] != 'Y'): ?>width="100%" style="border-top: 1px solid black; border-left: 1px solid black;"<?php else: ?>width="50%" style="border-top: 1px solid black; border-left: 1px solid black; font-size: 20px;"<?php endif; ?>>

<tr bgcolor="#ffffff">
<td colspan="2" style="color: #000000;  border-right: 1px solid black; border-bottom: 1px solid black;" align="center" nowrap="nowrap" >
Discount table
</td>
</tr>

<tr bgcolor="#ffffff">
<td align="center" <?php if ($this->_tpl_vars['mobile_skin'] != 'Y'): ?>width="30%" style="color: #006500; border-right: 1px solid black; border-bottom: 1px solid black; font-size: 13px;"<?php else: ?>width="50%" style="color: #006500; border-right: 1px solid black; border-bottom: 1px solid black; font-size: 20px;"<?php endif; ?> nowrap="nowrap">Qty</td>
<td align="center" <?php if ($this->_tpl_vars['mobile_skin'] != 'Y'): ?>width="70%" style="color: #CD3335; border-right: 1px solid #000000; border-bottom: 1px solid black; font-size: 13px;"<?php else: ?>width="50%" style="color: #CD3335; border-right: 1px solid #000000; border-bottom: 1px solid black; font-size: 20px;"<?php endif; ?> nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_price']; ?>
</td>
</tr>

<?php if ($this->_tpl_vars['product']['taxes']):  ob_start();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/taxed_price.tpl", 'smarty_include_vars' => array('taxes' => $this->_tpl_vars['product']['taxes'],'display_info' => 'N')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  $this->_smarty_vars['capture']['taxdata'] = ob_get_contents(); ob_end_clean();  endif; ?>

<?php unset($this->_sections['wi']);
$this->_sections['wi']['name'] = 'wi';
$this->_sections['wi']['loop'] = is_array($_loop=$this->_tpl_vars['product_wholesale']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['wi']['show'] = true;
$this->_sections['wi']['max'] = $this->_sections['wi']['loop'];
$this->_sections['wi']['step'] = 1;
$this->_sections['wi']['start'] = $this->_sections['wi']['step'] > 0 ? 0 : $this->_sections['wi']['loop']-1;
if ($this->_sections['wi']['show']) {
    $this->_sections['wi']['total'] = $this->_sections['wi']['loop'];
    if ($this->_sections['wi']['total'] == 0)
        $this->_sections['wi']['show'] = false;
} else
    $this->_sections['wi']['total'] = 0;
if ($this->_sections['wi']['show']):

            for ($this->_sections['wi']['index'] = $this->_sections['wi']['start'], $this->_sections['wi']['iteration'] = 1;
                 $this->_sections['wi']['iteration'] <= $this->_sections['wi']['total'];
                 $this->_sections['wi']['index'] += $this->_sections['wi']['step'], $this->_sections['wi']['iteration']++):
$this->_sections['wi']['rownum'] = $this->_sections['wi']['iteration'];
$this->_sections['wi']['index_prev'] = $this->_sections['wi']['index'] - $this->_sections['wi']['step'];
$this->_sections['wi']['index_next'] = $this->_sections['wi']['index'] + $this->_sections['wi']['step'];
$this->_sections['wi']['first']      = ($this->_sections['wi']['iteration'] == 1);
$this->_sections['wi']['last']       = ($this->_sections['wi']['iteration'] == $this->_sections['wi']['total']);
?>
<TR style="background: #ffffff;" id="wp_tr<?php echo $this->_sections['wi']['index']; ?>
">
<TD nowrap="nowrap" id="wp_dt_l<?php echo $this->_sections['wi']['index']; ?>
" style="font-weight: normal; color: #006500; border-right: 1px solid black; border-bottom: 1px solid black; <?php if ($this->_tpl_vars['mobile_skin'] == 'Y'): ?>font-size: 20px;<?php else: ?>font-size: 13px;<?php endif; ?>" align="center">&nbsp;<?php if ($this->_tpl_vars['product_wholesale'][$this->_sections['wi']['index']]['quantity'] <= '0'):  if ($this->_tpl_vars['product']['mult_order_quantity'] == 'Y' && $this->_tpl_vars['product']['min_amount'] > 1):  echo $this->_tpl_vars['product']['min_amount'];  else: ?>1<?php endif;  else:  echo $this->_tpl_vars['product_wholesale'][$this->_sections['wi']['index']]['quantity'];  if ($this->_sections['wi']['last'] && $this->_tpl_vars['product_wholesale'][$this->_sections['wi']['index']]['next_quantity'] == '0'):  if ($this->_tpl_vars['product']['avail'] > $this->_tpl_vars['product_wholesale'][$this->_sections['wi']['index']]['quantity']): ?>+<?php endif;  else:  if ($this->_tpl_vars['product']['mult_order_quantity'] == 'Y' && $this->_tpl_vars['product']['min_amount'] > 1):  else:  if ($this->_tpl_vars['product_wholesale'][$this->_sections['wi']['index']]['quantity'] != $this->_tpl_vars['product_wholesale'][$this->_sections['wi']['index']]['next_quantity']): ?>-<?php echo $this->_tpl_vars['product_wholesale'][$this->_sections['wi']['index']]['next_quantity'];  endif;  endif;  endif;  endif; ?>&nbsp;</TD>

<TD nowrap="nowrap" id="wp_dt_r<?php echo $this->_sections['wi']['index']; ?>
" style="color: #CD3335; border-right: 1px solid #000000; border-bottom: 1px solid black; <?php if ($this->_tpl_vars['mobile_skin'] == 'Y'): ?>font-size: 20px;<?php else: ?>font-size: 13px;<?php endif; ?>" height="20" align="center"><SPAN id="wp<?php echo $this->_sections['wi']['index']; ?>
">&nbsp;<?php echo $this->_tpl_vars['config']['General']['currency_symbol'];  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency2.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['product_wholesale'][$this->_sections['wi']['index']]['taxed_price'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>&nbsp;</SPAN></TD>
</TR>
<?php endfor; endif; ?>
</TABLE>

<?php endif;  if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "customer/main/product_prices.tpl"), $this); endif; ?>
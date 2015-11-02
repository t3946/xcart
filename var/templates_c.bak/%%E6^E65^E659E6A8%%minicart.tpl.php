<?php /* Smarty version 2.6.12, created on 2011-10-11 05:37:51
         compiled from customer/main/minicart.tpl */ ?>
<?php func_load_lang($this, "customer/main/minicart.tpl","lbl_cart_items,lbl_total,lbl_cart_is_empty"); ?><table cellpadding="1" cellspacing="0">
<?php if ($this->_tpl_vars['minicart_total_items'] > 0): ?>
<tr>
	<td rowspan="2" width="23"><a href="cart.php"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/cart_full.gif" width="19" height="16" alt="" /></a></td>
	<td class="Green2" align=right><b><?php echo $this->_tpl_vars['lng']['lbl_cart_items']; ?>
:</b></td>
	<td class="Green2">&nbsp;<?php echo $this->_tpl_vars['minicart_total_items']; ?>
</td>
</tr>
<tr>
	<td class="Green2" align=right><b><?php echo $this->_tpl_vars['lng']['lbl_total']; ?>
:</b></td>
	<td class="Green2">&nbsp;<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['minicart_total_cost'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</td>
</tr>
<?php else: ?>
<tr>
	<td rowspan="2" width="23"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/cart_empty.gif" width="19" height="16" alt="" /></td>
	<td class="Green2" valign="center"><b><?php echo $this->_tpl_vars['lng']['lbl_cart_is_empty']; ?>
</b></td>
</tr>
<?php endif; ?>
</table>
<hr class="VertMenuHr" size="1" />
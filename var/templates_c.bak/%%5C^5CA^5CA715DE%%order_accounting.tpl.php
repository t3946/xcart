<?php /* Smarty version 2.6.12, created on 2011-10-11 06:30:34
         compiled from main/order_accounting.tpl */ ?>
<?php func_load_lang($this, "main/order_accounting.tpl","lbl_update,lbl_accounting"); ?><?php ob_start(); ?>
<form action="order.php" method="post" name="accountingform">
<input type="hidden" name="mode" value="accounting_apply" />
<input type="hidden" name="orderid" value="<?php echo $this->_tpl_vars['order']['orderid']; ?>
" />

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/order_accounting_table.tpl", 'smarty_include_vars' => array('order' => $this->_tpl_vars['order'],'static' => $this->_tpl_vars['static'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if (! $this->_tpl_vars['static']): ?>
<br />
<input type="submit" value="<?php echo $this->_tpl_vars['lng']['lbl_update']; ?>
" />
<?php endif; ?>
</form>
<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_accounting'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
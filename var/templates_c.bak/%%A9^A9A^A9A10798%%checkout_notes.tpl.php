<?php /* Smarty version 2.6.12, created on 2011-10-11 06:23:05
         compiled from customer/main/checkout_notes.tpl */ ?>
<?php func_load_lang($this, "customer/main/checkout_notes.tpl","txt_notes,lbl_customer_notes"); ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['txt_notes'],'class' => 'grey')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<table cellspacing="0" cellpadding="2">
<tr valign="top">
	<td><?php echo $this->_tpl_vars['lng']['lbl_customer_notes']; ?>
:</td>
	<td>&nbsp;</td>
	<td nowrap="nowrap"><textarea cols="70" rows="10" name="Customer_Notes"></textarea></td>
</tr>
</table>
<?php /* Smarty version 2.6.12, created on 2011-10-11 06:09:44
         compiled from customer/main/retrieve_orders.tpl */ ?>
<?php func_load_lang($this, "customer/main/retrieve_orders.tpl","txt_retrieve_orders,lbl_email,txt_email_invalid,lbl_retrieve_orders"); ?><p />

<?php ob_start(); ?>

<?php echo $this->_tpl_vars['lng']['txt_retrieve_orders']; ?>


<br /><br />

<form action="retrieve_orders.php" method="post" name="retrieveordersform">
<input type="hidden" name="mode" value="retrieve_orders" />

<table cellpadding="0" cellspacing="0">
	<tr> 
		<td height="10" width="78" class="FormButton"><?php echo $this->_tpl_vars['lng']['lbl_email']; ?>
</td>
		<td width="10" height="10"><font class="CustomerMessage">*</font></td>
		<td width="282" height="10"> 
			<input type="text" name="email" size="30" value="<?php echo $GLOBALS['HTTP_GET_VARS']['email']; ?>
" />
		</td>
	</tr>

	<?php if ($GLOBALS['HTTP_GET_VARS']['section'] == 'retrieve_order_error'): ?>
	<tr>
		<td width="78" class="FormButton" height="5">&nbsp;</td>
		<td width="10" height="5">&nbsp;</td>
		<td width="282" height="5" class="ErrorMessage"><?php echo $this->_tpl_vars['lng']['txt_email_invalid']; ?>
</td>
	</tr>
	<?php endif; ?>

	<tr> 
		<td width="78" class="FormButton">&nbsp;</td>
		<td width="10">&nbsp;</td>
		<td width="282"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/submit.tpl", 'smarty_include_vars' => array('href' => "javascript: document.retrieveordersform.submit()",'js_to_href' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
	</tr>
</table>

</form>
<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_retrieve_orders'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
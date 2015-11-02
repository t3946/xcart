<?php /* Smarty version 2.6.12, created on 2011-10-11 06:21:51
         compiled from modules/Fast_Lane_Checkout/home_main.tpl */ ?>
<?php func_load_lang($this, "modules/Fast_Lane_Checkout/home_main.tpl","lbl_continue_shopping,lbl_shipping_quote,lbl_checkout"); ?><?php if ($this->_tpl_vars['checkout_step'] == 0):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Fast_Lane_Checkout/checkout_0_enter.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php elseif ($this->_tpl_vars['checkout_step'] == 1):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Fast_Lane_Checkout/checkout_1_profile.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php elseif ($this->_tpl_vars['checkout_step'] == 2):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Fast_Lane_Checkout/checkout_2_method.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php elseif ($this->_tpl_vars['checkout_step'] == 3):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Fast_Lane_Checkout/checkout_3_place.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php else:  if ($this->_tpl_vars['last_categoryid'] != 0):  $this->assign('last_categoryid', "?cat=".($this->_tpl_vars['last_categoryid']));  else:  $this->assign('last_categoryid', "");  endif; ?>
<div align="left" width="100%">
<table cellpadding="0" cellspacing="0" width="100%">
<tr>
	<td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Fast_Lane_Checkout/big_button.tpl", 'smarty_include_vars' => array('button_title' => $this->_tpl_vars['lng']['lbl_continue_shopping'],'style' => 'button','href' => "home.php".($this->_tpl_vars['last_categoryid']))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
	<td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Fast_Lane_Checkout/big_button.tpl", 'smarty_include_vars' => array('button_title' => $this->_tpl_vars['lng']['lbl_shipping_quote'],'bold' => 'N','style' => 'button','href' => "javascript: window.open('popup_shipquote.php','popup_shipquote','width=800,height=600,toolbar=no,status=no,scrollbars=yes,menubar=no,location=no,direction=no');",'js_to_href' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
	<td width="30%">&nbsp;</td>
	<td align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Fast_Lane_Checkout/big_button.tpl", 'smarty_include_vars' => array('button_title' => $this->_tpl_vars['lng']['lbl_checkout'],'style' => 'button','href' => "cart.php?mode=checkout",'color' => 'red','arrow' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>
</table>
</div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/cart.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php endif; ?>
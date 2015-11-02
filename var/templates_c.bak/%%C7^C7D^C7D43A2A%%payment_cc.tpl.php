<?php /* Smarty version 2.6.12, created on 2011-10-11 16:05:28
         compiled from customer/main/payment_cc.tpl */ ?>
<?php func_load_lang($this, "customer/main/payment_cc.tpl","disable_ccinfo_msg"); ?><?php if ($this->_tpl_vars['payment_cc_data']['disable_ccinfo'] != 'Y'): ?>
<table cellspacing="0" cellpadding="2">
<?php if ($this->_tpl_vars['payment_cc_data']['c_template'] != ""):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['payment_cc_data']['c_template'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  else:  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/register_ccinfo.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
</table>
<?php else:  echo $this->_tpl_vars['lng']['disable_ccinfo_msg']; ?>
<br />
<?php endif; ?>
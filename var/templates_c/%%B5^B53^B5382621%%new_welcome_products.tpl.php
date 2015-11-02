<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:24
         compiled from customer/main/new_welcome_products.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'customer/main/new_welcome_products.tpl', 1, false),)), $this); ?>
<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "customer/main/new_welcome_products.tpl"), $this); endif;  if ($this->_tpl_vars['new_products'] != ""):  ob_start();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/products.tpl", 'smarty_include_vars' => array('products' => $this->_tpl_vars['new_products'],'featured' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => 'New products','content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"','new_href' => "/new_products.php",'new_design' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<br />
<br />
<?php endif;  if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "customer/main/new_welcome_products.tpl"), $this); endif; ?>
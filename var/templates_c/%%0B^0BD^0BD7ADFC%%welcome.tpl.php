<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:24
         compiled from customer/main/welcome.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'customer/main/welcome.tpl', 1, false),)), $this); ?>
<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "customer/main/welcome.tpl"), $this); endif; ?>

<?php if ($this->_tpl_vars['e_products_found'] == 'Y'): ?>

	<?php if ($this->_tpl_vars['current_storefront'] == '41'): ?>
        	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/products_new_style.tpl", 'smarty_include_vars' => array('products' => $this->_tpl_vars['products'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php else: ?>
        	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/products.tpl", 'smarty_include_vars' => array('products' => $this->_tpl_vars['products'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php endif; ?>

	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/navigation.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php else: ?>
	<br />

	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/new_welcome_products.tpl", 'smarty_include_vars' => array('new_products' => $this->_tpl_vars['new_products'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/featured.tpl", 'smarty_include_vars' => array('f_products' => $this->_tpl_vars['f_products'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

	<?php if ($this->_tpl_vars['config']['Company']['cidev_main_page_code'] != "" && ( $GLOBALS['_GET']['page'] == '0' || $GLOBALS['_GET']['page'] == "" )): ?>
	<?php echo $this->_tpl_vars['config']['Company']['cidev_main_page_code']; ?>

	<?php else: ?>
	<br />
	<?php endif; ?>

	<?php if ($this->_tpl_vars['active_modules']['Bestsellers'] != "" && $this->_tpl_vars['config']['Bestsellers']['bestsellers_menu'] != 'Y'): ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Bestsellers/bestsellers.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php endif; ?>

	<?php if ($this->_tpl_vars['current_storefront'] == '0'): ?>
        	<?php if ($this->_tpl_vars['current_html_banner'] != ""):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['current_html_banner'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
	<?php endif; ?>

<?php endif; ?>

<br />

<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "customer/main/welcome.tpl"), $this); endif; ?>
<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:18
         compiled from customer/main/infinite_products_load_next_productids.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'customer/main/infinite_products_load_next_productids.tpl', 1, false),)), $this); ?>
<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "customer/main/infinite_products_load_next_productids.tpl"), $this); endif;  echo $this->_tpl_vars['next_productids']; ?>

<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "customer/main/infinite_products_load_next_productids.tpl"), $this); endif; ?>
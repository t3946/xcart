<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:06
         compiled from modules/Upselling_Products/related_products.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'modules/Upselling_Products/related_products.tpl', 1, false),)), $this); ?>
<?php func_load_lang($this, "modules/Upselling_Products/related_products.tpl","lbl_related_products"); ?><?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "modules/Upselling_Products/related_products.tpl"), $this); endif;  if ($this->_tpl_vars['product_links'] != ""): ?>
<br />
<?php ob_start(); ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/products_t_new.tpl", 'smarty_include_vars' => array('products' => $this->_tpl_vars['product_links'],'flag' => 'related')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_related_products'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%" class="recommends no_padding_bottom"','do_not_use_h1' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "modules/Upselling_Products/related_products.tpl"), $this); endif; ?>
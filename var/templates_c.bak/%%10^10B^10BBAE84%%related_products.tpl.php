<?php /* Smarty version 2.6.12, created on 2011-10-11 05:37:52
         compiled from modules/Upselling_Products/related_products.tpl */ ?>
<?php func_load_lang($this, "modules/Upselling_Products/related_products.tpl","lbl_related_products"); ?><?php if ($this->_tpl_vars['product_links'] != ""): ?>
<br />
<?php ob_start(); ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/products_t.tpl", 'smarty_include_vars' => array('products' => $this->_tpl_vars['product_links'],'flag' => 'related')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_related_products'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%" class="recommends no_padding_bottom"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
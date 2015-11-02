<?php /* Smarty version 2.6.12, created on 2011-10-11 05:37:54
         compiled from customer/main/featured.tpl */ ?>
<?php func_load_lang($this, "customer/main/featured.tpl","txt_no_featured,lbl_featured_products"); ?><?php ob_start();  if ($this->_tpl_vars['f_products'] != ""):  if ($this->_tpl_vars['total_pages'] > 2): ?>
<br />
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/navigation.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/products.tpl", 'smarty_include_vars' => array('products' => $this->_tpl_vars['f_products'],'featured' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  if ($this->_tpl_vars['total_pages'] > 2): ?>
<br />
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/navigation.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  else:  echo $this->_tpl_vars['lng']['txt_no_featured']; ?>

<?php endif;  $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_featured_products'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
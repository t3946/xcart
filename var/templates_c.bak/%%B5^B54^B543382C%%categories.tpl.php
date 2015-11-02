<?php /* Smarty version 2.6.12, created on 2011-10-11 05:37:50
         compiled from customer/categories.tpl */ ?>
<?php func_load_lang($this, "customer/categories.tpl","lbl_category_title,lbl_information"); ?><?php ob_start();  if ($this->_tpl_vars['active_modules']['Fancy_Categories'] != ""):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Fancy_Categories/categories.tpl", 'smarty_include_vars' => array('cat_start' => 0,'cat_end' => 500)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  $this->assign('fc_cellpadding', '0');  else:  if ($this->_tpl_vars['config']['General']['root_categories'] == 'Y'):  $_from = $this->_tpl_vars['categories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['c']):
 if ($this->_tpl_vars['c']['order_by'] >= 0 && $this->_tpl_vars['c']['order_by'] <= 500): ?>
<font class="CategoriesList"><a href="home.php?cat=<?php echo $this->_tpl_vars['c']['categoryid']; ?>
" class="VertMenuItems"><?php if ($this->_tpl_vars['c']['is_bold'] == 'Y'): ?><b><?php echo $this->_tpl_vars['c']['category']; ?>
</b><?php else:  echo $this->_tpl_vars['c']['category'];  endif; ?></a></font><br />
<?php endif;  endforeach; endif; unset($_from);  else: ?> <?php $_from = $this->_tpl_vars['subcategories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['catid'] => $this->_tpl_vars['c']):
 if ($this->_tpl_vars['c']['order_by'] >= 0 && $this->_tpl_vars['c']['order_by'] <= 500): ?>
<font class="CategoriesList"><a href="home.php?cat=<?php echo $this->_tpl_vars['catid']; ?>
" class="VertMenuItems"><?php if ($this->_tpl_vars['c']['is_bold'] == 'Y'): ?><b><?php echo $this->_tpl_vars['c']['category']; ?>
</b><?php else:  echo $this->_tpl_vars['c']['category'];  endif; ?></a></font><br />
<?php endif;  endforeach; endif; unset($_from);  endif;  endif;  $this->_smarty_vars['capture']['menu'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "menu.tpl", 'smarty_include_vars' => array('menu_title' => $this->_tpl_vars['lng']['lbl_category_title'],'menu_content' => $this->_smarty_vars['capture']['menu'],'cellpadding' => $this->_tpl_vars['fc_cellpadding'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php ob_start();  if ($this->_tpl_vars['active_modules']['Fancy_Categories'] != ""):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Fancy_Categories/categories.tpl", 'smarty_include_vars' => array('cat_start' => 501,'cat_end' => 50000)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  $this->assign('fc_cellpadding', '0');  else:  if ($this->_tpl_vars['config']['General']['root_categories'] == 'Y'):  $_from = $this->_tpl_vars['categories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['c']):
 if ($this->_tpl_vars['c']['order_by'] > 500): ?>
<font class="CategoriesList"><a href="home.php?cat=<?php echo $this->_tpl_vars['c']['categoryid']; ?>
" class="VertMenuItems"><?php if ($this->_tpl_vars['c']['is_bold'] == 'Y'): ?><b><?php echo $this->_tpl_vars['c']['category']; ?>
</b><?php else:  echo $this->_tpl_vars['c']['category'];  endif; ?></a></font><br />
<?php endif;  endforeach; endif; unset($_from);  else: ?> <?php $_from = $this->_tpl_vars['subcategories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['catid'] => $this->_tpl_vars['c']):
 if ($this->_tpl_vars['c']['order_by'] > 500): ?>
<font class="CategoriesList"><a href="home.php?cat=<?php echo $this->_tpl_vars['catid']; ?>
" class="VertMenuItems"><?php echo $this->_tpl_vars['c']['category']; ?>
</a></font><br />
<?php endif;  endforeach; endif; unset($_from);  endif;  endif;  $this->_smarty_vars['capture']['menu'] = ob_get_contents(); ob_end_clean(); ?>
<br />
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "menu.tpl", 'smarty_include_vars' => array('menu_title' => $this->_tpl_vars['lng']['lbl_information'],'menu_content' => $this->_smarty_vars['capture']['menu'],'cellpadding' => $this->_tpl_vars['fc_cellpadding'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
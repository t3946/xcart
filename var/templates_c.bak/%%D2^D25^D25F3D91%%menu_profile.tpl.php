<?php /* Smarty version 2.6.12, created on 2011-10-11 05:37:57
         compiled from menu_profile.tpl */ ?>
<?php func_load_lang($this, "menu_profile.tpl","lbl_modify,lbl_delete,lbl_change_mpassword,lbl_orders_history,lbl_your_profile"); ?><?php ob_start(); ?>
<a href="register.php?mode=update" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_modify']; ?>
</a><br />
<a href="register.php?mode=delete" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_delete']; ?>
</a><br />
<?php if (( $this->_tpl_vars['usertype'] == 'A' || ( $this->_tpl_vars['usertype'] == 'P' && $this->_tpl_vars['active_modules']['Simple_Mode'] ) ) && $this->_tpl_vars['is_merchant_password'] == 'Y'): ?>
<a class="VertMenuItems" href="<?php echo $this->_tpl_vars['catalogs']['admin']; ?>
/change_mpassword.php"><?php echo $this->_tpl_vars['lng']['lbl_change_mpassword']; ?>
</a><br />
<?php endif;  if ($this->_tpl_vars['usertype'] == 'C'): ?>
<a href="orders.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_orders_history']; ?>
</a><br />
<?php if ($this->_tpl_vars['user_subscription'] != ""):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Subscriptions/subscriptions_menu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></a><br />
<?php endif;  endif;  $this->_smarty_vars['capture']['menu'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "menu.tpl", 'smarty_include_vars' => array('dingbats' => "dingbats_profil.gif",'menu_title' => $this->_tpl_vars['lng']['lbl_your_profile'],'menu_content' => $this->_smarty_vars['capture']['menu'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php /* Smarty version 2.6.12, created on 2011-10-11 05:37:54
         compiled from customer/main/welcome.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'replace', 'customer/main/welcome.tpl', 3, false),array('modifier', 'substitute', 'customer/main/welcome.tpl', 4, false),)), $this); ?>
<?php func_load_lang($this, "customer/main/welcome.tpl","lbl_welcome_back,lbl_site_title,lbl_welcome_to,lbl_site_title,lbl_site_title,lbl_welcome_to,txt_welcome"); ?><?php if (( $this->_tpl_vars['active_modules']['Greet_Visitor'] != "" ) && ( $GLOBALS['HTTP_COOKIE_VARS']['GreetingCookie'] != "" ) && $this->_tpl_vars['logout_user'] == ''): ?>
<?php $this->assign('_name', ((is_array($_tmp=$GLOBALS['HTTP_COOKIE_VARS']['GreetingCookie'])) ? $this->_run_mod_handler('replace', true, $_tmp, "\'", "'") : smarty_modifier_replace($_tmp, "\'", "'"))); ?>
<h3><?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_welcome_back'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'name', $this->_tpl_vars['_name']) : smarty_modifier_substitute($_tmp, 'name', $this->_tpl_vars['_name'])); ?>
 </h3> 
<?php elseif ($this->_tpl_vars['lng']['lbl_site_title']): ?>
<h3><?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_welcome_to'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'company', $this->_tpl_vars['lng']['lbl_site_title']) : smarty_modifier_substitute($_tmp, 'company', $this->_tpl_vars['lng']['lbl_site_title'])); ?>
</h3>
<?php else: ?>
<h3><?php echo $this->_tpl_vars['lng']['lbl_welcome_to']; ?>
 <?php echo $this->_tpl_vars['config']['Company']['company_name']; ?>
</h3>
<?php endif; ?>
<?php echo $this->_tpl_vars['lng']['txt_welcome']; ?>

<?php if ($this->_tpl_vars['active_modules']['Bestsellers'] != "" && $this->_tpl_vars['config']['Bestsellers']['bestsellers_menu'] != 'Y'): ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Bestsellers/bestsellers.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>
<?php if ($this->_tpl_vars['current_html_banner'] != ""):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['current_html_banner'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
<br />
<br />
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/featured.tpl", 'smarty_include_vars' => array('f_products' => $this->_tpl_vars['f_products'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
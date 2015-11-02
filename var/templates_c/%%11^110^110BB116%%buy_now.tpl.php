<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:05
         compiled from buttons/buy_now.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'buttons/buy_now.tpl', 1, false),)), $this); ?>
<?php func_load_lang($this, "buttons/buy_now.tpl","lbl_buy_now"); ?><?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "buttons/buy_now.tpl"), $this); endif;  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/button.tpl", 'smarty_include_vars' => array('button_title' => $this->_tpl_vars['lng']['lbl_buy_now'],'href' => $this->_tpl_vars['href'],'title' => $this->_tpl_vars['title'],'style' => $this->_tpl_vars['style'],'b' => $this->_tpl_vars['b'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "buttons/buy_now.tpl"), $this); endif; ?>
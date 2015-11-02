<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:13
         compiled from buttons/delete_item.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'buttons/delete_item.tpl', 1, false),)), $this); ?>
<?php func_load_lang($this, "buttons/delete_item.tpl","lbl_delete_item"); ?><?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "buttons/delete_item.tpl"), $this); endif;  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/button.tpl", 'smarty_include_vars' => array('button_title' => $this->_tpl_vars['lng']['lbl_delete_item'],'href' => $this->_tpl_vars['href'],'title' => $this->_tpl_vars['title'],'style' => $this->_tpl_vars['style'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "buttons/delete_item.tpl"), $this); endif; ?>
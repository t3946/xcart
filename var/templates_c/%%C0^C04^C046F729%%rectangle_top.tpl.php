<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:05
         compiled from rectangle_top.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'rectangle_top.tpl', 1, false),)), $this); ?>
<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "rectangle_top.tpl"), $this); endif; ?>
<table cellpadding="0" cellspacing="0" <?php if ($this->_tpl_vars['usertype'] == 'A' || $this->_tpl_vars['usertype'] == 'P'): ?> width="1080"<?php else: ?>width="960"<?php endif; ?> align="center"><tr><td valign="top" width="50">

<table class="Container" cellpadding="0" cellspacing="0" <?php if ($this->_tpl_vars['usertype'] == 'A' || $this->_tpl_vars['usertype'] == 'P'): ?> width="1080" bgcolor=#E4E0C5 <?php else: ?> bgcolor="#ffffff" width="960"<?php endif; ?> align="center" >
<tr><td class="Container">
<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "rectangle_top.tpl"), $this); endif; ?>
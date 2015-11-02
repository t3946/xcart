<?php /* Smarty version 2.6.12, created on 2015-11-02 03:13:50
         compiled from main/error_access_denied.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'main/error_access_denied.tpl', 1, false),)), $this); ?>
<?php func_load_lang($this, "main/error_access_denied.tpl","err_access_denied,err_access_denied_msg,lbl_error_id"); ?><?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "main/error_access_denied.tpl"), $this); endif; ?><h3><?php echo $this->_tpl_vars['lng']['err_access_denied']; ?>
</h3>
<?php echo $this->_tpl_vars['lng']['err_access_denied_msg']; ?>

<?php if ($this->_tpl_vars['id'] != ''): ?>
<br /><br />
<b><?php echo $this->_tpl_vars['lng']['lbl_error_id']; ?>
:</b> <?php echo $this->_tpl_vars['id']; ?>

<?php endif;  if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "main/error_access_denied.tpl"), $this); endif; ?>
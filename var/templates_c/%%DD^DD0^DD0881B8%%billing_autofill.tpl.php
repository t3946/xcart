<?php /* Smarty version 2.6.12, created on 2015-11-02 03:06:29
         compiled from billing_autofill.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'billing_autofill.tpl', 1, false),)), $this); ?>
<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "billing_autofill.tpl"), $this); endif; ?><script type="text/javascript" src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/billing_autofill.js"></script>
<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "billing_autofill.tpl"), $this); endif; ?>
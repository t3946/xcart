<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:05
         compiled from main/include_js.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'main/include_js.tpl', 1, false),)), $this); ?>
<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "main/include_js.tpl"), $this); endif;  if ($this->_tpl_vars['config']['UA']['platform'] == 'MacPPC'): ?>
<script language="JavaScript" type="text/javascript">
<!--
<?php require_once(SMARTY_CORE_DIR . 'core.smarty_include_php.php');
smarty_core_smarty_include_php(array('smarty_file' => ($this->_tpl_vars['template_dir'])."/".($this->_tpl_vars['src']), 'smarty_assign' => '', 'smarty_once' => false, 'smarty_include_vars' => array()), $this); ?>

-->
</script>
<?php else: ?>
<script src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/<?php echo $this->_tpl_vars['src']; ?>
" language="JavaScript" type="text/javascript"></script>
<?php endif;  if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "main/include_js.tpl"), $this); endif; ?>
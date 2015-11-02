<?php /* Smarty version 2.6.12, created on 2015-11-02 03:06:29
         compiled from dialog_FLC.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'dialog_FLC.tpl', 1, false),array('modifier', 'default', 'dialog_FLC.tpl', 6, false),)), $this); ?>
<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "dialog_FLC.tpl"), $this); endif;  if ($this->_tpl_vars['printable'] != ''): ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog_printable.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php else: ?>
<table cellspacing="0" <?php echo $this->_tpl_vars['extra']; ?>
>
<tr><td class="DialogBox" valign="<?php echo ((is_array($_tmp=@$this->_tpl_vars['valign'])) ? $this->_run_mod_handler('default', true, $_tmp, 'top') : smarty_modifier_default($_tmp, 'top')); ?>
"><?php echo $this->_tpl_vars['content']; ?>

</td></tr>
</table>
<?php endif; ?>
<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "dialog_FLC.tpl"), $this); endif; ?>
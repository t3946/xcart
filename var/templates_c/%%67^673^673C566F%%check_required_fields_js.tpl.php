<?php /* Smarty version 2.6.12, created on 2015-11-02 03:06:28
         compiled from check_required_fields_js.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'check_required_fields_js.tpl', 1, false),array('modifier', 'strip_tags', 'check_required_fields_js.tpl', 13, false),array('modifier', 'escape', 'check_required_fields_js.tpl', 13, false),)), $this); ?>
<?php func_load_lang($this, "check_required_fields_js.tpl","lbl_required_field_is_empty"); ?><?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "check_required_fields_js.tpl"), $this); endif; ?><script type="text/javascript">
<!--
var lbl_required_field_is_empty = "<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_required_field_is_empty'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
";
-->
</script>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/include_js.tpl", 'smarty_include_vars' => array('src' => "check_required_fields_js.js")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "check_required_fields_js.tpl"), $this); endif; ?>
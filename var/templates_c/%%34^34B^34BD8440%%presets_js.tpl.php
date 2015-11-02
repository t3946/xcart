<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:05
         compiled from presets_js.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'presets_js.tpl', 1, false),array('modifier', 'replace', 'presets_js.tpl', 9, false),)), $this); ?>
<?php func_load_lang($this, "presets_js.tpl","lbl_no_items_have_been_selected"); ?><?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "presets_js.tpl"), $this); endif; ?><script type="text/javascript">
<!--
var number_format_dec = '<?php echo $this->_tpl_vars['number_format_dec']; ?>
';
var number_format_th = '<?php echo $this->_tpl_vars['number_format_th']; ?>
';
var number_format_point = '<?php echo $this->_tpl_vars['number_format_point']; ?>
';
var store_language = '<?php echo $this->_tpl_vars['store_language']; ?>
';
var xcart_web_dir = "<?php echo $this->_tpl_vars['xcart_web_dir']; ?>
";
var images_dir = "<?php echo $this->_tpl_vars['ImagesDir']; ?>
";
var lbl_no_items_have_been_selected = '<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_no_items_have_been_selected'])) ? $this->_run_mod_handler('replace', true, $_tmp, "'", "\'") : smarty_modifier_replace($_tmp, "'", "\'")); ?>
';
var current_area = '<?php echo $this->_tpl_vars['usertype']; ?>
';
-->
</script>
<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "presets_js.tpl"), $this); endif; ?>
<?php /* Smarty version 2.6.12, created on 2011-10-11 06:16:25
         compiled from capitalize_js.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'capitalize_js.tpl', 8, false),array('modifier', 'strip_tags', 'capitalize_js.tpl', 14, false),)), $this); ?>
<?php func_load_lang($this, "capitalize_js.tpl","lbl_capitalize"); ?>
<script type="text/javascript">
<!--

var reps = Array();
<?php $_from = $this->_tpl_vars['replacements']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['r']):
?>
	reps['<?php echo $this->_tpl_vars['key']; ?>
'] = ['<?php echo ((is_array($_tmp=$this->_tpl_vars['r']['what'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
', '<?php echo ((is_array($_tmp=$this->_tpl_vars['r']['by'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
'];
<?php endforeach; endif; unset($_from); ?>

-->
</script>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/include_js.tpl", 'smarty_include_vars' => array('src' => "capitalize_do.js")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<input type="button" value=" <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_capitalize'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 " onclick="javascript: capitalize('<?php echo $this->_tpl_vars['id']; ?>
');" />
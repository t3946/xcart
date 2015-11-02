<?php /* Smarty version 2.6.12, created on 2011-10-11 05:37:52
         compiled from submit_wo_js.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'strip_tags', 'submit_wo_js.tpl', 2, false),array('modifier', 'escape', 'submit_wo_js.tpl', 2, false),)), $this); ?>
<?php func_load_lang($this, "submit_wo_js.tpl","txt_js_disabled_msg"); ?><input type="submit" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['value'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /><br />
<?php if ($this->_tpl_vars['note'] != 'off'): ?>
<br /><?php echo $this->_tpl_vars['lng']['txt_js_disabled_msg']; ?>

<?php endif; ?>
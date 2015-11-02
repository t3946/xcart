<?php /* Smarty version 2.6.12, created on 2011-10-11 06:30:34
         compiled from buttons/multirow_add.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'buttons/multirow_add.tpl', 2, false),)), $this); ?>
<?php func_load_lang($this, "buttons/multirow_add.tpl","lbl_add"); ?><a href="javascript: void(0);" onclick="javascript: add_inputset('<?php echo $this->_tpl_vars['mark']; ?>
', this<?php if ($this->_tpl_vars['is_lined']): ?>, true<?php endif; ?>);"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/plus.gif" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_add'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></a>
<?php /* Smarty version 2.6.12, created on 2011-10-11 05:43:52
         compiled from provider/main/ie_delimiter.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'provider/main/ie_delimiter.tpl', 3, false),)), $this); ?>
<?php func_load_lang($this, "provider/main/ie_delimiter.tpl","lbl_semicolon,lbl_comma,lbl_tab"); ?><?php if ($this->_tpl_vars['saved_delimiter'] == ''):  $this->assign('saved_delimiter', $GLOBALS['HTTP_GET_VARS']['delimiter']);  endif; ?>
<select name="<?php echo ((is_array($_tmp=@$this->_tpl_vars['field_name'])) ? $this->_run_mod_handler('default', true, $_tmp, 'delimiter') : smarty_modifier_default($_tmp, 'delimiter')); ?>
">
	<option value=";"<?php if ($this->_tpl_vars['saved_delimiter'] == ";"): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_semicolon']; ?>
</option>
	<option value=","<?php if ($this->_tpl_vars['saved_delimiter'] == ","): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_comma']; ?>
</option>
	<option value="tab"<?php if ($this->_tpl_vars['saved_delimiter'] == "\t"): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_tab']; ?>
</option>
</select>
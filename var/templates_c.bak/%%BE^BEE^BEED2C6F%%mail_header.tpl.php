<?php /* Smarty version 2.6.12, created on 2011-10-11 06:23:27
         compiled from mail/mail_header.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'substitute', 'mail/mail_header.tpl', 3, false),)), $this); ?>
<?php func_load_lang($this, "mail/mail_header.tpl","eml_mail_header,eml_mail_header"); ?><?php if ($this->_tpl_vars['sf_info']):  echo ((is_array($_tmp=$this->_tpl_vars['lng']['eml_mail_header'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'company', $this->_tpl_vars['sf_info']['config']['Company']['company_name']) : smarty_modifier_substitute($_tmp, 'company', $this->_tpl_vars['sf_info']['config']['Company']['company_name'])); ?>

<?php else:  echo ((is_array($_tmp=$this->_tpl_vars['lng']['eml_mail_header'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'company', $this->_tpl_vars['config']['Company']['company_name']) : smarty_modifier_substitute($_tmp, 'company', $this->_tpl_vars['config']['Company']['company_name'])); ?>

<?php endif; ?>
-------------------------------------------------------------------

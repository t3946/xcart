<?php /* Smarty version 2.6.12, created on 2011-10-11 06:23:27
         compiled from mail/html/mail_header.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'substitute', 'mail/html/mail_header.tpl', 11, false),)), $this); ?>
<?php func_load_lang($this, "mail/html/mail_header.tpl","eml_mail_header"); ?><p /><font size="2">
<?php if ($this->_tpl_vars['sf_info']): ?>
    <?php $this->assign('location', $this->_tpl_vars['sf_info']['config']['Company']['company_website']); ?>
    <?php $this->assign('company_name', $this->_tpl_vars['sf_info']['config']['Company']['company_name']);  else: ?>
    <?php $this->assign('location', $this->_tpl_vars['http_location']); ?>
    <?php $this->assign('company_name', $this->_tpl_vars['config']['Company']['company_name']);  endif;  $this->assign('link', "<a href=\"".($this->_tpl_vars['location'])."/\" target=\"_new\">".($this->_tpl_vars['company_name'])."</a>");  echo ((is_array($_tmp=$this->_tpl_vars['lng']['eml_mail_header'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'company', $this->_tpl_vars['link']) : smarty_modifier_substitute($_tmp, 'company', $this->_tpl_vars['link'])); ?>

</font>

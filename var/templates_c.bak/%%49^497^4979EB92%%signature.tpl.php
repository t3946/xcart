<?php /* Smarty version 2.6.12, created on 2011-10-11 06:23:27
         compiled from mail/signature.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'mail/signature.tpl', 5, false),array('modifier', 'truncate', 'mail/signature.tpl', 6, false),array('modifier', 'cat', 'mail/signature.tpl', 6, false),array('modifier', 'string_format', 'mail/signature.tpl', 6, false),)), $this); ?>
<?php func_load_lang($this, "mail/signature.tpl","eml_signature,lbl_division_of,lbl_phone,lbl_fax,lbl_url,lbl_url"); ?>--
<?php echo $this->_tpl_vars['lng']['eml_signature']; ?>


<?php echo ((is_array($_tmp=@$this->_tpl_vars['sf_info']['config']['Company']['company_name'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['config']['Company']['company_name']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['config']['Company']['company_name'])); ?>
, <?php echo $this->_tpl_vars['lng']['lbl_division_of']; ?>
 <?php echo $this->_tpl_vars['config']['Company']['operating_company_name']; ?>

<?php if ($this->_tpl_vars['config']['Company']['company_phone']):  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_phone'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['config']['Company']['company_phone']; ?>

<?php endif;  if ($this->_tpl_vars['config']['Company']['company_fax']):  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_fax'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['config']['Company']['company_fax']; ?>

<?php endif;  if ($this->_tpl_vars['sf_info']['config']['Company']['company_website']):  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_url'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['sf_info']['config']['Company']['company_website']; ?>

<?php else:  if ($this->_tpl_vars['config']['Company']['company_website']):  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_url'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['config']['Company']['company_website']; ?>

<?php endif;  endif; ?>
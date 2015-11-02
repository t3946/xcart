<?php /* Smarty version 2.6.12, created on 2011-10-11 05:37:51
         compiled from copyright.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'copyright.tpl', 3, false),)), $this); ?>
<?php func_load_lang($this, "copyright.tpl","lbl_copyright,lbl_terms_n_conditions,lbl_privacy_statement"); ?><font color="#000000" size=2>
<?php echo $this->_tpl_vars['lng']['lbl_copyright']; ?>
 &copy; <?php echo $this->_tpl_vars['config']['Company']['start_year'];  if ($this->_tpl_vars['config']['Company']['start_year'] < $this->_tpl_vars['config']['Company']['end_year']): ?>-<?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y") : smarty_modifier_date_format($_tmp, "%Y"));  endif; ?> <?php echo $this->_tpl_vars['config']['Company']['holding_company_name']; ?>
 All Rights Reserved.
<?php if ($this->_tpl_vars['usertype'] == 'C'): ?>&nbsp;&nbsp;<a href="help.php?section=conditions" class="NavigationPath"><?php echo $this->_tpl_vars['lng']['lbl_terms_n_conditions']; ?>
</a> | <a href="help.php?section=business" class="NavigationPath"><?php echo $this->_tpl_vars['lng']['lbl_privacy_statement']; ?>
</a><?php endif; ?>
</font>
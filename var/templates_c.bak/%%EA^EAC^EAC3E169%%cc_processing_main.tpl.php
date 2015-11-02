<?php /* Smarty version 2.6.12, created on 2011-10-11 07:04:02
         compiled from admin/main/cc_processing_main.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'substitute', 'admin/main/cc_processing_main.tpl', 13, false),array('modifier', 'escape', 'admin/main/cc_processing_main.tpl', 17, false),)), $this); ?>
<?php func_load_lang($this, "admin/main/cc_processing_main.tpl","lbl_payment_gateways,txt_no_https_modules_detected,txt_file_none_exe_no_exists,txt_some_requirements_failed,lbl_environment_info,lbl_check_environment_link,lbl_warning"); ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "page_title.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_payment_gateways'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['processing_module'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  if ($this->_tpl_vars['module_data'] != "" && $this->_tpl_vars['module_data']['status'] != '1'): ?>
<br /><br />
<?php ob_start(); ?>
<table cellpadding="2" cellspacing="1" width="100%">
<tr><td>
<br />
<?php if ($this->_tpl_vars['module_data']['failed_func'] == 'httpsmod'): ?>
<font class="AdminTitle"><?php echo $this->_tpl_vars['lng']['txt_no_https_modules_detected']; ?>
</font>
<?php elseif ($this->_tpl_vars['module_data']['failed_func'] == 'testexec'): ?>
<font class="AdminTitle"><?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['txt_file_none_exe_no_exists'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'file', $this->_tpl_vars['module_data']['failed_param']) : smarty_modifier_substitute($_tmp, 'file', $this->_tpl_vars['module_data']['failed_param'])); ?>
</font>
<?php else: ?>
<font class="AdminTitle"><?php echo $this->_tpl_vars['lng']['txt_some_requirements_failed']; ?>
</font>
<?php endif; ?>
&nbsp;&nbsp;&nbsp; <a href="<?php echo $this->_tpl_vars['catalogs']['admin']; ?>
/general.php#Environment" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_environment_info'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><?php echo $this->_tpl_vars['lng']['lbl_check_environment_link']; ?>
 &gt;&gt;</a>
</td></tr>
</table>
<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_warning'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
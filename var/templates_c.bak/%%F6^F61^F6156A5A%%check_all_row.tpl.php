<?php /* Smarty version 2.6.12, created on 2011-10-11 06:30:30
         compiled from main/check_all_row.tpl */ ?>
<?php func_load_lang($this, "main/check_all_row.tpl","lbl_check_all,lbl_uncheck_all"); ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/include_js.tpl", 'smarty_include_vars' => array('src' => "change_all_checkboxes.js")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div<?php if ($this->_tpl_vars['style'] != ''): ?> style="<?php echo $this->_tpl_vars['style']; ?>
"<?php endif; ?>><a href="javascript: checkAll(true, document.<?php echo $this->_tpl_vars['form']; ?>
, '<?php echo $this->_tpl_vars['prefix']; ?>
');"><?php echo $this->_tpl_vars['lng']['lbl_check_all']; ?>
</a> / <a href="javascript: checkAll(false, document.<?php echo $this->_tpl_vars['form']; ?>
, '<?php echo $this->_tpl_vars['prefix']; ?>
');"><?php echo $this->_tpl_vars['lng']['lbl_uncheck_all']; ?>
</a></div>
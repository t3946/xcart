<?php /* Smarty version 2.6.12, created on 2011-10-11 05:40:31
         compiled from admin/main/modules.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'admin/main/modules.tpl', 13, false),array('modifier', 'amp', 'admin/main/modules.tpl', 27, false),array('modifier', 'strip_tags', 'admin/main/modules.tpl', 37, false),array('modifier', 'escape', 'admin/main/modules.tpl', 37, false),)), $this); ?>
<?php func_load_lang($this, "admin/main/modules.tpl","lbl_modules,txt_modules_top_text,lbl_configure,lbl_update,lbl_modules"); ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "page_title.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_modules'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php echo $this->_tpl_vars['lng']['txt_modules_top_text']; ?>


<br /><br />

<?php ob_start(); ?>
<form action="modules.php?mode=update" method="post" name="myform">

<table cellpadding="5">
<?php $_from = $this->_tpl_vars['modules']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['m']):
?>
<tr<?php echo smarty_function_cycle(array('values' => ", class='TableSubHead'"), $this);?>
>
	<td width="20"><input type="checkbox" id="<?php echo $this->_tpl_vars['m']['module_name']; ?>
" name="<?php echo $this->_tpl_vars['m']['module_name']; ?>
"<?php if ($this->_tpl_vars['m']['active'] == 'Y'): ?> checked="checked"<?php endif; ?> /></td>
	<td width="20%" nowrap="nowrap">
<label for="<?php echo $this->_tpl_vars['m']['module_name']; ?>
">
<?php $this->assign('module_name', "module_name_".($this->_tpl_vars['m']['module_name']));  if ($this->_tpl_vars['lng'][$this->_tpl_vars['module_name']]):  echo $this->_tpl_vars['lng'][$this->_tpl_vars['module_name']];  else:  echo $this->_tpl_vars['m']['module_name'];  endif; ?>
</label>
	</td>
	<td width="80%">
<?php $this->assign('module_descr', "module_descr_".($this->_tpl_vars['m']['module_name']));  if ($this->_tpl_vars['lng'][$this->_tpl_vars['module_descr']]):  echo $this->_tpl_vars['lng'][$this->_tpl_vars['module_descr']];  else:  echo $this->_tpl_vars['m']['module_descr'];  endif; ?>
	</td>
	<td>
<?php if ($this->_tpl_vars['m']['options_url'] != "" && $this->_tpl_vars['m']['active'] == 'Y'): ?>
<a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['m']['options_url'])) ? $this->_run_mod_handler('amp', true, $_tmp) : smarty_modifier_amp($_tmp)); ?>
"><?php echo $this->_tpl_vars['lng']['lbl_configure']; ?>
</a>
<?php else: ?>
&nbsp;
<?php endif; ?>
	</td>
</tr>
<?php endforeach; endif; unset($_from); ?>
</table>
<br />

<center><input type="submit" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_update'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></center>

</form>
<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_modules'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
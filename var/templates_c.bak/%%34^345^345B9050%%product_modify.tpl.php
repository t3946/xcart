<?php /* Smarty version 2.6.12, created on 2011-10-11 06:53:29
         compiled from modules/Extra_Fields/product_modify.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'modules/Extra_Fields/product_modify.tpl', 6, false),)), $this); ?>
<?php $_from = $this->_tpl_vars['extra_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['ef']):
?>
<tr> 
<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[efields][<?php echo $this->_tpl_vars['ef']['fieldid']; ?>
]" /></td><?php endif; ?>
	<td class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['ef']['field']; ?>
</td>
	<td><input type="text" name="efields[<?php echo $this->_tpl_vars['ef']['fieldid']; ?>
]" size="24" value="<?php if ($this->_tpl_vars['ef']['is_value'] == 'Y'):  echo ((is_array($_tmp=$this->_tpl_vars['ef']['field_value'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'));  else:  echo ((is_array($_tmp=$this->_tpl_vars['ef']['value'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'));  endif; ?>" /></td>
</tr>
<?php endforeach; endif; unset($_from); ?>
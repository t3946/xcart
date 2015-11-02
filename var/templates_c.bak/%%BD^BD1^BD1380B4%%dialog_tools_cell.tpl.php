<?php /* Smarty version 2.6.12, created on 2011-10-11 05:37:57
         compiled from dialog_tools_cell.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'amp', 'dialog_tools_cell.tpl', 8, false),array('modifier', 'escape', 'dialog_tools_cell.tpl', 8, false),)), $this); ?>
<?php if ($this->_tpl_vars['cell']['separator']): ?>
<table cellpadding="0" cellspacing="0" width="80%"><tr>
<td class="NavDialogSeparator"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" alt="" /></td>
</tr></table>
<?php else: ?>
<table cellspacing="0" cellpadding="0"><tr>
<td class="NavDialogCell"><a class="<?php if ($this->_tpl_vars['cell']['style'] == 'hl'): ?>VertMenuItemsHL<?php else: ?>VertMenuItems<?php endif; ?>" href="<?php echo ((is_array($_tmp=$this->_tpl_vars['cell']['link'])) ? $this->_run_mod_handler('amp', true, $_tmp) : smarty_modifier_amp($_tmp)); ?>
" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['cell']['title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"<?php if ($this->_tpl_vars['cell']['target'] != ""): ?> target="<?php echo $this->_tpl_vars['cell']['target']; ?>
"<?php endif;  if ($this->_tpl_vars['cell']['onclick'] != ""): ?> onclick="<?php echo $this->_tpl_vars['cell']['onclick']; ?>
"<?php endif; ?>><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/rarrow.gif" alt="" /></a></td>
<td><a class="<?php if ($this->_tpl_vars['cell']['style'] == 'hl'): ?>VertMenuItemsHL<?php else: ?>VertMenuItems<?php endif; ?>" href="<?php echo ((is_array($_tmp=$this->_tpl_vars['cell']['link'])) ? $this->_run_mod_handler('amp', true, $_tmp) : smarty_modifier_amp($_tmp)); ?>
" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['cell']['title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"<?php if ($this->_tpl_vars['cell']['target'] != ""): ?> target="<?php echo $this->_tpl_vars['cell']['target']; ?>
"<?php endif; ?>><?php echo $this->_tpl_vars['cell']['title']; ?>
</a></td>
</tr></table>
<?php endif; ?>

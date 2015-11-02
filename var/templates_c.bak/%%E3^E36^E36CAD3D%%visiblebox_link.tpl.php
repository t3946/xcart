<?php /* Smarty version 2.6.12, created on 2011-10-11 05:43:52
         compiled from main/visiblebox_link.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'main/visiblebox_link.tpl', 5, false),)), $this); ?>
<?php func_load_lang($this, "main/visiblebox_link.tpl","lbl_click_to_open,lbl_click_to_close"); ?><?php if ($this->_tpl_vars['js_enabled'] != 'Y'):  $this->assign('visible', true);  endif; ?>
<table cellspacing="1" cellpadding="2">
<tr>
	<td class="ExpandSectionMark" id="close<?php echo $this->_tpl_vars['mark']; ?>
" style="<?php if ($this->_tpl_vars['visible']): ?>display: none; <?php endif; ?>" onclick="javascript: visibleBox('<?php echo $this->_tpl_vars['mark']; ?>
');"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/plus.gif" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_click_to_open'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td>
	<td class="ExpandSectionMark" id="open<?php echo $this->_tpl_vars['mark']; ?>
" style="<?php if (! $this->_tpl_vars['visible']): ?>display: none; <?php endif; ?>" onclick="javascript: visibleBox('<?php echo $this->_tpl_vars['mark']; ?>
');"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/minus.gif" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_click_to_close'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td>
	<td nowrap="nowrap"><a href="javascript: void(0);" onclick="javascript: visibleBox('<?php echo $this->_tpl_vars['mark']; ?>
');"><b><?php echo $this->_tpl_vars['title']; ?>
</b></a></td>
</tr>
</table>

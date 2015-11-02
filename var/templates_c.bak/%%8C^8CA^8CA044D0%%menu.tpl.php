<?php /* Smarty version 2.6.12, created on 2011-10-11 05:37:50
         compiled from menu.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'menu.tpl', 12, false),)), $this); ?>
<table cellspacing="1" width="100%" class="VertMenuBorder">
<tr>
<td class="VertMenuTitle">
<table cellspacing="0" cellpadding="0" width="100%"><tr>
<td></td>
<td width="100%"><?php if ($this->_tpl_vars['link_href']): ?><a href="<?php echo $this->_tpl_vars['link_href']; ?>
"><?php endif; ?><font class="VertMenuTitle"><?php echo $this->_tpl_vars['menu_title']; ?>
</font><?php if ($this->_tpl_vars['link_href']): ?></a><?php endif; ?></td>
</tr></table>
</td>
</tr>
<tr> 
<td class="VertMenuBox">
<table cellpadding="<?php echo ((is_array($_tmp=@$this->_tpl_vars['cellpadding'])) ? $this->_run_mod_handler('default', true, $_tmp, '5') : smarty_modifier_default($_tmp, '5')); ?>
" cellspacing="0" width="100%">
<tr><td><?php echo $this->_tpl_vars['menu_content']; ?>
</td></tr>
</table>
</td></tr>
</table>
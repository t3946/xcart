<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:05
         compiled from menu.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'menu.tpl', 1, false),array('modifier', 'default', 'menu.tpl', 12, false),)), $this); ?>
<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "menu.tpl"), $this); endif; ?><table cellspacing="1" width="100%" class="VertMenuBorder">
<tr>
<td class="VertMenuTitle">
<table cellspacing="0" cellpadding="0" width="100%"><tr>
<td></td>
<td width="100%" valign="middle"><?php if ($this->_tpl_vars['link_href']): ?><a href="<?php echo $this->_tpl_vars['link_href']; ?>
" <?php if ($this->_tpl_vars['id_expand'] != ""): ?>onclick="javascript: $('#<?php echo $this->_tpl_vars['id_expand']; ?>
').toggle();"<?php endif; ?>><?php endif; ?><font class="VertMenuTitle" <?php if ($this->_tpl_vars['id_expand'] != ""): ?>style="color: #0033cc;"<?php endif; ?>><?php echo $this->_tpl_vars['menu_title']; ?>
</font><?php if ($this->_tpl_vars['link_href']): ?></a><?php endif; ?> <?php if ($this->_tpl_vars['id_expand'] != ""): ?><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/br_down.png" alt="" onclick="javascript: $('#<?php echo $this->_tpl_vars['id_expand']; ?>
').toggle();" /><?php endif; ?></td>
</tr></table>
</td>
</tr>
<tr <?php if ($this->_tpl_vars['id_expand'] != ""): ?>id="<?php echo $this->_tpl_vars['id_expand']; ?>
"<?php endif; ?> <?php if ($this->_tpl_vars['content_hide'] == 'Y'): ?>style="display: none;"<?php endif; ?>>
<td class="VertMenuBox">
<table cellpadding="<?php echo ((is_array($_tmp=@$this->_tpl_vars['cellpadding'])) ? $this->_run_mod_handler('default', true, $_tmp, '5') : smarty_modifier_default($_tmp, '5')); ?>
" cellspacing="0" width="100%">
<tr><td><?php echo $this->_tpl_vars['menu_content']; ?>
</td></tr>
</table>
</td></tr>
</table>
<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "menu.tpl"), $this); endif; ?>
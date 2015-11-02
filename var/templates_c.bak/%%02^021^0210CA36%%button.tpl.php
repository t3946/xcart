<?php /* Smarty version 2.6.12, created on 2011-10-11 05:37:50
         compiled from buttons/button.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'regex_replace', 'buttons/button.tpl', 4, false),)), $this); ?>
<?php if ($this->_tpl_vars['config']['Adaptives']['platform'] == 'MacPPC' && $this->_tpl_vars['config']['Adaptives']['browser'] == 'NN'):  $this->assign('js_to_href', 'Y');  endif;  if ($this->_tpl_vars['type'] == 'input'):  $this->assign('img_type', 'INPUT type="image"');  else:  $this->assign('img_type', 'IMG');  endif;  $this->assign('js_link', ((is_array($_tmp=$this->_tpl_vars['href'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/^\s*javascript\s*:/Si", "") : smarty_modifier_regex_replace($_tmp, "/^\s*javascript\s*:/Si", "")));  if ($this->_tpl_vars['js_link'] == $this->_tpl_vars['href']):  $this->assign('js_link', "javascript: self.location='".($this->_tpl_vars['href'])."'");  else:  $this->assign('js_link', $this->_tpl_vars['href']);  if ($this->_tpl_vars['js_to_href'] != 'Y'):  $this->assign('onclick', $this->_tpl_vars['href']);  $this->assign('href', "javascript: void(0);");  endif;  endif; ?>

<table border="0" cellspacing="0" cellpadding="0" onclick="<?php echo $this->_tpl_vars['js_link']; ?>
" style="cursor: pointer;" valign="middle"<?php if ($this->_tpl_vars['title'] != ''): ?> title="<?php echo $this->_tpl_vars['title']; ?>
"<?php endif;  if ($this->_tpl_vars['class']): ?> class="<?php echo $this->_tpl_vars['class']; ?>
"<?php endif; ?>>
<tr>
<td class="Button2Off" valign="middle" onMouseOver="this.className='Button2On'" onMouseOut="this.className='Button2Off'"><font class="Button2">&nbsp;<?php if ($this->_tpl_vars['b'] == '1'): ?><b><?php endif;  echo $this->_tpl_vars['button_title']; ?>
&nbsp;<?php if ($this->_tpl_vars['b'] == 1): ?></b><?php endif; ?></font></td>
</tr>
</table>
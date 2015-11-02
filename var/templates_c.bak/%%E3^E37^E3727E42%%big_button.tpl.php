<?php /* Smarty version 2.6.12, created on 2011-10-11 06:21:51
         compiled from modules/Fast_Lane_Checkout/big_button.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'regex_replace', 'modules/Fast_Lane_Checkout/big_button.tpl', 4, false),array('modifier', 'escape', 'modules/Fast_Lane_Checkout/big_button.tpl', 36, false),)), $this); ?>
<?php if ($this->_tpl_vars['config']['Adaptives']['platform'] == 'MacPPC' && $this->_tpl_vars['config']['Adaptives']['browser'] == 'NN'):  $this->assign('js_to_href', 'Y');  endif;  if ($this->_tpl_vars['type'] == 'input'):  $this->assign('img_type', 'INPUT type="image"');  else:  $this->assign('img_type', 'IMG');  endif;  $this->assign('js_link', ((is_array($_tmp=$this->_tpl_vars['href'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/^\s*javascript\s*:/Si", "") : smarty_modifier_regex_replace($_tmp, "/^\s*javascript\s*:/Si", "")));  if ($this->_tpl_vars['js_link'] == $this->_tpl_vars['href']):  $this->assign('js_link', "javascript: self.location='".($this->_tpl_vars['href'])."'");  else:  $this->assign('js_link', $this->_tpl_vars['href']);  if ($this->_tpl_vars['js_to_href'] != 'Y'):  $this->assign('onclick', $this->_tpl_vars['href']);  $this->assign('href', "javascript: void(0);");  endif;  endif;  if ($this->_tpl_vars['config']['Adaptives']['platform'] != 'MacPPC' || $this->_tpl_vars['config']['Adaptives']['browser'] != 'NN'):  if ($this->_tpl_vars['color'] == 'red'):  $this->assign('bg_title_class', 'RedBackground');  $this->assign('sfx', '_r');  else:  $this->assign('bg_title_class', 'YellowBackground');  $this->assign('sfx', "");  endif; ?>
<table cellspacing="0" cellpadding="0" onclick="<?php echo $this->_tpl_vars['js_link']; ?>
" style="cursor: pointer;" dir="ltr">
<tr>
<td width="9" style="background-repeat: no-repeat; background-image: url(<?php echo $this->_tpl_vars['ImagesDir']; ?>
/top_cl<?php echo $this->_tpl_vars['sfx']; ?>
.gif);"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" class="BBCorner" alt="" /></td>
<td height="9" style="background-repeat: repeat-x; background-image: url(<?php echo $this->_tpl_vars['ImagesDir']; ?>
/top_b<?php echo $this->_tpl_vars['sfx']; ?>
.gif);"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" class="BBCorner" alt="" /></td>
<td width="9" style="background-repeat: no-repeat; background-image: url(<?php echo $this->_tpl_vars['ImagesDir']; ?>
/top_cr<?php echo $this->_tpl_vars['sfx']; ?>
.gif);"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" class="BBCorner" alt="" /></td>
</tr>

<tr>
<td width="9" style="background-repeat: repeat-y; background-image: url(<?php echo $this->_tpl_vars['ImagesDir']; ?>
/tab_left<?php echo $this->_tpl_vars['sfx']; ?>
.gif);"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" class="BBCorner" alt="" /></td>
<td class="<?php echo $this->_tpl_vars['bg_title_class']; ?>
"<?php echo $this->_tpl_vars['reading_direction_tag']; ?>
><font class="ProductTitle<?php if ($this->_tpl_vars['bold'] == 'N'): ?> ProductTitleRelated<?php endif; ?>">&nbsp;<?php echo $this->_tpl_vars['button_title']; ?>
&nbsp;</font><?php if ($this->_tpl_vars['arrow'] == 'Y'): ?><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/rarrow_flc.gif" class="BBCorner" alt="" /><?php endif; ?></td>
<td width="9" style="background-repeat: repeat-y; background-image: url(<?php echo $this->_tpl_vars['ImagesDir']; ?>
/tab_right<?php echo $this->_tpl_vars['sfx']; ?>
.gif);"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" class="BBCorner" alt="" /></td>
</tr>

<tr>
<td width="9" style="background-repeat: no-repeat; background-image: url(<?php echo $this->_tpl_vars['ImagesDir']; ?>
/tab_cl<?php echo $this->_tpl_vars['sfx']; ?>
.gif);"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" class="BBCorner" alt="" /></td>
<td height="9" style="background-repeat: repeat-x; background-image: url(<?php echo $this->_tpl_vars['ImagesDir']; ?>
/tab_bt<?php echo $this->_tpl_vars['sfx']; ?>
.gif);"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" class="BBCorner" alt="" /></td>
<td width="9" style="background-repeat: no-repeat; background-image: url(<?php echo $this->_tpl_vars['ImagesDir']; ?>
/tab_cr<?php echo $this->_tpl_vars['sfx']; ?>
.gif);"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" class="BBCorner" alt="" /></td>
</tr>

</table>
<?php else: ?>
<a href="<?php echo $this->_tpl_vars['href']; ?>
"<?php if ($this->_tpl_vars['onclick'] != ''): ?> onclick="<?php echo $this->_tpl_vars['onclick']; ?>
"<?php endif;  if ($this->_tpl_vars['title'] != ''): ?> title="<?php echo ((is_array($_tmp=$this->_tpl_vars['title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"<?php endif;  if ($this->_tpl_vars['target'] != ''): ?> target="<?php echo $this->_tpl_vars['target']; ?>
"<?php endif; ?>><font class="FormButton"><?php echo $this->_tpl_vars['button_title']; ?>
 <<?php echo $this->_tpl_vars['img_type']; ?>
 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/go_image.tpl", 'smarty_include_vars' => array('full_url' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>></font></a>
<?php endif; ?>
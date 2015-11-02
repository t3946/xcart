<?php /* Smarty version 2.6.12, created on 2011-10-11 06:21:51
         compiled from modules/Fast_Lane_Checkout/tabs_menu.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'math', 'modules/Fast_Lane_Checkout/tabs_menu.tpl', 16, false),array('modifier', 'amp', 'modules/Fast_Lane_Checkout/tabs_menu.tpl', 67, false),)), $this); ?>
<?php func_load_lang($this, "modules/Fast_Lane_Checkout/tabs_menu.tpl","lbl_continue_shopping"); ?><div align="right">
<table width="100%"><tr><td align="left">
<?php if ($this->_tpl_vars['last_categoryid'] != 0):  $this->assign('last_categoryid', "?cat=".($this->_tpl_vars['last_categoryid']));  else:  $this->assign('last_categoryid', "");  endif;  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Fast_Lane_Checkout/big_button.tpl", 'smarty_include_vars' => array('button_title' => $this->_tpl_vars['lng']['lbl_continue_shopping'],'style' => 'button','href' => "home.php".($this->_tpl_vars['last_categoryid']))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</td><td align="right">
<table cellpadding="0" cellspacing="0" width="70%">

<tr>
<?php $this->assign('columns_counter', 0);  $this->assign('curpos', 'B');  $_from = $this->_tpl_vars['checkout_tabs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['step']):
 echo smarty_function_math(array('assign' => 'columns_counter','equation' => "x+1",'x' => $this->_tpl_vars['columns_counter']), $this);?>

<td>
<?php if ($this->_tpl_vars['step']['selected'] == 'Y'):  $this->assign('curpos', 'A'); ?>
<table cellspacing="0" cellpadding="0" width="100%">
<tr>
<td width="50%"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" class="Spc" alt="" /></td>
<td width="19"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/cart_checkout.gif" width="19" height="16" alt="" /></td>
<td width="50%"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" class="Spc" alt="" /></td>
</tr>
</table>
<?php else: ?>&nbsp;<?php endif; ?></td>
<?php endforeach; endif; unset($_from); ?>
</tr>

<tr>
<td colspan="<?php echo $this->_tpl_vars['columns_counter']; ?>
"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" width="1" height="3" alt="" /></td>
</tr>

<tr>
<?php $this->assign('cnt', 0);  $this->assign('curpos', 'B');  $this->assign('mark', 'B');  $_from = $this->_tpl_vars['checkout_tabs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['step']):
 echo smarty_function_math(array('assign' => 'cnt','equation' => "x+1",'x' => $this->_tpl_vars['cnt']), $this);?>

<td>
<table cellspacing="0" cellpadding="0" width="100%">
<tr>
<td<?php if ($this->_tpl_vars['cnt'] > 1): ?> class="<?php if ($this->_tpl_vars['curpos'] == 'B'): ?>LineBeforeCart<?php else: ?>LineAfterCart<?php endif; ?>"<?php endif; ?> width="50%"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" width="1" height="2" alt="" /></td>
<td class="<?php if ($this->_tpl_vars['curpos'] == 'B'): ?>LineBeforeCart<?php else: ?>LineAfterCart<?php endif; ?>" width="2"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" width="2" height="2" alt="" /></td>
<?php if ($this->_tpl_vars['step']['selected'] == 'Y'):  $this->assign('curpos', 'A');  endif; ?>
<td <?php if ($this->_tpl_vars['cnt'] < $this->_tpl_vars['columns_counter']): ?>class="<?php if ($this->_tpl_vars['curpos'] == 'B'): ?>LineBeforeCart<?php else: ?>LineAfterCart<?php endif; ?>"<?php endif; ?> width="50%"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" width="1" height="2" alt="" /></td>
</tr>
<tr>
<td width="50%"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" width="1" height="3" alt="" /></td>
<td class="<?php if ($this->_tpl_vars['mark'] == 'B'): ?>LineBeforeCart<?php else: ?>LineAfterCart<?php endif; ?>" width="2"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" width="2" height="5" alt="" /></td>
<?php if ($this->_tpl_vars['mark'] != $this->_tpl_vars['curpos']):  $this->assign('mark', 'A');  endif; ?>
<td width="50%"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" width="1" height="3" alt="" /></td>
</tr>
</table>
</td>
<?php endforeach; endif; unset($_from); ?>
</tr>

<tr>
<td colspan="<?php echo $this->_tpl_vars['columns_counter']; ?>
"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" width="1" height="3" alt="" /></td>
</tr>

<tr>
<?php $this->assign('hide_link', 0);  $_from = $this->_tpl_vars['checkout_tabs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['step']):
?>
<td align="center"><?php if ($this->_tpl_vars['step']['link'] != "" && $this->_tpl_vars['step']['selected'] != 'Y' && $this->_tpl_vars['hide_link'] == 0): ?><a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['step']['link'])) ? $this->_run_mod_handler('amp', true, $_tmp) : smarty_modifier_amp($_tmp)); ?>
" class="CheckoutTab" style="color: #0000FF;"><?php echo $this->_tpl_vars['step']['title']; ?>
</a><?php else: ?><font class="CheckoutTabSel" style="color: #000000;"><?php echo $this->_tpl_vars['step']['title']; ?>
</font><?php endif;  if ($this->_tpl_vars['step']['selected'] == 'Y'):  $this->assign('hide_link', 1);  endif; ?></td>
<?php endforeach; endif; unset($_from); ?>
</tr>

</table>
</td></tr></table>
</div>
<br />

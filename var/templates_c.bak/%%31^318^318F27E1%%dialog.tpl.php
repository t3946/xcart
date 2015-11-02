<?php /* Smarty version 2.6.12, created on 2011-10-11 05:37:51
         compiled from dialog.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'math', 'dialog.tpl', 13, false),array('modifier', 'default', 'dialog.tpl', 43, false),)), $this); ?>
<?php func_load_lang($this, "dialog.tpl","lbl_sku"); ?><?php if ($this->_tpl_vars['printable'] != ''): ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog_printable.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php else: ?>
<?php if ($this->_tpl_vars['title'] || $this->_tpl_vars['product']): ?>
<table cellspacing="0" <?php echo $this->_tpl_vars['extra']; ?>
>
<tr> 
<td class="DialogTitle valign-top"><?php if ($this->_tpl_vars['align'] == 'center'): ?><center><b><?php echo $this->_tpl_vars['title']; ?>
</b></center><?php else: ?><b><?php echo $this->_tpl_vars['title']; ?>
</b><?php endif; ?></td>
<?php if ($this->_tpl_vars['product'] != "" && $this->_tpl_vars['save_label'] == 'true' && $this->_tpl_vars['product']['taxed_price'] > 0 && $this->_tpl_vars['product']['list_price'] > 0 && $this->_tpl_vars['product']['list_price'] > $this->_tpl_vars['product']['taxed_price']): ?>
<td align="right" valign="center" style="padding-right: 10px;" id="save_percent_box">
<TABLE border="0" cellpadding="0" cellspacing="0">
<TR><TD nowrap height="20" style="background-color: #cc3333; color: white; font-size: 15px; font-weight: bold;" align="center">
&nbsp;SAVE&nbsp;<?php echo smarty_function_math(array('equation' => "100-(price/lprice)*100",'price' => $this->_tpl_vars['product']['taxed_price'],'lprice' => $this->_tpl_vars['product']['list_price'],'format' => "%3.0f",'assign' => 'discount'), $this);?>
<SPAN id="save_percent"><?php echo $this->_tpl_vars['discount']; ?>
</SPAN>%&nbsp;
</TD></TR>
</TABLE>
</td>
<?php endif; ?>
</tr>
</table>
<?php endif; ?>
<?php if ($this->_tpl_vars['product_sku'] != "" || $this->_tpl_vars['product_free_ship'] != ""): ?>
<table cellspacing="0" <?php echo $this->_tpl_vars['extra']; ?>
>
<tr>
<td class="DialogTitle valign-top">
<?php if ($this->_tpl_vars['product_sku'] != ""): ?><font color="#006600" class="DialogTitleT"><?php echo $this->_tpl_vars['lng']['lbl_sku']; ?>
: <?php echo $this->_tpl_vars['product_sku']; ?>
</font><br /><?php endif; ?>
</td>
<?php if ($this->_tpl_vars['product_free_ship'] != ""): ?>
<td align="right" valign="center" nowrap="nowrap" style="padding-right: 10px;">
<table border="0" cellpadding="0" cellspacing="0">
<tr>
<td height="20" style="background-color: #006600; color: white; font-size: 15px; font-weight: bold; padding-left: 4px; padding-right: 4px;" align="center">
<?php echo $this->_tpl_vars['product_free_ship']; ?>

</td>
</tr>
</table>
</td>
<?php endif; ?>
</tr>
</table>
<?php endif; ?>
<table cellspacing="0" <?php echo $this->_tpl_vars['extra']; ?>
>
<tr><td colspan="2" class="DialogBorder"><table cellspacing="1" class="DialogBox">
<tr><td class="DialogBox" valign="<?php echo ((is_array($_tmp=@$this->_tpl_vars['valign'])) ? $this->_run_mod_handler('default', true, $_tmp, 'top') : smarty_modifier_default($_tmp, 'top')); ?>
"><?php echo $this->_tpl_vars['content']; ?>

</td></tr>
</table></td></tr>
</table>
<?php endif; ?>
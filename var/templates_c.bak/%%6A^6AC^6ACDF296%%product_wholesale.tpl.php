<?php /* Smarty version 2.6.12, created on 2011-10-11 06:53:29
         compiled from modules/Wholesale_Trading/product_wholesale.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'formatprice', 'modules/Wholesale_Trading/product_wholesale.tpl', 37, false),array('modifier', 'strip_tags', 'modules/Wholesale_Trading/product_wholesale.tpl', 83, false),array('modifier', 'escape', 'modules/Wholesale_Trading/product_wholesale.tpl', 83, false),array('function', 'cycle', 'modules/Wholesale_Trading/product_wholesale.tpl', 43, false),)), $this); ?>
<?php func_load_lang($this, "modules/Wholesale_Trading/product_wholesale.tpl","txt_wholesales_top_text,lbl_note,lbl_wholesale_admin_note_small,lbl_more,lbl_wholesale_admin_note,lbl_note,txt_edit_product_group,lbl_quantity,lbl_price_per_item,lbl_membership,lbl_all,lbl_all,lbl_add_new_price,lbl_all,lbl_add_update,lbl_delete_selected,lbl_generate_discounts,lbl_wholesale_prices"); ?><?php if ($this->_tpl_vars['active_modules']['Wholesale_Trading'] != ""): ?>

<?php echo $this->_tpl_vars['lng']['txt_wholesales_top_text']; ?>


<br /><br />

<?php ob_start(); ?>

<form action="product_modify.php" method="post" name="pricing_form">
<input type="hidden" name="productid" value="<?php echo $this->_tpl_vars['product']['productid']; ?>
" />
<input type="hidden" name="mode" value="wholesales_modify" />
<input type="hidden" name="geid" value="<?php echo $this->_tpl_vars['geid']; ?>
" />

<b><?php echo $this->_tpl_vars['lng']['lbl_note']; ?>
:</b> <span id="wholesale_admin_note_small"><?php echo $this->_tpl_vars['lng']['lbl_wholesale_admin_note_small']; ?>
 <a href="javascript: void(0);" onclick="javascript: document.getElementById('wholesale_admin_note_small').style.display = 'none'; document.getElementById('wholesale_admin_note').style.display = '';"><?php echo $this->_tpl_vars['lng']['lbl_more']; ?>
</a></span><span id="wholesale_admin_note" style="display: none;"><?php echo $this->_tpl_vars['lng']['lbl_wholesale_admin_note']; ?>
</span><br />
<br />
<table <?php if ($this->_tpl_vars['geid'] != ''): ?>cellspacing="0" cellpadding="4"<?php else: ?>cellspacing="1" cellpadding="2"<?php endif; ?> width="100%">

<?php if ($this->_tpl_vars['geid'] != ''): ?>
<tr>
    <td width="15" class="TableSubHead">&nbsp;</td>
    <td class="TableSubHead" colspan="4"><b>* <?php echo $this->_tpl_vars['lng']['lbl_note']; ?>
:</b> <?php echo $this->_tpl_vars['lng']['txt_edit_product_group']; ?>
</td>
</tr>
<?php endif; ?>
<tr class="TableHead">
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead">&nbsp;</td><?php endif; ?>
	<td width="15" class="DataTable"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" width="15" height="1" border="0" /></td>
	<td width="25%" class="DataTable"><?php echo $this->_tpl_vars['lng']['lbl_quantity']; ?>
</td>
	<td width="25%" class="DataTable"><?php echo $this->_tpl_vars['lng']['lbl_price_per_item']; ?>
 (<?php echo $this->_tpl_vars['config']['General']['currency_symbol']; ?>
)</td>
	<td width="50%"><?php echo $this->_tpl_vars['lng']['lbl_membership']; ?>
</td>
</tr>

<tr height="20">
<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead">&nbsp;</td><?php endif; ?>
	<td class="DataTable">&nbsp;</td>
	<td class="DataTable"><b>1</b></td>
	<td class="DataTable"><b><?php echo ((is_array($_tmp=$this->_tpl_vars['product']['price'])) ? $this->_run_mod_handler('formatprice', true, $_tmp) : smarty_modifier_formatprice($_tmp)); ?>
</b></td>
	<td colspan="2"><b><?php echo $this->_tpl_vars['lng']['lbl_all']; ?>
</b></td>
</tr>

<?php $_from = $this->_tpl_vars['pricing']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
 if ($this->_tpl_vars['v']['membershipid'] > 0 || $this->_tpl_vars['v']['quantity'] > 1): ?>
<tr<?php echo smarty_function_cycle(array('values' => ' class="TableSubHead",'), $this);?>
>
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[w_price][<?php echo $this->_tpl_vars['v']['priceid']; ?>
]" /></td><?php endif; ?>
	<td width="15" class="DataTable"><input type="checkbox" value="Y" name="wpids[<?php echo $this->_tpl_vars['v']['priceid']; ?>
]" /></td>
	<td class="DataTable"><?php echo $this->_tpl_vars['v']['quantity']; ?>
</td>
	<td class="DataTable"><input type="text" maxlength="16" size="16" name="wprices[<?php echo $this->_tpl_vars['v']['priceid']; ?>
][price]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['v']['price'])) ? $this->_run_mod_handler('formatprice', true, $_tmp) : smarty_modifier_formatprice($_tmp)); ?>
" style="width=100%" /></td>
	<td><select name="wprices[<?php echo $this->_tpl_vars['v']['priceid']; ?>
][membershipid]" style="width=100%">
<?php if ($this->_tpl_vars['v']['quantity'] > 1): ?><option value=""><?php echo $this->_tpl_vars['lng']['lbl_all']; ?>
</option><?php endif;  $_from = $this->_tpl_vars['memberships']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['m']):
?>
<option value="<?php echo $this->_tpl_vars['m']['membershipid']; ?>
"<?php if ($this->_tpl_vars['v']['membershipid'] == $this->_tpl_vars['m']['membershipid']): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['m']['membership']; ?>
</option>
<?php endforeach; endif; unset($_from); ?>
	</select></td>
</tr>
<?php endif;  endforeach; endif; unset($_from); ?>

<tr>
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead">&nbsp;</td><?php endif; ?>
	<td colspan="4">&nbsp;</td>
</tr>

<tr>
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead">&nbsp;</td><?php endif; ?>
	<td colspan="4"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_add_new_price'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>

<tr>
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[new_w_price]" /></td><?php endif; ?>
	<td>&nbsp;</td>
	<td align="center"><input type="text" size="8" name="newquantity" style="width=100%" /></td>
	<td><input type="text" size="16" name="newprice" value="<?php echo $this->_tpl_vars['zero']; ?>
" style="width=100%" /></td>
	<td width="40%"><select name="membershipid" style="width=100%">
<option value=""><?php echo $this->_tpl_vars['lng']['lbl_all']; ?>
</option>
<?php $_from = $this->_tpl_vars['memberships']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['m']):
?>
<option value="<?php echo $this->_tpl_vars['m']['membershipid']; ?>
"><?php echo $this->_tpl_vars['m']['membership']; ?>
</option>
<?php endforeach; endif; unset($_from); ?>
	</select></td>
</tr>

<tr>
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead">&nbsp;</td><?php endif; ?>
	<td colspan="4"><br /><input type="submit" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_add_update'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
<?php if ($this->_tpl_vars['pricing'] != ''): ?> 
&nbsp;&nbsp;&nbsp;
	<input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_delete_selected'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: document.pricing_form.mode.value='wholesales_delete'; document.pricing_form.submit();" />
<?php endif; ?>
&nbsp;&nbsp;&nbsp;
	<input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_generate_discounts'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: document.pricing_form.mode.value='generate_discounts'; document.pricing_form.submit();" />
	</td>
</tr>

</table>
</form>

<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_wholesale_prices'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
<?php /* Smarty version 2.6.12, created on 2011-10-11 06:22:21
         compiled from modules/Fast_Lane_Checkout/shipping_methods.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'modules/Fast_Lane_Checkout/shipping_methods.tpl', 71, false),array('modifier', 'replace', 'modules/Fast_Lane_Checkout/shipping_methods.tpl', 72, false),array('modifier', 'trademark', 'modules/Fast_Lane_Checkout/shipping_methods.tpl', 80, false),)), $this); ?>
<?php func_load_lang($this, "modules/Fast_Lane_Checkout/shipping_methods.tpl","lbl_shipping_address,txt_for_fastlane_checkout_delivery,lbl_delivery_methods,lbl_no_shipping_for_location"); ?><table cellpadding="5" cellspacing="5" width="100%">

<tr>
<td valign="top" width="30%">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_shipping_address'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  if ($this->_tpl_vars['userinfo']):  echo $this->_tpl_vars['userinfo']['s_address']; ?>
<br />
<?php if ($this->_tpl_vars['userinfo']['s_address_2']):  echo $this->_tpl_vars['userinfo']['s_address_2']; ?>
<br />
<?php endif;  echo $this->_tpl_vars['userinfo']['s_city']; ?>
<br />
<?php echo $this->_tpl_vars['userinfo']['s_statename']; ?>
<br />
<?php echo $this->_tpl_vars['userinfo']['s_countryname']; ?>
<br />
<?php echo $this->_tpl_vars['userinfo']['s_zipcode']; ?>

<?php else: ?>
No data
<?php endif; ?>

<?php if ($this->_tpl_vars['login'] != ""): ?>
<br /><br />
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/modify.tpl", 'smarty_include_vars' => array('href' => "register.php?mode=update&action=cart")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

</td>
<td valign="top" width="70%">



<?php if ($this->_tpl_vars['cart']['shipping_groups'] != ""): ?>

<?php if ($this->_tpl_vars['login'] != "" || $this->_tpl_vars['config']['General']['apply_default_country'] == 'Y' || $this->_tpl_vars['cart']['shipping_cost'] > 0):  $_from = $this->_tpl_vars['cart']['shipping_groups']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
 $this->assign('found_any_shipping', 'N');  $this->assign('selected_any', 'N');  echo smarty_function_cycle(array('values' => ''), $this);?>

<?php $this->assign('delivery_text', ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['txt_for_fastlane_checkout_delivery'])) ? $this->_run_mod_handler('replace', true, $_tmp, 'XX', ($this->_tpl_vars['v']['m_city']).", ".($this->_tpl_vars['v']['m_state']).", ".($this->_tpl_vars['v']['m_country']).".") : smarty_modifier_replace($_tmp, 'XX', ($this->_tpl_vars['v']['m_city']).", ".($this->_tpl_vars['v']['m_state']).", ".($this->_tpl_vars['v']['m_country']).".")))) ? $this->_run_mod_handler('replace', true, $_tmp, 'YY', ($this->_tpl_vars['v']['group_name'])) : smarty_modifier_replace($_tmp, 'YY', ($this->_tpl_vars['v']['group_name'])))); ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/subheader.tpl", 'smarty_include_vars' => array('title' => ($this->_tpl_vars['lng']['lbl_delivery_methods'])." ".($this->_tpl_vars['delivery_text']))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php $_from = $this->_tpl_vars['shippings'][$this->_tpl_vars['k']]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['s']):
?>
	<?php if ($this->_tpl_vars['s']['active'] == 'Y' && $this->_tpl_vars['s']['allowed'] == '1'): ?>
	<?php $this->assign('found_any_shipping', 'Y'); ?>
	<table cellpadding="1" cellspacing="0" width="100%" <?php echo smarty_function_cycle(array('values' => " class='TableSubHead', "), $this);?>
>
	<tr>
		<td width="5"><input type="radio" id="shippingid<?php echo $this->_tpl_vars['s']['shippingid']; ?>
" name="shippingids[<?php echo $this->_tpl_vars['k']; ?>
]" value="<?php echo $this->_tpl_vars['s']['shippingid']; ?>
"<?php if ($this->_tpl_vars['s']['shippingid'] == $this->_tpl_vars['cart']['shippingids'][$this->_tpl_vars['k']] || ( $this->_tpl_vars['cart']['shippingids'][$this->_tpl_vars['k']] == "" && $this->_tpl_vars['selected_any'] == 'N' )):  $this->assign('selected_any', 'Y'); ?> checked="checked"<?php endif;  if ($this->_tpl_vars['allow_cod']): ?> onclick="javascript: display_cod(<?php if ($this->_tpl_vars['s']['is_cod'] == 'Y'): ?>true<?php else: ?>false<?php endif; ?>);"<?php endif; ?> /></td>
		<td><label for="shippingid<?php echo $this->_tpl_vars['s']['shippingid']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['s']['shipping'])) ? $this->_run_mod_handler('trademark', true, $_tmp, $this->_tpl_vars['insert_trademark']) : smarty_modifier_trademark($_tmp, $this->_tpl_vars['insert_trademark']));  if ($this->_tpl_vars['s']['shipping_time'] != ""): ?> - <?php echo $this->_tpl_vars['s']['shipping_time'];  endif;  if ($this->_tpl_vars['config']['Appearance']['display_shipping_cost'] == 'Y' && ( $this->_tpl_vars['login'] != "" || $this->_tpl_vars['config']['General']['apply_default_country'] == 'Y' || $this->_tpl_vars['cart']['shipping_cost'] > 0 )): ?>: <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['s']['rate'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?></label></td>
	</tr>
	<?php if ($this->_tpl_vars['s']['warning'] != ""): ?>
	<tr>
	<td>&nbsp;</td>
	<td class="SmallText"><?php echo $this->_tpl_vars['s']['warning']; ?>
</td>
	</tr>
	<?php endif; ?>
	</table>
	<?php endif; ?>
	<?php endforeach; endif; unset($_from); ?>

<?php if ($this->_tpl_vars['found_any_shipping'] != 'Y' && $this->_tpl_vars['need_shipping']): ?>
<font class="ErrorMessage"><?php echo $this->_tpl_vars['lng']['lbl_no_shipping_for_location']; ?>
</font><br />
<br />
<?php endif; ?>

<br /><br />
<?php endforeach; endif; unset($_from);  endif;  else: ?>
<input type="hidden" name="shippingid" value="0" />
<?php endif;  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/dhl_ext_countries.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</td>
</tr>
</table>
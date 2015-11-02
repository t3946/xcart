<?php /* Smarty version 2.6.12, created on 2011-10-11 07:23:41
         compiled from mail/html/order_invoice_mnf.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'config_load', 'mail/html/order_invoice_mnf.tpl', 3, false),array('modifier', 'date_format', 'mail/html/order_invoice_mnf.tpl', 20, false),array('modifier', 'trademark', 'mail/html/order_invoice_mnf.tpl', 20, false),)), $this); ?>
<?php func_load_lang($this, "mail/html/order_invoice_mnf.tpl","lbl_invoice,lbl_date,lbl_order_id,lbl_delivery,lbl_phone_1_title,lbl_phone_2_title,lbl_fax,lbl_email,lbl_company,lbl_tax_number,lbl_first_name,lbl_last_name,lbl_phone,lbl_fax,lbl_email,lbl_url,lbl_shipping_address,lbl_first_name,lbl_last_name,lbl_address,lbl_city,lbl_county,lbl_state,lbl_country,lbl_zip_code,lbl_additional_information,lbl_products_ordered,lbl_sku,lbl_product,lbl_qty,lbl_options,lbl_download"); ?><?php if ($this->_tpl_vars['customer'] != ''):  $this->assign('_userinfo', $this->_tpl_vars['customer']);  else:  $this->assign('_userinfo', $this->_tpl_vars['userinfo']);  endif;  echo smarty_function_config_load(array('file' => ($this->_tpl_vars['skin_config'])), $this);?>

<?php if ($this->_tpl_vars['is_nomail'] != 'Y'): ?>
<p />
<?php endif; ?>
<table cellspacing="0" cellpadding="0" width="<?php if ($this->_tpl_vars['is_nomail'] == 'Y'): ?>100%<?php else: ?>600<?php endif; ?>" bgcolor="#ffffff">
<tr>
	<td>
	<table cellspacing="0" cellpadding="0" width="100%" border="0">
	<tr>
		<td valign="top"><br /><br /><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/companyname_small.gif" alt="" /></td>
		<td width="100%">
		<table cellspacing="0" cellpadding="2" width="100%">
		<tr>
			<td width="30">&nbsp;</td>
			<td valign="top">
<font style="FONT-SIZE: 28px"><b style="text-transform: uppercase;"><?php echo $this->_tpl_vars['lng']['lbl_invoice']; ?>
</b></font>
<br /><br />
<b><?php echo $this->_tpl_vars['lng']['lbl_date']; ?>
:</b> <?php echo ((is_array($_tmp=$this->_tpl_vars['order']['date'])) ? $this->_run_mod_handler('date_format', true, $_tmp, $this->_tpl_vars['config']['Appearance']['datetime_format']) : smarty_modifier_date_format($_tmp, $this->_tpl_vars['config']['Appearance']['datetime_format'])); ?>
<br /><b><?php echo $this->_tpl_vars['lng']['lbl_order_id']; ?>
:</b> <?php echo $this->_tpl_vars['order']['order_prefix'];  echo $this->_tpl_vars['order']['orderid']; ?>
<br /><?php if ($this->_tpl_vars['show_shipping'] == 'Y'): ?><b><?php echo $this->_tpl_vars['lng']['lbl_delivery']; ?>
:</b><br /><?php $_from = $this->_tpl_vars['order']['shipping_groups']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
 if ($this->_tpl_vars['k'] == $this->_tpl_vars['manufacturerid']):  echo ((is_array($_tmp=$this->_tpl_vars['v']['shipping'])) ? $this->_run_mod_handler('trademark', true, $_tmp, '') : smarty_modifier_trademark($_tmp, ''));  endif;  endforeach; endif; unset($_from);  else: ?><br /><?php endif; ?><br /><br /><br /><br /><br />
			</td>
			<td valign="bottom" align="right">
<b><?php echo $this->_tpl_vars['config']['Company']['operating_company_name']; ?>
</b><br />
<?php echo $this->_tpl_vars['config']['Company']['location_address']; ?>
,<br /><?php echo $this->_tpl_vars['config']['Company']['location_city'];  if ($this->_tpl_vars['config']['Company']['location_country_has_states']): ?>, <?php echo $this->_tpl_vars['config']['Company']['location_state_name'];  endif; ?><br />
<?php echo $this->_tpl_vars['config']['Company']['location_zipcode']; ?>
, <?php echo $this->_tpl_vars['config']['Company']['location_country_name']; ?>
<br />
<?php if ($this->_tpl_vars['config']['Company']['company_phone']):  echo $this->_tpl_vars['lng']['lbl_phone_1_title']; ?>
: <?php echo $this->_tpl_vars['config']['Company']['company_phone']; ?>
<br /><?php endif;  if ($this->_tpl_vars['config']['Company']['company_phone_2']):  echo $this->_tpl_vars['lng']['lbl_phone_2_title']; ?>
: <?php echo $this->_tpl_vars['config']['Company']['company_phone_2']; ?>
<br /><?php endif;  if ($this->_tpl_vars['config']['Company']['company_fax']):  echo $this->_tpl_vars['lng']['lbl_fax']; ?>
: <?php echo $this->_tpl_vars['config']['Company']['company_fax']; ?>
<br /><?php endif;  if ($this->_tpl_vars['config']['Company']['orders_department']):  echo $this->_tpl_vars['lng']['lbl_email']; ?>
: <?php echo $this->_tpl_vars['config']['Company']['orders_department']; ?>
<br /><?php endif;  if ($this->_tpl_vars['order']['applied_taxes']): ?>
<br />
<?php $_from = $this->_tpl_vars['order']['applied_taxes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['tax_name'] => $this->_tpl_vars['tax']):
 echo $this->_tpl_vars['tax']['regnumber']; ?>
<br />
<?php endforeach; endif; unset($_from);  endif; ?>
			</td>
		</tr>
		</table>
		</td>
	</tr>
	</table>
	<table cellspacing="0" cellpadding="0" width="100%" border="0">
	<tr>
		<td><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" alt="" /></td>
	</tr>
	<tr>
		<td bgcolor="#000000">  <hr style="width:100%;margin: 0px;" /></td>
	</tr>
	<tr>
		<td><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" width="1" alt="" /></td>
	</tr>
	</table>
	<br />
	<table cellspacing="0" cellpadding="0" width="45%" border="0">
<?php if ($this->_tpl_vars['_userinfo']['default_fields']['company']): ?>
	<tr>
		<td><b><?php echo $this->_tpl_vars['lng']['lbl_company']; ?>
:</b></td>
		<td><?php echo $this->_tpl_vars['order']['company']; ?>
</td>
	</tr>
<?php endif;  if ($this->_tpl_vars['_userinfo']['default_fields']['tax_number']): ?>
	<tr>
		<td><b><?php echo $this->_tpl_vars['lng']['lbl_tax_number']; ?>
:</b></td>
		<td><?php echo $this->_tpl_vars['order']['tax_number']; ?>
</td>
	</tr>
<?php endif;  if ($this->_tpl_vars['_userinfo']['default_fields']['firstname']): ?>
	<tr>
		<td nowrap="nowrap"><b><?php echo $this->_tpl_vars['lng']['lbl_first_name']; ?>
:</b></td>
		<td><?php echo $this->_tpl_vars['order']['firstname']; ?>
</td>
	</tr>
<?php endif;  if ($this->_tpl_vars['_userinfo']['default_fields']['lastname']): ?>
	<tr>
		<td nowrap="nowrap"><b><?php echo $this->_tpl_vars['lng']['lbl_last_name']; ?>
:</b></td>
		<td><?php echo $this->_tpl_vars['order']['lastname']; ?>
</td>
	</tr>
<?php endif;  if ($this->_tpl_vars['_userinfo']['default_fields']['phone']): ?>
	<tr>
		<td><b><?php echo $this->_tpl_vars['lng']['lbl_phone']; ?>
:</b></td>
		<td><?php echo $this->_tpl_vars['order']['phone']; ?>
</td>
	</tr>
<?php endif;  if ($this->_tpl_vars['_userinfo']['default_fields']['fax']): ?>
	<tr>
		<td><b><?php echo $this->_tpl_vars['lng']['lbl_fax']; ?>
:</b></td>
		<td><?php echo $this->_tpl_vars['order']['fax']; ?>
</td>
	</tr>
<?php endif;  if ($this->_tpl_vars['_userinfo']['default_fields']['email']): ?>
	<tr>
		<td><b><?php echo $this->_tpl_vars['lng']['lbl_email']; ?>
:</b></td>
		<td><?php echo $this->_tpl_vars['order']['email']; ?>
</td>
	</tr>
<?php endif;  if ($this->_tpl_vars['_userinfo']['default_fields']['url']): ?>
	<tr>
		<td><b><?php echo $this->_tpl_vars['lng']['lbl_url']; ?>
:</b></td>
		<td><?php echo $this->_tpl_vars['order']['url']; ?>
</td>
	</tr>
<?php endif;  $_from = $this->_tpl_vars['_userinfo']['additional_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
 if ($this->_tpl_vars['v']['section'] == 'C' || $this->_tpl_vars['v']['section'] == 'P'): ?>
	<tr>
		<td><b><?php echo $this->_tpl_vars['v']['title']; ?>
:</b></td>
        <td><?php echo $this->_tpl_vars['v']['value']; ?>
</td>
	</tr>
	<?php endif;  endforeach; endif; unset($_from); ?>
	</table>
	<br />
	<table cellspacing="0" cellpadding="0" width="100%" border="0">
	<tr>
		<td height="25"><b><?php echo $this->_tpl_vars['lng']['lbl_shipping_address']; ?>
</b></td>
	</tr>
	<tr>
		<td bgcolor="#000000" height="2">  <hr style="width:100%;margin: 0px;" /></td>
	</tr>
	<tr>
		<td><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" width="1" alt="" /></td>
	</tr>
	<tr>
		<td>
		<table cellspacing="0" cellpadding="0" width="45%" border="0">
<?php if ($this->_tpl_vars['_userinfo']['default_fields']['s_firstname']): ?>
		<tr>
			<td><b><?php echo $this->_tpl_vars['lng']['lbl_first_name']; ?>
:</b> </td>
			<td><?php echo $this->_tpl_vars['order']['s_firstname']; ?>
</td>
		</tr>
<?php endif;  if ($this->_tpl_vars['_userinfo']['default_fields']['s_lastname']): ?>
		<tr>
			<td><b><?php echo $this->_tpl_vars['lng']['lbl_last_name']; ?>
:</b> </td>
			<td><?php echo $this->_tpl_vars['order']['s_lastname']; ?>
</td>
		</tr>
<?php endif;  $_from = $this->_tpl_vars['_userinfo']['additional_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
 if ($this->_tpl_vars['v']['section'] == 'S'): ?>
		<tr>
			<td><b><?php echo $this->_tpl_vars['v']['title']; ?>
:</b></td>
			<td><?php echo $this->_tpl_vars['v']['value']; ?>
</td>
		</tr>
<?php endif;  endforeach; endif; unset($_from);  if ($this->_tpl_vars['_userinfo']['default_fields']['s_address']): ?>
		<tr>
			<td><b><?php echo $this->_tpl_vars['lng']['lbl_address']; ?>
:</b> </td>
			<td><?php echo $this->_tpl_vars['order']['s_address']; ?>
<br /><?php echo $this->_tpl_vars['order']['s_address_2']; ?>
</td>
		</tr>
<?php endif;  if ($this->_tpl_vars['_userinfo']['default_fields']['s_city']): ?>
		<tr>
			<td><b><?php echo $this->_tpl_vars['lng']['lbl_city']; ?>
:</b> </td>
			<td><?php echo $this->_tpl_vars['order']['s_city']; ?>
</td>
		</tr>
<?php endif;  if ($this->_tpl_vars['_userinfo']['default_fields']['s_county'] && $this->_tpl_vars['config']['General']['use_counties'] == 'Y'): ?>
		<tr>
			<td><b><?php echo $this->_tpl_vars['lng']['lbl_county']; ?>
:</b> </td>
			<td><?php echo $this->_tpl_vars['order']['s_countyname']; ?>
</td>
		</tr>
<?php endif;  if ($this->_tpl_vars['_userinfo']['default_fields']['s_state']): ?>
		<tr>
			<td><b><?php echo $this->_tpl_vars['lng']['lbl_state']; ?>
:</b> </td>
			<td><?php echo $this->_tpl_vars['order']['s_statename']; ?>
</td>
		</tr>
<?php endif;  if ($this->_tpl_vars['_userinfo']['default_fields']['s_country']): ?>
		<tr>
			<td><b><?php echo $this->_tpl_vars['lng']['lbl_country']; ?>
:</b> </td>
			<td><?php echo $this->_tpl_vars['order']['s_countryname']; ?>
</td>
		</tr>
<?php endif;  if ($this->_tpl_vars['_userinfo']['default_fields']['s_zipcode']): ?>
		<tr>
			<td><b><?php echo $this->_tpl_vars['lng']['lbl_zip_code']; ?>
:</b> </td>
			<td><?php echo $this->_tpl_vars['order']['s_zipcode']; ?>
</td>
		</tr>
<?php endif; ?>
		</table>
        </td>
	</tr>

<?php $this->assign('is_header', "");  $_from = $this->_tpl_vars['_userinfo']['additional_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
 if ($this->_tpl_vars['v']['section'] == 'A'):  if ($this->_tpl_vars['is_header'] == ''): ?>
<tr>
	<td>&nbsp;</td>
</tr>
<tr>
	<td height="25"><b><?php echo $this->_tpl_vars['lng']['lbl_additional_information']; ?>
</b></td>
</tr>
<tr>
	<td bgcolor="#000000" height="2">  <hr style="width:100%;margin: 0px;" /></td>
	<td width="55%"><img height="2" src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" width="1" alt="" /></td>
</tr>
<tr>
	<td><img height="2" src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" width="1" alt="" /></td>
</tr>
<tr>
	<td>
	<table cellspacing="0" cellpadding="0" width="100%" border="0">
<?php $this->assign('is_header', 'E');  endif; ?>
<tr valign="top">
	<td><b><?php echo $this->_tpl_vars['v']['title']; ?>
</b></td>
   	<td><?php echo $this->_tpl_vars['v']['value']; ?>
</td>
</tr>
<?php endif;  endforeach; endif; unset($_from);  if ($this->_tpl_vars['is_header'] == 'E'): ?>
	</table>
	</td>
</tr>
<?php endif; ?>
</table>
<br />
<br />

<table cellspacing="0" cellpadding="0" width="100%" border="0">

<tr>
<td align="center"><font style="FONT-SIZE: 14px; FONT-WEIGHT: bold;"><?php echo $this->_tpl_vars['lng']['lbl_products_ordered']; ?>
</font></td>
</tr>

</table>

<table cellspacing="0" cellpadding="3" width="100%" border="1">

<tr>
<th width="60" bgcolor="#cccccc"><?php echo $this->_tpl_vars['lng']['lbl_sku']; ?>
</th>
<th bgcolor="#cccccc"><?php echo $this->_tpl_vars['lng']['lbl_product']; ?>
</th>
<th width="60" bgcolor="#cccccc"><?php echo $this->_tpl_vars['lng']['lbl_qty']; ?>
</th>
</tr>
<?php $_from = $this->_tpl_vars['order']['shipping_groups']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
 if ($this->_tpl_vars['k'] == $this->_tpl_vars['manufacturerid']):  if ($this->_tpl_vars['show_shipping'] == 'Y'): ?>
<tr>
<td colspan="3">
<b><?php echo $this->_tpl_vars['v']['group_name']; ?>
 Items (delivery by <?php echo ((is_array($_tmp=$this->_tpl_vars['v']['shipping'])) ? $this->_run_mod_handler('trademark', true, $_tmp, '') : smarty_modifier_trademark($_tmp, '')); ?>
):</b>
</td>
</tr>
<?php endif;  $_from = $this->_tpl_vars['v']['products']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['product']):
?>
<tr>
<td align="center" style="font-size: 11px; width: 25%;"><?php echo $this->_tpl_vars['product']['productcode']; ?>
</td>
<td><font style="font-size: 11px"><?php echo $this->_tpl_vars['product']['product']; ?>
</font>
<?php if ($this->_tpl_vars['product']['product_options'] != '' && $this->_tpl_vars['active_modules']['Product_Options']): ?>
<table>

<tr>
<td valign="top"><b><?php echo $this->_tpl_vars['lng']['lbl_options']; ?>
:</b></td> 
<td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Product_Options/display_options.tpl", 'smarty_include_vars' => array('options' => $this->_tpl_vars['product']['product_options'],'options_txt' => $this->_tpl_vars['product']['product_options_txt'],'force_product_options_txt' => $this->_tpl_vars['product']['force_product_options_txt'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>

</table>
<?php endif;  if ($this->_tpl_vars['active_modules']['Egoods'] && $this->_tpl_vars['product']['download_key'] && ( $this->_tpl_vars['order']['status'] == 'P' || $this->_tpl_vars['order']['status'] == 'C' )): ?>
<br />
<a href="<?php echo $this->_tpl_vars['catalogs']['customer']; ?>
/download.php?id=<?php echo $this->_tpl_vars['product']['download_key']; ?>
" class="SmallNote" target="_blank"><?php echo $this->_tpl_vars['lng']['lbl_download']; ?>
</a>
<?php endif; ?>
</td>
<td align="center" style="font-size: 11px"><?php echo $this->_tpl_vars['product']['amount']; ?>
</td>
</tr>
<?php endforeach; endif; unset($_from);  endif;  endforeach; endif; unset($_from); ?>
</table>
	</td>
</tr>


</table>

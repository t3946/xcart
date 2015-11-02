<?php /* Smarty version 2.6.12, created on 2011-10-11 06:23:28
         compiled from mail/html/order_invoice.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'config_load', 'mail/html/order_invoice.tpl', 3, false),array('modifier', 'date_format', 'mail/html/order_invoice.tpl', 23, false),array('modifier', 'trademark', 'mail/html/order_invoice.tpl', 24, false),array('modifier', 'replace', 'mail/html/order_invoice.tpl', 347, false),)), $this); ?>
<?php func_load_lang($this, "mail/html/order_invoice.tpl","txt_order_shipped,lbl_invoice,lbl_date,lbl_order_id,lbl_order_status,lbl_payment_method,lbl_delivery,lbl_phone_1_title,lbl_phone_2_title,lbl_fax,lbl_email,lbl_company,lbl_tax_number,lbl_first_name,lbl_last_name,lbl_phone,lbl_fax,lbl_email,lbl_url,lbl_po_number,lbl_company_name,lbl_name_of_purchaser,lbl_position,lbl_billing_address,lbl_shipping_address,lbl_first_name,lbl_last_name,lbl_address,lbl_city,lbl_county,lbl_state,lbl_country,lbl_zip_code,lbl_first_name,lbl_last_name,lbl_address,lbl_city,lbl_county,lbl_state,lbl_country,lbl_zip_code,lbl_additional_information,lbl_order_payment_details,lbl_customer_notes,txt_thank_you_for_purchase"); ?><?php if ($this->_tpl_vars['customer'] != ''):  $this->assign('_userinfo', $this->_tpl_vars['customer']);  else:  $this->assign('_userinfo', $this->_tpl_vars['userinfo']);  endif;  echo smarty_function_config_load(array('file' => ($this->_tpl_vars['skin_config'])), $this);?>

<?php if ($this->_tpl_vars['is_nomail'] != 'Y'): ?>
<p />
<?php endif;  if ($this->_tpl_vars['order']['status'] == 'S'): ?>
<p><?php echo $this->_tpl_vars['lng']['txt_order_shipped']; ?>
</p>
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
<br /><b><?php echo $this->_tpl_vars['lng']['lbl_order_status']; ?>
:</b> <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/order_status.tpl", 'smarty_include_vars' => array('status' => $this->_tpl_vars['order']['status'],'mode' => 'static')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><br />
<b><?php echo $this->_tpl_vars['lng']['lbl_payment_method']; ?>
:</b><br /><?php echo $this->_tpl_vars['order']['payment_method']; ?>
<br /><b><?php echo $this->_tpl_vars['lng']['lbl_delivery']; ?>
s:</b><br /><?php if ($this->_tpl_vars['order']['shipping_groups'] != ''):  $_from = $this->_tpl_vars['order']['shipping_groups']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
 echo ((is_array($_tmp=$this->_tpl_vars['v']['shipping'])) ? $this->_run_mod_handler('trademark', true, $_tmp, '') : smarty_modifier_trademark($_tmp, '')); ?>
<br /><?php endforeach; endif; unset($_from);  endif; ?>
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
	<tr>
		<td colspan='2'>  <hr style="width:100%;margin: 0px;" /></td>
	</tr>
	</table>
	<br /> 
	<table cellspacing="0" cellpadding="0" width="100%" border="0">
	<tr>
		<td width="45%">
		<table cellspacing="0" cellpadding="0" width="100%" border="0">
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
		</td>
		<td width="10%">&nbsp;</td>
		<td width="45%" style="vertical-align: top;">
		<?php if ($this->_tpl_vars['order']['po_details']): ?>
		<table cellspacing="0" cellpadding="0" width="100%" border="0">
		<tr>
			<td><b><?php echo $this->_tpl_vars['lng']['lbl_po_number']; ?>
:</b> </td>
			<td><?php echo $this->_tpl_vars['order']['po_details']['po_number']; ?>
</td>
		</tr>
		<tr>
			<td><b><?php echo $this->_tpl_vars['lng']['lbl_company_name']; ?>
:</b> </td>
			<td><?php echo $this->_tpl_vars['order']['po_details']['company_name']; ?>
</td>
		</tr>
		<tr>
			<td><b><?php echo $this->_tpl_vars['lng']['lbl_name_of_purchaser']; ?>
:</b> </td>
			<td><?php echo $this->_tpl_vars['order']['po_details']['name_of_purchaser']; ?>
</td>
		</tr>
		<tr>
			<td><b><?php echo $this->_tpl_vars['lng']['lbl_position']; ?>
:</b> </td>
			<td><?php echo $this->_tpl_vars['order']['po_details']['position']; ?>
</td>
		</tr>
		</table>
		<?php endif; ?>
		</td>
	</tr>
	</table>
	<br />
	<table cellspacing="0" cellpadding="0" width="100%" border="0">
	<tr>
		<td width="45%" height="25"><b><?php echo $this->_tpl_vars['lng']['lbl_billing_address']; ?>
</b><hr style="width:100%;margin: 0px;" /></td>
		<td width="10%">&nbsp;</td>
		<td width="45%" height="25"><b><?php echo $this->_tpl_vars['lng']['lbl_shipping_address']; ?>
</b><hr style="width:100%;margin: 0px;" /></td>
	</tr>
	<tr>
		<td>
		<table cellspacing="0" cellpadding="0" width="100%" border="0">
<?php if ($this->_tpl_vars['_userinfo']['default_fields']['b_firstname']): ?>
		<tr>
			<td><b><?php echo $this->_tpl_vars['lng']['lbl_first_name']; ?>
:</b> </td>
			<td><?php echo $this->_tpl_vars['order']['b_firstname']; ?>
</td>
		</tr>
<?php endif;  if ($this->_tpl_vars['_userinfo']['default_fields']['b_lastname']): ?>
		<tr>
			<td><b><?php echo $this->_tpl_vars['lng']['lbl_last_name']; ?>
:</b> </td>
			<td><?php echo $this->_tpl_vars['order']['b_lastname']; ?>
</td>
		</tr>
<?php endif;  $_from = $this->_tpl_vars['_userinfo']['additional_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
 if ($this->_tpl_vars['v']['section'] == 'B'): ?>
		<tr>
			<td><b><?php echo $this->_tpl_vars['v']['title']; ?>
:</b></td>
        	<td><?php echo $this->_tpl_vars['v']['value']; ?>
</td>
		</tr>
	<?php endif;  endforeach; endif; unset($_from);  if ($this->_tpl_vars['_userinfo']['default_fields']['b_address']): ?>
		<tr>
			<td><b><?php echo $this->_tpl_vars['lng']['lbl_address']; ?>
:</b> </td>
			<td><?php echo $this->_tpl_vars['order']['b_address']; ?>
<br /><?php echo $this->_tpl_vars['order']['b_address_2']; ?>
</td>
		</tr>
<?php endif;  if ($this->_tpl_vars['_userinfo']['default_fields']['b_city']): ?>
		<tr>
			<td><b><?php echo $this->_tpl_vars['lng']['lbl_city']; ?>
:</b> </td>
			<td><?php echo $this->_tpl_vars['order']['b_city']; ?>
</td>
		</tr>
<?php endif;  if ($this->_tpl_vars['_userinfo']['default_fields']['b_county'] && $this->_tpl_vars['config']['General']['use_counties'] == 'Y'): ?>
		<tr>
			<td><b><?php echo $this->_tpl_vars['lng']['lbl_county']; ?>
:</b> </td>
			<td><?php echo $this->_tpl_vars['order']['b_countyname']; ?>
</td>
		</tr>
<?php endif;  if ($this->_tpl_vars['_userinfo']['default_fields']['b_state']): ?>
		<tr>
			<td><b><?php echo $this->_tpl_vars['lng']['lbl_state']; ?>
:</b> </td>
			<td><?php echo $this->_tpl_vars['order']['b_statename']; ?>
</td>
		</tr>
<?php endif;  if ($this->_tpl_vars['_userinfo']['default_fields']['b_country']): ?>
		<tr>
			<td><b><?php echo $this->_tpl_vars['lng']['lbl_country']; ?>
:</b> </td>
			<td><?php echo $this->_tpl_vars['order']['b_countryname']; ?>
</td>
		</tr>
<?php endif;  if ($this->_tpl_vars['_userinfo']['default_fields']['b_zipcode']): ?>
		<tr>
			<td><b><?php echo $this->_tpl_vars['lng']['lbl_zip_code']; ?>
:</b> </td>
			<td><?php echo $this->_tpl_vars['order']['b_zipcode']; ?>
</td>
		</tr>
<?php endif; ?>
		</table>
		</td>
		<td>&nbsp;</td>
		<td>
		<table cellspacing="0" cellpadding="0" width="100%" border="0">
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
	<td colspan="3">&nbsp;</td>
</tr>
<tr>
	<td width="45%" height="25"><b><?php echo $this->_tpl_vars['lng']['lbl_additional_information']; ?>
</b></td>
	<td colspan="2" width="55%">&nbsp;</td>
</tr>
<tr>
	<td height="2">  <hr style="width:100%;" /></td>
	<td colspan="2" width="55%"><img height="2" src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" width="1" alt="" style="height: 2px; max-height: 2px;" /></td>
</tr>
<tr>
	<td colspan="3"><img height="2" src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" width="1" alt="" style="height: 2px; max-height: 2px;" /></td>
</tr>
<tr>
	<td><table cellspacing="0" cellpadding="0" width="100%" border="0">
<?php $this->assign('is_header', 'E');  endif; ?>
<tr valign="top">
	<td><b><?php echo $this->_tpl_vars['v']['title']; ?>
</b></td>
   	<td><?php echo $this->_tpl_vars['v']['value']; ?>
</td>
</tr>
<?php endif;  endforeach; endif; unset($_from);  if ($this->_tpl_vars['is_header'] == 'E'): ?>
</table></td>
<td colspan="2" width="55%">&nbsp;</td>
</tr>
<?php endif; ?>


<?php if ($this->_tpl_vars['config']['Email']['show_cc_info'] == 'Y' && $this->_tpl_vars['show_order_details'] == 'Y'): ?>

	<tr>
	<td colspan="3">&nbsp;</td>
	</tr>

	<tr>
	<td width="45%" height="25"><b><?php echo $this->_tpl_vars['lng']['lbl_order_payment_details']; ?>
</b></td>
	<td colspan="2" width="55%">&nbsp;</td>
	</tr>
	
	<tr>
	<td height="2">  <hr style="width:100%;" /></td>
	<td colspan="2"><img height="2" src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" width="1" alt="" style="height: 2px; max-height: 2px;" /></td>
	</tr>
	<tr>
	<td colspan="3"><img height="2" src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" width="1" alt="" style="height: 2px; max-height: 2px;" /></td>
	</tr>

	<tr>
	<td colspan="3"><?php echo ((is_array($_tmp=$this->_tpl_vars['order']['details'])) ? $this->_run_mod_handler('replace', true, $_tmp, "\n", "<br />") : smarty_modifier_replace($_tmp, "\n", "<br />")); ?>
</td>
	</tr>

<?php endif; ?>

<?php if ($this->_tpl_vars['order']['netbanx_reference']): ?>
<tr>
	<td colspan="3">NetBanx Reference: <?php echo $this->_tpl_vars['order']['netbanx_reference']; ?>
</td>
</tr>
<?php endif; ?>

	</table>
	<br />
	<br />

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "mail/html/order_data.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

	</td>
</tr>

<?php if ($this->_tpl_vars['order']['customer_notes'] != ""): ?>

<tr>
	<td colspan="3">
	<br />
	<table cellspacing="0" cellpadding="0" width="100%" border="0">

	<tr>
		<td align="center"><font style="FONT-SIZE: 14px; FONT-WEIGHT: bold;"><?php echo $this->_tpl_vars['lng']['lbl_customer_notes']; ?>
</font></td>
	</tr>

	</table>
	<table cellspacing="0" cellpadding="10" width="100%" style="border: 1px solid;">
	<tr>
		<td style="height:50px;"><?php echo $this->_tpl_vars['order']['customer_notes']; ?>
</td>
	</tr>
	</table>
	</td>
</tr>

<?php endif;  if ($this->_tpl_vars['retrieve'] != 'Y' && $this->_tpl_vars['order']['empty_shipping_groups'] == 'Y'): ?>
<tr>
<td align="center"><br /><br /><font style="FONT-SIZE:12px"><?php echo $this->_tpl_vars['lng']['txt_thank_you_for_purchase']; ?>
</font></td>
</tr>
<?php endif; ?>

</table>

<?php /* Smarty version 2.6.12, created on 2011-10-11 06:23:05
         compiled from modules/Fast_Lane_Checkout/customer_details_html.tpl */ ?>
<?php func_load_lang($this, "modules/Fast_Lane_Checkout/customer_details_html.tpl","lbl_contact_information,lbl_first_name,lbl_last_name,lbl_company,lbl_tax_number,lbl_phone,lbl_fax,lbl_email,lbl_web_site,lbl_billing_address,lbl_first_name,lbl_last_name,lbl_address,lbl_city,lbl_state,lbl_country,lbl_zip_code,lbl_shipping_address,lbl_first_name,lbl_last_name,lbl_address,lbl_city,lbl_state,lbl_country,lbl_zip_code,lbl_additional_information"); ?>
<table cellspacing="0" cellpadding="10" width="100%">

<tr>
<td valign="top" width="50%">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_contact_information'],'class' => 'grey')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<table cellspacing="0" cellpadding="2" width="100%">
<tr>
<td width="40%"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" class="Spc" alt="" /></td>
<td width="60%"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" class="Spc" alt="" /></td>
</tr>
<?php if ($this->_tpl_vars['userinfo']['default_fields']['firstname']): ?>
<tr>
<td><?php echo $this->_tpl_vars['lng']['lbl_first_name']; ?>
:</td>
<td><?php echo $this->_tpl_vars['userinfo']['firstname']; ?>
</td>
</tr>
<?php endif;  if ($this->_tpl_vars['userinfo']['default_fields']['lastname']): ?>
<tr> 
<td><?php echo $this->_tpl_vars['lng']['lbl_last_name']; ?>
:</td>
<td><?php echo $this->_tpl_vars['userinfo']['lastname']; ?>
</td>
</tr>
<?php endif;  if ($this->_tpl_vars['userinfo']['default_fields']['company']): ?>
<tr> 
<td><?php echo $this->_tpl_vars['lng']['lbl_company']; ?>
:</td>
<td><?php echo $this->_tpl_vars['userinfo']['company']; ?>
</td>
</tr>
<?php endif;  if ($this->_tpl_vars['userinfo']['default_fields']['tax_number']): ?>
<tr>
<td><?php echo $this->_tpl_vars['lng']['lbl_tax_number']; ?>
:</td>
<td><?php echo $this->_tpl_vars['userinfo']['tax_number']; ?>
</td>
</tr>
<?php endif;  if ($this->_tpl_vars['userinfo']['default_fields']['phone']): ?>
<tr> 
<td><?php echo $this->_tpl_vars['lng']['lbl_phone']; ?>
:</td>
<td><?php echo $this->_tpl_vars['userinfo']['phone']; ?>
</td>
</tr>
<?php endif;  if ($this->_tpl_vars['userinfo']['default_fields']['fax']): ?>
<tr>  
<td><?php echo $this->_tpl_vars['lng']['lbl_fax']; ?>
:</td>
<td><?php echo $this->_tpl_vars['userinfo']['fax']; ?>
</td>
</tr>
<?php endif;  if ($this->_tpl_vars['userinfo']['default_fields']['email']): ?>
<tr>   
<td><?php echo $this->_tpl_vars['lng']['lbl_email']; ?>
:</td>
<td><?php echo $this->_tpl_vars['userinfo']['email']; ?>
</td>
</tr>
<?php endif;  if ($this->_tpl_vars['userinfo']['default_fields']['url']): ?>
<tr>   
<td><?php echo $this->_tpl_vars['lng']['lbl_web_site']; ?>
:</td>
<td><?php echo $this->_tpl_vars['userinfo']['url']; ?>
</td>
</tr>
<?php endif;  $_from = $this->_tpl_vars['userinfo']['additional_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
 if ($this->_tpl_vars['v']['section'] == 'C' || $this->_tpl_vars['v']['section'] == 'P'): ?>
<tr>
<td><?php echo $this->_tpl_vars['v']['title']; ?>
:</td>
<td><?php echo $this->_tpl_vars['v']['value']; ?>
</td>
</tr>
<?php endif;  endforeach; endif; unset($_from); ?>
</table>
</td>
<td> </td>
</tr>

<tr>
<td valign="top" width="50%">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_billing_address'],'class' => 'grey')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<table cellspacing="0" cellpadding="2" width="100%">
<tr>
<td width="40%"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" class="Spc" alt="" /></td>
<td width="60%"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" class="Spc" alt="" /></td>
</tr>
<?php if ($this->_tpl_vars['userinfo']['default_fields']['b_firstname']): ?>
<tr>
<td><?php echo $this->_tpl_vars['lng']['lbl_first_name']; ?>
:</td>
<td><?php echo $this->_tpl_vars['userinfo']['b_firstname']; ?>
</td>
</tr>
<?php endif;  if ($this->_tpl_vars['userinfo']['default_fields']['b_lastname']): ?>
<tr>
<td><?php echo $this->_tpl_vars['lng']['lbl_last_name']; ?>
:</td>
<td><?php echo $this->_tpl_vars['userinfo']['b_lastname']; ?>
</td>
</tr>
<?php endif;  $_from = $this->_tpl_vars['userinfo']['additional_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
 if ($this->_tpl_vars['v']['section'] == 'B'): ?>
<tr>
<td><?php echo $this->_tpl_vars['v']['title']; ?>
:</td>
<td><?php echo $this->_tpl_vars['v']['value']; ?>
</td>
</tr>
<?php endif;  endforeach; endif; unset($_from);  if ($this->_tpl_vars['userinfo']['default_fields']['b_address']): ?>
<tr>
<td><?php echo $this->_tpl_vars['lng']['lbl_address']; ?>
:</td>
<td><?php echo $this->_tpl_vars['userinfo']['b_address']; ?>

<?php if ($this->_tpl_vars['userinfo']['b_address_2']): ?>
<br /><?php echo $this->_tpl_vars['userinfo']['b_address_2']; ?>

<?php endif; ?>
</td>
</tr>
<?php endif;  if ($this->_tpl_vars['userinfo']['default_fields']['b_city']): ?>
<tr> 
<td><?php echo $this->_tpl_vars['lng']['lbl_city']; ?>
:</td>
<td><?php echo $this->_tpl_vars['userinfo']['b_city']; ?>
</td>
</tr>
<?php endif;  if ($this->_tpl_vars['userinfo']['default_fields']['b_state']): ?>
<tr> 
<td><?php echo $this->_tpl_vars['lng']['lbl_state']; ?>
:</td>
<td><?php echo $this->_tpl_vars['userinfo']['b_statename']; ?>
</td>
</tr>
<?php endif;  if ($this->_tpl_vars['userinfo']['default_fields']['b_country']): ?>
<tr> 
<td><?php echo $this->_tpl_vars['lng']['lbl_country']; ?>
:</td>
<td><?php echo $this->_tpl_vars['userinfo']['b_countryname']; ?>
</td>
</tr>
<?php endif;  if ($this->_tpl_vars['userinfo']['default_fields']['b_zipcode']): ?>
<tr> 
<td><?php echo $this->_tpl_vars['lng']['lbl_zip_code']; ?>
:</td>
<td><?php echo $this->_tpl_vars['userinfo']['b_zipcode']; ?>
</td>
</tr>
<?php endif; ?>
</table>
</td>

<td valign="top" width="50%">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_shipping_address'],'class' => 'grey')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<table cellspacing="0" cellpadding="2" width="100%">
<tr>
<td width="40%"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" class="Spc" alt="" /></td>
<td width="60%"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" class="Spc" alt="" /></td>
</tr>
<?php if ($this->_tpl_vars['userinfo']['default_fields']['s_firstname']): ?>
<tr>
<td><?php echo $this->_tpl_vars['lng']['lbl_first_name']; ?>
:</td>
<td><?php echo $this->_tpl_vars['userinfo']['s_firstname']; ?>
</td>
</tr>
<?php endif;  if ($this->_tpl_vars['userinfo']['default_fields']['s_lastname']): ?>
<tr> 
<td><?php echo $this->_tpl_vars['lng']['lbl_last_name']; ?>
:</td>
<td><?php echo $this->_tpl_vars['userinfo']['s_lastname']; ?>
</td>
</tr>
<?php endif;  $_from = $this->_tpl_vars['userinfo']['additional_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
 if ($this->_tpl_vars['v']['section'] == 'S'): ?>
<tr>
<td><?php echo $this->_tpl_vars['v']['title']; ?>
:</td>
<td><?php echo $this->_tpl_vars['v']['value']; ?>
</td>
</tr>
<?php endif;  endforeach; endif; unset($_from);  if ($this->_tpl_vars['userinfo']['default_fields']['s_address']): ?>
<tr> 
<td><?php echo $this->_tpl_vars['lng']['lbl_address']; ?>
:</td>
<td><?php echo $this->_tpl_vars['userinfo']['s_address']; ?>

<?php if ($this->_tpl_vars['userinfo']['s_address_2']): ?>
<br /><?php echo $this->_tpl_vars['userinfo']['s_address_2']; ?>

<?php endif; ?>
</td>
</tr>
<?php endif;  if ($this->_tpl_vars['userinfo']['default_fields']['s_city']): ?>
<tr> 
<td><?php echo $this->_tpl_vars['lng']['lbl_city']; ?>
:</td>
<td><?php echo $this->_tpl_vars['userinfo']['s_city']; ?>
</td>
</tr>
<?php endif;  if ($this->_tpl_vars['userinfo']['default_fields']['s_state']): ?>
<tr> 
<td><?php echo $this->_tpl_vars['lng']['lbl_state']; ?>
:</td>
<td><?php echo $this->_tpl_vars['userinfo']['s_statename']; ?>
</td>
</tr>
<?php endif;  if ($this->_tpl_vars['userinfo']['default_fields']['s_country']): ?>
<tr> 
<td><?php echo $this->_tpl_vars['lng']['lbl_country']; ?>
:</td>
<td><?php echo $this->_tpl_vars['userinfo']['s_countryname']; ?>
</td>
</tr>
<?php endif;  if ($this->_tpl_vars['userinfo']['default_fields']['s_zipcode']): ?>
<tr> 
<td><?php echo $this->_tpl_vars['lng']['lbl_zip_code']; ?>
:</td>
<td><?php echo $this->_tpl_vars['userinfo']['s_zipcode']; ?>
</td>
</tr>
<?php endif; ?>
</table>
</td>
</tr>

<?php ob_start();  $_from = $this->_tpl_vars['userinfo']['additional_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
 if ($this->_tpl_vars['v']['section'] == 'A'): ?>
<tr>
<td><?php echo $this->_tpl_vars['v']['title']; ?>
:</td>
<td><?php echo $this->_tpl_vars['v']['value']; ?>
</td>
</tr>
<?php endif;  endforeach; endif; unset($_from);  $this->_smarty_vars['capture']['addfields'] = ob_get_contents(); ob_end_clean(); ?>

<?php if ($this->_smarty_vars['capture']['addfields'] != ""): ?>
<tr>
<td valign="top" width="50%">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_additional_information'],'class' => 'grey')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<table cellspacing="0" cellpadding="2" width="100%">
<tr>
<td width="40%"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" class="Spc" alt="" /></td>
<td width="60%"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" class="Spc" alt="" /></td>
</tr>
<?php echo $this->_smarty_vars['capture']['addfields']; ?>

</table>
</td>
<td> </td>
</tr>
<?php endif; ?>

</table>
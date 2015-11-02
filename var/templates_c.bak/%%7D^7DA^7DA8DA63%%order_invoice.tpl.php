<?php /* Smarty version 2.6.12, created on 2011-10-11 06:23:28
         compiled from mail/order_invoice.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'config_load', 'mail/order_invoice.tpl', 3, false),array('function', 'math', 'mail/order_invoice.tpl', 7, false),array('modifier', 'cat', 'mail/order_invoice.tpl', 7, false),array('modifier', 'truncate', 'mail/order_invoice.tpl', 8, false),array('modifier', 'string_format', 'mail/order_invoice.tpl', 8, false),array('modifier', 'date_format', 'mail/order_invoice.tpl', 9, false),)), $this); ?>
<?php func_load_lang($this, "mail/order_invoice.tpl","txt_order_shipped,lbl_order_id,lbl_order_date,lbl_order_status,lbl_tracking_number,lbl_registration_number,lbl_po_number,lbl_company_name,lbl_name_of_purchaser,lbl_position,lbl_customer_info,lbl_first_name,lbl_last_name,lbl_company,lbl_tax_number,lbl_phone,lbl_fax,lbl_email,lbl_url,lbl_billing_address,lbl_first_name,lbl_last_name,lbl_address,lbl_address_2,lbl_city,lbl_county,lbl_state,lbl_country,lbl_zip_code,lbl_shipping_address,lbl_first_name,lbl_last_name,lbl_address,lbl_address_2,lbl_city,lbl_county,lbl_state,lbl_country,lbl_zip_code,lbl_additional_information,lbl_order_payment_details,lbl_customer_notes"); ?><?php if ($this->_tpl_vars['customer'] != ''):  $this->assign('_userinfo', $this->_tpl_vars['customer']);  else:  $this->assign('_userinfo', $this->_tpl_vars['userinfo']);  endif;  echo smarty_function_config_load(array('file' => ($this->_tpl_vars['skin_config'])), $this);?>

<?php if ($this->_tpl_vars['order']['status'] == 'S'):  echo $this->_tpl_vars['lng']['txt_order_shipped']; ?>

<?php endif;  $this->assign('max_truncate', 30);  echo smarty_function_math(array('assign' => 'max_space','equation' => "x+5",'x' => $this->_tpl_vars['max_truncate']), $this); $this->assign('max_space', ((is_array($_tmp=((is_array($_tmp="%-")) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['max_space'])))) ? $this->_run_mod_handler('cat', true, $_tmp, 's') : smarty_modifier_cat($_tmp, 's')));  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_order_id'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['order']['order_prefix'];  echo $this->_tpl_vars['order']['orderid']; ?>

<?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_order_date'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo ((is_array($_tmp=$this->_tpl_vars['order']['date'])) ? $this->_run_mod_handler('date_format', true, $_tmp, $this->_tpl_vars['config']['Appearance']['datetime_format']) : smarty_modifier_date_format($_tmp, $this->_tpl_vars['config']['Appearance']['datetime_format'])); ?>

<?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_order_status'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/order_status.tpl", 'smarty_include_vars' => array('status' => $this->_tpl_vars['order']['status'],'mode' => 'static')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if ($this->_tpl_vars['order']['applied_taxes']):  $_from = $this->_tpl_vars['order']['applied_taxes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['tax_name'] => $this->_tpl_vars['tax']):
 echo $this->_tpl_vars['tax']['regnumber']; ?>

<?php endforeach; endif; unset($_from);  endif; ?>

<?php if ($this->_tpl_vars['order']['tracking']):  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_tracking_number'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['order']['tracking']; ?>

<?php endif;  if ($this->_tpl_vars['order']['reg_numbers']):  unset($this->_sections['rn']);
$this->_sections['rn']['name'] = 'rn';
$this->_sections['rn']['loop'] = is_array($_loop=$this->_tpl_vars['order']['reg_numbers']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['rn']['show'] = true;
$this->_sections['rn']['max'] = $this->_sections['rn']['loop'];
$this->_sections['rn']['step'] = 1;
$this->_sections['rn']['start'] = $this->_sections['rn']['step'] > 0 ? 0 : $this->_sections['rn']['loop']-1;
if ($this->_sections['rn']['show']) {
    $this->_sections['rn']['total'] = $this->_sections['rn']['loop'];
    if ($this->_sections['rn']['total'] == 0)
        $this->_sections['rn']['show'] = false;
} else
    $this->_sections['rn']['total'] = 0;
if ($this->_sections['rn']['show']):

            for ($this->_sections['rn']['index'] = $this->_sections['rn']['start'], $this->_sections['rn']['iteration'] = 1;
                 $this->_sections['rn']['iteration'] <= $this->_sections['rn']['total'];
                 $this->_sections['rn']['index'] += $this->_sections['rn']['step'], $this->_sections['rn']['iteration']++):
$this->_sections['rn']['rownum'] = $this->_sections['rn']['iteration'];
$this->_sections['rn']['index_prev'] = $this->_sections['rn']['index'] - $this->_sections['rn']['step'];
$this->_sections['rn']['index_next'] = $this->_sections['rn']['index'] + $this->_sections['rn']['step'];
$this->_sections['rn']['first']      = ($this->_sections['rn']['iteration'] == 1);
$this->_sections['rn']['last']       = ($this->_sections['rn']['iteration'] == $this->_sections['rn']['total']);
 if ($this->_sections['rn']['first']):  echo $this->_tpl_vars['lng']['lbl_registration_number']; ?>
:
<?php endif;  echo $this->_tpl_vars['order']['reg_numbers'][$this->_sections['rn']['index']]; ?>

<?php endfor; endif;  endif; ?>

<?php if ($this->_tpl_vars['order']['po_details']):  echo $this->_tpl_vars['lng']['lbl_po_number']; ?>
: <?php echo $this->_tpl_vars['order']['po_details']['po_number']; ?>

<?php echo $this->_tpl_vars['lng']['lbl_company_name']; ?>
: <?php echo $this->_tpl_vars['order']['po_details']['company_name']; ?>

<?php echo $this->_tpl_vars['lng']['lbl_name_of_purchaser']; ?>
: <?php echo $this->_tpl_vars['order']['po_details']['name_of_purchaser']; ?>

<?php echo $this->_tpl_vars['lng']['lbl_position']; ?>
: <?php echo $this->_tpl_vars['order']['po_details']['position']; ?>

<?php endif; ?>

<?php echo $this->_tpl_vars['lng']['lbl_customer_info']; ?>
:
---------------------
<?php if ($this->_tpl_vars['_userinfo']['default_fields']['firstname']):  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_first_name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['order']['firstname']; ?>

<?php endif;  if ($this->_tpl_vars['_userinfo']['default_fields']['lastname']):  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_last_name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['order']['lastname']; ?>

<?php endif;  if ($this->_tpl_vars['_userinfo']['default_fields']['company']):  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_company'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['order']['company']; ?>

<?php endif;  if ($this->_tpl_vars['_userinfo']['default_fields']['tax_number']):  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_tax_number'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['order']['tax_number']; ?>

<?php endif;  if ($this->_tpl_vars['_userinfo']['default_fields']['phone']):  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_phone'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['order']['phone']; ?>

<?php endif;  if ($this->_tpl_vars['_userinfo']['default_fields']['fax']):  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_fax'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['order']['fax']; ?>

<?php endif;  if ($this->_tpl_vars['_userinfo']['default_fields']['email']):  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_email'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['order']['email']; ?>

<?php endif;  if ($this->_tpl_vars['_userinfo']['default_fields']['url']):  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_url'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['order']['url']; ?>

<?php endif;  $_from = $this->_tpl_vars['_userinfo']['additional_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
 if ($this->_tpl_vars['v']['section'] == 'P' || $this->_tpl_vars['v']['section'] == 'C'):  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['v']['title'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['v']['value']; ?>

<?php endif;  endforeach; endif; unset($_from); ?>

<?php echo $this->_tpl_vars['lng']['lbl_billing_address']; ?>
:
----------------
<?php if ($this->_tpl_vars['_userinfo']['default_fields']['b_firstname']):  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_first_name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['order']['b_firstname']; ?>

<?php endif;  if ($this->_tpl_vars['_userinfo']['default_fields']['b_lastname']):  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_last_name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['order']['b_lastname']; ?>

<?php endif;  if ($this->_tpl_vars['_userinfo']['default_fields']['b_address']):  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_address'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['order']['b_address']; ?>

<?php endif;  if ($this->_tpl_vars['_userinfo']['default_fields']['b_address_2']):  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_address_2'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['order']['b_address_2']; ?>

<?php endif;  if ($this->_tpl_vars['_userinfo']['default_fields']['b_city']):  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_city'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['order']['b_city']; ?>

<?php endif;  if ($this->_tpl_vars['_userinfo']['default_fields']['b_county']):  if ($this->_tpl_vars['config']['General']['use_counties'] == 'Y'):  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_county'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['order']['b_countyname'];  endif;  endif;  if ($this->_tpl_vars['_userinfo']['default_fields']['b_state']):  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_state'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['order']['b_statename']; ?>

<?php endif;  if ($this->_tpl_vars['_userinfo']['default_fields']['b_country']):  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_country'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['order']['b_countryname']; ?>

<?php endif;  if ($this->_tpl_vars['_userinfo']['default_fields']['b_zipcode']):  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_zip_code'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['order']['b_zipcode']; ?>

<?php endif;  $_from = $this->_tpl_vars['_userinfo']['additional_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
 if ($this->_tpl_vars['v']['section'] == 'B'):  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['v']['title'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['v']['value']; ?>

<?php endif;  endforeach; endif; unset($_from); ?>

<?php echo $this->_tpl_vars['lng']['lbl_shipping_address']; ?>
:
-----------------
<?php if ($this->_tpl_vars['_userinfo']['default_fields']['s_firstname']):  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_first_name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['order']['s_firstname']; ?>

<?php endif;  if ($this->_tpl_vars['_userinfo']['default_fields']['s_lastname']):  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_last_name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['order']['s_lastname']; ?>

<?php endif;  if ($this->_tpl_vars['_userinfo']['default_fields']['s_address']):  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_address'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['order']['s_address']; ?>

<?php endif;  if ($this->_tpl_vars['_userinfo']['default_fields']['s_address_2']):  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_address_2'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['order']['s_address_2']; ?>

<?php endif;  if ($this->_tpl_vars['_userinfo']['default_fields']['s_city']):  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_city'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['order']['s_city']; ?>

<?php endif;  if ($this->_tpl_vars['_userinfo']['default_fields']['s_county']):  if ($this->_tpl_vars['config']['General']['use_counties'] == 'Y'):  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_county'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['order']['s_countyname'];  endif;  endif;  if ($this->_tpl_vars['_userinfo']['default_fields']['s_state']):  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_state'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['order']['s_statename']; ?>

<?php endif;  if ($this->_tpl_vars['_userinfo']['default_fields']['s_country']):  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_country'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['order']['s_countryname']; ?>

<?php endif;  if ($this->_tpl_vars['_userinfo']['default_fields']['s_zipcode']):  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_zip_code'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['order']['s_zipcode']; ?>

<?php endif;  $_from = $this->_tpl_vars['_userinfo']['additional_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
 if ($this->_tpl_vars['v']['section'] == 'S'):  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['v']['title'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['v']['value']; ?>

<?php endif;  endforeach; endif; unset($_from);  $this->assign('is_header', "");  $_from = $this->_tpl_vars['_userinfo']['additional_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
 if ($this->_tpl_vars['v']['section'] == 'A'):  if ($this->_tpl_vars['is_header'] != 'Y'): ?>

<?php echo $this->_tpl_vars['lng']['lbl_additional_information']; ?>
:
-----------------
<?php $this->assign('is_header', 'Y');  endif;  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['v']['title'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['v']['value']; ?>

<?php endif;  endforeach; endif; unset($_from); ?> 

<?php if ($this->_tpl_vars['config']['Email']['show_cc_info'] == 'Y' && $this->_tpl_vars['show_order_details'] == 'Y'):  echo $this->_tpl_vars['lng']['lbl_order_payment_details']; ?>
:
------------------------
<?php echo $this->_tpl_vars['order']['details']; ?>

<?php endif;  if ($this->_tpl_vars['order']['netbanx_reference']): ?>
NetBanx Reference: <?php echo $this->_tpl_vars['order']['netbanx_reference']; ?>

<?php endif; ?>


<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "mail/order_data.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if ($this->_tpl_vars['order']['customer_notes'] != ""):  echo $this->_tpl_vars['lng']['lbl_customer_notes']; ?>
:
------------------------
<?php echo $this->_tpl_vars['order']['customer_notes']; ?>

<?php endif; ?>

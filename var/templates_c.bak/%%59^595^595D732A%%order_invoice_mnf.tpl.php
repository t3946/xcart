<?php /* Smarty version 2.6.12, created on 2011-10-11 07:23:41
         compiled from mail/order_invoice_mnf.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'config_load', 'mail/order_invoice_mnf.tpl', 3, false),array('function', 'math', 'mail/order_invoice_mnf.tpl', 4, false),array('modifier', 'cat', 'mail/order_invoice_mnf.tpl', 4, false),array('modifier', 'truncate', 'mail/order_invoice_mnf.tpl', 5, false),array('modifier', 'string_format', 'mail/order_invoice_mnf.tpl', 5, false),array('modifier', 'date_format', 'mail/order_invoice_mnf.tpl', 6, false),array('modifier', 'trademark', 'mail/order_invoice_mnf.tpl', 68, false),)), $this); ?>
<?php func_load_lang($this, "mail/order_invoice_mnf.tpl","lbl_order_id,lbl_order_date,lbl_customer_info,lbl_first_name,lbl_last_name,lbl_company,lbl_tax_number,lbl_phone,lbl_fax,lbl_email,lbl_url,lbl_shipping_address,lbl_first_name,lbl_last_name,lbl_address,lbl_address_2,lbl_city,lbl_county,lbl_state,lbl_country,lbl_zip_code,lbl_additional_information,lbl_products_ordered,lbl_delivery,lbl_sku,lbl_product,lbl_quantity,lbl_selected_options"); ?><?php if ($this->_tpl_vars['customer'] != ''):  $this->assign('_userinfo', $this->_tpl_vars['customer']);  else:  $this->assign('_userinfo', $this->_tpl_vars['userinfo']);  endif;  echo smarty_function_config_load(array('file' => ($this->_tpl_vars['skin_config'])), $this);?>

<?php $this->assign('max_truncate', 30);  echo smarty_function_math(array('assign' => 'max_space','equation' => "x+5",'x' => $this->_tpl_vars['max_truncate']), $this); $this->assign('max_space', ((is_array($_tmp=((is_array($_tmp="%-")) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['max_space'])))) ? $this->_run_mod_handler('cat', true, $_tmp, 's') : smarty_modifier_cat($_tmp, 's')));  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_order_id'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['order']['order_prefix'];  echo $this->_tpl_vars['order']['orderid']; ?>

<?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_order_date'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo ((is_array($_tmp=$this->_tpl_vars['order']['date'])) ? $this->_run_mod_handler('date_format', true, $_tmp, $this->_tpl_vars['config']['Appearance']['datetime_format']) : smarty_modifier_date_format($_tmp, $this->_tpl_vars['config']['Appearance']['datetime_format'])); ?>


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

<?php echo $this->_tpl_vars['lng']['lbl_shipping_address']; ?>
:
-----------------
<?php if ($this->_tpl_vars['_userinfo']['default_fields']['s_firstname']):  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_first_name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['order']['s_firstname']; ?>

<?php endif;  if ($this->_tpl_vars['_userinfo']['default_fields']['s_lastname']):  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_last_name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['order']['s_lastname']; ?>

<?php endif;  $_from = $this->_tpl_vars['_userinfo']['additional_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
 if ($this->_tpl_vars['v']['section'] == 'S'):  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['v']['title'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['v']['value']; ?>

<?php endif;  endforeach; endif; unset($_from);  $this->assign('is_header', "");  if ($this->_tpl_vars['_userinfo']['default_fields']['s_address']):  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_address'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['order']['s_address']; ?>

<?php endif;  if ($this->_tpl_vars['_userinfo']['default_fields']['s_address_2']):  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_address_2'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['order']['s_address_2']; ?>

<?php endif;  if ($this->_tpl_vars['_userinfo']['default_fields']['s_city']):  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_city'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['order']['s_city']; ?>

<?php endif;  if ($this->_tpl_vars['_userinfo']['default_fields']['s_county']):  if ($this->_tpl_vars['config']['General']['use_counties'] == 'Y'):  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_county'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['order']['s_countyname'];  endif;  endif;  if ($this->_tpl_vars['_userinfo']['default_fields']['s_state']):  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_state'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['order']['s_statename']; ?>

<?php endif;  if ($this->_tpl_vars['_userinfo']['default_fields']['s_country']):  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_country'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['order']['s_countryname']; ?>

<?php endif;  if ($this->_tpl_vars['_userinfo']['default_fields']['s_zipcode']):  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_zip_code'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['order']['s_zipcode']; ?>

<?php endif;  $_from = $this->_tpl_vars['_userinfo']['additional_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
 if ($this->_tpl_vars['v']['section'] == 'A'):  if ($this->_tpl_vars['is_header'] != 'Y'): ?>

<?php echo $this->_tpl_vars['lng']['lbl_additional_information']; ?>
:
-----------------
<?php $this->assign('is_header', 'Y');  endif;  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['v']['title'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['v']['value']; ?>

<?php endif;  endforeach; endif; unset($_from); ?> 

<?php echo $this->_tpl_vars['lng']['lbl_products_ordered']; ?>
:
-----------------

<?php $_from = $this->_tpl_vars['order']['shipping_groups']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
 if ($this->_tpl_vars['k'] == $this->_tpl_vars['manufacturerid']):  if ($this->_tpl_vars['show_shipping'] == 'Y'):  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_delivery'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space'])); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['v']['shipping'])) ? $this->_run_mod_handler('trademark', true, $_tmp, "") : smarty_modifier_trademark($_tmp, "")); ?>


<?php endif;  unset($this->_sections['prod_num']);
$this->_sections['prod_num']['name'] = 'prod_num';
$this->_sections['prod_num']['loop'] = is_array($_loop=$this->_tpl_vars['v']['products']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['prod_num']['show'] = true;
$this->_sections['prod_num']['max'] = $this->_sections['prod_num']['loop'];
$this->_sections['prod_num']['step'] = 1;
$this->_sections['prod_num']['start'] = $this->_sections['prod_num']['step'] > 0 ? 0 : $this->_sections['prod_num']['loop']-1;
if ($this->_sections['prod_num']['show']) {
    $this->_sections['prod_num']['total'] = $this->_sections['prod_num']['loop'];
    if ($this->_sections['prod_num']['total'] == 0)
        $this->_sections['prod_num']['show'] = false;
} else
    $this->_sections['prod_num']['total'] = 0;
if ($this->_sections['prod_num']['show']):

            for ($this->_sections['prod_num']['index'] = $this->_sections['prod_num']['start'], $this->_sections['prod_num']['iteration'] = 1;
                 $this->_sections['prod_num']['iteration'] <= $this->_sections['prod_num']['total'];
                 $this->_sections['prod_num']['index'] += $this->_sections['prod_num']['step'], $this->_sections['prod_num']['iteration']++):
$this->_sections['prod_num']['rownum'] = $this->_sections['prod_num']['iteration'];
$this->_sections['prod_num']['index_prev'] = $this->_sections['prod_num']['index'] - $this->_sections['prod_num']['step'];
$this->_sections['prod_num']['index_next'] = $this->_sections['prod_num']['index'] + $this->_sections['prod_num']['step'];
$this->_sections['prod_num']['first']      = ($this->_sections['prod_num']['iteration'] == 1);
$this->_sections['prod_num']['last']       = ($this->_sections['prod_num']['iteration'] == $this->_sections['prod_num']['total']);
 echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_sku'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['v']['products'][$this->_sections['prod_num']['index']]['productcode']; ?>

<?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_product'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['v']['products'][$this->_sections['prod_num']['index']]['product']; ?>

<?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_quantity'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['v']['products'][$this->_sections['prod_num']['index']]['amount']; ?>

<?php if ($this->_tpl_vars['v']['products'][$this->_sections['prod_num']['index']]['product_options'] != ""):  echo $this->_tpl_vars['lng']['lbl_selected_options']; ?>
:
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Product_Options/display_options.tpl", 'smarty_include_vars' => array('options' => $this->_tpl_vars['v']['products'][$this->_sections['prod_num']['index']]['product_options'],'options_txt' => $this->_tpl_vars['v']['products'][$this->_sections['prod_num']['index']]['product_options_txt'],'is_plain' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  endfor; endif;  endif;  endforeach; endif; unset($_from); ?>



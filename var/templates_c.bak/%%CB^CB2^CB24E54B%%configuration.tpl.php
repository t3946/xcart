<?php /* Smarty version 2.6.12, created on 2011-10-11 05:40:36
         compiled from admin/main/configuration.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'replace', 'admin/main/configuration.tpl', 26, false),array('modifier', 'escape', 'admin/main/configuration.tpl', 45, false),array('modifier', 'substitute', 'admin/main/configuration.tpl', 91, false),array('modifier', 'regex_replace', 'admin/main/configuration.tpl', 186, false),array('modifier', 'date_format', 'admin/main/configuration.tpl', 201, false),array('modifier', 'default', 'admin/main/configuration.tpl', 208, false),array('modifier', 'formatnumeric', 'admin/main/configuration.tpl', 251, false),array('modifier', 'strip_tags', 'admin/main/configuration.tpl', 309, false),array('function', 'cycle', 'admin/main/configuration.tpl', 163, false),array('function', 'assign_ext', 'admin/main/configuration.tpl', 196, false),array('function', 'math', 'admin/main/configuration.tpl', 276, false),)), $this); ?>
<?php func_load_lang($this, "admin/main/configuration.tpl","lbl_general_settings,txt_general_settings_top_text,lbl_current_sf_properties,lbl_no_options,txt_main_sf,lbl_usps_labels_help,txt_gcheckout_setup_note,txt_rate_estimation_note,txt_fancy_cache_note,txt_intershipper_account_note,txt_usps_account_note,txt_canadapost_account_note,txt_airborne_account_note,txt_dhl_account_note,lbl_none,lbl_none,lbl_enabled,txt_no_disable_blowfish,lbl_log_act_nothing,lbl_log_act_log,lbl_log_act_email,lbl_log_act_log_n_email,lbl_save,lbl_test_realtime_calculation,txt_test_realtime_calculation_text,lbl_package_weight,lbl_test,lbl_test_data_encryption,lbl_test_data_encryption_link,txt_domain_specific_option,lbl_sf_properties,lbl_general_settings"); ?><?php if ($this->_tpl_vars['option'] != 'Multiple_Storefronts'): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "page_title.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_general_settings'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

    <?php echo $this->_tpl_vars['lng']['txt_general_settings_top_text']; ?>


    <br /><br />

    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog_tools.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

     <br />
<?php else: ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "page_title.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_current_sf_properties'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

<?php ob_start(); ?>

<?php if ($this->_tpl_vars['active_modules']['Multiple_Storefronts'] && ! $this->_tpl_vars['configuration']): ?>

<table cellpadding="3" cellspacing="1" width="100%">

<?php $this->assign('option_title', "option_title_".($this->_tpl_vars['option']));  if ($this->_tpl_vars['lng'][$this->_tpl_vars['option_title']]):  $this->assign('option_title', $this->_tpl_vars['lng'][$this->_tpl_vars['option_title']]);  else:  $this->assign('option_title', ((is_array($_tmp=$this->_tpl_vars['option'])) ? $this->_run_mod_handler('replace', true, $_tmp, '_', ' ') : smarty_modifier_replace($_tmp, '_', ' ')));  $this->assign('option_title', ($this->_tpl_vars['option_title'])." options");  endif; ?>

<tr>
<td class="TopLabel"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['option_title'],'class' => 'black')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>

<tr>
<td><?php if ($this->_tpl_vars['current_storefront'] > 0):  echo $this->_tpl_vars['lng']['lbl_no_options'];  else:  echo $this->_tpl_vars['lng']['txt_main_sf'];  endif; ?></td>
</tr>

</table>
	
<?php else: ?>

<?php $this->assign('cycle_name', 'sep'); ?>

<?php if ($this->_tpl_vars['option'] != 'User_Profiles' && $this->_tpl_vars['option'] != 'Contact_Us' && $this->_tpl_vars['option'] != 'Search_products'): ?>
<form action="configuration.php?option=<?php echo ((is_array($_tmp=$this->_tpl_vars['option'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" method="post" name="processform">
<?php endif; ?>

<table cellpadding="3" cellspacing="1" width="100%">

<?php $this->assign('option_title', "option_title_".($this->_tpl_vars['option']));  if ($this->_tpl_vars['lng'][$this->_tpl_vars['option_title']]):  $this->assign('option_title', $this->_tpl_vars['lng'][$this->_tpl_vars['option_title']]);  else:  $this->assign('option_title', ((is_array($_tmp=$this->_tpl_vars['option'])) ? $this->_run_mod_handler('replace', true, $_tmp, '_', ' ') : smarty_modifier_replace($_tmp, '_', ' ')));  $this->assign('option_title', ($this->_tpl_vars['option_title'])." options");  endif; ?>

<tr>
<td class="TopLabel"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['option_title'],'class' => 'black')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>
<?php if ($this->_tpl_vars['option'] == 'Shipping_Label_Generator'): ?>
<tr>
<td>
<div align="right">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/button.tpl", 'smarty_include_vars' => array('button_title' => $this->_tpl_vars['lng']['lbl_usps_labels_help'],'href' => "javascript:window.open('popup_info.php?action=TSTLBL','TSTLBL_HELP','width=600,height=460,toolbar=no,status=no,scrollbars=yes,resizable=no,menubar=no,location=no,direction=no');")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>

</td>
</tr>
<?php endif; ?>

</table>

<br />

<?php if ($this->_tpl_vars['option'] == 'User_Profiles'): ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/main/user_profiles.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php elseif ($this->_tpl_vars['option'] == 'Contact_Us'): ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/main/contact_us_profiles.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php elseif ($this->_tpl_vars['option'] == 'Search_products'): ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/main/search_products_form.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php else: ?>

<?php if ($this->_tpl_vars['option'] == 'Google_Checkout'):  echo ((is_array($_tmp=$this->_tpl_vars['lng']['txt_gcheckout_setup_note'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'callback_url', $this->_tpl_vars['gcheckout_callback_url']) : smarty_modifier_substitute($_tmp, 'callback_url', $this->_tpl_vars['gcheckout_callback_url'])); ?>

<br />
<br />
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Google_Checkout/gcheckout_requirements.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php endif;  if ($this->_tpl_vars['option'] == 'Image_Verification'):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Image_Verification/spambot_requirements.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

<?php if ($this->_tpl_vars['option'] == 'XPayments_Connector'): ?>
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/XPayments_Connector/config_recommends.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

<table cellpadding="3" cellspacing="1" width=100%>

<?php $this->assign('first_row', 1); ?>

<?php unset($this->_sections['cat_num']);
$this->_sections['cat_num']['name'] = 'cat_num';
$this->_sections['cat_num']['loop'] = is_array($_loop=$this->_tpl_vars['configuration']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['cat_num']['show'] = true;
$this->_sections['cat_num']['max'] = $this->_sections['cat_num']['loop'];
$this->_sections['cat_num']['step'] = 1;
$this->_sections['cat_num']['start'] = $this->_sections['cat_num']['step'] > 0 ? 0 : $this->_sections['cat_num']['loop']-1;
if ($this->_sections['cat_num']['show']) {
    $this->_sections['cat_num']['total'] = $this->_sections['cat_num']['loop'];
    if ($this->_sections['cat_num']['total'] == 0)
        $this->_sections['cat_num']['show'] = false;
} else
    $this->_sections['cat_num']['total'] = 0;
if ($this->_sections['cat_num']['show']):

            for ($this->_sections['cat_num']['index'] = $this->_sections['cat_num']['start'], $this->_sections['cat_num']['iteration'] = 1;
                 $this->_sections['cat_num']['iteration'] <= $this->_sections['cat_num']['total'];
                 $this->_sections['cat_num']['index'] += $this->_sections['cat_num']['step'], $this->_sections['cat_num']['iteration']++):
$this->_sections['cat_num']['rownum'] = $this->_sections['cat_num']['iteration'];
$this->_sections['cat_num']['index_prev'] = $this->_sections['cat_num']['index'] - $this->_sections['cat_num']['step'];
$this->_sections['cat_num']['index_next'] = $this->_sections['cat_num']['index'] + $this->_sections['cat_num']['step'];
$this->_sections['cat_num']['first']      = ($this->_sections['cat_num']['iteration'] == 1);
$this->_sections['cat_num']['last']       = ($this->_sections['cat_num']['iteration'] == $this->_sections['cat_num']['total']);
?>

<?php $this->assign('opt_comment', "opt_".($this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['name']));  $this->assign('opt_label_id', "opt_".($this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['name'])); ?>

<?php if ($this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['type'] == 'separator'): ?>

<tr><td colspan="3" class="TableSeparator"><?php if ($this->_tpl_vars['first_row'] == 0): ?><br /><?php endif; ?><br /><?php if ($this->_tpl_vars['lng'][$this->_tpl_vars['opt_comment']] != ""):  echo $this->_tpl_vars['lng'][$this->_tpl_vars['opt_comment']];  elseif ($this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['comment']):  echo $this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['comment'];  else: ?><hr /><?php endif; ?><br /><br /></td></tr>
<?php $this->assign('cycle_name', $this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['name']); ?>

<?php else: ?>

<?php if ($this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['name'] == 'realtime_shipping'): ?>

<tr><td colspan="3">
<?php echo $this->_tpl_vars['lng']['txt_rate_estimation_note']; ?>
<br /><br />
</td>
</tr>
<?php elseif ($this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['name'] == 'fancy_cache'): ?>

<tr>
	<td colspan="3"><br /><br /><?php echo $this->_tpl_vars['lng']['txt_fancy_cache_note']; ?>
<br /></td>
</tr>

<?php endif; ?>

<?php if ($this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['name'] == 'intershipper_username' || $this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['name'] == 'USPS_servername' || $this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['name'] == 'UPS_username' || $this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['name'] == 'CPC_merchant_id' || $this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['name'] == 'ARB_id' || $this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['name'] == 'dhl_siteid'): ?>

<tr>
<td colspan="3">
<?php if ($this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['name'] == 'intershipper_username'):  echo $this->_tpl_vars['lng']['txt_intershipper_account_note']; ?>

<?php elseif ($this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['name'] == 'USPS_servername'):  echo $this->_tpl_vars['lng']['txt_usps_account_note']; ?>

<?php elseif ($this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['name'] == 'CPC_merchant_id'):  echo $this->_tpl_vars['lng']['txt_canadapost_account_note']; ?>

<?php elseif ($this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['name'] == 'ARB_id'):  echo $this->_tpl_vars['lng']['txt_airborne_account_note']; ?>

<?php elseif ($this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['name'] == 'dhl_siteid'):  echo $this->_tpl_vars['lng']['txt_dhl_account_note']; ?>

<?php endif; ?>
<br /><br /></td>
</tr>

<?php endif; ?>

<?php if ($this->_tpl_vars['cols_count'] == '1'):  $this->assign('bgcolor', "");  $this->assign('cols_count', "");  else:  $this->assign('bgcolor', "class=''");  $this->assign('cols_count', '1');  endif; ?>

<?php echo smarty_function_cycle(array('name' => $this->_tpl_vars['cycle_name'],'values' => " class='TableSubHead', ",'assign' => 'row_style'), $this);?>


<tr>
	<td width="3%">&nbsp;</td>
	<td <?php echo $this->_tpl_vars['row_style']; ?>
 width="37%">
<?php if ($this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['type'] == 'checkbox'): ?>
<label for="<?php echo $this->_tpl_vars['opt_label_id']; ?>
">
<?php endif;  if ($this->_tpl_vars['lng'][$this->_tpl_vars['opt_comment']]):  echo $this->_tpl_vars['lng'][$this->_tpl_vars['opt_comment']];  else:  echo $this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['comment'];  endif; ?>:
<?php if ($this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['type'] == 'checkbox'): ?>
</label>
<?php endif; ?>
	</td>
	<td <?php echo $this->_tpl_vars['row_style']; ?>
 width="60%">

<?php $this->assign('prefix', false); ?>

<?php if ($this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['name'] == 'default_country' || $this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['name'] == 'location_country'): ?>
	<select name="<?php echo $this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['name']; ?>
" id="<?php echo $this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['name']; ?>
">
<?php unset($this->_sections['country_idx']);
$this->_sections['country_idx']['name'] = 'country_idx';
$this->_sections['country_idx']['loop'] = is_array($_loop=$this->_tpl_vars['countries']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['country_idx']['show'] = true;
$this->_sections['country_idx']['max'] = $this->_sections['country_idx']['loop'];
$this->_sections['country_idx']['step'] = 1;
$this->_sections['country_idx']['start'] = $this->_sections['country_idx']['step'] > 0 ? 0 : $this->_sections['country_idx']['loop']-1;
if ($this->_sections['country_idx']['show']) {
    $this->_sections['country_idx']['total'] = $this->_sections['country_idx']['loop'];
    if ($this->_sections['country_idx']['total'] == 0)
        $this->_sections['country_idx']['show'] = false;
} else
    $this->_sections['country_idx']['total'] = 0;
if ($this->_sections['country_idx']['show']):

            for ($this->_sections['country_idx']['index'] = $this->_sections['country_idx']['start'], $this->_sections['country_idx']['iteration'] = 1;
                 $this->_sections['country_idx']['iteration'] <= $this->_sections['country_idx']['total'];
                 $this->_sections['country_idx']['index'] += $this->_sections['country_idx']['step'], $this->_sections['country_idx']['iteration']++):
$this->_sections['country_idx']['rownum'] = $this->_sections['country_idx']['iteration'];
$this->_sections['country_idx']['index_prev'] = $this->_sections['country_idx']['index'] - $this->_sections['country_idx']['step'];
$this->_sections['country_idx']['index_next'] = $this->_sections['country_idx']['index'] + $this->_sections['country_idx']['step'];
$this->_sections['country_idx']['first']      = ($this->_sections['country_idx']['iteration'] == 1);
$this->_sections['country_idx']['last']       = ($this->_sections['country_idx']['iteration'] == $this->_sections['country_idx']['total']);
?>
		<option value="<?php echo $this->_tpl_vars['countries'][$this->_sections['country_idx']['index']]['country_code']; ?>
"<?php if ($this->_tpl_vars['countries'][$this->_sections['country_idx']['index']]['country_code'] == $this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['value']): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['countries'][$this->_sections['country_idx']['index']]['country']; ?>
</option>
<?php endfor; endif; ?>
	</select>
<?php $this->assign('prefix', ((is_array($_tmp=$this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['name'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/_country$/", "") : smarty_modifier_regex_replace($_tmp, "/_country$/", ""))); ?>

<?php elseif ($this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['name'] == 'location_state' || $this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['name'] == 'default_state'):  if ($this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['name'] == 'location_state'):  $this->assign('country', $this->_tpl_vars['config']['Company']['location_country']);  else:  $this->assign('country', $this->_tpl_vars['config']['General']['default_country']);  endif;  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/states.tpl", 'smarty_include_vars' => array('states' => $this->_tpl_vars['states'],'name' => $this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['name'],'default' => $this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['value'],'default_country' => $this->_tpl_vars['country'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  $this->assign('prefix', ((is_array($_tmp=$this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['name'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/_state$/", "") : smarty_modifier_regex_replace($_tmp, "/_state$/", "")));  echo smarty_function_assign_ext(array('var' => "state_values[".($this->_tpl_vars['prefix'])."]",'value' => $this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['value']), $this);?>


<?php elseif ($this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['name'] == 'date_format'): ?>
	<select name="<?php echo $this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['name']; ?>
">
<?php unset($this->_sections['df']);
$this->_sections['df']['name'] = 'df';
$this->_sections['df']['loop'] = is_array($_loop=$this->_tpl_vars['date_formats']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['df']['show'] = true;
$this->_sections['df']['max'] = $this->_sections['df']['loop'];
$this->_sections['df']['step'] = 1;
$this->_sections['df']['start'] = $this->_sections['df']['step'] > 0 ? 0 : $this->_sections['df']['loop']-1;
if ($this->_sections['df']['show']) {
    $this->_sections['df']['total'] = $this->_sections['df']['loop'];
    if ($this->_sections['df']['total'] == 0)
        $this->_sections['df']['show'] = false;
} else
    $this->_sections['df']['total'] = 0;
if ($this->_sections['df']['show']):

            for ($this->_sections['df']['index'] = $this->_sections['df']['start'], $this->_sections['df']['iteration'] = 1;
                 $this->_sections['df']['iteration'] <= $this->_sections['df']['total'];
                 $this->_sections['df']['index'] += $this->_sections['df']['step'], $this->_sections['df']['iteration']++):
$this->_sections['df']['rownum'] = $this->_sections['df']['iteration'];
$this->_sections['df']['index_prev'] = $this->_sections['df']['index'] - $this->_sections['df']['step'];
$this->_sections['df']['index_next'] = $this->_sections['df']['index'] + $this->_sections['df']['step'];
$this->_sections['df']['first']      = ($this->_sections['df']['iteration'] == 1);
$this->_sections['df']['last']       = ($this->_sections['df']['iteration'] == $this->_sections['df']['total']);
?>
		<option value="<?php echo $this->_tpl_vars['date_formats'][$this->_sections['df']['index']]; ?>
"<?php if ($this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['value'] == $this->_tpl_vars['date_formats'][$this->_sections['df']['index']]): ?> selected="selected"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['gmnow'])) ? $this->_run_mod_handler('date_format', true, $_tmp, $this->_tpl_vars['date_formats'][$this->_sections['df']['index']]) : smarty_modifier_date_format($_tmp, $this->_tpl_vars['date_formats'][$this->_sections['df']['index']])); ?>
 (<?php echo $this->_tpl_vars['date_formats_alt'][$this->_sections['df']['index']]; ?>
)</option>
<?php endfor; endif; ?>
	</select>

<?php elseif ($this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['name'] == 'time_format'): ?>
	<select name="<?php echo $this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['name']; ?>
">
<?php unset($this->_sections['df']);
$this->_sections['df']['name'] = 'df';
$this->_sections['df']['loop'] = is_array($_loop=$this->_tpl_vars['time_formats']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['df']['show'] = true;
$this->_sections['df']['max'] = $this->_sections['df']['loop'];
$this->_sections['df']['step'] = 1;
$this->_sections['df']['start'] = $this->_sections['df']['step'] > 0 ? 0 : $this->_sections['df']['loop']-1;
if ($this->_sections['df']['show']) {
    $this->_sections['df']['total'] = $this->_sections['df']['loop'];
    if ($this->_sections['df']['total'] == 0)
        $this->_sections['df']['show'] = false;
} else
    $this->_sections['df']['total'] = 0;
if ($this->_sections['df']['show']):

            for ($this->_sections['df']['index'] = $this->_sections['df']['start'], $this->_sections['df']['iteration'] = 1;
                 $this->_sections['df']['iteration'] <= $this->_sections['df']['total'];
                 $this->_sections['df']['index'] += $this->_sections['df']['step'], $this->_sections['df']['iteration']++):
$this->_sections['df']['rownum'] = $this->_sections['df']['iteration'];
$this->_sections['df']['index_prev'] = $this->_sections['df']['index'] - $this->_sections['df']['step'];
$this->_sections['df']['index_next'] = $this->_sections['df']['index'] + $this->_sections['df']['step'];
$this->_sections['df']['first']      = ($this->_sections['df']['iteration'] == 1);
$this->_sections['df']['last']       = ($this->_sections['df']['iteration'] == $this->_sections['df']['total']);
?>
		<option value="<?php echo $this->_tpl_vars['time_formats'][$this->_sections['df']['index']]; ?>
"<?php if ($this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['value'] == $this->_tpl_vars['time_formats'][$this->_sections['df']['index']]): ?> selected="selected"<?php endif; ?>><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['gmnow'])) ? $this->_run_mod_handler('date_format', true, $_tmp, $this->_tpl_vars['time_formats'][$this->_sections['df']['index']]) : smarty_modifier_date_format($_tmp, $this->_tpl_vars['time_formats'][$this->_sections['df']['index']])))) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['lng']['lbl_none']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['lng']['lbl_none'])); ?>
</option>
<?php endfor; endif; ?>
	</select>

<?php elseif ($this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['name'] == 'blowfish_enabled' && $this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['value'] == 'Y' && $this->_tpl_vars['is_merchant_password'] != 'Y'):  echo $this->_tpl_vars['lng']['lbl_enabled']; ?>
<input type="hidden" name="<?php echo $this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['name']; ?>
" value='<?php echo $this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['value']; ?>
' />
	</td>
</tr>

<tr>
<td colspan="2"><font class="ErrorMessage"><?php echo $this->_tpl_vars['lng']['txt_no_disable_blowfish']; ?>
</font></td>
</tr>

<?php elseif ($this->_tpl_vars['option'] == 'Logging' && ((is_array($_tmp=$this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['name'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/_.*/", "") : smarty_modifier_regex_replace($_tmp, "/_.*/", "")) == 'log'): ?>
<select name="<?php echo $this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['name']; ?>
">
<option value="N"<?php if ($this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['value'] == 'N'): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_log_act_nothing']; ?>
</option>
<option value="L"<?php if ($this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['value'] == 'L'): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_log_act_log']; ?>
</option>
<option value="E"<?php if ($this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['value'] == 'E'): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_log_act_email']; ?>
</option>
<option value="LE"<?php if ($this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['value'] == 'LE'): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_log_act_log_n_email']; ?>
</option>
</select>

<?php elseif ($this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['name'] == 'default_giftcert_template'): ?>
<select name="<?php echo $this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['name']; ?>
">
<?php $_from = $this->_tpl_vars['gc_templates']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['gc_tpl']):
?>
<option value="<?php echo ((is_array($_tmp=$this->_tpl_vars['gc_tpl'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"<?php if ($this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['value'] == $this->_tpl_vars['gc_tpl']): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['gc_tpl']; ?>
</option>
<?php endforeach; endif; unset($_from); ?>
</select>

<?php elseif ($this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['name'] == 'periodic_logs'): ?>
<input type="hidden" name="periodic_logs" value="" />
<select name="periodic_logs[]" multiple="multiple" size="10">
<?php $_from = $this->_tpl_vars['periodical_logs_names']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['log_label'] => $this->_tpl_vars['txt_label']):
?>
<option value="<?php echo $this->_tpl_vars['log_label']; ?>
"<?php if ($this->_tpl_vars['periodical_log_labels'][$this->_tpl_vars['log_label']] != ""): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['txt_label']; ?>
</option>
<?php endforeach; endif; unset($_from); ?>
</select>

<?php elseif ($this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['name'] == 'spambot_arrest_img_generator'): ?>
<select name="<?php echo $this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['name']; ?>
">
<?php $_from = $this->_tpl_vars['img_generators']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['generator']):
?>
<option value="<?php echo $this->_tpl_vars['generator']; ?>
" <?php if ($this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['value'] == $this->_tpl_vars['generator']): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['generator']; ?>
</option>
<?php endforeach; endif; unset($_from); ?>
</select>
<?php elseif ($this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['type'] == 'numeric'): ?>
<input type="text" size="10" name="<?php echo $this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['name']; ?>
" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['value'])) ? $this->_run_mod_handler('formatnumeric', true, $_tmp) : smarty_modifier_formatnumeric($_tmp)); ?>
" />

<?php elseif ($this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['type'] == 'text'): ?>
<input type="text" size="71" name="<?php echo $this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['name']; ?>
" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['value'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" />

<?php elseif ($this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['type'] == 'checkbox'): ?>
<input type="checkbox" id="<?php echo $this->_tpl_vars['opt_label_id']; ?>
" name="<?php echo $this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['name']; ?>
"<?php if ($this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['value'] == 'Y'): ?> checked="checked"<?php endif; ?> />

<?php elseif ($this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['type'] == 'textarea'): ?>
<textarea name="<?php echo $this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['name']; ?>
" cols="71" rows="5"><?php echo ((is_array($_tmp=$this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['value'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</textarea>

<?php elseif (( $this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['type'] == 'selector' || $this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['type'] == 'multiselector' ) && $this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['variants'] != ''):  if ($this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['type'] == 'multiselector'): ?>
<select name="<?php echo $this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['name']; ?>
[]" multiple="multiple" size="5">
<?php else: ?>
<select name="<?php echo $this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['name']; ?>
"<?php if ($this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['auto_submit']): ?> onchange="javascript: document.processform.submit()"<?php endif; ?>>
<?php endif;  $_from = $this->_tpl_vars['configuration'][$this->_sections['cat_num']['index']]['variants']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['vkey'] => $this->_tpl_vars['vitem']):
?>
	<option value="<?php echo $this->_tpl_vars['vkey']; ?>
"<?php if ($this->_tpl_vars['vitem']['selected']): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['vitem']['name']; ?>
</option>
<?php endforeach; endif; unset($_from); ?>
</select>
<?php endif; ?>

<?php if ($this->_tpl_vars['prefix'] != ''):  if ($this->_tpl_vars['dynamic_states'][$this->_tpl_vars['prefix']] > 0):  echo smarty_function_math(array('assign' => 'next','equation' => "x+1",'x' => $this->_tpl_vars['dynamic_states'][$this->_tpl_vars['prefix']]), $this);?>

<?php echo smarty_function_assign_ext(array('var' => "dynamic_states[".($this->_tpl_vars['prefix'])."]",'value' => $this->_tpl_vars['next']), $this);?>

<?php else:  echo smarty_function_assign_ext(array('var' => "dynamic_states[".($this->_tpl_vars['prefix'])."]",'value' => 1), $this);?>

<?php endif;  endif; ?>
</td>
</tr>

<?php endif; ?>

<?php $this->assign('first_row', 0); ?>

<?php endfor; endif; ?>

<?php if ($this->_tpl_vars['dynamic_states'] != '' && $this->_tpl_vars['js_enabled'] == 'Y' && $this->_tpl_vars['config']['General']['use_js_states'] == 'Y'): ?>
<tr style="display: none;">
<td>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "change_states_js.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  $_from = $this->_tpl_vars['dynamic_states']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['name'] => $this->_tpl_vars['cnt']):
 if ($this->_tpl_vars['cnt'] == 2):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/register_states.tpl", 'smarty_include_vars' => array('state_name' => ($this->_tpl_vars['name'])."_state",'country_name' => ($this->_tpl_vars['name'])."_country",'state_value' => $this->_tpl_vars['state_values'][$this->_tpl_vars['name']])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  endforeach; endif; unset($_from); ?>

</td>
</tr>
<?php endif;  if ($this->_tpl_vars['option'] == 'Product_Page'): ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/main/product_page_options.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
<tr>
<td colspan="3"><br /><br />
<input type="submit" value=" <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_save'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 "  />
</td>
</tr>

</table>

<?php if ($this->_tpl_vars['option'] != 'User_Profiles' && $this->_tpl_vars['option'] != 'Contact_Us' && $this->_tpl_vars['option'] != 'Search_products'): ?>
</form>
<?php endif; ?>

<?php if ($this->_tpl_vars['option'] == 'Shipping' && $this->_tpl_vars['is_realtime']): ?>

<hr />

<h3><?php echo $this->_tpl_vars['lng']['lbl_test_realtime_calculation']; ?>
</h3>

<?php echo $this->_tpl_vars['lng']['txt_test_realtime_calculation_text']; ?>


<br /><br />

<form action="test_realtime_shipping.php" target="_blank">

<?php echo $this->_tpl_vars['lng']['lbl_package_weight']; ?>
 <input type="text" name="weight" value="1" /> <input type="submit" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_test'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />

</form>

<?php elseif ($this->_tpl_vars['option'] == 'Security'): ?>

<hr />

<h3><?php echo $this->_tpl_vars['lng']['lbl_test_data_encryption']; ?>
</h3>

<a href="test_pgp.php"><?php echo $this->_tpl_vars['lng']['lbl_test_data_encryption_link']; ?>
</a>

<?php elseif ($this->_tpl_vars['option'] == 'XPayments_Connector'): ?>

  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/XPayments_Connector/config_bottom.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php endif; ?>

<br />

<?php endif; ?>

<?php if ($this->_tpl_vars['current_storefront'] != '0'): ?>
<script type="text/javascript">
<?php echo 'var ds_config = new Array(';  $_from = $this->_tpl_vars['domain_specific_config'][$this->_tpl_vars['option']]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['f_dc_config'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['f_dc_config']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
        $this->_foreach['f_dc_config']['iteration']++;
 echo '';  if ($this->_tpl_vars['v'] == 'Y'):  echo '';  if (! ($this->_foreach['f_dc_config']['iteration'] <= 1)):  echo ', ';  endif;  echo '\'';  echo $this->_tpl_vars['k'];  echo '\'';  endif;  echo '';  endforeach; endif; unset($_from);  echo ');'; ?>


var txt_domain_specific_option = '<?php echo $this->_tpl_vars['lng']['txt_domain_specific_option']; ?>
';

<?php echo '
if (ds_config.length > 0) {
	for (i = 0; i < ds_config.length; i++) {
		$(\'input[name="\' + ds_config[i] + \'"]\').attr(\'disabled\', \'disabled\');
		$(\'input[name="\' + ds_config[i] + \'"]\').after(\'<br />\' + txt_domain_specific_option);
	}
}
'; ?>

</script>
<?php endif;  endif; ?> 
<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean(); ?>

<?php if ($this->_tpl_vars['option'] == 'Multiple_Storefronts'): ?>
    <?php $this->assign('capture_title', $this->_tpl_vars['lng']['lbl_sf_properties']);  else: ?>
    <?php $this->assign('capture_title', $this->_tpl_vars['lng']['lbl_general_settings']);  endif; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['capture_title'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  if ($this->_tpl_vars['additional_config']):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['additional_config'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
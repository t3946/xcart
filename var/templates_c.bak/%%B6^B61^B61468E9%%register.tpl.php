<?php /* Smarty version 2.6.12, created on 2011-10-11 06:21:51
         compiled from customer/main/register.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'amp', 'customer/main/register.tpl', 146, false),array('modifier', 'default', 'customer/main/register.tpl', 168, false),array('modifier', 'strip_tags', 'customer/main/register.tpl', 228, false),array('modifier', 'escape', 'customer/main/register.tpl', 228, false),)), $this); ?>
<?php func_load_lang($this, "customer/main/register.tpl","lbl_modify_profile,lbl_create_profile,lbl_create_customer_profile,lbl_modify_customer_profile,txt_create_customer_profile,txt_modify_customer_profile,txt_modify_profile_msg,lbl_return_to_search_results,txt_registration_error,txt_email_already_exists,txt_user_already_exists,err_billing_state,err_shipping_state,err_billing_county,err_shipping_county,txt_email_invalid,err_username_invalid,txt_terms_and_conditions_newbie_note,txt_newbie_registration_bottom,txt_user_registration_bottom,lbl_save,txt_profile_modified,txt_partner_created,txt_profile_created"); ?><?php if ($this->_tpl_vars['av_error'] == 1): ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/UPS_OnLine_Tools/register.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php else: ?>

<?php if ($this->_tpl_vars['js_enabled'] == 'Y'):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "check_email_script.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "check_zipcode_js.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "generate_required_fields_js.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> 
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "check_required_fields_js.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  if ($this->_tpl_vars['config']['General']['use_js_states'] == 'Y'):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "change_states_js.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  endif; ?>

<?php if ($this->_tpl_vars['action'] != 'cart'): ?>

<?php if ($this->_tpl_vars['newbie'] == 'Y'):  if ($this->_tpl_vars['login'] != ""):  $this->assign('title', $this->_tpl_vars['lng']['lbl_modify_profile']);  else:  $this->assign('title', $this->_tpl_vars['lng']['lbl_create_profile']);  endif;  else:  if ($this->_tpl_vars['main'] == 'user_add'):  $this->assign('title', $this->_tpl_vars['lng']['lbl_create_customer_profile']);  else: ?> 
<?php $this->assign('title', $this->_tpl_vars['lng']['lbl_modify_customer_profile']);  endif;  endif; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "page_title.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['title'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<!-- IN THIS SECTION -->

<?php if ($this->_tpl_vars['newbie'] != 'Y'):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog_tools.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

<!-- IN THIS SECTION -->

<?php if ($this->_tpl_vars['usertype'] != 'C'): ?>
<br />
<?php if ($this->_tpl_vars['main'] == 'user_add'):  echo $this->_tpl_vars['lng']['txt_create_customer_profile']; ?>

<?php else:  echo $this->_tpl_vars['lng']['txt_modify_customer_profile']; ?>

<?php endif; ?>
<br /><br />
<?php endif; ?>

<?php endif; ?>

<?php ob_start(); ?>

<?php if ($this->_tpl_vars['newbie'] == 'Y'):  if ($this->_tpl_vars['registered'] == ""):  if ($GLOBALS['HTTP_GET_VARS']['mode'] == 'update'): ?>
<font class="Text">
<?php echo $this->_tpl_vars['lng']['txt_modify_profile_msg']; ?>

</font>
<?php if (! is_flc): ?>
<br /><br />
<?php endif;  endif;  endif;  endif; ?>

<?php if ($this->_tpl_vars['newbie'] != 'Y' && $this->_tpl_vars['main'] != 'user_add' && ( $this->_tpl_vars['usertype'] == 'P' && $this->_tpl_vars['active_modules']['Simple_Mode'] == 'Y' || $this->_tpl_vars['usertype'] == 'A' )): ?>
<div align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/button.tpl", 'smarty_include_vars' => array('button_title' => $this->_tpl_vars['lng']['lbl_return_to_search_results'],'href' => "users.php?mode=search")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></div>
<?php endif; ?>

<?php $this->assign('reg_error', $this->_tpl_vars['top_message']['reg_error']);  $this->assign('error', $this->_tpl_vars['top_message']['error']);  $this->assign('emailerror', $this->_tpl_vars['top_message']['emailerror']); ?>

<?php if ($this->_tpl_vars['registered'] == ""):  if ($this->_tpl_vars['reg_error']): ?>
<font class="Star">
<?php if ($this->_tpl_vars['reg_error'] == 'F'):  echo $this->_tpl_vars['lng']['txt_registration_error']; ?>

<?php elseif ($this->_tpl_vars['reg_error'] == 'E'):  echo $this->_tpl_vars['lng']['txt_email_already_exists']; ?>

<?php elseif ($this->_tpl_vars['reg_error'] == 'U'):  echo $this->_tpl_vars['lng']['txt_user_already_exists']; ?>

<?php endif; ?>
</font>
<br />
<?php endif; ?>

<?php if ($this->_tpl_vars['error'] != ""): ?>
<font class="Star"></strong>
<?php if ($this->_tpl_vars['error'] == 'b_statecode'):  echo $this->_tpl_vars['lng']['err_billing_state']; ?>

<?php elseif ($this->_tpl_vars['error'] == 's_statecode'):  echo $this->_tpl_vars['lng']['err_shipping_state']; ?>

<?php elseif ($this->_tpl_vars['error'] == 'b_county'):  echo $this->_tpl_vars['lng']['err_billing_county']; ?>

<?php elseif ($this->_tpl_vars['error'] == 's_county'):  echo $this->_tpl_vars['lng']['err_shipping_county']; ?>

<?php elseif ($this->_tpl_vars['error'] == 'email'):  echo $this->_tpl_vars['lng']['txt_email_invalid']; ?>

<?php elseif ($this->_tpl_vars['error'] == 'username'):  echo $this->_tpl_vars['lng']['err_username_invalid']; ?>

<?php else:  echo $this->_tpl_vars['error']; ?>

<?php endif; ?>
</strong></font>
<br />
<?php endif; ?>

<script type="text/javascript" language="JavaScript 1.2">
<!--

var show_spam = false;
function check_spam() <?php echo $this->_tpl_vars['ldelim']; ?>

    if ( 0 ||
        <?php if ($this->_tpl_vars['default_fields']['s_country']['avail'] == 'Y'): ?> document.registerform.s_country.value == "AF" ||<?php endif; ?> 
        <?php if ($this->_tpl_vars['default_fields']['b_country']['avail'] == 'Y'): ?> document.registerform.b_country.value == "AF" ||<?php endif; ?>
        <?php if ($this->_tpl_vars['default_fields']['s_firstname']['avail'] == 'Y' && $this->_tpl_vars['default_fields']['s_lastname']['avail'] == 'Y'): ?>document.registerform.s_firstname.value ==  document.registerform.s_lastname.value ||<?php endif; ?>
        <?php if ($this->_tpl_vars['default_fields']['firstname']['avail'] == 'Y' && $this->_tpl_vars['default_fields']['lastname']['avail'] == 'Y'): ?>document.registerform.firstname.value ==  document.registerform.lastname.value ||<?php endif; ?> 
        0
        )
        return false;
    return true; 
<?php echo $this->_tpl_vars['rdelim']; ?>


var is_run = false;
function check_registerform_fields() {
	if(is_run)
		return false;
	is_run = true;
	if (check_zip_code()<?php if ($this->_tpl_vars['default_fields']['email']['avail'] == 'Y'): ?> && checkEmailAddress(document.registerform.email, '<?php echo $this->_tpl_vars['default_fields']['email']['required']; ?>
')<?php endif; ?> <?php if ($this->_tpl_vars['config']['General']['check_cc_number'] == 'Y' && $this->_tpl_vars['config']['General']['disable_cc'] != 'Y'): ?>&& checkCCNumber(document.registerform.card_number,document.registerform.card_type) <?php endif; ?>&& checkRequired(requiredFields)) {
		document.registerform.submit();
		return true;
	}
	is_run = false;
	return false;
}
-->
</script>

<form action="<?php echo $this->_tpl_vars['register_script_name']; ?>
?<?php echo ((is_array($_tmp=$GLOBALS['HTTP_SERVER_VARS']['QUERY_STRING'])) ? $this->_run_mod_handler('amp', true, $_tmp) : smarty_modifier_amp($_tmp)); ?>
" method="post" name="registerform" id="autofillform" onsubmit="javascript: check_registerform_fields(); return false;">
<?php if ($this->_tpl_vars['config']['Security']['use_https_login'] == 'Y'): ?>
<input type="hidden" name="<?php echo $this->_tpl_vars['XCARTSESSNAME']; ?>
" value="<?php echo $this->_tpl_vars['XCARTSESSID']; ?>
" />
<?php endif; ?>
<table cellspacing="1" cellpadding="2" width="100%">
<tbody>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/register_shipping_address.tpl", 'smarty_include_vars' => array('userinfo' => $this->_tpl_vars['userinfo'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/register_billing_address.tpl", 'smarty_include_vars' => array('userinfo' => $this->_tpl_vars['userinfo'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/register_contact_info.tpl", 'smarty_include_vars' => array('userinfo' => $this->_tpl_vars['userinfo'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/register_additional_info.tpl", 'smarty_include_vars' => array('section' => 'A')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if ($this->_tpl_vars['config']['General']['disable_cc'] != 'Y'):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/register_ccinfo.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

<?php if (! ( ( ( $this->_tpl_vars['active_modules']['Simple_Mode'] != "" && $this->_tpl_vars['usertype'] == 'P' ) || $this->_tpl_vars['usertype'] == 'A' ) && ( $this->_tpl_vars['userinfo']['uname'] && $this->_tpl_vars['userinfo']['uname'] != $this->_tpl_vars['login'] || ! $this->_tpl_vars['userinfo']['uname'] && $this->_tpl_vars['userinfo']['login'] != $this->_tpl_vars['login'] ) )): ?>
	<tr style="display: none;">
		<td>
			<input type="hidden" name="uname" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['userinfo']['login'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['userinfo']['uname']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['userinfo']['uname'])); ?>
" />
			<input type="hidden" name="passwd1" value="<?php echo $this->_tpl_vars['userinfo']['passwd1']; ?>
" />
			<input type="hidden" name="passwd2" value="<?php echo $this->_tpl_vars['userinfo']['passwd2']; ?>
" />
		</td>
	</tr>
<?php endif; ?>

<?php if ($this->_tpl_vars['active_modules']['Special_Offers'] && $this->_tpl_vars['usertype'] != 'C'):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Special_Offers/customer/register_bonuses.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

<?php if ($this->_tpl_vars['active_modules']['News_Management'] && $this->_tpl_vars['newslists']):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/News_Management/register_newslists.tpl", 'smarty_include_vars' => array('userinfo' => $this->_tpl_vars['userinfo'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

<?php if ($this->_tpl_vars['active_modules']['Image_Verification'] && $this->_tpl_vars['show_antibot']['on_registration'] == 'Y' && $this->_tpl_vars['display_antibot']):  $this->assign('antibot_err', $this->_tpl_vars['reg_antibot_err']);  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Image_Verification/spambot_arrest.tpl", 'smarty_include_vars' => array('mode' => 'advanced','id' => $this->_tpl_vars['antibot_sections']['on_registration'],'reg_id' => 'img_reg')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

<tr>
<td colspan="3">
<?php if ($this->_tpl_vars['newbie'] == 'Y'):  echo $this->_tpl_vars['lng']['txt_terms_and_conditions_newbie_note']; ?>

<?php echo $this->_tpl_vars['lng']['txt_newbie_registration_bottom']; ?>

<?php else:  echo $this->_tpl_vars['lng']['txt_user_registration_bottom']; ?>

<?php endif;  if ($this->_tpl_vars['is_areas']['S'] == 'Y' || $this->_tpl_vars['is_areas']['B'] == 'Y'):  if ($this->_tpl_vars['active_modules']['UPS_OnLine_Tools'] && $this->_tpl_vars['av_enabled'] == 'Y'): ?>
<table cellpadding="1" cellspacing="1" width="100%">
<tbody>
<tr>
<td colspan="3">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/UPS_OnLine_Tools/ups_av_notice.tpl", 'smarty_include_vars' => array('postoffice' => 1)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/UPS_OnLine_Tools/ups_av_notice.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</td>
</tr>
</tbody>
</table>
<?php endif;  endif; ?>
</td>
</tr>

<tr>
<td colspan="3" align='center'>

<?php if ($GLOBALS['HTTP_GET_VARS']['mode'] == 'update'): ?>
<input type="hidden" name="mode" value="update" />
<?php endif; ?>

<input type="hidden" name="anonymous" value="<?php echo $this->_tpl_vars['anonymous']; ?>
" />

<br>

<?php if ($this->_tpl_vars['js_enabled'] && $this->_tpl_vars['usertype'] == 'C'):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/submit.tpl", 'smarty_include_vars' => array('type' => 'input','style' => 'button','href' => "javascript: return check_registerform_fields();",'b' => '1')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  else: ?>
<input type="submit" value=" <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_save'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 " />
<?php endif; ?>

</td>
</tr>

</tbody>
</table>
<input type="hidden" name="usertype" value="<?php if ($GLOBALS['HTTP_GET_VARS']['usertype'] != ""):  echo ((is_array($_tmp=$GLOBALS['HTTP_GET_VARS']['usertype'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'));  else:  echo $this->_tpl_vars['usertype'];  endif; ?>" />
</form>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "billing_autofill.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  else:  if ($GLOBALS['HTTP_POST_VARS']['mode'] == 'update' || $GLOBALS['HTTP_GET_VARS']['mode'] == 'update'):  echo $this->_tpl_vars['lng']['txt_profile_modified']; ?>

<?php elseif ($GLOBALS['HTTP_GET_VARS']['usertype'] == 'B' || $this->_tpl_vars['usertype'] == 'B'):  echo $this->_tpl_vars['lng']['txt_partner_created']; ?>

<?php else:  echo $this->_tpl_vars['lng']['txt_profile_created']; ?>

<?php endif;  endif;  $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => '','content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
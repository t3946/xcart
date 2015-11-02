<?php /* Smarty version 2.6.12, created on 2011-10-11 06:10:08
         compiled from modules/Gift_Certificates/giftcert.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'strip_tags', 'modules/Gift_Certificates/giftcert.tpl', 13, false),array('modifier', 'replace', 'modules/Gift_Certificates/giftcert.tpl', 13, false),array('modifier', 'escape', 'modules/Gift_Certificates/giftcert.tpl', 20, false),array('modifier', 'default', 'modules/Gift_Certificates/giftcert.tpl', 22, false),array('modifier', 'formatprice', 'modules/Gift_Certificates/giftcert.tpl', 224, false),)), $this); ?>
<?php func_load_lang($this, "modules/Gift_Certificates/giftcert.tpl","lbl_gift_certificate,txt_recipient_invalid,txt_amount_invalid,txt_gc_enter_mail_address,txt_gc_header,txt_gift_certificate_checking_msg,err_gc_not_found,lbl_gift_certificate,lbl_submit,lbl_gc_id,lbl_amount,lbl_remain,lbl_status,lbl_pending,lbl_active,lbl_blocked,lbl_disabled,lbl_expired,lbl_used,lbl_gift_certificate_checking,txt_amount_invalid,lbl_gc_whom_sending,lbl_gc_whom_sending_subtitle,lbl_from,lbl_to,lbl_gc_add_message,lbl_gc_add_message_subtitle,lbl_message,lbl_gc_choose_amount,lbl_gc_choose_amount_subtitle,lbl_gc_amount_msg,lbl_gc_from,lbl_gc_through,lbl_gc_choose_delivery_method,lbl_gc_send_via_email,lbl_gc_enter_email,lbl_email,lbl_gc_send_via_postal_mail,txt_gc_enter_postal_mail,lbl_first_name,lbl_last_name,lbl_address,lbl_city,lbl_county,lbl_state,lbl_country,lbl_zip_code,lbl_phone,lbl_gc_template,lbl_preview,lbl_gc_add_to_cart,lbl_gc_create,lbl_gift_certificate_details"); ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "page_title.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_gift_certificate'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if (( $this->_tpl_vars['config']['Gift_Certificates']['allow_customer_select_tpl'] == 'Y' && $this->_tpl_vars['usertype'] == 'C' ) || $this->_tpl_vars['usertype'] == 'A' || ( $this->_tpl_vars['usertype'] == 'P' && $this->_tpl_vars['active_modules']['Simple_Mode'] != "" )):  $this->assign('allow_tpl', '1');  else:  $this->assign('allow_tpl', '');  endif; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "check_email_script.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<script type="text/javascript" language="JavaScript 1.2">
<!--
var txt_recipient_invalid = "<?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['txt_recipient_invalid'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)))) ? $this->_run_mod_handler('replace', true, $_tmp, "\n", ' ') : smarty_modifier_replace($_tmp, "\n", ' ')))) ? $this->_run_mod_handler('replace', true, $_tmp, "\r", ' ') : smarty_modifier_replace($_tmp, "\r", ' ')); ?>
";
var txt_amount_invalid = "<?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['txt_amount_invalid'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)))) ? $this->_run_mod_handler('replace', true, $_tmp, "\n", ' ') : smarty_modifier_replace($_tmp, "\n", ' ')))) ? $this->_run_mod_handler('replace', true, $_tmp, "\r", ' ') : smarty_modifier_replace($_tmp, "\r", ' ')); ?>
";
var txt_gc_enter_mail_address = "<?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['txt_gc_enter_mail_address'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)))) ? $this->_run_mod_handler('replace', true, $_tmp, "\n", ' ') : smarty_modifier_replace($_tmp, "\n", ' ')))) ? $this->_run_mod_handler('replace', true, $_tmp, "\r", ' ') : smarty_modifier_replace($_tmp, "\r", ' ')); ?>
";

<?php if ($this->_tpl_vars['usertype'] == 'C'): ?>
var orig_mode = "gc2cart";
<?php else: ?>
var orig_mode = "<?php echo ((is_array($_tmp=$GLOBALS['HTTP_GET_VARS']['mode'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
";
<?php endif; ?>
var min_gc_amount = <?php echo ((is_array($_tmp=@$this->_tpl_vars['min_gc_amount'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
;
var max_gc_amount = <?php echo ((is_array($_tmp=@$this->_tpl_vars['max_gc_amount'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
;
var is_c_area = <?php if ($this->_tpl_vars['usertype'] == 'C'): ?>true<?php else: ?>false<?php endif; ?>;
var enablePostMailGC = "<?php echo $this->_tpl_vars['config']['Gift_Certificates']['enablePostMailGC']; ?>
";

<?php echo '
function check_gc_form() {
	if (document.gccreate.recipient.value == "") {
		document.gccreate.recipient.focus();
		alert (txt_recipient_invalid);
		return false;
	}

	var num = convert_number(document.gccreate.amount.value);
	if (!check_is_number(document.gccreate.amount.value) || (is_c_area && (num < min_gc_amount || (max_gc_amount > 0 && num > max_gc_amount)))) {
		document.gccreate.amount.focus();
	    alert (txt_amount_invalid);
		return false;
	}

	if (enablePostMailGC == \'Y\') {
		if ((document.gccreate.send_via[0].checked) && (!checkEmailAddress(document.gccreate.recipient_email))) {
			document.gccreate.recipient_email.focus();
			return false;
		}
		if (document.gccreate.send_via[1].checked && (document.gccreate.recipient_firstname.value == "" || document.gccreate.recipient_lastname.value == "" || document.gccreate.recipient_address.value == "" || document.gccreate.recipient_city.value == "" || document.gccreate.recipient_zipcode.value == "")) {
			document.gccreate.recipient_firstname.focus();
			alert (txt_gc_enter_mail_address);
			return false;
		}

	} else if (!checkEmailAddress(document.gccreate.recipient_email)) {
		document.gccreate.recipient_email.focus();
		return false;
	}

	return true;
}

function formSubmit() {
	if (check_gc_form()) {
		document.gccreate.mode.value = orig_mode;
		document.gccreate.target = \'\'
		document.gccreate.submit();
	}
}
-->
</script>
'; ?>


<?php if ($this->_tpl_vars['config']['Gift_Certificates']['enablePostMailGC'] == 'Y' && $this->_tpl_vars['allow_tpl']): ?>
<script type="text/javascript" language="JavaScript 1.2">
<!--
<?php echo '
function switchPreview() {
	if (document.gccreate.send_via[0].checked) {
		document.getElementById(\'preview_button\').style.display=\'none\';
		document.getElementById(\'preview_template\').style.display=\'none\';
	}
	if (document.gccreate.send_via[1].checked) {
		document.getElementById(\'preview_button\').style.display=\'\';
		document.getElementById(\'preview_template\').style.display=\'\';
	}
}

function formPreview() {
	if (check_gc_form()) {
		document.gccreate.mode.value=\'preview\';
		document.gccreate.target=\'_blank\'
		document.gccreate.submit();
	}
}
'; ?>

-->
</script>
<?php else: ?>
<script type="text/javascript" language="JavaScript 1.2">
<!--
<?php echo '
function switchPreview() {
	return false;
}
'; ?>

-->
</script>
<?php endif; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "check_zipcode_js.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<table cellpadding="5">
<tr>
	<td><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/gift.gif" alt="" /></td>
	<td><?php echo $this->_tpl_vars['lng']['txt_gc_header']; ?>
</td>
</tr>
</table>
<?php if ($this->_tpl_vars['login'] && $this->_tpl_vars['usertype'] == 'C'): ?>
<p />
<?php ob_start();  echo $this->_tpl_vars['lng']['txt_gift_certificate_checking_msg']; ?>

<br /><br />
<?php if ($GLOBALS['HTTP_GET_VARS']['gcid'] && $this->_tpl_vars['gc_array'] == ""): ?>
<font class="ErrorMessage"><?php echo $this->_tpl_vars['lng']['err_gc_not_found']; ?>
</font>
<?php endif; ?>
<form action="giftcert.php">
<table>
<tr>
	<td><?php echo $this->_tpl_vars['lng']['lbl_gift_certificate']; ?>
:</td>
	<td><input type="text" size="25" maxlength="16" name="gcid" value="<?php echo ((is_array($_tmp=$GLOBALS['HTTP_GET_VARS']['gcid'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" /></td>
	<td><input type="submit" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_submit'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td>
</tr>
</table>
</form>
<?php if ($this->_tpl_vars['gc_array']): ?>
<hr size="1" noshade="noshade" />
<table>
<tr>
	<td><b><?php echo $this->_tpl_vars['lng']['lbl_gc_id']; ?>
:</b></td>
	<td><?php echo $this->_tpl_vars['gc_array']['gcid']; ?>
</td>
</tr>
<tr>
	<td><b><?php echo $this->_tpl_vars['lng']['lbl_amount']; ?>
:</b></td>
	<td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['gc_array']['amount'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>
<tr>
	<td><b><?php echo $this->_tpl_vars['lng']['lbl_remain']; ?>
:</b></td>
	<td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['gc_array']['debit'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>
<tr>
	<td><b><?php echo $this->_tpl_vars['lng']['lbl_status']; ?>
:</b></td>
	<td>
<?php if ($this->_tpl_vars['gc_array']['status'] == 'P'):  echo $this->_tpl_vars['lng']['lbl_pending']; ?>

<?php elseif ($this->_tpl_vars['gc_array']['status'] == 'A'):  echo $this->_tpl_vars['lng']['lbl_active']; ?>

<?php elseif ($this->_tpl_vars['gc_array']['status'] == 'B'):  echo $this->_tpl_vars['lng']['lbl_blocked']; ?>

<?php elseif ($this->_tpl_vars['gc_array']['status'] == 'D'):  echo $this->_tpl_vars['lng']['lbl_disabled']; ?>

<?php elseif ($this->_tpl_vars['gc_array']['status'] == 'E'):  echo $this->_tpl_vars['lng']['lbl_expired']; ?>

<?php elseif ($this->_tpl_vars['gc_array']['status'] == 'U'):  echo $this->_tpl_vars['lng']['lbl_used']; ?>

<?php endif; ?>
	</td>
</tr>
</table>
<?php endif;  $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_gift_certificate_checking'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
<p />
<?php ob_start();  if ($this->_tpl_vars['amount_error']): ?>
<p class="ErrorMessage"><?php echo $this->_tpl_vars['lng']['txt_amount_invalid']; ?>
</p>
<?php endif;  if ($this->_tpl_vars['usertype'] == 'C'): ?>

<form name="gccreate" action="giftcert.php" method="post" onsubmit="javascript: return check_gc_form()">
<input type="hidden" name="gcindex" value="<?php echo ((is_array($_tmp=$GLOBALS['HTTP_GET_VARS']['gcindex'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" />
<input type="hidden" name="mode" value="gc2cart" />

<?php else: ?>

<form name="gccreate" action="giftcerts.php" method="post" onsubmit="javascript: return check_gc_form()">
<input type="hidden" name="mode" value="<?php echo ((is_array($_tmp=$GLOBALS['HTTP_GET_VARS']['mode'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" />
<input type="hidden" name="gcid" value="<?php echo ((is_array($_tmp=$GLOBALS['HTTP_GET_VARS']['gcid'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" />

<?php endif; ?>
<table width="100%" cellpadding="0">
<tr>
<td colspan="3"><b><font class="Green2">1. <?php echo $this->_tpl_vars['lng']['lbl_gc_whom_sending']; ?>
<br /></font></b>
<?php echo $this->_tpl_vars['lng']['lbl_gc_whom_sending_subtitle']; ?>
<br />
</td>
</tr>

<tr>
<td align="right"><?php echo $this->_tpl_vars['lng']['lbl_from']; ?>
</td>
<td><font class="Star">*</font></td>
<td align="left"><input type="text" name="purchaser" size="30" value="<?php if ($this->_tpl_vars['usertype'] == 'A' && $GLOBALS['HTTP_GET_VARS']['mode'] == 'add_gc'):  echo $this->_tpl_vars['config']['Company']['company_name'];  else:  if ($this->_tpl_vars['giftcert']['purchaser']):  echo ((is_array($_tmp=$this->_tpl_vars['giftcert']['purchaser'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'));  else:  echo $this->_tpl_vars['userinfo']['firstname'];  if ($this->_tpl_vars['userinfo']['firstname'] != ''): ?> <?php endif;  echo $this->_tpl_vars['userinfo']['lastname'];  endif;  endif; ?>" /></td>
</tr>

<tr>
<td align="right"><?php echo $this->_tpl_vars['lng']['lbl_to']; ?>
</td>
<td><font class="Star">*</font></td>
<td align="left"><input type="text" name="recipient" size="30" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['giftcert']['recipient'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" /> </td>
</tr>

<tr>
<td colspan="3"><b><font class="Green2"><br />2. <?php echo $this->_tpl_vars['lng']['lbl_gc_add_message']; ?>
<br /></font></b>
<?php echo $this->_tpl_vars['lng']['lbl_gc_add_message_subtitle']; ?>
<br />
</td>
</tr>

<tr>
<td align="right"><?php echo $this->_tpl_vars['lng']['lbl_message']; ?>
</td>
<td><font class="Star"></font></td>
<td align="left"><textarea name="message" rows="8" cols="50"><?php echo $this->_tpl_vars['giftcert']['message']; ?>
</textarea></td>
</tr>

<tr>
<td colspan="3"><b><font class="Green2"><br />3. <?php echo $this->_tpl_vars['lng']['lbl_gc_choose_amount']; ?>
<br /></font></b>
<?php echo $this->_tpl_vars['lng']['lbl_gc_choose_amount_subtitle']; ?>
<br />
</td>
</tr>

<tr>
<td align="right"><?php echo $this->_tpl_vars['config']['General']['currency_symbol']; ?>
</td>
<td><font class="Star">*</font></td>
<td align="left"><input type="text" name="amount" size="10" maxlength="9" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['giftcert']['amount'])) ? $this->_run_mod_handler('formatprice', true, $_tmp) : smarty_modifier_formatprice($_tmp)); ?>
" />
<?php if ($this->_tpl_vars['usertype'] == 'C' && ( $this->_tpl_vars['min_gc_amount'] > 0 || $this->_tpl_vars['max_gc_amount'] > 0 )):  echo $this->_tpl_vars['lng']['lbl_gc_amount_msg']; ?>
 <?php if ($this->_tpl_vars['min_gc_amount'] > 0):  echo $this->_tpl_vars['lng']['lbl_gc_from']; ?>
 <?php echo $this->_tpl_vars['config']['General']['currency_symbol'];  echo ((is_array($_tmp=$this->_tpl_vars['min_gc_amount'])) ? $this->_run_mod_handler('formatprice', true, $_tmp) : smarty_modifier_formatprice($_tmp));  endif; ?> <?php if ($this->_tpl_vars['max_gc_amount'] > 0):  echo $this->_tpl_vars['lng']['lbl_gc_through']; ?>
 <?php echo $this->_tpl_vars['config']['General']['currency_symbol'];  echo ((is_array($_tmp=$this->_tpl_vars['max_gc_amount'])) ? $this->_run_mod_handler('formatprice', true, $_tmp) : smarty_modifier_formatprice($_tmp));  endif;  endif; ?>
</td></tr>


<tr><td colspan="3">&nbsp;</td></tr>

<tr><td colspan="3"><b><font class="Green2"><br />4. <?php echo $this->_tpl_vars['lng']['lbl_gc_choose_delivery_method']; ?>
<br /><br /></font></b></td>
</tr>

<tr><td colspan="3">
<table cellspacing="0" cellpadding="0"><tr>
<?php if ($this->_tpl_vars['config']['Gift_Certificates']['enablePostMailGC'] == 'Y'): ?>
<td align="right"><input id="gc_send_e" type="radio" name="send_via" value="E" onclick="switchPreview();"<?php if ($this->_tpl_vars['giftcert']['send_via'] != 'P'): ?> checked="checked"<?php endif; ?> /></td>
<?php else: ?>
<input type="hidden" name="send_via" value="E" />
<?php endif; ?>
<td><label for="gc_send_e"><b><?php echo $this->_tpl_vars['lng']['lbl_gc_send_via_email']; ?>
</b></label></td>
</tr></table></td>
</tr>

<tr><td colspan="3"><?php echo $this->_tpl_vars['lng']['lbl_gc_enter_email']; ?>
<br /><br /></td></tr>

<tr>
<td nowrap="nowrap" align="right"><?php echo $this->_tpl_vars['lng']['lbl_email']; ?>
</td>
<td><font class="Star">*</font></td>
<td align="left"><input type="text" name="recipient_email" size="30" value="<?php echo $this->_tpl_vars['giftcert']['recipient_email']; ?>
" /></td>
</tr>

<?php if ($this->_tpl_vars['config']['Gift_Certificates']['enablePostMailGC'] == 'Y'): ?>

<tr><td colspan="3">&nbsp;</td></tr>

<tr><td colspan="3"><table cellspacing="0" cellpadding="0" width="100%"><tr><td bgcolor="#CCCCCC"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/null.gif" class="Spc" alt="" /><br /></td></tr></table></td></tr>

<tr><td colspan="3">&nbsp;</td></tr>

<tr><td colspan="3"><table cellspacing="0" cellpadding="0"><tr>
<td align="right"><input id="gc_send_p" type="radio" name="send_via" value="P" onclick="switchPreview();"<?php if ($this->_tpl_vars['giftcert']['send_via'] == 'P'): ?> checked="checked"<?php endif; ?> /></td>
<td><label for="gc_send_p"><b><?php echo $this->_tpl_vars['lng']['lbl_gc_send_via_postal_mail']; ?>
</b></label></td>
</tr></table></td>
</tr>

<tr><td colspan="3"><?php echo $this->_tpl_vars['lng']['txt_gc_enter_postal_mail']; ?>
<br /><br /></td></tr>

<tr>
<td nowrap="nowrap" align="right"><?php echo $this->_tpl_vars['lng']['lbl_first_name']; ?>
</td>
<td><font class="Star">*</font></td>
<td align="left"><input type="text" name="recipient_firstname" size="30" value="<?php echo $this->_tpl_vars['giftcert']['recipient_firstname']; ?>
" /></td>
</tr>

<tr>
<td nowrap="nowrap" align="right"><?php echo $this->_tpl_vars['lng']['lbl_last_name']; ?>
</td>
<td><font class="Star">*</font></td>
<td align="left"><input type="text" name="recipient_lastname" size="30" value="<?php echo $this->_tpl_vars['giftcert']['recipient_lastname']; ?>
" /></td>
</tr>

<tr>
<td nowrap="nowrap" align="right"><?php echo $this->_tpl_vars['lng']['lbl_address']; ?>
</td>
<td><font class="Star">*</font></td>
<td align="left"><input type="text" name="recipient_address" size="40" value="<?php echo $this->_tpl_vars['giftcert']['recipient_address']; ?>
" /></td>
</tr>

<tr>
<td nowrap="nowrap" align="right"><?php echo $this->_tpl_vars['lng']['lbl_city']; ?>
</td>
<td><font class="Star">*</font></td>
<td align="left"><input type="text" name="recipient_city" size="30" value="<?php echo $this->_tpl_vars['giftcert']['recipient_city']; ?>
" /></td>
</tr>

<?php if ($this->_tpl_vars['config']['General']['use_counties'] == 'Y'): ?>
<tr>
<td nowrap="nowrap" align="right"><?php echo $this->_tpl_vars['lng']['lbl_county']; ?>
</td>
<td><font class="Star">*</font></td>
<td align="left">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/counties.tpl", 'smarty_include_vars' => array('counties' => $this->_tpl_vars['counties'],'name' => 'recipient_county','default' => $this->_tpl_vars['giftcert']['recipient_county'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</td>
</tr>
<?php endif; ?>

<tr>
<td nowrap="nowrap" align="right"><?php echo $this->_tpl_vars['lng']['lbl_state']; ?>
</td>
<td><font class="Star">*</font></td>
<td align="left">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/states.tpl", 'smarty_include_vars' => array('states' => $this->_tpl_vars['states'],'name' => 'recipient_state','default' => $this->_tpl_vars['giftcert']['recipient_state'],'default_country' => $this->_tpl_vars['giftcert']['recipient_country'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</td></tr>

<tr>
<td nowrap="nowrap" align="right"><?php echo $this->_tpl_vars['lng']['lbl_country']; ?>
</td>
<td><font class="Star">*</font></td>
<td align="left">
<select id="recipient_country" name="recipient_country" size="1" onchange="javascript: check_zip_code_field(this, document.gccreate.recipient_zipcode);">
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
"<?php if ($this->_tpl_vars['giftcert']['recipient_country'] == $this->_tpl_vars['countries'][$this->_sections['country_idx']['index']]['country_code']): ?> selected="selected"<?php elseif ($this->_tpl_vars['countries'][$this->_sections['country_idx']['index']]['country_code'] == $this->_tpl_vars['config']['General']['default_country'] && $this->_tpl_vars['giftcert']['recipient_country'] == ""): ?> selected="selected"<?php elseif ($this->_tpl_vars['countries'][$this->_sections['country_idx']['index']]['country_code'] == $this->_tpl_vars['userinfo']['b_country'] && $this->_tpl_vars['giftcert']['recipient_country'] == ""): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['countries'][$this->_sections['country_idx']['index']]['country']; ?>
</option>
<?php endfor; endif; ?>
</select>
</td>
</tr>

<?php if ($this->_tpl_vars['js_enabled'] == 'Y' && $this->_tpl_vars['config']['General']['use_js_states'] == 'Y'): ?>
<tr style="display: none;">
	<td>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "change_states_js.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/register_states.tpl", 'smarty_include_vars' => array('state_name' => 'recipient_state','country_name' => 'recipient_country','county_name' => 'recipient_county','state_value' => $this->_tpl_vars['giftcert']['recipient_state'],'county_value' => $this->_tpl_vars['giftcert']['recipient_county'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	</td>
</tr>
<?php endif; ?>

<tr>
<td nowrap="nowrap" align="right"><?php echo $this->_tpl_vars['lng']['lbl_zip_code']; ?>
</td>
<td><font class="Star">*</font></td>
<td align="left"><input type="text" name="recipient_zipcode" size="30" value="<?php echo $this->_tpl_vars['giftcert']['recipient_zipcode']; ?>
" onchange="javascript: check_zip_code_field(document.forms['gccreate'].recipient_country, document.forms['gccreate'].recipient_zipcode);" /></td>
</tr>

<tr>
<td nowrap="nowrap" align="right"><?php echo $this->_tpl_vars['lng']['lbl_phone']; ?>
</td>
<td></td>
<td align="left"><input type="text" name="recipient_phone" size="30" value="<?php echo $this->_tpl_vars['giftcert']['recipient_phone']; ?>
" /></td>
</tr>

<?php endif; ?>

<?php if ($this->_tpl_vars['allow_tpl']): ?>
<tr id="preview_template" <?php if ($this->_tpl_vars['giftcert']['send_via'] != 'P'): ?>style="display: none;"<?php else: ?>style="display: '';"<?php endif; ?>>
<td nowrap="nowrap" align="right"><?php echo $this->_tpl_vars['lng']['lbl_gc_template']; ?>
</td>
<td>&nbsp;</td>
<td align="left">
<select name="gc_template">
<?php $_from = $this->_tpl_vars['gc_templates']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['gc_tpl']):
?>
<option value="<?php echo ((is_array($_tmp=$this->_tpl_vars['gc_tpl'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"<?php if ($this->_tpl_vars['gc_tpl'] == $this->_tpl_vars['giftcert']['tpl_file'] || $this->_tpl_vars['giftcert']['tpl_file'] == "" && $this->_tpl_vars['gc_tpl'] == $this->_tpl_vars['config']['Gift_Certificates']['default_giftcert_template']): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['gc_tpl']; ?>
</option>
<?php endforeach; endif; unset($_from); ?>
</select>
</td>
</tr>

<?php endif; ?>
</table>
</form>
<p />
<center>

<table cellspacing="0" cellpadding="0">
<tr>
<?php if ($this->_tpl_vars['allow_tpl']): ?>
<td id="preview_button" <?php if ($this->_tpl_vars['giftcert']['send_via'] != 'P'): ?>style="display: none;"<?php endif; ?>><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/button.tpl", 'smarty_include_vars' => array('button_title' => $this->_tpl_vars['lng']['lbl_preview'],'href' => "javascript: void(formPreview());",'js_to_href' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
<?php endif; ?>

<?php if ($this->_tpl_vars['usertype'] == 'C' || ( ( $this->_tpl_vars['usertype'] == 'A' || ( $this->_tpl_vars['usertype'] == 'P' && $this->_tpl_vars['active_modules']['Simple_Mode'] != "" ) ) && $GLOBALS['HTTP_GET_VARS']['mode'] == 'modify_gc' )):  if ($GLOBALS['HTTP_GET_VARS']['gcindex'] != "" || ( $this->_tpl_vars['usertype'] == 'A' && $GLOBALS['HTTP_GET_VARS']['mode'] == 'modify_gc' )): ?>
<td>
<?php if ($this->_tpl_vars['active_modules']['Wishlist'] != "" && $this->_tpl_vars['action'] == 'wl'):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/gc_update.tpl", 'smarty_include_vars' => array('href' => "javascript: document.gccreate.mode.value='addgc2wl'; formSubmit();",'js_to_href' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  else:  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/gc_update.tpl", 'smarty_include_vars' => array('href' => "javascript: formSubmit();",'js_to_href' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
</td>
<?php else: ?>
<td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/button.tpl", 'smarty_include_vars' => array('button_title' => $this->_tpl_vars['lng']['lbl_gc_add_to_cart'],'href' => "javascript: formSubmit();",'js_to_href' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
<?php if ($this->_tpl_vars['active_modules']['Wishlist'] && $this->_tpl_vars['login'] != ""): ?>
<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/add_to_wishlist.tpl", 'smarty_include_vars' => array('href' => "javascript: orig_mode = 'addgc2wl'; formSubmit();",'js_to_href' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
<?php endif;  endif;  else: ?>
<td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/button.tpl", 'smarty_include_vars' => array('button_title' => $this->_tpl_vars['lng']['lbl_gc_create'],'href' => "javascript: formSubmit();",'js_to_href' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
<?php endif; ?>
</tr>
</table>
</center>
<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_gift_certificate_details'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php /* Smarty version 2.6.12, created on 2015-11-02 03:06:29
         compiled from main/register_contact_info.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'main/register_contact_info.tpl', 1, false),array('modifier', 'replace', 'main/register_contact_info.tpl', 111, false),)), $this); ?>
<?php func_load_lang($this, "main/register_contact_info.tpl","lbl_contact_information,lbl_title,lbl_first_name,lbl_CHECKOUT_FIELD_DESCRIPTION_first_name,lbl_CHECKOUT_FIELD_DESCRIPTION_first_name,lbl_fill_in_examples_firstname,lbl_last_name,lbl_company,lbl_fill_in_examples_Company_name,lbl_ssn,lbl_tax_number,lbl_tax_exemption,txt_tax_exemption_assigned,lbl_referred_by,lbl_unknown,lbl_phone,lbl_CHECKOUT_FIELD_DESCRIPTION_phone,lbl_fill_in_examples_phone,lbl_phone_ext,lbl_CHECKOUT_FIELD_DESCRIPTION_phone_ext,lbl_fill_in_examples_phone_ext,lbl_email,lbl_CHECKOUT_FIELD_DESCRIPTION_email,lbl_fill_in_examples_email,txt_email_invalid,lbl_fax,lbl_web_site"); ?><?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "main/register_contact_info.tpl"), $this); endif;  if ($this->_tpl_vars['usertype'] != 'P' && $this->_tpl_vars['usertype'] != 'A'): ?>
<script type="text/javascript" language="JavaScript 1.2">
//<![CDATA[
<?php echo '
  $(document).ready(function() {  

        $(\'#email\').focusout(function() {

                if ($(\'#email\').val() != ""){
			checkEmailAddress(document.registerform.email, \'Y\')
                }
                else {
                        document.getElementById("email_verified").style.display = \'none\';                      
                        document.getElementById("email_error").style.display = \'\';  
                        document.getElementById("email_error_text").style.display = \'\';  
                }
        });

        $(\'#phone\').focusout(function() {


		var s_country_in_registerform = cidev_get_country_code("s_countryname");
		var b_country_in_registerform = cidev_get_country_code("b_countryname");

		if (!$(\'#ship2diff\').attr(\'checked\')) {
			b_country_in_registerform = s_country_in_registerform;
		}

		var phone_length_ok = "Y";

		if (s_country_in_registerform == b_country_in_registerform && b_country_in_registerform == "US"){
			var tmp_phone_field_val = $(\'#phone\').val();
			tmp_phone_field_val = tmp_phone_field_val.replace(/[^0-9]/g, \'\');
			var tmp_phone_field_val_length = tmp_phone_field_val.length;

			if (tmp_phone_field_val_length < 10){
				phone_length_ok = "N";
			}
		}

		if (phone_length_ok == "Y") {
			cidev_check_verified_image_for_field(\'phone\');
		}
		else {
                        document.getElementById("phone_error").style.display = \'\';
                        document.getElementById("phone_verified").style.display = \'none\';
		}
        });

        $(\'#phone_ext\').focusout(function() {
		if ($(\'#phone_ext\').val() != ""){
                        document.getElementById("phone_ext_verified").style.display = \'\';
                        document.getElementById("phone_ext_error").style.display = \'none\';
		} else {
			document.getElementById("phone_ext_error").style.display = \'none\';
			document.getElementById("phone_ext_verified").style.display = \'none\';
		}
        });

        $(\'#firstname\').focusout(function() {
		cidev_check_verified_image_for_field(\'firstname\');
        });

	$(\'#company\').focusout(function() {
		cidev_check_verified_image_for_field(\'company\');
	});
  });
'; ?>

//]]>
</script>
<?php endif; ?>


<?php if ($this->_tpl_vars['is_areas']['C'] == 'Y'):  if ($this->_tpl_vars['hide_header'] == ""): ?>
<tr>
<td height="20" colspan="3"><font class="RegSectionTitle"><?php echo $this->_tpl_vars['lng']['lbl_contact_information']; ?>
</font><hr size="1" noshade="noshade" /></td>
</tr>
<?php endif; ?>


<?php if ($this->_tpl_vars['is_areas']['P'] == 'Y'):  if ($this->_tpl_vars['default_fields']['title']['avail'] == 'Y'): ?>
<tr>
<td valign="top" align="right"><?php echo $this->_tpl_vars['lng']['lbl_title']; ?>
</td>
<td valign="top"><?php if ($this->_tpl_vars['default_fields']['title']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td valign="top" nowrap="nowrap">
<select name="title" id="title">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/title_selector.tpl", 'smarty_include_vars' => array('field' => $this->_tpl_vars['userinfo']['titleid'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</select>
</td>
</tr>
<?php endif;  if ($this->_tpl_vars['default_fields']['firstname']['avail'] == 'Y'): ?>
<tr>
<td class="cidev_padding_top" valign="top" align="right"><?php echo $this->_tpl_vars['lng']['lbl_first_name']; ?>

<?php if ($this->_tpl_vars['usertype'] == 'C' && $this->_tpl_vars['lng']['lbl_CHECKOUT_FIELD_DESCRIPTION_first_name'] != ""): ?><div class="cidev_checkout_descr"><?php echo $this->_tpl_vars['lng']['lbl_CHECKOUT_FIELD_DESCRIPTION_first_name']; ?>
</div><?php endif; ?>
</td>
<td valign="top"><?php if ($this->_tpl_vars['default_fields']['firstname']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td valign="top" nowrap="nowrap">
<table cellpadding="0" cellspacing="0">
<tr>
<td valign="top" nowrap="nowrap">
<input type="text" id="firstname" name="firstname" size="32" maxlength="32" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['userinfo']['firstname'])) ? $this->_run_mod_handler('replace', true, $_tmp, "&amp;#039;", "'") : smarty_modifier_replace($_tmp, "&amp;#039;", "'")); ?>
" placeholder="<?php echo $this->_tpl_vars['lng']['lbl_fill_in_examples_firstname']; ?>
" onkeyup="cidev_check_field_name('firstname')" />
</td>
<?php if ($this->_tpl_vars['usertype'] == 'C'): ?>
<td id="firstname_verified" valign="top" nowrap="nowrap" <?php if ($this->_tpl_vars['userinfo']['firstname'] == ""): ?>style="display: none;"<?php endif; ?>>
<img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/checkmark-verified.png" alt="" />
</td>

<td id="firstname_error" valign="top" nowrap="nowrap" style="display: none;">
<img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/checkmark-error.png" alt="" />
</td>
<?php endif; ?>

</tr>
</table>

<?php if ($this->_tpl_vars['reg_error'] != "" && $this->_tpl_vars['userinfo']['firstname'] == "" && $this->_tpl_vars['default_fields']['firstname']['required'] == 'Y'): ?><font class="Star">&lt;&lt;</font><?php endif; ?>
</td>
</tr>
<?php endif;  if ($this->_tpl_vars['default_fields']['lastname']['avail'] == 'Y'): ?>
<tr>
<td valign="top" align="right"><?php echo $this->_tpl_vars['lng']['lbl_last_name']; ?>
</td>
<td valign="top"><?php if ($this->_tpl_vars['default_fields']['lastname']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td valign="top" nowrap="nowrap">
<input type="text" id="lastname" name="lastname" size="32" maxlength="32" value="<?php echo $this->_tpl_vars['userinfo']['lastname']; ?>
" onkeyup="cidev_check_field_name('lastname')" />
<?php if ($this->_tpl_vars['reg_error'] != "" && $this->_tpl_vars['userinfo']['lastname'] == "" && $this->_tpl_vars['default_fields']['lastname']['required'] == 'Y'): ?><font class="Star">&lt;&lt;</font><?php endif; ?>
</td>
</tr>
<?php endif;  if ($this->_tpl_vars['default_fields']['company']['avail'] == 'Y'): ?>
<tr>
<td valign="top" align="right"><?php echo $this->_tpl_vars['lng']['lbl_company']; ?>
</td>
<td valign="top"><?php if ($this->_tpl_vars['default_fields']['company']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td valign="top" nowrap="nowrap">
<table cellpadding="0" cellspacing="0">
<tr>
<td valign="top" nowrap="nowrap">
<input type="text" id="company" name="company" size="32" maxlength="255" value="<?php echo $this->_tpl_vars['userinfo']['company']; ?>
" placeholder="<?php echo $this->_tpl_vars['lng']['lbl_fill_in_examples_Company_name']; ?>
" onkeyup="cidev_check_field('company')" />
</td>
<?php if ($this->_tpl_vars['usertype'] == 'C'): ?>
<td id="company_verified" valign="top" nowrap="nowrap" <?php if ($this->_tpl_vars['userinfo']['firstname'] == ""): ?>style="display: none;"<?php endif; ?>>
<img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/checkmark-verified.png" alt="" />
</td>

<td id="company_error" valign="top" nowrap="nowrap" <?php if ($this->_tpl_vars['userinfo']['company'] == ""): ?>style="display: none;"<?php endif; ?>>
<img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/checkmark-error.png" alt="" />
</td>
<?php endif; ?>

</tr>
</table>

<?php if ($this->_tpl_vars['reg_error'] != "" && $this->_tpl_vars['userinfo']['company'] == "" && $this->_tpl_vars['default_fields']['company']['required'] == 'Y'): ?><font class="Star">&lt;&lt;</font><?php endif; ?>
</td>
</tr>
<?php endif;  if ($this->_tpl_vars['default_fields']['ssn']['avail'] == 'Y'): ?>
<tr>
<td valign="top" align="right"><?php echo $this->_tpl_vars['lng']['lbl_ssn']; ?>
</td>
<td valign="top"><?php if ($this->_tpl_vars['default_fields']['ssn']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td valign="top" nowrap="nowrap">
<input type="text" id="ssn" name="ssn" size="32" maxlength="32" value="<?php echo $this->_tpl_vars['userinfo']['ssn']; ?>
" onkeyup="cidev_check_field('ssn')" />
<?php if ($this->_tpl_vars['reg_error'] != "" && $this->_tpl_vars['userinfo']['ssn'] == "" && $this->_tpl_vars['default_fields']['ssn']['required'] == 'Y'): ?><font class="Star">&lt;&lt;</font><?php endif; ?>
</td>
</tr>
<?php endif;  if ($this->_tpl_vars['default_fields']['tax_number']['avail'] == 'Y'): ?>
<tr>
<td valign="top" align="right"><?php echo $this->_tpl_vars['lng']['lbl_tax_number']; ?>
</td>
<td valign="top"><?php if ($this->_tpl_vars['default_fields']['tax_number']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td valign="top" nowrap="nowrap">
<?php if ($this->_tpl_vars['userinfo']['tax_exempt'] != 'Y' || $this->_tpl_vars['config']['Taxes']['allow_user_modify_tax_number'] == 'Y' || $this->_tpl_vars['usertype'] == 'A' || $this->_tpl_vars['usertype'] == 'P'): ?>
<input type="text" id="tax_number" name="tax_number" size="32" maxlength="32" value="<?php echo $this->_tpl_vars['userinfo']['tax_number']; ?>
" onkeyup="cidev_check_field('tax_number')" />
<?php if ($this->_tpl_vars['reg_error'] != "" && $this->_tpl_vars['userinfo']['tax_number'] == "" && $this->_tpl_vars['default_fields']['tax_number']['required'] == 'Y'): ?><font class="Star">&lt;&lt;</font><?php endif;  else:  echo $this->_tpl_vars['userinfo']['tax_number']; ?>

<?php endif; ?>
</td>
</tr>
<?php endif;  if ($this->_tpl_vars['config']['Taxes']['enable_user_tax_exemption'] == 'Y'):  if (( ( $this->_tpl_vars['userinfo']['usertype'] == 'C' || $GLOBALS['_GET']['usertype'] == 'C' ) && $this->_tpl_vars['userinfo']['tax_exempt'] == 'Y' ) || ( $this->_tpl_vars['usertype'] == 'A' || $this->_tpl_vars['usertype'] == 'P' )): ?>
<tr>
<td valign="top" align="right"><?php echo $this->_tpl_vars['lng']['lbl_tax_exemption']; ?>
</td>
<td valign="top">&nbsp;</td>
<td valign="top" nowrap="nowrap">
<?php if ($this->_tpl_vars['usertype'] == 'A' || $this->_tpl_vars['usertype'] == 'P'): ?> 
<input type="checkbox" id="tax_exempt" name="tax_exempt" value="Y"<?php if ($this->_tpl_vars['userinfo']['tax_exempt'] == 'Y'): ?> checked="checked"<?php endif; ?> />
<?php elseif ($this->_tpl_vars['userinfo']['tax_exempt'] == 'Y'):  echo $this->_tpl_vars['lng']['txt_tax_exemption_assigned']; ?>

<?php endif; ?>
</td>
</tr>
<?php endif;  endif;  if ($this->_tpl_vars['usertype'] == 'A' || $this->_tpl_vars['usertype'] == 'P'): ?>
<tr>
<td valign="top" align="right"><?php echo $this->_tpl_vars['lng']['lbl_referred_by']; ?>
</td>
<td valign="top"></td>
<td nowrap="nowrap">
<?php if ($this->_tpl_vars['userinfo']['referer']): ?>
<a href="<?php echo $this->_tpl_vars['userinfo']['referer']; ?>
"><?php echo $this->_tpl_vars['userinfo']['referer']; ?>
</a>
<?php else:  echo $this->_tpl_vars['lng']['lbl_unknown']; ?>

<?php endif; ?>
</td>
</tr>
<?php endif;  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/register_additional_info.tpl", 'smarty_include_vars' => array('section' => 'P')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

<?php if ($this->_tpl_vars['default_fields']['phone']['avail'] == 'Y'): ?>
<tr>
<td class="cidev_padding_top" valign="top" align="right"><?php echo $this->_tpl_vars['lng']['lbl_phone']; ?>

<?php if ($this->_tpl_vars['usertype'] == 'C'): ?><div class="cidev_checkout_descr"><?php echo $this->_tpl_vars['lng']['lbl_CHECKOUT_FIELD_DESCRIPTION_phone']; ?>
</div><?php endif; ?>
</td>
<td valign="top"><?php if ($this->_tpl_vars['default_fields']['phone']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td valign="top" nowrap="nowrap">
<table cellpadding="0" cellspacing="0">
<tr>
<td valign="top" nowrap="nowrap">
<input type="text" id="phone" name="phone" size="32" maxlength="32" value="<?php if ($this->_tpl_vars['userinfo']['phone'] == "" && ( $this->_tpl_vars['new_login_type'] == 'P' || $this->_tpl_vars['new_login_type'] == 'A' ) && $this->_tpl_vars['main'] == 'user_add'):  echo $this->_tpl_vars['config']['Company']['company_phone_2'];  else:  echo $this->_tpl_vars['userinfo']['phone'];  endif; ?>" placeholder="<?php echo $this->_tpl_vars['lng']['lbl_fill_in_examples_phone']; ?>
" onkeyup="cidev_check_field_phone('phone')" />
</td>

<?php if ($this->_tpl_vars['usertype'] == 'C'): ?>
<td width="25">
<table cellpadding="0" cellspacing="0">
<tr>
<td id="phone_verified" valign="top" nowrap="nowrap" <?php if ($this->_tpl_vars['userinfo']['phone'] == ""): ?>style="display: none;"<?php endif; ?>>
<img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/checkmark-verified.png" alt="" />
</td>
<td id="phone_error" valign="top" nowrap="nowrap" style="display: none;">
<img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/checkmark-error.png" alt="" />
</td>
</tr>
</table>
</td>
<?php endif; ?>

<td class="cidev_padding_top" valign="top" align="right"><?php echo $this->_tpl_vars['lng']['lbl_phone_ext']; ?>

<?php if ($this->_tpl_vars['usertype'] == 'C'): ?><div class="cidev_checkout_descr"><?php echo $this->_tpl_vars['lng']['lbl_CHECKOUT_FIELD_DESCRIPTION_phone_ext']; ?>
</div><?php endif; ?>
</td>
<td valign="top"><?php if ($this->_tpl_vars['default_fields']['phone_ext']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td valign="top" nowrap="nowrap" width="40px">
<input type="text" id="phone_ext" name="phone_ext" size="6" maxlength="6" value="<?php echo $this->_tpl_vars['userinfo']['phone_ext']; ?>
" placeholder="<?php echo $this->_tpl_vars['lng']['lbl_fill_in_examples_phone_ext']; ?>
" onkeyup="cidev_check_field_phone_ext('phone_ext')" />
</td>

<?php if ($this->_tpl_vars['usertype'] == 'C'): ?>
<td id="phone_ext_verified" valign="top" nowrap="nowrap" <?php if ($this->_tpl_vars['userinfo']['phone_ext'] == ""): ?>style="display: none;"<?php endif; ?>>
<img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/checkmark-verified.png" alt="" />
</td>
<td id="phone_ext_error" valign="top" nowrap="nowrap" style="display: none;">
<img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/checkmark-error.png" alt="" />
</td>
<?php endif; ?>

</tr>
</table>

<?php if ($this->_tpl_vars['reg_error'] != "" && $this->_tpl_vars['userinfo']['phone'] == "" && $this->_tpl_vars['default_fields']['phone']['required'] == 'Y'): ?><font class="Star">&lt;&lt;</font><?php endif; ?>
</td>
</tr>

<?php endif;  if ($this->_tpl_vars['default_fields']['email']['avail'] == 'Y'): ?>
<tr>
<td valign="top" align="right" class="cidev_padding_top"><?php echo $this->_tpl_vars['lng']['lbl_email']; ?>

<?php if ($this->_tpl_vars['usertype'] == 'C'): ?><div class="cidev_checkout_descr"><?php echo $this->_tpl_vars['lng']['lbl_CHECKOUT_FIELD_DESCRIPTION_email']; ?>
</div><?php endif; ?>
</td>
<td valign="top"><?php if ($this->_tpl_vars['default_fields']['email']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td valign="top" nowrap="nowrap">
<table cellpadding="0" cellspacing="0">
<tr>
<td valign="top" nowrap="nowrap">
<input type="text" id="email" name="email" size="32" maxlength="128" value="<?php if ($this->_tpl_vars['userinfo']['email'] == "" && ( $this->_tpl_vars['new_login_type'] == 'P' || $this->_tpl_vars['new_login_type'] == 'A' ) && $this->_tpl_vars['main'] == 'user_add'):  echo $this->_tpl_vars['config']['Company']['users_department'];  else:  echo $this->_tpl_vars['userinfo']['email'];  endif; ?>" placeholder="<?php echo $this->_tpl_vars['lng']['lbl_fill_in_examples_email']; ?>
"  />
</td>

<?php if ($this->_tpl_vars['usertype'] == 'C'): ?>
<td id="email_verified" valign="top" nowrap="nowrap" <?php if ($this->_tpl_vars['userinfo']['email'] == ""): ?>style="display: none;"<?php endif; ?>>
<img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/checkmark-verified.png" alt="" />
</td>

<td id="email_error" valign="top" nowrap="nowrap" style="display: none;">
<img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/checkmark-error.png" alt="" />
</td>
<td id="email_error_text" valign="top" style="display: none;">
<div id="email_note" class="cidev_NoteBox"><?php echo $this->_tpl_vars['lng']['txt_email_invalid']; ?>
</div>
</td>
<?php endif; ?>

</tr>
</table>

</td>
</tr>
<?php endif;  if ($this->_tpl_vars['default_fields']['fax']['avail'] == 'Y'): ?>
<tr>
<td valign="top" align="right"><?php echo $this->_tpl_vars['lng']['lbl_fax']; ?>
</td>
<td valign="top"><?php if ($this->_tpl_vars['default_fields']['fax']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td valign="top" nowrap="nowrap">
<input type="text" id="fax" name="fax" size="32" maxlength="128" value="<?php echo $this->_tpl_vars['userinfo']['fax']; ?>
" />
<?php if ($this->_tpl_vars['reg_error'] != "" && $this->_tpl_vars['userinfo']['fax'] == "" && $this->_tpl_vars['default_fields']['fax']['required'] == 'Y'): ?><font class="Star">&lt;&lt;</font><?php endif; ?>
</td>
</tr>
<?php endif;  if ($this->_tpl_vars['default_fields']['url']['avail'] == 'Y'): ?>
<tr>
<td valign="top" align="right"><?php echo $this->_tpl_vars['lng']['lbl_web_site']; ?>
</td>
<td valign="top"><?php if ($this->_tpl_vars['default_fields']['url']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td valign="top" nowrap="nowrap">
<input type="text" id="url" name="url" size="32" maxlength="128" value="<?php echo $this->_tpl_vars['userinfo']['url']; ?>
" />
<?php if ($this->_tpl_vars['reg_error'] != "" && $this->_tpl_vars['userinfo']['url'] == "" && $this->_tpl_vars['default_fields']['url']['required'] == 'Y'): ?><font class="Star">&lt;&lt;</font><?php endif; ?>
</td>
</tr>
<?php endif;  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/register_additional_info.tpl", 'smarty_include_vars' => array('section' => 'C')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>



<?php if ($this->_tpl_vars['default_fields']['pbx_extension']['avail'] == 'Y'): ?>
<tr>
<td valign="top" align="right">PBX extension</td>
<td valign="top"><?php if ($this->_tpl_vars['default_fields']['pbx_extension']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td valign="top" nowrap="nowrap">
<input type="text" id="pbx_extension" name="pbx_extension" size="32" maxlength="128" value="<?php echo $this->_tpl_vars['userinfo']['pbx_extension']; ?>
" <?php if ($this->_tpl_vars['membership_code'] != ""): ?>readonly="readonly"<?php endif; ?> />
<?php if ($this->_tpl_vars['reg_error'] != "" && $this->_tpl_vars['userinfo']['pbx_extension'] == "" && $this->_tpl_vars['default_fields']['pbx_extension']['required'] == 'Y'): ?><font class="Star">&lt;&lt;</font><?php endif; ?>
</td>
</tr>
<?php endif; ?>


<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "main/register_contact_info.tpl"), $this); endif; ?>
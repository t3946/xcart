<?php /* Smarty version 2.6.12, created on 2011-10-11 06:21:51
         compiled from main/register_contact_info.tpl */ ?>
<?php func_load_lang($this, "main/register_contact_info.tpl","lbl_contact_information,txt_newbie_registration_bottom_small,lbl_title,lbl_first_name,lbl_last_name,lbl_company,lbl_ssn,lbl_tax_number,lbl_tax_exemption,txt_tax_exemption_assigned,lbl_referred_by,lbl_unknown,lbl_phone,lbl_email,lbl_fax,lbl_web_site"); ?><?php if ($this->_tpl_vars['is_areas']['C'] == 'Y'):  if ($this->_tpl_vars['hide_header'] == ""): ?>
<tr>
<td height="20" colspan="3"><font class="RegSectionTitle"><?php echo $this->_tpl_vars['lng']['lbl_contact_information']; ?>
</font><hr size="1" noshade="noshade" /></td>
</tr>
<?php endif; ?>

<tr>
<td colspan="3"><?php echo $this->_tpl_vars['lng']['txt_newbie_registration_bottom_small']; ?>
</td>
</tr>

<?php if ($this->_tpl_vars['is_areas']['P'] == 'Y'):  if ($this->_tpl_vars['default_fields']['title']['avail'] == 'Y'): ?>
<tr>
<td align="right"><?php echo $this->_tpl_vars['lng']['lbl_title']; ?>
</td>
<td><?php if ($this->_tpl_vars['default_fields']['title']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td nowrap="nowrap">
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
<td align="right"><?php echo $this->_tpl_vars['lng']['lbl_first_name']; ?>
</td>
<td><?php if ($this->_tpl_vars['default_fields']['firstname']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td nowrap="nowrap">
<input type="text" id="firstname" name="firstname" size="32" maxlength="32" value="<?php echo $this->_tpl_vars['userinfo']['firstname']; ?>
" />
<?php if ($this->_tpl_vars['reg_error'] != "" && $this->_tpl_vars['userinfo']['firstname'] == "" && $this->_tpl_vars['default_fields']['firstname']['required'] == 'Y'): ?><font class="Star">&lt;&lt;</font><?php endif; ?>
</td>
</tr>
<?php endif;  if ($this->_tpl_vars['default_fields']['lastname']['avail'] == 'Y'): ?>
<tr>
<td align="right"><?php echo $this->_tpl_vars['lng']['lbl_last_name']; ?>
</td>
<td><?php if ($this->_tpl_vars['default_fields']['lastname']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td nowrap="nowrap">
<input type="text" id="lastname" name="lastname" size="32" maxlength="32" value="<?php echo $this->_tpl_vars['userinfo']['lastname']; ?>
" />
<?php if ($this->_tpl_vars['reg_error'] != "" && $this->_tpl_vars['userinfo']['lastname'] == "" && $this->_tpl_vars['default_fields']['lastname']['required'] == 'Y'): ?><font class="Star">&lt;&lt;</font><?php endif; ?>
</td>
</tr>
<?php endif;  if ($this->_tpl_vars['default_fields']['company']['avail'] == 'Y'): ?>
<tr>
<td align="right"><?php echo $this->_tpl_vars['lng']['lbl_company']; ?>
</td>
<td><?php if ($this->_tpl_vars['default_fields']['company']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td nowrap="nowrap">
<input type="text" id="company" name="company" size="32" maxlength="255" value="<?php echo $this->_tpl_vars['userinfo']['company']; ?>
" />
<?php if ($this->_tpl_vars['reg_error'] != "" && $this->_tpl_vars['userinfo']['company'] == "" && $this->_tpl_vars['default_fields']['company']['required'] == 'Y'): ?><font class="Star">&lt;&lt;</font><?php endif; ?>
</td>
</tr>
<?php endif;  if ($this->_tpl_vars['default_fields']['ssn']['avail'] == 'Y'): ?>
<tr>
<td align="right"><?php echo $this->_tpl_vars['lng']['lbl_ssn']; ?>
</td>
<td><?php if ($this->_tpl_vars['default_fields']['ssn']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td nowrap="nowrap">
<input type="text" id="ssn" name="ssn" size="32" maxlength="32" value="<?php echo $this->_tpl_vars['userinfo']['ssn']; ?>
" />
<?php if ($this->_tpl_vars['reg_error'] != "" && $this->_tpl_vars['userinfo']['ssn'] == "" && $this->_tpl_vars['default_fields']['ssn']['required'] == 'Y'): ?><font class="Star">&lt;&lt;</font><?php endif; ?>
</td>
</tr>
<?php endif;  if ($this->_tpl_vars['default_fields']['tax_number']['avail'] == 'Y'): ?>
<tr>
<td align="right"><?php echo $this->_tpl_vars['lng']['lbl_tax_number']; ?>
</td>
<td><?php if ($this->_tpl_vars['default_fields']['tax_number']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td nowrap="nowrap">
<?php if ($this->_tpl_vars['userinfo']['tax_exempt'] != 'Y' || $this->_tpl_vars['config']['Taxes']['allow_user_modify_tax_number'] == 'Y' || $this->_tpl_vars['usertype'] == 'A' || $this->_tpl_vars['usertype'] == 'P'): ?>
<input type="text" id="tax_number" name="tax_number" size="32" maxlength="32" value="<?php echo $this->_tpl_vars['userinfo']['tax_number']; ?>
" />
<?php if ($this->_tpl_vars['reg_error'] != "" && $this->_tpl_vars['userinfo']['tax_number'] == "" && $this->_tpl_vars['default_fields']['tax_number']['required'] == 'Y'): ?><font class="Star">&lt;&lt;</font><?php endif;  else:  echo $this->_tpl_vars['userinfo']['tax_number']; ?>

<?php endif; ?>
</td>
</tr>
<?php endif;  if ($this->_tpl_vars['config']['Taxes']['enable_user_tax_exemption'] == 'Y'):  if (( ( $this->_tpl_vars['userinfo']['usertype'] == 'C' || $GLOBALS['HTTP_GET_VARS']['usertype'] == 'C' ) && $this->_tpl_vars['userinfo']['tax_exempt'] == 'Y' ) || ( $this->_tpl_vars['usertype'] == 'A' || $this->_tpl_vars['usertype'] == 'P' )): ?>
<tr>
<td align="right"><?php echo $this->_tpl_vars['lng']['lbl_tax_exemption']; ?>
</td>
<td>&nbsp;</td>
<td nowrap="nowrap">
<?php if ($this->_tpl_vars['usertype'] == 'A' || $this->_tpl_vars['usertype'] == 'P'): ?> 
<input type="checkbox" id="tax_exempt" name="tax_exempt" value="Y"<?php if ($this->_tpl_vars['userinfo']['tax_exempt'] == 'Y'): ?> checked="checked"<?php endif; ?> />
<?php elseif ($this->_tpl_vars['userinfo']['tax_exempt'] == 'Y'):  echo $this->_tpl_vars['lng']['txt_tax_exemption_assigned']; ?>

<?php endif; ?>
</td>
</tr>
<?php endif;  endif;  if ($this->_tpl_vars['usertype'] == 'A' || $this->_tpl_vars['usertype'] == 'P'): ?>
<tr>
<td align="right"><?php echo $this->_tpl_vars['lng']['lbl_referred_by']; ?>
</td>
<td></td>
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
<td align="right"><?php echo $this->_tpl_vars['lng']['lbl_phone']; ?>
</td>
<td><?php if ($this->_tpl_vars['default_fields']['phone']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td nowrap="nowrap">
<input type="text" id="phone" name="phone" size="32" maxlength="32" value="<?php echo $this->_tpl_vars['userinfo']['phone']; ?>
" />
<?php if ($this->_tpl_vars['reg_error'] != "" && $this->_tpl_vars['userinfo']['phone'] == "" && $this->_tpl_vars['default_fields']['phone']['required'] == 'Y'): ?><font class="Star">&lt;&lt;</font><?php endif; ?>
</td>
</tr>
<?php endif;  if ($this->_tpl_vars['default_fields']['email']['avail'] == 'Y'): ?>
<tr>
<td align="right"><?php echo $this->_tpl_vars['lng']['lbl_email']; ?>
</td>
<td><?php if ($this->_tpl_vars['default_fields']['email']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td nowrap="nowrap">
<input type="text" id="email" name="email" size="32" maxlength="128" value="<?php echo $this->_tpl_vars['userinfo']['email']; ?>
" />
<?php if ($this->_tpl_vars['emailerror'] != "" || ( $this->_tpl_vars['reg_error'] != "" && $this->_tpl_vars['userinfo']['email'] == "" && $this->_tpl_vars['default_fields']['email']['required'] == 'Y' )): ?><font class="Star">&lt;&lt;</font><?php endif; ?>
</td>
</tr>
<?php endif;  if ($this->_tpl_vars['default_fields']['fax']['avail'] == 'Y'): ?>
<tr>
<td align="right"><?php echo $this->_tpl_vars['lng']['lbl_fax']; ?>
</td>
<td><?php if ($this->_tpl_vars['default_fields']['fax']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td nowrap="nowrap">
<input type="text" id="fax" name="fax" size="32" maxlength="128" value="<?php echo $this->_tpl_vars['userinfo']['fax']; ?>
" />
<?php if ($this->_tpl_vars['reg_error'] != "" && $this->_tpl_vars['userinfo']['fax'] == "" && $this->_tpl_vars['default_fields']['fax']['required'] == 'Y'): ?><font class="Star">&lt;&lt;</font><?php endif; ?>
</td>
</tr>
<?php endif;  if ($this->_tpl_vars['default_fields']['url']['avail'] == 'Y'): ?>
<tr>
<td align="right"><?php echo $this->_tpl_vars['lng']['lbl_web_site']; ?>
</td>
<td><?php if ($this->_tpl_vars['default_fields']['url']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td nowrap="nowrap">
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

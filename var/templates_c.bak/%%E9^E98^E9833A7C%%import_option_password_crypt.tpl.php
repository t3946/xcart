<?php /* Smarty version 2.6.12, created on 2011-10-11 05:43:52
         compiled from main/import_option_password_crypt.tpl */ ?>
<?php func_load_lang($this, "main/import_option_password_crypt.tpl","lbl_imported_customer_passwords_encrypted,lbl_yes,lbl_no"); ?><table cellpadding="1" cellspacing="1" width="100%">
<tr>
	<td><b><?php echo $this->_tpl_vars['lng']['lbl_imported_customer_passwords_encrypted']; ?>
:</b></td>
</tr>
<tr>
	<td><select name="options[crypt_password]">
	<option value="Y"<?php if ($this->_tpl_vars['import_data']['options']['crypt_password'] == 'Y' || $this->_tpl_vars['import_data'] == ''): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_yes']; ?>
</option>
	<option value=""<?php if ($this->_tpl_vars['import_data']['options']['crypt_password'] == '' && $this->_tpl_vars['import_data'] != ''): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_no']; ?>
</option>
	</select ></td>
</tr>
</table>
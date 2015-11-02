{*
$Id: osn_settings.tpl, v 1.0.0 2011/10/18 13:54:21 kate Exp $
vim: set ts=2 sw=2 sts=2 et:
*}

<tr class="VariableSettings">
  <td>{$lng.lbl_subject_line_customer}</td>
  <td><input type="text" name="update[customer_subject]" value="{$osn_settings.customer_subject}" size="110" /></td>
</tr>

<tr class="VariableSettings">
  <td>{$lng.lbl_subject_line_us}</td>
  <td><input type="text" name="update[copy_subject]" value="{$osn_settings.copy_subject}" size="110" /></td>
</tr>

<tr class="VariableSettings">
  <td>{$lng.lbl_body}</td>
  <td>{include file="main/textarea.tpl" name="update[email_body]" cols=45 rows=24 data=$osn_settings.email_body width="80%" btn_rows=4}</td>
</tr>

<tr class="VariableSettings">
  <td>{$lng.lbl_turn_on_off_this_notifications}</td>
  <td><input type="checkbox" name="update[enabled]" value="Y"{if $osn_settings.enabled eq 'Y'} checked="checked"{/if} /></td>
</tr>

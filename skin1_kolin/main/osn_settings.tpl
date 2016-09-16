{*
$Id: osn_settings.tpl, v 1.0.0 2011/10/18 13:54:21 kate Exp $
vim: set ts=2 sw=2 sts=2 et:
*}


{if $aOrderNotifications}
{foreach from=$aOrderNotifications item=oOrderNotification}
<tr class="VariableSettings">
  <td><B>{$lng.lbl_body}</B></td>
  <td>
{assign var=osn_settings value=$oOrderNotification->getFields()}


<textarea rows="24" cols="45" name="update[email_body]" style="width: 80%;" class="new_editor">{$osn_settings.email_body|replace:"\n":"<br />"}</textarea>

{*
<textarea rows="24" cols="45" name="update[email_body]" style="width: 80%;" class="new_editor">{$osn_settings.email_body}</textarea>
*}

 </td>
</tr>

<tr class="VariableSettings">
  <td><B>{$lng.lbl_turn_on_off_this_notifications}</B></td>
  <td><input type="checkbox" name="update[enabled]" value="Y"{if $osn_settings.enabled eq 'Y'} checked="checked"{/if} /></td>
</tr>



<tr class="VariableSettings"><td colspan="2"><br />
{include file="main/subheader.tpl" title="Email to customer"}
</td></tr>

<tr class="VariableSettings">
  <td><B>{$lng.lbl_subject_line_customer}</B></td>
  <td><B><input type="text" name="update[customer_subject]" value="{$osn_settings.customer_subject}" size="110" /></B></td>
</tr>

<tr class="VariableSettings">
  <td><B>Attach PDF invoice</B></td>
  <td><input type="checkbox" name="update[customer_attach_pdf_invoice]" value="Y"{if $osn_settings.customer_attach_pdf_invoice eq 'Y'} checked="checked"{/if} /></td>
</tr>



<tr class="VariableSettings"><td colspan="2"><br />
{include file="main/subheader.tpl" title="Email-copy to us"}
</td></tr>

<tr class="VariableSettings">
  <td><B>{$lng.lbl_subject_line_us}</B></td>
  <td><input type="text" name="update[copy_subject]" value="{$osn_settings.copy_subject}" size="110" /></td>
</tr>

<tr class="VariableSettings">
  <td><B>Attach PDF invoice</B></td>
  <td><input type="checkbox" name="update[admin_attach_pdf_invoice]" value="Y"{if $osn_settings.admin_attach_pdf_invoice eq 'Y'} checked="checked"{/if} /></td>
</tr>
{/foreach}
{/if}
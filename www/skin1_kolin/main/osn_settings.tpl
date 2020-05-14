
{if $aOrderNotifications}
{foreach from=$aOrderNotifications item=oOrderNotification}
  <tr class="VariableSettings">
    <td><B>{$lng.lbl_body}</B></td>
    <td>
      {assign var=osn_settings value=$oOrderNotification->getFields()}
      <textarea rows="24" cols="45" name="update[email_body][{$osn_settings.number}]" style="width: 80%;"
                class="new_editor">{$osn_settings.email_body}</textarea>
    </td>
  </tr>
  <tr class="VariableSettings">
    <td><B>{$lng.lbl_turn_on_off_this_notifications}</B></td>
    <td>
      <input type="checkbox" name="update[enabled][{$osn_settings.number}]"
               value="Y"{if $osn_settings.enabled eq 'Y'} checked="checked"{/if} />
    </td>
  </tr>
  <tr class="VariableSettings">
    <td colspan="2"><br/>
      {include file="main/subheader.tpl" title="Email to customer"}
    </td>
  </tr>
  <tr class="VariableSettings">
    <td><B>{$lng.lbl_subject_line_customer}</B></td>
    <td>
      <B><input type="text" name="update[customer_subject][{$osn_settings.number}]"
                  value="{$osn_settings.customer_subject}" size="110"/></B>
    </td>
  </tr>
  <tr class="VariableSettings">
    <td><B>Attach PDF invoice</B></td>
    <td>
      <input type="checkbox" name="update[customer_attach_pdf_invoice][{$osn_settings.number}]"
               value="Y"{if $osn_settings.customer_attach_pdf_invoice eq 'Y'} checked="checked"{/if} />
    </td>
  </tr>
  <tr class="VariableSettings">
    <td colspan="2"><br/>
      {include file="main/subheader.tpl" title="Email-copy to us"}
    </td>
  </tr>
  <tr class="VariableSettings">
    <td><B>{$lng.lbl_subject_line_us}</B></td>
    <td>
      <input type="text" name="update[copy_subject][{$osn_settings.number}]" value="{$osn_settings.copy_subject}"
               size="110"/>
    </td>
  </tr>
  <tr class="VariableSettings">
    <td><B>Attach PDF invoice</B></td>
    <td>
      <input type="checkbox" name="update[admin_attach_pdf_invoice][{$osn_settings.number}]"
               value="Y"{if $osn_settings.admin_attach_pdf_invoice eq 'Y'} checked="checked"{/if} />
    </td>
  </tr>
{/foreach}
{/if}
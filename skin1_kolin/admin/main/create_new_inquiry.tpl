
{include file="page_title.tpl" title="Create new inquiry"}
{capture name=dialog}
<form name="inquiry_typeform1" action="create_new_inquiry.php" method="POST">
    <input type="hidden" name="mode" value="add_inquiry">

<table cellpadding="3" cellspacing="1" width="100%">
 <tr>
  <td width="20%" nowrap="nowrap"><B>Inquiry type:</B></td>
  <td>
        <select name="add_inq_type_id">
                <option value="">Select</option>
                {if $inquiry_types ne ""}
                {foreach from=$inquiry_types item=v key=k}
                <option value="{$v.inq_type_id}">{$v.inquiry_type}</option>
                {/foreach}
                {/if}
        </select>
  </td>
 </tr>
 <tr>
  <td width="20%" nowrap="nowrap"><B>Subject line:</B></td>
  <td>
<input type="text" name="add_inq_email_subject" value="" style="width: 98%;" />
  </td>
 </tr>
 <tr>
  <td width="*" nowrap="nowrap"><B>Message body:</B></td>
  <td align="center">
        <textarea class="new_editor" name="add_inq_subject" value="" style="width: 80%;" cols="60" rows="20" maxlength="500" /></textarea>
  </td>
</tr>

{if $inquiry_attn_tags ne ""}
 <tr>
 <td width="20%" nowrap="nowrap"><B>Inquiry tags:</B></td>
 <td>
{assign var="td_counter" value="0"}
<table>
{foreach from=$inquiry_attn_tags item=v key=k}
{if $td_counter eq "0"}
<tr>
{/if}
{math assign="td_counter" equation="x+1" x=$td_counter}

<td nowrap="nowrap">
<input type="checkbox" name="add_inq_tag_id[{$v.inq_tag_id}]" value="Y" />{$v.inquiry_attn_tag} &nbsp;
</td>

{if $td_counter eq "5"}
</tr>
{assign var="td_counter" value="0"}
{/if}
{/foreach}

{if $td_counter lt "5"}
{math assign="td_colspan" equation="5-x" x=$td_counter}
<td {if $td_colspan gt 1}colspan="{$td_colspan}"{/if}>&nbsp;</td>
</tr>
{/if}

</table>
 </td>
 </tr>
{/if}

<tr>
<td colspan="2" align="center">
<input type="submit" name="Create new inquiry" value="Create new inquiry" />
</td>
</tr>
</table>
</form>
{/capture}
{include file="dialog.tpl" title="Create new inquiry" content=$smarty.capture.dialog extra='width="100%"'}

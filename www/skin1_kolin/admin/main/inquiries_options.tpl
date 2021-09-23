
<B>Inquiry types</B>
<hr>
<br />

<form name="inquiry_typeform1" action="configuration.php" method="POST">
    <input type="hidden" name="option" value="Inquiries_options">
    <input type="hidden" name="mode" value="add_inquiry_type">

<table cellpadding="3" cellspacing="1">
 <tr>
  <td width="20" nowrap="nowrap">inquiry_type:</td>
  <td width="400">
<input type="text" name="add_inquiry_type" value="" style="width: 95%;" />
  </td>
  <td width="20">active:</td>
  <td width="20"><input type="checkbox" name="add_active" value="Y" /></td>
</tr>
</table>

<input type="submit" name="Add" value="Add" />
</form>

<br />
<hr />
<br />

{if $inquiry_types ne ""}
<form name="inquiry_typeform2" action="configuration.php" method="POST">
    <input type="hidden" name="option" value="Inquiries_options">
    <input type="hidden" name="mode" value="update_inquiry_type">

<table cellpadding="3" cellspacing="1">
<tr class='TableSubHead'>
<td>type_position</td>
<td>inquiry_type</td>
<td>active</td>
<td>delete</td>
</tr>

{foreach from=$inquiry_types item=v key=k}
 <tr {cycle values=", class='TableSubHead'"}>
  <td width="40">
<input type="text" name="post_data[{$v.inq_type_id}][type_position]" value="{$v.type_position}" style="width: 95%;" />
  </td>
  <td width="400">
<input type="text" name="post_data[{$v.inq_type_id}][inquiry_type]" value="{$v.inquiry_type}" style="width: 95%;" />
  </td>
  <td>
<input type="checkbox" name="post_data[{$v.inq_type_id}][active]" {if $v.active eq "Y"}checked="ckecked"{/if} value="Y"/>
  </td>
  <td>
<input type="checkbox" name="post_data[{$v.inq_type_id}][delete]" value="Y" />
  </td>
</tr>
{/foreach}
</table>

<input type="submit" name="Submit" value="Submit" />
</form>
{/if}

<br />
<br />
<br />
<B>Inquiries Attention tags</B>
<hr />
<br />

<form name="inquiry_attn_tagform1" action="configuration.php" method="POST">
    <input type="hidden" name="option" value="Inquiries_options">
    <input type="hidden" name="mode" value="add_inquiry_attn_tag">

<table cellpadding="3" cellspacing="1">
 <tr>
  <td width="20" nowrap="nowrap">inquiry_attn_tag:</td>
  <td width="400">
<input type="text" name="add_inquiry_attn_tag" value="" style="width: 95%;" />
  </td>
  <td width="20">active:</td>
  <td width="20"><input type="checkbox" name="add_active" value="Y" /></td>
</tr>
</table>

<input type="submit" name="Add" value="Add" />
</form>

<br />
<hr />
<br />

{if $inquiry_attn_tags ne ""}
<form name="inquiry_attn_tagform2" action="configuration.php" method="POST">
    <input type="hidden" name="option" value="Inquiries_options">
    <input type="hidden" name="mode" value="update_inquiry_attn_tag">

<table cellpadding="3" cellspacing="1">
<tr class='TableSubHead'>
<td>tag_position</td>
<td>inquiry_attn_tag</td>
<td>active</td>
<td>delete</td>
</tr>

{foreach from=$inquiry_attn_tags item=v key=k}
 <tr {cycle values=", class='TableSubHead'"}>
  <td width="40">
<input type="text" name="post_data[{$v.inq_tag_id}][tag_position]" value="{$v.tag_position}" style="width: 95%;" />
  </td>
  <td width="400">
<input type="text" name="post_data[{$v.inq_tag_id}][inquiry_attn_tag]" value="{$v.inquiry_attn_tag}" style="width: 95%;" />
  </td>
  <td>
<input type="checkbox" name="post_data[{$v.inq_tag_id}][active]" {if $v.active eq "Y"}checked="ckecked"{/if} value="Y"/>
  </td>
  <td>
<input type="checkbox" name="post_data[{$v.inq_tag_id}][delete]" value="Y" />
  </td>
</tr>
{/foreach}
</table>

<input type="submit" name="Submit" value="Submit" />
</form>
{/if}


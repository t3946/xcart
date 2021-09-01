
<form name="osnotificform1" action="configuration.php" method="POST">
    <input type="hidden" name="option" value="Order_page_permissions">
    <input type="hidden" name="mode" value="add">

<table cellpadding="3" cellspacing="1" width="100%">

 <tr>
  <td width="20%">element_id:</td>
  <td>
<input type="text" name="add_element_id" value="" style="width: 55%;" />
  </td>
 </tr>

 <tr>
  <td width="20%">Memberships:</td>
  <td>
{foreach from=$all_memberships item=item key=key}
<input type="checkbox" name="add_membershipid[{$item.membershipid}]" value="Y" /> {if $item.area eq "A"}Admin{elseif $item.area eq "P"}Provider{/if}/{$item.membership}</br />
{/foreach}
  </td>
</tr>

</table>
<input type="submit" name="Add" value="Add" />
</form>

<br />
<hr />
<br />

{if $order_page_permissions ne ""}
<form name="osnotificform2" action="configuration.php" method="POST">
    <input type="hidden" name="option" value="Order_page_permissions">
    <input type="hidden" name="mode" value="update">

<table cellpadding="3" cellspacing="1" width="100%">

<tr class='TableSubHead'>
<td>element_id</td>
<td>membership_ids</td>
<td>delete</td>
</tr>

{foreach from=$order_page_permissions item=v key=k}
 <tr {cycle values=", class='TableSubHead'"}>
  <td width="48%">
<input type="text" name="post_data[{$v.id}][element_id]" value="{$v.element_id}" style="width: 95%;" />
  </td>
  <td>
	{foreach from=$all_memberships item=item key=key}
<input type="checkbox" name="post_data[{$v.id}][membershipid][{$item.membershipid}]"
{if $v.membership_ids_arr ne ""}
{foreach from=$v.membership_ids_arr item=vv key=kk}
{if $item.membershipid eq $vv} 
checked="ckecked"
{/if}
{/foreach}
{/if}
value="Y"/> {if $item.area eq "A"}Admin{elseif $item.area eq "P"}Provider{/if}/{$item.membership}</br />
	{/foreach}
  </td>
  <td>
<input type="checkbox" name="post_data[{$v.id}][delete]" value="Y" />
  </td>
</tr>
{/foreach}

</table>

<input type="submit" name="Submit" value="Submit" />

</form>
{/if}


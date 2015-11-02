{include file="page_title.tpl" title="User permission groups"}
<br /><br />
{include file="dialog_tools.tpl"}
<br />
{capture name=dialog}

<table align="center">
{foreach from=$all_memberships_users item=v key=k}

<tr>
<td class="TableHead" align="left">
	{if $k eq "A"}
		<b>Admin</b>
	{else}
		<b>Provider</b>
	{/if}
<td colspan="4" class="TableHead"></td>
</tr>

	{if $v ne ""}
		{foreach from=$v item=vv key=membershipid}

<tr>
<td class="TableHead"></td>
<td align="left" class="TableHead">
			{if $membershipid eq "0"}
				<b>Not member in</b>
			{else}
				{foreach from=$all_memberships item=v_m key=k_m}
					{if $v_m.membershipid eq $membershipid}
						<b>{$v_m.membership}</b>
					{/if}
				{/foreach}
			{/if}
</td>
<td colspan="3" class="TableHead"></td>

</tr>

			{if $vv ne ""}
				{foreach from=$vv item=vvv ke=kkk}
<tr {cycle values=", class='TableSubHead'" name="cycle_totals"}>
					<td colspan="2">&nbsp;</td>
					<td>{$vvv.login}</td>
					<td>{$vvv.firstname}</td>
					<td><a href="user_modify.php?user={$vvv.login}&usertype={$k}" target="_blank">edit</a></td>
</tr>
				{/foreach}
			{/if}
		{/foreach}
	{/if}
{/foreach}
</table>

{/capture}
{include file="dialog.tpl" title="User permission groups" content=$smarty.capture.dialog extra='width="100%"'}

<br />

{if $distributors_logins_view_log ne ""}

{if $mode eq "search"}
{if $total_items gt "0"}
{$lng.txt_N_results_found|substitute:"items":$total_items}<br />
{$lng.txt_displaying_X_Y_results|substitute:"first_item":$first_item:"last_item":$last_item}
{else}
{$lng.txt_N_results_found|substitute:"items":0}
{/if}
{/if}
<br />
<br />

{capture name=dialog}

{include file="customer/main/navigation.tpl"}

<table border="0" {* width="100%" *} cellpadding="3" cellspacing="1" align="center">
<tr class='TableSubHead'>
<td><B>User login</B></td>
<td><B>Distributor</B></td>
<td><B>Manufacturer</B></td>
</tr>

{foreach from=$distributors_logins_view_log item=v key=k}

   <tr {cycle values=", class='TableSubHead'" name="cycle_totals"}>

        <td><a href="user_modify.php?user={$v.login}&usertype={$v.usertype|default:'A'}" target="_blank" style="color: blue;">{$v.login}</a></td>
        <td>{$v.date|date_format:'%d-%b-%Y&nbsp; %H:%M'}</td>
        <td><a href="manufacturers.php?manufacturerid={$v.manufacturerid}&distributor_section=1" target="_blank" style="color: blue;">{$v.manufacturer}</a></td>
   </tr>

{/foreach}

</table>

{/capture}
{include file="dialog.tpl" title="Distributors logins view log" content=$smarty.capture.dialog extra='width="100%"'}

{else}
<br />Empty
{/if}



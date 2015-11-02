{* $Id: condition_membership.tpl,v 1.4 2005/12/07 14:07:32 max Exp $ *}

<table>
{foreach from=$condition.memberships item=membership}
{if $membership.selected}
<tr>
	<td>{$membership.name|escape}</td>
</tr>
{/if}
{/foreach}
</table>

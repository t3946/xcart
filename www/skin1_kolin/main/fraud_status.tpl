{if $fraud_statuses ne ""}
	{if $fraud_static eq "Y"}
		{$fraud_statuses[$fraud_status]}
	{else}
		<select name="fraud_status" id="fraud_status">
		{foreach from=$fraud_statuses item=item key=key}
			<option value="{$key}" {if $key eq $fraud_status}selected="selected"{/if}>{$item}</option>
		{/foreach}
		</select>
	{/if}
{/if}

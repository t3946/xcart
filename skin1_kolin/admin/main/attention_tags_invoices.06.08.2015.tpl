<form name="fraudform" action="configuration.php" method="POST">
<input type="hidden" name="option" value="Attention_tags_invoices">
<input type="hidden" name="mode" value="Update_Attention_tags_invoices">


<table width="100%" cellspacing="1" cellpadding="3">

<tr>
	<td width="3%">&nbsp;</td>
	<td width="37%">
If Unit cost &gt; Cost to us, then set the following attention tag:
	</td>
	<td width="60%">
<select name="tag_for_Unit_cost_GT_Cost_to_us">
        <option value="">None</option>
        {foreach from=$attention_tags_values item=v key=k}
                <option value="{$v.status_id}" {if $v.status_id eq $config.Attention_tags_invoices.tag_for_Unit_cost_GT_Cost_to_us}selected="selected"{/if}>{$v.status}</option>
        {/foreach}
</select>
	</td>
</tr>

<tr>
	<td width="3%">&nbsp;</td>
	<td width="37%" class="TableSubHead">
If Unit cost &lt; Cost to us, then set the following attention tag:
	</td>
	<td width="60%" class="TableSubHead">
<select name="tag_for_Unit_cost_LT_Cost_to_us">
        <option value="">None</option>
        {foreach from=$attention_tags_values item=v key=k}
                <option value="{$v.status_id}" {if $v.status_id eq $config.Attention_tags_invoices.tag_for_Unit_cost_LT_Cost_to_us}selected="selected"{/if}>{$v.status}</option>
        {/foreach}
</select>
	</td>
</tr>

<tr>
	<td width="3%">&nbsp;</td>
	<td width="37%" class="TableSubHead">
If Qty invoiced != Qty dispatched, then set the following attention tag:
	</td>
	<td width="60%" class="TableSubHead">
<select name="tag_for_Qty_invoiced_NOT_EQ_Qty_dispatched">
        <option value="">None</option>
        {foreach from=$attention_tags_values item=v key=k}
                <option value="{$v.status_id}" {if $v.status_id eq $config.Attention_tags_invoices.tag_for_Qty_invoiced_NOT_EQ_Qty_dispatched}selected="selected"{/if}>{$v.status}</option>
        {/foreach}
</select>
	</td>
</tr>

<tr>
        <td width="3%">&nbsp;</td>
        <td width="37%">
If Tax charged (except HST) &gt; 0, then set the following attention tag:
        </td>
        <td width="60%">
<select name="tag_for_Tax_charged_except_HST_GT_0">
        <option value="">None</option>
        {foreach from=$attention_tags_values item=v key=k}
                <option value="{$v.status_id}" {if $v.status_id eq $config.Attention_tags_invoices.tag_for_Tax_charged_except_HST_GT_0}selected="selected"{/if}>{$v.status}</option>
        {/foreach}
</select>
        </td>
</tr>

<tr>
	<td width="3%">&nbsp;</td>
	<td width="37%">
If Shipping charged &gt; Shipping quoted by distributor, then set the following attention tag:
	</td>
	<td width="60%">
<select name="tag_for_Shipping_charged_GT_Shipping_quoted_by_distr">
        <option value="">None</option>
        {foreach from=$attention_tags_values item=v key=k}
                <option value="{$v.status_id}" {if $v.status_id eq $config.Attention_tags_invoices.tag_for_Shipping_charged_GT_Shipping_quoted_by_distr}selected="selected"{/if}>{$v.status}</option>
        {/foreach}
</select>
	</td>
</tr>

<tr>
	<td width="3%">&nbsp;</td>
	<td width="37%" class="TableSubHead">
If Drop-ship fee charged &gt; Drop-ship fee in X-cart, then set the following attention tag:
	</td>
	<td width="60%" class="TableSubHead">
<select name="tag_for_Drop_ship_fee_charged_GT_Drop_ship_fee_in_xcart">
        <option value="">None</option>
        {foreach from=$attention_tags_values item=v key=k}
                <option value="{$v.status_id}" {if $v.status_id eq $config.Attention_tags_invoices.tag_for_Drop_ship_fee_charged_GT_Drop_ship_fee_in_xcart}selected="selected"{/if}>{$v.status}</option>
        {/foreach}
</select>
	</td>
</tr>

<tr>
        <td width="3%">&nbsp;</td>
        <td width="37%" class="TableSubHead">
If HST charged &gt; 0, then set the following attention tag:
        </td>
        <td width="60%" class="TableSubHead">
<select name="tag_for_HST_charged_GT_0">
        <option value="">None</option>
        {foreach from=$attention_tags_values item=v key=k}
                <option value="{$v.status_id}" {if $v.status_id eq $config.Attention_tags_invoices.tag_for_HST_charged_GT_0}selected="selected"{/if}>{$v.status}</option>
        {/foreach}
</select>
        </td>
</tr>

</table>

<br />
<input type="submit" value=" Save ">
</form>

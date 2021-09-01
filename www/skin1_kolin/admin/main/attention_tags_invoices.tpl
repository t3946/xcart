{*<script type="text/javascript" language="JavaScript 1.2" src="{$SkinDir}/lib/jqueryui/jquery-ui.custom.min.js"></script>*}
{*<script type="text/javascript" language="JavaScript 1.2" src="{$SkinDir}/lib/jqueryui/jquery-ui-timepicker-addon.min.js"></script>*}
{*<link rel="stylesheet" href="{$SkinDir}/lib/jqueryui/jquery.ui.theme.css" />*}
{*<link rel="stylesheet" href="{$SkinDir}/lib/jqueryui/jquery-ui-timepicker-addon.min.css" />*}
<form name="fraudform" action="configuration.php" method="POST">
<input type="hidden" name="option" value="Attention_tags_invoices">
<input type="hidden" name="mode" value="Update_Attention_tags_invoices">

<table width="100%" cellspacing="1" cellpadding="3">
<tr>
   <td class="TableSeparator" colspan="3">
       Attention tags triggered by invoices
       <br>
       <br>
   </td>
</tr>
<tr>
	<td width="3%">&nbsp;</td>
	<td width="57%">
If <b>Unit cost</b> &gt; <b>Cost to us</b>, then set the following attention tag:
	</td>
	<td width="40%">
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
	<td width="57%" class="TableSubHead">
If <b>Unit cost</b> &lt; <b>Cost to us</b>, then set the following attention tag:
	</td>
	<td width="40%" class="TableSubHead">
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
	<td width="57%">
If <b>Qty invoiced</b> != <b>Qty dispatched</b>, then set the following attention tag:
	</td>
	<td width="40%">
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
        <td width="57%" class="TableSubHead">
If <b>extra items are present on the invoice</b>, then set the following attention tag:
        </td>
        <td width="40%" class="TableSubHead">
<select name="tag_for_extra_items_on_invoice">
        <option value="">None</option>
        {foreach from=$attention_tags_values item=v key=k}
                <option value="{$v.status_id}" {if $v.status_id eq $config.Attention_tags_invoices.tag_for_extra_items_on_invoice}selected="selected"{/if}>{$v.status}</option>
        {/foreach}
</select>
        </td>
</tr>

<tr>
        <td width="3%">&nbsp;</td>
        <td width="57%">
If <b>Non-HST tax charged (for example, Sales tax)</b> &gt; <b>0</b>, then set the following attention tag:
        </td>
        <td width="40%">
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
	<td width="57%" class="TableSubHead">
If <b>Shipping charged</b> &gt; <b>Shipping quoted by distributor != 0.00</b>, then set the following attention tag:
	</td>
	<td width="40%" class="TableSubHead">
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
        <td width="57%">
If <b>Shipping charged</b> = <b>0.00</b>, then set the following attention tag:
        </td>
        <td width="40%">
<select name="tag_for_Shipping_charged_EQ_0">
        <option value="">None</option>
        {foreach from=$attention_tags_values item=v key=k}
                <option value="{$v.status_id}" {if $v.status_id eq $config.Attention_tags_invoices.tag_for_Shipping_charged_EQ_0}selected="selected"{/if}>{$v.status}</option>
        {/foreach}
</select>
        </td>
</tr>

<tr>
        <td width="3%">&nbsp;</td>
        <td width="57%" class="TableSubHead">
If <b>items are shipped to the wrong address</b>, then set the following attention tag:
        </td>
        <td width="40%" class="TableSubHead">
<select name="tag_for_items_shipped_to_wrong_address">
        <option value="">None</option>
        {foreach from=$attention_tags_values item=v key=k}
                <option value="{$v.status_id}" {if $v.status_id eq $config.Attention_tags_invoices.tag_for_items_shipped_to_wrong_address}selected="selected"{/if}>{$v.status}</option>
        {/foreach}
</select>
        </td>
</tr>

<tr>
	<td width="3%">&nbsp;</td>
	<td width="57%">
If <b>Drop-ship fee charged</b> &gt; <b>Drop-ship fee in X-cart</b>, then set the following attention tag:
	</td>
	<td width="40%">
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
        <td width="57%" class="TableSubHead">
If <b>HST charged</b> &gt; <b>0</b>, then set the following attention tag:
        </td>
        <td width="40%" class="TableSubHead">
<select name="tag_for_HST_charged_GT_0">
        <option value="">None</option>
        {foreach from=$attention_tags_values item=v key=k}
                <option value="{$v.status_id}" {if $v.status_id eq $config.Attention_tags_invoices.tag_for_HST_charged_GT_0}selected="selected"{/if}>{$v.status}</option>
        {/foreach}
</select>
        </td>
</tr>

<tr>
        <td width="3%">&nbsp;</td>
        <td width="57%">
If <b>PROFIT</b> &lt; <b>0.00</b>, then set the following attention tag:
        </td>
        <td width="40%">
<select name="tag_for_PROFIT_LT_0">
        <option value="">None</option>
        {foreach from=$attention_tags_values item=v key=k}
                <option value="{$v.status_id}" {if $v.status_id eq $config.Attention_tags_invoices.tag_for_PROFIT_LT_0}selected="selected"{/if}>{$v.status}</option>
        {/foreach}
</select>
        </td>
</tr>

        <tr>
                <td class="TableSeparator" colspan="3">
                        <br>
                        <br>
                        Attention tags triggered by events
                        <br>
                        <br>
                </td>
        </tr>
        <tr>
                <td width="3%">&nbsp;</td>
                <td width="57%">
                        <b>PayPal processing failed</b>:
                </td>
                <td width="40%">
                        <select name="tag_for_events_paypal_processing_failed">
                                <option value="">None</option>
                                {foreach from=$attention_tags_values item=v key=k}
                                        <option value="{$v.status_id}" {if $v.status_id eq $config.Attention_tags_invoices.tag_for_events_paypal_processing_failed}selected="selected"{/if}>{$v.status}</option>
                                {/foreach}
                        </select>
                </td>
        </tr>
        <tr>
                <td width="3%">&nbsp;</td>
                <td class="TableSeparator" width="57%">
                        Order transaction dispute tags:
                </td>
                <td width="40%">
                </td>
        </tr>
        <tr>
                <td width="3%">&nbsp;</td>
                <td width="57%">
                        &nbsp;&nbsp;&nbsp;&nbsp;<b> - dispute created</b>:
                </td>
                <td width="40%">
                        <select name="tag_for_events_dispute_created">
                                <option value="">None</option>
                            {foreach from=$attention_tags_values item=v key=k}
                                    <option value="{$v.status_id}" {if $v.status_id eq $config.Attention_tags_invoices.tag_for_events_dispute_created}selected="selected"{/if}>{$v.status}</option>
                            {/foreach}
                        </select>
                </td>
        </tr>
        <tr>
                <td width="3%">&nbsp;</td>
                <td width="57%">
                        &nbsp;&nbsp;&nbsp;&nbsp;<b>- dispute updated</b>:
                </td>
                <td width="40%">
                        <select name="tag_for_events_dispute_updated">
                                <option value="">None</option>
                            {foreach from=$attention_tags_values item=v key=k}
                                    <option value="{$v.status_id}" {if $v.status_id eq $config.Attention_tags_invoices.tag_for_events_dispute_updated}selected="selected"{/if}>{$v.status}</option>
                            {/foreach}
                        </select>
                </td>
        </tr>
        <tr>
                <td width="3%">&nbsp;</td>
                <td width="57%">
                        &nbsp;&nbsp;&nbsp;&nbsp;<b>- dispute resolved</b>:
                </td>
                <td width="40%">
                        <select name="tag_for_events_dispute_resolved">
                                <option value="">None</option>
                            {foreach from=$attention_tags_values item=v key=k}
                                    <option value="{$v.status_id}" {if $v.status_id eq $config.Attention_tags_invoices.tag_for_events_dispute_resolved}selected="selected"{/if}>{$v.status}</option>
                            {/foreach}
                        </select>
                </td>
        </tr>

        <tr>
                <td class="TableSeparator" colspan="3">
                        <br>
                        <br>
                        Auto-removable tags
                        <br>
                        <br>
                </td>
        </tr>
        <tr>
                <td width="3%">&nbsp;</td>
                <td width="57%">
                        <b>One day tag Unset time</b>:
                </td>
                <td width="40%">
                        <input size="3" type="text" id="one_day_unset_time_box" name="one_day_unset_time" value="{$config.Attention_tags_invoices.one_day_unset_time}"/>
                        <select  style="max-width: 194px;" name="tag_one_day_unset">
                                <option value="">None</option>
                                {foreach from=$attention_tags_values item=v key=k}
                                        <option value="{$v.status_id}" {if $v.status_id eq $config.Attention_tags_invoices.tag_one_day_unset}selected="selected"{/if}>{$v.status}</option>
                                {/foreach}
                        </select>
                        {literal}
                                <script>
                                        $('#one_day_unset_time_box').timepicker()();
                                </script>
                        {/literal}
                </td>
        </tr>

        <tr>
                <td class="TableSeparator" colspan="3">
                        <br>
                        <br>
                        Order related tags
                        <br>
                        <br>
                </td>
        </tr>
        <tr>
                <td width="3%">&nbsp;</td>
                <td width="57%">
                        <b>Customer service tips tag</b>:
                </td>
                <td width="40%">
                        <select style="max-width: 194px;" name="tag_customer_tips">
                                <option value="">None</option>
                                {foreach from=$attention_tags_values item=v key=k}
                                        <option value="{$v.status_id}"
                                                {if $v.status_id eq $config.Order_related_tags.tag_customer_tips}selected="selected"{/if}>{$v.status}</option>
                                {/foreach}
                        </select>
                        {literal}
                                <script>
                                        $('#one_day_unset_time_box').timepicker()();
                                </script>
                        {/literal}
                </td>
        </tr>
        <tr>
                <td colspan="3" align="center">
                        <input type="submit" value=" Save ">
                </td>
        </tr>
</table>
</form>
<br/>
<br/>
<form name="tag_settings_form" action="{$xcartApp->router->url('order:order_note_tag_settings')}" method="POST">
        <input type="hidden" name="option" value="Templates_OrderRelatedMessages_Tags">
        <table width="100%">
                <tr>
                        <td>Add the following Attention tag when order note is left:</td>
                        <td>
                                <select id="o_status" style="width:230px;" class="select2-field"  name="order_note_tag">
                                        <option></option>
                                        {foreach from=$ca_statuses item=item_v key=key_k}
                                                <option {if $global_config.order_note_tag == $item_v.status_id}selected="selected"{/if}value="{$item_v.status_id}">{$item_v.status}</option>
                                        {/foreach}
                                </select>
                        </td>
                        <td width="40%"></td>
                </tr>
                <tr>
                        <td>Do NOT apply Attention tag when order note is left by:</td>
                        <td>
                                <select style="width:230px;" id="o_users" multiple class="select2-field" name="order_note_tag_users[]">
                                        <option></option>
                                        {foreach from=$users item=item_u key=key_k}
                                                <option {if in_array($item_u->id, ','|explode:$global_config.order_note_tag_users)}selected="selected"{/if} value="{$item_u->id}">{$item_u}</option>
                                        {/foreach}
                                </select>
                        </td>
                </tr>
                <tr>
                        <td colspan="3" align="center">
                                <button type="submit">Save</button>
                        </td>
                </tr>
        </table>
</form>

<form name="fraudform" action="configuration.php" method="POST">
<input type="hidden" name="option" value="Fraud_check">
<input type="hidden" name="mode" value="Update_Fraud_check">

<table width="100%" cellspacing="1" cellpadding="3">
<tr>
<td class="TableSeparator" colspan="3">
<br>General fraud check options<br><br></td>
</tr>

<tr>
<td width="100" class="TableSubHead" nowrap="nowrap">
Domains of free email providers:
</td>
<td width="*" class="TableSubHead">
<input type="text" style="width: 98%;" name="fraud_domains_free_email_provider" value="{$config.Fraud_check.fraud_domains_free_email_provider}">
</td>
</tr>

<tr>
<td width="100" class="TableSubHead" nowrap="nowrap">
Overall FC threshold for `Clear` status:
</td>
<td width="*" class="TableSubHead">
<input type="text" style="width: 98%;" name="Overall_FC_threshold_for_Clear_status" value="{$config.Fraud_check.Overall_FC_threshold_for_Clear_status}">
</td>
</tr>

<tr>
<td width="100" class="TableSubHead" nowrap="nowrap">
Threshold status:
</td>
<td width="*" class="TableSubHead">
<select name="Threshold_status">
{if $fraud_statuses ne ""}
{foreach from=$fraud_statuses item=item key=key}
<option {if $config.Fraud_check.Threshold_status eq $key}selected="selected" {/if} value="{$key}">{$item}</option>
{/foreach}
{/if}
</select>
</td>
</tr>

<tr>
<td width="100" class="TableSubHead" nowrap="nowrap">
Below threshold status
</td>
<td width="*" class="TableSubHead">
<select name="below_threshold_status">
{if $fraud_statuses ne ""}
{foreach from=$fraud_statuses item=item key=key}
<option {if $config.Fraud_check.below_threshold_status eq $key}selected="selected" {/if} value="{$key}">{$item}</option>
{/foreach}
{/if}
</select>
</td>
</tr>


</table>

<script type="text/javascript">
        <!--
                var lbl_add = '{$lng.lbl_add|escape}';
                var lbl_remove_row = '{$lng.lbl_remove_row|escape}';
                var ImagesDir = '{$ImagesDir}';
                var row_max_index = {$row_max_index};
        -->
</script>

{include file="main/include_js.tpl" src="admin/main/fraud_check_options.js"}

<br />
<hr />
<br />

<table width="100%" cellpadding="3">
<tr id="tr_header_row" style="background-color: #EEEEEE;">
        <td valign="top" nowrap="nowrap" width="25%"><b>Question code</b></td>
        <td valign="top" nowrap="nowrap" width="10" align="center"><b>Auto</b></td>
        <td valign="top" nowrap="nowrap" width="10" align="center"><b>Importance<br />factors</b></td>
        <td valign="top" nowrap="nowrap" width="10" align="center"><b>Order<br /> by</b></td>
        <td valign="top" nowrap="nowrap" width="*"><b>Question template</b></td>
        <td nowrap="nowrap" width="20"></td>
        <td nowrap="nowrap" width="20"></td>
</tr>

        {if $fraud_checks ne ""}
                {foreach from=$fraud_checks item="template_item" key=key name="depforeach"}

                <tr id="template_row{$key}">

<td valign="top" align="center" width="25%">
<input type="text"  name="fraud_checks[{$key}][question_code]" value="{$template_item.question_code}" size="8" style="width: 96%;" />
</td>

<td valign="top" align="center" width="10">
<input type="checkbox" name="fraud_checks[{$key}][auto]" value="Y" {if $template_item.auto eq "Y"}checked="checked"{/if} style="padding: 0px; margin: -2px 0 0 0;" />
</td>

<td valign="top" align="center" width="10">
<input type="text"  name="fraud_checks[{$key}][importance_factor]" value="{$template_item.importance_factor}" size="8" style="width: 96%;" />
</td>

<td valign="top" align="center" width="10">
<input type="text"  name="fraud_checks[{$key}][orderby]" value="{$template_item.orderby}" size="2" style="width: 96%;" />
</td>

<td valign="top" align="center" width="*">
<textarea cols="45" rows="8" name="fraud_checks[{$key}][question_template_body]" style="width: 96%;" />{$template_item.question_template_body}</textarea>
</td>

<td valign="top" align="center" width="20">
<a href="javascript: void(0);" onclick="javascript: remove_row('{$key}');"><img src="{$ImagesDir}/minus.gif" alt="{$lng.lbl_remove_row|escape:'javascript'}" /></a>
</td>
<td valign="top" align="center" width="20">
<a href="javascript: void(0);" onclick="javascript: add_row('{$row_max_index}');"><img src="{$ImagesDir}/plus.gif" alt="{$lng.lbl_add|escape}" /></a>
</td>
                </tr>

                {/foreach}
        {/if}

                <tr id="template_row{$row_max_index}"><td colspan="7"></td></tr>


</table>

<br />
<input type="submit" value=" Save ">
</form>

<br />
<div style="float: left;">
{$lng.lbl_fraud_check_variables}
</div>

<div style="float: left; margin-left: 20px;">
<B>'Question codes' for 'Auto':</B><br />
IS_EMAIL_DOMAIN_FREE<br />
CHECK_EMAIL_VS_NAME<br /> 	
ORDER_FULLNAMES<br />
CHECK_STATES<br />
GEOIP_CITY_VS_B_S<br />	
CHECK_OK_ORDERS_FOR_EMAIL<br />
CHECK_FULLNAMES_FOR_EMAIL<br />	
CHECK_DIFFERENT_SHIPPINGS_FOR_IP<br />
CHECK_DIFFERENT_BILLINGS_FOR_IP<br />	
CHECK_DIFFERENT_SHIPPINGS_FOR_PHONE<br />	
CHECK_DIFFERENT_BILLINGSS_FOR_PHONE<br />
CHECK_DIFFERENT_SHIPPINGS_FOR_EMAIL<br />
CHECK_DIFFERENT_BILLINGS_FOR_EMAIL<br />
CHECK_DIFFERENT_SHIPPINGS_FOR_CARD<br />
CHECK_DIFFERENT_BILLING_FOR_SHIPPING<br />
CHECK_TOTAL<br />
CHECK_SHIPPING_ADDRESS_LINE2<br />
</div>

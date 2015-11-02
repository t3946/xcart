{* $Id: payment_methods.tpl,v 1.43.2.2 2006/07/11 08:39:26 svowl Exp $ *}
{include file="page_title.tpl" title=$lng.lbl_payment_methods}

{$lng.txt_payment_methods_top_text}

<br /><br />

{capture name=dialog}

{include file="main/language_selector.tpl" script="payment_methods.php?"}

{include file="main/check_all_row.tpl" style="line-height: 170%;" form="pmform" prefix="posted_data.+active"}

<form action="payment_methods.php" method="post" name="pmform">
<input type="hidden" name="mode" value="update" />
{* All payment methods are always available for all memberships *}
<input type="hidden" value="-1" name="posted_data[{$payment_methods[method].paymentid}][membershipids][]" />

<table cellpadding="5" cellspacing="1" width="100%">

<tr class="TableHead">
	<td width="13px" style="background-color: #D9EAD3;" >CO</td>
	<td style="background-color: #D9EAD3;" >{$lng.lbl_pos}</td>
	<td style="background-color: #D9EAD3;" width="20%">Payment method</td>
	<td style="background-color: #D9EAD3;" width="35%" nowrap="nowrap">checkout instructions</td>
{if $active_modules.Anti_Fraud}
	<td width="10%" nowrap="nowrap">{$lng.lbl_check}*</td>
{/if}
	<td width="40px" style="background-color: #FFD44C;">{$lng.lbl_acc_proc}</td>
	<td width="40px" style="background-color: #FFD44C;">Trans fees</td>
	<td width="40px" style="background-color: #F4CCCC;">VT</td>
	<td width="40px" style="background-color: #F4CCCC;" nowrap="nowrap">Transaction id link</td>
</tr>

{section name=method loop=$payment_methods}
{cycle values=', class="TableSubHead"' assign=trcolor}

{if $payment_methods[method].disable_checkbox eq "Y"}<input type="hidden" name="posted_data[{$payment_methods[method].paymentid}][active]" value="Y" />{/if}

<tr{$trcolor}>
	<td valign="top"{if $payment_methods[method].module_name ne ""} rowspan="2"{/if}>
	<input type="checkbox" name="posted_data[{$payment_methods[method].paymentid}][active]" value="Y"{if $payment_methods[method].active eq "Y"} checked="checked"{/if}{if $payment_methods[method].disable_checkbox eq "Y"} disabled="disabled"{/if} />
	</td>
	<td valign="top"{if $payment_methods[method].module_name ne ""} rowspan="2"{/if}>
	<input type="text" size="3" maxlength="5" name="posted_data[{$payment_methods[method].paymentid}][orderby]" value="{$payment_methods[method].orderby}" />
	</td>
	<td valign="top">
	<input type="text" size="30" name="posted_data[{$payment_methods[method].paymentid}][payment_method]" value="{$payment_methods[method].payment_method|escape:"html"}" />
<br />
<table cellpadding="1" cellspacing="0">
<tr>
	<td class="FormButton">{$lng.lbl_cod_extra_charge}:</td>
	<td><input type="text" size="8" name="posted_data[{$payment_methods[method].paymentid}][surcharge]" value="{$payment_methods[method].surcharge|default:"0"|formatprice}" /></td>
	<td>
	<select name="posted_data[{$payment_methods[method].paymentid}][surcharge_type]">
		<option value="%"{if $payment_methods[method].surcharge_type eq "%"} selected="selected"{/if}>%</option>
		<option value="$"{if $payment_methods[method].surcharge_type eq "$"} selected="selected"{/if}>{$config.General.currency_symbol}</option>
	</select>
	</td>
</tr>

<tr>
	<td class="FormButton">Protocol:</td>
	<td colspan="2">
        <select name="posted_data[{$payment_methods[method].paymentid}][protocol]">
                <option value="http"{if $payment_methods[method].protocol eq "http"} selected="selected"{/if}>HTTP</option>
                <option value="https"{if $payment_methods[method].protocol eq "https"} selected="selected"{/if}>HTTPS</option>
        </select>
	</td>
</tr>
</table>
	</td>
	<td valign="top" nowrap="nowrap">
	<textarea name="posted_data[{$payment_methods[method].paymentid}][payment_details]" cols="45" rows="3">{$payment_methods[method].payment_details|escape:"html"}</textarea>

{if $payment_methods[method].processor_file eq ""}
<br />
<table cellpadding="1" cellspacing="0">
<tr>
        <td><input type="checkbox" id="is_cod_{$payment_methods[method].paymentid}" name="posted_data[{$payment_methods[method].paymentid}][is_cod]" value="Y"{if $payment_methods[method].is_cod eq 'Y'} checked="checked"{/if} /></td>
        <td class="FormButton"><label for="is_cod_{$payment_methods[method].paymentid}">{$lng.lbl_cash_on_delivery_method}</label></td>
</tr>
</table>
{/if}

	</td>
{if $active_modules.Anti_Fraud}
	<td valign="top"{if $payment_methods[method].module_name ne ""} rowspan="2"{/if}>
	<input type="checkbox" name="posted_data[{$payment_methods[method].paymentid}][af_check]" value="Y"{if $payment_methods[method].af_check eq 'Y'} checked="checked"{/if} />
	</td>
{/if}
	<td valign="top"{if $payment_methods[method].module_name ne ""} rowspan="2"{/if}>
	<input type="checkbox" name="posted_data[{$payment_methods[method].paymentid}][acc_proc]" value="Y"{if $payment_methods[method].acc_proc eq "Y"} checked="checked"{/if} />
	</td>


	<td valign="top"{if $payment_methods[method].module_name ne ""} rowspan="2"{/if} nowrap="nowrap" align="right">%
	<input type="text" size="4" name="posted_data[{$payment_methods[method].paymentid}][acc_percent]" value="{$payment_methods[method].acc_percent}" />
	<br />per sale
	<input type="text" size="4" name="posted_data[{$payment_methods[method].paymentid}][acc_per_trans]" value="{$payment_methods[method].acc_per_trans}" />
	<br />% ref
        <input type="text" size="4" name="posted_data[{$payment_methods[method].paymentid}][percent_ref]" value="{$payment_methods[method].percent_ref}" />
	<br />per ref
	<input type="text" size="4" name="posted_data[{$payment_methods[method].paymentid}][per_ref]" value="{$payment_methods[method].per_ref}" />
	<br />
	</td>

	<td valign="top"{if $payment_methods[method].module_name ne ""} rowspan="2"{/if}>
		<input type="checkbox" name="posted_data[{$payment_methods[method].paymentid}][vt]" value="Y"{if $payment_methods[method].vt eq "Y"} checked="checked"{/if} />
	</td>
	<td valign="top"{if $payment_methods[method].module_name ne ""} rowspan="2"{/if}>
		<input type="text" size="30" name="posted_data[{$payment_methods[method].paymentid}][transaction_id_link]" value="{$payment_methods[method].transaction_id_link|escape:"html"}" />
	</td>
</tr>

{if $payment_methods[method].module_name ne ""}
<tr{$trcolor}>
	<td colspan="{if $active_modules.Anti_Fraud}8{else}7{/if}" valign="bottom">
{if $payment_methods[method].type eq "C"}{$lng.lbl_credit_card_processor}{elseif $payment_methods[method].type eq "H"}{$lng.lbl_check_processor}{else}{assign var=type value="ps"}{$lng.lbl_ps_processor}{/if} <b>{$payment_methods[method].module_name}</b>:
{if $payment_methods[method].processor eq "ps_gcheckout.php"}
<a href="configuration.php?option=Google_Checkout">{$lng.lbl_configure}</a>
{else}
<a href="cc_processing.php?mode=update&amp;cc_processor={$payment_methods[method].processor}">{$lng.lbl_configure}</a>
{/if}
 | <a href="cc_processing.php?mode=delete&amp;paymentid={$payment_methods[method].paymentid}">{$lng.lbl_delete}</a>
{if $payment_methods[method].is_down or $payment_methods[method].in_testmode}
<table cellpadding="2">
{if $payment_methods[method].is_down}
<tr>
	<td><img src="{$ImagesDir}/log_type_Warning.gif" alt="" /></td>
	<td><font class="AdminSmallMessage">{$lng.txt_cc_processor_requirements_failed|substitute:"processor":$payment_methods[method].module_name}</font></td>
</tr>
{/if}
{if $payment_methods[method].in_testmode}
<tr>
	<td><img src="{$ImagesDir}/log_type_Warning.gif" alt="" /></td>
	<td><font class="AdminSmallMessage">{$lng.txt_cc_processor_in_text_mode|substitute:"processor":$payment_methods[method].module_name}</font></td>
</tr>
{/if}
</table>
{/if}{* $payment_methods[method].is_down or $payment_methods[method].in_testmode *}
	</td>
</tr>
{/if}

{/section}

<tr>
	<td align="center" colspan="{if $active_modules.Anti_Fraud}8{else}7{/if}" class="SubmitBox"><input type="submit" value="{$lng.lbl_update|strip_tags:false|escape}" /></td>
</tr>

{if $active_modules.Anti_Fraud}
<tr>
	<td colspan="8">&nbsp;</td>
</tr>
<tr>
	<td colspan="8">*) {$lng.txt_af_payment_method_note}</td>
</tr>
{/if}

</table>
</form>

{/capture}
{include file="dialog.tpl" title=$lng.lbl_payment_methods content=$smarty.capture.dialog extra='width="100%"'}

<br /><br />{include file="admin/main/cc_processing.tpl"}

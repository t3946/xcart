{* $Id: configuration.tpl,v 1.91.2.7 2007/01/16 09:06:32 twice Exp $ *}

<script src="{$SkinDir}/tinymce/js/tinymce/tinymce.min.js" type="text/javascript"></script>

<script type="text/javascript">
//<![CDATA[
{literal}

tinymce.init({
    selector: "textarea.new_editor",
    resize: "both",
    plugins: [
        "advlist autolink lists link image charmap print preview anchor",
        "searchreplace visualblocks code fullscreen",
        "insertdatetime media table contextmenu paste"
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
    forced_root_block : false,
    force_br_newlines : true,
    force_p_newlines : false,
    convert_urls: false,
    relative_urls: false
});

{/literal}
//]]>
</script>

{if $option ne 'Multiple_Storefronts'}
    {include file="page_title.tpl" title=$lng.lbl_general_settings}

    {$lng.txt_general_settings_top_text}

    <br /><br />

    {include file="dialog_tools.tpl"}

     <br />
{else}
	{include file="page_title.tpl" title=$sf_page_title}
	{include file="main/include_js.tpl" src="main/popup_image_selection.js"}
{/if}

{capture name=dialog}

{if $active_modules.Multiple_Storefronts && !$configuration && $option ne "Filter_Presets"}

<table cellpadding="3" cellspacing="1" width="100%">

{assign var="option_title" value="option_title_`$option`"}
{if $lng.$option_title}
{assign var="option_title" value=$lng.$option_title}
{else}
{assign var="option_title" value=$option|replace:"_":" "}
{assign var="option_title" value="`$option_title` options"}
{/if}

<tr>
<td class="TopLabel">{include file="main/subheader.tpl" title=$option_title class="black"}</td>
</tr>

<tr>
<td>{if $current_storefront gt 0}{$lng.lbl_no_options}{else}{$lng.txt_main_sf}{/if}</td>
</tr>

</table>
	
{else}

{assign var="cycle_name" value="sep"}

{if $option ne "User_Profiles" && $option ne "Contact_Us" && $option ne "Search_products" && $option ne "Templates_OrderRelatedMessages" && $option ne "Request_availability_options" && $option ne "Fraud_check" && $option ne "Reconciliation" && $option ne "currently_assigned_to_statuses" && $option ne "Attention_tag_options" && $option ne "Product_classification" && $option ne "OTRS_options" && $option ne "Order_page_permissions" && $option ne "Inquiries_options" && $option ne "Supplier_feeds" && $option ne "PBX_options" && $option ne "Attention_tags_invoices"}
<form action="configuration.php?option={$option|escape}" method="post" name="processform" enctype="multipart/form-data">
{/if}

{if $active_modules.Multiple_Storefronts and $option eq "Multiple_Storefronts"}
<input type="hidden" name="storefrontid" value="{$current_storefront}" />
{/if}

<table cellpadding="3" cellspacing="1" width="100%">

{assign var="option_title" value="option_title_`$option`"}
{if $lng.$option_title}
{assign var="option_title" value=$lng.$option_title}
{else}
{assign var="option_title" value=$option|replace:"_":" "}
{assign var="option_title" value="`$option_title` options"}
{/if}
{if $option eq 'Filter_Presets' && $filter ne ''}
    {if $filter.title ne ''}
        {assign var="filter_preset_title" value=$filter.title}
    {else}
        {assign var="filter_preset_title" value=$lng.lbl_filter_empty|escape}
    {/if}
{assign var="option_title" value="`$lng.lbl_order_filter_preset`: `$filter_preset_title` (`$filter.row`, `$filter.column`) <a href=\"configuration.php?option=Filter_Presets\" class=\"preset_back\">`$lng.lbl_back_to_presets`</span>"}
{/if}

{if $option  ne 'Search_products'}
<tr>
<td class="TopLabel">
{include file="main/subheader.tpl" title=$option_title class="black"}
</td>
</tr>
{/if}

{if $option eq 'Shipping_Label_Generator'}
<tr>
<td>
<div align="right">
{include file="buttons/button.tpl" button_title=$lng.lbl_usps_labels_help href="javascript:window.open('popup_info.php?action=TSTLBL','TSTLBL_HELP','width=600,height=460,toolbar=no,status=no,scrollbars=yes,resizable=no,menubar=no,location=no,direction=no');"}
</div>

</td>
</tr>
{/if}

</table>

{if $option ne 'Search_products'}
{* <br /> *}
{/if}

{if $option eq "User_Profiles"}

{include file="admin/main/user_profiles.tpl"}

{elseif $option eq "Templates_OrderRelatedMessages"}

{include file="admin/main/templates_order_related_messages.tpl"}

{elseif $option eq "Attention_tag_options"}

{include file="admin/main/attention_tag_options.tpl"}

{elseif $option eq "Attention_tags_invoices"}

{include file="admin/main/attention_tags_invoices.tpl"}

{elseif $option eq "Product_classification"}

{include file="admin/main/product_classification.tpl"}

{elseif $option eq "Order_page_permissions"}

{include file="admin/main/order_page_permissions.tpl"}

{elseif $option eq "Inquiries_options"}

{include file="admin/main/inquiries_options.tpl"}

{elseif $option eq "Fraud_check"}

{include file="admin/main/fraud_check_options.tpl"}

{elseif $option eq "Supplier_feeds"}

{include file="modules/Supplier_feeds/supplier_feeds_options.tpl"}

{elseif $option eq "OTRS_options"}

{include file="admin/main/otrs_options.tpl"}

{elseif $option eq "PBX_options"}

{include file="admin/main/pbx_options.tpl"}

{elseif $option eq "currently_assigned_to_statuses"}

{include file="admin/main/order_status.tpl"}

{elseif $option eq "Reconciliation"}

{include file="admin/main/reconciliation_options.tpl"}

{elseif $option eq "Request_availability_options"}

{include file="admin/main/request_availability_options.tpl"}

{elseif $option eq "Contact_Us"}

{include file="admin/main/contact_us_profiles.tpl"}

{elseif $option eq "Search_products"}

{include file="admin/main/search_products_form.tpl"}

{elseif $option eq "Filter_Presets"}

{include file="admin/main/filter_presets.tpl"}


{elseif $option eq "Autosubmit_orderentry_operator"}
<table cellpadding="3" cellspacing="1" width="100%">
<tr>
<td>
Do NOT auto-submit to order entry operator if at least one of the following conditions hold true
<br />
<br />
<input type="checkbox" name="number_of_OTRS_messages" {if $config.Autosubmit_orderentry_operator.number_of_OTRS_messages eq "Y"} checked="checked"{/if} /> The number of OTRS messages is NOT equal to 
<input type="text" size="3" name="number_of_OTRS_messages_is_NOT_equal_to_value" value="{$config.Autosubmit_orderentry_operator.number_of_OTRS_messages_is_NOT_equal_to_value}" />
<br />
<input type="checkbox" name="ETA_date_is_present_for_at_least_one_of_the_items" {if $config.Autosubmit_orderentry_operator.ETA_date_is_present_for_at_least_one_of_the_items eq "Y"} checked="checked"{/if} /> ETA date is present for at least one of the items
<br />
<input type="checkbox" name="Customer_notes_field_is_NOT_empty" {if $config.Autosubmit_orderentry_operator.Customer_notes_field_is_NOT_empty eq "Y"} checked="checked"{/if} />Customer notes field is NOT empty
</td>
</tr>
<tr>
<td align="center"><br />
<input type="submit" value="{$lng.lbl_save|strip_tags:false|escape}" />
</td>
</tr>
</table>


{elseif $option eq "Reference_tab"}
<table cellpadding="3" cellspacing="1" width="100%">
<tr>
<td>
<textarea class="new_editor" style="width: 80%" name="reference_text" cols="60" rows="20">{$config.Reference_tab.reference_text|replace:"\n":"<br />"}</textarea>
</td>
</tr>
<tr>
<td align="center"><br />
<input type="submit" value="{$lng.lbl_save|strip_tags:false|escape}" />
</td>
</tr>
</table>
{elseif $option eq "Additional_shipping_charge"}


<table cellpadding="3" cellspacing="1" width="100%">

<tr>
	<td class='FormButton' width="20%">Credit card processing fees: </td>
	<td>
	<input type="text" size="7" name="credit_card_processing_fees" value="{$config.Additional_shipping_charge.credit_card_processing_fees}" />% + $<input type="text" size="7" name="per_transaction" value="{$config.Additional_shipping_charge.per_transaction}" /> per transaction
	</td>
</tr>
<tr>
        <td class='FormButton' colspan = "2">
Required shipping charge {literal}({{required}}){/literal} = <input type="text" size="7" name="required_shipping_charge_k" value="{$config.Additional_shipping_charge.required_shipping_charge_k}" /> * Actual shipping cost
	</td>
</tr>

<tr>
        <td class='FormButton' colspan = "2">
Additional shipping charge {literal}({{additional}}){/literal} = Required shipping charge {literal}({{required}}){/literal} - Original shipping charge
        </td>
</tr>

{*
<tr>
        <td class='FormButton' colspan = "2">Threshhold margin: <input type="text" size="7" name="threshhold_margin" value="{$config.Additional_shipping_charge.threshhold_margin}" />%</td>
</tr>
*}

<tr>
	<td class='FormButton' colspan = "2">
Waive additional shipping charge if it is &lt; &#36;<input type="text" size="7" name="waive_additional_shipping_charge" value="{$config.Additional_shipping_charge.waive_additional_shipping_charge}" />
<br />
OR
<br />
estimated profit &gt; &#36;<input type="text" size="7" name="estimated_profit" value="{$config.Additional_shipping_charge.estimated_profit}" /> AND estimated profit margin &gt; <input type="text" size="7" name="estimated_profit_margin" value="{$config.Additional_shipping_charge.estimated_profit_margin}" />%
	</td>
</tr>

<tr>
        <td colspan = "2">
<br />
{$lng.lbl_additional_shipping_charge_vars}
<br />
        </td>
</tr>

<tr>
        <td class='FormButton'>'Copy to' email</td>
        <td><input type="text" style="width: 80%" name="copy_to_email" value="{$config.Additional_shipping_charge.copy_to_email}" /></td>  
</tr>

<tr>
        <td class='FormButton'>Subject line</td>
        <td><input type="text" style="width: 80%" name="subject_line" value="{$config.Additional_shipping_charge.subject_line}" /></td>   
</tr>

<tr>
	<td class='FormButton'>Message body</td>
	<td><textarea class="new_editor" style="width: 80%" name="message_body" cols="60" rows="20">{$config.Additional_shipping_charge.message_body|replace:"\n":"<br />"}</textarea></td>
</tr>


<tr>
        <td class='TopLabel' colspan = "2">
<br />
<br />
<table cellspacing="0" class="SubHeaderBlack">
<tbody><tr>
	<td class="SubHeaderBlack">Purchase order exception</td>
</tr>
<tr>
	<td class="SubHeaderBlackLine"><img alt="" class="Spc" src="/skin1_kolin/images/spacer.gif"><br></td>
</tr>
</tbody>
</table>
	</td>
</tr>

<tr>
        <td class='FormButton'>'Copy to' email</td>
        <td><input type="text" style="width: 80%" name="po_copy_to_email" value="{$config.Additional_shipping_charge.po_copy_to_email}" /></td>
</tr>

<tr>
        <td class='FormButton'>Subject line</td>
        <td><input type="text" style="width: 80%" name="po_subject_line" value="{$config.Additional_shipping_charge.po_subject_line}" /></td>
</tr>

<tr>
        <td class='FormButton'>Message body</td>
        <td><textarea class="new_editor" style="width: 80%" name="po_message_body" cols="60" rows="20">{$config.Additional_shipping_charge.po_message_body|replace:"\n":"<br />"}</textarea></td>
</tr>


<tr>
        <td align="center" colspan = "2"><br />
        <input type="submit" value="{$lng.lbl_save|strip_tags:false|escape}" />
        </td>
</tr>

</table>

{elseif $option eq "backorder_decision_request"}

<table cellpadding="3" cellspacing="1" width="100%">

<tr><td colspan = "2">
Highlight ETA date on the order list pages in pink if ETA date - <input type="text" size="5" name="backorder_eta_date_x" value="{$config.backorder_decision_request.backorder_eta_date_x}" /> days &lt; <B>Current date</B> &lt; ETA date + <input type="text" size="5" name="backorder_eta_date_y" value="{$config.backorder_decision_request.backorder_eta_date_y}" /> days</td></tr>
<tr><td colspan = "2"><br /></td></tr>
<tr><td colspan = "2">{$lng.lbl_backorder_decision_request_text}</td></tr>

<tr>
        <td class='FormButton' width="30%">
	Do NOT offer backorder if ETA is more than
	</td>
	<td class='FormButton'>
        <input type="text" size="5" name="do_not_offer_backorder_if_eta_more_than_days" value="{$config.backorder_decision_request.do_not_offer_backorder_if_eta_more_than_days}" />
	days forward
	</td>
</tr>

<tr>
        <td class='FormButton'>'Copy to' email</td>
        <td><input type="text" style="width: 80%" name="backorder_copy_to_email" value="{$config.backorder_decision_request.backorder_copy_to_email}" /></td>
</tr>

<tr>
<td colspan="2">
<hr />
<br />

<table width="100%">
<tr>
        <td class='FormButton' width="30%">Case code</td>
        <td class='FormButton'>Subject line /Message body</td>
</tr>

<tr>
        <td class='FormButton'><input readonly="readonly" type="text" style="width: 80%" name="backorder_code_condition_case_a" value="{$config.backorder_decision_request.backorder_code_condition_case_a}" /></td>
        <td><input type="text" style="width: 80%" name="backorder_subject_line_condition_case_a" value="{$config.backorder_decision_request.backorder_subject_line_condition_case_a}" /></td>
</tr>

<tr>
        <td class='FormButton'></td>
        <td><textarea class="new_editor" style="width: 80%" name="backorder_message_body_condition_case_a" cols="60" rows="20">{$config.backorder_decision_request.backorder_message_body_condition_case_a|replace:"\n":"<br />"}</textarea></td>
</tr>

<tr>
        <td class='FormButton'><input readonly="readonly" type="text" style="width: 80%" name="backorder_code_condition_case_b" value="{$config.backorder_decision_request.backorder_code_condition_case_b}" /></td>
        <td><input type="text" style="width: 80%" name="backorder_subject_line_condition_case_b" value="{$config.backorder_decision_request.backorder_subject_line_condition_case_b}" /></td>
</tr>

<tr>
        <td class='FormButton'></td>
        <td><textarea class="new_editor" style="width: 80%" name="backorder_message_body_condition_case_b" cols="60" rows="20">{$config.backorder_decision_request.backorder_message_body_condition_case_b|replace:"\n":"<br />"}</textarea></td>
</tr>

<tr>
        <td class='FormButton'><input readonly="readonly" type="text" style="width: 80%" name="backorder_code_condition_case_c" value="{$config.backorder_decision_request.backorder_code_condition_case_c}" /></td>
        <td><input type="text" style="width: 80%" name="backorder_subject_line_condition_case_c" value="{$config.backorder_decision_request.backorder_subject_line_condition_case_c}" /></td>
</tr>

<tr>
        <td class='FormButton'></td>
        <td><textarea class="new_editor" style="width: 80%" name="backorder_message_body_condition_case_c" cols="60" rows="20">{$config.backorder_decision_request.backorder_message_body_condition_case_c|replace:"\n":"<br />"}</textarea></td>
</tr>

<tr>
        <td class='FormButton'><input readonly="readonly" type="text" style="width: 80%" name="backorder_code_condition_case_d" value="{$config.backorder_decision_request.backorder_code_condition_case_d}" /></td>
        <td><input type="text" style="width: 80%" name="backorder_subject_line_condition_case_d" value="{$config.backorder_decision_request.backorder_subject_line_condition_case_d}" /></td>
</tr>

<tr>
        <td class='FormButton'></td>
        <td><textarea class="new_editor" style="width: 80%" name="backorder_message_body_condition_case_d" cols="60" rows="20">{$config.backorder_decision_request.backorder_message_body_condition_case_d|replace:"\n":"<br />"}</textarea></td>
</tr>

<tr>
        <td class='FormButton'><input readonly="readonly" type="text" style="width: 80%" name="backorder_code_condition_case_e" value="{$config.backorder_decision_request.backorder_code_condition_case_e}" /></td>
        <td><input type="text" style="width: 80%" name="backorder_subject_line_condition_case_e" value="{$config.backorder_decision_request.backorder_subject_line_condition_case_e}" /></td>
</tr>

<tr>
        <td class='FormButton'></td>
        <td><textarea class="new_editor" style="width: 80%" name="backorder_message_body_condition_case_e" cols="60" rows="20">{$config.backorder_decision_request.backorder_message_body_condition_case_e|replace:"\n":"<br />"}</textarea></td>
</tr>

<tr>
        <td class='FormButton'><input readonly="readonly" type="text" style="width: 80%" name="backorder_code_condition_case_f" value="{$config.backorder_decision_request.backorder_code_condition_case_f}" /></td>
        <td><input type="text" style="width: 80%" name="backorder_subject_line_condition_case_f" value="{$config.backorder_decision_request.backorder_subject_line_condition_case_f}" /></td>
</tr>

<tr>
        <td class='FormButton'></td>
        <td><textarea class="new_editor" style="width: 80%" name="backorder_message_body_condition_case_f" cols="60" rows="20">{$config.backorder_decision_request.backorder_message_body_condition_case_f|replace:"\n":"<br />"}</textarea></td>
</tr>



{*
<tr><td colspan="2"><br /><br /><hr />Bellow - Old. Will be deleted later<hr /><br /></td></tr>

<tr>
        <td class='FormButton'><input readonly="readonly" type="text" style="width: 80%" name="backorder_code_condition_no_stock_no_eta" value="{$config.backorder_decision_request.backorder_code_condition_no_stock_no_eta}" /></td>
        <td><input type="text" style="width: 80%" name="backorder_subject_line_condition_no_stock_no_eta" value="{$config.backorder_decision_request.backorder_subject_line_condition_no_stock_no_eta}" /></td>
</tr>

<tr>
        <td class='FormButton'></td>
        <td><textarea class="new_editor" style="width: 80%" name="backorder_message_body_condition_no_stock_no_eta" cols="60" rows="20">{$config.backorder_decision_request.backorder_message_body_condition_no_stock_no_eta|replace:"\n":"<br />"}</textarea></td>
</tr>


<tr>
        <td class='FormButton'><input readonly="readonly" type="text" style="width: 80%" name="backorder_code_condition_no_stock_defined_eta" value="{$config.backorder_decision_request.backorder_code_condition_no_stock_defined_eta}" /></td>
        <td><input type="text" style="width: 80%" name="backorder_subject_line_condition_no_stock_defined_eta" value="{$config.backorder_decision_request.backorder_subject_line_condition_no_stock_defined_eta}" /></td>
</tr>

<tr>
        <td class='FormButton'></td>
        <td><textarea class="new_editor" style="width: 80%" name="backorder_message_body_condition_no_stock_defined_eta" cols="60" rows="20">{$config.backorder_decision_request.backorder_message_body_condition_no_stock_defined_eta|replace:"\n":"<br />"}</textarea></td>
</tr>


<tr>
        <td class='FormButton'><input readonly="readonly" type="text" style="width: 80%" name="backorder_code_condition_partially_in_stock_no_eta" value="{$config.backorder_decision_request.backorder_code_condition_partially_in_stock_no_eta}" /></td>
        <td><input type="text" style="width: 80%" name="backorder_subject_line_condition_partially_in_stock_no_eta" value="{$config.backorder_decision_request.backorder_subject_line_condition_partially_in_stock_no_eta}" /></td>
</tr>

<tr>
        <td class='FormButton'></td>
        <td><textarea style="width: 80%" class="new_editor" name="backorder_message_body_condition_partially_in_stock_no_eta" cols="60" rows="20">{$config.backorder_decision_request.backorder_message_body_condition_partially_in_stock_no_eta|replace:"\n":"<br />"}</textarea></td>
</tr>


<tr>
        <td class='FormButton'><input readonly="readonly" type="text" style="width: 80%" name="backorder_code_condition_partially_in_stock_defined_eta" value="{$config.backorder_decision_request.backorder_code_condition_partially_in_stock_defined_eta}" /></td>
        <td><input type="text" style="width: 80%" name="backorder_subject_line_condition_partially_in_stock_defined_eta" value="{$config.backorder_decision_request.backorder_subject_line_condition_partially_in_stock_defined_eta}" /></td>
</tr>

<tr>
        <td class='FormButton'></td>
        <td><textarea class="new_editor" style="width: 80%" name="backorder_message_body_condition_partially_in_stock_defined_eta" cols="60" rows="20">{$config.backorder_decision_request.backorder_message_body_condition_partially_in_stock_defined_eta|replace:"\n":"<br />"}</textarea></td>
</tr>
*}

</table>

</td>
</tr>

<tr>
        <td align="center" colspan = "2"><br />
        <input type="submit" value="{$lng.lbl_save|strip_tags:false|escape}" />
        </td>
</tr>

</table>

{elseif $option eq "product_queries"}

<table cellpadding="3" cellspacing="1" width="100%">

<tr>
        <td class='FormButton' width="38%">Enable product queries on product page</td>
        <td><input type="checkbox" name="product_queries_enable" {if $config.product_queries.product_queries_enable eq "Y"} checked="checked"{/if} /></td>
</tr>

<tr>
        <td class='FormButton'>Get content url</td>
        <td><input type="text" style="width: 80%" name="product_queries_get_content_url" value="{$config.product_queries.product_queries_get_content_url}" /></td>
</tr>

<tr>
        <td class='FormButton'>Get question forms url</td>
        <td><input type="text" style="width: 80%" name="product_queries_get_question_forms_url" value="{$config.product_queries.product_queries_get_question_forms_url}" /></td>
</tr>

<tr>
        <td align="center" colspan = "2"><br />
        <input type="submit" value="{$lng.lbl_save|strip_tags:false|escape}" />
        </td>
</tr>

</table>

{elseif $option eq "product_question_email"}

<table cellpadding="3" cellspacing="1" width="100%">

<tr>
        <td class='FormButton'>Enable product questions on product page</td>
        <td><input type="checkbox" name="product_question_enable" {if $config.product_question_email.product_question_enable eq "Y"} checked="checked"{/if} /></td>
</tr>


<tr>
        <td colspan = "2">
{$lng.lbl_product_question_email_vars}
<hr />
        </td>
</tr>

<tr>
        <td class='FormButton'>From email address:</td>
        <td><input type="text" style="width: 80%" name="product_question_from" value="{$config.product_question_email.product_question_from|default:'product.questions@s3stores.com'}" /></td>
</tr>

<tr>
        <td class='FormButton'>Bcc email address:</td>
        <td><input type="text" style="width: 80%" name="product_question_bc_email" value="{$config.product_question_email.product_question_bc_email|default:'product.questions@s3stores.com'}" /></td>
</tr>

<tr><td colspan = "2"><hr /></td></tr>

<tr>
        <td class='FormButton'>Subject line</td>
        <td><input type="text" style="width: 80%" name="product_question_subject_line" value="{$config.product_question_email.product_question_subject_line}" /></td>
</tr>

<tr>
        <td class='FormButton'>Message body to us</td>
        <td><textarea style="width: 80%" class="new_editor" name="product_question_message_body_to_brand" cols="60" rows="20">{$config.product_question_email.product_question_message_body_to_brand|replace:"\n":"<br />"}</textarea></td>
</tr>

<tr>
        <td class='FormButton'>Message body to customer</td>
        <td><textarea style="width: 80%" class="new_editor" name="product_question_message_body_to_customer" cols="60" rows="20">{$config.product_question_email.product_question_message_body_to_customer|replace:"\n":"<br />"}</textarea></td>
</tr>

<tr><td colspan = "2"><hr /></td></tr>

<tr>
        <td class='FormButton'>Question subject line to distributor/brand</td>
        <td><input type="text" style="width: 80%" name="product_question_subject_line_to_distr" value="{$config.product_question_email.product_question_subject_line_to_distr}" /></td>
</tr>

<tr>
        <td class='FormButton'>Question message body to distributor/brand</td>
        <td><textarea style="width: 80%" class="new_editor" name="product_question_message_body_to_distr" cols="60" rows="20">{$config.product_question_email.product_question_message_body_to_distr|replace:"\n":"<br />"}</textarea></td>
</tr>

<tr><td colspan = "2"><hr /></td></tr>

<tr>
        <td class='FormButton'>Answer subject line to customer</td>
        <td><input type="text" style="width: 80%" name="product_answer_subject_line" value="{$config.product_question_email.product_answer_subject_line}" /></td>
</tr>

<tr>
        <td class='FormButton'>Answer message body to customer</td>
        <td><textarea style="width: 80%" class="new_editor" name="product_answer_message_body" cols="60" rows="20">{$config.product_question_email.product_answer_message_body|replace:"\n":"<br />"}</textarea></td>
</tr>

<tr><td colspan = "2"><hr /></td></tr>

<tr>
        <td class='FormButton'>Email product question to brand</td>
        <td>
<input type="checkbox" name="product_question_send_to_brand" {if $config.product_question_email.product_question_send_to_brand eq "Y"} checked="checked"{/if} />
	</td>
</tr>

<tr>
        <td class='FormButton'>Email product question to distributor</td>
        <td>
	<input type="checkbox" name="product_question_send_to_distributor" {if $config.product_question_email.product_question_send_to_distributor eq "Y"} checked="checked"{/if} />
	</td>
</tr>

<tr>
        <td class='FormButton'>Email product question to our customer service</td>
        <td>
	<input type="checkbox" name="product_question_send_to_customer_service" {if $config.product_question_email.product_question_send_to_customer_service eq "Y"} checked="checked"{/if} />
	</td>
</tr>

<tr>
        <td align="center" colspan = "2"><br />
        <input type="submit" value="{$lng.lbl_save|strip_tags:false|escape}" />
        </td>
</tr>

</table>


{else}

{if $option eq "Google_Checkout"}
{$lng.txt_gcheckout_setup_note|substitute:"callback_url":$gcheckout_callback_url}
<br />
<br />
{include file="modules/Google_Checkout/gcheckout_requirements.tpl"}

{/if}
{if $option eq "Image_Verification"}
{include file="modules/Image_Verification/spambot_requirements.tpl"}
{/if}


{if $option eq "SEO"}
  {$lng.txt_clean_url_htaccess_info|substitute:"clean_url_htaccess":$clean_url_htaccess|substitute:"htaccess":$clean_url_htaccess_path|substitute:"clean_url_test_url":$clean_url_test_url}<br />
{/if}


{if $option eq "XPayments_Connector"}
  {include file="modules/XPayments_Connector/config_recommends.tpl"}
{/if}

<table cellpadding="3" cellspacing="1" width=100%>

{assign var="first_row" value=1}

{section name=cat_num loop=$configuration}

{assign var="opt_comment" value="opt_`$configuration[cat_num].name`"}
{assign var="opt_label_id" value="opt_`$configuration[cat_num].name`"}

{if $configuration[cat_num].type eq "separator"}

<tr><td colspan="3" class="TableSeparator">

{if $configuration[cat_num].name ne "po_sep" && $configuration[cat_num].name ne "sep6" && $configuration[cat_num].name ne "sep61"}
{if $first_row eq 0}
<br />
{/if}
<br />
{/if}

{if $lng.$opt_comment ne ""}
	{if $opt_comment ne "opt_sep6"}
		{$lng.$opt_comment}
	{/if}
{elseif $configuration[cat_num].comment}
	{$configuration[cat_num].comment}
{else}
	<hr />
{/if}

{if $configuration[cat_num].name ne "po_sep" && $configuration[cat_num].name ne "sep6" && $configuration[cat_num].name ne "po_sep1"}
<br /><br />
{/if}

</td></tr>
{assign var="cycle_name" value=$configuration[cat_num].name}

{else}


{* --- *}
{if !($option eq "Company" && ($configuration[cat_num].name eq "company_name" || $configuration[cat_num].name eq "company_website" || $configuration[cat_num].name eq "cidev_keywords" || $configuration[cat_num].name eq "cidev_description" || $configuration[cat_num].name eq "start_year" || $configuration[cat_num].name eq "cidev_top_header_code" || $configuration[cat_num].name eq "cidev_header_code" || $configuration[cat_num].name eq "cidev_main_page_code" || $configuration[cat_num].name eq "cidev_footer_code" || $configuration[cat_num].name eq "cidev_yandex_code_number" || $configuration[cat_num].name eq "cidev_ga_code_number" || $configuration[cat_num].name eq "cidev_google_adwords" || $configuration[cat_num].name eq "opt_order_prefix" || $configuration[cat_num].name eq "search_products_unique_id" || $configuration[cat_num].name eq "transfer_to_gcs_if_sku_search_null" || $configuration[cat_num].name eq "newsletter_email" || $configuration[cat_num].name eq "brands_columns" || $configuration[cat_num].name eq "storefront_columns" || $configuration[cat_num].name eq "show_seed_cats" || $configuration[cat_num].name eq "search_all_website_show" || $configuration[cat_num].name eq "shop_closed" || $configuration[cat_num].name eq "cidev_tracking_code"))}

{* --- *}

{if $configuration[cat_num].name eq "po_faxage_operator_email"}
<tr>
        <td colspan="3">{$lng.txt_po_options_notes}<br /></td>
</tr>
{/if}

{if $configuration[cat_num].name eq "po_missing_copy_to_email"}
<tr>
        <td colspan="3">{$lng.txt_po_missing_information}<br /></td>
</tr>
{/if}


{if $configuration[cat_num].name eq "realtime_shipping"}

<tr><td colspan="3">
{$lng.txt_rate_estimation_note}<br /><br />
</td>
</tr>
{elseif $configuration[cat_num].name eq "fancy_cache"}

<tr>
	<td colspan="3"><br /><br />{$lng.txt_fancy_cache_note}<br /></td>
</tr>

{/if}

{if $configuration[cat_num].name eq "intershipper_username" or $configuration[cat_num].name eq "USPS_servername" or $configuration[cat_num].name eq "UPS_username" or $configuration[cat_num].name eq "CPC_merchant_id" or $configuration[cat_num].name eq "ARB_id" or $configuration[cat_num].name eq "dhl_siteid"}
<tr>
<td colspan="3">
{if $configuration[cat_num].name eq "intershipper_username"}
{$lng.txt_intershipper_account_note}
{elseif $configuration[cat_num].name eq "USPS_servername"}
{$lng.txt_usps_account_note}
{elseif $configuration[cat_num].name eq "CPC_merchant_id"}
{$lng.txt_canadapost_account_note}
{elseif $configuration[cat_num].name eq "ARB_id"}
{$lng.txt_airborne_account_note}
{elseif $configuration[cat_num].name eq "dhl_siteid"}
{$lng.txt_dhl_account_note}
{/if}
<br /><br /></td>
</tr>
{/if}

{if $cols_count eq "1"}
{assign var="bgcolor" value=""}
{assign var="cols_count" value=""}
{else}
{assign var="bgcolor" value="class=''"}
{assign var="cols_count" value="1"}
{/if}

{cycle name=$cycle_name values=" class='TableSubHead', " assign="row_style"}

<tr>
	<td width="3%">&nbsp;</td>
	<td {$row_style} width="37%">
{if $configuration[cat_num].type eq "checkbox"}
<label for="{$opt_label_id}">
{/if}
{if $lng.$opt_comment}{$lng.$opt_comment}{else}{$configuration[cat_num].comment}{/if}:
{if $configuration[cat_num].type eq "checkbox"}
</label>
{/if}
	</td>
	<td {$row_style} width="60%">

{assign var="prefix" value=false}

{if $configuration[cat_num].name eq "default_country" || $configuration[cat_num].name eq "location_country"}
	<select name="{$configuration[cat_num].name}" id="{$configuration[cat_num].name}">
{section name=country_idx loop=$countries}
		<option value="{$countries[country_idx].country_code}"{if $countries[country_idx].country_code eq $configuration[cat_num].value} selected="selected"{/if}>{$countries[country_idx].country}</option>
{/section}
	</select>
{assign var="prefix" value=$configuration[cat_num].name|regex_replace:"/_country$/":""}

{elseif $configuration[cat_num].name eq "location_state" || $configuration[cat_num].name eq "default_state"}
{if $configuration[cat_num].name eq "location_state"}
{assign var="country" value=$config.Company.location_country}
{else}
{assign var="country" value=$config.General.default_country}
{/if}
{include file="main/states.tpl" states=$states name=$configuration[cat_num].name default=$configuration[cat_num].value default_country=$country}
{assign var="prefix" value=$configuration[cat_num].name|regex_replace:"/_state$/":""}
{assign_ext var="state_values[`$prefix`]" value=$configuration[cat_num].value}

{elseif $configuration[cat_num].name eq "date_format"}
	<select name="{$configuration[cat_num].name}">
{section name=df loop=$date_formats}
		<option value="{$date_formats[df]}"{if $configuration[cat_num].value eq $date_formats[df]} selected="selected"{/if}>{$gmnow|date_format:$date_formats[df]} ({$date_formats_alt[df]})</option>
{/section}
	</select>

{elseif $configuration[cat_num].name eq "time_format"}
	<select name="{$configuration[cat_num].name}">
{section name=df loop=$time_formats}
		<option value="{$time_formats[df]}"{if $configuration[cat_num].value eq $time_formats[df]} selected="selected"{/if}>{$gmnow|date_format:$time_formats[df]|default:$lng.lbl_none}</option>
{/section}
	</select>

{elseif $configuration[cat_num].name eq "blowfish_enabled" && $configuration[cat_num].value eq "Y" && $is_merchant_password ne "Y"}
{$lng.lbl_enabled}<input type="hidden" name="{$configuration[cat_num].name}" value='{$configuration[cat_num].value}' />
	</td>
</tr>

<tr>
<td colspan="2"><font class="ErrorMessage">{$lng.txt_no_disable_blowfish}</font></td>
</tr>

{elseif $option eq "Logging" and $configuration[cat_num].name|regex_replace:"/_.*/":"" eq "log"}
<select name="{$configuration[cat_num].name}">
<option value="N"{if $configuration[cat_num].value eq "N"} selected="selected"{/if}>{$lng.lbl_log_act_nothing}</option>
<option value="L"{if $configuration[cat_num].value eq "L"} selected="selected"{/if}>{$lng.lbl_log_act_log}</option>
<option value="E"{if $configuration[cat_num].value eq "E"} selected="selected"{/if}>{$lng.lbl_log_act_email}</option>
<option value="LE"{if $configuration[cat_num].value eq "LE"} selected="selected"{/if}>{$lng.lbl_log_act_log_n_email}</option>
</select>

{elseif $configuration[cat_num].name eq "default_giftcert_template"}
<select name="{$configuration[cat_num].name}">
{foreach from=$gc_templates item=gc_tpl}
<option value="{$gc_tpl|escape}"{if $configuration[cat_num].value eq $gc_tpl} selected="selected"{/if}>{$gc_tpl}</option>
{/foreach}
</select>

{elseif $configuration[cat_num].name eq "periodic_logs"}
<input type="hidden" name="periodic_logs" value="" />
<select name="periodic_logs[]" multiple="multiple" size="10">
{foreach key=log_label item=txt_label from=$periodical_logs_names}
<option value="{$log_label}"{if $periodical_log_labels.$log_label ne ""} selected="selected"{/if}>{$txt_label}</option>
{/foreach}
</select>

{elseif $option eq "Multiple_Storefronts" and $configuration[cat_num].name eq "company_website"}
<input type="text" style="width: 370px;" name="{$configuration[cat_num].name}" value="{$configuration[cat_num].value|escape:html}" /> 
&nbsp;&nbsp;<a href="{$configuration[cat_num].value}" target="_blank">{$lng.lbl_sf_website}</a>


{* ----- 
{elseif $option eq "Multiple_Storefronts" and $configuration[cat_num].name eq "cidev_tracking_code"}
<textarea name="{$configuration[cat_num].name}" cols="71" rows="5">{$configuration[cat_num].value|escape}</textarea>

{elseif $option eq "Multiple_Storefronts" and $configuration[cat_num].name eq "cidev_main_page_code"}
<textarea name="{$configuration[cat_num].name}" cols="71" rows="5">{$configuration[cat_num].value|escape}</textarea>
{elseif $option eq "Multiple_Storefronts" and $configuration[cat_num].name eq "cidev_footer_code"}
<textarea name="{$configuration[cat_num].name}" cols="71" rows="5">{$configuration[cat_num].value|escape}</textarea>
----- *}

{elseif $option eq "Multiple_Storefronts" and $configuration[cat_num].name eq "cidev_keywords"}
<textarea name="{$configuration[cat_num].name}" cols="71" rows="2">{$configuration[cat_num].value|escape}</textarea>

{* ---- *}
{elseif $option eq "Multiple_Storefronts" and $configuration[cat_num].name eq "sf_top_image_favicon"}
<span id="upload_fname_favicon">{if $storefront_info.is_image_favicon}<a href="{$catalogs.customer}/image.php?type=F&amp;id={$current_storefront}" target="_blank">{$storefront_info.image_favicon.filename|escape}</a>&nbsp;&nbsp;&nbsp;{/if}</span>
<input type="button" id="F_edit_image_favicon" value="{$lng.lbl_plus|strip_tags:false|escape}" onclick="javascript: popup_image_selection('F', '{$current_storefront}', 'edit_image_favicon');" />
<input type="file" id="file_edit_image_favicon" size="25" name="file_edit_image_favicon" onchange="javascript: $('#F_edit_image_favicon').attr('disabled', 'disabled');" />
{* ---- *}

{elseif $option eq "Multiple_Storefronts" and $configuration[cat_num].name eq "sf_top_image"}
<span id="upload_fname">{if $storefront_info.is_image}<a href="{$catalogs.customer}/image.php?type=S&amp;id={$current_storefront}" target="_blank">{$storefront_info.image.filename|escape}</a>&nbsp;&nbsp;&nbsp;{/if}</span>
<input type="button" id="S_edit_image" value="{$lng.lbl_plus|strip_tags:false|escape}" onclick="javascript: popup_image_selection('S', '{$current_storefront}', 'edit_image');" />
<input type="file" id="file_edit_image" size="25" name="file_edit_image" onchange="javascript: $('#S_edit_image').attr('disabled', 'disabled');" />

{elseif $configuration[cat_num].name eq "spambot_arrest_img_generator"}
<select name="{$configuration[cat_num].name}">
{foreach item=generator from=$img_generators}
<option value="{$generator}" {if $configuration[cat_num].value eq $generator}selected="selected"{/if}>{$generator}</option>
{/foreach}
</select>
{elseif $configuration[cat_num].type eq "numeric"}
<input type="text" size="10" name="{$configuration[cat_num].name}" value="{$configuration[cat_num].value|formatnumeric}" />

{elseif $configuration[cat_num].type eq "text"}
<input type="text" style="width: 370px;" name="{$configuration[cat_num].name}" value="{$configuration[cat_num].value|escape:html}" />

{elseif $configuration[cat_num].type eq "checkbox"}
<input type="checkbox" id="{$opt_label_id}" name="{$configuration[cat_num].name}"{if $configuration[cat_num].value eq "Y"} checked="checked"{/if} />

{elseif $configuration[cat_num].type eq "textarea"}
<textarea 

{if $configuration[cat_num].name eq "thank_you_message_body" || $configuration[cat_num].name eq "po_instructions" || $configuration[cat_num].name eq "po_missing_instructions" || $configuration[cat_num].name eq "reference_text" || $configuration[cat_num].name eq "signature" || $configuration[cat_num].name eq "outside_sf_localization_warning" || $configuration[cat_num].name eq "po_entry_dashboard_text"}
class="new_editor" rows="30" cols="60"
{/if}

 name="{$configuration[cat_num].name}" cols="71" rows="5">
{if $configuration[cat_num].name eq "thank_you_message_body" || $configuration[cat_num].name eq "po_instructions"}
{$configuration[cat_num].value|replace:"\n":"<br />"}
{else}
{$configuration[cat_num].value|escape:html}
{/if}
</textarea>

{elseif ($configuration[cat_num].type eq "selector" || $configuration[cat_num].type eq "multiselector") && $configuration[cat_num].variants ne ''}
{if $configuration[cat_num].type eq "multiselector"}
<select name="{$configuration[cat_num].name}[]" multiple="multiple" size="5">
{else}
<select name="{$configuration[cat_num].name}"{if $configuration[cat_num].auto_submit} onchange="javascript: document.processform.submit()"{/if}>
{/if}
{foreach from=$configuration[cat_num].variants item=vitem key=vkey}
	<option value="{$vkey}"{if $vitem.selected} selected="selected"{/if}>{$vitem.name}</option>
{/foreach}
</select>
{/if}

{if $prefix ne ''}
{if $dynamic_states.$prefix > 0}
{math assign="next" equation="x+1" x=$dynamic_states.$prefix}
{assign_ext var="dynamic_states[`$prefix`]" value=$next}
{else}
{assign_ext var="dynamic_states[`$prefix`]" value=1}
{/if}
{/if}
</td>
</tr>


{* --- *}
{/if}
{* --- *}

{if $configuration[cat_num].name eq "po_missing_instructions"}
<tr>
        <td colspan="3"><br /></td>
</tr>
{/if}


{/if}

{assign var="first_row" value=0}

{/section}

{if $dynamic_states ne '' && $js_enabled eq 'Y' && $config.General.use_js_states eq 'Y'}
<tr style="display: none;">
<td>
{include file="change_states_js.tpl"}
{foreach from=$dynamic_states item=cnt key=name}
{if $cnt eq 2}
{include file="main/register_states.tpl" state_name="`$name`_state" country_name="`$name`_country" state_value=$state_values.$name}
{/if}
{/foreach}

</td>
</tr>
{/if}
{if $option eq "Product_Page"}
	{include file="admin/main/product_page_options.tpl"}
{/if}
<tr>
<td colspan="3"><br /><br />
<input type="submit" value=" {$lng.lbl_save|strip_tags:false|escape} "  />
</td>
</tr>

</table>

{if $option ne "User_Profiles" && $option ne "Contact_Us" && $option ne "Search_products" && $option ne "Templates_OrderRelatedMessages" && $option ne "Request_availability_options" && $option ne "Fraud_check" && $option ne "Reconciliation" && $option ne "currently_assigned_to_statuses" && $option ne "Attention_tag_options" && $option ne "Product_classification" && $option ne "OTRS_options" && $option ne "Order_page_permissions" && $option ne "Inquiries_options" && $option ne "Supplier_feeds" && $option ne "PBX_options" && $option ne "Attention_tags_invoices"}
</form>
{/if}

{if $option eq "Shipping" && $is_realtime}

<hr />

<h3>{$lng.lbl_test_realtime_calculation}</h3>

{$lng.txt_test_realtime_calculation_text}

<br /><br />

<form action="test_realtime_shipping.php" target="_blank">

{$lng.lbl_package_weight} <input type="text" name="weight" value="1" /> <input type="submit" value="{$lng.lbl_test|strip_tags:false|escape}" />

</form>

{elseif $option eq "Security"}

<hr />

<h3>{$lng.lbl_test_data_encryption}</h3>

<a href="test_pgp.php">{$lng.lbl_test_data_encryption_link}</a>

{elseif $option eq "XPayments_Connector"}

  {include file="modules/XPayments_Connector/config_bottom.tpl"}

{/if}

<br />

{/if}

{if $current_storefront ne '0'}
<script type="text/javascript">
{strip}
var ds_config = new Array(
{foreach from=$domain_specific_config[$option] item=v key=k name="f_dc_config"}
	{if $v eq 'Y'}
		{if !$smarty.foreach.f_dc_config.first}, {/if}'{$k}'
	{/if}
{/foreach}
);
{/strip}

var txt_domain_specific_option = '{$lng.txt_domain_specific_option}';

{literal}
if (ds_config.length > 0) {
	for (i = 0; i < ds_config.length; i++) {
		$('input[name="' + ds_config[i] + '"]').attr('disabled', 'disabled');
		$('input[name="' + ds_config[i] + '"]').after('<br />' + txt_domain_specific_option);
	}
}
{/literal}
</script>
{/if}
{/if} {* /if $active_module.Multiple_Storefronts && !$configuration*}

{/capture}

{if $option eq 'Multiple_Storefronts'}
    {assign var=capture_title value=$lng.lbl_sf_properties}
{else}
    {assign var=capture_title value=$lng.lbl_general_settings}
{/if}

{include file="dialog.tpl" title=$capture_title content=$smarty.capture.dialog extra='width="100%"'}
{if $additional_config}{include file=$additional_config}{/if}

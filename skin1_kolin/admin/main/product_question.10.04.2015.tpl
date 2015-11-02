{include file="change_states_js.tpl"}
<script type="text/javascript" language="JavaScript 1.2" src="{$SkinDir}/lib/jqueryui/jquery-ui.custom.min.js"></script>
<script src="{$SkinDir}/tinymce/js/tinymce/tinymce.min.js" type="text/javascript"></script>

<script type="text/javascript">
//<![CDATA[
{literal}

tinymce.init({
    selector: "textarea",
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

<script type="text/javascript">
//<![CDATA[
$(function() {ldelim}
  $('#question_tabs-container').tabs();
{rdelim});
//]]>
</script>


 <table align="center">
 <tr>
 <td>
 {include file="page_title.tpl" title="Product question about"}
 </td>

 <td style="font-size: 15px; {* font-weight: bold; *}">
        <a target="_blank" style="color: #140BFC;{* text-decoration: none;*}" href="{$product_info.customer_url}">{$product_info.productcode}</a>
 </td>

{*
 {assign var="ticket_resolver_link" value="http://helpdesk.s3stores.com/otrs/"}
*}

 {if $ticket_resolver_link ne ""}
 <td style="font-size: 15px; {* font-weight: bold; *}">
        / <a target="_blank" style="color: #140BFC;{* text-decoration: none;*}" href="{$ticket_resolver_link}">OTRS ticket{if $ticket_resolver_messages ne ""} ({$ticket_resolver_messages}){/if}</a>
 </td>
 {/if}
 </tr>
 </table>


{capture name=dialog}

<form name="sqform" action="product_question.php" method="post">
<input type="hidden" name="mode" value="" id="mode" />
<input type="hidden" name="id" value="{$product_question.id}" />

<table border="0" width="100%" cellpadding="3" cellspacing="1">
<tr>
<td colspan="3"><B>Product question status:</B>
	{include file="admin/main/product_question_status.tpl" status=$product_question.status mode="select" name="status" extra="" empty="N"}
</td>
</tr>

<tr>
<td valign="top" width="48%">
        {include file="main/subheader.tpl" title="Product question"}
        <textarea name="question" cols="60" rows="10">{$product_question.question|escape:"html"}</textarea>
</td>
<td></td>
<td valign="top" width="48%">
        {include file="main/subheader.tpl" title="Product answer"}
        <textarea name="answer" cols="60" rows="10">{$product_question.answer|escape:"html"}</textarea>
</td>
</tr>

<tr>

<td valign="top" width="48%">

	<table border="0" width="100%" cellpadding="3" cellspacing="1">

        <tr>
        <td colspan="2">
        {include file="main/subheader.tpl" title="Product information"}
        </td>
        </tr>

        <tr>
        <td><B>Product thumbnail:</B></td>
        <td>
<a href="{$product_info.customer_url}" target="_blank">
{include file="product_thumbnail.tpl" productid=$product_info.productid image_x=$product_info.tmbn_x|default:$config.Appearance.thumbnail_width image_y=$product_info.tmbn_y product=$product_info.product tmbn_url=$product_info.tmbn_url}
</a>
        </td>
        </tr>

	<tr>
	<td><B>Product name:</B></td>
	<td><a href="{$product_info.customer_url}" title="" style="color: #3A3AFF;" target="_blank">{$product_info.product}</a></td>
	</tr>

        <tr>
        <td><B>Product SKU:</B></td>
        <td><a href="product_modify.php?productid={$product_info.productid}" style="color: #3A3AFF;" target="_blank">{$product_info.productcode}</a></td>
        </tr>

        <tr>
        <td><B>Product MPN:</B></td>
        <td>{if $product_info.d_website_search_for_sku_url ne ""}<a href="{$product_info.d_website_search_for_sku_url|replace:"---mpn---":"$mpn"}" style="color: #3A3AFF;" target="_blank">{/if}{$product_info.mpn}{if $product_info.d_website_search_for_sku_url ne ""}</a>{/if}</td>
        </tr>

        <tr>
        <td><B>Product distributor:</B></td>
        <td><a href="manufacturers.php?manufacturerid={$product_info.manufacturerid}&distributor_section=16" style="color: #3A3AFF;" target="_blank">{$product_info.manufacturer}</a></td>
        </tr>

        <tr>
        <td><B>Product brand:</B></td>
        <td><a href="brands.php?brandid={$product_info.brandid}" style="color: #3A3AFF;" target="_blank">{$product_info.brand}</a></td>
        </tr>

	</table>
</td>
<td>&nbsp;</td>
<td valign="top" width="48%">

        <table border="0" width="100%" cellpadding="3" cellspacing="1">

        <tr>
        <td colspan="2">
        {include file="main/subheader.tpl" title="Customer contact information"}
        </td>
        </tr>

        <tr>
        <td><B>Phone:</B></td>
        <td><input type="text" name="phone" size="18" value="{$product_question.phone|escape}" style="width: 98%;" /></td>
        </tr>

        <tr>
        <td><B>Email:</B></td>
        <td><input type="text" name="email" size="18" value="{$product_question.email|escape}" style="width: 98%;" /></td>
        </tr>


	<tr><td colspan="2"><br />{include file="main/subheader.tpl" title="Shipping address"}</td></tr>

        <td><B>Full name:</B></td>
        <td><input type="text" name="name" size="18" value="{$product_question.name|escape}" style="width: 98%;" /></td>
        </tr>

        <tr>
        <td><B>Company:</B></td>
        <td><input type="text" name="company" size="18" value="{$product_question.company|escape}" style="width: 98%;" /></td>
        </tr>

        <td><B>Address:</B></td>
        <td><input type="text" name="address" size="18" value="{$product_question.address|escape}" style="width: 98%;" /></td>
        </tr>

        <tr>
        <td><B>Address (line 2):</B></td>
        <td><input type="text" name="address2" size="18" value="{$product_question.address2|escape}" style="width: 98%;" /></td>
        </tr>

        <tr>
        <td><B>City:</B></td>
        <td><input type="text" name="city" size="18" value="{$product_question.city|escape}" style="width: 98%;" /></td>
        </tr>

	<tr>
	<td><B>{$lng.lbl_country}:</B></td>
	<td nowrap="nowrap">
	<select name="country" id="country">
	{section name=country_idx loop=$countries}
	<option value="{$countries[country_idx].country_code}"{if $product_question.country eq $countries[country_idx].country_code} selected="selected"{elseif $countries[country_idx].country_code eq $config.General.default_country and $product_question.country eq ""} selected="selected"{/if}>{$countries[country_idx].country|amp}</option>
	{/section}
	</select>
	</td>
	</tr>

	<tr>
	<td><B>{$lng.lbl_state}:</B></td>
	<td nowrap="nowrap">
	{include file="main/states.tpl" states=$states name="state" default=$product_question.state default_country=$product_question.country country_name="country"}
	</td>
	</tr>

	<tr style="display: none;">
	<td colspan="2">
	{include file="main/register_states.tpl" state_name="state" country_name="country" county_name="county" state_value=$product_question.state county_value=""}
	</td>
	</tr>

	<tr>
	<td><B>{$lng.lbl_zip_code}:</B></td>
	<td nowrap="nowrap">
	<input type="text" id="zipcode" name="zipcode" size="32" maxlength="32" value="{$product_question.zipcode}" />
	</td>
	</tr>

        </table>
</td>

</tr>

<tr>
        <td>
<input type="button" value="{$lng.lbl_update|strip_tags:false|escape}" onclick="javascript: submitForm(this, 'update');" />
	</td>
        <td colspan="2" align="right">

{if $product_question.answer ne ""}
	<input type="button" value="Transfer Q&A on product page" onclick="javascript: submitForm(this, 'transfer');" />

	<input type="button" value="Transfer and publish Q&A on product page" onclick="javascript: submitForm(this, 'transfer_and_publish');" />
{/if}

	<input type="hidden" name="add_products" value="{$product_info.productcode}=1;" />
	<input type="button" value="Generate Queued order" onclick="javascript: submitForm(this, 'generate_queued_order');" />
        </td>
</tr>
</table>

<br />
<br />

<div id="question_tabs-container">
  <ul>
    <li><a href="#question_tabs-question">Send question to distributor/brand</a></li>
    <li><a href="#question_tabs-answer">Send answer to customer</a></li>
  </ul>

  <div id="question_tabs-answer">
   {include file="main/subheader.tpl" title="Send answer to customer"}
   <B>{$lng.lbl_from}:</B><br />
   <input type="text" name="from" value="{$config.product_question_email.product_question_from}" readonly="readonly" style="width: 80%;" /><br /><br />
   <B>{$lng.lbl_to}:</B><br />
   <input type="text" name="to" value="{$product_question.email}" style="width: 80%;" /><br /><br />
   <B>Subject line:</B><br />
   <input type="text" name="product_answer_subject_line" value="{$product_info.product_answer_subject_line}" style="width: 80%;" /><br /><br />
   <B>{$lng.lbl_message_body}:</B><br />
   <textarea rows="30" cols="60" id="product_answer_message_body" name="product_answer_message_body" style="width: 80%;" class="new_editor">{$product_info.product_answer_message_body|replace:"\n":"<br />"}</textarea>
   <br />
   <div align="left">
    <input type="button" value="Send answer to customer" onclick="javascript: submitForm(this, 'send_answer_to_customer');" />
   </div>
  </div>

  <div id="question_tabs-question">
   {include file="main/subheader.tpl" title="Send question to distributor/brand"}
   <B>{$lng.lbl_from}:</B><br />
   <input type="text" name="from" value="{$config.product_question_email.product_question_from}" readonly="readonly" style="width: 80%; height: 80%;" /><br /><br />
   <B>{$lng.lbl_to}:</B>
   <select name="to">
	<option value="{$product_info.distributor_email}">Product distributor contact ({if $product_info.distributor_email ne ""}{$product_info.distributor_email}{else}not specified{/if})</option>
	<option value="{$product_info.brand_email}">Product brand contact ({if $product_info.brand_email}{$product_info.brand_email}{else}not specified{/if})</option>
   </select>
   <br /><br />
   <B>Subject line:</B><br />
   <input type="text" name="product_question_subject_line_to_distr" value="{$product_info.product_question_subject_line_to_distr}" style="width: 80%;" /><br /><br />
   <B>{$lng.lbl_message_body}:</B><br />
   <textarea rows="30" cols="60" id="product_question_message_body_to_distr" name="product_question_message_body_to_distr" style="width: 80%;" class="new_editor">{$product_info.product_question_message_body_to_distr|replace:"\n":"<br />"}</textarea>
   <br />
   <div align="left">
    <input type="button" value="Send question to distributor/brand" onclick="javascript: submitForm(this, 'send_question_to_distr_brand');" />
   </div>
  </div>
</div>

</form>

{/capture}
{include file="dialog.tpl" title="Product question communication" content=$smarty.capture.dialog extra='width="100%"'}


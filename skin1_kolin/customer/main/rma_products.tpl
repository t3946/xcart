
<form action="{if $usertype ne "C"}order.php{else}rma_request.php{/if}" method="post" name="rma_request_form2_{$rma_id}" enctype="multipart/form-data">


{if $usertype ne "C"}
<table width="98%" align="center">

<tr>
<td>
<B>RMA request date:</B> {$rma_info.date|date_format:'%d-%b-%Y&nbsp; %H:%M'}
</td>
<td align="right">
<B>Status:</B> {$rma_statuses[$rma_info.status].name}
</td>
</tr>

<tr>
<td colspan="2"><B>Zip/Postal code:</B> {$rma_info.zipcode}</td>
</tr>

<tr>
<td colspan="2"><B>Email:</B> {$rma_info.email}</td>
</tr>

</table>
<br />
{/if}


<input type="hidden" name="mode" value="" id="mode2" />

{if $usertype ne "C"}
<input type="hidden" name="orderid" value="{$orderid}" />
{else}
<input type="hidden" name="o" value="{$o}" />
{/if}

<input type="hidden" name="rma_id" value="{$rma_id}" />


<table width="98%" align="center">
{foreach from=$order.shipping_groups item=v key=m_id}
{if $m_id gt 0}

<tr>
  <td colspan="4">
{assign var="subheader_name" value="`$v.group_name` items"}
{include file="main/subheader.tpl" title=$subheader_name}
  </td>
</tr>

<tr style="height: 20px; text-align: center; text-transform: uppercase; font-weight: bold; background-color: #cccccc">
  <td>SKU</td>
  <td>Product Name</td>
  <td>Return QTY</td>
  <td>{if $usertype eq "C"}I{else}You{/if} would like to</td>
</tr>

{foreach from=$v.products item=product key=prod_num}
 <tr{cycle values=", class='TableSubHead'" name="cycle_`$m_id`"}>
   <td>{$product.productcode}</td>
   <td><a target="_blank" href="{if $usertype ne "C"}../{/if}product.php?productid={$product.productid}">{$product.product}</a></td>
   <td align="center">

<input type="hidden" name="post_rma[products][{$prod_num}][productid]" value="{$product.productid}" />

{math equation="x+y" assign="mq" x=$product.amount y=1}
<select name="post_rma[products][{$prod_num}][amount]">
{section name=quantity loop=$mq start=0 step=1}
<option value="{%quantity.index%}" {if $rma_info ne "" && $rma_info.products[$prod_num].amount eq %quantity.index%}selected="selected"{/if}>{%quantity.index%}</option>
{/section}
</select>

   </td>
   <td align="center">
<select name="post_rma[products][{$prod_num}][would_like]">
<option value=""></option>
{foreach from=$rma_would_like_variants item=v_would_like key=k_would_like}
<option value="{$v_would_like.code}" {if $rma_info ne "" && $rma_info.products[$prod_num].would_like eq $v_would_like.code}selected="selected"{/if}>{$v_would_like.name}</option>
{/foreach}
</select>
   </td>
 </tr>
 {/foreach}

<tr><td colspan="4">&nbsp;</td></tr>
{/if}
{/foreach}

<tr><td colspan="4"><br /><I>Please explain why you would like to return products for a refund or replace them with the same or different products:</I></td></tr>

<tr>
  <td colspan="4" align="center">
   <textarea style="width: 99%" name="post_rma[explanation]" cols="60" rows="4">{if $rma_info ne "" && $rma_info.explanation ne ""}{$rma_info.explanation}{/if}</textarea>
  </td>
</tr>

<tr>
  <td colspan="4">

{if $rma_info.images ne ""}
	<br />
	<I>Uploaded images:</I><br />

	{foreach from=$rma_info.images item=v_img key=k_img}
		<a href="{if $usertype ne "C"}.{/if}{$v_img.image_path}" target="_blank">{$v_img.filename}</a><br />
	{/foreach}

{/if}

  </td>
</tr>

<tr>
  <td colspan="4" valign="top">

{if $rma_info.images eq ""}
<br />
{/if}

<table cellpadding="0" cellspacing="0">
<tr>
<td valign="top">
        <div style="padding-top: 7px;"><I>Please attach product images to speed up the RMA process:</I>&nbsp;&nbsp;</div>
</td>
<td valign="top">

<script type="text/javascript">
<!--
        p_f_row_max_index = 1000;

        function p_f_add_upload_row(multi_id, rma_id) {ldelim}
                p_f_row_max_index = p_f_row_max_index + 1;
                var tr = document.getElementById(rma_id+'_p_f_upload_row_'+multi_id);
                var new_row = tr.parentNode.parentNode.insertRow(tr.rowIndex+1);
                new_row.id = rma_id+'_p_f_upload_row_'+p_f_row_max_index;
                var td;
/*
		td = new_row.insertCell(-1);
                td.innerHTML = 'Attach file:';
*/
                td = new_row.insertCell(-1);
                td.innerHTML = "<input type=\"file\" size=\"25\" name=\""+rma_id+"userfile_D_"+p_f_row_max_index+"\" id=\""+rma_id+"userfile_"+p_f_row_max_index+"\" />";
                td = new_row.insertCell(-1);
                td.innerHTML = "<a href=\"javascript: void(0);\" onclick=\"javascript: p_f_add_upload_row("+p_f_row_max_index+","+rma_id+");\"><img src=\"{$ImagesDir}/plus.gif\" alt=\"{$lng.lbl_add_row|escape:'javascript'}\" /></a>&nbsp;<a href=\"javascript: void(0);\" onclick=\"javascript: p_f_remove_upload_row("+p_f_row_max_index+","+rma_id+");\"><img src=\"{$ImagesDir}/minus.gif\" alt=\"{$lng.lbl_remove_row|escape:'javascript'}\" /></a>";
        {rdelim}


        function p_f_remove_upload_row(multi_id,rma_id) {ldelim}
                var tr = document.getElementById(rma_id+'_p_f_upload_row_'+multi_id);
                tr.parentNode.parentNode.deleteRow(tr.rowIndex);
        {rdelim}
-->
</script>


 <table cellpadding="4" cellspacing="0" align="left">

 <tr id="{$rma_id}_p_f_upload_row_1000">
{* <td>Attach file:</td> *}
 <td>
<input type="file" size="25" name="{$rma_id}_userfile_D_1000" id="{$rma_id}_userfile_1000" {* style="border: solid 1px #b7b7b7;" *} />
 </td>
 <td><a href="javascript: void(0);" onclick="javascript: p_f_add_upload_row(1000, {$rma_id});"><img src="{$ImagesDir}/plus.gif" alt="{$lng.lbl_add|escape}" /></a></td>
 </tr>

 </table>

</td>
</tr>
</table>

  </td>
</tr>

{if $usertype eq "C"}
<tr>
  <td align="center" colspan="4">
<br />
<input type="button" value="Submit request to RMA department" onclick="javascript: submitForm(this, 'to_rma_department');" />
  </td>
</tr>

{else}

<tr>
  <td align="left" colspan="4">

<br />
<I>I will now send email to</I> <input type="text" name="post_rma[order_email]" value="{$rma_info.order_email|default:$order.email}" />

{assign var="rma_form_link" value="<a href='../rma_request.php?step=2&o=`$crypt_orderid`&rma_id=`$rma_id`&prefilled=Y' target='_blank' style='color: blue;'>link</a>"}

<br />
{$lng.lbl_back_end_RMA_ending|substitute:"rma_form_link":$rma_form_link}
<br />
<br />

<table width="100%">
<tr>
<td width="33%" valign="top">
<input type="button" value="Update RMA request" onclick="javascript: submitForm(this, 'rma_update_request');" />
</td>

<td width="*" align="center" valign="top">
<input type="button" value="Send email to customer" onclick="javascript: submitForm(this, 'rma_send_email_to_customer');" {if $rma_info.status eq "3"}disabled="disabled" style="backgroud-color: #cccccc; border: solid 1px red;"{else}style="border: solid 1px green;"{/if} />
<br />
{if $rma_info.status eq "3"}<I>Customer has already submitted this RMA request.</I>{/if}
</td>

<td align="right" valign="top">
<input type="button" value="Delete RMA request" onclick="javascript: submitForm(this, 'delete_rma_request');" />
</td>
</tr>
</table>

  </td>
</tr>

{/if}

</table>

</form>

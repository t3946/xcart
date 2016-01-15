<br />

{if $product_questions eq "" && $search_data.PQ_OTRS_filter eq "true"}
<input type="checkbox" value="true" id="id_PQ_OTRS_filter" name="PQ_OTRS_filter" {if $search_data.PQ_OTRS_filter eq "true"}checked="checked"{/if} onclick="javascript: self.location='product_question_search.php?mode=search&status=all&from_dashboard=Y&PQ_OTRS_filter='+$('#id_PQ_OTRS_filter').is(':checked')+'';" /> Don’t show product questions with <B>PQ and order status = ‘Closed’</B> AND <B>Publication status ≠ ‘Unpublished’</B> AND <B>New OTRS message</B> = 'N'
{/if}

{if $product_questions ne ""}

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

{*
<form name="sqform" action="product_question_search.php" method="post">
<input type="hidden" name="mode" value="" id="mode" />
*}

<input type="checkbox" value="true" id="id_PQ_OTRS_filter" name="PQ_OTRS_filter" {if $search_data.PQ_OTRS_filter eq "true"}checked="checked"{/if} onclick="javascript: self.location='product_question_search.php?mode=search&status='+$('#id_status').val()+'&from_dashboard=Y&PQ_OTRS_filter='+$('#id_PQ_OTRS_filter').is(':checked')+'';" /> Don’t show product questions with <B>PQ and order status = ‘Closed’</B> AND <B>Publication status ≠ ‘Unpublished’</B> AND <B>New OTRS message</B> = 'N'

<table border="0" width="100%" cellpadding="3" cellspacing="1">
<tr class='TableSubHead'>
<td align="center"><br /><B>PQ #</B></td>
<td align="center"><br /><B>Date</B></td>
<td align="center"><br /><B>Product SKU</B></td>
{* <td><B>Product name</B></td> *}
{* <td><B>Product question</B></td> *}
{*
<td><B>Email</B></td>
<td><B>Phone</B></td>
*}
<td align="center">

    <select id="id_status" name="status" onchange="javascript: self.location='product_question_search.php?mode=search&status='+this.value+'&from_dashboard=Y';">
        <option value="all">All</option>

    {foreach from=$product_question_statuses key="code" item="o_status"}
                <option value="{$code}"{if $search_data.status eq $code} selected="selected"{/if}>{$o_status}</option>
    {/foreach}
    </select>

    <br />
    <B>PQ and order status</B>

</td>
<td align="center"><br /><B>Publication status</B></td>
<td align="center"><br /><B>New OTRS message</B></td>
<td align="center"><br /><B>Orderid</B></td>
</tr>

{foreach from=$product_questions item=v key=k}

   <tr {cycle values=", class='TableSubHead'"}>
	<td nowrap="nowrap" align="center"><a href="product_question.php?id={$v.id}" target="_blank">{$v.id}</a></td>
	<td nowrap="nowrap" align="center"><a href="product_question.php?id={$v.id}" target="_blank">{$v.date|date_format:'%d-%b-%Y'}</a></td>
	<td nowrap="nowrap"><a href="product_question.php?id={$v.id}" target="_blank">{$v.productcode}</a></td>
{*	<td>{$v.product}</td> *}
{*	<td>{$v.question}</td> *}
{*
	<td>{$v.email}</td>
	<td>{$v.phone}</td>
*}
	<td nowrap="nowrap">
		<a href="product_question.php?id={$v.id}" target="_blank">{include file="admin/main/product_question_status.tpl" status=$v.status mode="static"}</a>
	</td nowrap="nowrap">
	<td align="center">{include file="admin/main/product_question_publication_statuses.tpl" status=$v.publication_status mode="static"}</td>
	<td align="center">{$v.new_otrs_email}</td>
	<td align="center">{if $v.orderid ne "0"}<a href="order.php?orderid={$v.orderid}">{$v.orderid}</a>{/if}</td>
   </tr>

{/foreach}

</table>

{*
<input type="button" value="{$lng.lbl_update|strip_tags:false|escape}" onclick="javascript: submitForm(this, 'update');" />
</form>
*}

{/capture}
{include file="dialog.tpl" title="Product questions" content=$smarty.capture.dialog extra='width="100%"'}

{else}

<form name="sqform" action="product_question_search.php" method="post">
<input type="hidden" name="mode" value="search" id="mode" />
Question: <input type="text" name="question" value="" />
<br />
<input type="submit" name="submit" value="Search" />
</form>
{/if}



<br />

<a name="Product_questions"></a>
{capture name=dialog}
{if $product_questions_arr ne ""}

<form name="pqform" action="product_modify.php" method="post">
<input type="hidden" name="mode" value="update_PQ" id="mode" />
<input type="hidden" name="productid" value="{$productid}" />


<table>

<tr class="TableHead">
<td></td>
<td>POS.</td>
<td>Product Question</td>
<td>Product Answer</td>
<td>Publish?</td>
</tr>

{foreach from=$product_questions_arr item=oProductQuestion key=k}
    {assign var="v" value=$oProductQuestion->getFields()}
<tr>

 <td>
<input type="checkbox" name="posted_data[{$v.id}][to_delete]" value="{$v.id}" />
 </td>

 <td>
<input type="text" name="posted_data[{$v.id}][order_by]" value="{$v.order_by}" size="5" />
 </td>

 <td>
<textarea class="new_editor" name="posted_data[{$v.id}][question]" cols="60" rows="10">{$v.question|escape:"html"}</textarea>
 </td>

 <td>
<textarea class="new_editor" name="posted_data[{$v.id}][answer]" cols="60" rows="10">{$v.answer|escape:"html"}</textarea>
 </td>

 <td>
<select name="posted_data[{$v.id}][answered_on_page_OR_question_published_on_page]">
<option value="answered_on_page"{if $v.answered_on_page eq "Y"} selected="selected"{/if}>Do NOT publish</option>
<option value="question_published_on_page"{if $v.question_published_on_page eq "Y"} selected="selected"{/if}>Yes, publish!</option>
</select>

<br />
<br />
<B>Customer's First name:</B>
<br />
<input type="text" name="posted_data[{$v.id}][firstname]" value="{$v.firstname}" style="width: 95%;" />


<br />
<br />
<B>Asked on:</B>
<br />
<script type="text/javascript" language="JavaScript 1.2">
<!--
{literal}
  $(function() {
    $("#posted_data_date_{/literal}{$v.id}{literal}").datepicker();
  });
{/literal}
-->
</script>
<input id="posted_data_date_{$v.id}" type="text" name="posted_data[{$v.id}][date]" value="{$v.date|date_format:'%m/%d/%Y'}" size="9" style="width: 95%;" />

<br />
<br />
<B>Answered on:</B>
<br />
<script type="text/javascript" language="JavaScript 1.2">
<!--
{literal}
  $(function() {
    $("#posted_data_answered_date_{/literal}{$v.id}{literal}").datepicker();
  });
{/literal}
-->
</script>
<input id="posted_data_answered_date_{$v.id}" type="text" name="posted_data[{$v.id}][answered_date]" value="{$v.answered_date|date_format:'%m/%d/%Y'}" size="9" style="width: 95%;" />


 </td>

</tr>
{if $oProductQuestion->getField('added_from_product_modify_page') == 'N'}
    <tr>
        <td></td>
        <td></td>
        <td>
            This Q&A has been transferred from <a style="color: #3A3AFF;" target="_blank" href="{$oProductQuestion->getProductQuestionModifyURL()}">this product question</a>.
        </td>
    </tr>
{/if}
<tr><td colspan="5"><hr /></td></tr>

{/foreach}

<tr>
<td colspan="2" align="left">
        <input type="button" value="{$lng.lbl_update|strip_tags:false|escape}" onclick="javascript: submitForm(this, 'update_PQ');" />
</td>
<td colspan="2" align="center">
        <input type="button" value="Add new PQ" onclick="javascript: submitForm(this, 'add_PQ');" />
</td>
<td align="right">
        <input type="button" value="{$lng.lbl_delete_selected|strip_tags:false|escape}" onclick="javascript: submitForm(this, 'delete_PQ');" />
</td>
</tr>

</table>

</form>

{else}
<form name="pqform" action="product_modify.php" method="post">
	<input type="hidden" name="mode" value="add_PQ" id="mode" />
	<input type="hidden" name="productid" value="{$productid}" />
	<input type="button" value="Add new PQ" onclick="javascript: submitForm(this, 'add_PQ');" />
</form>
{/if}
{/capture}
{include file="dialog.tpl" title="Product questions" content=$smarty.capture.dialog extra='width="100%"'}


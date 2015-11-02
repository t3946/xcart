<br />

{capture name=dialog}
{if $product_questions_arr ne ""}

{*
<script src="{$SkinDir}/tinymce/js/tinymce/tinymce.min.js" type="text/javascript"></script>
*}

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


<form name="pqform" action="product_modify.php" method="post">
<input type="hidden" name="mode" value="update_PQ" id="mode" />
<input type="hidden" name="productid" value="{$productid}" />


<table>

<tr class="TableHead">
<td></td>
<td>POS.</td>
<td>Product Question</td>
<td>Product Answer</td>
<td>Publish</td>
</tr>

{foreach from=$product_questions_arr item=v key=k}
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
<option value="question_published_on_page"{if $v.question_published_on_page eq "Y"} selected="selected"{/if}>Publish</option>
</select>

<br />
<br />
<B>Customer's First name:</B>
<br />
<input type="text" name="posted_data[{$v.id}][firstname]" value="{$v.firstname}" />

 </td>

</tr>
{/foreach}

<tr>
<td colspan="5">

        <input type="button" value="{$lng.lbl_delete_selected|strip_tags:false|escape}" onclick="javascript: submitForm(this, 'delete_PQ');" />
        <input type="button" value="{$lng.lbl_update|strip_tags:false|escape}" onclick="javascript: submitForm(this, 'update_PQ');" />

</td>
</tr>

</table>

</form>

{else}
no data available
{/if}
{/capture}
{include file="dialog.tpl" title="Product questions" content=$smarty.capture.dialog extra='width="100%"'}


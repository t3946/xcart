{if $smarty.get.sent ne "Y"}

{include file="main/include_js.tpl" src="main/popup_image_selection.js"}

{*
<script src="//tinymce.cachefly.net/4.0/tinymce.min.js"></script>
*}
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

{assign var="order_details_name" value="Compose message (Order # `$order.order_prefix``$order.orderid`)"}
{include file="page_title.tpl" title=$order_details_name}

{capture name=dialog}
  <form action="compose_message.php" method="post" name="compose_message_form" enctype="multipart/form-data">
  <input type="hidden" name="orderid" value="{$order.orderid}" />
  <input type="hidden" name="mode" value="send_message" />
  <input type="hidden" name="department" value="{$department}" />
  <input type="hidden" name="template_id" value="{$template_id}" />
  <input type="hidden" name="manufacturerid" value="{$manufacturerid}" />
  <input type="hidden" name="mid_templateid" value="{$mid_templateid}" />

  <div class="ProductTitle" align="center">
	Department: {$department_name} 
	{if $department eq "distributor" && $order_manufacturers[$manufacturerid].manufacturer ne ""}
		({$order_manufacturers[$manufacturerid].manufacturer})
	{/if}
  </div>

  <B>{$lng.lbl_from}:</B><br />
  <input type="text" name="from" value="{$from}" style="width: 99%;" /><br /><br />
  <B>{$lng.lbl_to}:</B><br />
  <input type="text" name="to" value="{$to}" style="width: 99%;" /><br /><br />
  <B>Subject line:</B><br />
  <input type="text" name="subject" value="{$subject}" style="width: 99%;" /><br /><br />
  <B>{$lng.lbl_message_body}:</B><br />

{*
  {include file="main/textarea_def.tpl" name="body" cols="60" rows="30" class="InputWidth" data=$body width="99%" btn_rows="30"}
*}

<textarea name="body" cols="60" rows="30">{$body|escape:"html"}</textarea>

{*
  <INPUT type="button" value="Send" onclick="disableEditor('body','body'); document.compose_message_form.submit();">
*}


<table width="100%">
<tr>
<td width="50%" valign="top">
  <input type="checkbox" name="attach_pdf_invoice" value="Y" {if $attach_pdf_invoice eq "Y"}checked="checked"{/if} /> Attach order invoice in pdf format
</td>
</tr>
<tr>
<td>
<script type="text/javascript">
<!--
        p_f_row_max_index = 1000;

        function p_f_add_upload_row(multi_id) {ldelim}
                p_f_row_max_index = p_f_row_max_index + 1;
                var tr = document.getElementById('p_f_upload_row_'+multi_id);
                var new_row = tr.parentNode.parentNode.insertRow(tr.rowIndex+1);
                new_row.id = 'p_f_upload_row_'+p_f_row_max_index;
                var td = new_row.insertCell(-1);
                td.innerHTML = 'Attach file:';
                td = new_row.insertCell(-1);
                td.innerHTML = "<input type=\"file\" size=\"25\" name=\"userfile_D_"+p_f_row_max_index+"\" id=\"userfile_"+p_f_row_max_index+"\" />";
                td = new_row.insertCell(-1);
                td.innerHTML = "<a href=\"javascript: void(0);\" onclick=\"javascript: p_f_add_upload_row("+p_f_row_max_index+");\"><img src=\"{$ImagesDir}/plus.gif\" alt=\"{$lng.lbl_add_row|escape:'javascript'}\" /></a>&nbsp;<a href=\"javascript: void(0);\" onclick=\"javascript: p_f_remove_upload_row("+p_f_row_max_index+");\"><img src=\"{$ImagesDir}/minus.gif\" alt=\"{$lng.lbl_remove_row|escape:'javascript'}\" /></a>";
        {rdelim}


        function p_f_remove_upload_row(multi_id) {ldelim}
                var tr = document.getElementById('p_f_upload_row_'+multi_id);
                tr.parentNode.parentNode.deleteRow(tr.rowIndex);
        {rdelim}
-->
</script>


 <table cellpadding="4" cellspacing="0" align="left">

 <tr id="p_f_upload_row_1000">
 <td>Attach file:</td>
 <td>
<input type="file" size="25" name="userfile_D_1000" id="userfile_1000" />
 </td>
 <td><a href="javascript: void(0);" onclick="javascript: p_f_add_upload_row(1000);"><img src="{$ImagesDir}/plus.gif" alt="{$lng.lbl_add|escape}" /></a></td>
 </tr>

 </table>

<script type="text/javascript">
<!--
 //       p_f_add_upload_row(1000);
    {literal}
    $('body').delegate('input[id^=userfile]', 'change', function() {
        id = $(this).attr('id').substring(9, 13);
        $('#plus_' + id).attr('disabled', 'disabled');
    });
    {/literal}
-->
</script>
{* ---------------------------- *}


</td>
</tr>
</table>
<br />
<br />

  <INPUT type="button" value="Send" onclick="tinyMCE.triggerSave(); document.compose_message_form.submit();">
  </form>

{/capture}
{include file="dialog.tpl" title="Compose message" content=$smarty.capture.dialog extra='width="100%"'}

{*
<script type="text/javascript">
//<![CDATA[
{literal}

function turn_Editor(){
//	disableEditor('body','body');
	enableEditor('body','body');
}

window.onload = turn_Editor();
{/literal}
//]]>
</script>
*}

{else}

<script type="text/javascript">
//<![CDATA[
window.onload = setTimeout('window.close()', 2000);
//]]>
</script>

{/if}

{* $Id: product_images_modify.tpl,v 1.26.2.2 2006/07/11 08:39:29 svowl Exp $ *}
{if $active_modules.Detailed_Product_Images ne ""}
<script type="text/javascript">
<!--
	row_max_index = 1;
	p_f_row_max_index = 1000;

	{literal}
	function copy_product_title(index) {
		$('#alt_' + index).val($('#product_name').val());
	}
	{/literal}
	
	function add_upload_row(multi_id) {ldelim}
		row_max_index = row_max_index + 1;
		var tr = document.getElementById('upload_alt_row_'+multi_id);
		var new_row = tr.parentNode.parentNode.insertRow(tr.rowIndex+1);
		new_row.id = 'upload_row_'+row_max_index;
		var td = new_row.insertCell(-1);
		td.innerHTML = '{$lng.lbl_select_file}:';
		td = new_row.insertCell(-1);
		td.innerHTML = "<input type=\"button\" id=\"plus_"+row_max_index+"\" value=\"{$lng.lbl_plus|strip_tags:false|escape}\" onclick=\"javascript: popup_image_selection('D_"+row_max_index+"', '{$product.productid}', '');\" />&nbsp;<span id=\"upload_fname_"+row_max_index+"\"></span><input type=\"file\" size=\"25\" name=\"userfile_D_"+row_max_index+"\" id=\"userfile_"+row_max_index+"\" />";
		td = new_row.insertCell(-1);
		td.innerHTML = "<a href=\"javascript: void(0);\" onclick=\"javascript: add_upload_row("+row_max_index+");\"><img src=\"{$ImagesDir}/plus.gif\" alt=\"{$lng.lbl_add_row|escape:'javascript'}\" /></a>&nbsp;<a href=\"javascript: void(0);\" onclick=\"javascript: remove_upload_row("+row_max_index+");\"><img src=\"{$ImagesDir}/minus.gif\" alt=\"{$lng.lbl_remove_row|escape:'javascript'}\" /></a>";
		new_row = tr.parentNode.parentNode.insertRow(new_row.rowIndex+1);
		new_row.id = 'upload_alt_row_'+row_max_index;
		td = new_row.insertCell(-1);
//		td.innerHTML = '{$lng.lbl_alt_text_file_descr}';

//		td.innerHTML = 'Additional operation:';

		td = new_row.insertCell(-1);
//		td.innerHTML = '<input type="text" size="80" name="alt['+row_max_index+']" value="" id="alt_' + row_max_index + '" />';

//--------
//		td.innerHTML = '<input type="radio" name="additional_operation['+row_max_index+']" value="none" id="additional_operation_none_' + row_max_index + '" checked="checked" />None <input type="radio" name="additional_operation['+row_max_index+']" value="gt" id="additional_operation_gt_' + row_max_index + '" />Generate thumbnail <input type="radio" name="additional_operation['+row_max_index+']" value="gt_del_img" id="additional_operation_gt_del_img_' + row_max_index + '" />Generate thumbnail & Delete this image <input type="hidden" size="80" name="alt['+row_max_index+']" value="" id="alt_' + row_max_index + '" />';
		td.innerHTML = '<input type="hidden" name="additional_operation['+row_max_index+']" value="none" id="additional_operation_none_' + row_max_index + '" /> <input type="hidden" name="additional_operation['+row_max_index+']" value="none" id="additional_operation_gt_' + row_max_index + '" /> <input type="hidden" name="additional_operation['+row_max_index+']" value="none" id="additional_operation_gt_del_img_' + row_max_index + '" /> <input type="hidden" size="80" name="alt['+row_max_index+']" value="" id="alt_' + row_max_index + '" />';
//--------

//		td.innerHTML = '';
		td = new_row.insertCell(-1);
//		td.innerHTML = '<input type="button" value="{$lng.lbl_copy_prod_name|strip_tags:false|escape}" onclick="javascript: copy_product_title(' + row_max_index + ')" />'
		td.innerHTML = ''
	{rdelim}

	function remove_upload_row(multi_id) {ldelim}
		var tr = document.getElementById('upload_row_'+multi_id);
		tr.parentNode.parentNode.deleteRow(tr.rowIndex);
		tr = document.getElementById('upload_alt_row_'+multi_id);
		tr.parentNode.parentNode.deleteRow(tr.rowIndex);
	{rdelim}




        function p_f_add_upload_row(multi_id) {ldelim}
                p_f_row_max_index = p_f_row_max_index + 1;
                var tr = document.getElementById('p_f_upload_alt_row_'+multi_id);
                var new_row = tr.parentNode.parentNode.insertRow(tr.rowIndex+1);
                new_row.id = 'p_f_upload_row_'+p_f_row_max_index;
                var td = new_row.insertCell(-1);
                td.innerHTML = '{$lng.lbl_select_file}:';
                td = new_row.insertCell(-1);
                td.innerHTML = "<input type=\"button\" id=\"plus_"+p_f_row_max_index+"\" value=\"{$lng.lbl_plus|strip_tags:false|escape}\" onclick=\"javascript: popup_image_selection('D_"+p_f_row_max_index+"', '{$product.productid}', '');\" />&nbsp;<span id=\"upload_fname_"+p_f_row_max_index+"\"></span><input type=\"file\" size=\"25\" name=\"userfile_D_"+p_f_row_max_index+"\" id=\"userfile_"+p_f_row_max_index+"\" />";
                td = new_row.insertCell(-1);
                td.innerHTML = "<a href=\"javascript: void(0);\" onclick=\"javascript: p_f_add_upload_row("+p_f_row_max_index+");\"><img src=\"{$ImagesDir}/plus.gif\" alt=\"{$lng.lbl_add_row|escape:'javascript'}\" /></a>&nbsp;<a href=\"javascript: void(0);\" onclick=\"javascript: p_f_remove_upload_row("+p_f_row_max_index+");\"><img src=\"{$ImagesDir}/minus.gif\" alt=\"{$lng.lbl_remove_row|escape:'javascript'}\" /></a>";
                new_row = tr.parentNode.parentNode.insertRow(new_row.rowIndex+1);
                new_row.id = 'p_f_upload_alt_row_'+p_f_row_max_index;
                td = new_row.insertCell(-1);
                td.innerHTML = '{$lng.lbl_alt_text_file_descr}';
                td = new_row.insertCell(-1);
                td.innerHTML = '<input type="text" size="80" name="alt['+p_f_row_max_index+']" value="" id="alt_' + p_f_row_max_index + '" />';
                td = new_row.insertCell(-1);
                td.innerHTML = '<input type="button" value="{$lng.lbl_copy_prod_name|strip_tags:false|escape}" onclick="javascript: copy_product_title(' + p_f_row_max_index + ')" />'
        {rdelim}


        function p_f_remove_upload_row(multi_id) {ldelim}
                var tr = document.getElementById('p_f_upload_row_'+multi_id);
                tr.parentNode.parentNode.deleteRow(tr.rowIndex);
                tr = document.getElementById('p_f_upload_alt_row_'+multi_id);
                tr.parentNode.parentNode.deleteRow(tr.rowIndex);
        {rdelim}


-->
</script>
<a name="section_product_files"></a>
{$lng.txt_det_product_files_top_text}

<br /><br />

{capture name=dialog}
{if $config.General.display_all_products_on_1_page eq 'Y'}
	<div>
		<table width="100%">
		<tr>
			<td>{* {$lng.txt_add_through_det_images_sec} *}</td>
			<td align="right"><a href="#main">{$lng.lbl_top}</a></td>
		</tr>
{*
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
*}
		</table>
	</div>
{/if}

<form action="product_modify.php" method="post" name="fileuploadform" enctype=multipart/form-data>

<input type="hidden" name="mode" value="product_files" />
<input type="hidden" name="cidev_form_name" value="product_files" />
<input type="hidden" name="productid" value="{$product.productid}" />
<input type="hidden" name="geid" value="{$geid}" />

<table cellspacing="0" cellpadding="3" width="100%">
{if $geid ne ''}
<tr>
    <td width="15" class="TableSubHead"><img src="{$ImagesDir}/spacer.gif" width="15" height="1" alt="" /></td>
    <td class="TableSubHead" colspan="6"><b>* {$lng.lbl_note}:</b> {$lng.txt_edit_product_group}</td>
</tr>
{/if}

<tr class="TableHead">
	{if $geid ne ''}<td width="15" class="TableSubHead">&nbsp;</td>{/if}
	<td width="15" class="DataTable">&nbsp;</td>
	<td width="5%" class="DataTable">{$lng.lbl_pos}</td>
	<td nowrap="nowrap" class="DataTable">{$lng.lbl_filename}</td>
	<td nowrap="nowrap" class="DataTable">{$lng.lbl_file_descr}</td>
	<td nowrap="nowrap" class="DataTable">{$lng.lbl_file_size}</td>
	<td width="15%" class="DataTable">{$lng.lbl_availability}</td>
</tr>

{if $product_files}

{section name=file loop=$product_files}

<tr{cycle values=", class='TableSubHead'"}>
	{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[p_files][{$product_files[file].fileid}]" /></td>{/if}
	<td width="15" class="DataTable"><input type="checkbox" value="Y" name="fids[{$product_files[file].fileid}]" /></td>
	<td class="DataTable">
		<input type="text" size="5" maxlength="5" name="file[{$product_files[file].fileid}][orderby]" value="{$product_files[file].orderby}" style="width: 100%;" />
	</td>
	<td class="DataTable">
		{$product_files[file].filename}
	</td>
	<td class="DataTable">
		<input type="text" size="32" name="file[{$product_files[file].fileid}][file_descr]" value="{$product_files[file].description|escape}" style="width:100%" />
	</td>
	<td class="DataTable">
		{$product_files[file].filesize}
	</td>
	<td class="DataTable">
		<select name="file[{$product_files[file].fileid}][avail]" style="width:100%">
			<option value="Y" {if $product_files[file].avail eq "Y"}selected{/if}>{$lng.lbl_enabled}</option>
			<option value="N" {if $product_files[file].avail eq "N"}selected{/if}>{$lng.lbl_disabled}</option>
		</select>
	</td>
</tr>
{/section}

<tr>
	{if $geid ne ''}<td width="15" class="TableSubHead">&nbsp;</td>{/if}
	<td colspan="6"><input type="button" value="{$lng.lbl_update|strip_tags:false|escape}" onclick="document.fileuploadform.mode.value='update_files';document.fileuploadform.submit();" />&nbsp;&nbsp;&nbsp;
    <input type="button" value="{$lng.lbl_delete_selected|strip_tags:false|escape}" onclick="javascript: if (checkMarks(this.form, new RegExp('fids\[[0-9]+\]', 'gi'))) {ldelim} document.fileuploadform.mode.value='delete_files'; document.fileuploadform.submit();{rdelim}" /></td>
</tr>

{else}

<tr>
	{if $geid ne ''}<td width="15" class="TableSubHead">&nbsp;</td>{/if}
	<td colspan="6" align="center">{$lng.lbl_no_files_found}</td>
</tr>

{/if}

{* ---------------------------- *}
{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[new_p_files]" /></td>{/if} {*  <----- *}
<td colspan="6">
<br />
{include file="main/subheader.tpl" title="Add product files"}
<table cellpadding="4" cellspacing="0">

<tr id="p_f_upload_row_1000">
<td>{$lng.lbl_select_file}:</td>
<td>
<input type="button" id="plus_1000" value="{$lng.lbl_plus|strip_tags:false|escape}" onclick="javascript: popup_image_selection('D_1000', '{$product.productid}', ''); $('upload_file_1000').val('111.txt');" />&nbsp;<span id="upload_fname_1000"></span><input type="file" size="25" name="userfile_D_1000" id="userfile_1000" />
</td>
<td><a href="javascript: void(0);" onclick="javascript: p_f_add_upload_row(1000);"><img src="{$ImagesDir}/plus.gif" alt="{$lng.lbl_add|escape}" /></a></td>
</tr>

<tr id="p_f_upload_alt_row_1000">
<td>{$lng.lbl_alt_text_file_descr}</td>
<td><input type="text" size="80" name="alt[1000]" value="" id="alt_1000" /></td>
<td><input type="button" value="{$lng.lbl_copy_prod_name|strip_tags:false|escape}" onclick="javascript: copy_product_title(1000)" /></td>
</tr>
<tr style="display: none;" id="err_size_text_det_{$product.productid}">
        {if $geid ne ''}<td>&nbsp;</td>{/if}
        <td colspan="3" class="ErrorMessage" nowrap="nowrap" id="err_size_text_det_td_{$product.productid}">&nbsp;</td>
</tr>
<tr>

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


</table>

{* ---------------------------- *}
<input type="button" value="{$lng.lbl_upload|strip_tags:false|escape}" onclick="javascript: document.fileuploadform.mode.value='product_images'; document.fileuploadform.submit();" />
{* ---------------------------- *}

</form>
{/capture}
{include file="dialog.tpl" title=$lng.lbl_product_files content=$smarty.capture.dialog extra='width="100%"'}

<br />
<a name="section_images"></a>
{$lng.txt_det_images_top_text}

<br /><br />

{capture name=dialog}
{if $config.General.display_all_products_on_1_page eq 'Y'}<div align="right"><a href="#main">{$lng.lbl_top}</a></div>{/if}

<form action="product_modify.php" method="post" name="uploadform" enctype=multipart/form-data>

<input type="hidden" name="mode" value="product_images" />
<input type="hidden" name="productid" value="{$product.productid}" />
<input type="hidden" name="thumbid" value="" />
<input type="hidden" name="geid" value="{$geid}" />

<table cellspacing="0" cellpadding="3" width="100%">
{if $geid ne ''}
<tr>
    <td width="15" class="TableSubHead"><img src="{$ImagesDir}/spacer.gif" width="15" height="1" alt="" /></td>
    <td class="TableSubHead" colspan="6"><b>* {$lng.lbl_note}:</b> {$lng.txt_edit_product_group}</td>
</tr>
{/if}

<tr class="TableHead">
{if $geid ne ''}<td width="15" class="TableSubHead">&nbsp;</td>{/if}
<td width="15" class="DataTable">&nbsp;</td>
<td width="65" class="DataTable">{$lng.lbl_image}</td>
<td width="3%" class="DataTable">{$lng.lbl_pos}</td>
<td width="10%" class="DataTable">{$lng.lbl_availability}</td>
<td width="*" nowrap="nowrap" class="DataTable">{$lng.lbl_alternative_text}</td>
<td width="10%" nowrap="nowrap" class="DataTable">{$lng.lbl_image_properties}</td>
<td width="10%" nowrap="nowrap">{$lng.lbl_gen_thumbnail}</td>
</tr>

{if $images}

{section name=image loop=$images}

<tr{cycle values=", class='TableSubHead'"}>
{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[d_image][{$images[image].imageid}]" /></td>{/if}
	<td width="15" class="DataTable"><input type="checkbox" value="Y" name="iids[{$images[image].imageid}]" /></td>
	<td align="center" class="DataTable">
<a href="{$xcart_web_dir}/image.php?id={$images[image].imageid}&amp;type=D" target="_blank"><img src="{$xcart_web_dir}/image.php?id={$images[image].imageid}&amp;type=D" width="50" alt="" /></a>
	</td>
	<td class="DataTable">
<input type="text" size="4" maxlength="5" name="image[{$images[image].imageid}][orderby]" value="{$images[image].orderby}" {* style="width: 100%;" *} />
	</td>
	<td class="DataTable">
<select name="image[{$images[image].imageid}][avail]" style="width:100%">
	<option value="Y" {if $images[image].avail eq "Y"}selected{/if}>{$lng.lbl_enabled}</option>
	<option value="N" {if $images[image].avail eq "N"}selected{/if}>{$lng.lbl_disabled}</option>
</select>
	</td>
	<td class="DataTable">
{*		<input type="text" size="32" name="image[{$images[image].imageid}][alt]" value="{$images[image].alt|escape}" style="width:100%" /> *}
		<input type="hidden" name="image[{$images[image].imageid}][alt]" value="{$images[image].alt|escape}" />
		{$images[image].alt|escape}
	</td>
	<td width="10%" class="DataTable">
	{$images[image].type},
	{$images[image].image_x}x{$images[image].image_y},
	{$images[image].image_size}b
	</td>
	<td>
		<input type="button" value=" {$lng.lbl_generate_thumbnail|strip_tags:false|escape} " onclick="javascript: $('#det_thumb_field').val($('#field_thumb').val()); document.uploadform.thumbid.value = {$images[image].imageid}; submitForm(this, 'gen_thumb_d');" />
	</td>
</tr>
{/section}

<tr>
	{if $geid ne ''}<td width="15" class="TableSubHead">&nbsp;</td>{/if}
	<td colspan="6">
		<input type="hidden" name="fields[thumbnail]" value="" id="det_thumb_field" />
		<input type="button" value="{$lng.lbl_update|strip_tags:false|escape}" onclick="document.uploadform.mode.value='update_availability';document.uploadform.submit();" />&nbsp;&nbsp;&nbsp;
    <input type="button" value="{$lng.lbl_delete_selected|strip_tags:false|escape}" onclick="javascript: document.uploadform.mode.value='product_images_delete'; document.uploadform.submit();" /></td>
</tr>

{else}

<tr>
{if $geid ne ''}<td width="15" class="TableSubHead">&nbsp;</td>{/if}
<td colspan="6" align="center">{$lng.txt_no_images}</td>
</tr>

{/if}
<tr>
{if $geid ne ''}<td width="15" class="TableSubHead">&nbsp;</td>{/if}
<td colspan="7">
<br /><br />

{include file="main/subheader.tpl" title=$lng.txt_add_new_detail_image|replace:"X":$config.Appearance.max_width_det_img|replace:"Y":$config.Appearance.max_height_det_img}
<script type="text/javascript">
<!--
	var not_image = 'avail';
-->
</script>

</td>
</tr>
{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[new_d_image]" /></td>{/if}
<td colspan="6">
<table cellpadding="4" cellspacing="0">

<tr id="upload_row_1">
<td>{$lng.lbl_select_file}:</td>
<td>
<input type="button" id="plus_1" value="{$lng.lbl_plus|strip_tags:false|escape}" onclick="javascript: popup_image_selection('D_1', '{$product.productid}', ''); $('upload_file_1').val('111.txt');" />&nbsp;<span id="upload_fname_1"></span><input type="file" size="25" name="userfile_D_1" id="userfile_1" />
</td>
<td><a href="javascript: void(0);" onclick="javascript: add_upload_row(1);"><img src="{$ImagesDir}/plus.gif" alt="{$lng.lbl_add|escape}" /></a></td>
</tr>

<tr id="upload_alt_row_1">
<td>
Additional operation:
{* {$lng.lbl_alt_text_file_descr} *}
</td>
{* <td><input type="text" size="80" name="alt[1]" value="" id="alt_1" /></td> *}
<td>
<input type="radio" name="additional_operation[1]" value="none" id="additional_operation_none_1" checked="checked" />None
<input type="radio" name="additional_operation[1]" value="gt" id="additional_operation_gt_1" />Generate thumbnails
<input type="radio" name="additional_operation[1]" value="gt_del_img" id="additional_operation_gt_del_img_1" />Generate thumbnails & Delete this image

<input type="hidden" size="80" name="alt[1]" value="" id="alt_1" />
<br />
<br />
</td>
<td>{* <input type="button" value="{$lng.lbl_copy_prod_name|strip_tags:false|escape}" onclick="javascript: copy_product_title(1)" />*}</td>
</tr>
<tr style="display: none;" id="err_size_text_det_{$product.productid}">
	{if $geid ne ''}<td>&nbsp;</td>{/if}
	<td colspan="3" class="ErrorMessage" nowrap="nowrap" id="err_size_text_det_td_{$product.productid}">&nbsp;</td>
</tr> 
<tr>

</table>


<script type="text/javascript">
<!--
	add_upload_row(1);
	add_upload_row(2);
    {literal}
    $('body').delegate('input[id^=userfile]', 'change', function() {
        id = $(this).attr('id').substring(9, 10);
        $('#plus_' + id).attr('disabled', 'disabled');
    });
    {/literal}
-->
</script>

<br />
<input type="submit" value="{$lng.lbl_upload|strip_tags:false|escape}" />

</td>
</tr>
</table>
</form>
{/capture}
{include file="dialog.tpl" title=$lng.lbl_detailed_images content=$smarty.capture.dialog extra='width="100%"'}
{/if}

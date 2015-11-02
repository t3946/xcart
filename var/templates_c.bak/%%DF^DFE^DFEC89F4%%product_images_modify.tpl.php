<?php /* Smarty version 2.6.12, created on 2011-10-11 06:53:29
         compiled from modules/Detailed_Product_Images/product_images_modify.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'strip_tags', 'modules/Detailed_Product_Images/product_images_modify.tpl', 21, false),array('modifier', 'escape', 'modules/Detailed_Product_Images/product_images_modify.tpl', 21, false),array('modifier', 'replace', 'modules/Detailed_Product_Images/product_images_modify.tpl', 222, false),array('function', 'cycle', 'modules/Detailed_Product_Images/product_images_modify.tpl', 90, false),)), $this); ?>
<?php func_load_lang($this, "modules/Detailed_Product_Images/product_images_modify.tpl","lbl_select_file,lbl_plus,lbl_add_row,lbl_remove_row,lbl_alt_text_file_descr,lbl_copy_prod_name,txt_det_product_files_top_text,txt_add_through_det_images_sec,lbl_top,lbl_note,txt_edit_product_group,lbl_pos,lbl_filename,lbl_file_descr,lbl_file_size,lbl_availability,lbl_enabled,lbl_disabled,lbl_update,lbl_delete_selected,lbl_no_files_found,lbl_product_files,txt_det_images_top_text,lbl_top,lbl_note,txt_edit_product_group,lbl_image,lbl_pos,lbl_availability,lbl_alternative_text,lbl_image_properties,lbl_gen_thumbnail,lbl_enabled,lbl_disabled,lbl_generate_thumbnail,lbl_update,lbl_delete_selected,txt_no_images,txt_add_new_detail_image,lbl_select_file,lbl_plus,lbl_add,lbl_alt_text_file_descr,lbl_copy_prod_name,lbl_upload,lbl_detailed_images"); ?><?php if ($this->_tpl_vars['active_modules']['Detailed_Product_Images'] != ""): ?>
<script type="text/javascript">
<!--
	row_max_index = 1;

	<?php echo '
	function copy_product_title(index) {
		$(\'#alt_\' + index).val($(\'#product_name\').val());
	}
	'; ?>

	
	function add_upload_row(multi_id) {
		row_max_index = row_max_index + 1;
		var tr = document.getElementById('upload_alt_row_'+multi_id);
		var new_row = tr.parentNode.parentNode.insertRow(tr.rowIndex+1);
		new_row.id = 'upload_row_'+row_max_index;
		var td = new_row.insertCell(-1);
		td.innerHTML = '<?php echo $this->_tpl_vars['lng']['lbl_select_file']; ?>
:';
		td = new_row.insertCell(-1);
		td.innerHTML = "<input type=\"button\" id=\"plus_"+row_max_index+"\" value=\"<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_plus'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
\" onclick=\"javascript: popup_image_selection('D_"+row_max_index+"', '<?php echo $this->_tpl_vars['product']['productid']; ?>
', '');\" />&nbsp;<span id=\"upload_fname_"+row_max_index+"\"></span><input type=\"file\" size=\"25\" name=\"userfile_D_"+row_max_index+"\" id=\"userfile_"+row_max_index+"\" />";
		td = new_row.insertCell(-1);
		td.innerHTML = "<a href=\"javascript: void(0);\" onclick=\"javascript: add_upload_row("+row_max_index+");\"><img src=\"<?php echo $this->_tpl_vars['ImagesDir']; ?>
/plus.gif\" alt=\"<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_add_row'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
\" /></a>&nbsp;<a href=\"javascript: void(0);\" onclick=\"javascript: remove_upload_row("+row_max_index+");\"><img src=\"<?php echo $this->_tpl_vars['ImagesDir']; ?>
/minus.gif\" alt=\"<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_remove_row'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
\" /></a>";
		new_row = tr.parentNode.parentNode.insertRow(new_row.rowIndex+1);
		new_row.id = 'upload_alt_row_'+row_max_index;
		td = new_row.insertCell(-1);
		td.innerHTML = '<?php echo $this->_tpl_vars['lng']['lbl_alt_text_file_descr']; ?>
';
		td = new_row.insertCell(-1);
		td.innerHTML = '<input type="text" size="80" name="alt['+row_max_index+']" value="" id="alt_' + row_max_index + '" />';
		td = new_row.insertCell(-1);
		td.innerHTML = '<input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_copy_prod_name'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: copy_product_title(' + row_max_index + ')" />'
	}

	function remove_upload_row(multi_id) {
		var tr = document.getElementById('upload_row_'+multi_id);
		tr.parentNode.parentNode.deleteRow(tr.rowIndex);
		tr = document.getElementById('upload_alt_row_'+multi_id);
		tr.parentNode.parentNode.deleteRow(tr.rowIndex);
	}
-->
</script>
<a name="section_product_files"></a>
<?php echo $this->_tpl_vars['lng']['txt_det_product_files_top_text']; ?>


<br /><br />

<?php ob_start();  if ($this->_tpl_vars['config']['General']['display_all_products_on_1_page'] == 'Y'): ?>
	<div>
		<table width="100%">
		<tr>
			<td><?php echo $this->_tpl_vars['lng']['txt_add_through_det_images_sec']; ?>
</td>
			<td align="right"><a href="#main"><?php echo $this->_tpl_vars['lng']['lbl_top']; ?>
</a></td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		</table>
	</div>
<?php endif; ?>

<form action="product_modify.php" method="post" name="fileuploadform">

<input type="hidden" name="mode" value="product_files" />
<input type="hidden" name="productid" value="<?php echo $this->_tpl_vars['product']['productid']; ?>
" />
<input type="hidden" name="geid" value="<?php echo $this->_tpl_vars['geid']; ?>
" />

<table cellspacing="0" cellpadding="3" width="100%">
<?php if ($this->_tpl_vars['geid'] != ''): ?>
<tr>
    <td width="15" class="TableSubHead"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" width="15" height="1" alt="" /></td>
    <td class="TableSubHead" colspan="6"><b>* <?php echo $this->_tpl_vars['lng']['lbl_note']; ?>
:</b> <?php echo $this->_tpl_vars['lng']['txt_edit_product_group']; ?>
</td>
</tr>
<?php endif; ?>

<tr class="TableHead">
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead">&nbsp;</td><?php endif; ?>
	<td width="15" class="DataTable">&nbsp;</td>
	<td width="5%" class="DataTable"><?php echo $this->_tpl_vars['lng']['lbl_pos']; ?>
</td>
	<td nowrap="nowrap" class="DataTable"><?php echo $this->_tpl_vars['lng']['lbl_filename']; ?>
</td>
	<td nowrap="nowrap" class="DataTable"><?php echo $this->_tpl_vars['lng']['lbl_file_descr']; ?>
</td>
	<td nowrap="nowrap" class="DataTable"><?php echo $this->_tpl_vars['lng']['lbl_file_size']; ?>
</td>
	<td width="15%" class="DataTable"><?php echo $this->_tpl_vars['lng']['lbl_availability']; ?>
</td>
</tr>

<?php if ($this->_tpl_vars['product_files']): ?>

<?php unset($this->_sections['file']);
$this->_sections['file']['name'] = 'file';
$this->_sections['file']['loop'] = is_array($_loop=$this->_tpl_vars['product_files']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['file']['show'] = true;
$this->_sections['file']['max'] = $this->_sections['file']['loop'];
$this->_sections['file']['step'] = 1;
$this->_sections['file']['start'] = $this->_sections['file']['step'] > 0 ? 0 : $this->_sections['file']['loop']-1;
if ($this->_sections['file']['show']) {
    $this->_sections['file']['total'] = $this->_sections['file']['loop'];
    if ($this->_sections['file']['total'] == 0)
        $this->_sections['file']['show'] = false;
} else
    $this->_sections['file']['total'] = 0;
if ($this->_sections['file']['show']):

            for ($this->_sections['file']['index'] = $this->_sections['file']['start'], $this->_sections['file']['iteration'] = 1;
                 $this->_sections['file']['iteration'] <= $this->_sections['file']['total'];
                 $this->_sections['file']['index'] += $this->_sections['file']['step'], $this->_sections['file']['iteration']++):
$this->_sections['file']['rownum'] = $this->_sections['file']['iteration'];
$this->_sections['file']['index_prev'] = $this->_sections['file']['index'] - $this->_sections['file']['step'];
$this->_sections['file']['index_next'] = $this->_sections['file']['index'] + $this->_sections['file']['step'];
$this->_sections['file']['first']      = ($this->_sections['file']['iteration'] == 1);
$this->_sections['file']['last']       = ($this->_sections['file']['iteration'] == $this->_sections['file']['total']);
?>

<tr<?php echo smarty_function_cycle(array('values' => ", class='TableSubHead'"), $this);?>
>
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[p_files][<?php echo $this->_tpl_vars['product_files'][$this->_sections['file']['index']]['fileid']; ?>
]" /></td><?php endif; ?>
	<td width="15" class="DataTable"><input type="checkbox" value="Y" name="fids[<?php echo $this->_tpl_vars['product_files'][$this->_sections['file']['index']]['fileid']; ?>
]" /></td>
	<td class="DataTable">
		<input type="text" size="5" maxlength="5" name="file[<?php echo $this->_tpl_vars['product_files'][$this->_sections['file']['index']]['fileid']; ?>
][orderby]" value="<?php echo $this->_tpl_vars['product_files'][$this->_sections['file']['index']]['orderby']; ?>
" style="width: 100%;" />
	</td>
	<td class="DataTable">
		<?php echo $this->_tpl_vars['product_files'][$this->_sections['file']['index']]['filename']; ?>

	</td>
	<td class="DataTable">
		<input type="text" size="32" name="file[<?php echo $this->_tpl_vars['product_files'][$this->_sections['file']['index']]['fileid']; ?>
][file_descr]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['product_files'][$this->_sections['file']['index']]['description'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" style="width:100%" />
	</td>
	<td class="DataTable">
		<?php echo $this->_tpl_vars['product_files'][$this->_sections['file']['index']]['filesize']; ?>

	</td>
	<td class="DataTable">
		<select name="file[<?php echo $this->_tpl_vars['product_files'][$this->_sections['file']['index']]['fileid']; ?>
][avail]" style="width:100%">
			<option value="Y" <?php if ($this->_tpl_vars['product_files'][$this->_sections['file']['index']]['avail'] == 'Y'): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_enabled']; ?>
</option>
			<option value="N" <?php if ($this->_tpl_vars['product_files'][$this->_sections['file']['index']]['avail'] == 'N'): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_disabled']; ?>
</option>
		</select>
	</td>
</tr>
<?php endfor; endif; ?>

<tr>
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead">&nbsp;</td><?php endif; ?>
	<td colspan="6"><input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_update'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="document.fileuploadform.mode.value='update_files';document.fileuploadform.submit();" />&nbsp;&nbsp;&nbsp;
    <input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_delete_selected'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: if (checkMarks(this.form, new RegExp('fids\[[0-9]+\]', 'gi'))) { document.fileuploadform.mode.value='delete_files'; document.fileuploadform.submit();}" /></td>
</tr>

<?php else: ?>

<tr>
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead">&nbsp;</td><?php endif; ?>
	<td colspan="6" align="center"><?php echo $this->_tpl_vars['lng']['lbl_no_files_found']; ?>
</td>
</tr>

<?php endif; ?>

</table>

</form>
<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_product_files'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<br />
<a name="section_images"></a>
<?php echo $this->_tpl_vars['lng']['txt_det_images_top_text']; ?>


<br /><br />

<?php ob_start();  if ($this->_tpl_vars['config']['General']['display_all_products_on_1_page'] == 'Y'): ?><div align="right"><a href="#main"><?php echo $this->_tpl_vars['lng']['lbl_top']; ?>
</a></div><?php endif; ?>

<form action="product_modify.php" method="post" name="uploadform" enctype=multipart/form-data>

<input type="hidden" name="mode" value="product_images" />
<input type="hidden" name="productid" value="<?php echo $this->_tpl_vars['product']['productid']; ?>
" />
<input type="hidden" name="thumbid" value="" />
<input type="hidden" name="geid" value="<?php echo $this->_tpl_vars['geid']; ?>
" />

<table cellspacing="0" cellpadding="3" width="100%">
<?php if ($this->_tpl_vars['geid'] != ''): ?>
<tr>
    <td width="15" class="TableSubHead"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" width="15" height="1" alt="" /></td>
    <td class="TableSubHead" colspan="6"><b>* <?php echo $this->_tpl_vars['lng']['lbl_note']; ?>
:</b> <?php echo $this->_tpl_vars['lng']['txt_edit_product_group']; ?>
</td>
</tr>
<?php endif; ?>

<tr class="TableHead">
<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead">&nbsp;</td><?php endif; ?>
<td width="15" class="DataTable">&nbsp;</td>
<td width="65" class="DataTable"><?php echo $this->_tpl_vars['lng']['lbl_image']; ?>
</td>
<td width="5%" class="DataTable"><?php echo $this->_tpl_vars['lng']['lbl_pos']; ?>
</td>
<td width="15%" class="DataTable"><?php echo $this->_tpl_vars['lng']['lbl_availability']; ?>
</td>
<td width="40%" nowrap="nowrap" class="DataTable"><?php echo $this->_tpl_vars['lng']['lbl_alternative_text']; ?>
</td>
<td width="20%" nowrap="nowrap" class="DataTable"><?php echo $this->_tpl_vars['lng']['lbl_image_properties']; ?>
</td>
<td width="20%" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_gen_thumbnail']; ?>
</td>
</tr>

<?php if ($this->_tpl_vars['images']): ?>

<?php unset($this->_sections['image']);
$this->_sections['image']['name'] = 'image';
$this->_sections['image']['loop'] = is_array($_loop=$this->_tpl_vars['images']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['image']['show'] = true;
$this->_sections['image']['max'] = $this->_sections['image']['loop'];
$this->_sections['image']['step'] = 1;
$this->_sections['image']['start'] = $this->_sections['image']['step'] > 0 ? 0 : $this->_sections['image']['loop']-1;
if ($this->_sections['image']['show']) {
    $this->_sections['image']['total'] = $this->_sections['image']['loop'];
    if ($this->_sections['image']['total'] == 0)
        $this->_sections['image']['show'] = false;
} else
    $this->_sections['image']['total'] = 0;
if ($this->_sections['image']['show']):

            for ($this->_sections['image']['index'] = $this->_sections['image']['start'], $this->_sections['image']['iteration'] = 1;
                 $this->_sections['image']['iteration'] <= $this->_sections['image']['total'];
                 $this->_sections['image']['index'] += $this->_sections['image']['step'], $this->_sections['image']['iteration']++):
$this->_sections['image']['rownum'] = $this->_sections['image']['iteration'];
$this->_sections['image']['index_prev'] = $this->_sections['image']['index'] - $this->_sections['image']['step'];
$this->_sections['image']['index_next'] = $this->_sections['image']['index'] + $this->_sections['image']['step'];
$this->_sections['image']['first']      = ($this->_sections['image']['iteration'] == 1);
$this->_sections['image']['last']       = ($this->_sections['image']['iteration'] == $this->_sections['image']['total']);
?>

<tr<?php echo smarty_function_cycle(array('values' => ", class='TableSubHead'"), $this);?>
>
<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[d_image][<?php echo $this->_tpl_vars['images'][$this->_sections['image']['index']]['imageid']; ?>
]" /></td><?php endif; ?>
	<td width="15" class="DataTable"><input type="checkbox" value="Y" name="iids[<?php echo $this->_tpl_vars['images'][$this->_sections['image']['index']]['imageid']; ?>
]" /></td>
	<td align="center" class="DataTable">
<a href="<?php echo $this->_tpl_vars['xcart_web_dir']; ?>
/image.php?id=<?php echo $this->_tpl_vars['images'][$this->_sections['image']['index']]['imageid']; ?>
&amp;type=D" target="_blank"><img src="<?php echo $this->_tpl_vars['xcart_web_dir']; ?>
/image.php?id=<?php echo $this->_tpl_vars['images'][$this->_sections['image']['index']]['imageid']; ?>
&amp;type=D" width="50" alt="" /></a>
	</td>
	<td class="DataTable">
<input type="text" size="5" maxlength="5" name="image[<?php echo $this->_tpl_vars['images'][$this->_sections['image']['index']]['imageid']; ?>
][orderby]" value="<?php echo $this->_tpl_vars['images'][$this->_sections['image']['index']]['orderby']; ?>
" style="width: 100%;" />
	</td>
	<td class="DataTable">
<select name="image[<?php echo $this->_tpl_vars['images'][$this->_sections['image']['index']]['imageid']; ?>
][avail]" style="width:100%">
	<option value="Y" <?php if ($this->_tpl_vars['images'][$this->_sections['image']['index']]['avail'] == 'Y'): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_enabled']; ?>
</option>
	<option value="N" <?php if ($this->_tpl_vars['images'][$this->_sections['image']['index']]['avail'] == 'N'): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_disabled']; ?>
</option>
</select>
	</td>
	<td class="DataTable"><input type="text" size="32" name="image[<?php echo $this->_tpl_vars['images'][$this->_sections['image']['index']]['imageid']; ?>
][alt]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['images'][$this->_sections['image']['index']]['alt'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" style="width:100%" /></td>
<td width="30%" class="DataTable">
<?php echo $this->_tpl_vars['images'][$this->_sections['image']['index']]['type']; ?>
,
<?php echo $this->_tpl_vars['images'][$this->_sections['image']['index']]['image_x']; ?>
x<?php echo $this->_tpl_vars['images'][$this->_sections['image']['index']]['image_y']; ?>
,
<?php echo $this->_tpl_vars['images'][$this->_sections['image']['index']]['image_size']; ?>
b
</td>
	<td>
		<input type="button" value=" <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_generate_thumbnail'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 " onclick="javascript: $('#det_thumb_field').val($('#field_thumb').val()); document.uploadform.thumbid.value = <?php echo $this->_tpl_vars['images'][$this->_sections['image']['index']]['imageid']; ?>
; submitForm(this, 'gen_thumb_d');" />
	</td>
</tr>
<?php endfor; endif; ?>

<tr>
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead">&nbsp;</td><?php endif; ?>
	<td colspan="6">
		<input type="hidden" name="fields[thumbnail]" value="" id="det_thumb_field" />
		<input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_update'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="document.uploadform.mode.value='update_availability';document.uploadform.submit();" />&nbsp;&nbsp;&nbsp;
    <input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_delete_selected'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: document.uploadform.mode.value='product_images_delete'; document.uploadform.submit();" /></td>
</tr>

<?php else: ?>

<tr>
<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead">&nbsp;</td><?php endif; ?>
<td colspan="6" align="center"><?php echo $this->_tpl_vars['lng']['txt_no_images']; ?>
</td>
</tr>

<?php endif; ?>
<tr>
<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead">&nbsp;</td><?php endif; ?>
<td colspan="6">
<br /><br />

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/subheader.tpl", 'smarty_include_vars' => array('title' => ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['txt_add_new_detail_image'])) ? $this->_run_mod_handler('replace', true, $_tmp, 'X', $this->_tpl_vars['config']['Appearance']['max_width_det_img']) : smarty_modifier_replace($_tmp, 'X', $this->_tpl_vars['config']['Appearance']['max_width_det_img'])))) ? $this->_run_mod_handler('replace', true, $_tmp, 'Y', $this->_tpl_vars['config']['Appearance']['max_height_det_img']) : smarty_modifier_replace($_tmp, 'Y', $this->_tpl_vars['config']['Appearance']['max_height_det_img'])))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<script type="text/javascript">
<!--
	var not_image = 'avail';
-->
</script>

</td>
</tr>
<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[new_d_image]" /></td><?php endif; ?>
<td colspan="6">
<table cellpadding="4" cellspacing="0">

<tr id="upload_row_1">
<td><?php echo $this->_tpl_vars['lng']['lbl_select_file']; ?>
:</td>
<td>
<input type="button" id="plus_1" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_plus'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: popup_image_selection('D_1', '<?php echo $this->_tpl_vars['product']['productid']; ?>
', ''); $('upload_file_1').val('111.txt');" />&nbsp;<span id="upload_fname_1"></span><input type="file" size="25" name="userfile_D_1" id="userfile_1" />
</td>
<td><a href="javascript: void(0);" onclick="javascript: add_upload_row(1);"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/plus.gif" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_add'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></a></td>
</tr>

<tr id="upload_alt_row_1">
<td><?php echo $this->_tpl_vars['lng']['lbl_alt_text_file_descr']; ?>
</td>
<td><input type="text" size="80" name="alt[1]" value="" id="alt_1" /></td>
<td><input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_copy_prod_name'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: copy_product_title(1)" /></td>
</tr>
<tr style="display: none;" id="err_size_text_det_<?php echo $this->_tpl_vars['product']['productid']; ?>
">
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td>&nbsp;</td><?php endif; ?>
	<td colspan="3" class="ErrorMessage" nowrap="nowrap" id="err_size_text_det_td_<?php echo $this->_tpl_vars['product']['productid']; ?>
">&nbsp;</td>
</tr> 
<tr>

</table>

<script type="text/javascript">
<!--
	add_upload_row(1);
	add_upload_row(2);
    <?php echo '
    $(\'body\').delegate(\'input[id^=userfile]\', \'change\', function() {
        id = $(this).attr(\'id\').substring(9, 10);
        $(\'#plus_\' + id).attr(\'disabled\', \'disabled\');
    });
    '; ?>

-->
</script>

<br />
<input type="submit" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_upload'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />

</td>
</tr>
</table>
</form>
<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_detailed_images'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
<?php /* Smarty version 2.6.12, created on 2011-10-11 06:47:01
         compiled from admin/main/languages.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'strip_tags', 'admin/main/languages.tpl', 20, false),array('modifier', 'escape', 'admin/main/languages.tpl', 20, false),array('modifier', 'substitute', 'admin/main/languages.tpl', 359, false),)), $this); ?>
<?php func_load_lang($this, "admin/main/languages.tpl","lbl_edit_languages,lbl_language,lbl_select_one,lbl_enable,lbl_disable,lbl_delete,txt_are_you_sure,lbl_csv_delimiter,lbl_export,lbl_language_options,lbl_charset,lbl_r2l_text_direction,lbl_apply,lbl_edit_language,txt_edit_language_note,lbl_select_topic,lbl_all,lbl_apply_filter,lbl_go,lbl_total_labels_found,msg_new_label_empty,lbl_topic,lbl_delete_selected,lbl_update_all,lbl_add_new_entry,lbl_select_topic,lbl_variable,lbl_value,lbl_add,lbl_edit_language_entries,lbl_default_customer_language,lbl_select_one,lbl_default_admin_language,lbl_select_one,lbl_update,lbl_default_languages,lbl_choose_language,lbl_select_one,lbl_csv_delimiter,txt_source_import_file,lbl_server,lbl_home_computer,txt_csv_file_is_located_on_the_server,txt_csv_file_is_located_on_the_server_expl,lbl_csv_file_for_upload,lbl_warning,txt_max_file_size_that_can_be_uploaded,txt_import_language_note,lbl_add_update_language,lbl_add_new_language"); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "page_title.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_edit_languages'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php ob_start(); ?>

<table cellpadding="5" cellspacing="0">

<tr>
	<td class="FormButton"><?php echo $this->_tpl_vars['lng']['lbl_language']; ?>
:</td>
	<td><select name="language" onchange='javascript: self.location="languages.php?language="+this.value;'>
<option value=""<?php if ($GLOBALS['HTTP_GET_VARS']['language'] == ""): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_select_one']; ?>
</option>
<?php $_from = $this->_tpl_vars['languages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['l']):
?>
<option value="<?php echo $this->_tpl_vars['l']['code']; ?>
"<?php if ($GLOBALS['HTTP_GET_VARS']['language'] == $this->_tpl_vars['l']['code']): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['l']['language']; ?>
</option>
<?php endforeach; endif; unset($_from); ?>
</select>
	</td>
<?php if ($GLOBALS['HTTP_GET_VARS']['language'] != "" && $GLOBALS['HTTP_GET_VARS']['language'] != $this->_tpl_vars['shop_language']): ?>
	<td>
<input type="button" value="<?php if ($this->_tpl_vars['lang_disabled'] == 'Y'):  echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_enable'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp));  else:  echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_disable'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp));  endif; ?>" onclick="javascript: self.location='languages.php?language=<?php echo ((is_array($_tmp=$GLOBALS['HTTP_GET_VARS']['language'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
&amp;mode=change'" />
<input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_delete'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: if (confirm('<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['txt_are_you_sure'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)); ?>
')) self.location='languages.php?language=<?php echo ((is_array($_tmp=$GLOBALS['HTTP_GET_VARS']['language'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
&amp;mode=del_lang';" />
	</td>
<?php endif; ?>
</tr>

</table>

<?php if ($GLOBALS['HTTP_GET_VARS']['language'] != ""): ?>

<br />
<br />

<form method="get" action="languages.php" name="dl_form">
<input type="hidden" name="mode" value="" />
<input type="hidden" name="language" value="<?php echo ((is_array($_tmp=$GLOBALS['HTTP_GET_VARS']['language'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" />

<table cellpadding="5" cellspacing="0">
<tr>
	<td><?php echo $this->_tpl_vars['lng']['lbl_csv_delimiter']; ?>
:</td>
	<td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "provider/main/ie_delimiter.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
	<td><input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_export'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: document.dl_form.mode.value = 'export'; document.dl_form.submit();" /></td>
</tr>
</table>

<br />
<br />

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_language_options'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<table cellpadding="5" cellspacing="0">
<tr>
	<td><?php echo $this->_tpl_vars['lng']['lbl_charset']; ?>
:</td>
	<td colspan="2"><input type="text" name="charset" value="<?php echo $this->_tpl_vars['default_charset']; ?>
" /></td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td colspan="2">
	<table cellspacing="2" cellpadding="0">
	<tr>
		<td><input type="checkbox" id="text_dir" name="text_dir" value="Y"<?php if ($this->_tpl_vars['config']['r2l_languages'][$GLOBALS['HTTP_GET_VARS']['language']]): ?> checked="checked"<?php endif; ?> /></td>
		<td><label for="text_dir"><?php echo $this->_tpl_vars['lng']['lbl_r2l_text_direction']; ?>
</label></td>
	</tr>
	</table>
	</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td colspan="2"><input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_apply'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: document.dl_form.mode.value = 'update_charset'; document.dl_form.submit();" /></td>
</tr>
</table>

</form>

<?php endif; ?>

<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('content' => $this->_smarty_vars['capture']['dialog'],'title' => $this->_tpl_vars['lng']['lbl_edit_language'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<br />

<?php if ($GLOBALS['HTTP_GET_VARS']['language'] != ""): ?>

<?php echo $this->_tpl_vars['lng']['txt_edit_language_note']; ?>


<form method="get" action="languages.php" name="topic_form">
<input type="hidden" name="language" value="<?php echo ((is_array($_tmp=$GLOBALS['HTTP_GET_VARS']['language'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" />
<br />
<?php echo $this->_tpl_vars['lng']['lbl_select_topic']; ?>
:
<select name="topic" onchange='javascript: document.topic_form.submit();'>
	<option value=""<?php if ($GLOBALS['HTTP_GET_VARS']['topic'] == ""): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_all']; ?>
</option>
<?php $_from = $this->_tpl_vars['topics']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['t']):
?>
	<option value="<?php echo $this->_tpl_vars['t']; ?>
"<?php if ($GLOBALS['HTTP_GET_VARS']['topic'] == $this->_tpl_vars['t']): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['t']; ?>
</option>
<?php endforeach; endif; unset($_from); ?>
</select>
&nbsp;
&nbsp;
&nbsp;
<?php echo $this->_tpl_vars['lng']['lbl_apply_filter']; ?>
:
<input type="text" size="16" name="filter" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['filter'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" />&nbsp;<input type="submit" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_go'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
</form>

<br />

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/navigation.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php echo $this->_tpl_vars['lng']['lbl_total_labels_found']; ?>
: <?php echo $this->_tpl_vars['total_labels_found']; ?>


<script type="text/javascript" language="JavaScript 1.2">
<!--
var msg_new_label_empty = "<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['msg_new_label_empty'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)); ?>
";
var delete_link = 'languages.php?mode=delete&page=<?php echo $this->_tpl_vars['page']; ?>
&language=<?php echo $GLOBALS['HTTP_GET_VARS']['language']; ?>
&filter=<?php echo $this->_tpl_vars['filter']; ?>
&topic=<?php echo $GLOBALS['HTTP_GET_VARS']['topic']; ?>
&var=';

<?php echo '
function func_checklang() {
	if (document.addlblform.new_var_name.value != \'\' && document.addlblform.new_var_value.value == \'\') {
		alert(msg_new_label_empty);
		return false;
	}
	return true;
}

'; ?>

-->
</script>

<?php if ($this->_tpl_vars['active_modules']['HTML_Editor']):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/HTML_Editor/editor.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

<form action="languages.php" method="post" name="languagespostform">

<input type="hidden" name="mode" value="update" />
<input type="hidden" name="page" value="<?php echo ((is_array($_tmp=$GLOBALS['HTTP_GET_VARS']['page'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" />
<input type="hidden" name="topic" value="<?php echo ((is_array($_tmp=$GLOBALS['HTTP_GET_VARS']['topic'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" />
<input type="hidden" name="filter" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['filter'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" />
<input type="hidden" name="language" value="<?php echo ((is_array($_tmp=$GLOBALS['HTTP_GET_VARS']['language'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" />

<br />

<?php ob_start(); ?>
<div valign="top">
<table cellpadding="0" cellspacing="2" width="100%">

<?php $this->assign('current_topic', ""); ?>

<tr>
	<td>

<table cellspacing="0" cellpadding="2" width="100%">
<?php $_from = $this->_tpl_vars['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['lbl']):
 if ($this->_tpl_vars['lbl']['topic'] != $this->_tpl_vars['current_topic']): ?>

<?php if ($this->_tpl_vars['current_topic'] != ""): ?>
<tr>
	<td colspan="2"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" width="1" height="20" alt="" /></td>
</tr>
<?php endif; ?>

<tr>
	<td colspan="2" class="TableHead"><?php echo $this->_tpl_vars['lng']['lbl_topic']; ?>
: <?php echo $this->_tpl_vars['lbl']['topic']; ?>
</td>
</tr>

<?php $this->assign('current_topic', $this->_tpl_vars['lbl']['topic']); ?>

<?php endif; ?>

<tr class="TableSubHead">
	<td><input type="checkbox" name="ids[]" value="<?php echo $this->_tpl_vars['lbl']['name']; ?>
" /></td>
	<td width="100%"><b><?php echo $this->_tpl_vars['lbl']['name']; ?>
</b></td>
</tr>
<tr class="TableSubHead">
	<td>&nbsp;</td>
	<td>
<?php if ($this->_tpl_vars['active_modules']['HTML_Editor']):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/HTML_Editor/popup_link.tpl", 'smarty_include_vars' => array('id' => "var_".($this->_tpl_vars['lbl']['name']),'width' => "99%")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
	<textarea id="var_<?php echo $this->_tpl_vars['lbl']['name']; ?>
" name="var_value[<?php echo $this->_tpl_vars['lbl']['name']; ?>
]" cols="70" rows="8" style="width: 99%;"><?php echo ((is_array($_tmp=$this->_tpl_vars['lbl']['value'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</textarea>
	</td>
</tr>
<tr>
	<td>&nbsp;</td>
</tr>

<?php endforeach; endif; unset($_from); ?>
<tr>
	<td colspan="2" class="SubmitBox">
<input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_delete_selected'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: submitForm(this, 'delete');" />
&nbsp;&nbsp;
<input type="submit" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_update_all'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
	</td>
</tr>
</table>
</form>

	</td>
</tr>
<tr>
	<td><br /><br /><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_add_new_entry'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>

<tr>
	<td>

<form action="languages.php" method="post" name="addlblform" onsubmit="javascript: return func_checklang();">

<input type="hidden" name="mode" value="add" />
<input type="hidden" name="page" value="<?php echo ((is_array($_tmp=$GLOBALS['HTTP_GET_VARS']['page'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" />
<input type="hidden" name="topic" value="<?php echo ((is_array($_tmp=$GLOBALS['HTTP_GET_VARS']['topic'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" />
<input type="hidden" name="filter" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['filter'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" />
<input type="hidden" name="language" value="<?php echo ((is_array($_tmp=$GLOBALS['HTTP_GET_VARS']['language'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" />

<table cellpadding="3" cellspacing="0" width="100%">

<?php if ($GLOBALS['HTTP_GET_VARS']['topic'] == ""):  $this->assign('new_topic_default', 'Labels');  else:  $this->assign('new_topic_default', $GLOBALS['HTTP_GET_VARS']['topic']);  endif; ?>
<tr>
	<td class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_select_topic']; ?>
: <font class="Star">*</font></td>
	<td>
	<select name="new_topic">
		<?php $_from = $this->_tpl_vars['topics']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['t']):
?>
		<option value="<?php echo $this->_tpl_vars['t']; ?>
"<?php if ($this->_tpl_vars['new_topic_default'] == $this->_tpl_vars['t']): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['t']; ?>
</option>
		<?php endforeach; endif; unset($_from); ?>
	</select>
	</td>
</tr>

<tr>
	<td class="FormButton" width="10%" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_variable']; ?>
: <font class="Star">*</font></td>
	<td align="left"><input type="text" size="50" name="new_var_name" /></td>
</tr>

<tr>
	<td colspan="2" class="FormButton"><?php echo $this->_tpl_vars['lng']['lbl_value']; ?>
: <font class="Star">*</font></td>
</tr>

<tr>
	<td colspan="2">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/textarea.tpl", 'smarty_include_vars' => array('name' => 'new_var_value','cols' => 70,'rows' => 8,'data' => "",'width' => "100%",'style' => "width: 100%;")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	</td>
</tr>

<tr>
	<td colspan="2"><input type="submit" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_add'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td>
</tr>

</table>
</form>

	</td>
</tr>
</table>
</div>
<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('content' => $this->_smarty_vars['capture']['dialog'],'title' => $this->_tpl_vars['lng']['lbl_edit_language_entries'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

<br />

<?php ob_start(); ?>
<form method="post" action="languages.php">

<table>
<tr>
	<td><b><?php echo $this->_tpl_vars['lng']['lbl_default_customer_language']; ?>
:</b></td>
	<td>
	<select name="new_customer_language">
		<option value=""><?php echo $this->_tpl_vars['lng']['lbl_select_one']; ?>
</option>
<?php $_from = $this->_tpl_vars['languages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['l']):
 if ($this->_tpl_vars['l']['disabled'] != 'Y' || $this->_tpl_vars['config']['default_customer_language'] == $this->_tpl_vars['l']['code']): ?>
		<option value="<?php echo $this->_tpl_vars['l']['code']; ?>
"<?php if ($this->_tpl_vars['config']['default_customer_language'] == $this->_tpl_vars['l']['code']): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['l']['language']; ?>
</option>
<?php endif;  endforeach; endif; unset($_from); ?>
	</select>
	</td>
</tr>
<tr>
	<td><b><?php echo $this->_tpl_vars['lng']['lbl_default_admin_language']; ?>
:</b></td>
	<td>
	<select name="new_admin_language">
		<option value=""><?php echo $this->_tpl_vars['lng']['lbl_select_one']; ?>
</option>
<?php $_from = $this->_tpl_vars['languages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['l']):
 if ($this->_tpl_vars['l']['disabled'] != 'Y' || $this->_tpl_vars['config']['default_admin_language'] == $this->_tpl_vars['l']['code']): ?>
		<option value="<?php echo $this->_tpl_vars['l']['code']; ?>
"<?php if ($this->_tpl_vars['config']['default_admin_language'] == $this->_tpl_vars['l']['code']): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['l']['language']; ?>
</option>
<?php endif;  endforeach; endif; unset($_from); ?>
	</select>
	</td>
</tr>
<tr>
	<td colspan="2"><input type="submit" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_update'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td>
</tr>
</table>

<input type="hidden" name="mode" value="change_defaults" />
<input type="hidden" name="language" value="<?php echo ((is_array($_tmp=$GLOBALS['HTTP_GET_VARS']['language'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" />
</form>
<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('content' => $this->_smarty_vars['capture']['dialog'],'title' => $this->_tpl_vars['lng']['lbl_default_languages'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<p />
<?php ob_start(); ?>
<form method="post" action="languages.php" enctype="multipart/form-data" name="newlanguageform">
<input type="hidden" name="mode" value="add_lang" />
<table>
<tr>
	<td><b><?php echo $this->_tpl_vars['lng']['lbl_choose_language']; ?>
:</b></td>
	<td>
	<select name="new_language">
		<option value=""><?php echo $this->_tpl_vars['lng']['lbl_select_one']; ?>
</option>
<?php $_from = $this->_tpl_vars['new_languages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['l']):
?>
		<option value="<?php echo $this->_tpl_vars['l']['code']; ?>
"><?php echo $this->_tpl_vars['l']['language']; ?>
</option>
<?php endforeach; endif; unset($_from); ?>
	</select>
	</td>
</tr>
</table>
<br />
<table>
<tr>
	<td><b><?php echo $this->_tpl_vars['lng']['lbl_csv_delimiter']; ?>
:</b></td>
	<td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "provider/main/ie_delimiter.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>
</table>

<table>
<tr>
	<td colspan="2">
		<script type="text/javascript">
		<!--
		filesrc='1';
		-->
		</script>
		<br />
		<b><?php echo $this->_tpl_vars['lng']['txt_source_import_file']; ?>
:</b>
		<table cellpadding="0" cellspacing="0">
		<tr>
			<td width="20">&nbsp;</td>
			<td><input type="radio" id="source_server" name="source" value="server"<?php if ($this->_tpl_vars['import_data'] == '' || $this->_tpl_vars['import_data']['source'] == 'server'): ?> checked="checked"<?php endif; ?> onclick="javascript: if (filesrc=='1') return true; visibleBox(filesrc, 1); filesrc='1'; visibleBox(filesrc, 1);" /></td>
			<td><label for="source_server"><?php echo $this->_tpl_vars['lng']['lbl_server']; ?>
</label></td>
		</tr>
		<tr>
			<td width="20">&nbsp;</td>
			<td><input type="radio" id="source_upload" name="source" value="upload"<?php if ($this->_tpl_vars['import_data']['source'] == 'upload'): ?> checked="checked"<?php endif; ?> onclick="javascript: if (filesrc=='2') return true; visibleBox(filesrc, 1); filesrc='2'; visibleBox(filesrc, 1);" /></td>
			<td><label for="source_upload"><?php echo $this->_tpl_vars['lng']['lbl_home_computer']; ?>
</label></td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td colspan="2"><br />
	<div id="box1" <?php if ($this->_tpl_vars['import_data'] != '' && $this->_tpl_vars['import_data']['source'] != 'server'): ?> style="display: none;"<?php endif; ?>>
	<b><?php echo $this->_tpl_vars['lng']['txt_csv_file_is_located_on_the_server']; ?>
:</b>
	<br />
	<input type="text" size="60" name="localfile" value="<?php echo $this->_tpl_vars['localfile']; ?>
" /> 
	<br />
	<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['txt_csv_file_is_located_on_the_server_expl'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'my_files_location', $this->_tpl_vars['my_files_location']) : smarty_modifier_substitute($_tmp, 'my_files_location', $this->_tpl_vars['my_files_location'])); ?>

	</div>

	<div id="box2"<?php if ($this->_tpl_vars['import_data'] == '' || $this->_tpl_vars['import_data']['source'] != 'upload'): ?> style="display: none;"<?php endif; ?>>
	<b><?php echo $this->_tpl_vars['lng']['lbl_csv_file_for_upload']; ?>
:</b><br /><input type="file" size="60" name="import_file" />

	<?php if ($this->_tpl_vars['upload_max_filesize']): ?>
	<br /><font class="Star"><?php echo $this->_tpl_vars['lng']['lbl_warning']; ?>
!</font> <?php echo $this->_tpl_vars['lng']['txt_max_file_size_that_can_be_uploaded']; ?>
: <?php echo $this->_tpl_vars['upload_max_filesize']; ?>
b.
	<?php endif; ?>
	</div>

	</td>
</tr>	
</table>

<p />
<?php echo $this->_tpl_vars['lng']['txt_import_language_note']; ?>

<p />
<input type="submit" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_add_update_language'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
</form>
<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('content' => $this->_smarty_vars['capture']['dialog'],'title' => $this->_tpl_vars['lng']['lbl_add_new_language'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
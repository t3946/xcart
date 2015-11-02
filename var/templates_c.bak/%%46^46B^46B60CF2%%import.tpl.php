<?php /* Smarty version 2.6.12, created on 2011-10-11 05:43:52
         compiled from main/import.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'substitute', 'main/import.tpl', 29, false),array('modifier', 'escape', 'main/import.tpl', 99, false),array('modifier', 'default', 'main/import.tpl', 165, false),array('modifier', 'strip_tags', 'main/import.tpl', 198, false),)), $this); ?>
<?php func_load_lang($this, "main/import.tpl","txt_import_error,lbl_view_entire_import_log,txt_log_file_error,lbl_back_to_import_page,txt_import_data_note,lbl_sample_import_file,lbl_import_data_provider,lbl_import_data_provider,txt_data_provider_login,lbl_data_provider_login,lbl_change,lbl_import_data,txt_import_data_note2,txt_import_data_types_js_warning,lbl_csv_delimiter,txt_source_import_file,lbl_server,lbl_home_computer,lbl_url,txt_csv_file_is_located_on_the_server,txt_csv_file_is_located_on_the_server_expl,lbl_csv_file_for_upload,lbl_warning,txt_max_file_size_that_can_be_uploaded,txt_csv_file_is_located_on_the_remote,lbl_import_options,lbl_import,lbl_view_import_log,lbl_import_data"); ?>
<?php ob_start(); ?>

<?php if ($this->_tpl_vars['show_error'] != ""): ?>

<?php echo $this->_tpl_vars['lng']['txt_import_error']; ?>


<br /><br />
<?php if ($this->_tpl_vars['import_log_url']): ?>
<div align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/button.tpl", 'smarty_include_vars' => array('href' => ($this->_tpl_vars['import_log_url']),'button_title' => $this->_tpl_vars['lng']['lbl_view_entire_import_log'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></div>

<br />

<table cellspacing="0" cellpadding="1" width="100%">
<tr>
	<td bgcolor="#CCCCCC">

<table cellspacing="0" cellpadding="10" width="100%">
<tr>
	<td class="SectionBox"><?php echo $this->_tpl_vars['import_log_content']; ?>
</td>
</tr>
</table>

	</td>
</tr>
</table>
<?php else:  echo ((is_array($_tmp=$this->_tpl_vars['lng']['txt_log_file_error'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'file', $this->_tpl_vars['import_log_file']) : smarty_modifier_substitute($_tmp, 'file', $this->_tpl_vars['import_log_file'])); ?>

<?php endif; ?>

<br /><br />

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/button.tpl", 'smarty_include_vars' => array('href' => "import.php",'button_title' => $this->_tpl_vars['lng']['lbl_back_to_import_page'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<br /><br />

<?php else: ?>

<?php echo $this->_tpl_vars['lng']['txt_import_data_note']; ?>


<br /><br />

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/button.tpl", 'smarty_include_vars' => array('button_title' => $this->_tpl_vars['lng']['lbl_sample_import_file'],'href' => "javascript:window.open('popup_info.php?action=IMPORT','IMPORT_HELP','width=600,height=460,toolbar=no,status=no,scrollbars=yes,resizable=no,menubar=no,location=no,direction=no');")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<br /><br />

<?php if ($this->_tpl_vars['need_select_provider']): ?>

<?php if ($this->_tpl_vars['data_provider'] != ''):  $this->assign('display_none_open', "display: none; ");  $this->assign('idp_visible', true);  else:  $this->assign('display_none_close', "display: none; ");  $this->assign('idp_visible', false);  endif; ?>

<div align="right">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/visiblebox_link.tpl", 'smarty_include_vars' => array('mark' => '4','title' => $this->_tpl_vars['lng']['lbl_import_data_provider'],'visible' => $this->_tpl_vars['idp_visible'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>

<br /><br />

<table cellpadding="0" cellspacing="0" width="100%" style="<?php echo $this->_tpl_vars['display_none_close']; ?>
" id="box4"><tr><td>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_import_data_provider'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php echo $this->_tpl_vars['lng']['txt_data_provider_login']; ?>


<form action="import.php" method="post" name="changeproviderform">
<input type="hidden" name="action" value="change_provider" />

<table cellpadding="0" cellspacing="3">

<tr>
	<td><b><?php echo $this->_tpl_vars['lng']['lbl_data_provider_login']; ?>
:</b></td>
	<td><input type="text" size="35" name="data_provider" value="<?php echo $this->_tpl_vars['data_provider']; ?>
" /></td>
	<td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/button.tpl", 'smarty_include_vars' => array('href' => "javascript: document.changeproviderform.submit();",'type' => 'input','button_title' => $this->_tpl_vars['lng']['lbl_change'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>

</table>
</form>

<br /><br /><br />

	</td>
</tr>
</table>
<?php endif; ?>


<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_import_data'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php echo $this->_tpl_vars['lng']['txt_import_data_note2']; ?>


<script type="text/javascript">
<!--

var drop_alert = '<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['txt_import_data_types_js_warning'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
';

<?php echo '
function checkDrops(f) {
	for (var x = 0; x < f.elements.length; x++) {
		if (f.elements[x].name.search(/^drop\\[/) != -1) {
			if (f.elements[x].checked)
				return confirm(drop_alert);
		}
	}
	return true;
}
'; ?>

-->
</script>
<form action="import.php" method="post" enctype="multipart/form-data" name="importdata_form" onsubmit="javascript: return checkDrops(this);">
<input type="hidden" name="mode" value="import" />

<table cellpadding="5" cellspacing="1" width="100%">

<tr>
	<td valign="top" width="50%">
	<b><?php echo $this->_tpl_vars['lng']['lbl_csv_delimiter']; ?>
:</b><br /><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "provider/main/ie_delimiter.tpl", 'smarty_include_vars' => array('saved_delimiter' => $this->_tpl_vars['import_data']['delimiter'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	</td>
</tr>

<tr>
	<td colspan="2">
<script type="text/javascript">
<!--
<?php if ($this->_tpl_vars['import_data'] == '' || $this->_tpl_vars['import_data']['source'] == 'server'): ?>
filesrc='1';
<?php elseif ($this->_tpl_vars['import_data']['source'] == 'upload'): ?>
filesrc='2';
<?php else: ?>
filesrc='3';
<?php endif; ?>
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
<tr>
	<td width="20">&nbsp;</td>
	<td><input type="radio" id="source_url" name="source" value="url"<?php if ($this->_tpl_vars['import_data']['source'] == 'url'): ?> checked="checked"<?php endif; ?> onclick="javascript: if (filesrc=='3') return true; visibleBox(filesrc, 1); filesrc='3'; visibleBox(filesrc, 1);" /></td>
	<td><label for="source_url"><?php echo $this->_tpl_vars['lng']['lbl_url']; ?>
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
<input type="text" size="70" name="localfile" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['import_data']['localfile'])) ? $this->_run_mod_handler('default', true, $_tmp, ($this->_tpl_vars['my_files_location'])."/import.csv") : smarty_modifier_default($_tmp, ($this->_tpl_vars['my_files_location'])."/import.csv")); ?>
" />
<br />
<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['txt_csv_file_is_located_on_the_server_expl'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'my_files_location', $this->_tpl_vars['my_files_location']) : smarty_modifier_substitute($_tmp, 'my_files_location', $this->_tpl_vars['my_files_location'])); ?>

</div>

<div id="box2"<?php if ($this->_tpl_vars['import_data'] == '' || $this->_tpl_vars['import_data']['source'] != 'upload'): ?> style="display: none;"<?php endif; ?>>
<b><?php echo $this->_tpl_vars['lng']['lbl_csv_file_for_upload']; ?>
:</b><br /><input type="file" size="70" name="userfile" />

<?php if ($this->_tpl_vars['upload_max_filesize']): ?>
<br /><font class="Star"><?php echo $this->_tpl_vars['lng']['lbl_warning']; ?>
!</font> <?php echo $this->_tpl_vars['lng']['txt_max_file_size_that_can_be_uploaded']; ?>
: <?php echo $this->_tpl_vars['upload_max_filesize']; ?>
b.
<?php endif; ?>
</div>

<div id="box3"<?php if ($this->_tpl_vars['import_data'] == '' || $this->_tpl_vars['import_data']['source'] != 'url'): ?> style="display: none;"<?php endif; ?>>
<b><?php echo $this->_tpl_vars['lng']['txt_csv_file_is_located_on_the_remote']; ?>
:</b>
<br />
<input type="text" size="70" name="urlfile" value="<?php echo $this->_tpl_vars['import_data']['urlfile']; ?>
" />
<br />&nbsp;
</div>

	</td>
</tr>
</table>

<br /><br />

<div align="right">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/visiblebox_link.tpl", 'smarty_include_vars' => array('mark' => '5','title' => $this->_tpl_vars['lng']['lbl_import_options'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/import_options.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<br /><br />

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/rarrow.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> <input type="submit" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_import'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />

</form>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/include_js.tpl", 'smarty_include_vars' => array('src' => "reset.js")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<script type="text/javascript">
<!--
var importdata_form_def = new Array();
importdata_form_def[0] = new Array('options[category_sep]', '/');
importdata_form_def[1] = new Array('options[categoryid]', '0');
importdata_form_def[2] = new Array('options[images_directory]', '');
importdata_form_def[3] = new Array('options[crypt_order_details]', 'Y');
importdata_form_def[4] = new Array('options[crypt_password]', 'Y');
-->
</script>
<?php if ($this->_tpl_vars['import_log_url']): ?>
<div align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/button.tpl", 'smarty_include_vars' => array('href' => ($this->_tpl_vars['import_log_url']),'button_title' => $this->_tpl_vars['lng']['lbl_view_import_log'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></div>
<?php endif; ?>

<?php endif; ?>

<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_import_data'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
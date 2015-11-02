<?php /* Smarty version 2.6.12, created on 2011-10-11 05:37:57
         compiled from modules/Froogle/froogle.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'modules/Froogle/froogle.tpl', 32, false),array('modifier', 'default', 'modules/Froogle/froogle.tpl', 42, false),array('modifier', 'strip_tags', 'modules/Froogle/froogle.tpl', 51, false),)), $this); ?>
<?php func_load_lang($this, "modules/Froogle/froogle.tpl","lbl_froogle_export,txt_froogle_note,txt_froogle_format_note,lbl_froogle_select_language,lbl_froogle_enter_language_code,lbl_filename,lbl_generate,lbl_download,lbl_upload,lbl_froogle_export"); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "page_title.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_froogle_export'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php echo $this->_tpl_vars['lng']['txt_froogle_note']; ?>


<br /><br />

<!-- IN THIS SECTION -->

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog_tools.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<!-- IN THIS SECTION -->

<br />

<?php ob_start();  echo $this->_tpl_vars['lng']['txt_froogle_format_note']; ?>

<br />
<br />

<form action="froogle.php" method="post" name="froogleform">
<input type="hidden" name="mode" value="fcreate" />
<table cellspacing="5" cellpadding="0">

<tr>
    <td style="padding-bottom: 5px;"><?php echo $this->_tpl_vars['lng']['lbl_froogle_select_language']; ?>
</td>
    <td>
<?php if ($this->_tpl_vars['all_languages_cnt'] > 1): ?>
<select name="froogle_lng">
<?php $_from = $this->_tpl_vars['all_languages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['l']):
?>
	<option value="<?php echo ((is_array($_tmp=$this->_tpl_vars['l']['code'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"<?php if ($this->_tpl_vars['froogle_lng'] == $this->_tpl_vars['l']['code']): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['l']['language']; ?>
</option>
<?php endforeach; endif; unset($_from); ?>
</select>
<?php else:  echo $this->_tpl_vars['all_languages']['0']['language']; ?>

<?php endif; ?>
	</td>
</tr>
<tr>
	<td width="50%" style="padding-bottom: 5px;"><?php echo $this->_tpl_vars['lng']['lbl_froogle_enter_language_code']; ?>
</td>
	<td><input type="text" name="froogle_iso" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['froogle_iso'])) ? $this->_run_mod_handler('default', true, $_tmp, 'en') : smarty_modifier_default($_tmp, 'en')); ?>
" maxlength="2" size="2" /></td>
</tr>

<tr>
	<td><?php echo $this->_tpl_vars['lng']['lbl_filename']; ?>
</td>
	<td><input type="text" name="froogle_file" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['froogle_file'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['def_froogle_file']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['def_froogle_file'])); ?>
" size="25" /></td>
</tr>
<tr>
	<td colspan="2" class="SubmitBox">
	<input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_generate'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: submitForm(this, 'fcreate');" />
	<input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_download'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: submitForm(this, 'fdownload');" />
	<?php if ($this->_tpl_vars['is_ftp_module'] == 'Y'): ?>
	<input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_upload'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: submitForm(this, 'fupload');" />
	<?php endif; ?>
</td>
</tr>
</table>
</form>
<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_froogle_export'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php /* Smarty version 2.6.12, created on 2011-10-11 05:43:52
         compiled from main/import_option_images_directory.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'main/import_option_images_directory.tpl', 7, false),array('modifier', 'substitute', 'main/import_option_images_directory.tpl', 10, false),)), $this); ?>
<?php func_load_lang($this, "main/import_option_images_directory.tpl","txt_directory_where_images_are_located,txt_directory_where_images_are_located_expl"); ?><table cellpadding="1" cellspacing="1" width="100%">
<tr>
	<td><b><?php echo $this->_tpl_vars['lng']['txt_directory_where_images_are_located']; ?>
:</b></td>
</tr>
<tr>
	<td><input type="text" size="55" name="options[images_directory]" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['import_data']['options']['images_directory'])) ? $this->_run_mod_handler('default', true, $_tmp, "") : smarty_modifier_default($_tmp, "")); ?>
" /></td>
</tr>
<tr>
	<td><?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['txt_directory_where_images_are_located_expl'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'my_files_location', $this->_tpl_vars['my_files_location']) : smarty_modifier_substitute($_tmp, 'my_files_location', $this->_tpl_vars['my_files_location'])); ?>
</td>
</tr>
</table>
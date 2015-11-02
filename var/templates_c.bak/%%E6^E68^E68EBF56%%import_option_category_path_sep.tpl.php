<?php /* Smarty version 2.6.12, created on 2011-10-11 05:43:52
         compiled from main/import_option_category_path_sep.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'main/import_option_category_path_sep.tpl', 7, false),)), $this); ?>
<?php func_load_lang($this, "main/import_option_category_path_sep.tpl","txt_category_path_sep,txt_category_path_sep_explain"); ?><table cellpadding="1" cellspacing="1" width="100%">
<tr>
	<td><b><?php echo $this->_tpl_vars['lng']['txt_category_path_sep']; ?>
:</b></td>
</tr>
<tr>
	<td><input type="text" name="options[category_sep]" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['import_data']['options']['category_sep'])) ? $this->_run_mod_handler('default', true, $_tmp, "/") : smarty_modifier_default($_tmp, "/")); ?>
" /></td>
</tr>
<tr>
	<td><?php echo $this->_tpl_vars['lng']['txt_category_path_sep_explain']; ?>
</td>
</tr>
</table>
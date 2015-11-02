<?php /* Smarty version 2.6.12, created on 2011-10-11 05:43:52
         compiled from main/import_option_default_category.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'main/import_option_default_category.tpl', 7, false),)), $this); ?>
<?php func_load_lang($this, "main/import_option_default_category.tpl","txt_default_category,txt_default_category_explain"); ?><table cellpadding="1" cellspacing="1" width="100%">
<tr>
	<td><b><?php echo $this->_tpl_vars['lng']['txt_default_category']; ?>
:</b></td>
</tr>
<tr>
	<td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/category_selector.tpl", 'smarty_include_vars' => array('field' => "options[categoryid]",'categoryid' => ((is_array($_tmp=@$this->_tpl_vars['import_data']['options']['categoryid'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>
<tr>
	<td><?php echo $this->_tpl_vars['lng']['txt_default_category_explain']; ?>
</td>
</tr>
</table>
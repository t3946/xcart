<?php /* Smarty version 2.6.12, created on 2011-10-11 05:43:52
         compiled from main/import_options.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'lower', 'main/import_options.tpl', 43, false),array('modifier', 'upper', 'main/import_options.tpl', 68, false),array('function', 'cycle', 'main/import_options.tpl', 61, false),)), $this); ?>
<?php func_load_lang($this, "main/import_options.tpl","lbl_import_options,txt_import_options_text,lbl_reset,txt_import_data_types,lbl_check_all,lbl_uncheck_all,lbl_drop,lbl_data_type,lbl_columns,lbl_note"); ?>
<table cellpadding="0" cellspacing="0" width="100%" style="display: none;" id="box5">
<tr>
	<td>

<br /><br />

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_import_options'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<table cellspacing="0" cellpadding="0" width="100%">
<tr>
	<td width="100%"><?php echo $this->_tpl_vars['lng']['txt_import_options_text']; ?>
</td>
	<td>&nbsp;&nbsp;&nbsp;</td>
	<td valign="top"><a href="javascript: void(0);" onclick="javascript: reset_form('importdata_form', importdata_form_def); change_all(false);"><?php echo $this->_tpl_vars['lng']['lbl_reset']; ?>
</a></td>
</tr>
</table>

<br /><br />

<?php if ($this->_tpl_vars['import_options'] != ''): ?>
<table cellpadding="5" cellspacing="1" width="100%">
<?php $_from = $this->_tpl_vars['import_options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
<tr>
	<td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['v'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>
<?php endforeach; endif; unset($_from); ?>
</table>
<?php endif; ?>

<table cellpadding="5" cellspacing="1" width="100%">
<tr>
	<td>
<br /><br />

<?php echo $this->_tpl_vars['lng']['txt_import_data_types']; ?>


<br /><br />

<?php if ($this->_tpl_vars['import_specification'] != ''): ?>
<script type="text/javascript" language="JavaScript 1.2">
<!--
var checkboxes_form = 'importdata_form';
var checkboxes = new Array(<?php $_from = $this->_tpl_vars['import_specification']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>'drop[<?php echo ((is_array($_tmp=$this->_tpl_vars['k'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)); ?>
]',<?php endforeach; endif; unset($_from); ?>'');
 
-->
</script>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/include_js.tpl", 'smarty_include_vars' => array('src' => "change_all_checkboxes.js")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div style="line-height:170%"><a href="javascript:change_all(true);"><?php echo $this->_tpl_vars['lng']['lbl_check_all']; ?>
</a> / <a href="javascript:change_all(false);"><?php echo $this->_tpl_vars['lng']['lbl_uncheck_all']; ?>
</a></div>
<?php endif; ?>

<table cellpadding="3" cellspacing="1" width="100%">

<tr class="TableHead">
	<td width="5%" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_drop']; ?>
</td>
	<td width="15%"><?php echo $this->_tpl_vars['lng']['lbl_data_type']; ?>
</td>
	<td width="80%"><?php echo $this->_tpl_vars['lng']['lbl_columns']; ?>
</td>
</tr>

<?php $_from = $this->_tpl_vars['import_specification']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['section_name'] => $this->_tpl_vars['section_data']):
 if (! $this->_tpl_vars['section_data']['no_import']):  echo smarty_function_cycle(array('values' => " , class='TableSubHead'",'assign' => 'tr_class'), $this);?>

<tr<?php echo $this->_tpl_vars['tr_class']; ?>
>
	<td align="center"<?php if ($this->_tpl_vars['section_data']['import_note']): ?> rowspan="2"<?php endif; ?>><input type="checkbox" id="drop_<?php echo ((is_array($_tmp=$this->_tpl_vars['section_name'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)); ?>
" name="drop[<?php echo ((is_array($_tmp=$this->_tpl_vars['section_name'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)); ?>
]" value="Y" /></td>
	<td><label for="drop_<?php echo ((is_array($_tmp=$this->_tpl_vars['section_name'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)); ?>
"><?php echo $this->_tpl_vars['section_name']; ?>
</label></td>
	<td>
<?php $_from = $this->_tpl_vars['section_data']['columns']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['col_name'] => $this->_tpl_vars['col_data']):
 if ($this->_tpl_vars['col_data']['required']): ?>
<b><?php echo ((is_array($_tmp=$this->_tpl_vars['col_name'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</b>
<?php else:  echo ((is_array($_tmp=$this->_tpl_vars['col_name'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>

<?php endif; ?>
&nbsp;
<?php endforeach; endif; unset($_from); ?>
	</td>
</tr>
<?php if ($this->_tpl_vars['section_data']['import_note']): ?>
<tr<?php echo $this->_tpl_vars['tr_class']; ?>
>
	<td colspan="2" style="padding-left: 20px;"><b><?php echo $this->_tpl_vars['lng']['lbl_note']; ?>
:</b> <?php echo $this->_tpl_vars['section_data']['import_note']; ?>
</td>
</tr>
<?php endif;  endif;  endforeach; endif; unset($_from); ?>

</table>

	</td>
</tr>
</table>

	</td>
</tr>
</table>
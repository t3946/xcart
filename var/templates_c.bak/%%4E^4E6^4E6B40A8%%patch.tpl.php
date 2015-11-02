<?php /* Smarty version 2.6.12, created on 2011-10-11 06:47:36
         compiled from admin/main/patch.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'replace', 'admin/main/patch.tpl', 31, false),array('modifier', 'strip_tags', 'admin/main/patch.tpl', 41, false),array('modifier', 'escape', 'admin/main/patch.tpl', 41, false),)), $this); ?>
<?php func_load_lang($this, "admin/main/patch.tpl","lbl_patch_upgrade_center,txt_patch_upgrade_center_top_text,lbl_current_version,lbl_target_version,lbl_no_available_patches,lbl_apply,lbl_check_for_upgrade_patches,lbl_upgrade,txt_patch_apply_note,lbl_patch_file,lbl_or,lbl_patch_url,lbl_reverse,lbl_no,lbl_yes,lbl_apply,lbl_apply_patch,txt_apply_sql_patch_note,lbl_patch_file,lbl_or,lbl_patch_url,lbl_or,lbl_sql_queries,lbl_apply,lbl_apply_sql_patch"); ?>
<?php if ($this->_tpl_vars['all_files_to_patch'] != ""):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/main/patch_apply.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  else:  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "page_title.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_patch_upgrade_center'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php echo $this->_tpl_vars['lng']['txt_patch_upgrade_center_top_text']; ?>


<br /><br />

<?php ob_start(); ?>
<form action="patch.php" method="post">
<input type="hidden" name="mode" value="upgrade" />

<table>

<tr>
	<td><?php echo $this->_tpl_vars['lng']['lbl_current_version']; ?>
:</td>
	<td><b><?php echo $this->_tpl_vars['config']['version']; ?>
</b></td>
</tr>

<tr>
	<td><?php echo $this->_tpl_vars['lng']['lbl_target_version']; ?>
:</td>
	<td>
	<select name="patch_filename">
<?php if ($this->_tpl_vars['target_versions'] == ""): ?>
		<option><?php echo $this->_tpl_vars['lng']['lbl_no_available_patches']; ?>
</option>
<?php else:  unset($this->_sections['ver']);
$this->_sections['ver']['name'] = 'ver';
$this->_sections['ver']['loop'] = is_array($_loop=$this->_tpl_vars['target_versions']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['ver']['show'] = true;
$this->_sections['ver']['max'] = $this->_sections['ver']['loop'];
$this->_sections['ver']['step'] = 1;
$this->_sections['ver']['start'] = $this->_sections['ver']['step'] > 0 ? 0 : $this->_sections['ver']['loop']-1;
if ($this->_sections['ver']['show']) {
    $this->_sections['ver']['total'] = $this->_sections['ver']['loop'];
    if ($this->_sections['ver']['total'] == 0)
        $this->_sections['ver']['show'] = false;
} else
    $this->_sections['ver']['total'] = 0;
if ($this->_sections['ver']['show']):

            for ($this->_sections['ver']['index'] = $this->_sections['ver']['start'], $this->_sections['ver']['iteration'] = 1;
                 $this->_sections['ver']['iteration'] <= $this->_sections['ver']['total'];
                 $this->_sections['ver']['index'] += $this->_sections['ver']['step'], $this->_sections['ver']['iteration']++):
$this->_sections['ver']['rownum'] = $this->_sections['ver']['iteration'];
$this->_sections['ver']['index_prev'] = $this->_sections['ver']['index'] - $this->_sections['ver']['step'];
$this->_sections['ver']['index_next'] = $this->_sections['ver']['index'] + $this->_sections['ver']['step'];
$this->_sections['ver']['first']      = ($this->_sections['ver']['iteration'] == 1);
$this->_sections['ver']['last']       = ($this->_sections['ver']['iteration'] == $this->_sections['ver']['total']);
?>
		<option value="<?php echo ((is_array($_tmp=$this->_tpl_vars['config']['version'])) ? $this->_run_mod_handler('replace', true, $_tmp, ' ', '_') : smarty_modifier_replace($_tmp, ' ', '_')); ?>
-<?php echo ((is_array($_tmp=$this->_tpl_vars['target_versions'][$this->_sections['ver']['index']])) ? $this->_run_mod_handler('replace', true, $_tmp, ' ', '_') : smarty_modifier_replace($_tmp, ' ', '_')); ?>
"><?php echo $this->_tpl_vars['target_versions'][$this->_sections['ver']['index']]; ?>
</option>
<?php endfor; endif;  endif; ?>
	</select>
	</td>
</tr>

<?php if ($this->_tpl_vars['target_versions'] != ""): ?>
<tr>
	<td>&nbsp;</td>
	<td><input type="submit" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_apply'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td>
</tr>
<?php endif; ?>

<tr>
	<td>&nbsp;</td>
	<td>
	<a href="https://secure.qualiteam.biz/customer.php?area=filearea&amp;target=upgrade_pack&amp;brand=xcart&amp;version=<?php echo ((is_array($_tmp=$this->_tpl_vars['config']['version'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
&amp;shop_url=<?php echo ((is_array($_tmp=$this->_tpl_vars['xcart_http_host'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
"><?php echo $this->_tpl_vars['lng']['lbl_check_for_upgrade_patches']; ?>
</a>
	</td>
</tr>

</table>
</form>

<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_upgrade'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<br /><br />

<?php echo $this->_tpl_vars['lng']['txt_patch_apply_note']; ?>


<br /><br />

<?php ob_start(); ?>

<form action="patch.php" enctype="multipart/form-data" method="post">
<input type="hidden" name="mode" value="normal" />

<table>

<tr>
	<td><?php echo $this->_tpl_vars['lng']['lbl_patch_file']; ?>
:</td>
	<td><input type="file" name="patch_file" /></td>
</tr>

<tr>
	<td>&nbsp;</td>
	<td><b><?php echo $this->_tpl_vars['lng']['lbl_or']; ?>
</b></td>
</tr>

<tr>
	<td><?php echo $this->_tpl_vars['lng']['lbl_patch_url']; ?>
:</td>
	<td><input type="text" name="patch_url" size="32" /></td>
</tr>

<tr>
	<td><?php echo $this->_tpl_vars['lng']['lbl_reverse']; ?>
:</td>
	<td>
	<select name="reverse">
		<option value="N"><?php echo $this->_tpl_vars['lng']['lbl_no']; ?>
</option>
		<option value="Y"><?php echo $this->_tpl_vars['lng']['lbl_yes']; ?>
</option>
	</select>
	</td>
</tr>

<tr>
	<td>&nbsp;</td>
	<td><input type="submit" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_apply'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td>
</tr>

</table>
</form>

<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_apply_patch'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<br /><br />

<a name="patch_sql"></a>
<?php echo $this->_tpl_vars['lng']['txt_apply_sql_patch_note']; ?>


<br /><br />

<?php ob_start(); ?>

<form action="patch.php" enctype="multipart/form-data" method="post">
<input type="hidden" name="mode" value="sql" />

<table>

<tr>
	<td><?php echo $this->_tpl_vars['lng']['lbl_patch_file']; ?>
:</td>
	<td><input type="file" name="patch_file" /></td>
</tr>

<tr>
	<td>&nbsp;</td>
	<td><b><?php echo $this->_tpl_vars['lng']['lbl_or']; ?>
</b></td>
</tr>

<tr>
	<td><?php echo $this->_tpl_vars['lng']['lbl_patch_url']; ?>
:</td>
	<td><input type="text" name="patch_url" size="32" /></td>
</tr>

<tr>
	<td>&nbsp;</td>
	<td><b><?php echo $this->_tpl_vars['lng']['lbl_or']; ?>
</b></td>
</tr>

<tr>
	<td><?php echo $this->_tpl_vars['lng']['lbl_sql_queries']; ?>
:</td>
	<td><textarea cols="48" rows="5" name="patch_query"></textarea></td>
</tr>

<tr>
	<td>&nbsp;</td>
	<td><input type="submit" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_apply'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td>
</tr>

</table>
</form>

<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_apply_sql_patch'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php endif; ?>

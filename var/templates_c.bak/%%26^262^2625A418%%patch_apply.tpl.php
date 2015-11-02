<?php /* Smarty version 2.6.12, created on 2011-10-11 06:47:48
         compiled from admin/main/patch_apply.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'strip_tags', 'admin/main/patch_apply.tpl', 30, false),array('modifier', 'escape', 'admin/main/patch_apply.tpl', 30, false),)), $this); ?>
<?php func_load_lang($this, "admin/main/patch_apply.tpl","lbl_applying_patch,txt_applying_patch_step_1,txt_testing_phase_result,txt_patch_application_error,lbl_go_back,txt_some_files_will_be_ignored,lbl_patch_text,txt_have_could_not_patch,lbl_tick_here_to_apply_patch,lbl_go_back,lbl_apply_patch,txt_patch_applying_note,txt_applying_patch_step_2,lbl_files_patch_status,lbl_files_excluded_from_patch,lbl_patch_results,lbl_patch_log,txt_correct_errors,lbl_go_back,txt_files_could_not_be_patched,txt_correct_errors,lbl_go_back,txt_files_could_not_be_patched,txt_patch_applied_successfuly,lbl_finish,lbl_applying_patch"); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "page_title.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_applying_patch'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  ob_start();  if (( $this->_tpl_vars['patch_type'] == 'text' || $this->_tpl_vars['patch_type'] == 'upgrade' ) && $this->_tpl_vars['patch_phase'] != 'upgrade_final'): ?>

<br /><br />

<b><?php echo $this->_tpl_vars['lng']['txt_applying_patch_step_1']; ?>
</b>

<br /><br />

<?php echo $this->_tpl_vars['lng']['txt_testing_phase_result']; ?>


<br /><br />

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/main/patch_apply_tbl.tpl", 'smarty_include_vars' => array('files' => $this->_tpl_vars['patch_files'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if ($this->_tpl_vars['ready_to_patch'] != 1): ?>
<br /><br />

<?php echo $this->_tpl_vars['lng']['txt_patch_application_error']; ?>


<br /><br />

<form action="patch.php" method="post">
<input type="hidden" name="mode" value="<?php echo $this->_tpl_vars['mode']; ?>
" />
<input type="hidden" name="patch_filename" value="<?php echo $this->_tpl_vars['patch_filename']; ?>
" />
<input type="hidden" name="reverse" value="<?php echo $this->_tpl_vars['reverse']; ?>
" />
<input type="submit" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_go_back'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
</form>
<?php endif;  endif; ?>

<?php if ($this->_tpl_vars['ready_to_patch'] == '1' && $this->_tpl_vars['patch_phase'] != 'upgrade_final'): ?>

<?php if ($this->_tpl_vars['mode'] != 'sql'): ?>
<br /><br />
<?php echo $this->_tpl_vars['lng']['txt_some_files_will_be_ignored']; ?>

<br /><br />
<?php endif; ?>

<form action="patch.php" method="post" name="step1form">
<input type="hidden" name="mode" value="<?php echo $this->_tpl_vars['mode']; ?>
" />
<?php echo $this->_tpl_vars['lng']['lbl_patch_text']; ?>
:<br />
<textarea cols="60" rows="10"><?php echo $this->_tpl_vars['patch_text']; ?>
</textarea>
<p>
<input type="hidden" name="patch_filename" value="<?php echo $this->_tpl_vars['patch_filename']; ?>
" />
<input type="hidden" name="reverse" value="<?php echo $this->_tpl_vars['reverse']; ?>
" />
<input type="hidden" name="confirmed" value="Y" />

<?php if ($this->_tpl_vars['could_not_patch'] != '0' && $this->_tpl_vars['mode'] != 'sql'):  echo $this->_tpl_vars['lng']['txt_have_could_not_patch']; ?>

<br /><?php echo $this->_tpl_vars['lng']['lbl_tick_here_to_apply_patch']; ?>

<input type="checkbox" name="try_all" checked="checked" />
<br /><br />
<?php endif; ?>

<input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_go_back'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="document.step1form.confirmed.value='';document.step1form.submit();" />
&nbsp;
&nbsp;
<input type="submit" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_apply_patch'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
</form>
<?php endif; ?>

<?php if (! empty ( $this->_tpl_vars['files_to_patch'] )): ?>

<?php echo $this->_tpl_vars['lng']['txt_patch_applying_note']; ?>


<?php elseif ($this->_tpl_vars['confirmed'] != ""): ?>

<?php if ($this->_tpl_vars['patch_type'] == 'text' || $this->_tpl_vars['patch_type'] == 'upgrade'): ?>
<br /><br />
<b><?php echo $this->_tpl_vars['lng']['txt_applying_patch_step_2']; ?>
</b>
<br /><br />
<?php endif; ?>

<?php if ($this->_tpl_vars['patch_phase'] == 'upgrade_final'):  if ($this->_tpl_vars['patched_files'] != ""): ?>
<p>
<b><?php echo $this->_tpl_vars['lng']['lbl_files_patch_status']; ?>
:</b>
</p>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/main/patch_apply_tbl.tpl", 'smarty_include_vars' => array('files' => $this->_tpl_vars['patched_files'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  if ($this->_tpl_vars['excluded_files'] != ""): ?>
<p>
<b><?php echo $this->_tpl_vars['lng']['lbl_files_excluded_from_patch']; ?>
:</b>
</p>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/main/patch_apply_tbl.tpl", 'smarty_include_vars' => array('files' => $this->_tpl_vars['excluded_files'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
<p>
<b><?php echo $this->_tpl_vars['lng']['lbl_patch_results']; ?>
</b>
<p>
<?php unset($this->_sections['line']);
$this->_sections['line']['name'] = 'line';
$this->_sections['line']['loop'] = is_array($_loop=$this->_tpl_vars['patch_result']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['line']['show'] = true;
$this->_sections['line']['max'] = $this->_sections['line']['loop'];
$this->_sections['line']['step'] = 1;
$this->_sections['line']['start'] = $this->_sections['line']['step'] > 0 ? 0 : $this->_sections['line']['loop']-1;
if ($this->_sections['line']['show']) {
    $this->_sections['line']['total'] = $this->_sections['line']['loop'];
    if ($this->_sections['line']['total'] == 0)
        $this->_sections['line']['show'] = false;
} else
    $this->_sections['line']['total'] = 0;
if ($this->_sections['line']['show']):

            for ($this->_sections['line']['index'] = $this->_sections['line']['start'], $this->_sections['line']['iteration'] = 1;
                 $this->_sections['line']['iteration'] <= $this->_sections['line']['total'];
                 $this->_sections['line']['index'] += $this->_sections['line']['step'], $this->_sections['line']['iteration']++):
$this->_sections['line']['rownum'] = $this->_sections['line']['iteration'];
$this->_sections['line']['index_prev'] = $this->_sections['line']['index'] - $this->_sections['line']['step'];
$this->_sections['line']['index_next'] = $this->_sections['line']['index'] + $this->_sections['line']['step'];
$this->_sections['line']['first']      = ($this->_sections['line']['iteration'] == 1);
$this->_sections['line']['last']       = ($this->_sections['line']['iteration'] == $this->_sections['line']['total']);
 echo $this->_tpl_vars['patch_result'][$this->_sections['line']['index']]; ?>
<br />
<?php endfor; endif; ?>
<p>
<b><?php echo $this->_tpl_vars['lng']['lbl_patch_log']; ?>
</b>
<p>
<?php unset($this->_sections['line']);
$this->_sections['line']['name'] = 'line';
$this->_sections['line']['loop'] = is_array($_loop=$this->_tpl_vars['patch_log']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['line']['show'] = true;
$this->_sections['line']['max'] = $this->_sections['line']['loop'];
$this->_sections['line']['step'] = 1;
$this->_sections['line']['start'] = $this->_sections['line']['step'] > 0 ? 0 : $this->_sections['line']['loop']-1;
if ($this->_sections['line']['show']) {
    $this->_sections['line']['total'] = $this->_sections['line']['loop'];
    if ($this->_sections['line']['total'] == 0)
        $this->_sections['line']['show'] = false;
} else
    $this->_sections['line']['total'] = 0;
if ($this->_sections['line']['show']):

            for ($this->_sections['line']['index'] = $this->_sections['line']['start'], $this->_sections['line']['iteration'] = 1;
                 $this->_sections['line']['iteration'] <= $this->_sections['line']['total'];
                 $this->_sections['line']['index'] += $this->_sections['line']['step'], $this->_sections['line']['iteration']++):
$this->_sections['line']['rownum'] = $this->_sections['line']['iteration'];
$this->_sections['line']['index_prev'] = $this->_sections['line']['index'] - $this->_sections['line']['step'];
$this->_sections['line']['index_next'] = $this->_sections['line']['index'] + $this->_sections['line']['step'];
$this->_sections['line']['first']      = ($this->_sections['line']['iteration'] == 1);
$this->_sections['line']['last']       = ($this->_sections['line']['iteration'] == $this->_sections['line']['total']);
 echo $this->_tpl_vars['patch_log'][$this->_sections['line']['index']]; ?>
<br />
<?php endfor; endif;  else: ?>
<p>
<?php unset($this->_sections['line']);
$this->_sections['line']['name'] = 'line';
$this->_sections['line']['loop'] = is_array($_loop=$this->_tpl_vars['patch_result']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['line']['show'] = true;
$this->_sections['line']['max'] = $this->_sections['line']['loop'];
$this->_sections['line']['step'] = 1;
$this->_sections['line']['start'] = $this->_sections['line']['step'] > 0 ? 0 : $this->_sections['line']['loop']-1;
if ($this->_sections['line']['show']) {
    $this->_sections['line']['total'] = $this->_sections['line']['loop'];
    if ($this->_sections['line']['total'] == 0)
        $this->_sections['line']['show'] = false;
} else
    $this->_sections['line']['total'] = 0;
if ($this->_sections['line']['show']):

            for ($this->_sections['line']['index'] = $this->_sections['line']['start'], $this->_sections['line']['iteration'] = 1;
                 $this->_sections['line']['iteration'] <= $this->_sections['line']['total'];
                 $this->_sections['line']['index'] += $this->_sections['line']['step'], $this->_sections['line']['iteration']++):
$this->_sections['line']['rownum'] = $this->_sections['line']['iteration'];
$this->_sections['line']['index_prev'] = $this->_sections['line']['index'] - $this->_sections['line']['step'];
$this->_sections['line']['index_next'] = $this->_sections['line']['index'] + $this->_sections['line']['step'];
$this->_sections['line']['first']      = ($this->_sections['line']['iteration'] == 1);
$this->_sections['line']['last']       = ($this->_sections['line']['iteration'] == $this->_sections['line']['total']);
 echo $this->_tpl_vars['patch_result'][$this->_sections['line']['index']]; ?>
<br />
<?php endfor; endif;  endif; ?>

<?php if ($this->_tpl_vars['patch_completed'] != '1' && $this->_tpl_vars['mode'] == 'sql'): ?>
<br /><br />

<font color="red"><?php echo $this->_tpl_vars['lng']['txt_correct_errors']; ?>
</font><br />
<form action="patch.php#patch_sql" method="get">
<input type=submit value="<< <?php echo $this->_tpl_vars['lng']['lbl_go_back']; ?>
" />
</form>

<?php elseif ($this->_tpl_vars['patch_completed'] != '1' && $this->_tpl_vars['mode'] != 'sql'): ?>
<br /><br />

<?php echo $this->_tpl_vars['lng']['txt_files_could_not_be_patched']; ?>


<br /><br />

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/main/patch_apply_tbl.tpl", 'smarty_include_vars' => array('files' => $this->_tpl_vars['failed_files'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<font color="red"><?php echo $this->_tpl_vars['lng']['txt_correct_errors']; ?>
</font><br />
<form action="patch.php" method="post">
<input type="hidden" name="mode" value="<?php echo $this->_tpl_vars['mode']; ?>
" />
<input type="hidden" name="patch_filename" value="<?php echo $this->_tpl_vars['patch_filename']; ?>
" />
<input type="hidden" name="reverse" value="<?php echo $this->_tpl_vars['reverse']; ?>
" />
<input type="submit" value="&lt;&lt; <?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_go_back'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)); ?>
" />
</form>

<?php else: ?>

<form action="patch.php" method="get">

<?php if ($this->_tpl_vars['need_manual_patch']): ?>

<?php echo $this->_tpl_vars['lng']['txt_files_could_not_be_patched']; ?>


<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/main/patch_apply_tbl.tpl", 'smarty_include_vars' => array('files' => $this->_tpl_vars['failed_files'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php else: ?>

<font color="green"><?php echo $this->_tpl_vars['lng']['txt_patch_applied_successfuly']; ?>
</font>

<?php endif; ?>

<br /><br />

<input type="submit" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_finish'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />

<?php endif; ?>

</form>

<?php endif;  $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_applying_patch'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
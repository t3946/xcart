<?php /* Smarty version 2.6.12, created on 2011-10-11 05:37:57
         compiled from dialog_tools.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'assign_ext', 'dialog_tools.tpl', 43, false),array('function', 'math', 'dialog_tools.tpl', 61, false),)), $this); ?>
<?php func_load_lang($this, "dialog_tools.tpl","lbl_in_this_section,lbl_see_also"); ?><?php if ($this->_tpl_vars['dialog_tools_data']): ?>

<?php $this->assign('left', $this->_tpl_vars['dialog_tools_data']['left']);  $this->assign('right', $this->_tpl_vars['dialog_tools_data']['right']);  if ($this->_tpl_vars['dialog_tools_data']['columns']):  $this->assign('columns', $this->_tpl_vars['dialog_tools_data']['columns']);  else:  $this->assign('columns', 1);  endif; ?>

<table cellpadding="0" cellspacing="0" width="100%">

<tr> 
<td class="NavDialogBorder" height="15" valign="bottom">
<table width="100%" cellspacing="0" cellpadding="0">

<tr>
<?php if ($this->_tpl_vars['left'] || $this->_tpl_vars['dialog_tools_data']['mc_left']): ?>
<td class="NavDialogTitle"><?php echo $this->_tpl_vars['lng']['lbl_in_this_section']; ?>
:</td>
<?php endif;  if ($this->_tpl_vars['right']): ?>
<td class="NavDialogTitle"><?php echo $this->_tpl_vars['lng']['lbl_see_also']; ?>
:</td>
<?php endif; ?>
</tr>

</table></td>
</tr>

<tr> 
<td class="NavDialogBorder">
<table cellpadding="10" cellspacing="1" width="100%">

<tr> 
<td valign="top" class="NavDialogBox">
<table cellpadding="0" cellspacing="1" width="100%">

<tr>
<?php if ($this->_tpl_vars['left'] || $this->_tpl_vars['dialog_tools_data']['mc_left']): ?><td width="50%" valign="top">
<?php if ($this->_tpl_vars['columns'] > 1): ?>

<?php if ($this->_tpl_vars['dialog_tools_data']['mc_left']):  echo smarty_function_assign_ext(array('var' => 'table_rows','value' => $this->_tpl_vars['dialog_tools_data']['mc_left']), $this);?>

<?php else:  echo smarty_function_assign_ext(array('var' => "table_rows[0]",'value' => $this->_tpl_vars['left']), $this);?>

<?php endif; ?>

<?php $_from = $this->_tpl_vars['table_rows']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['table_row']):
?>

<?php if ($this->_tpl_vars['table_row']['title'] != ""): ?>
<br />
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['table_row']['title'],'class' => 'red')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

<?php $this->assign('left', $this->_tpl_vars['table_row']['data']); ?>

<?php unset($this->_sections['dt1']);
$this->_sections['dt1']['name'] = 'dt1';
$this->_sections['dt1']['loop'] = is_array($_loop=$this->_tpl_vars['left']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['dt1']['show'] = true;
$this->_sections['dt1']['max'] = $this->_sections['dt1']['loop'];
$this->_sections['dt1']['step'] = 1;
$this->_sections['dt1']['start'] = $this->_sections['dt1']['step'] > 0 ? 0 : $this->_sections['dt1']['loop']-1;
if ($this->_sections['dt1']['show']) {
    $this->_sections['dt1']['total'] = $this->_sections['dt1']['loop'];
    if ($this->_sections['dt1']['total'] == 0)
        $this->_sections['dt1']['show'] = false;
} else
    $this->_sections['dt1']['total'] = 0;
if ($this->_sections['dt1']['show']):

            for ($this->_sections['dt1']['index'] = $this->_sections['dt1']['start'], $this->_sections['dt1']['iteration'] = 1;
                 $this->_sections['dt1']['iteration'] <= $this->_sections['dt1']['total'];
                 $this->_sections['dt1']['index'] += $this->_sections['dt1']['step'], $this->_sections['dt1']['iteration']++):
$this->_sections['dt1']['rownum'] = $this->_sections['dt1']['iteration'];
$this->_sections['dt1']['index_prev'] = $this->_sections['dt1']['index'] - $this->_sections['dt1']['step'];
$this->_sections['dt1']['index_next'] = $this->_sections['dt1']['index'] + $this->_sections['dt1']['step'];
$this->_sections['dt1']['first']      = ($this->_sections['dt1']['iteration'] == 1);
$this->_sections['dt1']['last']       = ($this->_sections['dt1']['iteration'] == $this->_sections['dt1']['total']);
 endfor; endif; ?>

<?php $this->assign('total_rows', $this->_sections['dt1']['total']);  echo smarty_function_math(array('equation' => "ceil(x/y)",'x' => $this->_tpl_vars['total_rows'],'y' => $this->_tpl_vars['columns'],'assign' => 'rows'), $this);?>

<?php echo smarty_function_math(array('equation' => "floor(x/y)",'x' => 100,'y' => $this->_tpl_vars['columns'],'assign' => 'cell_width'), $this);?>


<table cellpadding="1" cellspacing="1" width="100%">

<tr>
<?php unset($this->_sections['col']);
$this->_sections['col']['name'] = 'col';
$this->_sections['col']['loop'] = is_array($_loop=$this->_tpl_vars['columns']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['col']['show'] = true;
$this->_sections['col']['max'] = $this->_sections['col']['loop'];
$this->_sections['col']['step'] = 1;
$this->_sections['col']['start'] = $this->_sections['col']['step'] > 0 ? 0 : $this->_sections['col']['loop']-1;
if ($this->_sections['col']['show']) {
    $this->_sections['col']['total'] = $this->_sections['col']['loop'];
    if ($this->_sections['col']['total'] == 0)
        $this->_sections['col']['show'] = false;
} else
    $this->_sections['col']['total'] = 0;
if ($this->_sections['col']['show']):

            for ($this->_sections['col']['index'] = $this->_sections['col']['start'], $this->_sections['col']['iteration'] = 1;
                 $this->_sections['col']['iteration'] <= $this->_sections['col']['total'];
                 $this->_sections['col']['index'] += $this->_sections['col']['step'], $this->_sections['col']['iteration']++):
$this->_sections['col']['rownum'] = $this->_sections['col']['iteration'];
$this->_sections['col']['index_prev'] = $this->_sections['col']['index'] - $this->_sections['col']['step'];
$this->_sections['col']['index_next'] = $this->_sections['col']['index'] + $this->_sections['col']['step'];
$this->_sections['col']['first']      = ($this->_sections['col']['iteration'] == 1);
$this->_sections['col']['last']       = ($this->_sections['col']['iteration'] == $this->_sections['col']['total']);
?>
<td width="<?php echo $this->_tpl_vars['cell_width']; ?>
%" valign="top">

<?php echo smarty_function_math(array('equation' => "x*y",'x' => $this->_tpl_vars['rows'],'y' => $this->_sections['col']['index'],'assign' => 'start_row'), $this);?>


<?php unset($this->_sections['dt1']);
$this->_sections['dt1']['name'] = 'dt1';
$this->_sections['dt1']['loop'] = is_array($_loop=$this->_tpl_vars['left']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['dt1']['start'] = (int)$this->_tpl_vars['start_row'];
$this->_sections['dt1']['max'] = (int)$this->_tpl_vars['rows'];
$this->_sections['dt1']['show'] = true;
if ($this->_sections['dt1']['max'] < 0)
    $this->_sections['dt1']['max'] = $this->_sections['dt1']['loop'];
$this->_sections['dt1']['step'] = 1;
if ($this->_sections['dt1']['start'] < 0)
    $this->_sections['dt1']['start'] = max($this->_sections['dt1']['step'] > 0 ? 0 : -1, $this->_sections['dt1']['loop'] + $this->_sections['dt1']['start']);
else
    $this->_sections['dt1']['start'] = min($this->_sections['dt1']['start'], $this->_sections['dt1']['step'] > 0 ? $this->_sections['dt1']['loop'] : $this->_sections['dt1']['loop']-1);
if ($this->_sections['dt1']['show']) {
    $this->_sections['dt1']['total'] = min(ceil(($this->_sections['dt1']['step'] > 0 ? $this->_sections['dt1']['loop'] - $this->_sections['dt1']['start'] : $this->_sections['dt1']['start']+1)/abs($this->_sections['dt1']['step'])), $this->_sections['dt1']['max']);
    if ($this->_sections['dt1']['total'] == 0)
        $this->_sections['dt1']['show'] = false;
} else
    $this->_sections['dt1']['total'] = 0;
if ($this->_sections['dt1']['show']):

            for ($this->_sections['dt1']['index'] = $this->_sections['dt1']['start'], $this->_sections['dt1']['iteration'] = 1;
                 $this->_sections['dt1']['iteration'] <= $this->_sections['dt1']['total'];
                 $this->_sections['dt1']['index'] += $this->_sections['dt1']['step'], $this->_sections['dt1']['iteration']++):
$this->_sections['dt1']['rownum'] = $this->_sections['dt1']['iteration'];
$this->_sections['dt1']['index_prev'] = $this->_sections['dt1']['index'] - $this->_sections['dt1']['step'];
$this->_sections['dt1']['index_next'] = $this->_sections['dt1']['index'] + $this->_sections['dt1']['step'];
$this->_sections['dt1']['first']      = ($this->_sections['dt1']['iteration'] == 1);
$this->_sections['dt1']['last']       = ($this->_sections['dt1']['iteration'] == $this->_sections['dt1']['total']);
 $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog_tools_cell.tpl", 'smarty_include_vars' => array('cell' => $this->_tpl_vars['left'][$this->_sections['dt1']['index']])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endfor; endif; ?>

</td>
<?php endfor; endif; ?>
</tr>

</table>

<?php endforeach; endif; unset($_from); ?>

<?php else: ?>

<?php $_from = $this->_tpl_vars['left']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['cell']):
 $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog_tools_cell.tpl", 'smarty_include_vars' => array('cell' => $this->_tpl_vars['cell'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endforeach; endif; unset($_from); ?>

<?php endif; ?>
</td>
<?php endif; ?>

<?php if ($this->_tpl_vars['right']): ?>

<td valign="top">
<?php $_from = $this->_tpl_vars['right']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['cell']):
 $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog_tools_cell.tpl", 'smarty_include_vars' => array('cell' => $this->_tpl_vars['cell'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endforeach; endif; unset($_from); ?>
</td>

<?php endif; ?>

</tr>

</table></td>
</tr>

</table></td>
</tr>

</table>

<?php endif; ?>

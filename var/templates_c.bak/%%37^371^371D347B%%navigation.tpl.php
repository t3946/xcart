<?php /* Smarty version 2.6.12, created on 2011-10-11 05:37:50
         compiled from customer/main/navigation.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'amp', 'customer/main/navigation.tpl', 2, false),array('modifier', 'escape', 'customer/main/navigation.tpl', 8, false),array('function', 'math', 'customer/main/navigation.tpl', 8, false),)), $this); ?>
<?php func_load_lang($this, "customer/main/navigation.tpl","lbl_result_pages,lbl_prev_group_pages,lbl_prev_page,lbl_current_page,lbl_page,lbl_next_page,lbl_next_group_pages"); ?><?php $this->assign('navigation_script', ((is_array($_tmp=$this->_tpl_vars['navigation_script'])) ? $this->_run_mod_handler('amp', true, $_tmp) : smarty_modifier_amp($_tmp)));  if ($this->_tpl_vars['total_pages'] > 2): ?>
<table cellpadding="0">
<tr>
	<td class="NavigationTitle"><?php echo $this->_tpl_vars['lng']['lbl_result_pages']; ?>
:</td>
<?php if ($this->_tpl_vars['current_super_page'] > 1): ?>
	<td><a href="<?php echo $this->_tpl_vars['navigation_script']; ?>
&amp;page=<?php echo smarty_function_math(array('equation' => "page-1",'page' => $this->_tpl_vars['start_page']), $this);?>
"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/larrow_2.gif" class="NavigationArrow" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_prev_group_pages'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></a></td>
<?php endif;  unset($this->_sections['page']);
$this->_sections['page']['name'] = 'page';
$this->_sections['page']['loop'] = is_array($_loop=$this->_tpl_vars['total_pages']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['page']['start'] = (int)$this->_tpl_vars['start_page'];
$this->_sections['page']['show'] = true;
$this->_sections['page']['max'] = $this->_sections['page']['loop'];
$this->_sections['page']['step'] = 1;
if ($this->_sections['page']['start'] < 0)
    $this->_sections['page']['start'] = max($this->_sections['page']['step'] > 0 ? 0 : -1, $this->_sections['page']['loop'] + $this->_sections['page']['start']);
else
    $this->_sections['page']['start'] = min($this->_sections['page']['start'], $this->_sections['page']['step'] > 0 ? $this->_sections['page']['loop'] : $this->_sections['page']['loop']-1);
if ($this->_sections['page']['show']) {
    $this->_sections['page']['total'] = min(ceil(($this->_sections['page']['step'] > 0 ? $this->_sections['page']['loop'] - $this->_sections['page']['start'] : $this->_sections['page']['start']+1)/abs($this->_sections['page']['step'])), $this->_sections['page']['max']);
    if ($this->_sections['page']['total'] == 0)
        $this->_sections['page']['show'] = false;
} else
    $this->_sections['page']['total'] = 0;
if ($this->_sections['page']['show']):

            for ($this->_sections['page']['index'] = $this->_sections['page']['start'], $this->_sections['page']['iteration'] = 1;
                 $this->_sections['page']['iteration'] <= $this->_sections['page']['total'];
                 $this->_sections['page']['index'] += $this->_sections['page']['step'], $this->_sections['page']['iteration']++):
$this->_sections['page']['rownum'] = $this->_sections['page']['iteration'];
$this->_sections['page']['index_prev'] = $this->_sections['page']['index'] - $this->_sections['page']['step'];
$this->_sections['page']['index_next'] = $this->_sections['page']['index'] + $this->_sections['page']['step'];
$this->_sections['page']['first']      = ($this->_sections['page']['iteration'] == 1);
$this->_sections['page']['last']       = ($this->_sections['page']['iteration'] == $this->_sections['page']['total']);
 if ($this->_sections['page']['first']):  if ($this->_tpl_vars['navigation_page'] > 1): ?>
	<td valign="middle"><a href="<?php echo $this->_tpl_vars['navigation_script']; ?>
&amp;page=<?php echo smarty_function_math(array('equation' => "page-1",'page' => $this->_tpl_vars['navigation_page']), $this);?>
"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/larrow.gif" class="NavigationArrow" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_prev_page'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></a>&nbsp;</td>
<?php endif;  endif;  if ($this->_sections['page']['index'] == $this->_tpl_vars['navigation_page']): ?>
	<td class="NavigationCellSel" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_current_page'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
: #<?php echo $this->_sections['page']['index']; ?>
"><?php echo $this->_sections['page']['index']; ?>
</td>
<?php else:  if ($this->_sections['page']['index'] >= 100):  $this->assign('suffix', 'Wide');  else:  $this->assign('suffix', "");  endif; ?>
	<td><a href="<?php echo $this->_tpl_vars['navigation_script']; ?>
&amp;page=<?php echo $this->_sections['page']['index']; ?>
" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_page'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 #<?php echo $this->_sections['page']['index']; ?>
" class="NavigationCell<?php echo $this->_tpl_vars['suffix']; ?>
"><?php echo $this->_sections['page']['index']; ?>
</a><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" alt="" /></td>
<?php endif;  if ($this->_sections['page']['last']):  echo smarty_function_math(array('equation' => "pages-1",'pages' => $this->_tpl_vars['total_pages'],'assign' => 'total_pages_minus'), $this);?>

<?php if ($this->_tpl_vars['navigation_page'] < $this->_tpl_vars['total_super_pages']*$this->_tpl_vars['config']['Appearance']['max_nav_pages']): ?>
	<td valign="middle">&nbsp;<a href="<?php echo $this->_tpl_vars['navigation_script']; ?>
&amp;page=<?php echo smarty_function_math(array('equation' => "page+1",'page' => $this->_tpl_vars['navigation_page']), $this);?>
"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/rarrow.gif" class="NavigationArrow" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_next_page'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></a></td>
<?php endif;  endif;  endfor; endif;  if ($this->_tpl_vars['current_super_page'] < $this->_tpl_vars['total_super_pages']): ?>
	<td><a href="<?php echo $this->_tpl_vars['navigation_script']; ?>
&amp;page=<?php echo smarty_function_math(array('equation' => "page+1",'page' => $this->_tpl_vars['total_pages_minus']), $this);?>
"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/rarrow_2.gif" class="NavigationArrow" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_next_group_pages'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></a></td>
<?php endif; ?>
</tr>
</table>
<p />
<?php endif; ?>
<?php /* Smarty version 2.6.12, created on 2015-11-02 03:09:04
         compiled from customer/main/navigation.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'customer/main/navigation.tpl', 1, false),array('function', 'math', 'customer/main/navigation.tpl', 19, false),array('modifier', 'amp', 'customer/main/navigation.tpl', 7, false),array('modifier', 'replace', 'customer/main/navigation.tpl', 48, false),array('modifier', 'escape', 'customer/main/navigation.tpl', 168, false),)), $this); ?>
<?php func_load_lang($this, "customer/main/navigation.tpl","lbl_result_pages,lbl_prev_group_pages,lbl_prev_page,lbl_current_page,lbl_page,lbl_next_page,lbl_next_group_pages"); ?><?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "customer/main/navigation.tpl"), $this); endif;  if ($this->_tpl_vars['usertype'] == 'A' || $this->_tpl_vars['usertype'] == 'P'): ?>

<?php if ($this->_tpl_vars['cidev_new_navigation'] == 'Y'): ?>

<?php $this->assign('navigation_script', ((is_array($_tmp=$this->_tpl_vars['navigation_script'])) ? $this->_run_mod_handler('amp', true, $_tmp) : smarty_modifier_amp($_tmp)));  if ($this->_tpl_vars['total_pages'] > 2): ?>

<?php if ($this->_tpl_vars['featured'] != 'Y'): ?>
<div class="b-paginator_new" >
<B>Pages</B>&nbsp;&nbsp;&nbsp;
<?php unset($this->_sections['page']);
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


        <?php $this->assign('cidev_navigation_script_1', ($this->_tpl_vars['navigation_script'])); ?>
        <?php echo smarty_function_math(array('equation' => "page-1",'page' => $this->_tpl_vars['navigation_page'],'assign' => 'cidev_page'), $this);?>


        <?php if ($this->_tpl_vars['cidev_page'] > '1'): ?>
	        <?php if ($this->_tpl_vars['clean_url_data']['resource_type'] == 'K'): ?>
        	        <?php $this->assign('cidev_navigation_script_1', ($this->_tpl_vars['cidev_navigation_script_1'])."?page=".($this->_tpl_vars['cidev_page'])); ?>
	        <?php else: ?>
	                <?php $this->assign('cidev_navigation_script_1', ($this->_tpl_vars['cidev_navigation_script_1'])."&amp;page=".($this->_tpl_vars['cidev_page'])); ?>
		<?php endif; ?>
        <?php endif; ?>

        <?php if ($this->_tpl_vars['main'] == 'catalog' && $this->_tpl_vars['current_category']['category'] == "" && $this->_tpl_vars['cidev_page'] == '1'): ?>


                <?php if ($this->_tpl_vars['clean_url_data']['resource_type'] == 'K'): ?>

                <?php else: ?>
                        <?php $this->assign('cidev_navigation_script_1', "/"); ?>
                <?php endif; ?>


        <?php endif; ?>

        <?php $this->assign('cidev_navigation_script_1', ((is_array($_tmp=$this->_tpl_vars['cidev_navigation_script_1'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.php&amp;', '.php?') : smarty_modifier_replace($_tmp, '.php&amp;', '.php?'))); ?>


        <a href="<?php echo $this->_tpl_vars['cidev_navigation_script_1']; ?>
">&larr; previous</a>
<?php endif;  endif; ?>

<?php if ($this->_sections['page']['last']):  echo smarty_function_math(array('equation' => "pages-1",'pages' => $this->_tpl_vars['total_pages'],'assign' => 'total_pages_minus'), $this);?>

<?php if ($this->_tpl_vars['navigation_page'] < $this->_tpl_vars['total_super_pages']*$this->_tpl_vars['navigation_max_pages']): ?>

        <?php $this->assign('cidev_navigation_script_3', ($this->_tpl_vars['navigation_script'])); ?>
        <?php echo smarty_function_math(array('equation' => "page+1",'page' => $this->_tpl_vars['navigation_page'],'assign' => 'cidev_page'), $this);?>


	<?php if ($this->_tpl_vars['clean_url_data']['resource_type'] == 'K'): ?>
	        <?php $this->assign('cidev_navigation_script_3', ($this->_tpl_vars['cidev_navigation_script_3'])."?page=".($this->_tpl_vars['cidev_page'])); ?>
	<?php else: ?>
	        <?php $this->assign('cidev_navigation_script_3', ($this->_tpl_vars['cidev_navigation_script_3'])."&amp;page=".($this->_tpl_vars['cidev_page'])); ?>
	<?php endif; ?>
        <?php $this->assign('cidev_navigation_script_3', ((is_array($_tmp=$this->_tpl_vars['cidev_navigation_script_3'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.php&amp;', '.php?') : smarty_modifier_replace($_tmp, '.php&amp;', '.php?'))); ?>

	<?php if ($this->_tpl_vars['navigation_page'] != $this->_tpl_vars['total_pages_minus']): ?>
         &nbsp;&nbsp;&nbsp;<a href="<?php echo $this->_tpl_vars['cidev_navigation_script_3']; ?>
">next &rarr;</a> 
	<?php endif;  endif;  endif;  endfor; endif; ?>
</div>
<?php endif; ?>

<div class="b-paginator" <?php if ($this->_tpl_vars['featured'] == 'Y'): ?>style="text-align:left;"<?php endif; ?>>
 <div class="b-paginator-cell type_content" <?php if ($this->_tpl_vars['featured'] == 'Y'): ?>style="margin-left: 0px;"<?php endif; ?>>
  <div class="b-paginator-cell-scrollbar">
   <div class="b-paginator-cell-scrollbar-h js-paginator-pages">

<?php if ($this->_tpl_vars['featured'] == 'Y'): ?>
<font style="font-size: 18px; font-weight: bold;">Pages</font>&nbsp;&nbsp;&nbsp;&nbsp;
<?php endif; ?>


<?php unset($this->_sections['page']);
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
?>

<?php if ($this->_sections['page']['index'] == $this->_tpl_vars['navigation_page']): ?>
	<span class="b-paginator-item g-current js-paginator-page-current" ><?php echo $this->_sections['page']['index']; ?>
</span>
<?php else:  if ($this->_sections['page']['index'] >= 100):  $this->assign('suffix', 'Wide');  else:  $this->assign('suffix', "");  endif; ?>


        <?php $this->assign('cidev_navigation_script_2', ($this->_tpl_vars['navigation_script'])); ?>
        <?php $this->assign('cidev_page', $this->_sections['page']['index']); ?>
        <?php if ($this->_tpl_vars['cidev_page'] > 1): ?>
	        <?php if ($this->_tpl_vars['clean_url_data']['resource_type'] == 'K'): ?>
        	        <?php $this->assign('cidev_navigation_script_2', ($this->_tpl_vars['cidev_navigation_script_2'])."?page=".($this->_tpl_vars['cidev_page'])); ?>
	        <?php else: ?>
        	        <?php $this->assign('cidev_navigation_script_2', ($this->_tpl_vars['cidev_navigation_script_2'])."&amp;page=".($this->_tpl_vars['cidev_page'])); ?>
		<?php endif; ?>
        <?php endif; ?>

        <?php if ($this->_tpl_vars['main'] == 'catalog' && $this->_tpl_vars['current_category']['category'] == "" && $this->_tpl_vars['cidev_page'] == '1'): ?>


                <?php if ($this->_tpl_vars['clean_url_data']['resource_type'] == 'K'): ?>

                <?php else: ?>
                        <?php $this->assign('cidev_navigation_script_2', "/"); ?>
                <?php endif; ?>

        <?php endif; ?>

        <?php $this->assign('cidev_navigation_script_2', ((is_array($_tmp=$this->_tpl_vars['cidev_navigation_script_2'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.php&amp;', '.php?') : smarty_modifier_replace($_tmp, '.php&amp;', '.php?'))); ?>

        <?php $this->assign('cidev_navigation_script_2', ((is_array($_tmp=$this->_tpl_vars['cidev_navigation_script_2'])) ? $this->_run_mod_handler('replace', true, $_tmp, '?&amp;', '?') : smarty_modifier_replace($_tmp, '?&amp;', '?'))); ?>

	<a class="b-paginator-item" href="<?php echo $this->_tpl_vars['cidev_navigation_script_2']; ?>
" ><?php echo $this->_sections['page']['index']; ?>
</a>

<?php endif;  endfor; endif; ?>



   </div>
  </div>
 </div>
</div>

<?php endif; ?>


<?php else: ?>




<?php $this->assign('navigation_script', ((is_array($_tmp=$this->_tpl_vars['navigation_script'])) ? $this->_run_mod_handler('amp', true, $_tmp) : smarty_modifier_amp($_tmp)));  if ($this->_tpl_vars['total_pages'] > 2): ?>
<table cellpadding="0">
<tr>
	<td class="NavigationTitle"><?php echo $this->_tpl_vars['lng']['lbl_result_pages']; ?>
:</td>
<?php if ($this->_tpl_vars['current_super_page'] > 1): ?>


	<?php $this->assign('cidev_navigation_script_0', ($this->_tpl_vars['navigation_script'])); ?>
	<?php echo smarty_function_math(array('equation' => "page-1",'page' => $this->_tpl_vars['start_page'],'assign' => 'cidev_page'), $this);?>

	<?php if ($this->_tpl_vars['cidev_page'] > '1' || $this->_tpl_vars['usertype'] == 'A' || $this->_tpl_vars['usertype'] == 'P'): ?>
                <?php $this->assign('cidev_navigation_script_0', ($this->_tpl_vars['cidev_navigation_script_0'])."&amp;page=".($this->_tpl_vars['cidev_page'])); ?>  
        <?php endif; ?>
        <?php $this->assign('cidev_navigation_script_0', ((is_array($_tmp=$this->_tpl_vars['cidev_navigation_script_0'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.php&amp;', '.php?') : smarty_modifier_replace($_tmp, '.php&amp;', '.php?'))); ?>


	<td><a href="<?php echo $this->_tpl_vars['cidev_navigation_script_0']; ?>
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


	<?php $this->assign('cidev_navigation_script_1', ($this->_tpl_vars['navigation_script'])); ?>
	<?php echo smarty_function_math(array('equation' => "page-1",'page' => $this->_tpl_vars['navigation_page'],'assign' => 'cidev_page'), $this);?>

	<?php if ($this->_tpl_vars['cidev_page'] > '1' || $this->_tpl_vars['usertype'] == 'A' || $this->_tpl_vars['usertype'] == 'P'): ?>
		<?php $this->assign('cidev_navigation_script_1', ($this->_tpl_vars['cidev_navigation_script_1'])."&amp;page=".($this->_tpl_vars['cidev_page'])); ?>	
	<?php endif; ?>

	<?php if ($this->_tpl_vars['main'] == 'catalog' && $this->_tpl_vars['current_category']['category'] == "" && $this->_tpl_vars['cidev_page'] == '1'): ?>
		<?php $this->assign('cidev_navigation_script_1', "/"); ?>
	<?php endif; ?>

	<?php $this->assign('cidev_navigation_script_1', ((is_array($_tmp=$this->_tpl_vars['cidev_navigation_script_1'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.php&amp;', '.php?') : smarty_modifier_replace($_tmp, '.php&amp;', '.php?'))); ?>


	<td valign="middle"><a href="<?php echo $this->_tpl_vars['cidev_navigation_script_1']; ?>
"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/larrow.gif" class="NavigationArrow" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_prev_page'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></a>&nbsp;</td>
<?php endif;  endif; ?>

<?php if ($this->_sections['page']['index'] == $this->_tpl_vars['navigation_page']): ?>
	<td class="NavigationCellSel" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_current_page'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
: #<?php echo $this->_sections['page']['index']; ?>
"><?php echo $this->_sections['page']['index']; ?>
</td>
<?php else:  if ($this->_sections['page']['index'] >= 100):  $this->assign('suffix', 'Wide');  else:  $this->assign('suffix', "");  endif; ?>


        <?php $this->assign('cidev_navigation_script_2', ($this->_tpl_vars['navigation_script'])); ?>
	<?php $this->assign('cidev_page', $this->_sections['page']['index']); ?>
	<?php if ($this->_tpl_vars['cidev_page'] > 1 || $this->_tpl_vars['usertype'] == 'A' || $this->_tpl_vars['usertype'] == 'P'): ?>
                <?php $this->assign('cidev_navigation_script_2', ($this->_tpl_vars['cidev_navigation_script_2'])."&amp;page=".($this->_tpl_vars['cidev_page'])); ?>
        <?php endif; ?>

	<?php if ($this->_tpl_vars['main'] == 'catalog' && $this->_tpl_vars['current_category']['category'] == "" && $this->_tpl_vars['cidev_page'] == '1'): ?>
		<?php $this->assign('cidev_navigation_script_2', "/"); ?>
	<?php endif; ?>

        <?php $this->assign('cidev_navigation_script_2', ((is_array($_tmp=$this->_tpl_vars['cidev_navigation_script_2'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.php&amp;', '.php?') : smarty_modifier_replace($_tmp, '.php&amp;', '.php?'))); ?>


	<td><a href="<?php echo $this->_tpl_vars['cidev_navigation_script_2']; ?>
" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_page'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 #<?php echo $this->_sections['page']['index']; ?>
" class="NavigationCell<?php echo $this->_tpl_vars['suffix']; ?>
"><?php echo $this->_sections['page']['index']; ?>
</a><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" alt="" /></td>
<?php endif;  if ($this->_sections['page']['last']):  echo smarty_function_math(array('equation' => "pages-1",'pages' => $this->_tpl_vars['total_pages'],'assign' => 'total_pages_minus'), $this);?>

<?php if ($this->_tpl_vars['navigation_page'] < $this->_tpl_vars['total_super_pages']*$this->_tpl_vars['config']['Appearance']['max_nav_pages']): ?>

	
	<?php $this->assign('cidev_navigation_script_3', ($this->_tpl_vars['navigation_script'])); ?>
	<?php echo smarty_function_math(array('equation' => "page+1",'page' => $this->_tpl_vars['navigation_page'],'assign' => 'cidev_page'), $this);?>
 
        <?php $this->assign('cidev_navigation_script_3', ($this->_tpl_vars['cidev_navigation_script_3'])."&amp;page=".($this->_tpl_vars['cidev_page'])); ?>  
	<?php $this->assign('cidev_navigation_script_3', ((is_array($_tmp=$this->_tpl_vars['cidev_navigation_script_3'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.php&amp;', '.php?') : smarty_modifier_replace($_tmp, '.php&amp;', '.php?'))); ?>
	

	<td valign="middle">&nbsp;<a href="<?php echo $this->_tpl_vars['cidev_navigation_script_3']; ?>
"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/rarrow.gif" class="NavigationArrow" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_next_page'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></a></td>
<?php endif;  endif;  endfor; endif;  if ($this->_tpl_vars['current_super_page'] < $this->_tpl_vars['total_super_pages']): ?>

	
	<?php $this->assign('cidev_navigation_script_4', ($this->_tpl_vars['navigation_script'])); ?>
	<?php echo smarty_function_math(array('equation' => "page+1",'page' => $this->_tpl_vars['total_pages_minus'],'assign' => 'cidev_page'), $this);?>

        <?php $this->assign('cidev_navigation_script_4', ($this->_tpl_vars['cidev_navigation_script_4'])."&amp;page=".($this->_tpl_vars['cidev_page'])); ?>        
        <?php $this->assign('cidev_navigation_script_4', ((is_array($_tmp=$this->_tpl_vars['cidev_navigation_script_4'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.php&amp;', '.php?') : smarty_modifier_replace($_tmp, '.php&amp;', '.php?'))); ?>


	<td><a href="<?php echo $this->_tpl_vars['cidev_navigation_script_4']; ?>
"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/rarrow_2.gif" class="NavigationArrow" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_next_group_pages'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></a></td>
<?php endif; ?>
</tr>
</table>
<p />
<?php endif; ?>

<?php endif; ?>

<?php endif;  if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "customer/main/navigation.tpl"), $this); endif; ?>
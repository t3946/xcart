<?php /* Smarty version 2.6.12, created on 2015-11-02 03:19:31
         compiled from main/search_sort_by.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'main/search_sort_by.tpl', 1, false),array('modifier', 'replace', 'main/search_sort_by.tpl', 2, false),array('modifier', 'cat', 'main/search_sort_by.tpl', 2, false),array('modifier', 'amp', 'main/search_sort_by.tpl', 2, false),array('modifier', 'escape', 'main/search_sort_by.tpl', 25, false),)), $this); ?>
<?php func_load_lang($this, "main/search_sort_by.tpl","lbl_sort_by,lbl_sort_by,lbl_sort_direction,lbl_sort_by"); ?><?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "main/search_sort_by.tpl"), $this); endif;  if ($this->_tpl_vars['url'] == '' && $this->_tpl_vars['navigation_script'] != ''):  $this->assign('url', ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['navigation_script'])) ? $this->_run_mod_handler('replace', true, $_tmp, "&", "&amp;") : smarty_modifier_replace($_tmp, "&", "&amp;")))) ? $this->_run_mod_handler('cat', true, $_tmp, "&amp;") : smarty_modifier_cat($_tmp, "&amp;")));  elseif ($this->_tpl_vars['url'] != ''):  $this->assign('url', ((is_array($_tmp=$this->_tpl_vars['url'])) ? $this->_run_mod_handler('amp', true, $_tmp) : smarty_modifier_amp($_tmp)));  endif; ?>
<table cellspacing="0" cellpadding="0">
<tr>
	<td class="Green2"><font size=2><?php echo $this->_tpl_vars['lng']['lbl_sort_by']; ?>
:</font> &nbsp;&nbsp;&nbsp;</td>
<?php $_from = $this->_tpl_vars['sort_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['name'] => $this->_tpl_vars['field']):
?>
	<?php $this->assign('cur_url', ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['url'])) ? $this->_run_mod_handler('cat', true, $_tmp, "sort=") : smarty_modifier_cat($_tmp, "sort=")))) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['name']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['name'])))) ? $this->_run_mod_handler('cat', true, $_tmp, "&amp;sort_direction=") : smarty_modifier_cat($_tmp, "&amp;sort_direction="))); ?>


	<?php if ($this->_tpl_vars['usertype'] == 'C'): ?>
		<?php $this->assign('cur_url', ((is_array($_tmp=$this->_tpl_vars['cur_url'])) ? $this->_run_mod_handler('replace', true, $_tmp, '&amp;page=1&amp;', '&amp;') : smarty_modifier_replace($_tmp, '&amp;page=1&amp;', '&amp;'))); ?>
		<?php $this->assign('cur_url', ((is_array($_tmp=$this->_tpl_vars['cur_url'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.php?page=1&amp;', '.php?') : smarty_modifier_replace($_tmp, '.php?page=1&amp;', '.php?'))); ?>

		<?php if ($this->_tpl_vars['main'] == 'catalog'): ?>
	                <?php $this->assign('cur_url', ((is_array($_tmp=$this->_tpl_vars['cur_url'])) ? $this->_run_mod_handler('replace', true, $_tmp, '&amp;path=alt&amp;', '&amp;') : smarty_modifier_replace($_tmp, '&amp;path=alt&amp;', '&amp;'))); ?>
        	        <?php $this->assign('cur_url', ((is_array($_tmp=$this->_tpl_vars['cur_url'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.php?path=alt&amp;', '.php?') : smarty_modifier_replace($_tmp, '.php?path=alt&amp;', '.php?'))); ?>
		<?php endif; ?>

		<?php $this->assign('cur_url', ((is_array($_tmp=$this->_tpl_vars['cur_url'])) ? $this->_run_mod_handler('replace', true, $_tmp, '?&amp;', '?') : smarty_modifier_replace($_tmp, '?&amp;', '?'))); ?>

	<?php endif; ?>


	<?php if ($this->_tpl_vars['name'] == $this->_tpl_vars['selected']): ?>
	<td>&nbsp;<a class="VertMenuItems" href="<?php echo $this->_tpl_vars['cur_url'];  if ($this->_tpl_vars['direction'] == 1): ?>0<?php else: ?>1<?php endif; ?>" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_sort_by'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
: <?php echo $this->_tpl_vars['field']; ?>
"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/<?php if ($this->_tpl_vars['direction']): ?>darrow.gif<?php else: ?>uarrow.gif<?php endif; ?>" class="VertMenuItems" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_sort_direction'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></a></td>
	<?php endif; ?>
	<td class="VertMenuItems"> &nbsp;<a class="VertMenuItems" href="<?php echo $this->_tpl_vars['cur_url'];  if ($this->_tpl_vars['name'] == $this->_tpl_vars['selected']):  if ($this->_tpl_vars['direction'] == 1): ?>0<?php else: ?>1<?php endif;  else:  echo $this->_tpl_vars['direction'];  endif; ?>" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_sort_by'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
: <?php echo $this->_tpl_vars['field']; ?>
"><?php if ($this->_tpl_vars['name'] == $this->_tpl_vars['selected']): ?><b><?php endif; ?><font size=2><?php echo $this->_tpl_vars['field']; ?>
</font><?php if ($this->_tpl_vars['name'] == $this->_tpl_vars['selected']): ?></b><?php endif; ?></a>&nbsp;&nbsp;</td>
<?php endforeach; endif; unset($_from); ?>
</tr>
</table>
<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "main/search_sort_by.tpl"), $this); endif; ?>
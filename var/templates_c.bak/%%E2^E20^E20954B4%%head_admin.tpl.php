<?php /* Smarty version 2.6.12, created on 2011-10-11 05:37:57
         compiled from head_admin.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'amp', 'head_admin.tpl', 21, false),)), $this); ?>
<?php func_load_lang($this, "head_admin.tpl","lbl_current_language,lbl_sf_properties"); ?><table cellpadding="0" cellspacing="0" width="100%">
<tr> 
	<td class="HeadLogo"><a href="<?php echo $this->_tpl_vars['http_location']; ?>
/admin/"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/admin_xlogo.gif" width="244" height="67" alt="" /></a></td>
<?php if ($this->_tpl_vars['login'] != ""): ?>
	<td align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "authbox_top.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
	<td width="10"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" width="10" height="1" alt="" /></td>
<?php endif; ?>
</tr>
</table>
<table cellpadding="0" cellspacing="0" width="100%">
<tr> 
	<td colspan="4" class="HeadThinLine"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" class="Spc" alt="" /></td>
</tr>
<tr> 
	<td class="HeadLine" height="22" width="70%">
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/search.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	</td>
	<td class="HeadLine" align="right" height="22">
<?php if (( $this->_tpl_vars['usertype'] == 'P' || $this->_tpl_vars['usertype'] == 'A' ) && $this->_tpl_vars['login'] && $this->_tpl_vars['all_languages_cnt'] > 1): ?>
<form action="<?php echo ((is_array($_tmp=$GLOBALS['HTTP_SERVER_VARS']['REQUEST_URI'])) ? $this->_run_mod_handler('amp', true, $_tmp) : smarty_modifier_amp($_tmp)); ?>
" method="post" name="asl_form">
<table cellpadding="0" cellspacing="0">
<tr>
	<td><b><?php echo $this->_tpl_vars['lng']['lbl_current_language']; ?>
:</b>&nbsp;</td>
	<td>
<input type="hidden" name="redirect" value="<?php echo ((is_array($_tmp=$GLOBALS['HTTP_SERVER_VARS']['QUERY_STRING'])) ? $this->_run_mod_handler('amp', true, $_tmp) : smarty_modifier_amp($_tmp)); ?>
" />
<select name="asl" onchange="javascript: document.asl_form.submit()">
<?php unset($this->_sections['ai']);
$this->_sections['ai']['name'] = 'ai';
$this->_sections['ai']['loop'] = is_array($_loop=$this->_tpl_vars['all_languages']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['ai']['show'] = true;
$this->_sections['ai']['max'] = $this->_sections['ai']['loop'];
$this->_sections['ai']['step'] = 1;
$this->_sections['ai']['start'] = $this->_sections['ai']['step'] > 0 ? 0 : $this->_sections['ai']['loop']-1;
if ($this->_sections['ai']['show']) {
    $this->_sections['ai']['total'] = $this->_sections['ai']['loop'];
    if ($this->_sections['ai']['total'] == 0)
        $this->_sections['ai']['show'] = false;
} else
    $this->_sections['ai']['total'] = 0;
if ($this->_sections['ai']['show']):

            for ($this->_sections['ai']['index'] = $this->_sections['ai']['start'], $this->_sections['ai']['iteration'] = 1;
                 $this->_sections['ai']['iteration'] <= $this->_sections['ai']['total'];
                 $this->_sections['ai']['index'] += $this->_sections['ai']['step'], $this->_sections['ai']['iteration']++):
$this->_sections['ai']['rownum'] = $this->_sections['ai']['iteration'];
$this->_sections['ai']['index_prev'] = $this->_sections['ai']['index'] - $this->_sections['ai']['step'];
$this->_sections['ai']['index_next'] = $this->_sections['ai']['index'] + $this->_sections['ai']['step'];
$this->_sections['ai']['first']      = ($this->_sections['ai']['iteration'] == 1);
$this->_sections['ai']['last']       = ($this->_sections['ai']['iteration'] == $this->_sections['ai']['total']);
?>
<option value="<?php echo $this->_tpl_vars['all_languages'][$this->_sections['ai']['index']]['code']; ?>
"<?php if ($this->_tpl_vars['current_language'] == $this->_tpl_vars['all_languages'][$this->_sections['ai']['index']]['code']): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['all_languages'][$this->_sections['ai']['index']]['language']; ?>
</option>
<?php endfor; endif; ?>
</select>
	</td>
</tr>
</table>
</form>
<?php else: ?>
&nbsp;
<?php endif; ?>
</td>
<td class="HeadLine" align="right" height="22">
	<?php if ($this->_tpl_vars['active_modules']['Multiple_Storefronts'] && ( $this->_tpl_vars['usertype'] == 'A' && $this->_tpl_vars['current_membership_flag'] != 'FS' || $this->_tpl_vars['usertype'] == 'P' ) && $this->_tpl_vars['login']): ?>
	<form action="<?php echo ((is_array($_tmp=$GLOBALS['HTTP_SERVER_VARS']['REQUEST_URI'])) ? $this->_run_mod_handler('amp', true, $_tmp) : smarty_modifier_amp($_tmp)); ?>
" method="post" name="storefrontsform">
	<input type="hidden" name="mode" value="change_storefront" />
		<select name="cur_sf" onchange="javascript: document.storefrontsform.submit();">
			<option value="0"<?php if ($this->_tpl_vars['current_storefront'] == '0'): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['main_storefront']; ?>
</option>
			<?php $_from = $this->_tpl_vars['storefronts']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sf']):
?>
				<option value="<?php echo $this->_tpl_vars['sf']['storefrontid']; ?>
"<?php if ($this->_tpl_vars['current_storefront'] == $this->_tpl_vars['sf']['storefrontid']): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['sf']['domain']; ?>
</option>
			<?php endforeach; endif; unset($_from); ?>
		</select>
	</form>
	<?php else: ?>
		&nbsp;
	<?php endif; ?>
</td>
<td class="HeadLine" align="right" height="22" style="padding-right: 12px">
    <?php if ($this->_tpl_vars['active_modules']['Multiple_Storefronts'] && $this->_tpl_vars['usertype'] == 'A' && $this->_tpl_vars['login'] && $this->_tpl_vars['current_membership_flag'] != 'FS'): ?>
       <a href="configuration.php?option=Multiple_Storefronts" title=""><?php echo $this->_tpl_vars['lng']['lbl_sf_properties']; ?>
</a>
    <?php else: ?>
        &nbsp;
    <?php endif; ?>
</td>
</tr>
<tr> 
	<td colspan="4" class="HeadThinLine"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" class="Spc" alt="" /></td>
</tr>
<tr>
	<td colspan="4">&nbsp;</td>
</tr>
</table>
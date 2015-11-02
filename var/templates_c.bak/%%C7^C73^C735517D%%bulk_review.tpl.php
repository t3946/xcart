<?php /* Smarty version 2.6.12, created on 2011-10-11 06:45:29
         compiled from main/bulk_review.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'upper', 'main/bulk_review.tpl', 28, false),array('modifier', 'date_format', 'main/bulk_review.tpl', 57, false),)), $this); ?>
<?php func_load_lang($this, "main/bulk_review.tpl","lbl_bulk_product_fields_updation_review,lbl_bulk_review_top_text,lbl_no_changes,lbl_cancel_changes,lbl_empty,lbl_empty,lbl_apply_changes,lbl_cancel_changes,lbl_ok,lbl_bulk_product_management"); ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "page_title.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_bulk_product_fields_updation_review'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php ob_start();  if ($this->_tpl_vars['log'] == ''):  echo $this->_tpl_vars['lng']['lbl_bulk_review_top_text']; ?>

<br />

<form name="bulkreviewform" action="bulk_management.php" method="post">
<input type="hidden" name="mode" value="apply" />

<table cellpadding="1" cellspacing="1" width="100%">

<?php if (( ! $this->_tpl_vars['changes']['new'] && ! $this->_tpl_vars['changes']['existing'] && $this->_tpl_vars['changes']['discontinued']['forsale'] == 'N' && $this->_tpl_vars['changes']['discontinued']['avail'] == 'N' && $this->_tpl_vars['changes']['discontinued']['categoryid'] == 'N' ) || ( ! $this->_tpl_vars['new'] && ! $this->_tpl_vars['existing'] && ! $this->_tpl_vars['discontinued'] )): ?>
	<tr>
		<td><b><?php echo $this->_tpl_vars['lng']['lbl_no_changes']; ?>
</b></td>
	</tr>
	<tr>
		<td><br /><input type="button" value="<?php echo $this->_tpl_vars['lng']['lbl_cancel_changes']; ?>
" onclick="javascript: document.bulkreviewform.mode.value = 'cancel'; document.bulkreviewform.submit();" /></td>
	</tr>
<?php else: ?>
<tr>
	<td>
		<div class="b_scroll_div">
			<table cellspacing="0" cellpadding="2" width="100%" class="b_scroll_table" border="0">
			<tr>
				<?php $_from = $this->_tpl_vars['colnames']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['col']):
?>
					<td class="b_colnames b_has_border" nowrap="nowrap">!<?php echo ((is_array($_tmp=$this->_tpl_vars['col'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</td>
				<?php endforeach; endif; unset($_from); ?>
			</tr>
			<?php if ($this->_tpl_vars['new'] && $this->_tpl_vars['changes']['new']): ?>
				<?php $_from = $this->_tpl_vars['new']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['nproduct']):
?>
					<tr>
						<?php $_from = $this->_tpl_vars['colnames']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['col']):
?>
							<td class="b_has_border p_new_style"<?php if ($this->_tpl_vars['col'] == 'productcode'): ?> nowrap="nowrap"<?php endif; ?>><?php if ($this->_tpl_vars['changes']['new'][$this->_tpl_vars['col']]):  echo $this->_tpl_vars['nproduct'][$this->_tpl_vars['col']];  else: ?>&nbsp;<?php endif; ?></td>
						<?php endforeach; endif; unset($_from); ?>
					</tr>
				<?php endforeach; endif; unset($_from); ?>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['existing'] && $this->_tpl_vars['changes']['existing']): ?>
				<?php $_from = $this->_tpl_vars['existing']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['ecolumns'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['ecolumns']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['eproduct']):
        $this->_foreach['ecolumns']['iteration']++;
?>
					<tr>
						<?php $_from = $this->_tpl_vars['colnames']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['col']):
?>
						<td class="b_has_border">
							<?php if ($this->_tpl_vars['col'] == 'productcode' || ! $this->_tpl_vars['changes']['existing'][$this->_tpl_vars['col']]): ?>
								<?php echo $this->_tpl_vars['eproduct']['dbsr'][$this->_tpl_vars['col']]; ?>

							<?php else: ?>
								<table>
								<tr>
									<td><?php echo $this->_tpl_vars['eproduct']['csv'][$this->_tpl_vars['col']]; ?>
</td>
								</tr>
								<tr>
									<td class="<?php if ($this->_tpl_vars['eproduct']['dbsr'][$this->_tpl_vars['col']] != ''): ?>b_old_value<?php else: ?>b_empty_value<?php endif; ?>">
									
									<?php if ($this->_tpl_vars['eproduct']['dbsr'][$this->_tpl_vars['col']] != ''): ?>
										<?php if ($this->_tpl_vars['col'] == 'add_date'): ?>
											<?php echo ((is_array($_tmp=$this->_tpl_vars['eproduct']['dbsr'][$this->_tpl_vars['col']])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%A %d %B %Y %T %p") : smarty_modifier_date_format($_tmp, "%A %d %B %Y %T %p")); ?>

										<?php else: ?>
											<?php echo $this->_tpl_vars['eproduct']['dbsr'][$this->_tpl_vars['col']]; ?>

										<?php endif; ?>
									<?php else: ?>
										&lt;<?php echo $this->_tpl_vars['lng']['lbl_empty']; ?>
&gt;
									<?php endif; ?>
									</td>
								</tr>
								</table>
							<?php endif; ?>
						</td>
						<?php endforeach; endif; unset($_from); ?>
					</tr>
				<?php endforeach; endif; unset($_from); ?>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['discontinued'] && ( $this->_tpl_vars['changes']['discontinued']['avail'] != 'N' || $this->_tpl_vars['changes']['discontinued']['forsale'] != 'N' || $this->_tpl_vars['changes']['discontinued']['categoryid'] != 'N' )): ?>
				<?php $_from = $this->_tpl_vars['discontinued']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['dproduct']):
?>
					<tr>
						<?php $_from = $this->_tpl_vars['colnames']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['col']):
?>
							<td class="b_has_border p_discont_style"<?php if ($this->_tpl_vars['col'] == 'productcode'): ?> nowrap="nowrap"<?php endif; ?>>
								<?php if ($this->_tpl_vars['col'] == 'productcode' || ! $this->_tpl_vars['changes']['discontinued'][$this->_tpl_vars['col']] || $this->_tpl_vars['changes']['discontinued'][$this->_tpl_vars['col']] == 'N'): ?>
									<?php if ($this->_tpl_vars['col'] == 'add_date'):  echo ((is_array($_tmp=$this->_tpl_vars['dproduct'][$this->_tpl_vars['col']])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%A %d %B %Y %T %p") : smarty_modifier_date_format($_tmp, "%A %d %B %Y %T %p"));  else:  echo $this->_tpl_vars['dproduct'][$this->_tpl_vars['col']];  endif; ?>
								<?php else: ?>
									<table>
									<tr>
										<td class="p_discont_style">
											<?php if ($this->_tpl_vars['col'] == 'avail' && $this->_tpl_vars['changes']['discontinued']['avail'] != 'N'): ?>
												0
											<?php elseif ($this->_tpl_vars['col'] == 'forsale' && $this->_tpl_vars['changes']['discontinued']['forsale'] != 'N'): ?>
												N
											<?php elseif ($this->_tpl_vars['col'] == 'categoryid' && $this->_tpl_vars['changes']['discontinued']['categoryid'] != 'N'): ?>
												<?php echo $this->_tpl_vars['changes']['discontinued'][$this->_tpl_vars['col']]; ?>

											<?php else: ?>
												<?php echo $this->_tpl_vars['dproduct']['csv'][$this->_tpl_vars['col']]; ?>

											<?php endif; ?>
										</td>
									</tr>
									<tr>
										<td class="<?php if ($this->_tpl_vars['dproduct'][$this->_tpl_vars['col']] != ''): ?>b_old_value<?php else: ?>b_empty_value<?php endif; ?>">
										<?php if ($this->_tpl_vars['dproduct'][$this->_tpl_vars['col']] != ''): ?>
											<?php if ($this->_tpl_vars['col'] == 'add_date'): ?>
												<?php echo ((is_array($_tmp=$this->_tpl_vars['dproduct'][$this->_tpl_vars['col']])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%A %d %B %Y %T %p") : smarty_modifier_date_format($_tmp, "%A %d %B %Y %T %p")); ?>

											<?php else: ?>
												<?php echo $this->_tpl_vars['dproduct'][$this->_tpl_vars['col']]; ?>

											<?php endif; ?>
										<?php else: ?>
											&lt;<?php echo $this->_tpl_vars['lng']['lbl_empty']; ?>
&gt;
										<?php endif; ?>
										</td>
									</tr>
									</table>
								<?php endif; ?>
							</td>
						<?php endforeach; endif; unset($_from); ?>
					</tr>
				<?php endforeach; endif; unset($_from); ?>
			<?php endif; ?>
			</table>
		</div>
	</td>
</tr>

<tr>
	<td><br /><input type="button" value="<?php echo $this->_tpl_vars['lng']['lbl_apply_changes']; ?>
" onclick="javascript: document.bulkreviewform.mode.value = 'apply'; document.bulkreviewform.submit();" />&nbsp;&nbsp;<input type="button" value="<?php echo $this->_tpl_vars['lng']['lbl_cancel_changes']; ?>
" onclick="javascript: document.bulkreviewform.mode.value = 'cancel'; document.bulkreviewform.submit();" /></td>
</tr>
<?php endif; ?>

</table>
</form>
<?php else: ?>
<pre><?php echo $this->_tpl_vars['log']; ?>
</pre>
<input type="button" value="<?php echo $this->_tpl_vars['lng']['lbl_ok']; ?>
" onclick="javascript: self.location = 'bulk_management.php?mode=complete'" />
<?php endif; ?>

<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_bulk_product_management'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
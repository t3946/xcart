<?php /* Smarty version 2.6.12, created on 2011-10-11 06:35:28
         compiled from main/bulk_management.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'upper', 'main/bulk_management.tpl', 41, false),array('modifier', 'date_format', 'main/bulk_management.tpl', 102, false),array('function', 'cycle', 'main/bulk_management.tpl', 55, false),)), $this); ?>
<?php func_load_lang($this, "main/bulk_management.tpl","lbl_bulk_product_management,txt_bulk_manage_new_title,txt_bulk_manage_new,lbl_select_all,lbl_sku,lbl_csv,lbl_no_products,txt_bulk_manage_existing_title,txt_bulk_manage_existing,lbl_select_all,lbl_sku,lbl_dbsr,lbl_csv,lbl_no_products,txt_bulk_manage_discontinued_title,txt_bulk_manage_discontinued,lbl_sku,lbl_dbsr,lbl_no_products,lbl_change_availability_to_disabled,lbl_change_quantity_in_stock_to_zero,lbl_change_main_catid,lbl_review_updation,lbl_cancel,lbl_bulk_product_management"); ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "page_title.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_bulk_product_management'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog_tools.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<br />
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/include_js.tpl", 'smarty_include_vars' => array('src' => "change_all_checkboxes.js")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php ob_start(); ?>

<script type="text/javascript">
<!--
<?php echo '
function select_all(id, prefix) {
	if (document.getElementById(id).checked) {
		checkAll(true, document.bulkform, prefix);
	} else {
		checkAll(false, document.bulkform, prefix);
	}
}
'; ?>

-->
</script>

<form name="bulkform" action="bulk_management.php" method="post">
<input type="hidden" name="mode" value="review" />

<table cellpadding="1" cellspacing="1" width="100%">

<tr>
	<td colspan="2" id="hnew"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['txt_bulk_manage_new_title'],'class' => 'just_red_line')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  echo $this->_tpl_vars['lng']['txt_bulk_manage_new']; ?>
</td>
</tr>
<tr>
	<td colspan="2">
		<?php if ($this->_tpl_vars['new']): ?>
			<div class="b_scroll_div">
				<table cellspacing="1" cellpadding="1" width="100%" class="b_scroll_table" border="0">
				<tr>
					<?php $_from = $this->_tpl_vars['colnames']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['col']):
?>
						<?php if ($this->_tpl_vars['col'] == 'productcode'): ?>
							<td class="b_colnames"><table cellpadding="0" cellspacing="0" class="b_colname_cell"><tr><td><input type="checkbox" name="new_all" value="Y" id="new_all" onchange="javascipt: select_all('new_all', 'new_sel');" /></td><td nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_select_all']; ?>
</td></tr></table></td>
						<?php else: ?>
							<td class="b_colnames"><table cellpadding="0" cellspacing="0" class="b_colname_cell"><tr><td><?php if ($this->_tpl_vars['col'] != 'productid' && $this->_tpl_vars['col'] != 'category'): ?><input type="checkbox" name="new_sel[<?php echo $this->_tpl_vars['col']; ?>
]" value="Y" /><?php else: ?>&nbsp;<?php endif; ?></td><td nowrap="nowrap">!<?php echo ((is_array($_tmp=$this->_tpl_vars['col'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</td></tr></table></td>
						<?php endif; ?>
					<?php endforeach; endif; unset($_from); ?>
				</tr>
				<tr class="dark">
					<?php $_from = $this->_tpl_vars['colnames']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['col']):
?>
						<?php if ($this->_tpl_vars['col'] == 'productcode'): ?>
							<td class="b_subheader"><b><?php echo $this->_tpl_vars['lng']['lbl_sku']; ?>
</b></td>
						<?php else: ?>
							<td class="b_subheader"><b><?php echo $this->_tpl_vars['lng']['lbl_csv']; ?>
</b></td>
						<?php endif; ?>
					<?php endforeach; endif; unset($_from); ?>
				</tr>
				<?php $_from = $this->_tpl_vars['new']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['nproduct']):
?>
					<tr<?php echo smarty_function_cycle(array('values' => ', class="dark"','name' => 'ncycle'), $this);?>
>
						<?php $_from = $this->_tpl_vars['colnames']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['col']):
?>
							<td class="p_new_style"<?php if ($this->_tpl_vars['col'] == 'productcode'): ?> nowrap="nowrap"<?php endif; ?>><?php echo $this->_tpl_vars['nproduct'][$this->_tpl_vars['col']]; ?>
</td>
						<?php endforeach; endif; unset($_from); ?>
					</tr>
				<?php endforeach; endif; unset($_from); ?>
				</table>
			</div>
		<?php else: ?>
			<b><?php echo $this->_tpl_vars['lng']['lbl_no_products']; ?>
</b>
		<?php endif; ?>
	</td>
</tr>
<tr><td colspan="2"><br /><br /></td></tr>
<tr>
	<td colspan="2" id="hexisting"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['txt_bulk_manage_existing_title'],'class' => 'just_red_line')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  echo $this->_tpl_vars['lng']['txt_bulk_manage_existing']; ?>
</td>
</tr>
<tr>
	<td colspan="2">
		<?php if ($this->_tpl_vars['existing']): ?>
			<div class="b_scroll_div">
				<table cellspacing="1" cellpadding="1" width="100%" class="b_scroll_table" border="0">
				<tr>
					<?php $_from = $this->_tpl_vars['colnames']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['col']):
?>
						<?php if ($this->_tpl_vars['col'] == 'productcode'): ?>
							<td class="b_colnames"><table cellpadding="0" cellspacing="0" class="b_colname_cell"><tr><td><input type="checkbox" name="existing_all" value="Y" id="existing_all" onchange="javascipt: select_all('existing_all', 'existing_sel');" /></td><td nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_select_all']; ?>
</td></tr></table></td>
						<?php else: ?>
							<td class="b_colnames" colspan="2"><table cellpadding="0" cellspacing="0" class="b_colname_cell"><tr><td><?php if ($this->_tpl_vars['col'] != 'productid' && $this->_tpl_vars['col'] != 'category'): ?><input type="checkbox" name="existing_sel[<?php echo $this->_tpl_vars['col']; ?>
]" value="Y" /><?php else: ?>&nbsp;<?php endif; ?></td><td nowrap="nowrap">!<?php echo ((is_array($_tmp=$this->_tpl_vars['col'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</td></tr></table></td>
						<?php endif; ?>
					<?php endforeach; endif; unset($_from); ?>
				</tr>
				<tr class="dark">
					<?php $_from = $this->_tpl_vars['colnames']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['col']):
?>
						<?php if ($this->_tpl_vars['col'] == 'productcode'): ?>
							<td class="b_subheader"><b><?php echo $this->_tpl_vars['lng']['lbl_sku']; ?>
</b></td>
						<?php else: ?>
							<td class="b_subheader"><b><?php echo $this->_tpl_vars['lng']['lbl_dbsr']; ?>
</b></td>
							<td class="b_subheader"><b><?php echo $this->_tpl_vars['lng']['lbl_csv']; ?>
</b></td>
						<?php endif; ?>
					<?php endforeach; endif; unset($_from); ?>
				</tr>
				<?php $_from = $this->_tpl_vars['existing']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['ecolumns'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['ecolumns']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['eproduct']):
        $this->_foreach['ecolumns']['iteration']++;
?>
					<tr<?php echo smarty_function_cycle(array('values' => ', class="dark"','name' => 'ecycle'), $this);?>
>
						<?php $_from = $this->_tpl_vars['colnames']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['col']):
?>
							<?php if ($this->_tpl_vars['col'] == 'productcode'): ?>
								<td nowrap="nowrap"><?php echo $this->_tpl_vars['eproduct']['dbsr'][$this->_tpl_vars['col']]; ?>
</td>
							<?php elseif ($this->_tpl_vars['col'] == 'add_date'): ?>
								<td class="b_empty_value"><?php echo ((is_array($_tmp=$this->_tpl_vars['eproduct']['dbsr'][$this->_tpl_vars['col']])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%A %d %B %Y %T %p") : smarty_modifier_date_format($_tmp, "%A %d %B %Y %T %p")); ?>

								<td><?php echo $this->_tpl_vars['eproduct']['csv'][$this->_tpl_vars['col']]; ?>
</td>
							<?php else: ?>
								<td class="b_empty_value"><?php echo $this->_tpl_vars['eproduct']['dbsr'][$this->_tpl_vars['col']]; ?>
</td>
								<td><?php echo $this->_tpl_vars['eproduct']['csv'][$this->_tpl_vars['col']]; ?>
</td>
							<?php endif; ?>
						<?php endforeach; endif; unset($_from); ?>
					</tr>
				<?php endforeach; endif; unset($_from); ?>
				</table>
			</div>
		<?php else: ?>
			<b><?php echo $this->_tpl_vars['lng']['lbl_no_products']; ?>
</b>
		<?php endif; ?>
	</td>
</tr>
<tr><td colspan="2"><br /><br /></td></tr>
<tr>
<td colspan="2" id="hdiscontinued"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['txt_bulk_manage_discontinued_title'],'class' => 'just_red_line')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  echo $this->_tpl_vars['lng']['txt_bulk_manage_discontinued']; ?>
</td>
</tr>
<tr>
	<td colspan="2">
		<?php if ($this->_tpl_vars['discontinued']): ?>
			<div class="b_scroll_div">
				<table cellspacing="1" cellpadding="1" width="100%" class="b_scroll_table" border="0">
				<tr>
					<?php $_from = $this->_tpl_vars['colnames']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['col']):
?>
						<?php if ($this->_tpl_vars['col'] == 'productcode'): ?>
							<td class="b_colnames">&nbsp;</td>
						<?php else: ?>
							<td class="b_colnames" nowrap="nowrap">!<?php echo ((is_array($_tmp=$this->_tpl_vars['col'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</td>
						<?php endif; ?>
					<?php endforeach; endif; unset($_from); ?>
				</tr>
				<tr class="dark">
					<?php $_from = $this->_tpl_vars['colnames']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['col']):
?>
						<?php if ($this->_tpl_vars['col'] == 'productcode'): ?>
							<td class="b_subheader"><b><?php echo $this->_tpl_vars['lng']['lbl_sku']; ?>
</b></td>
						<?php else: ?>
							<td class="b_subheader"><b><?php echo $this->_tpl_vars['lng']['lbl_dbsr']; ?>
</b></td>
						<?php endif; ?>
					<?php endforeach; endif; unset($_from); ?>
				</tr>
				<?php $_from = $this->_tpl_vars['discontinued']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['dproduct']):
?>
					<tr<?php echo smarty_function_cycle(array('values' => ', class="dark"','name' => 'dcycle'), $this);?>
>
						<?php $_from = $this->_tpl_vars['colnames']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['col']):
?>
							<?php if ($this->_tpl_vars['col'] == 'add_date'): ?>
								<td class="p_discont_style"><?php echo ((is_array($_tmp=$this->_tpl_vars['dproduct'][$this->_tpl_vars['col']])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%A %d %B %Y %T %p") : smarty_modifier_date_format($_tmp, "%A %d %B %Y %T %p")); ?>

							<?php else: ?>
								<td class="p_discont_style"<?php if ($this->_tpl_vars['col'] == 'productcode'): ?> nowrap="nowrap"<?php endif; ?>><?php echo $this->_tpl_vars['dproduct'][$this->_tpl_vars['col']]; ?>
</td>
							<?php endif; ?>
						<?php endforeach; endif; unset($_from); ?>
					</tr>
				<?php endforeach; endif; unset($_from); ?>
				</table>
			</div>
		<?php else: ?>
			<b><?php echo $this->_tpl_vars['lng']['lbl_no_products']; ?>
</b>
		<?php endif; ?>
	</td>
</tr>

<tr>
	<td width="6px"><input type="checkbox" name="avail_disabled" value="Y" /></td>
	<td><?php echo $this->_tpl_vars['lng']['lbl_change_availability_to_disabled']; ?>
</td>
</tr>

<tr>
	<td><input type="checkbox" name="qis_zero" value="Y" /></td>
	<td><?php echo $this->_tpl_vars['lng']['lbl_change_quantity_in_stock_to_zero']; ?>
</td>
</tr>

<tr>
	<td><input type="checkbox" name="change_catid" value="Y" /></td>
	<td><?php echo $this->_tpl_vars['lng']['lbl_change_main_catid']; ?>
&nbsp;<input type="text" name="newcatid" value="" size="15" /></td>
</tr>

<tr>
	<td colspan="2">
		<br />
		<input type="button" value="<?php echo $this->_tpl_vars['lng']['lbl_review_updation']; ?>
" onclick="javascript: document.bulkform.submit();" />&nbsp;
		<input type="button" value="<?php echo $this->_tpl_vars['lng']['lbl_cancel']; ?>
" onclick="javascript: self.location = 'search.php?mode=search';" />
	</td>
</tr>

</table>
</form>

<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_bulk_product_management'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
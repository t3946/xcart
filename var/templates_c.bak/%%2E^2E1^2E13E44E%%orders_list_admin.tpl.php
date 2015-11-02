<?php /* Smarty version 2.6.12, created on 2011-10-11 06:30:30
         compiled from main/orders_list_admin.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'main/orders_list_admin.tpl', 19, false),array('modifier', 'default', 'main/orders_list_admin.tpl', 21, false),array('modifier', 'strip_tags', 'main/orders_list_admin.tpl', 77, false),array('modifier', 'escape', 'main/orders_list_admin.tpl', 77, false),)), $this); ?>
<?php func_load_lang($this, "main/orders_list_admin.tpl","lbl_inventory_sales,lbl_direct_ship_sales,lbl_search_again,lbl_search_results,lbl_update,lbl_invoices_for_selected,lbl_labels_for_selected,lbl_delete_selected,txt_delete_selected_orders_warning,txt_shipping_labels_note,lbl_get_shipping_labels,lbl_export_orders,txt_export_all_found_orders_text,lbl_export_file_format,lbl_standart,lbl_40x_compatible,lbl_with_tab_delimiter,lbl_40x_compatible,lbl_with_semicolon_delimiter,lbl_40x_compatible,lbl_with_comma_delimiter,lbl_export,lbl_export_all_found,lbl_search_results"); ?>
<?php $this->assign('total', 0.00);  $this->assign('total_paid', 0.00); ?>

<?php if ($this->_tpl_vars['orders'] != ""): ?>

<?php ob_start(); ?>

<?php if ($this->_tpl_vars['current_membership_flag'] != 'FS'): ?>
<table width="100%">
<tr>
<td>
<table cellspacing="1" class="DataSheet" style="width: auto;">
<tr>
<td><?php echo ((is_array($_tmp=$this->_tpl_vars['cur_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d-%b-%Y") : smarty_modifier_date_format($_tmp, "%d-%b-%Y")); ?>
</td>
<td><?php echo $this->_tpl_vars['lng']['lbl_inventory_sales']; ?>
</td>
<td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => ((is_array($_tmp=@$this->_tpl_vars['today_totals']['ARTS'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>
<tr>
<td><?php echo ((is_array($_tmp=$this->_tpl_vars['cur_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d-%b-%Y") : smarty_modifier_date_format($_tmp, "%d-%b-%Y")); ?>
</td>
<td><?php echo $this->_tpl_vars['lng']['lbl_direct_ship_sales']; ?>
</td>
<td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => ((is_array($_tmp=@$this->_tpl_vars['today_totals']['other'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>
</table>
</td>
<td align="right" valign="top">
<div align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/button.tpl", 'smarty_include_vars' => array('button_title' => $this->_tpl_vars['lng']['lbl_search_again'],'href' => "orders.php")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></div>
</td>
</tr>
</table>
<br />
<?php endif; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_search_results'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/navigation.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/check_all_row.tpl", 'smarty_include_vars' => array('form' => 'processorderform','prefix' => 'orderids')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<form action="process_order.php" method="post" name="processorderform">
<input type="hidden" name="mode" value="" />
<br />
<table cellpadding="3" cellspacing="1" class="OrderSheet">
<?php $this->assign('cycle_state', 'first');  if ($this->_tpl_vars['usertype'] == 'A' && $this->_tpl_vars['current_membership_flag'] != 'FS'): ?>
  <?php $this->assign('static', 'O');  else: ?>
  <?php $this->assign('static', 'Y');  endif;  unset($this->_sections['oid']);
$this->_sections['oid']['name'] = 'oid';
$this->_sections['oid']['loop'] = is_array($_loop=$this->_tpl_vars['orders']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['oid']['show'] = true;
$this->_sections['oid']['max'] = $this->_sections['oid']['loop'];
$this->_sections['oid']['step'] = 1;
$this->_sections['oid']['start'] = $this->_sections['oid']['step'] > 0 ? 0 : $this->_sections['oid']['loop']-1;
if ($this->_sections['oid']['show']) {
    $this->_sections['oid']['total'] = $this->_sections['oid']['loop'];
    if ($this->_sections['oid']['total'] == 0)
        $this->_sections['oid']['show'] = false;
} else
    $this->_sections['oid']['total'] = 0;
if ($this->_sections['oid']['show']):

            for ($this->_sections['oid']['index'] = $this->_sections['oid']['start'], $this->_sections['oid']['iteration'] = 1;
                 $this->_sections['oid']['iteration'] <= $this->_sections['oid']['total'];
                 $this->_sections['oid']['index'] += $this->_sections['oid']['step'], $this->_sections['oid']['iteration']++):
$this->_sections['oid']['rownum'] = $this->_sections['oid']['iteration'];
$this->_sections['oid']['index_prev'] = $this->_sections['oid']['index'] - $this->_sections['oid']['step'];
$this->_sections['oid']['index_next'] = $this->_sections['oid']['index'] + $this->_sections['oid']['step'];
$this->_sections['oid']['first']      = ($this->_sections['oid']['iteration'] == 1);
$this->_sections['oid']['last']       = ($this->_sections['oid']['iteration'] == $this->_sections['oid']['total']);
 $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/order_accounting_table.tpl", 'smarty_include_vars' => array('order' => $this->_tpl_vars['orders'][$this->_sections['oid']['index']],'static' => $this->_tpl_vars['static'],'cycle_state' => $this->_tpl_vars['cycle_state'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  $this->assign('cycle_state', 'continue');  endfor; endif; ?>
</table>
<br />

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/navigation.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<br /><br />

<?php if ($this->_tpl_vars['usertype'] == 'A' && $this->_tpl_vars['current_membership_flag'] != 'FS'): ?>
<input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_update'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: submitForm(this, 'accounting_apply');" />
&nbsp;&nbsp;&nbsp;&nbsp;
<?php endif; ?>
<input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_invoices_for_selected'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: if (checkMarks(this.form, new RegExp('orderids\[[0-9]+\]', 'gi'))) { document.processorderform.target='invoices'; submitForm(this, 'invoice'); document.processorderform.target=''; }" />
&nbsp;&nbsp;&nbsp;&nbsp;
<?php if ($this->_tpl_vars['usertype'] != 'C'): ?>
<input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_labels_for_selected'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: if (checkMarks(this.form, new RegExp('orderids\[[0-9]+\]', 'gi'))) { document.processorderform.target='labels'; submitForm(this, 'label'); document.processorderform.target=''; }" />
&nbsp;&nbsp;&nbsp;&nbsp;
<?php endif;  if (( $this->_tpl_vars['usertype'] == 'A' && $this->_tpl_vars['current_membership_flag'] != 'FS' ) || ( $this->_tpl_vars['usertype'] == 'P' && $this->_tpl_vars['active_modules']['Simple_Mode'] )): ?>
<input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_delete_selected'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: if (checkMarks(this.form, new RegExp('orderids\[[0-9]+\]', 'gi'))) if (confirm('<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['txt_delete_selected_orders_warning'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)); ?>
')) submitForm(this, 'delete');" />
&nbsp;&nbsp;&nbsp;&nbsp;
<?php endif; ?>

<?php if ($this->_tpl_vars['active_modules']['Shipping_Label_Generator'] != '' && ( $this->_tpl_vars['usertype'] == 'A' || $this->_tpl_vars['usertype'] == 'P' )): ?>
<br />
<br />
<br />
<?php echo $this->_tpl_vars['lng']['txt_shipping_labels_note']; ?>

<br />
<br />
<input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_get_shipping_labels'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: if (checkMarks(this.form, new RegExp('orderids\[[0-9]+\]', 'gi'))) { document.processorderform.action='generator.php'; submitForm(this, ''); }" />
<?php endif; ?>

<?php if ($this->_tpl_vars['usertype'] != 'C'): ?>
<br />
<br />
<br />
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_export_orders'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  echo $this->_tpl_vars['lng']['txt_export_all_found_orders_text']; ?>

<br /><br />
<?php echo $this->_tpl_vars['lng']['lbl_export_file_format']; ?>
:<br />
<select id="export_fmt" name="export_fmt">
	<option value="std"><?php echo $this->_tpl_vars['lng']['lbl_standart']; ?>
</option>
	<option value="csv_tab"><?php echo $this->_tpl_vars['lng']['lbl_40x_compatible']; ?>
: CSV <?php echo $this->_tpl_vars['lng']['lbl_with_tab_delimiter']; ?>
</option>
	<option value="csv_semi"><?php echo $this->_tpl_vars['lng']['lbl_40x_compatible']; ?>
: CSV <?php echo $this->_tpl_vars['lng']['lbl_with_semicolon_delimiter']; ?>
</option>
	<option value="csv_comma"><?php echo $this->_tpl_vars['lng']['lbl_40x_compatible']; ?>
: CSV <?php echo $this->_tpl_vars['lng']['lbl_with_comma_delimiter']; ?>
</option>
<?php if ($this->_tpl_vars['active_modules']['QuickBooks'] == 'Y'):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/QuickBooks/orders.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
</select>
<br />
<br />
<input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_export'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: if (checkMarks(this.form, new RegExp('orderids\[[0-9]+\]', 'gi'))) submitForm(this, 'export');" />&nbsp;&nbsp;&nbsp;
<input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_export_all_found'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: self.location='orders.php?mode=search&amp;export=export_found&amp;export_fmt='+document.getElementById('export_fmt').value;" />
<?php endif; ?>

</form>

<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_search_results'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
<?php /* Smarty version 2.6.12, created on 2011-10-11 06:30:33
         compiled from main/history_order.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'strip_tags', 'main/history_order.tpl', 31, false),array('modifier', 'escape', 'main/history_order.tpl', 31, false),array('modifier', 'date_format', 'main/history_order.tpl', 46, false),array('modifier', 'func_order_details_translate', 'main/history_order.tpl', 139, false),array('modifier', 'truncate', 'main/history_order.tpl', 199, false),)), $this); ?>
<?php func_load_lang($this, "main/history_order.tpl","lbl_order_details_label,txt_order_details_top_text,txt_enter_merchant_password_note,lbl_merchant_password,lbl_enter_mpassword,lbl_enter_merchant_password,lbl_order,lbl_date,lbl_order,lbl_order,lbl_print_order,lbl_create_return,lbl_order_returns,lbl_shipping_label,lbl_print_invoice,lbl_modify,lbl_customer_notes,lbl_ip_address,lbl_ip_address_blocked,lbl_block_ip_address,lbl_af_lookup_address,lbl_order_details,lbl_view_mode,lbl_edit_mode,lbl_order_notes,lbl_apply_changes,lbl_apply_changes_send_email,txt_apply_changes,lbl_prolong_ttl,lbl_regenerate_ttl,txt_prolong_ttl,lbl_complete_order,txt_complete_order,lbl_tracking_order,lbl_tracking_number,lbl_order_details_label,lbl_status,lbl_sent,lbl_queued,lbl_from,lbl_to,lbl_mnf_send_shipping,lbl_yes,lbl_no,lbl_message_body,lbl_send,lbl_manufacturers_notifications"); ?><?php if ($this->_tpl_vars['current_membership_flag'] == 'FS'):  $this->assign('membership_static', 'F');  else:  $this->assign('membership_static', "");  endif;  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/multirow.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<script type="text/javascript">
<!--
multirowInputSets['track'] = [];
multirowInputSets['track'].noCloneContent = 1;
-->
</script>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "page_title.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_order_details_label'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php echo $this->_tpl_vars['lng']['txt_order_details_top_text']; ?>


<br /><br />

<?php if ($this->_tpl_vars['usertype'] == 'A' && $this->_tpl_vars['is_merchant_password'] != 'Y' && $this->_tpl_vars['config']['Security']['blowfish_enabled'] == 'Y'):  ob_start(); ?>
<form action="<?php echo $this->_tpl_vars['catalogs']['admin']; ?>
/merchant_password.php" method="post" name="mpasswordform">
<input type="hidden" name="orderid" value="<?php echo $this->_tpl_vars['orderid']; ?>
" />
<?php echo $this->_tpl_vars['lng']['txt_enter_merchant_password_note']; ?>

<br /><br />
<table>
<tr>
	<td><font class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_merchant_password']; ?>
</font></td>
	<td><input type="password" name="mpassword" size="16" /></td>
	<td><input type="submit" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_enter_mpassword'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td>
</tr>
</table>
</form>
<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_enter_merchant_password'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<br />
<?php endif; ?>

<?php ob_start(); ?>
<table width="100%">
<tr> 
	<td valign="top">

<?php if ($this->_tpl_vars['usertype'] != 'C'): ?>
  <div align="left"><b><?php echo $this->_tpl_vars['lng']['lbl_order']; ?>
 # <?php echo $this->_tpl_vars['order']['order_prefix'];  echo $this->_tpl_vars['order']['orderid']; ?>
</b><br /><?php echo $this->_tpl_vars['lng']['lbl_date']; ?>
: <?php echo ((is_array($_tmp=$this->_tpl_vars['order']['date'])) ? $this->_run_mod_handler('date_format', true, $_tmp, $this->_tpl_vars['config']['Appearance']['datetime_format']) : smarty_modifier_date_format($_tmp, $this->_tpl_vars['config']['Appearance']['datetime_format'])); ?>
</div>
<?php endif; ?>

<p class="prev-next-links">
<?php if ($this->_tpl_vars['orderid_prev'] != ""): ?><a href="order.php?orderid=<?php echo $this->_tpl_vars['orderid_prev']; ?>
">&lt;&lt;&nbsp;<?php echo $this->_tpl_vars['lng']['lbl_order']; ?>
 <?php if ($this->_tpl_vars['usertype'] == 'A' || $this->_tpl_vars['usertype'] == 'P'): ?># <?php endif;  echo $this->_tpl_vars['order_prefix_prev'];  echo $this->_tpl_vars['orderid_prev']; ?>
</a><?php endif;  if ($this->_tpl_vars['orderid_next'] != ""):  if ($this->_tpl_vars['orderid_prev'] != ""): ?> | <?php endif; ?><a href="order.php?orderid=<?php echo $this->_tpl_vars['orderid_next']; ?>
"><?php echo $this->_tpl_vars['lng']['lbl_order']; ?>
 <?php if ($this->_tpl_vars['usertype'] == 'A' || $this->_tpl_vars['usertype'] == 'P'): ?># <?php endif;  echo $this->_tpl_vars['order_prefix_next'];  echo $this->_tpl_vars['orderid_next']; ?>
&nbsp;&gt;&gt;</a><?php endif; ?>
</p>

<table cellspacing="1" cellpadding="2" class="ButtonsRow">
<tr>
<?php if ($this->_tpl_vars['usertype'] == 'P' || $this->_tpl_vars['usertype'] == 'A'): ?>
<td class="ButtonsRow"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/button.tpl", 'smarty_include_vars' => array('button_title' => $this->_tpl_vars['lng']['lbl_print_order'],'target' => '_blank','href' => "order.php?orderid=".($this->_tpl_vars['order']['orderid'])."&mode=printable")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
<?php endif;  if ($this->_tpl_vars['active_modules']['RMA'] != '' && $this->_tpl_vars['current_membership_flag'] != 'FS'): ?> 
<?php if (( $this->_tpl_vars['usertype'] == 'C' || $this->_tpl_vars['usertype'] == 'A' || ( $this->_tpl_vars['usertype'] == 'P' && $this->_tpl_vars['active_modules']['Simple_Mode'] ) ) && $this->_tpl_vars['return_products'] != ''): ?>
<td class="ButtonsRow"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/button.tpl", 'smarty_include_vars' => array('button_title' => $this->_tpl_vars['lng']['lbl_create_return'],'href' => "#returns")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
<?php endif;  if (( $this->_tpl_vars['usertype'] == 'C' || $this->_tpl_vars['usertype'] == 'A' || ( $this->_tpl_vars['usertype'] == 'P' && $this->_tpl_vars['active_modules']['Simple_Mode'] ) ) && $this->_tpl_vars['order']['is_returns']): ?>
<td class="ButtonsRow"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/button.tpl", 'smarty_include_vars' => array('button_title' => $this->_tpl_vars['lng']['lbl_order_returns'],'href' => "returns.php?mode=search&search[orderid]=".($this->_tpl_vars['order']['orderid']))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
<?php endif;  endif;  if ($this->_tpl_vars['active_modules']['Shipping_Label_Generator'] != '' && ( $this->_tpl_vars['usertype'] == 'A' || $this->_tpl_vars['usertype'] == 'P' )): ?> 
<td class="ButtonsRow"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/button.tpl", 'smarty_include_vars' => array('button_title' => $this->_tpl_vars['lng']['lbl_shipping_label'],'href' => "generator.php?orderid=".($this->_tpl_vars['order']['orderid']))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
<?php endif; ?>
<td class="ButtonsRow"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/button.tpl", 'smarty_include_vars' => array('button_title' => $this->_tpl_vars['lng']['lbl_print_invoice'],'target' => '_blank','href' => "order.php?orderid=".($this->_tpl_vars['order']['orderid'])."&mode=invoice")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
<?php if (( $this->_tpl_vars['usertype'] == 'A' || ( $this->_tpl_vars['usertype'] == 'P' && $this->_tpl_vars['active_modules']['Simple_Mode'] ) ) && $this->_tpl_vars['active_modules']['Advanced_Order_Management']): ?>
<td class="ButtonsRow"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/button.tpl", 'smarty_include_vars' => array('button_title' => $this->_tpl_vars['lng']['lbl_modify'],'href' => "order.php?orderid=".($this->_tpl_vars['order']['orderid'])."&mode=edit")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
<?php endif; ?>
</tr>
</table>

<p />
<?php if ($this->_tpl_vars['usertype'] == 'C'): ?>
<hr />
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "mail/html/order_invoice.tpl", 'smarty_include_vars' => array('is_nomail' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  elseif ($this->_tpl_vars['usertype'] == 'A' || ( $this->_tpl_vars['usertype'] == 'P' && $this->_tpl_vars['active_modules']['Simple_Mode'] )):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/order_info_admin.tpl", 'smarty_include_vars' => array('static' => $this->_tpl_vars['membership_static'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  else:  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/order_info.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
	</td>
</tr>
<tr>
	<td height="1" valign="top">
<script type="text/javascript">
<!--
var details_mode = false;
var details_fields_labels = new Object();
<?php $_from = $this->_tpl_vars['order_details_fields_labels']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['dfield'] => $this->_tpl_vars['dlabel']):
?>
details_fields_labels["<?php echo ((is_array($_tmp=$this->_tpl_vars['dfield'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
"] = "<?php echo ((is_array($_tmp=$this->_tpl_vars['dlabel'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
";
<?php endforeach; endif; unset($_from); ?>
-->
</script>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/include_js.tpl", 'smarty_include_vars' => array('src' => "main/history_order.js")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<form action="order.php" method="post" name="ordernotesform">
<input type="hidden" name="send_email" value="N" />

<?php if ($this->_tpl_vars['usertype'] != 'C'): ?>
<p />
<?php echo $this->_tpl_vars['lng']['lbl_customer_notes']; ?>
:<br />
<textarea name="customer_notes" cols="70" rows="8" style="width: 520px;"<?php if ($this->_tpl_vars['current_membership_flag'] == 'FS'): ?> readonly="readonly"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['order']['customer_notes'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'quotes') : smarty_modifier_escape($_tmp, 'quotes')); ?>
</textarea>
<p />
<?php endif; ?>

<?php if ($this->_tpl_vars['usertype'] == 'A' || ( $this->_tpl_vars['usertype'] == 'P' && $this->_tpl_vars['active_modules']['Simple_Mode'] )): ?>

<?php if ($this->_tpl_vars['order']['extra']['ip'] != ''): ?>
<p />
<?php echo $this->_tpl_vars['lng']['lbl_ip_address']; ?>
: <?php echo $this->_tpl_vars['order']['extra']['ip'];  if ($this->_tpl_vars['order']['extra']['proxy_ip'] != ''): ?> (<?php echo $this->_tpl_vars['order']['extra']['proxy_ip']; ?>
)<?php endif; ?><br />
<?php if ($this->_tpl_vars['active_modules']['Stop_List'] != ''):  if ($this->_tpl_vars['order']['blocked'] == 'Y'): ?>
<font class="Star"><?php echo $this->_tpl_vars['lng']['lbl_ip_address_blocked']; ?>
</font><br />
<?php else: ?>
<input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_block_ip_address'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: self.location='order.php?mode=block_ip&amp;orderid=<?php echo $this->_tpl_vars['orderid']; ?>
'" />
<?php endif;  endif; ?>

<?php endif; ?>

<?php if ($this->_tpl_vars['active_modules']['Anti_Fraud'] != ''): ?>
<input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_af_lookup_address'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: window.open('<?php echo $this->_tpl_vars['catalogs']['admin']; ?>
/anti_fraud.php?mode=popup&amp;ip=<?php echo $this->_tpl_vars['order']['extra']['ip']; ?>
&amp;proxy_ip=<?php echo $this->_tpl_vars['order']['extra']['proxy_ip']; ?>
','AFLOOKUP','width=600,height=460,toolbar=no,status=no,scrollbars=yes,resizable=no,menubar=no,location=no,direction=no');" />
<?php endif; ?>
<p />
<?php echo $this->_tpl_vars['lng']['lbl_order_details']; ?>
:
<?php if (! $this->_tpl_vars['order']['details_encrypted']): ?>
<div style="text-align: right; width: 520px; padding-bottom: 3px;">
<a id="view_mode" href="javascript: void(0);" onclick="javascript: switch_details_mode(false, this, document.getElementById('edit_mode'));" style="font-weight: bold;"><?php echo $this->_tpl_vars['lng']['lbl_view_mode']; ?>
</a>
&nbsp;&nbsp;&nbsp;
<a id="edit_mode" href="javascript: void(0);" onclick="javascript: switch_details_mode(true, this, document.getElementById('view_mode'));"><?php echo $this->_tpl_vars['lng']['lbl_edit_mode']; ?>
</a>
</div>
<?php endif; ?>
<textarea id="details_view" cols="70" style="color: #666666; background-color:#EEEEEE; width: 520px;" readonly="readonly" rows="12"<?php if ($this->_tpl_vars['order']['details_encrypted']): ?> disabled="disabled"<?php endif; ?>><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['order']['details'])) ? $this->_run_mod_handler('func_order_details_translate', true, $_tmp) : func_order_details_translate($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp, 'quotes') : smarty_modifier_escape($_tmp, 'quotes')); ?>
</textarea>
<?php if ($this->_tpl_vars['order']['details_encrypted'] == ''): ?>
<textarea id="details_edit" style="display: none; width: 520px;" name="details" cols="70" rows="12"><?php echo ((is_array($_tmp=$this->_tpl_vars['order']['details'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'quotes') : smarty_modifier_escape($_tmp, 'quotes')); ?>
</textarea>
<?php endif;  endif; ?>

<?php if ($this->_tpl_vars['usertype'] != 'C'): ?>
<p />
<?php echo $this->_tpl_vars['lng']['lbl_order_notes']; ?>
:<br />
<textarea name="notes" cols="70" style="width: 520px;" rows="8"><?php echo ((is_array($_tmp=$this->_tpl_vars['order']['notes'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'quotes') : smarty_modifier_escape($_tmp, 'quotes')); ?>
</textarea>
<?php endif; ?>

<?php if ($this->_tpl_vars['usertype'] == 'A' || $this->_tpl_vars['usertype'] == 'P'): ?>
<p />
<input type="submit" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_apply_changes'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
<?php if ($this->_tpl_vars['current_membership_flag'] != 'FS'): ?>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="submit" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_apply_changes_send_email'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: document.ordernotesform.send_email.value = 'Y'; document.ordernotesform.submit();" /><br />
<?php endif;  if ($this->_tpl_vars['usertype'] != 'A'):  echo $this->_tpl_vars['lng']['txt_apply_changes']; ?>
	
<?php endif;  endif; ?>

<?php if ($this->_tpl_vars['active_modules']['Special_Offers'] != "" && ( $this->_tpl_vars['usertype'] == 'A' || $this->_tpl_vars['usertype'] == 'P' )): ?>
<br /><br /><br />
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Special_Offers/order_extra_data.tpl", 'smarty_include_vars' => array('data' => $this->_tpl_vars['order']['extra'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

<?php if (( $this->_tpl_vars['usertype'] == 'A' || ( $this->_tpl_vars['usertype'] == 'P' && $this->_tpl_vars['active_modules']['Simple_Mode'] ) ) && $this->_tpl_vars['active_modules']['Anti_Fraud']): ?>
<br /><br /><br />
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Anti_Fraud/extra_data.tpl", 'smarty_include_vars' => array('data' => $this->_tpl_vars['order']['extra']['Anti_Fraud'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

<?php if (( $this->_tpl_vars['usertype'] == 'A' || ( $this->_tpl_vars['usertype'] == 'P' && $this->_tpl_vars['active_modules']['Simple_Mode'] ) ) && $this->_tpl_vars['order']['is_egood'] != '' && $this->_tpl_vars['active_modules']['Egoods']): ?>
<p />
<input type="button" value="<?php if ($this->_tpl_vars['order']['is_egood'] == 'Y'):  echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_prolong_ttl'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp));  else:  echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_regenerate_ttl'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp));  endif; ?>" onclick="javascript: self.location='order.php?mode=prolong_ttl&amp;orderid=<?php echo $this->_tpl_vars['orderid']; ?>
'" /><br />
<?php echo $this->_tpl_vars['lng']['txt_prolong_ttl']; ?>

<?php endif; ?>

<input type="hidden" name="mode" value="status_change" />
<input type="hidden" name="orderid" value="<?php echo $this->_tpl_vars['order']['orderid']; ?>
" />
</form>

<?php if ($this->_tpl_vars['usertype'] == 'P' && $this->_tpl_vars['order']['status'] != 'C'): ?>
<br />
<form action="order.php" method="post">
<input type="hidden" name="mode" value="complete_order" />
<input type="submit" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_complete_order'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /><br />
<?php echo $this->_tpl_vars['lng']['txt_complete_order']; ?>

<input type="hidden" name="orderid" value="<?php echo $this->_tpl_vars['order']['orderid']; ?>
" />
</form>
<?php endif; ?>

<?php if ($this->_tpl_vars['active_modules']['Order_Tracking'] != "" && $this->_tpl_vars['order']['tracking'] != ""): ?>

<br /><br /><br />

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_tracking_order'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php $this->assign('postal_service', ((is_array($_tmp=$this->_tpl_vars['order']['shipping'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 3, "", true) : smarty_modifier_truncate($_tmp, 3, "", true)));  echo $this->_tpl_vars['lng']['lbl_tracking_number']; ?>
: <?php echo $this->_tpl_vars['order']['tracking']; ?>

<br /><br />

<?php if ($this->_tpl_vars['postal_service'] == 'UPS'):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Order_Tracking/ups.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  elseif ($this->_tpl_vars['postal_service'] == 'USP'):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Order_Tracking/usps.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  elseif ($this->_tpl_vars['postal_service'] == 'Fed'):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Order_Tracking/fedex.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  elseif ($this->_tpl_vars['postal_service'] == 'Aus'):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Order_Tracking/australia_post.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

<?php endif; ?>

	</td>
</tr>
</table>
<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_order_details_label'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  if ($this->_tpl_vars['active_modules']['RMA'] != '' && ( $this->_tpl_vars['usertype'] == 'C' || ( $this->_tpl_vars['usertype'] == 'A' && $this->_tpl_vars['current_membership_flag'] != 'FS' ) || ( $this->_tpl_vars['usertype'] == 'P' && $this->_tpl_vars['active_modules']['Simple_Mode'] ) )): ?>

<br />
<a name="returns"></a>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/RMA/add_returns.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

<?php if ($this->_tpl_vars['usertype'] == 'A' && $this->_tpl_vars['order_manufacturers'] && $this->_tpl_vars['current_membership_flag'] != 'FS'): ?>
<br />
<a name="mnf_notify"></a>

<?php ob_start();  $_from = $this->_tpl_vars['order_manufacturers']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['mnf_id'] => $this->_tpl_vars['v']):
?>
<div class="ProductTitle" align="center"><?php echo $this->_tpl_vars['v']['manufacturer']; ?>
</div>
<form action="order.php" method="post" name="mnfnotifyform_<?php echo $this->_tpl_vars['mnf_id']; ?>
">
<input type="hidden" name="mode" value="mnf_notify" />
<input type="hidden" name="orderid" value="<?php echo $this->_tpl_vars['order']['orderid']; ?>
" />
<input type="hidden" name="mnf_id" value="<?php echo $this->_tpl_vars['mnf_id']; ?>
" />
<?php echo $this->_tpl_vars['lng']['lbl_status']; ?>
:<br />
<?php if ($this->_tpl_vars['v']['notify_sent'] == 'Y'): ?><span style="color: green; font-weight: bold"><?php echo $this->_tpl_vars['lng']['lbl_sent']; ?>
</span><?php else: ?><span style="color: green;"><?php echo $this->_tpl_vars['lng']['lbl_queued']; ?>
</span><?php endif; ?><br /><br />
<?php echo $this->_tpl_vars['lng']['lbl_from']; ?>
:<br />
<input type="text" name="mnf_from" value="<?php echo $this->_tpl_vars['config']['Company']['orders_department']; ?>
" readonly="readonly" style="width: 80%;" /><br /><br />
<?php echo $this->_tpl_vars['lng']['lbl_to']; ?>
:<br />
<input type="text" name="mnf_to" value="<?php echo $this->_tpl_vars['v']['email']; ?>
" style="width: 80%;" /><br /><br />
<?php echo $this->_tpl_vars['lng']['lbl_mnf_send_shipping']; ?>
:<br />
<select name="mnf_shipping">
<option value="Y"><?php echo $this->_tpl_vars['lng']['lbl_yes']; ?>
</option>
<option value="N"><?php echo $this->_tpl_vars['lng']['lbl_no']; ?>
</option>
</select><br /><br />
<?php echo $this->_tpl_vars['lng']['lbl_message_body']; ?>
:<br />
<textarea rows="20" cols="60" name="mnf_body" style="width: 80%;"><?php echo $this->_tpl_vars['v']['mess_body']; ?>
</textarea><br /><br />
<input type="submit" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_send'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /><br /><br />
<hr /><br />
</form>
<?php endforeach; endif; unset($_from); ?>

<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_manufacturers_notifications'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

<?php if ($this->_tpl_vars['usertype'] == 'A' || ( $this->_tpl_vars['usertype'] == 'P' && $this->_tpl_vars['active_modules']['Simple_Mode'] )): ?>
<br />
<a name="accounting"></a>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/order_accounting.tpl", 'smarty_include_vars' => array('static' => $this->_tpl_vars['membership_static'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

<?php if (( $this->_tpl_vars['usertype'] == 'A' || $this->_tpl_vars['usertype'] == 'P' ) && $this->_tpl_vars['active_modules']['Google_Checkout'] != '' && $this->_tpl_vars['order']['extra']['goid'] != ''): ?>

<br />
<a name="gcheckout"></a>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Google_Checkout/gcheckout_order.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
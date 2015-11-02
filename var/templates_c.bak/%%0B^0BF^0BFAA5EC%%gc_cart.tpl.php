<?php /* Smarty version 2.6.12, created on 2011-10-11 07:07:23
         compiled from modules/Gift_Certificates/gc_cart.tpl */ ?>
<?php func_load_lang($this, "modules/Gift_Certificates/gc_cart.tpl","lbl_purchased,lbl_gift_certificate,lbl_recipient,lbl_email,lbl_mail_address,lbl_phone,lbl_amount"); ?><?php if ($this->_tpl_vars['giftcerts_data'] != ""):  unset($this->_sections['giftcert']);
$this->_sections['giftcert']['name'] = 'giftcert';
$this->_sections['giftcert']['loop'] = is_array($_loop=$this->_tpl_vars['giftcerts_data']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['giftcert']['show'] = true;
$this->_sections['giftcert']['max'] = $this->_sections['giftcert']['loop'];
$this->_sections['giftcert']['step'] = 1;
$this->_sections['giftcert']['start'] = $this->_sections['giftcert']['step'] > 0 ? 0 : $this->_sections['giftcert']['loop']-1;
if ($this->_sections['giftcert']['show']) {
    $this->_sections['giftcert']['total'] = $this->_sections['giftcert']['loop'];
    if ($this->_sections['giftcert']['total'] == 0)
        $this->_sections['giftcert']['show'] = false;
} else
    $this->_sections['giftcert']['total'] = 0;
if ($this->_sections['giftcert']['show']):

            for ($this->_sections['giftcert']['index'] = $this->_sections['giftcert']['start'], $this->_sections['giftcert']['iteration'] = 1;
                 $this->_sections['giftcert']['iteration'] <= $this->_sections['giftcert']['total'];
                 $this->_sections['giftcert']['index'] += $this->_sections['giftcert']['step'], $this->_sections['giftcert']['iteration']++):
$this->_sections['giftcert']['rownum'] = $this->_sections['giftcert']['iteration'];
$this->_sections['giftcert']['index_prev'] = $this->_sections['giftcert']['index'] - $this->_sections['giftcert']['step'];
$this->_sections['giftcert']['index_next'] = $this->_sections['giftcert']['index'] + $this->_sections['giftcert']['step'];
$this->_sections['giftcert']['first']      = ($this->_sections['giftcert']['iteration'] == 1);
$this->_sections['giftcert']['last']       = ($this->_sections['giftcert']['iteration'] == $this->_sections['giftcert']['total']);
?>
<table width="100%" cellpadding="0" cellspacing="0">
<tr>
	<td width="100" valign="top"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/gift.gif" width="84" height="69" alt="" /></td>
	<td valign="top">

<?php if ($this->_tpl_vars['giftcerts_data'][$this->_sections['giftcert']['index']]['amount_purchased'] > 1): ?>
<font class="ProductDetailsTitle"><?php echo $this->_tpl_vars['lng']['lbl_purchased']; ?>
</font>
<br />
<?php endif; ?>

<font class="ProductTitle"><?php echo $this->_tpl_vars['lng']['lbl_gift_certificate']; ?>
</font>
<p />
<font class="TableCenterCustomerForm"><?php echo $this->_tpl_vars['lng']['lbl_recipient']; ?>
:</font> <?php echo $this->_tpl_vars['giftcerts_data'][$this->_sections['giftcert']['index']]['recipient']; ?>
<br />

<?php if ($this->_tpl_vars['giftcerts_data'][$this->_sections['giftcert']['index']]['send_via'] == 'E'): ?>
<font class="TableCenterCustomerForm"><?php echo $this->_tpl_vars['lng']['lbl_email']; ?>
:</font> <?php echo $this->_tpl_vars['giftcerts_data'][$this->_sections['giftcert']['index']]['recipient_email']; ?>
<br />
<?php elseif ($this->_tpl_vars['giftcerts_data'][$this->_sections['giftcert']['index']]['send_via'] == 'P'): ?>
<font class="TableCenterCustomerForm"><?php echo $this->_tpl_vars['lng']['lbl_mail_address']; ?>
:</font> <?php echo $this->_tpl_vars['giftcerts_data'][$this->_sections['giftcert']['index']]['recipient_address']; ?>
, <?php echo $this->_tpl_vars['giftcerts_data'][$this->_sections['giftcert']['index']]['recipient_city']; ?>
, <?php if ($this->_tpl_vars['config']['General']['use_counties'] == 'Y'):  echo $this->_tpl_vars['giftcerts_data'][$this->_sections['giftcert']['index']]['recipient_countyname']; ?>
 <?php endif;  echo $this->_tpl_vars['giftcerts_data'][$this->_sections['giftcert']['index']]['recipient_state']; ?>
 <?php echo $this->_tpl_vars['giftcerts_data'][$this->_sections['giftcert']['index']]['recipient_country']; ?>
 <?php echo $this->_tpl_vars['giftcerts_data'][$this->_sections['giftcert']['index']]['recipient_zipcode']; ?>
<br />
<?php if ($this->_tpl_vars['giftcerts_data'][$this->_sections['giftcert']['index']]['recipient_phone']): ?>
<font class="TableCenterCustomerForm"><?php echo $this->_tpl_vars['lng']['lbl_phone']; ?>
:</font> <?php echo $this->_tpl_vars['giftcerts_data'][$this->_sections['giftcert']['index']]['recipient_phone']; ?>
<br />
<?php endif;  endif; ?>
<font class="TableCenterCustomerForm"><?php echo $this->_tpl_vars['lng']['lbl_amount']; ?>
:</font> <font class="TableCenterProductPriceOrange"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['giftcerts_data'][$this->_sections['giftcert']['index']]['amount'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></font>
<br />
<br />

<?php if ($this->_tpl_vars['active_modules']['Wishlist'] != "" && $this->_tpl_vars['wl_giftcerts'] != ""):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Wishlist/wl_buttons.tpl", 'smarty_include_vars' => array('buttons_for' => 'giftcerts')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  else: ?>
<table cellspacing="0" cellpadding="0">
<tr>
	<td class="ButtonsRow"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/delete_item.tpl", 'smarty_include_vars' => array('href' => "giftcert.php?mode=delgc&gcindex=".($this->_sections['giftcert']['index']))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
	<td class="ButtonsRow"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/modify.tpl", 'smarty_include_vars' => array('href' => "giftcert.php?gcindex=".($this->_sections['giftcert']['index']))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>
</table>
<?php endif; ?>

	</td>
</tr>
</table>
<hr size="1" noshade="noshade" />
<?php endfor; endif;  endif; ?>
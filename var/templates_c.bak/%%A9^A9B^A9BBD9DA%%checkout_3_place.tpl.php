<?php /* Smarty version 2.6.12, created on 2011-10-11 06:23:05
         compiled from modules/Fast_Lane_Checkout/checkout_3_place.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'cat', 'modules/Fast_Lane_Checkout/checkout_3_place.tpl', 85, false),)), $this); ?>
<?php func_load_lang($this, "modules/Fast_Lane_Checkout/checkout_3_place.tpl","lbl_personal_information,lbl_payment_method,lbl_change_payment_method,lbl_payment_details,txt_terms_and_conditions_note,lbl_submit_order,lbl_submit_order,lbl_payment_details"); ?><br>
<?php ob_start(); ?>

<?php if ($this->_tpl_vars['config']['Appearance']['show_cart_details'] == 'Y' || ( $this->_tpl_vars['config']['Appearance']['show_cart_details'] == 'L' && $GLOBALS['HTTP_GET_VARS']['paymentid'] != "" && $GLOBALS['HTTP_GET_VARS']['mode'] == 'checkout' )): ?> 
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/cart_details.tpl", 'smarty_include_vars' => array('link_qty' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  else:  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/cart_contents.tpl", 'smarty_include_vars' => array('link_qty' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/cart_totals.tpl", 'smarty_include_vars' => array('link_shipping' => 'Y','no_form_fields' => true)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<br /><br />

<form action="<?php echo $this->_tpl_vars['payment_data']['payment_script_url']; ?>
" method="post" name="checkout_form">
<input type="hidden" name="paymentid" value="<?php echo $this->_tpl_vars['payment_data']['paymentid']; ?>
" />
<input type="hidden" name="action" value="place_order" />
<table cellpadding="0" cellspacing="0" width="100%">
<tr><td>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_personal_information'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/modify.tpl", 'smarty_include_vars' => array('href' => "register.php?mode=update&amp;action=cart&amp;paymentid=".($GLOBALS['HTTP_GET_VARS']['paymentid']))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></div>

<table cellspacing="0" cellpadding="0" width="100%">
<tr>
<td width="20"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" width="20" height="1" alt="" /></td>
<td>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Fast_Lane_Checkout/customer_details_html.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</td>
</tr>
</table>

<br /><br />

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/subheader.tpl", 'smarty_include_vars' => array('title' => ($this->_tpl_vars['lng']['lbl_payment_method']).": ".($this->_tpl_vars['payment_data']['payment_method']))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  if ($this->_tpl_vars['ignore_payment_method_selection'] == ""): ?>
<div align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/button.tpl", 'smarty_include_vars' => array('button_title' => $this->_tpl_vars['lng']['lbl_change_payment_method'],'href' => "cart.php?mode=checkout")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></div>
<?php endif; ?>

<input type="hidden" name="<?php echo $this->_tpl_vars['XCARTSESSNAME']; ?>
" value="<?php echo $this->_tpl_vars['XCARTSESSID']; ?>
" />
<script type="text/javascript">
<!--
requiredFields = new Array();
-->
</script>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "check_required_fields_js.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<table cellspacing="0" cellpadding="0" width="100%">
<tr>
<td width="20"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" width="20" height="1" alt="" /></td>
<td>
<?php if ($this->_tpl_vars['payment_data']['payment_template'] != ""):  ob_start();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['payment_data']['payment_template'], 'smarty_include_vars' => array('hide_header' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  $this->_smarty_vars['capture']['payment_template_output'] = ob_get_contents(); ob_end_clean();  if ($this->_smarty_vars['capture']['payment_template_output'] != ""):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_payment_details'],'class' => 'grey')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  echo $this->_smarty_vars['capture']['payment_template_output']; ?>

<br />
<?php endif;  endif; ?>

<?php if ($this->_tpl_vars['payment_cc_data']['cmpi'] == 'Y' && $this->_tpl_vars['config']['CMPI']['cmpi_enabled'] == 'Y'):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/cmpi.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/checkout_notes.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  if ($this->_tpl_vars['active_modules']['XAffiliate'] == 'Y' && $this->_tpl_vars['partner'] == ''):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "partner/main/checkout_partner.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
</td>
</tr>
</table>

<br />
<input type="hidden" name="payment_method" value="<?php echo $this->_tpl_vars['payment_data']['payment_method_orig']; ?>
" />
<center>
<?php echo $this->_tpl_vars['lng']['txt_terms_and_conditions_note']; ?>


<p />

<?php if ($this->_tpl_vars['js_enabled']):  $this->assign('button_href', "javascript: ");  if ($this->_tpl_vars['config']['General']['check_cc_number'] == 'Y' && ( $this->_tpl_vars['payment_cc_data']['type'] == 'C' || $this->_tpl_vars['payment_data']['paymentid'] == 1 || ( $this->_tpl_vars['payment_data']['processor_file'] == "ps_paypal_pro.php" && $this->_tpl_vars['payment_cc_data']['paymentid'] != $this->_tpl_vars['payment_data']['paymentid'] ) ) && $this->_tpl_vars['payment_cc_data']['disable_ccinfo'] != 'Y'):  $this->assign('button_href', ((is_array($_tmp=$this->_tpl_vars['button_href'])) ? $this->_run_mod_handler('cat', true, $_tmp, "if(checkCCNumber(document.checkout_form.card_number,document.checkout_form.card_type) && checkExpirationDate(document.checkout_form.card_expire_Month,document.checkout_form.card_expire_Year)") : smarty_modifier_cat($_tmp, "if(checkCCNumber(document.checkout_form.card_number,document.checkout_form.card_type) && checkExpirationDate(document.checkout_form.card_expire_Month,document.checkout_form.card_expire_Year)")));  if ($this->_tpl_vars['payment_cc_data']['disable_ccinfo'] != 'C'):  $this->assign('button_href', ((is_array($_tmp=$this->_tpl_vars['button_href'])) ? $this->_run_mod_handler('cat', true, $_tmp, " && checkCVV2(document.checkout_form.card_cvv2,document.checkout_form.card_type)") : smarty_modifier_cat($_tmp, " && checkCVV2(document.checkout_form.card_cvv2,document.checkout_form.card_type)")));  endif;  $this->assign('button_href', ((is_array($_tmp=$this->_tpl_vars['button_href'])) ? $this->_run_mod_handler('cat', true, $_tmp, ")") : smarty_modifier_cat($_tmp, ")")));  endif;  $this->assign('button_href', ((is_array($_tmp=$this->_tpl_vars['button_href'])) ? $this->_run_mod_handler('cat', true, $_tmp, " if(checkRequired(requiredFields)) document.checkout_form.submit()") : smarty_modifier_cat($_tmp, " if(checkRequired(requiredFields)) document.checkout_form.submit()"))); ?>

<?php if ($this->_tpl_vars['payment_data']['processor_file'] == 'ps_gcheckout.php'):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/gcheckout.tpl", 'smarty_include_vars' => array('onclick' => $this->_tpl_vars['button_href'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  else:  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/button.tpl", 'smarty_include_vars' => array('button_title' => $this->_tpl_vars['lng']['lbl_submit_order'],'style' => 'button','href' => $this->_tpl_vars['button_href'],'b' => 1)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

<?php else: ?>

<?php if ($this->_tpl_vars['payment_data']['processor_file'] == 'ps_gcheckout.php'):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/gcheckout.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  else:  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "submit_wo_js.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['lng']['lbl_submit_order'],'b' => 1)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

<?php endif; ?>
</center>
</td></tr>
</table>
</form>
<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_payment_details'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
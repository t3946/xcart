<?php /* Smarty version 2.6.12, created on 2011-10-11 06:21:51
         compiled from modules/Fast_Lane_Checkout/checkout_0_enter.tpl */ ?>
<table cellpadding="0" cellspacing="0" width="100%">
<tr>
<?php if ($this->_tpl_vars['active_modules']['Image_Verification'] && $this->_tpl_vars['show_antibot']['on_login'] == 'Y' && $this->_tpl_vars['login_antibot_on']):  $this->assign('is_antibot', 'Y');  endif; ?>
<td class="<?php if ($this->_tpl_vars['is_antibot'] == 'Y'): ?>FLCDialogCellAntibot<?php else: ?>FLCDialogCell<?php endif; ?>">

<?php ob_start(); ?>



<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/register.tpl", 'smarty_include_vars' => array('is_flc' => true)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog_FLC.tpl", 'smarty_include_vars' => array('title' => '','content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'class="FLCDialog"','is_flc_dialog' => true,'align' => 'center')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

</td>
</tr>
</table>

<?php if ($this->_tpl_vars['paypal_express_active']):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "payments/ps_paypal_pro_express_checkout.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

<?php if (( $this->_tpl_vars['top_message']['reg_error'] != '' || $this->_tpl_vars['av_error'] == 1 ) && $GLOBALS['HTTP_GET_VARS']['toreg'] == ''): ?>
<script type="text/javascript">
<!--
self.location.hash = 'regdlg';
-->
</script>
<?php endif; ?>
<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:17
         compiled from modules/Fast_Lane_Checkout/checkout_0_enter.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'modules/Fast_Lane_Checkout/checkout_0_enter.tpl', 1, false),)), $this); ?>
<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "modules/Fast_Lane_Checkout/checkout_0_enter.tpl"), $this); endif; ?><table cellpadding="0" cellspacing="0" width="100%">
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

<?php if (( $this->_tpl_vars['top_message']['reg_error'] != '' || $this->_tpl_vars['av_error'] == 1 ) && $GLOBALS['_GET']['toreg'] == ''): ?>
<script type="text/javascript">
<!--
self.location.hash = 'regdlg';
-->
</script>
<?php endif;  if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "modules/Fast_Lane_Checkout/checkout_0_enter.tpl"), $this); endif; ?>
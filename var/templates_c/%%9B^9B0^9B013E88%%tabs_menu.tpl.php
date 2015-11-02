<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:13
         compiled from modules/Fast_Lane_Checkout/tabs_menu.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'modules/Fast_Lane_Checkout/tabs_menu.tpl', 1, false),)), $this); ?>
<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "modules/Fast_Lane_Checkout/tabs_menu.tpl"), $this); endif;  if ($this->_tpl_vars['saved_paymentid'] != 'Y'): ?><div align="right" id="cidev_tabs_menu"><?php endif; ?>
<table width="960" align="center" border="0" cellpadding="0" cellspacing="0"><tr>

<tr>
<td
<?php if ($this->_tpl_vars['checkout_step'] == "-1" && $this->_tpl_vars['login'] == ""): ?>
class="cidev_checkout_bar0"
<?php elseif ($this->_tpl_vars['checkout_step'] == "-1" && $this->_tpl_vars['login'] != ""): ?>
class="cidev_checkout_bar01"
<?php elseif ($this->_tpl_vars['checkout_step'] == '0'): ?>
class="cidev_checkout_bar2"
<?php elseif ($this->_tpl_vars['checkout_step'] == '1'): ?>
	<?php if ($this->_tpl_vars['cart']['paymentid'] != "" || $this->_tpl_vars['paymentid'] != ""): ?>
class="cidev_checkout_bar22"
	<?php else: ?>
class="cidev_checkout_bar2"
	<?php endif;  elseif ($this->_tpl_vars['checkout_step'] == '2'): ?>
class="cidev_checkout_bar3"
<?php elseif ($this->_tpl_vars['checkout_step'] == '3'): ?>
class="cidev_checkout_bar4"
<?php elseif ($this->_tpl_vars['paypal_payment'] == 'Y'): ?>
class="cidev_checkout_bar5"
<?php endif; ?>
>
<?php if ($this->_tpl_vars['checkout_step'] == "-1" && $this->_tpl_vars['login'] == ""): ?>

<?php elseif ($this->_tpl_vars['checkout_step'] == '0'): ?>
<ul class="cidev_checkout_tabs">
 <li class="cidev_checkout_link_cart" onclick="javascript: self.location='cart.php'"></li>
</ul>
<?php elseif ($this->_tpl_vars['checkout_step'] == "-1" && $this->_tpl_vars['login'] != ""): ?>
<ul class="cidev_checkout_tabs">
 <li class="cidev_checkout_link_cart" style="cursor: default;"></li>
 <li class="cidev_checkout_link_address" onclick="javascript: self.location='register.php?mode=update&action=cart&paymentid=<?php echo $this->_tpl_vars['cart']['paymentid']; ?>
'"></li>
 <li class="cidev_checkout_link_shippings" onclick="javascript: self.location='cart.php?mode=checkout'"></li>
</ul>
<?php elseif ($this->_tpl_vars['checkout_step'] == '1'): ?>
<ul class="cidev_checkout_tabs">
 <li class="cidev_checkout_link_cart" onclick="javascript: self.location='cart.php'"></li>
<?php if ($this->_tpl_vars['cart']['paymentid'] != "" || $this->_tpl_vars['paymentid'] != ""): ?>
<li class="cidev_checkout_link_address" style="cursor: default;"></li>
<li class="cidev_checkout_link_shippings" onclick="javascript: document.registerform.action='register.php?mode=update&action=cart&cidev_return_to_step=3&paymentid=<?php echo $this->_tpl_vars['paymentid']; ?>
'; document.registerform.submit();"></li>
<?php endif; ?>
</ul>
<?php elseif ($this->_tpl_vars['checkout_step'] == '2'): ?>
<ul class="cidev_checkout_tabs">
 <li class="cidev_checkout_link_cart" onclick="javascript: self.location='cart.php'"></li>
 <li class="cidev_checkout_link_address" onclick="javascript: self.location='register.php?mode=update&action=cart&paymentid=<?php echo $this->_tpl_vars['cart']['paymentid']; ?>
'"></li>
</ul>
<?php elseif ($this->_tpl_vars['checkout_step'] == '3'): ?>
<ul class="cidev_checkout_tabs">
 <li class="cidev_checkout_link_cart" onclick="javascript: self.location='cart.php'"></li>
 <li class="cidev_checkout_link_address" onclick="javascript: self.location='register.php?mode=update&action=cart&paymentid=<?php echo $this->_tpl_vars['paymentid']; ?>
'"></li>
 <li class="cidev_checkout_link_shippings" onclick="javascript: self.location='cart.php?mode=checkout'"></li>
</ul>
<?php elseif ($this->_tpl_vars['checkout_step'] == '4'): ?>
<ul class="cidev_checkout_tabs">
 <li class="cidev_checkout_link_cart" onclick="javascript: self.location='cart.php'"></li>
 <li class="cidev_checkout_link_address" onclick="javascript: self.location='register.php?mode=update&action=cart&paymentid=<?php echo $this->_tpl_vars['cart']['paymentid']; ?>
'"></li>
 <li class="cidev_checkout_link_shippings" onclick="javascript: self.location='cart.php?mode=checkout'"></li>
 <li class="cidev_checkout_link_review" onclick="javascript: self.location='cart.php?mode=checkout&paymentid=<?php echo $this->_tpl_vars['paymentid']; ?>
'"></li>
</ul>
<?php elseif ($this->_tpl_vars['paypal_payment'] == 'Y'): ?>
<ul class="cidev_checkout_tabs">
 <li class="cidev_checkout_link_cart" onclick="javascript: self.location='../cart.php'"></li>
 <li class="cidev_checkout_link_address" onclick="javascript: self.location='../register.php?mode=update&action=cart&paymentid=<?php echo $this->_tpl_vars['cart']['paymentid']; ?>
'"></li>
 <li class="cidev_checkout_link_shippings" onclick="javascript: self.location='../cart.php?mode=checkout'"></li>
 <li class="cidev_checkout_link_review" onclick="javascript: self.location='../cart.php?mode=checkout&paymentid=<?php echo $this->_tpl_vars['paymentid']; ?>
'"></li>
</ul>
<?php endif; ?>
</td>
</tr>
</table>
<?php if ($this->_tpl_vars['saved_paymentid'] != 'Y'): ?>
</div>
<br />
<?php endif;  if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "modules/Fast_Lane_Checkout/tabs_menu.tpl"), $this); endif; ?>
<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:13
         compiled from modules/Fast_Lane_Checkout/home_main.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'modules/Fast_Lane_Checkout/home_main.tpl', 1, false),)), $this); ?>
<?php func_load_lang($this, "modules/Fast_Lane_Checkout/home_main.tpl","lbl_continue_shopping,lbl_shipping_quote,lbl_checkout,lbl_checkout,lbl_checkout,lbl_checkout"); ?><?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "modules/Fast_Lane_Checkout/home_main.tpl"), $this); endif;  if ($this->_tpl_vars['checkout_step'] == 0):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Fast_Lane_Checkout/checkout_0_enter.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php elseif ($this->_tpl_vars['checkout_step'] == 1):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Fast_Lane_Checkout/checkout_1_profile.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php elseif ($this->_tpl_vars['checkout_step'] == 2):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Fast_Lane_Checkout/checkout_2_method.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php elseif ($this->_tpl_vars['checkout_step'] == 3):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Fast_Lane_Checkout/checkout_3_place.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php else:  if ($this->_tpl_vars['last_categoryid'] != 0):  $this->assign('last_categoryid', "?cat=".($this->_tpl_vars['last_categoryid']));  else:  $this->assign('last_categoryid', "");  endif; ?>


<script type="text/javascript" language="JavaScript 1.2">
//<![CDATA[
<?php echo '

var set_warehouse_background = 0;

function func_set_warehouse_background(m){

return true;

/*
	if (set_warehouse_background == "0"){
		document.getElementById(\'warehouse\').style.background = \'#CC3333\';
		alert(m);
	}
	if (set_warehouse_background == "1") {
		document.getElementById(\'warehouse\').style.background = \'#ffffff\';
	}
        if (set_warehouse_background == "2") {
		return true;
	}

	set_warehouse_background++;
	setTimeout(func_set_warehouse_background(), 100);
	set_warehouse_background = 0;
*/
}
'; ?>

//]]>
</script>


<div align="left" width="100%">
<table cellpadding="0" cellspacing="0" width="100%">
<tr>
	<td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Fast_Lane_Checkout/big_button.tpl", 'smarty_include_vars' => array('button_title' => $this->_tpl_vars['lng']['lbl_continue_shopping'],'style' => 'button','href' => "home.php".($this->_tpl_vars['last_categoryid']))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
	<td>
<?php if ($this->_tpl_vars['variant_id_for_point2'] != "" && $this->_tpl_vars['variant_id_for_point2'] == '0'): ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Fast_Lane_Checkout/big_button.tpl", 'smarty_include_vars' => array('button_title' => $this->_tpl_vars['lng']['lbl_shipping_quote'],'bold' => 'N','style' => 'button','href' => "javascript: window.open('popup_shipquote.php','popup_shipquote','width=800,height=600,toolbar=no,status=no,scrollbars=yes,menubar=no,location=no,direction=no');",'js_to_href' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
	</td>

	<td>
<?php if ($this->_tpl_vars['variant_id_for_point6'] == '1'): ?>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Fast_Lane_Checkout/big_button.tpl", 'smarty_include_vars' => array('button_title' => 'Request a quote','bold' => 'N','style' => 'button','href' => "javascript: window.open('popup_requestaquote.php','popup_requestaquote','width=800,height=600,toolbar=no,status=no,scrollbars=yes,menubar=no,location=no,direction=no');",'js_to_href' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
	</td>

	<td width="30%">&nbsp;</td>
	<td align="right">
<?php if ($this->_tpl_vars['cart']['paymentid'] != ""): ?>
	<?php if ($this->_tpl_vars['warehouse_cart_url'] != ""):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Fast_Lane_Checkout/big_button.tpl", 'smarty_include_vars' => array('button_title' => $this->_tpl_vars['lng']['lbl_checkout'],'style' => 'button','href' => $this->_tpl_vars['warehouse_cart_url'],'color' => 'red','arrow' => 'Y','js_onclick_to_href' => "func_set_warehouse_background('".($this->_tpl_vars['lbl_minimum_order_amount_mes'])."');")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php else:  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Fast_Lane_Checkout/big_button.tpl", 'smarty_include_vars' => array('button_title' => $this->_tpl_vars['lng']['lbl_checkout'],'style' => 'button','href' => "cart.php?mode=checkout&l=y&review=y&paymentid=".($this->_tpl_vars['cart']['paymentid']),'color' => 'red','arrow' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php endif;  else: ?>
	<?php if ($this->_tpl_vars['warehouse_cart_url'] != ""):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Fast_Lane_Checkout/big_button.tpl", 'smarty_include_vars' => array('button_title' => $this->_tpl_vars['lng']['lbl_checkout'],'style' => 'button','href' => $this->_tpl_vars['warehouse_cart_url'],'color' => 'red','arrow' => 'Y','js_onclick_to_href' => "func_set_warehouse_background('".($this->_tpl_vars['lbl_minimum_order_amount_mes'])."');")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php else:  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Fast_Lane_Checkout/big_button.tpl", 'smarty_include_vars' => array('button_title' => $this->_tpl_vars['lng']['lbl_checkout'],'style' => 'button','href' => "cart.php?mode=checkout&l=y",'color' => 'red','arrow' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php endif;  endif; ?>
	</td>
</tr>
</table>
</div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/cart.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php endif;  if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "modules/Fast_Lane_Checkout/home_main.tpl"), $this); endif; ?>
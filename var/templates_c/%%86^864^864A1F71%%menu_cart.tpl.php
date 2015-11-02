<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:05
         compiled from customer/menu_cart.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'customer/menu_cart.tpl', 1, false),)), $this); ?>
<?php func_load_lang($this, "customer/menu_cart.tpl","lbl_shipping_quote"); ?><?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "customer/menu_cart.tpl"), $this); endif; ?><div id="ajax_minicart">

<table cellpadding="0" cellspacing="0" border="0">
<tr <?php if ($this->_tpl_vars['minicart_total_items'] > 0): ?> id="id_tr_minicart" onclick="javascript: self.location='/cart.php';" style="cursor: pointer;"<?php endif; ?>>
        <td class="cidev_minicart_l"></td>
        <td class="cidev_minicart_c"><div class="cidev_minicart_c_amount"><?php if ($this->_tpl_vars['minicart_total_items'] > 0):  echo $this->_tpl_vars['minicart_total_items'];  else: ?>0<?php endif; ?></div></td>
	<?php if ($this->_tpl_vars['minicart_total_items'] > 0): ?>
        <td class="cidev_minicart_r"><a id="id_tr_minicart_a" href="/cart.php"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" width="1" height="1" alt="View Cart" /></a></td>
	<?php else: ?>
        <td class="cidev_minicart_r_empty"></td>
	<?php endif; ?>
</tr>
</tr>
</table>
<?php if ($this->_tpl_vars['minicart_total_items'] > 0 && $this->_tpl_vars['variant_id_for_point2'] == '0' && $this->_tpl_vars['variant_id_for_point2'] != ""): ?>
<div style="position: absolute; margin-top: 2px; padding-left: 2px;">
<a href="javascript: void(0);" onclick="javascript: window.open('/popup_shipquote.php?short=Y','popup_shipquote','width=800,height=600,toolbar=no,status=no,scrollbars=yes,menubar=no,location=no,direction=no');" class="cart_popup_shipquote"><?php echo $this->_tpl_vars['lng']['lbl_shipping_quote']; ?>
</a>
</div>
<?php endif; ?>





</div>
<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "customer/menu_cart.tpl"), $this); endif; ?>
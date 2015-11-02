<?php /* Smarty version 2.6.12, created on 2011-10-11 07:07:23
         compiled from customer/main/cart.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'math', 'customer/main/cart.tpl', 37, false),)), $this); ?>
<?php func_load_lang($this, "customer/main/cart.tpl","lbl_your_shopping_cart,txt_cart_note,txt_cart_header,lbl_selected_options,lbl_no_shipping_for_location,lbl_update_qty,lbl_gcheckout_product_disabled,lbl_subtotal_sg,lbl_your_mer_subtotal,lbl_continue_shopping,lbl_shipping_quote,lbl_checkout,lbl_shipping_quote,lbl_update_qties,lbl_clear_cart,lbl_checkout,lbl_checkout,txt_your_shopping_cart_is_empty,lbl_items_in_cart"); ?><?php $this->assign('subtotal_shipping_charge', 0);  if ($this->_tpl_vars['active_modules']['Product_Options']):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/include_js.tpl", 'smarty_include_vars' => array('src' => "modules/Product_Options/edit_product_options.js")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

<h3><?php echo $this->_tpl_vars['lng']['lbl_your_shopping_cart']; ?>
</h3>
<?php if ($this->_tpl_vars['cart'] != ''):  if ($this->_tpl_vars['active_modules']['Gift_Certificates'] != ""):  echo $this->_tpl_vars['lng']['txt_cart_note']; ?>

<?php endif;  endif; ?>
<p />
<?php ob_start();  echo $this->_tpl_vars['lng']['txt_cart_header']; ?>

<?php if ($this->_tpl_vars['active_modules']['Special_Offers']):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Special_Offers/customer/cart_offers.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
<p />
<?php if ($this->_tpl_vars['products'] != ""): ?>
<form action="cart.php" method="post" name="cartform">
<table width="100%">
<?php $_from = $this->_tpl_vars['cart']['shipping_groups']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['shipping_groups_f'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['shipping_groups_f']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
        $this->_foreach['shipping_groups_f']['iteration']++;
?>
<tr>
<td colspan="3" valign="top" class="DialogTitleBox"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" width="1" height="1" alt="" >
</td>
</tr>
<tr>
<td colspan="2">
<table><tr><td class="DialogTitle"><b><?php echo $this->_tpl_vars['v']['group_name']; ?>
</b></td></tr><tr><td><?php echo $this->_tpl_vars['v']['manufact_text_displayed']; ?>
</td></tr></table></td>
</tr>
<tr><td colspan="2"><hr size="1" noshade="noshade" /></td></tr>
<?php $this->assign('deliv_subt', '0');  unset($this->_sections['product']);
$this->_sections['product']['name'] = 'product';
$this->_sections['product']['loop'] = is_array($_loop=$this->_tpl_vars['products']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['product']['show'] = true;
$this->_sections['product']['max'] = $this->_sections['product']['loop'];
$this->_sections['product']['step'] = 1;
$this->_sections['product']['start'] = $this->_sections['product']['step'] > 0 ? 0 : $this->_sections['product']['loop']-1;
if ($this->_sections['product']['show']) {
    $this->_sections['product']['total'] = $this->_sections['product']['loop'];
    if ($this->_sections['product']['total'] == 0)
        $this->_sections['product']['show'] = false;
} else
    $this->_sections['product']['total'] = 0;
if ($this->_sections['product']['show']):

            for ($this->_sections['product']['index'] = $this->_sections['product']['start'], $this->_sections['product']['iteration'] = 1;
                 $this->_sections['product']['iteration'] <= $this->_sections['product']['total'];
                 $this->_sections['product']['index'] += $this->_sections['product']['step'], $this->_sections['product']['iteration']++):
$this->_sections['product']['rownum'] = $this->_sections['product']['iteration'];
$this->_sections['product']['index_prev'] = $this->_sections['product']['index'] - $this->_sections['product']['step'];
$this->_sections['product']['index_next'] = $this->_sections['product']['index'] + $this->_sections['product']['step'];
$this->_sections['product']['first']      = ($this->_sections['product']['iteration'] == 1);
$this->_sections['product']['last']       = ($this->_sections['product']['iteration'] == $this->_sections['product']['total']);
 if (( $this->_tpl_vars['products'][$this->_sections['product']['index']]['manufacturerid'] == $this->_tpl_vars['k'] && $this->_tpl_vars['products'][$this->_sections['product']['index']]['shipping_freight'] != '0' ) || ( $this->_tpl_vars['k'] == $this->_tpl_vars['artss_manufacturerid'] && $this->_tpl_vars['products'][$this->_sections['product']['index']]['shipping_freight'] == '0' )):  echo smarty_function_math(array('equation' => "x+y",'x' => $this->_tpl_vars['deliv_subt'],'y' => $this->_tpl_vars['products'][$this->_sections['product']['index']]['display_subtotal'],'assign' => 'deliv_subt'), $this);?>

<?php if ($this->_tpl_vars['products'][$this->_sections['product']['index']]['hidden'] == ""): ?>
<tr><td class="PListImgBox">
<a href="product.php?productid=<?php echo $this->_tpl_vars['products'][$this->_sections['product']['index']]['productid']; ?>
"><?php if ($this->_tpl_vars['products'][$this->_sections['product']['index']]['is_pimage'] == 'W'):  $this->assign('imageid', $this->_tpl_vars['products'][$this->_sections['product']['index']]['variantid']);  else:  $this->assign('imageid', $this->_tpl_vars['products'][$this->_sections['product']['index']]['productid']);  endif;  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "product_thumbnail.tpl", 'smarty_include_vars' => array('productid' => $this->_tpl_vars['imageid'],'image_x' => $this->_tpl_vars['config']['Appearance']['thumbnail_width'],'product' => $this->_tpl_vars['products'][$this->_sections['product']['index']]['product'],'tmbn_url' => $this->_tpl_vars['products'][$this->_sections['product']['index']]['pimage_url'],'type' => $this->_tpl_vars['products'][$this->_sections['product']['index']]['is_pimage'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></a>
<?php if ($this->_tpl_vars['active_modules']['Special_Offers'] != "" && $this->_tpl_vars['products'][$this->_sections['product']['index']]['have_offers']):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Special_Offers/customer/product_offer_thumb.tpl", 'smarty_include_vars' => array('product' => $this->_tpl_vars['products'][$this->_sections['product']['index']])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
</td>
<td valign="top">
<a href="product.php?productid=<?php echo $this->_tpl_vars['products'][$this->_sections['product']['index']]['productid']; ?>
"><font class="ProductTitle"><?php echo $this->_tpl_vars['products'][$this->_sections['product']['index']]['product']; ?>
</font></a>
<br>
<font color="#006600" class="DialogTitleT">SKU: <?php echo $this->_tpl_vars['products'][$this->_sections['product']['index']]['productcode']; ?>
</font>
<br>
<br>
<table cellpadding="0" cellspacing="0" width="100%"><tr><td>
<?php echo $this->_tpl_vars['products'][$this->_sections['product']['index']]['descr']; ?>

</td></tr></table>
<br />
<?php if ($this->_tpl_vars['products'][$this->_sections['product']['index']]['product_options'] != ""): ?>
<b><?php echo $this->_tpl_vars['lng']['lbl_selected_options']; ?>
:</b><br />
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Product_Options/display_options.tpl", 'smarty_include_vars' => array('options' => $this->_tpl_vars['products'][$this->_sections['product']['index']]['product_options'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<br />
<br />
<?php endif;  $this->assign('price', $this->_tpl_vars['products'][$this->_sections['product']['index']]['display_price']);  if ($this->_tpl_vars['active_modules']['Product_Configurator'] != "" && $this->_tpl_vars['products'][$this->_sections['product']['index']]['product_type'] == 'C'):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Product_Configurator/pconf_customer_cart.tpl", 'smarty_include_vars' => array('main_product' => $this->_tpl_vars['products'][$this->_sections['product']['index']])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  $this->assign('price', $this->_tpl_vars['products'][$this->_sections['product']['index']]['pconf_display_price']); ?>
<br /><br />
<?php endif; ?>
<div align="left">
<?php if ($this->_tpl_vars['active_modules']['Subscriptions'] != "" && $this->_tpl_vars['products'][$this->_sections['product']['index']]['sub_plan'] != "" && $this->_tpl_vars['products'][$this->_sections['product']['index']]['product_type'] != 'C'):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Subscriptions/subscription_priceincart.tpl", 'smarty_include_vars' => array('product' => $this->_tpl_vars['products'][$this->_sections['product']['index']])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  else:  if ($this->_tpl_vars['active_modules']['Special_Offers']):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Special_Offers/customer/cart_price_special.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
<font class="ProductPriceConverting"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['price'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> x <?php if ($this->_tpl_vars['active_modules']['Egoods'] && $this->_tpl_vars['products'][$this->_sections['product']['index']]['distribution']): ?>1<input type="hidden"<?php else: ?><input type="text" size="3"<?php endif; ?> name="productindexes[<?php echo $this->_tpl_vars['products'][$this->_sections['product']['index']]['cartid']; ?>
]" value="<?php echo $this->_tpl_vars['products'][$this->_sections['product']['index']]['amount']; ?>
" /> = </font><font class="ProductPrice"><?php echo smarty_function_math(array('equation' => "price*amount",'price' => $this->_tpl_vars['price'],'amount' => $this->_tpl_vars['products'][$this->_sections['product']['index']]['amount'],'format' => "%.2f",'assign' => 'unformatted'), $this); $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['unformatted'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></font><font class="MarketPrice"> <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/alter_currency_value.tpl", 'smarty_include_vars' => array('alter_currency_value' => $this->_tpl_vars['unformatted'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></font>
<br />
<?php echo smarty_function_math(array('equation' => "price*amount",'price' => $this->_tpl_vars['products'][$this->_sections['product']['index']]['shipping_freight'],'amount' => $this->_tpl_vars['products'][$this->_sections['product']['index']]['amount'],'assign' => 'charge'), $this);?>


<?php echo smarty_function_math(array('equation' => "subtotal_shipping_charge+charge",'charge' => $this->_tpl_vars['charge'],'subtotal_shipping_charge' => $this->_tpl_vars['subtotal_shipping_charge'],'assign' => 'subtotal_shipping_charge'), $this);?>


<?php if ($this->_tpl_vars['config']['Taxes']['display_taxed_order_totals'] == 'Y' && $this->_tpl_vars['products'][$this->_sections['product']['index']]['taxes']): ?><br /><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/taxed_price.tpl", 'smarty_include_vars' => array('taxes' => $this->_tpl_vars['products'][$this->_sections['product']['index']]['taxes'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  if ($this->_tpl_vars['active_modules']['Special_Offers']):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Special_Offers/customer/cart_free.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  endif; ?>
<br />
<table cellspacing="0" cellpadding="0">
<?php if ($this->_tpl_vars['shippings'][$this->_tpl_vars['k']] == "" && $this->_tpl_vars['login'] != ""): ?>
<tr>
	<td class="ButtonsRow" colspan="2">
	<font color="red"><?php echo $this->_tpl_vars['lng']['lbl_no_shipping_for_location']; ?>
</font>
	</td>
</tr>
<?php endif; ?>
<tr>
	<td class="ButtonsRow"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/button.tpl", 'smarty_include_vars' => array('button_title' => $this->_tpl_vars['lng']['lbl_update_qty'],'type' => 'input','href' => "javascript: document.cartform.submit()",'js_to_href' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
	<td class="ButtonsRow"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/delete_item.tpl", 'smarty_include_vars' => array('href' => "cart.php?mode=delete&amp;productindex=".($this->_tpl_vars['products'][$this->_sections['product']['index']]['cartid']))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
	<td class="ButtonsRow">

<?php if ($this->_tpl_vars['products'][$this->_sections['product']['index']]['product_options'] != ''):  if ($this->_tpl_vars['config']['UA']['platform'] == 'MacPPC' && $this->_tpl_vars['config']['UA']['browser'] == 'MSIE'):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/edit_product_options.tpl", 'smarty_include_vars' => array('id' => $this->_tpl_vars['products'][$this->_sections['product']['index']]['cartid'],'js_to_href' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  else:  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/edit_product_options.tpl", 'smarty_include_vars' => array('id' => $this->_tpl_vars['products'][$this->_sections['product']['index']]['cartid'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  endif; ?>
	</td>
</tr>
</table>
<?php if ($this->_tpl_vars['gcheckout_display_product_note'] && $this->_tpl_vars['products'][$this->_sections['product']['index']]['valid_for_gcheckout'] == 'N'): ?>
<br />
<?php echo $this->_tpl_vars['lng']['lbl_gcheckout_product_disabled']; ?>

<?php endif; ?>
</div>
</td></tr>
<tr><td colspan="2"><hr size="1" noshade="noshade" /></td></tr>
<?php endif;  endif;  endfor; endif;  if ($this->_tpl_vars['catalog_checkboxes'][$this->_tpl_vars['k']]): ?>
<tr>
	<td colspan="2">
		<table cellpadding="2" cellspacing="0">
		<tr>
			<td>&nbsp;</td>
			<td><input type="checkbox" name="add_catalog[<?php echo $this->_tpl_vars['k']; ?>
]" value="Y" id="cc_<?php echo $this->_tpl_vars['k']; ?>
" onclick="javascript: add_catalog(<?php echo $this->_tpl_vars['k']; ?>
);" /></td>
			<td><?php echo $this->_tpl_vars['catalog_checkboxes'][$this->_tpl_vars['k']]; ?>
</td>
		</tr>
		</table>
	</td>
</tr>
<tr><td colspan="2"><hr size="1" noshade="noshade" /></td></tr>
<?php endif; ?>
<tr>
<td colspan="2">
<table cellpadding="3" cellspacing="0" width="30%" align="right">
<tr>
<td nowrap="nowrap">
<font color="#006600"><b><?php echo $this->_tpl_vars['v']['group_name']; ?>
&nbsp;<?php echo $this->_tpl_vars['lng']['lbl_subtotal_sg']; ?>
:</b></font>
</td>
<td><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/null.gif" width="5" height="1" alt="" /><br /></td>
<td nowrap="nowrap" align="right"><font class="ProductPriceSmall"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['deliv_subt'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></font>&nbsp;</td>
<td>&nbsp;</td>
</tr>
</table>
<br />
<br />
<br />
</td>
</tr>
<?php endforeach; endif; unset($_from); ?>
<tr><td colspan="2"><hr size="1" noshade="noshade" /></td></tr>
</table>
<?php if ($this->_tpl_vars['active_modules']['Gift_Certificates'] != ""):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Gift_Certificates/gc_cart.tpl", 'smarty_include_vars' => array('giftcerts_data' => $this->_tpl_vars['cart']['giftcerts'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  if ($this->_tpl_vars['main'] == 'fast_lane_checkout'):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Fast_Lane_Checkout/cart_subtotal.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  else:  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/cart_totals.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  echo $this->_tpl_vars['lng']['lbl_your_mer_subtotal']; ?>
<br /><br />
<?php if ($this->_tpl_vars['js_enabled']):  if ($this->_tpl_vars['active_modules']['Fast_Lane_Checkout']): ?>
<div align="left" width="100%">
	<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Fast_Lane_Checkout/big_button.tpl", 'smarty_include_vars' => array('button_title' => $this->_tpl_vars['lng']['lbl_continue_shopping'],'style' => 'button','href' => "home.php".($this->_tpl_vars['last_categoryid']))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
		<td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Fast_Lane_Checkout/big_button.tpl", 'smarty_include_vars' => array('button_title' => $this->_tpl_vars['lng']['lbl_shipping_quote'],'bold' => 'N','style' => 'button','href' => "javascript: window.open('popup_shipquote.php','popup_shipquote','width=800,height=600,toolbar=no,status=no,scrollbars=yes,menubar=no,location=no,direction=no');",'js_to_href' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
		<td width="30%">&nbsp;</td>
		<td align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Fast_Lane_Checkout/big_button.tpl", 'smarty_include_vars' => array('button_title' => $this->_tpl_vars['lng']['lbl_checkout'],'style' => 'button','href' => "cart.php?mode=checkout",'color' => 'red','arrow' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
	</tr>
	</table>
</div>
<?php else: ?>
<table cellpadding="0" cellspacing="0" width="100%">
<tr>
<td>
<table cellspacing="0" cellpadding="0">
<tr>
	<td class="ButtonsRow"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/button.tpl", 'smarty_include_vars' => array('button_title' => $this->_tpl_vars['lng']['lbl_shipping_quote'],'type' => 'input','href' => "javascript: window.open('popup_shipquote.php','popup_shipquote','width=800,height=600,toolbar=no,status=no,scrollbars=yes,menubar=no,location=no,direction=no');",'js_to_href' => 'Y','b' => '1')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
	<td class="ButtonsRow"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/button.tpl", 'smarty_include_vars' => array('button_title' => $this->_tpl_vars['lng']['lbl_update_qties'],'type' => 'input','href' => "javascript: document.cartform.submit()",'js_to_href' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
	<td class="ButtonsRow"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/button.tpl", 'smarty_include_vars' => array('button_title' => $this->_tpl_vars['lng']['lbl_clear_cart'],'href' => "cart.php?mode=clear_cart")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>
</table>
</td>
<?php if ($this->_tpl_vars['active_modules']['Special_Offers']):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Special_Offers/customer/cart_checkout_buttons.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
<td align="right">
<?php if ($this->_tpl_vars['gcheckout_enabled']):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Google_Checkout/gcheckout_button.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  else:  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/button.tpl", 'smarty_include_vars' => array('button_title' => $this->_tpl_vars['lng']['lbl_checkout'],'style' => 'button','href' => "cart.php?mode=checkout",'b' => '1')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
</td>
</tr>
</table>
<?php endif;  else: ?>
<input type="hidden" name="mode" value="checkout" />
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "submit_wo_js.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['lng']['lbl_checkout'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
</form>
<?php if ($this->_tpl_vars['catalog_checkboxes']): ?>
<form name="catalogform" action="cart.php" method="post">
	<input type="hidden" name="mode" value="add_catalog" />
	<input type="hidden" name="cc_manufacturerid" id="cc_manufacturerid" value="" />
</form>

<script type="text/javascript">
<!--
<?php echo '

	function add_catalog(id) {
		if (document.getElementById(\'cc_\' + id).checked) {
			document.catalogform.cc_manufacturerid.value = id;
			document.catalogform.submit();
		}
	}

'; ?>

-->
</script>
<?php endif;  else:  echo $this->_tpl_vars['lng']['txt_your_shopping_cart_is_empty']; ?>

<?php endif;  $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_items_in_cart'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php /* Smarty version 2.6.12, created on 2015-11-02 03:12:07
         compiled from customer/main/cart.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'customer/main/cart.tpl', 1, false),array('function', 'math', 'customer/main/cart.tpl', 246, false),array('modifier', 'substitute', 'customer/main/cart.tpl', 214, false),)), $this); ?>
<?php func_load_lang($this, "customer/main/cart.tpl","txt_cart_note,lbl_minimum_order_amount_message,lbl_items_shipped_from_warehouse,lbl_we_dont_ship_to_Canada_cart_page,lbl_selected_options,txt_warn_increase,lbl_no_shipping_for_location,lbl_gcheckout_product_disabled,lbl_minimum_order_amount_message,lbl_minimum_order_amount_message,lbl_subtotal_sg,lbl_your_mer_subtotal,lbl_shipping_quote,lbl_update_qties,lbl_clear_cart,lbl_checkout,lbl_checkout,lbl_checkout,txt_your_shopping_cart_is_empty"); ?><?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "customer/main/cart.tpl"), $this); endif; ?>
<div class="ui-grid-a">
  <div class="ui-block-a">
    <?php echo $this->_smarty_vars['capture']['continue_button']; ?>

  </div>
  <div class="ui-block-b checkout-button">
    <?php echo $this->_smarty_vars['capture']['checkout_button']; ?>

  </div>
</div>

<?php $this->assign('subtotal_shipping_charge', 0);  if ($this->_tpl_vars['active_modules']['Product_Options']):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/include_js.tpl", 'smarty_include_vars' => array('src' => "modules/Product_Options/edit_product_options.js")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

<script src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/cidev_ajax.js" type="text/javascript"></script>

<script type="text/javascript" language="JavaScript 1.2">
//<![CDATA[
<?php echo '
function cidev_update_product_amount(cartid, manufacturerid){

	var productindex_id = \'productindex_\' + cartid;
	var hidden_productindex_id = \'hidden_productindex_\' + cartid;
	var amount = $("#" + productindex_id).val();
	var hidden_amount = $("#" + hidden_productindex_id).val();

	amount = amount.replace(/[^0-9]/g, \'\');
	$(\'#\'+productindex_id).val(amount);

	if (amount > \'0\'){

		var check_amount = amount;
		check_amount = check_amount.replace(/^0*/g, \'\');
		if (check_amount != amount){
			amount = check_amount;
			$("#" + productindex_id).val(amount);
		}

		if (hidden_amount != amount && amount > \'0\'){
			$(\'#\'+hidden_productindex_id).val(amount);
			setTimeout(\'cidev_update_product_amount_next(\'+cartid+\',\'+amount+\',\'+manufacturerid+\')\', 600);
		}
	}
	else if (amount == \'\' || amount == \'0\') {
		setTimeout(\'cidev_empty_amount(\'+cartid+\',\'+manufacturerid+\')\', 800);
	}
}

function cidev_empty_amount(cartid, manufacturerid){

	var hidden_productindex_id = \'hidden_productindex_\' + cartid;
	var productindex_id = \'productindex_\' + cartid;
	var amount = $("#" + productindex_id).val();

	if (amount == "" || amount == \'0\'){
		$(\'#\'+productindex_id).css(\'background\',\'#E01B1B\');

		new_amount = "1";
		$("#" + productindex_id).val(new_amount);
		$(\'#\'+hidden_productindex_id).val(new_amount);

		setTimeout(\'cidev_update_product_amount_next(\'+cartid+\',\'+new_amount+\',\'+manufacturerid+\')\', 600);
	}
}

function cidev_update_product_amount_next(cartid, amount, manufacturerid){

        var productindex_id = \'productindex_\' + cartid;
        var current_amount = $("#" + productindex_id).val();
        current_amount = current_amount.replace(/[^0-9]/g, \'\');

	if (current_amount == amount){
	
                        cidev_xmlHttp=cidev_createHttpRequestObject();
                        if (cidev_xmlHttp.readyState==4 || cidev_xmlHttp.readyState==0){

                                var cidev_parameters = \'action=update&cartid=\' + cartid + \'&amount=\' + amount + \'&manufacturerid=\' + manufacturerid;

                                cidev_xmlHttp.onreadystatechange=function(){
                                        if(cidev_xmlHttp.readyState==4){
                                                if(cidev_xmlHttp.status==200){

                                                        cidev_id$("cidev_cart_subtotal").innerHTML=cidev_xmlHttp.responseText;


							var cidev_hidden_display_price_cid = \'cidev_hidden_display_price_\' + cartid;
							if (cidev_id$(cidev_hidden_display_price_cid)){
								var cidev_hidden_display_price_val = $("#" + cidev_hidden_display_price_cid).val();


								var cidev_hidden_set_new_amount_cid = \'cidev_hidden_set_new_amount_\' + cartid;
								if (cidev_id$(cidev_hidden_set_new_amount_cid)){

									$(\'#\'+productindex_id).css(\'background\',\'#E01B1B\');

									new_amount = $("#" + cidev_hidden_set_new_amount_cid).val();
									$("#" + productindex_id).val(new_amount);

									var hidden_productindex_id = \'hidden_productindex_\' + cartid;
									$(\'#\'+hidden_productindex_id).val(new_amount);

									setTimeout(\'cidev_update_product_amount_next(\'+cartid+\',\'+new_amount+\',\'+manufacturerid+\')\', 600);
									return false;
								}

								$(\'#\'+productindex_id).css(\'background\',\'#ffffff\');
				
								var cidev_display_price_cid = \'cidev_display_price_\' + cartid;
								if (cidev_id$(cidev_display_price_cid)){
									cidev_id$(cidev_display_price_cid).innerHTML = cidev_hidden_display_price_val;

									var cidev_hidden_price_on_amount_cid = \'cidev_hidden_price_on_amount_\' + cartid;
									var cidev_hidden_price_on_amount_val = $("#" + cidev_hidden_price_on_amount_cid).val();
									var cidev_price_on_amount_cid = \'cidev_price_on_amount_\' + cartid;
									cidev_id$(cidev_price_on_amount_cid).innerHTML = cidev_hidden_price_on_amount_val;
								}
							}

						        var cidev_hidden_deliv_subt_mid = \'cidev_hidden_deliv_subt_\' + manufacturerid;
							if (cidev_id$(cidev_hidden_deliv_subt_mid)){
        							var cidev_hidden_deliv_subt_mid_amount = $("#" + cidev_hidden_deliv_subt_mid).val();


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
								var warehouse_mid = \'warehouse_\' + manufacturerid;
								var need_add_more_mid = \'need_add_more_\' + manufacturerid;

								var cidev_hidden_need_add_more_mid = \'cidev_hidden_need_add_more_\' + manufacturerid;


								if (cidev_id$(cidev_hidden_need_add_more_mid) && cidev_id$(warehouse_mid) && cidev_id$(need_add_more_mid)){
									var cidev_hidden_need_add_more_mid_val = $("#" + cidev_hidden_need_add_more_mid).val();
									if (cidev_hidden_need_add_more_mid_val == "Y"){
										document.getElementById(warehouse_mid).style.border = "1px solid #CC3333";
										document.getElementById(need_add_more_mid).style.display = "";
									}
									else {
										document.getElementById(warehouse_mid).style.border = "0px";
										document.getElementById(need_add_more_mid).style.display = "none";
									}
								}


								if (cidev_id$(cidev_hidden_allow_to_checkout)){
									var cidev_hidden_allow_to_checkout_val = $("#" + cidev_hidden_allow_to_checkout).val();
									if (cidev_hidden_allow_to_checkout_val == "Y"){

									} 
									else {

									}
								}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

						        	var cidev_shipping_groups_deliv_subt_mid = \'cidev_shipping_groups_deliv_subt_\' + manufacturerid;
								if (cidev_id$(cidev_shipping_groups_deliv_subt_mid)){
									cidev_id$(cidev_shipping_groups_deliv_subt_mid).innerHTML = cidev_hidden_deliv_subt_mid_amount;
								}
							}

                                                }else{
                                                        cidev_Error(\'no_server\', \'Y\');
                                                }
                                        }
                                };

                                cidev_xmlHttp.open(\'POST\',\'cidev_cart.php\',true);
                                cidev_xmlHttp.setRequestHeader(\'Content-type\',\'application/x-www-form-urlencoded\');
                                cidev_xmlHttp.setRequestHeader(\'Content-length\',cidev_parameters.length);
                                cidev_xmlHttp.setRequestHeader(\'Connection\',\'close\');
                                cidev_xmlHttp.send(cidev_parameters);
                        }
                        else {
                                setTimeout(\'cidev_update_product_amount_next(\'+cartid+\',\'+amount+\',\'+manufacturerid+\')\', 600);
                        }
	}
}

'; ?>

//]]>
</script>


<?php if ($this->_tpl_vars['cart'] != ''):  if ($this->_tpl_vars['active_modules']['Gift_Certificates'] != ""):  echo $this->_tpl_vars['lng']['txt_cart_note']; ?>

<?php endif;  endif; ?>
<p />
<?php if ($this->_tpl_vars['active_modules']['Special_Offers']):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Special_Offers/customer/cart_offers.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
<p />
<?php if ($this->_tpl_vars['products'] != ""): ?>
<form action="cart.php" method="post" name="cartform">

<?php $this->assign('a_warehouse', ""); ?>

<?php $_from = $this->_tpl_vars['cart']['shipping_groups']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['shipping_groups_f'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['shipping_groups_f']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
        $this->_foreach['shipping_groups_f']['iteration']++;
?>

<?php if ($this->_tpl_vars['v']['need_add_more'] != "" && $this->_tpl_vars['a_warehouse'] == ""):  $this->assign('a_warehouse', 'warehouse'); ?>
<a name="<?php echo $this->_tpl_vars['a_warehouse']; ?>
"></a>

<?php $this->assign('d_minimum_order_amount_in_us', "$".($this->_tpl_vars['v']['d_minimum_order_amount_in_us']));  $this->assign('lbl_minimum_order_amount_mes', ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_minimum_order_amount_message'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'minimum_order_amount', $this->_tpl_vars['d_minimum_order_amount_in_us']) : smarty_modifier_substitute($_tmp, 'minimum_order_amount', $this->_tpl_vars['d_minimum_order_amount_in_us'])));  endif; ?>


<table width="100%" cellpadding="0" cellspacing="0" <?php if ($this->_tpl_vars['v']['need_add_more'] != ""): ?>id="warehouse_<?php echo $this->_tpl_vars['k']; ?>
" style="border: 1px solid #CC3333; background: #ffffff;"<?php endif; ?>>
<tr>
<td colspan="3" valign="top" class="DialogTitleBox"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" width="1" height="1" alt="" >
</td>
</tr>
<tr>
<td class="DialogTitle" colspan="2" valign="top" style="background-color: #FEF6F3;">

<b><?php echo $this->_tpl_vars['lng']['lbl_items_shipped_from_warehouse']; ?>
 <?php echo $this->_tpl_vars['v']['m_city']; ?>
, <?php echo $this->_tpl_vars['v']['m_state_code']; ?>
, <?php if ($this->_tpl_vars['v']['m_country_code'] == 'US'): ?>USA<?php else:  echo $this->_tpl_vars['v']['m_country'];  endif; ?></b>


</tr>
<tr><td colspan="2"><br />

<?php if ($this->_tpl_vars['v']['count_shipping_rates_for_canada'] == '0' && ( $this->_tpl_vars['userinfo']['s_country'] == 'CA' || $this->_tpl_vars['userinfo']['s_country'] == "" )): ?>
<font class="ErrorMessage">
&nbsp;&nbsp;&nbsp;<?php echo $this->_tpl_vars['lng']['lbl_we_dont_ship_to_Canada_cart_page']; ?>

</font>
<br />
<br />
<?php endif; ?>

</td></tr> 
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
">
<?php if ($this->_tpl_vars['products'][$this->_sections['product']['index']]['is_pimage'] == 'W'): ?>
	<?php $this->assign('imageid', $this->_tpl_vars['products'][$this->_sections['product']['index']]['variantid']); ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "product_thumbnail.tpl", 'smarty_include_vars' => array('productid' => $this->_tpl_vars['imageid'],'image_x' => $this->_tpl_vars['config']['Appearance']['thumbnail_width'],'product' => $this->_tpl_vars['products'][$this->_sections['product']['index']]['product'],'tmbn_url' => $this->_tpl_vars['products'][$this->_sections['product']['index']]['pimage_url'],'type' => $this->_tpl_vars['products'][$this->_sections['product']['index']]['is_pimage'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  else: ?>
	<?php $this->assign('imageid', $this->_tpl_vars['products'][$this->_sections['product']['index']]['productid']); ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "product_thumbnail.tpl", 'smarty_include_vars' => array('productid' => $this->_tpl_vars['products'][$this->_sections['product']['index']]['productid'],'image_x' => $this->_tpl_vars['config']['Appearance']['thumbnail_width'],'product' => $this->_tpl_vars['products'][$this->_sections['product']['index']]['product'],'tmbn_url' => $this->_tpl_vars['products'][$this->_sections['product']['index']]['tmbn_url'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
</a>
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

<table>
<tr>
<td>
<span id="cidev_display_price_<?php echo $this->_tpl_vars['products'][$this->_sections['product']['index']]['cartid']; ?>
">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['price'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> 
</span>
</td>
<td>
x <?php if ($this->_tpl_vars['active_modules']['Egoods'] && $this->_tpl_vars['products'][$this->_sections['product']['index']]['distribution']): ?>1<input type="hidden"<?php else: ?>
</td>
<td>
<input type="number" <?php if ($this->_tpl_vars['main'] == 'fast_lane_checkout'): ?> autocomplete="off" style="background: #ffffff; width: 100px; height: 50px;" id="productindex_<?php echo $this->_tpl_vars['products'][$this->_sections['product']['index']]['cartid']; ?>
" onkeyup="cidev_update_product_amount('<?php echo $this->_tpl_vars['products'][$this->_sections['product']['index']]['cartid']; ?>
', '<?php echo $this->_tpl_vars['products'][$this->_sections['product']['index']]['manufacturerid']; ?>
')"<?php endif; ?>  size="3"<?php endif; ?> name="productindexes[<?php echo $this->_tpl_vars['products'][$this->_sections['product']['index']]['cartid']; ?>
]" value="<?php echo $this->_tpl_vars['products'][$this->_sections['product']['index']]['amount']; ?>
" />
</td>
<td>
 = 
</td>
<td>

<input type="hidden" id="hidden_productindex_<?php echo $this->_tpl_vars['products'][$this->_sections['product']['index']]['cartid']; ?>
" value="<?php echo $this->_tpl_vars['products'][$this->_sections['product']['index']]['amount']; ?>
" />


<font class="ProductPrice">
<?php echo smarty_function_math(array('equation' => "price*amount",'price' => $this->_tpl_vars['price'],'amount' => $this->_tpl_vars['products'][$this->_sections['product']['index']]['amount'],'format' => "%.2f",'assign' => 'unformatted'), $this);?>


<span id="cidev_price_on_amount_<?php echo $this->_tpl_vars['products'][$this->_sections['product']['index']]['cartid']; ?>
">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['unformatted'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</span>


</font>

<font class="MarketPrice"> <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/alter_currency_value.tpl", 'smarty_include_vars' => array('alter_currency_value' => $this->_tpl_vars['unformatted'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></font>

</td>
</tr>
</table>


<br />
<?php $this->assign('cartid', $this->_tpl_vars['products'][$this->_sections['product']['index']]['cartid']); ?>
<?php if ($this->_tpl_vars['mult_amount_warns'] != "" && $this->_tpl_vars['mult_amount_warns'][$this->_tpl_vars['cartid']] != ""): ?>
<font class="ProductDetailsTitleWithoutBold"><?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['txt_warn_increase'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'start', $this->_tpl_vars['mult_amount_warns'][$this->_tpl_vars['cartid']]['old'], 'end', $this->_tpl_vars['mult_amount_warns'][$this->_tpl_vars['cartid']]['new']) : smarty_modifier_substitute($_tmp, 'start', $this->_tpl_vars['mult_amount_warns'][$this->_tpl_vars['cartid']]['old'], 'end', $this->_tpl_vars['mult_amount_warns'][$this->_tpl_vars['cartid']]['new'])); ?>
</font>
<br />
<?php endif; ?>
<?php echo smarty_function_math(array('equation' => "price*amount",'price' => $this->_tpl_vars['products'][$this->_sections['product']['index']]['shipping_freight'],'amount' => $this->_tpl_vars['products'][$this->_sections['product']['index']]['amount'],'assign' => 'charge'), $this);?>


<?php echo smarty_function_math(array('equation' => "subtotal_shipping_charge+charge",'charge' => $this->_tpl_vars['charge'],'subtotal_shipping_charge' => $this->_tpl_vars['subtotal_shipping_charge'],'assign' => 'subtotal_shipping_charge'), $this);?>


<?php if ($this->_tpl_vars['config']['Taxes']['display_taxed_order_totals'] == 'Y' && $this->_tpl_vars['products'][$this->_sections['product']['index']]['taxes']): ?><br /><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/taxed_price.tpl", 'smarty_include_vars' => array('taxes' => $this->_tpl_vars['products'][$this->_sections['product']['index']]['taxes'],'price_in_cart' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  if ($this->_tpl_vars['active_modules']['Special_Offers']):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Special_Offers/customer/cart_free.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  endif; ?>
<br />
<table cellspacing="0" cellpadding="0">
<?php if ($this->_tpl_vars['shippings'][$this->_tpl_vars['k']] == "" && $this->_tpl_vars['login'] != "" && ! ( $this->_tpl_vars['v']['count_shipping_rates_for_canada'] == '0' && ( $this->_tpl_vars['userinfo']['s_country'] == 'CA' || $this->_tpl_vars['userinfo']['s_country'] == "" ) )): ?>
<tr>
	<td class="ButtonsRow" colspan="2">
	<font color="red"><?php echo $this->_tpl_vars['lng']['lbl_no_shipping_for_location']; ?>
</font>
	</td>
</tr>
<?php endif; ?>
<tr>
	<td class="ButtonsRow"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/buttons/button.tpl", 'smarty_include_vars' => array('href' => "cart.php?mode=delete&amp;productindex=".($this->_tpl_vars['products'][$this->_sections['product']['index']]['cartid']),'button_title' => 'Delete item')));
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
<table cellpadding="3" cellspacing="0" width="40%" align="right" border="0">

<tr id="need_add_more_<?php echo $this->_tpl_vars['k']; ?>
" <?php if ($this->_tpl_vars['v']['need_add_more'] != ""):  else: ?>style="display: none;"<?php endif; ?>>
<td colspan="3" style="background: #F79647;">
<?php $this->assign('d_minimum_order_amount_in_us', "$".($this->_tpl_vars['v']['d_minimum_order_amount_in_us']));  echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_minimum_order_amount_message'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'minimum_order_amount', $this->_tpl_vars['d_minimum_order_amount_in_us']) : smarty_modifier_substitute($_tmp, 'minimum_order_amount', $this->_tpl_vars['d_minimum_order_amount_in_us'])); ?>

<?php $this->assign('lbl_minimum_order_amount_mes', ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_minimum_order_amount_message'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'minimum_order_amount', $this->_tpl_vars['d_minimum_order_amount_in_us']) : smarty_modifier_substitute($_tmp, 'minimum_order_amount', $this->_tpl_vars['d_minimum_order_amount_in_us']))); ?>
</td>
</tr>

<tr>
<td nowrap="nowrap" align="right">
<font color="#006600"><b><?php echo $this->_tpl_vars['v']['m_city']; ?>
, <?php echo $this->_tpl_vars['v']['m_state_code']; ?>
, <?php if ($this->_tpl_vars['v']['m_country_code'] == 'US'): ?>USA<?php else:  echo $this->_tpl_vars['v']['m_country'];  endif; ?>&nbsp;warehouse&nbsp;<?php echo $this->_tpl_vars['lng']['lbl_subtotal_sg']; ?>
:</b></font>
</td>
<td><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/null.gif" width="5" height="1" alt="" /><br /></td>
<td width="60" nowrap="nowrap" align="right"><font class="ProductPriceSmall"><span id="cidev_shipping_groups_deliv_subt_<?php echo $this->_tpl_vars['k']; ?>
"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['deliv_subt'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></span></font>&nbsp;</td>
</tr>
</table>
<br />
<br />
<br />
</td>
</tr>



</table>
<br />
<?php endforeach; endif; unset($_from); ?>
<hr size="1" noshade="noshade" />

<?php if ($this->_tpl_vars['active_modules']['Gift_Certificates'] != ""):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Gift_Certificates/gc_cart.tpl", 'smarty_include_vars' => array('giftcerts_data' => $this->_tpl_vars['cart']['giftcerts'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  if ($this->_tpl_vars['main'] == 'fast_lane_checkout'): ?>
<div id="cidev_cart_subtotal">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Fast_Lane_Checkout/cart_subtotal.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>
<?php else:  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/cart_totals.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  echo $this->_tpl_vars['lng']['lbl_your_mer_subtotal']; ?>
<br /><br />
<?php if ($this->_tpl_vars['js_enabled']): ?>


<?php if ($this->_tpl_vars['active_modules']['Fast_Lane_Checkout']):  else: ?>
<table cellpadding="0" cellspacing="0" width="100%">
<tr>
<td>
<table cellspacing="0" cellpadding="0">
<tr>
	<td class="ButtonsRow">
<?php if ($this->_tpl_vars['variant_id_for_point2'] != "" && $this->_tpl_vars['variant_id_for_point2'] == '0'): ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/button.tpl", 'smarty_include_vars' => array('button_title' => $this->_tpl_vars['lng']['lbl_shipping_quote'],'type' => 'input','href' => "javascript: window.open('popup_shipquote.php','popup_shipquote','width=800,height=600,toolbar=no,status=no,scrollbars=yes,menubar=no,location=no,direction=no');",'js_to_href' => 'Y','b' => '1')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
	</td>
	<td class="ButtonsRow">
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Fast_Lane_Checkout/big_button.tpl", 'smarty_include_vars' => array('button_title' => 'Request a quote','bold' => 'N','style' => 'button','href' => "javascript: window.open('popup_requestaquote.php','popup_requestaquote','width=800,height=600,toolbar=no,status=no,scrollbars=yes,menubar=no,location=no,direction=no');",'js_to_href' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        </td>
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
  else:  if ($this->_tpl_vars['warehouse_cart_url'] != ""):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/button.tpl", 'smarty_include_vars' => array('button_title' => $this->_tpl_vars['lng']['lbl_checkout'],'style' => 'button','href' => $this->_tpl_vars['warehouse_cart_url'],'b' => '1','js_onclick_to_href' => "func_set_warehouse_background('".($this->_tpl_vars['lbl_minimum_order_amount_mes'])."');")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  else:  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/button.tpl", 'smarty_include_vars' => array('button_title' => $this->_tpl_vars['lng']['lbl_checkout'],'style' => 'button','href' => "cart.php?mode=checkout",'b' => '1')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  endif; ?>
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
<?php endif; ?>

<div class="ui-grid-a">
  <div class="ui-block-a">
    <?php echo $this->_smarty_vars['capture']['continue_button']; ?>

  </div>
  <div class="ui-block-b checkout-button">
    <?php echo $this->_smarty_vars['capture']['checkout_button']; ?>

  </div>
</div>

<?php else:  echo $this->_tpl_vars['lng']['txt_your_shopping_cart_is_empty']; ?>

<?php endif;  if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "customer/main/cart.tpl"), $this); endif; ?>
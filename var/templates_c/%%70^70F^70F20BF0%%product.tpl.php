<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:05
         compiled from customer/main/product.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'customer/main/product.tpl', 1, false),array('function', 'math', 'customer/main/product.tpl', 133, false),array('modifier', 'lower', 'customer/main/product.tpl', 68, false),array('modifier', 'string_format', 'customer/main/product.tpl', 140, false),array('modifier', 'substitute', 'customer/main/product.tpl', 154, false),array('modifier', 'default', 'customer/main/product.tpl', 201, false),array('modifier', 'replace', 'customer/main/product.tpl', 458, false),array('modifier', 'escape', 'customer/main/product.tpl', 474, false),)), $this); ?>
<?php func_load_lang($this, "customer/main/product.tpl","lbl_list_price,lbl_price,lbl_in_stock,txt_items_available,lbl_no_items_available,lbl_quantity,txt_out_of_stock,txt_product_downloadable,txt_need_min_amount_mult,txt_need_min_amount,lbl_added,lbl_error,txt_pconf_product_is_bundled,lbl_pconf_add_to_configuration,lbl_note,lbl_pconf_slot_out_of_stock_note,txt_add_to_configuration_note,lbl_add_to_cart,lbl_products_also_bought_with_this_product,lbl_related_products,lbl_similar_products,lbl_recently_viewed_products"); ?><?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "customer/main/product.tpl"), $this); endif;  if ($this->_tpl_vars['use_schema_org'] == 'Y'):  if ($this->_tpl_vars['current_storefront'] == '0'):  if ($this->_tpl_vars['product']['clean_url'] != ""): ?>
<meta itemprop="url" content="http://www.artistsupplysource.com/<?php echo $this->_tpl_vars['product']['clean_url']; ?>
/" />
<?php else: ?>
<meta itemprop="url" content="http://www.artistsupplysource.com/product.php?productid=<?php echo $this->_tpl_vars['product']['productid']; ?>
" />
<?php endif;  else:  if ($this->_tpl_vars['product']['clean_url'] != ""): ?>
<meta itemprop="url" content="http://<?php echo $this->_tpl_vars['cidev_store_domain']; ?>
/<?php echo $this->_tpl_vars['product']['clean_url']; ?>
/" />
<?php else: ?>
<meta itemprop="url" content="http://<?php echo $this->_tpl_vars['cidev_store_domain']; ?>
/product.php?productid=<?php echo $this->_tpl_vars['product']['productid']; ?>
" />
<?php endif;  endif;  endif; ?>
<br>

<?php if ($this->_tpl_vars['product']['seo_product_name'] != ""):  $this->assign('producttitle', $this->_tpl_vars['product']['seo_product_name']);  elseif ($this->_tpl_vars['product']['producttitle'] != ""):  $this->assign('producttitle', $this->_tpl_vars['product']['producttitle']);  else:  $this->assign('producttitle', $this->_tpl_vars['product']['product']);  endif; ?>

<?php if ($this->_tpl_vars['product']['new_notify_in_stock_price'] != ""): ?>
	<?php $this->assign('current_price', $this->_tpl_vars['product']['new_notify_in_stock_price']);  else: ?>
	<?php if ($this->_tpl_vars['product']['map_price'] > $this->_tpl_vars['product']['taxed_price']): ?>
		<?php $this->assign('current_price', $this->_tpl_vars['product']['map_price']); ?>
	<?php else: ?>
		<?php $this->assign('current_price', $this->_tpl_vars['product']['taxed_price']); ?>
	<?php endif;  endif; ?>


<?php if ($this->_tpl_vars['active_modules']['Special_Offers']):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Special_Offers/customer/product_offers_short_list.tpl", 'smarty_include_vars' => array('product' => $this->_tpl_vars['product'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "form_validation_js.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  if ($this->_tpl_vars['product']['product_type'] == 'C' && $this->_tpl_vars['active_modules']['Product_Configurator']):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Product_Configurator/pconf_customer_product.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  else:  ob_start(); ?>

<?php if ($this->_tpl_vars['config']['General']['unlimited_products'] == 'N' && ( $this->_tpl_vars['product']['avail'] <= 0 || $this->_tpl_vars['product']['avail'] < $this->_tpl_vars['product']['min_amount'] ) && $this->_tpl_vars['variants'] == '' && $this->_tpl_vars['product_feed_enabled'] == 'Y'): ?>


<form name="notifyform" method="post" action="product.php">
<input type="hidden" name="productid" value="<?php echo $this->_tpl_vars['product']['productid']; ?>
" />
<input type="hidden" id="notify_mode" name="mode" value="" />
<input type="hidden" id="notify_email" name="notify_email" value="" />
</form>
<?php endif; ?>

<form name="orderform" method="post" action="cart.php?mode=add" onsubmit="javascript: return FormValidation();">
<table width="100%" border="0">
<tr>
	<td   height="300" width="300" style="border: 1px dashed #cccccc; text-align: center; vertical-align: middle;">
<?php if ($this->_tpl_vars['active_modules']['Detailed_Product_Images'] != "" && $this->_tpl_vars['config']['Detailed_Product_Images']['det_image_popup'] == 'Y' && $this->_tpl_vars['images'] != '' && $this->_tpl_vars['js_enabled'] == 'Y'):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Detailed_Product_Images/popup_image.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  else:  if ($this->_tpl_vars['active_modules']['Detailed_Product_Images'] != "" && $this->_tpl_vars['images'] != ''): ?>
<a style="font-size: 0px;" href="http://<?php if ($this->_tpl_vars['cidev_store_domain'] != ""):  echo ((is_array($_tmp=$this->_tpl_vars['cidev_store_domain'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp));  else: ?>www.artistsupplysource.com<?php endif; ?>/<?php echo $this->_tpl_vars['canonical_url']; ?>
#dp_images"><?php endif;  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "product_thumbnail.tpl", 'smarty_include_vars' => array('productid' => $this->_tpl_vars['product']['productid'],'image_x' => $this->_tpl_vars['product']['image_x'],'image_y' => $this->_tpl_vars['product']['image_y'],'product' => $this->_tpl_vars['producttitle'],'tmbn_url' => $this->_tpl_vars['product']['tmbn_url'],'id' => 'product_thumbnail','type' => 'P')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  if ($this->_tpl_vars['active_modules']['Detailed_Product_Images'] != "" && $this->_tpl_vars['images'] != ''): ?></a><?php endif;  endif;  if ($this->_tpl_vars['active_modules']['Magnifier'] != "" && $this->_tpl_vars['config']['Magnifier']['magnifier_image_popup'] == 'Y' && $this->_tpl_vars['zoomer_images'] != '' && $this->_tpl_vars['js_enabled'] == 'Y'):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Magnifier/popup_magnifier.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
	</td>
	<td valign="top" width="140" style="padding-left: 20px;">


<?php if ($this->_tpl_vars['product']['map_price'] < $this->_tpl_vars['product']['taxed_price']):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/product_prices.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>


	</td>
	<td valign="top" width="*" style="padding-left: 16px;">

<table width="100%" cellpadding="0" cellspacing="0">

<?php if ($this->_tpl_vars['current_price'] > 0 && $this->_tpl_vars['product']['list_price'] > 0 && $this->_tpl_vars['product']['list_price'] > $this->_tpl_vars['current_price']): ?>
<tr>
<td nowrap="nowrap" class="BlackT" width="30%" valign="top"><?php echo $this->_tpl_vars['lng']['lbl_list_price']; ?>
:</td>
<td><font style=" font-size: 12px; color: #848C84;"><strike><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['product']['list_price'],'plain_text_message' => true)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></strike></font></td>
</tr>
<?php endif; ?>

<?php if ($this->_tpl_vars['active_modules']['Feature_Comparison'] != ""):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Feature_Comparison/product.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  if ($this->_tpl_vars['active_modules']['Subscriptions'] != "" && $this->_tpl_vars['subscription']):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Subscriptions/subscription_info.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  else: ?>
<tr>
<td width="30%" class="ProductPriceConverting" valign="top"><?php echo $this->_tpl_vars['lng']['lbl_price']; ?>
:</td>
<td width="70%" valign="top">
<?php if ($this->_tpl_vars['current_price'] != 0 || $this->_tpl_vars['variant_price_no_empty']): ?>

		<?php if ($this->_tpl_vars['product']['new_notify_in_stock_price'] != "" && $this->_tpl_vars['current_price'] == $this->_tpl_vars['product']['new_notify_in_stock_price']): ?>
		<input type="hidden" name="new_notify_in_stock_price" id="new_notify_in_stock_price" />
	<?php endif; ?>
	
<font class="ProductPriceConverting"><span id="product_price" style="white-space: nowrap;"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['current_price'],'plain_text_message' => true)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></span></font>
<font class="MarketPrice"> <span id="product_alt_price" style="white-space: nowrap;"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/alter_currency_value.tpl", 'smarty_include_vars' => array('alter_currency_value' => $this->_tpl_vars['current_price'],'plain_text_message' => true)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></span></font>
<?php if ($this->_tpl_vars['product']['map_price'] > $this->_tpl_vars['product']['taxed_price']): ?>
<br />
<span class="map_price_help"><?php echo $this->_tpl_vars['config']['Product_Page']['map_bridge_text']; ?>
</span>
<?php endif;  else: ?>
<input type="text" size="7" name="price" />
<?php endif; ?>
</td>
</tr>

<?php if ($this->_tpl_vars['product']['taxes']):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/taxed_price.tpl", 'smarty_include_vars' => array('taxes' => $this->_tpl_vars['product']['taxes'],'product_page_tax' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>


<?php endif; ?>

<?php if ($this->_tpl_vars['current_price'] > 0 && $this->_tpl_vars['product']['list_price'] > 0 && $this->_tpl_vars['product']['list_price'] > $this->_tpl_vars['current_price']):  echo smarty_function_math(array('equation' => "100-(price/lprice)*100",'price' => $this->_tpl_vars['current_price'],'lprice' => $this->_tpl_vars['product']['list_price'],'format' => "%3.5f",'assign' => 'discount'), $this);?>

<?php if ($this->_tpl_vars['discount'] >= 1): ?>
<TR id="save_percent_box"<?php if ($this->_tpl_vars['product']['taxed_price'] >= $this->_tpl_vars['product']['list_price']): ?> style="display: none;"<?php endif; ?>>
<TD nowrap="nowrap">
<font style="font-size: 12px; color: #CC3333;">You save:</font>
</TD>
<TD nowrap="nowrap" style="font-size: 12px; font-weight: normal; color: #CC3333;">
<SPAN id="save_percent"><?php echo ((is_array($_tmp=$this->_tpl_vars['discount'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%3.0f") : smarty_modifier_string_format($_tmp, "%3.0f")); ?>
</SPAN>
</TD>
</TR>
<?php endif;  endif; ?>



<tr><td colspan="2" height="20"></td></tr>

<?php if ($this->_tpl_vars['config']['Appearance']['show_in_stock'] == 'Y' && $this->_tpl_vars['config']['General']['unlimited_products'] != 'Y' && $this->_tpl_vars['product']['distribution'] == "" && $this->_tpl_vars['product']['avail'] <= $this->_tpl_vars['config']['Appearance']['quantity_threshold'] && $this->_tpl_vars['product']['avail'] > 0): ?>
<tr>
        <td width="10%" class="BlackT"><?php echo $this->_tpl_vars['lng']['lbl_in_stock']; ?>
:</td>
        <td nowrap="nowrap" id="product_avail_txt" class="BlackT">
<?php if ($this->_tpl_vars['product']['avail'] > 0):  echo ((is_array($_tmp=$this->_tpl_vars['lng']['txt_items_available'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'items', $this->_tpl_vars['product']['avail']) : smarty_modifier_substitute($_tmp, 'items', $this->_tpl_vars['product']['avail']));  else:  echo $this->_tpl_vars['lng']['lbl_no_items_available'];  endif; ?>
        </td>
</tr>
<?php endif; ?>

<tr><td height="25" width="30%" class="BlackT_new"><?php echo $this->_tpl_vars['lng']['lbl_quantity']; ?>
:</td>
<td style="text-align:left;width:70% !important; font-size: 16px;" width="70%">
<?php if ($this->_tpl_vars['config']['General']['unlimited_products'] == 'N' && ( $this->_tpl_vars['product']['avail'] <= 0 || $this->_tpl_vars['product']['avail'] < $this->_tpl_vars['product']['min_amount'] ) && $this->_tpl_vars['variants'] == ''): ?>
<script type="text/javascript" language="JavaScript 1.2">
<!--
var min_avail = 1;
var avail = 0;
var product_avail = 0;
-->
</script>
<b><?php echo $this->_tpl_vars['lng']['txt_out_of_stock']; ?>
</b>
<?php else: ?>
	<?php if ($this->_tpl_vars['config']['General']['unlimited_products'] == 'Y'): ?>
		<?php $this->assign('mq', $this->_tpl_vars['config']['Appearance']['max_select_quantity']); ?>
	<?php else: ?>
	<?php echo smarty_function_math(array('equation' => "x/y",'x' => $this->_tpl_vars['config']['Appearance']['max_select_quantity'],'y' => $this->_tpl_vars['product']['min_amount'],'assign' => 'tmp'), $this);?>

		<?php $this->assign('minamount', $this->_tpl_vars['product']['min_amount']); ?>
				<?php $this->assign('step', '1'); ?>
	<?php if ($this->_tpl_vars['product']['mult_order_quantity'] == 'Y'): ?>
		<?php $this->assign('step', $this->_tpl_vars['product']['min_amount']); ?>
	<?php else: ?>
	<?php endif; ?>

	<?php echo smarty_function_math(array('equation' => "min(maxquantity*step+minamount, productquantity+1)",'assign' => 'mq','maxquantity' => $this->_tpl_vars['config']['Appearance']['max_select_quantity'],'minamount' => $this->_tpl_vars['minamount'],'productquantity' => $this->_tpl_vars['product']['avail'],'step' => $this->_tpl_vars['step']), $this);?>

<?php endif; ?>

<?php if ($this->_tpl_vars['product']['distribution'] == "" && ! ( $this->_tpl_vars['active_modules']['Subscriptions'] != "" && $this->_tpl_vars['subscription'] )): ?>
	<?php if ($this->_tpl_vars['product']['min_amount'] <= 1): ?>
		<?php $this->assign('start_quantity', 1); ?>
	<?php else: ?>
		<?php $this->assign('start_quantity', $this->_tpl_vars['product']['min_amount']); ?>
	<?php endif; ?>

	<?php if ($this->_tpl_vars['config']['General']['unlimited_products'] == 'Y'): ?>
	<?php echo smarty_function_math(array('equation' => "x+y",'assign' => 'mq','x' => $this->_tpl_vars['mq'],'y' => $this->_tpl_vars['start_quantity']), $this);?>

	<?php endif; ?>
<script type="text/javascript" language="JavaScript 1.2">
<!--
var min_avail = <?php echo ((is_array($_tmp=@$this->_tpl_vars['start_quantity'])) ? $this->_run_mod_handler('default', true, $_tmp, 1) : smarty_modifier_default($_tmp, 1)); ?>
;
var avail = <?php echo ((is_array($_tmp=@$this->_tpl_vars['mq'])) ? $this->_run_mod_handler('default', true, $_tmp, 1) : smarty_modifier_default($_tmp, 1)); ?>
-1;
var product_avail = <?php echo ((is_array($_tmp=@$this->_tpl_vars['product']['avail'])) ? $this->_run_mod_handler('default', true, $_tmp, '0') : smarty_modifier_default($_tmp, '0')); ?>
;


<?php echo '
function func_dec_inc_qty(type_of_action, qty_step){

	var qty_val = $("#product_avail").val();

        if (qty_val == "" || qty_val == 0){
                qty_val = min_avail;
        }

	qty_val = parseInt(qty_val);
	qty_step = parseInt(qty_step);
	
	if (type_of_action == "inc"){
		qty_val += qty_step;
	} 

        if (type_of_action == "dec"){
                qty_val = qty_val - qty_step;
        } 

	if (qty_val > product_avail){
		qty_val = avail;
	}

	if (qty_val < min_avail){
		qty_val = min_avail;
	}

	$("#product_avail").val(qty_val);

	if (qty_val == 1){
		$("#qty-dec").addClass("disabled");
	} else {
		$("#qty-dec").removeClass("disabled");
	}

	check_wholesale(qty_val);
}

function check_min_amount_step(mult_order_quantity, min_amount){
        if (mult_order_quantity == \'Y\' && min_amount > 1){

                var m_order_quantity = mult_order_quantity;
                var m_amount = min_amount;

                var ceil_amount;
                var new_qty_val;
                var qty_val;

                qty_val = $("#product_avail").val();
                ceil_amount = qty_val / m_amount;
                ceil_amount = Math.ceil(ceil_amount);
                new_qty_val = ceil_amount * m_amount;

                if (qty_val != new_qty_val){

                    setTimeout(function() {

                        qty_val = $("#product_avail").val();
                        ceil_amount = qty_val / m_amount;
                        ceil_amount = Math.ceil(ceil_amount);
                        new_qty_val = ceil_amount * min_amount;
                        if (qty_val != new_qty_val){
                                $("#product_avail").val(new_qty_val);
                                check_wholesale(new_qty_val);
                        }
                    }, 2000);

                }
        }
}

'; ?>

-->
</script>





<div class="product_attr quantity clearfix">
<a rel="nofollow" class="oper reduce<?php if (((is_array($_tmp=@$this->_tpl_vars['start_quantity'])) ? $this->_run_mod_handler('default', true, $_tmp, 1) : smarty_modifier_default($_tmp, 1)) == '1'): ?> disabled<?php endif; ?>" href="javascript:void(0);" id="qty-dec" onclick="javascript: func_dec_inc_qty('dec', '<?php echo $this->_tpl_vars['step']; ?>
');"></a>
<input type="text" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['start_quantity'])) ? $this->_run_mod_handler('default', true, $_tmp, 1) : smarty_modifier_default($_tmp, 1)); ?>
" class="quantity" id="product_avail" name="amount" onkeyup="check_wholesale(this.value); check_min_amount_step('<?php echo $this->_tpl_vars['product']['mult_order_quantity']; ?>
', '<?php echo $this->_tpl_vars['product']['min_amount']; ?>
');">
<a rel="nofollow" class="oper add" href="javascript:void(0);" id="qty-inc" onclick="javascript: func_dec_inc_qty('inc', '<?php echo $this->_tpl_vars['step']; ?>
');"></a>
</div>



<?php else: ?>
<script type="text/javascript" language="JavaScript 1.2">
<!--
var min_avail = 1;
var avail = 1;
var product_avail = 1;
-->
</script>
<font class="ProductDetailsTitle">1</font><input type="hidden" name="amount" value="1" /> <?php if ($this->_tpl_vars['product']['distribution'] != ""):  echo $this->_tpl_vars['lng']['txt_product_downloadable'];  endif;  endif;  endif; ?>
</td></tr>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Product_Options/customer_options.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if ($this->_tpl_vars['product']['eta_date_in_future'] == 'Y'): ?>
<tr><td colspan="2">&nbsp;</td></tr>
<tr>
<td>Expected availability:</td>
<td><?php echo $this->_tpl_vars['product']['eta_date_dd_month_yyyy']; ?>
</td>
</tr>
<?php if ($this->_tpl_vars['product']['allow_pre_orders'] != 'Y'): ?>
<tr><td colspan="2">Sorry we don't take pre-orders.</td></tr>
<?php endif;  endif; ?>

<?php if ($this->_tpl_vars['config']['General']['unlimited_products'] == 'N' && ( $this->_tpl_vars['product']['avail'] <= 0 || $this->_tpl_vars['product']['avail'] < $this->_tpl_vars['product']['min_amount'] ) && $this->_tpl_vars['variants'] == '' && $this->_tpl_vars['product_feed_enabled'] == 'Y' && $this->_tpl_vars['notify_when_in_stock'][$this->_tpl_vars['product']['productid']] != 'Y'): ?>

	<?php if ($this->_tpl_vars['product']['eta_date_in_future'] != 'Y'): ?>
	<tr><td colspan="2">&nbsp;</td></tr>
	<?php endif; ?>

<tr id="notify_tr1">
<td colspan="2">
<I><a href="javascript: void(0);" onclick="javascript: $('#notify_tr1').hide(); $('#notify_tr2').show();" >Notify me when it's in stock</a></I>
</td>
</tr>

<tr id="notify_tr2" style="display: none;">
<td>Your email address:</td>
<td>
<input type="text" name="notify_email" value="" />
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/button.tpl", 'smarty_include_vars' => array('button_title' => 'Notify me','style' => 'button','href' => "javascript:if (checkEmailAddress(document.orderform.notify_email, 'Y')) ".($this->_tpl_vars['ldelim'])."document.notifyform.mode.value='notify';document.notifyform.notify_email.value=document.orderform.notify_email.value;document.notifyform.submit()".($this->_tpl_vars['rdelim']))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<tr>
<tr><td colspan="2">&nbsp;</td></tr>
<?php endif; ?>

<?php if ($this->_tpl_vars['product']['min_amount'] > 1): ?>
<tr><td colspan="2">
<font class="ProductDetailsTitleWithoutBold"><?php if ($this->_tpl_vars['product']['mult_order_quantity'] == 'Y'):  echo ((is_array($_tmp=$this->_tpl_vars['lng']['txt_need_min_amount_mult'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'items', $this->_tpl_vars['product']['min_amount']) : smarty_modifier_substitute($_tmp, 'items', $this->_tpl_vars['product']['min_amount']));  else:  echo ((is_array($_tmp=$this->_tpl_vars['lng']['txt_need_min_amount'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'items', $this->_tpl_vars['product']['min_amount']) : smarty_modifier_substitute($_tmp, 'items', $this->_tpl_vars['product']['min_amount']));  endif; ?></font>
</td></tr>
</table>





 
<table width="100%" cellspacing="0" cellpadding="0">
<?php endif; ?>
<tr><td colspan="2">
<input type="hidden" name="mode" value="add" />

<?php if ($this->_tpl_vars['config']['General']['unlimited_products'] == 'Y' || ( $this->_tpl_vars['product']['avail'] > 0 && $this->_tpl_vars['product']['avail'] >= $this->_tpl_vars['product']['min_amount'] )):  if ($this->_tpl_vars['js_enabled']):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/include_js.tpl", 'smarty_include_vars' => array('src' => "ajax_add_to_cart.js")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<script type="text/javascript">
var lbl_added = "<?php echo $this->_tpl_vars['lng']['lbl_added']; ?>
";
var lbl_error = "<?php echo $this->_tpl_vars['lng']['lbl_error']; ?>
";
</script>


<?php if ($this->_tpl_vars['product']['min_amount'] >= 1): ?>
<br />
<?php if ($this->_tpl_vars['product_subtotal_value'] == ""):  echo smarty_function_math(array('equation' => "price*quantity",'price' => $this->_tpl_vars['current_price'],'quantity' => $this->_tpl_vars['product']['min_amount'],'format' => "%3.5f",'assign' => 'product_subtotal_value'), $this);?>

<?php endif; ?>
<div style="font-size: 16px; color: #000000; font-weight: bold;" id="product_subtotal_value">Subtotal: <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['product_subtotal_value'],'plain_text_message' => true)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></div>
<?php endif; ?>


<br />
<?php if ($this->_tpl_vars['product']['forsale'] != 'B'): ?>
<table cellspacing="0" cellpadding="0" border="0" >
<tr>
		<td id="add2cart_<?php echo $this->_tpl_vars['product']['productid']; ?>
" nowrap="nowrap">

<?php if ($this->_tpl_vars['product']['lead_time_message'] != ""):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/buy_now.tpl", 'smarty_include_vars' => array('style' => 'button','href' => "javascript: if ('".($this->_tpl_vars['config']['General']['opt_ajax_cart'])."' == 'Y') if (confirm('".($this->_tpl_vars['product']['lead_time_message'])."')) ajax_add_to_cart(".($this->_tpl_vars['product']['productid']).", ".($this->_tpl_vars['product']['add_date']).", 'product'); if (!('".($this->_tpl_vars['config']['General']['opt_ajax_cart'])."' == 'Y')) document.orderform.submit();",'b' => 1,'class' => 'ajax_button','add_to_cart_btn' => 'big')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  else:  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/buy_now.tpl", 'smarty_include_vars' => array('style' => 'button','href' => "javascript: if ('".($this->_tpl_vars['config']['General']['opt_ajax_cart'])."' == 'Y') ajax_add_to_cart(".($this->_tpl_vars['product']['productid']).", ".($this->_tpl_vars['product']['add_date']).", 'product'); else document.orderform.submit();",'b' => 1,'class' => 'ajax_button','add_to_cart_btn' => 'big')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>



		</td>
</tr>
</table>
<?php else:  echo $this->_tpl_vars['lng']['txt_pconf_product_is_bundled']; ?>

<?php endif;  if ($GLOBALS['_GET']['pconf'] != "" && $this->_tpl_vars['active_modules']['Product_Configurator']): ?>
<br /><br />
<input type="hidden" name="slot" value="<?php echo $GLOBALS['_GET']['slot']; ?>
" />
<input type="hidden" name="addproductid" value="<?php echo $this->_tpl_vars['product']['productid']; ?>
" />
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/button.tpl", 'smarty_include_vars' => array('button_title' => $this->_tpl_vars['lng']['lbl_pconf_add_to_configuration'],'style' => 'button','href' => "javascript:if (FormValidation()) ".($this->_tpl_vars['ldelim'])."document.orderform.productid.value='".($GLOBALS['_GET']['pconf'])."';document.orderform.action='pconf.php';document.orderform.submit()".($this->_tpl_vars['rdelim']))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  if ($this->_tpl_vars['config']['General']['unlimited_products'] != 'Y' && $this->_tpl_vars['product']['pconf_avail'] <= 0): ?>
<br />
<font class="Message"><b><?php echo $this->_tpl_vars['lng']['lbl_note']; ?>
:</b> <?php echo $this->_tpl_vars['lng']['lbl_pconf_slot_out_of_stock_note']; ?>
</font><br />
<?php endif; ?>
<br />
<?php echo $this->_tpl_vars['lng']['txt_add_to_configuration_note']; ?>

<br />
<?php endif;  else:  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "submit_wo_js.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['lng']['lbl_add_to_cart'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  endif;  if ($this->_tpl_vars['active_modules']['Feature_Comparison'] != ""):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Feature_Comparison/product_buttons.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

<?php if ($this->_tpl_vars['config']['Security']['ssl_seal'] != ""): ?>
<br /><?php echo $this->_tpl_vars['config']['Security']['ssl_seal']; ?>

<?php endif; ?>

<?php if ($this->_tpl_vars['variant_id_for_point5'] == '0'): ?>
<br />
<br />
<?php $this->assign('social_buttons_data_services', $this->_tpl_vars['config']['Appearance']['social_buttons_data_services']);  echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['config']['Appearance']['social_buttons_script_code'])) ? $this->_run_mod_handler('replace', true, $_tmp, "[data-services]", ($this->_tpl_vars['social_buttons_data_services'])) : smarty_modifier_replace($_tmp, "[data-services]", ($this->_tpl_vars['social_buttons_data_services']))))) ? $this->_run_mod_handler('replace', true, $_tmp, "[size]", 'big') : smarty_modifier_replace($_tmp, "[size]", 'big')); ?>

<?php endif; ?>

</td>
</tr></table>
</td>

<td>
<?php if ($this->_tpl_vars['variant_id_for_point3'] == '1'):  echo $this->_tpl_vars['config']['Appearance']['product_advantages_code']; ?>

<?php endif; ?>
</td>

</tr>
</table>
<input type="hidden" name="productid" value="<?php echo $this->_tpl_vars['product']['productid']; ?>
" />
<input type="hidden" name="cat" value="<?php echo ((is_array($_tmp=$GLOBALS['_GET']['cat'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" />
<input type="hidden" name="page" value="<?php echo ((is_array($_tmp=$GLOBALS['_GET']['page'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" />
</form>
<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean(); ?>


<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['producttitle'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"','product' => $this->_tpl_vars['product'],'save_label' => 'true','product_sku' => $this->_tpl_vars['product']['productcode'],'product_free_ship' => $this->_tpl_vars['product']['free_ship_text'],'use_h1' => 'Y','lbl_minimum_order_amount_message_product' => $this->_tpl_vars['product']['lbl_minimum_order_amount_message_product'],'d_minimum_order_amount_in_us' => $this->_tpl_vars['product']['d_minimum_order_amount_in_us'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>


        <?php if ($this->_tpl_vars['config']['Appearance']['code_below_thumb']): ?>
                <table width="300">
                <tr>
                        <td align="right">
<div style="margin-top: -56px; margin-left: -39px;">
                                <table cellpadding="0" cellspacing="0">
                                <tr>
                                        <td><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['config']['Appearance']['code_below_thumb'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'prn', ($this->_tpl_vars['product']['product'])) : smarty_modifier_substitute($_tmp, 'prn', ($this->_tpl_vars['product']['product']))))) ? $this->_run_mod_handler('substitute', true, $_tmp, 'url', ($this->_tpl_vars['current_location'])."/product.php?productid=".($this->_tpl_vars['product']['productid'])) : smarty_modifier_substitute($_tmp, 'url', ($this->_tpl_vars['current_location'])."/product.php?productid=".($this->_tpl_vars['product']['productid']))); ?>
</td>
                                </tr>
                                </table>
                        </td>
                </tr>
                </table>
</div>
        <?php endif; ?>

<?php if ($this->_tpl_vars['product']['upc_ean_isbn']): ?>
<br />
<table width="100%" cellpadding="0" cellspacing="0">
<tr>
        <td width="22%" class="BlackT"><?php echo $this->_tpl_vars['product']['upc_ean_isbn']['type']; ?>
:</td>
        <td nowrap="nowrap"><?php if ($this->_tpl_vars['use_schema_org'] == 'Y'): ?><span itemprop="gtin13"><?php endif;  echo $this->_tpl_vars['product']['upc_ean_isbn']['value'];  if ($this->_tpl_vars['use_schema_org'] == 'Y'): ?></span><?php endif; ?></td>
</tr>
</table>
<?php endif; ?>


<?php if ($this->_tpl_vars['product']['cart_manufact_text_displayed'] != ""): ?>
<br />
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/ui_tabs.tpl", 'smarty_include_vars' => array('prefix' => "product-tabs-",'mode' => 'inline','tabs' => $this->_tpl_vars['product_tabs'],'productid' => $this->_tpl_vars['product']['productid'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<br />
<?php endif; ?>

<?php if ($this->_tpl_vars['active_modules']['Magnifier'] != "" && ( $this->_tpl_vars['config']['Magnifier']['magnifier_image_popup'] != 'Y' || $this->_tpl_vars['js_enabled'] != 'Y' )): ?>
<p />
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Magnifier/product_magnifier.tpl", 'smarty_include_vars' => array('productid' => $this->_tpl_vars['product']['productid'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  if ($this->_tpl_vars['config']['Appearance']['send_to_friend_enabled'] == 'Y'): ?>
<p />
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/send_to_friend.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  if ($this->_tpl_vars['active_modules']['Detailed_Product_Images'] != "" && ( $this->_tpl_vars['config']['Detailed_Product_Images']['det_image_popup'] != 'Y' || $this->_tpl_vars['js_enabled'] != 'Y' )): ?>
<p />
<a name="dp_images"></a>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Detailed_Product_Images/product_images.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

<br />

<div id="products_also_bought_with_this_product" style="display: none;"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/ajax_carousel_products.tpl", 'smarty_include_vars' => array('section_name' => 'products_also_bought_with_this_product','section_title' => $this->_tpl_vars['lng']['lbl_products_also_bought_with_this_product'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></div>

<br />

<div id="related_products" style="display: none;"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/ajax_carousel_products.tpl", 'smarty_include_vars' => array('section_name' => 'related_products','section_title' => $this->_tpl_vars['lng']['lbl_related_products'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></div>

<br />

<div id="similar_products" style="display: none;"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/ajax_carousel_products.tpl", 'smarty_include_vars' => array('section_name' => 'similar_products','section_title' => $this->_tpl_vars['lng']['lbl_similar_products'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></div>

<br />

<div id="recently_viewed_products" style="display: none;"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/ajax_carousel_products.tpl", 'smarty_include_vars' => array('section_name' => 'recently_viewed_products','section_title' => $this->_tpl_vars['lng']['lbl_recently_viewed_products'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></div>

<script type="text/javascript">
//<![CDATA[
func_load_ALL_ajax_carousels("products_also_bought_with_this_product,related_products,similar_products,recently_viewed_products", 0);
//]]>
</script>


<?php if ($this->_tpl_vars['active_modules']['Recommended_Products'] != ""):  if ($this->_tpl_vars['recommends']): ?>
<br />
<p />
<?php endif;  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Recommended_Products/recommends.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  if ($this->_tpl_vars['active_modules']['Customer_Reviews'] != ""): ?>
<p />
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Customer_Reviews/vote_reviews.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  if ($this->_tpl_vars['active_modules']['Product_Options'] != ''): ?>
<script type="text/javascript" language="JavaScript 1.2">
<!--
check_options();
-->
</script>
<?php endif;  if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "customer/main/product.tpl"), $this); endif; ?>
<?php /* Smarty version 2.6.12, created on 2011-10-11 05:37:52
         compiled from customer/main/product.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'substitute', 'customer/main/product.tpl', 31, false),array('modifier', 'formatprice', 'customer/main/product.tpl', 93, false),array('modifier', 'default', 'customer/main/product.tpl', 153, false),array('modifier', 'escape', 'customer/main/product.tpl', 240, false),array('function', 'math', 'customer/main/product.tpl', 134, false),)), $this); ?>
<?php func_load_lang($this, "customer/main/product.tpl","lbl_in_stock,txt_items_available,lbl_no_items_available,lbl_shipping_dimensions,lbl_weight,lbl_price,lbl_quantity,txt_need_min_amount,txt_out_of_stock,txt_product_downloadable,lbl_added,lbl_error,txt_pconf_product_is_bundled,lbl_pconf_add_to_configuration,lbl_note,lbl_pconf_slot_out_of_stock_note,txt_add_to_configuration_note,lbl_add_to_cart"); ?><br>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/include_js.tpl", 'smarty_include_vars' => array('src' => "main/popup_image.js")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php if ($this->_tpl_vars['active_modules']['Special_Offers']): ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Special_Offers/customer/product_offers_short_list.tpl", 'smarty_include_vars' => array('product' => $this->_tpl_vars['product'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "form_validation_js.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php if ($this->_tpl_vars['product']['product_type'] == 'C' && $this->_tpl_vars['active_modules']['Product_Configurator']): ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Product_Configurator/pconf_customer_product.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php else: ?>
<?php ob_start(); ?>
<form name="orderform" method="post" action="cart.php?mode=add" onsubmit="javascript: return FormValidation();">
<table width="100%">
<tr>
	<td class="PImgBox" rowspan="2">

<?php if ($this->_tpl_vars['active_modules']['Detailed_Product_Images'] != "" && $this->_tpl_vars['config']['Detailed_Product_Images']['det_image_popup'] == 'Y' && $this->_tpl_vars['images'] != '' && $this->_tpl_vars['js_enabled'] == 'Y'): ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Detailed_Product_Images/popup_image.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php else: ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "product_thumbnail.tpl", 'smarty_include_vars' => array('productid' => $this->_tpl_vars['product']['productid'],'image_x' => $this->_tpl_vars['product']['image_x'],'image_y' => $this->_tpl_vars['product']['image_y'],'product' => $this->_tpl_vars['product']['product'],'tmbn_url' => $this->_tpl_vars['product']['tmbn_url'],'id' => 'product_thumbnail','type' => 'P')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>
<?php if ($this->_tpl_vars['active_modules']['Magnifier'] != "" && $this->_tpl_vars['config']['Magnifier']['magnifier_image_popup'] == 'Y' && $this->_tpl_vars['zoomer_images'] != '' && $this->_tpl_vars['js_enabled'] == 'Y'): ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Magnifier/popup_magnifier.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>
	<?php if ($this->_tpl_vars['config']['Appearance']['code_below_thumb']): ?>
		<table width="100%">
		<tr>
			<td align="center">
				<table cellpadding="0" cellspacing="0">
				<tr>
					<td><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['config']['Appearance']['code_below_thumb'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'prn', ($this->_tpl_vars['product']['product'])) : smarty_modifier_substitute($_tmp, 'prn', ($this->_tpl_vars['product']['product']))))) ? $this->_run_mod_handler('substitute', true, $_tmp, 'url', ($this->_tpl_vars['current_location'])."/product.php?productid=".($this->_tpl_vars['product']['productid'])) : smarty_modifier_substitute($_tmp, 'url', ($this->_tpl_vars['current_location'])."/product.php?productid=".($this->_tpl_vars['product']['productid']))); ?>
</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
	<?php endif; ?>
	</td>
	<td valign="top" width="100%">

<table width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td><span style="font-size: 13px; color: #000000;"><?php if ($this->_tpl_vars['product']['fulldescr'] != ""):  echo $this->_tpl_vars['product']['fulldescr'];  else:  echo $this->_tpl_vars['product']['descr'];  endif; ?></span></td>
</tr>
</table>

<p />
<table width="100%" cellpadding="0" cellspacing="0">
<?php if ($this->_tpl_vars['product']['upc_ean_isbn']): ?>
<tr>
	<td width="30%" class="BlackT"><?php echo $this->_tpl_vars['product']['upc_ean_isbn']['type']; ?>
:</td>
	<td nowrap="nowrap"><?php echo $this->_tpl_vars['product']['upc_ean_isbn']['value']; ?>
</td>
</tr>
<?php endif; ?>
<?php if ($this->_tpl_vars['config']['Appearance']['show_in_stock'] == 'Y' && $this->_tpl_vars['config']['General']['unlimited_products'] != 'Y' && $this->_tpl_vars['product']['distribution'] == "" && $this->_tpl_vars['product']['avail'] <= $this->_tpl_vars['config']['Appearance']['quantity_threshold']): ?>
<tr>
	<td width="30%" class="BlackT"><?php echo $this->_tpl_vars['lng']['lbl_in_stock']; ?>
:</td>
	<td nowrap="nowrap" id="product_avail_txt" class="BlackT">
<?php if ($this->_tpl_vars['product']['avail'] > 0):  echo ((is_array($_tmp=$this->_tpl_vars['lng']['txt_items_available'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'items', $this->_tpl_vars['product']['avail']) : smarty_modifier_substitute($_tmp, 'items', $this->_tpl_vars['product']['avail']));  else:  echo $this->_tpl_vars['lng']['lbl_no_items_available'];  endif; ?>
	</td>
</tr>
<?php endif; ?>
<?php if ($this->_tpl_vars['show_dimensions']): ?>
<tr>
	<td width="30%"><?php echo $this->_tpl_vars['lng']['lbl_shipping_dimensions']; ?>
:</td>
	<td nowrap="nowrap"><span id="product_weight"><?php echo $this->_tpl_vars['product']['dim_x']; ?>
" x <?php echo $this->_tpl_vars['product']['dim_y']; ?>
" x <?php echo $this->_tpl_vars['product']['dim_z']; ?>
"</span></td>
</tr>
<?php endif; ?>
<?php if ($this->_tpl_vars['product']['weight'] != "0.00" || $this->_tpl_vars['variants'] != ''): ?>
<tr id="product_weight_box">
	<td width="30%"><?php echo $this->_tpl_vars['lng']['lbl_weight']; ?>
:</td>
	<td nowrap="nowrap"><span id="product_weight"><?php echo ((is_array($_tmp=$this->_tpl_vars['product']['weight'])) ? $this->_run_mod_handler('formatprice', true, $_tmp) : smarty_modifier_formatprice($_tmp)); ?>
</span> <?php echo $this->_tpl_vars['config']['General']['weight_symbol']; ?>
</td>
</tr>
<?php endif; ?>
<?php if ($this->_tpl_vars['active_modules']['Extra_Fields'] != ""): ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Extra_Fields/product.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>
<?php if ($this->_tpl_vars['active_modules']['Feature_Comparison'] != ""): ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Feature_Comparison/product.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>
<?php if ($this->_tpl_vars['active_modules']['Subscriptions'] != "" && $this->_tpl_vars['subscription']): ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Subscriptions/subscription_info.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php else: ?>
<tr><td class="ProductPriceConverting" valign="top"><?php echo $this->_tpl_vars['lng']['lbl_price']; ?>
:</td>
<td valign="top">
<?php if ($this->_tpl_vars['product']['taxed_price'] != 0 || $this->_tpl_vars['variant_price_no_empty']): ?>
<font class="ProductDetailsTitle"><span id="product_price" style="white-space: nowrap;"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['product']['taxed_price'],'plain_text_message' => true)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></span></font><font class="MarketPrice"> <span id="product_alt_price" style="white-space: nowrap;"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/alter_currency_value.tpl", 'smarty_include_vars' => array('alter_currency_value' => $this->_tpl_vars['product']['taxed_price'],'plain_text_message' => true)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></span></font>
<?php if ($this->_tpl_vars['product']['taxes']): ?><br /><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/taxed_price.tpl", 'smarty_include_vars' => array('taxes' => $this->_tpl_vars['product']['taxes'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
<?php else: ?>
<input type="text" size="7" name="price" />
<?php endif; ?>
</td>
</tr>
<?php endif; ?>
<?php if ($this->_tpl_vars['active_modules']['Product_Options'] != ""): ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Product_Options/customer_options.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>
<tr><td height="25" width="30%" class="BlackT"><?php echo $this->_tpl_vars['lng']['lbl_quantity']; ?>
:<?php if ($this->_tpl_vars['product']['min_amount'] > 1): ?><br /><font class="ProductDetailsTitle"><?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['txt_need_min_amount'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'items', $this->_tpl_vars['product']['min_amount']) : smarty_modifier_substitute($_tmp, 'items', $this->_tpl_vars['product']['min_amount'])); ?>
</font><?php endif; ?></td>
<td style="text-align:left;width:70% !important;" width="70%">
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

<?php if ($this->_tpl_vars['tmp'] < 2): ?>
<?php $this->assign('minamount', $this->_tpl_vars['product']['min_amount']); ?>
<?php else: ?>
<?php $this->assign('minamount', 1); ?>
<?php endif; ?>
<?php echo smarty_function_math(array('equation' => "min(maxquantity+minamount, productquantity+1)",'assign' => 'mq','maxquantity' => $this->_tpl_vars['config']['Appearance']['max_select_quantity'],'minamount' => $this->_tpl_vars['minamount'],'productquantity' => $this->_tpl_vars['product']['avail']), $this);?>

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
-->
</script>
<select id="product_avail" name="amount"<?php if ($this->_tpl_vars['active_modules']['Product_Options'] != '' && $this->_tpl_vars['product_options'] != ''): ?> onchange="check_wholesale(this.value);"<?php endif; ?>>
<?php unset($this->_sections['quantity']);
$this->_sections['quantity']['name'] = 'quantity';
$this->_sections['quantity']['loop'] = is_array($_loop=$this->_tpl_vars['mq']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['quantity']['start'] = (int)$this->_tpl_vars['start_quantity'];
$this->_sections['quantity']['show'] = true;
$this->_sections['quantity']['max'] = $this->_sections['quantity']['loop'];
$this->_sections['quantity']['step'] = 1;
if ($this->_sections['quantity']['start'] < 0)
    $this->_sections['quantity']['start'] = max($this->_sections['quantity']['step'] > 0 ? 0 : -1, $this->_sections['quantity']['loop'] + $this->_sections['quantity']['start']);
else
    $this->_sections['quantity']['start'] = min($this->_sections['quantity']['start'], $this->_sections['quantity']['step'] > 0 ? $this->_sections['quantity']['loop'] : $this->_sections['quantity']['loop']-1);
if ($this->_sections['quantity']['show']) {
    $this->_sections['quantity']['total'] = min(ceil(($this->_sections['quantity']['step'] > 0 ? $this->_sections['quantity']['loop'] - $this->_sections['quantity']['start'] : $this->_sections['quantity']['start']+1)/abs($this->_sections['quantity']['step'])), $this->_sections['quantity']['max']);
    if ($this->_sections['quantity']['total'] == 0)
        $this->_sections['quantity']['show'] = false;
} else
    $this->_sections['quantity']['total'] = 0;
if ($this->_sections['quantity']['show']):

            for ($this->_sections['quantity']['index'] = $this->_sections['quantity']['start'], $this->_sections['quantity']['iteration'] = 1;
                 $this->_sections['quantity']['iteration'] <= $this->_sections['quantity']['total'];
                 $this->_sections['quantity']['index'] += $this->_sections['quantity']['step'], $this->_sections['quantity']['iteration']++):
$this->_sections['quantity']['rownum'] = $this->_sections['quantity']['iteration'];
$this->_sections['quantity']['index_prev'] = $this->_sections['quantity']['index'] - $this->_sections['quantity']['step'];
$this->_sections['quantity']['index_next'] = $this->_sections['quantity']['index'] + $this->_sections['quantity']['step'];
$this->_sections['quantity']['first']      = ($this->_sections['quantity']['iteration'] == 1);
$this->_sections['quantity']['last']       = ($this->_sections['quantity']['iteration'] == $this->_sections['quantity']['total']);
?>
<option value="<?php echo $this->_sections['quantity']['index']; ?>
" <?php if ($GLOBALS['HTTP_GET_VARS']['quantity'] == $this->_sections['quantity']['index']): ?>selected<?php endif; ?>><?php echo $this->_sections['quantity']['index']; ?>
</option>
<?php endfor; endif; ?>
</select>
<?php else: ?>
<script type="text/javascript" language="JavaScript 1.2">
<!--
var min_avail = 1;
var avail = 1;
var product_avail = 1;
-->
</script>
<font class="ProductDetailsTitle">1</font><input type="hidden" name="amount" value="1" /> <?php if ($this->_tpl_vars['product']['distribution'] != ""):  echo $this->_tpl_vars['lng']['txt_product_downloadable'];  endif; ?>
<?php endif; ?>
<?php endif; ?>
</td></tr>
<tr><td colspan="2">
<input type="hidden" name="mode" value="add" />
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/product_prices.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php if ($this->_tpl_vars['product']['shipping_freight'] > 0): ?>
<?php else: ?>
<?php endif; ?>
<br /><?php echo $this->_tpl_vars['product']['cart_manufact_text_displayed']; ?>
<br /><br />
<?php if ($this->_tpl_vars['config']['General']['unlimited_products'] == 'Y' || ( $this->_tpl_vars['product']['avail'] > 0 && $this->_tpl_vars['product']['avail'] >= $this->_tpl_vars['product']['min_amount'] )): ?>
<?php if ($this->_tpl_vars['js_enabled']): ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
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
<br />
<?php if ($this->_tpl_vars['product']['forsale'] != 'B'): ?>
<table cellspacing="0" cellpadding="0">
<tr>
		<td id="add2cart_<?php echo $this->_tpl_vars['product']['productid']; ?>
"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/buy_now.tpl", 'smarty_include_vars' => array('style' => 'button','href' => "javascript: if ('".($this->_tpl_vars['config']['General']['opt_ajax_cart'])."' == 'Y') ajax_add_to_cart(".($this->_tpl_vars['product']['productid']).", ".($this->_tpl_vars['product']['add_date']).", 'product'); else document.orderform.submit();",'b' => 1,'class' => 'ajax_button')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		</td>
	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
	<td>
<?php if (( $this->_tpl_vars['login'] != "" || $this->_tpl_vars['config']['Wishlist']['add2wl_unlogged_user'] == 'Y' ) && $this->_tpl_vars['active_modules']['Wishlist'] != ""): ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/add2wl.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>
	</td>
</tr>
</table>
<?php else: ?>
<?php echo $this->_tpl_vars['lng']['txt_pconf_product_is_bundled']; ?>

<?php endif; ?>
<?php if ($GLOBALS['HTTP_GET_VARS']['pconf'] != "" && $this->_tpl_vars['active_modules']['Product_Configurator']): ?>
<br /><br />
<input type="hidden" name="slot" value="<?php echo $GLOBALS['HTTP_GET_VARS']['slot']; ?>
" />
<input type="hidden" name="addproductid" value="<?php echo $this->_tpl_vars['product']['productid']; ?>
" />
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/button.tpl", 'smarty_include_vars' => array('button_title' => $this->_tpl_vars['lng']['lbl_pconf_add_to_configuration'],'style' => 'button','href' => "javascript:if (FormValidation()) ".($this->_tpl_vars['ldelim'])."document.orderform.productid.value='".($GLOBALS['HTTP_GET_VARS']['pconf'])."';document.orderform.action='pconf.php';document.orderform.submit()".($this->_tpl_vars['rdelim']))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php if ($this->_tpl_vars['config']['General']['unlimited_products'] != 'Y' && $this->_tpl_vars['product']['pconf_avail'] <= 0): ?>
<br />
<font class="Message"><b><?php echo $this->_tpl_vars['lng']['lbl_note']; ?>
:</b> <?php echo $this->_tpl_vars['lng']['lbl_pconf_slot_out_of_stock_note']; ?>
</font><br />
<?php endif; ?>
<br />
<?php echo $this->_tpl_vars['lng']['txt_add_to_configuration_note']; ?>

<br />
<?php endif; ?>
<?php else: ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "submit_wo_js.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['lng']['lbl_add_to_cart'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>
<?php endif; ?>
<?php if ($this->_tpl_vars['active_modules']['Feature_Comparison'] != ""): ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Feature_Comparison/product_buttons.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>
<br />
</td>
</tr></table>
</td>
</tr>
</table>
<input type="hidden" name="productid" value="<?php echo $this->_tpl_vars['product']['productid']; ?>
" />
<input type="hidden" name="cat" value="<?php echo ((is_array($_tmp=$GLOBALS['HTTP_GET_VARS']['cat'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" />
<input type="hidden" name="page" value="<?php echo ((is_array($_tmp=$GLOBALS['HTTP_GET_VARS']['page'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" />
</form>
<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean(); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['product']['producttitle'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"','product' => $this->_tpl_vars['product'],'save_label' => 'true','product_sku' => $this->_tpl_vars['product']['productcode'],'product_free_ship' => $this->_tpl_vars['product']['free_ship_text'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>
<?php if ($this->_tpl_vars['active_modules']['Magnifier'] != "" && ( $this->_tpl_vars['config']['Magnifier']['magnifier_image_popup'] != 'Y' || $this->_tpl_vars['js_enabled'] != 'Y' )): ?>
<p />
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Magnifier/product_magnifier.tpl", 'smarty_include_vars' => array('productid' => $this->_tpl_vars['product']['productid'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>
<?php if ($this->_tpl_vars['config']['Appearance']['send_to_friend_enabled'] == 'Y'): ?>
<p />
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/send_to_friend.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>
<?php if ($this->_tpl_vars['active_modules']['Detailed_Product_Images'] != "" && ( $this->_tpl_vars['config']['Detailed_Product_Images']['det_image_popup'] != 'Y' || $this->_tpl_vars['js_enabled'] != 'Y' )): ?>
<p />
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Detailed_Product_Images/product_images.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>
<?php if ($this->_tpl_vars['active_modules']['Upselling_Products'] != ""): ?>
<?php if ($this->_tpl_vars['product_links']): ?>
<p />
<?php endif; ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Upselling_Products/related_products.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>
<br />
<p />
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/similar_products.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php if ($this->_tpl_vars['active_modules']['Recommended_Products'] != ""): ?>
<?php if ($this->_tpl_vars['recommends']): ?>
<br />
<p />
<?php endif; ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Recommended_Products/recommends.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>
<?php if ($this->_tpl_vars['active_modules']['Customer_Reviews'] != ""): ?>
<p />
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Customer_Reviews/vote_reviews.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>
<?php if ($this->_tpl_vars['active_modules']['Product_Options'] != '' && $this->_tpl_vars['product_options'] != ''): ?>
<script type="text/javascript" language="JavaScript 1.2">
<!--
check_options();
-->
</script>
<?php endif; ?>
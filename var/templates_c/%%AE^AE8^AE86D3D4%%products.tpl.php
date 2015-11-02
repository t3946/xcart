<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:05
         compiled from customer/main/products.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'customer/main/products.tpl', 1, false),array('function', 'math', 'customer/main/products.tpl', 96, false),array('modifier', 'truncate', 'customer/main/products.tpl', 81, false),)), $this); ?>
<?php func_load_lang($this, "customer/main/products.tpl","lbl_elasticsearch_correct_suggestions_label,lbl_see_details,lbl_sku,lbl_market_price,lbl_our_price,lbl_save_price,lbl_enter_your_price,lbl_quantity,txt_out_of_stock,lbl_nothing_found_home_page,txt_no_products_found"); ?><?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "customer/main/products.tpl"), $this); endif; ?>
        	<?php if ($this->_tpl_vars['e_search_data']['substring'] != ""): ?>
		<?php echo $this->_tpl_vars['e_search_data']['total']; ?>
 <?php if ($this->_tpl_vars['e_search_data']['total'] == '1'): ?>product<?php else: ?>products<?php endif; ?> found for "<?php if ($this->_tpl_vars['e_search_data']['orig_substring'] != ""):  echo $this->_tpl_vars['e_search_data']['orig_substring'];  else:  echo $this->_tpl_vars['e_search_data']['substring'];  endif; ?>"
	<?php endif; ?>

        <?php if ($this->_tpl_vars['suggests_arr'] != ""): ?>
		<br />
		<br />
                <span style="font-size: 14px; font-weight: bold;"><?php echo $this->_tpl_vars['lng']['lbl_elasticsearch_correct_suggestions_label']; ?>
</span>

                <?php $_from = $this->_tpl_vars['suggests_arr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k_s'] => $this->_tpl_vars['v_s']):
?>
                        <br /><a href="/keyword/<?php echo $this->_tpl_vars['v_s']['clean_suggest']; ?>
/"><?php echo $this->_tpl_vars['v_s']['twotabsearchtextbox']; ?>
</a>
                <?php endforeach; endif; unset($_from); ?>

		<br />
		<br />
        <?php endif; ?>
        

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "check_email_script.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>


<?php if ($this->_tpl_vars['active_modules']['Feature_Comparison'] != '' && $this->_tpl_vars['products'] && $this->_tpl_vars['printable'] != 'Y' && $this->_tpl_vars['products_has_fclasses']):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Feature_Comparison/compare_selected_button.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Feature_Comparison/products_check_js.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  if ($this->_tpl_vars['usertype'] == 'C' && $this->_tpl_vars['config']['Appearance']['products_per_row'] != "" && $this->_tpl_vars['config']['Appearance']['products_per_row'] > 0 && $this->_tpl_vars['config']['Appearance']['products_per_row'] < 4 && ( $this->_tpl_vars['featured'] == 'Y' || $this->_tpl_vars['config']['Appearance']['featured_only_multicolumn'] == 'N' )): ?>

        <?php if ($this->_tpl_vars['featured'] == 'Y'): ?>
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/products_t_new.tpl", 'smarty_include_vars' => array('products' => $this->_tpl_vars['products'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php else: ?>
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/products_t.tpl", 'smarty_include_vars' => array('products' => $this->_tpl_vars['products'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php endif; ?>

<?php else:  if ($this->_tpl_vars['products']):  unset($this->_sections['product']);
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
?>

<?php if ($this->_tpl_vars['products'][$this->_sections['product']['index']]['new_notify_in_stock_price'] != ""): ?>
	<?php $this->assign('current_price', $this->_tpl_vars['products'][$this->_sections['product']['index']]['new_notify_in_stock_price']);  else: ?>
	<?php if ($this->_tpl_vars['products'][$this->_sections['product']['index']]['map_price'] > $this->_tpl_vars['products'][$this->_sections['product']['index']]['taxed_price']): ?>
		<?php $this->assign('current_price', $this->_tpl_vars['products'][$this->_sections['product']['index']]['map_price']); ?>
	<?php else: ?>
		<?php $this->assign('current_price', $this->_tpl_vars['products'][$this->_sections['product']['index']]['taxed_price']); ?>
	<?php endif;  endif; ?>

<?php $this->assign('discount', 0); ?>
<table width="100%">
<tr>
<td class="PListImgBox">
<div class="PListImgBox">
<a href="<?php if ($this->_tpl_vars['search_all_website'] == 'Y'):  if ($this->_tpl_vars['products'][$this->_sections['product']['index']]['clean_url'] != ""):  echo $this->_tpl_vars['products'][$this->_sections['product']['index']]['clean_url'];  else: ?>http://<?php echo $this->_tpl_vars['products'][$this->_sections['product']['index']]['domain']; ?>
/product.php?productid=<?php echo $this->_tpl_vars['products'][$this->_sections['product']['index']]['productid'];  endif;  else: ?>/product.php?productid=<?php echo $this->_tpl_vars['products'][$this->_sections['product']['index']]['productid'];  endif; ?>"  <?php if ($this->_tpl_vars['search_all_website'] == 'Y'): ?>target="_blank"<?php endif; ?>><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "product_thumbnail.tpl", 'smarty_include_vars' => array('productid' => $this->_tpl_vars['products'][$this->_sections['product']['index']]['productid'],'image_x' => $this->_tpl_vars['config']['Appearance']['thumbnail_width'],'product' => $this->_tpl_vars['products'][$this->_sections['product']['index']]['product'],'tmbn_url' => $this->_tpl_vars['products'][$this->_sections['product']['index']]['tmbn_url'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></a>
<?php if ($this->_tpl_vars['active_modules']['Special_Offers'] != "" && $this->_tpl_vars['products'][$this->_sections['product']['index']]['have_offers']):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Special_Offers/customer/product_offer_thumb.tpl", 'smarty_include_vars' => array('product' => $this->_tpl_vars['products'][$this->_sections['product']['index']])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
</div>
<a href="<?php if ($this->_tpl_vars['search_all_website'] == 'Y'):  if ($this->_tpl_vars['products'][$this->_sections['product']['index']]['clean_url'] != ""):  echo $this->_tpl_vars['products'][$this->_sections['product']['index']]['clean_url'];  else: ?>http://<?php echo $this->_tpl_vars['products'][$this->_sections['product']['index']]['domain']; ?>
/product.php?productid=<?php echo $this->_tpl_vars['products'][$this->_sections['product']['index']]['productid'];  endif;  else: ?>/product.php?productid=<?php echo $this->_tpl_vars['products'][$this->_sections['product']['index']]['productid'];  endif; ?>" class="SeeDetails" <?php if ($this->_tpl_vars['search_all_website'] == 'Y'): ?>target="_blank"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_see_details']; ?>
</a>
<?php if ($this->_tpl_vars['active_modules']['Feature_Comparison'] != '' && $this->_tpl_vars['products'][$this->_sections['product']['index']]['fclassid'] > 0 && $this->_tpl_vars['printable'] != 'Y'): ?>
<br />
<br />
<div align="center">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Feature_Comparison/compare_checkbox.tpl", 'smarty_include_vars' => array('id' => $this->_tpl_vars['products'][$this->_sections['product']['index']]['productid'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>
<?php endif; ?>
</td>
<td valign="top">
<a href="<?php if ($this->_tpl_vars['search_all_website'] == 'Y'):  if ($this->_tpl_vars['products'][$this->_sections['product']['index']]['clean_url'] != ""):  echo $this->_tpl_vars['products'][$this->_sections['product']['index']]['clean_url'];  else: ?>http://<?php echo $this->_tpl_vars['products'][$this->_sections['product']['index']]['domain']; ?>
/product.php?productid=<?php echo $this->_tpl_vars['products'][$this->_sections['product']['index']]['productid'];  endif;  else: ?>/product.php?productid=<?php echo $this->_tpl_vars['products'][$this->_sections['product']['index']]['productid'];  endif; ?>" <?php if ($this->_tpl_vars['search_all_website'] == 'Y'): ?>target="_blank"<?php endif; ?>><font class="ProductTitle"><?php echo $this->_tpl_vars['products'][$this->_sections['product']['index']]['product']; ?>
</font></a>
<?php if ($this->_tpl_vars['config']['Appearance']['display_productcode_in_list'] == 'Y' && $this->_tpl_vars['products'][$this->_sections['product']['index']]['productcode'] != ""): ?>
<br />
<font color="#006600" size=2><?php echo $this->_tpl_vars['lng']['lbl_sku']; ?>
: <?php echo $this->_tpl_vars['products'][$this->_sections['product']['index']]['productcode']; ?>
</font>
<?php endif; ?>
<font size="2">
<br />
<br />
<div style="max-height: 44px; overflow: hidden; line-height: 14px">
	<span class="SPItems-description"><?php echo ((is_array($_tmp=$this->_tpl_vars['products'][$this->_sections['product']['index']]['descr'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 225, "...", true) : smarty_modifier_truncate($_tmp, 225, "...", true)); ?>
</span>
</div>
</font>
<br>
<?php if ($this->_tpl_vars['products'][$this->_sections['product']['index']]['product_type'] == 'C'):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/details.tpl", 'smarty_include_vars' => array('href' => "/product.php?productid=".($this->_tpl_vars['products'][$this->_sections['product']['index']]['productid']))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  else:  if ($this->_tpl_vars['active_modules']['Subscriptions'] != "" && ( $this->_tpl_vars['products'][$this->_sections['product']['index']]['catalogprice'] > 0 || $this->_tpl_vars['products'][$this->_sections['product']['index']]['sub_priceplan'] > 0 )):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Subscriptions/subscription_info_inlist.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  else:  if ($this->_tpl_vars['config']['General']['unlimited_products'] != 'Y' && ( $this->_tpl_vars['products'][$this->_sections['product']['index']]['avail'] <= 0 || $this->_tpl_vars['products'][$this->_sections['product']['index']]['avail'] < $this->_tpl_vars['products'][$this->_sections['product']['index']]['min_amount'] ) && $this->_tpl_vars['products'][$this->_sections['product']['index']]['variantid']): ?>
&nbsp;
<?php elseif ($this->_tpl_vars['current_price'] != 0):  if ($this->_tpl_vars['products'][$this->_sections['product']['index']]['list_price'] > 0 && $this->_tpl_vars['current_price'] < $this->_tpl_vars['products'][$this->_sections['product']['index']]['list_price']):  echo smarty_function_math(array('equation' => "100-(price/lprice)*100",'price' => $this->_tpl_vars['current_price'],'lprice' => $this->_tpl_vars['products'][$this->_sections['product']['index']]['list_price'],'format' => "%3.0f",'assign' => 'discount'), $this);?>

<?php if ($this->_tpl_vars['discount'] > 0): ?>
<font class="MarketPrice"><?php echo $this->_tpl_vars['lng']['lbl_market_price']; ?>
:
<s><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['products'][$this->_sections['product']['index']]['list_price'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></s>
</font><br />
<?php endif;  endif;  if ($this->_tpl_vars['active_modules']['Special_Offers'] != "" && $this->_tpl_vars['products'][$this->_sections['product']['index']]['use_special_price'] != ""): ?>
<s>
<?php endif; ?>
<font class="ProductPrice"><?php echo $this->_tpl_vars['lng']['lbl_our_price']; ?>
: <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['current_price'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></font><font class="MarketPrice"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/alter_currency_value.tpl", 'smarty_include_vars' => array('alter_currency_value' => $this->_tpl_vars['current_price'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></font><?php if ($this->_tpl_vars['discount'] > 0):  if ($this->_tpl_vars['config']['General']['alter_currency_symbol'] != ""): ?>,<?php endif; ?><font class="ProductPrice">, <?php echo $this->_tpl_vars['lng']['lbl_save_price']; ?>
 <?php echo $this->_tpl_vars['discount']; ?>
%</font><?php endif;  if ($this->_tpl_vars['products'][$this->_sections['product']['index']]['map_price'] > $this->_tpl_vars['products'][$this->_sections['product']['index']]['taxed_price']): ?>
<br />
<span class="map_price_help"><?php echo $this->_tpl_vars['config']['Product_Page']['map_bridge_text']; ?>
</span>
<?php endif;  if ($this->_tpl_vars['active_modules']['Special_Offers'] != "" && $this->_tpl_vars['products'][$this->_sections['product']['index']]['use_special_price'] != ""): ?>
</s>
<?php endif;  if ($this->_tpl_vars['products'][$this->_sections['product']['index']]['taxes']): ?>
<br />
<div class="PListTaxBox"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/taxed_price.tpl", 'smarty_include_vars' => array('taxes' => $this->_tpl_vars['products'][$this->_sections['product']['index']]['taxes'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></div>
<?php endif;  if ($this->_tpl_vars['active_modules']['Special_Offers'] != "" && $this->_tpl_vars['products'][$this->_sections['product']['index']]['use_special_price'] != ""):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Special_Offers/customer/product_special_price.tpl", 'smarty_include_vars' => array('product' => $this->_tpl_vars['products'][$this->_sections['product']['index']])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  else: ?>
<font class="ProductPrice"><?php echo $this->_tpl_vars['lng']['lbl_enter_your_price']; ?>
</font>
<?php endif;  endif; ?>

<?php if ($this->_tpl_vars['products'][$this->_sections['product']['index']]['eta_date_in_future'] == 'Y'): ?>
<br />
<br />
<font color="#000000" size=2>
Expected availability: <?php echo $this->_tpl_vars['products'][$this->_sections['product']['index']]['eta_date_dd_month_yyyy']; ?>

<?php if ($this->_tpl_vars['products'][$this->_sections['product']['index']]['allow_pre_orders'] != 'Y'): ?>
<br />
Sorry we don't take pre-orders.
<?php endif; ?>
</font>
<?php endif; ?>

<?php if ($this->_tpl_vars['usertype'] == 'C' && $this->_tpl_vars['config']['Appearance']['buynow_button_enabled'] == 'Y'): ?>

	<?php $this->assign('tmp_productid', $this->_tpl_vars['products'][$this->_sections['product']['index']]['productid']); ?>

	<?php if ($this->_tpl_vars['products'][$this->_sections['product']['index']]['new_notify_in_stock_price'] != "" && $this->_tpl_vars['notify_when_in_stock'][$this->_tpl_vars['tmp_productid']] != 'Y'): ?>
<br />
<span class="BuyNowQuantity"><?php echo $this->_tpl_vars['lng']['lbl_quantity']; ?>
:</span> <b><?php echo $this->_tpl_vars['lng']['txt_out_of_stock']; ?>
</b><br />

<div id="notify_tr1_<?php echo $this->_tpl_vars['products'][$this->_sections['product']['index']]['productid']; ?>
">
<I><a href="javascript: void(0);" onclick="javascript: $('#notify_tr1_<?php echo $this->_tpl_vars['products'][$this->_sections['product']['index']]['productid']; ?>
').hide(); $('#notify_tr2_<?php echo $this->_tpl_vars['products'][$this->_sections['product']['index']]['productid']; ?>
').show();" >Notify me when it's in stock</a></I>
</div>
<div id="notify_tr2_<?php echo $this->_tpl_vars['products'][$this->_sections['product']['index']]['productid']; ?>
" style="display: none;">

<form name="notifyform_<?php echo $this->_tpl_vars['products'][$this->_sections['product']['index']]['productid']; ?>
" method="post" 
action='<?php if ($this->_tpl_vars['main'] == 'catalog'):  if ($this->_tpl_vars['action_notify_url'] != ""):  echo $this->_tpl_vars['action_notify_url'];  else: ?>home.php<?php endif;  elseif ($this->_tpl_vars['main'] == 'brand_products'): ?>brands.php<?php endif; ?>'
>
<input type="hidden" name="productid" value="<?php echo $this->_tpl_vars['products'][$this->_sections['product']['index']]['productid']; ?>
" />
<input type="hidden" name="mode" value="notify" />
<B>Your email address:</B> <input type="text" name="notify_email" value="" />

<?php if ($this->_tpl_vars['main'] == 'catalog'): ?>
	<input type="hidden" name="cat" value="<?php echo $this->_tpl_vars['cat']; ?>
" />

	<?php if ($this->_tpl_vars['action_notify_url'] != ""): ?>
		<input type="hidden" name="redirect_to_notify_url" value="<?php echo $this->_tpl_vars['action_notify_url']; ?>
" />
	<?php endif;  elseif ($this->_tpl_vars['main'] == 'brand_products'): ?>
	<input type="hidden" name="brandid" value="<?php echo $this->_tpl_vars['brandid']; ?>
" />
<?php endif; ?>

	<?php if ($GLOBALS['_GET']['page'] != ""): ?>
		<input type="hidden" name="page" value="<?php echo $GLOBALS['_GET']['page']; ?>
" />
	<?php endif; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/button.tpl", 'smarty_include_vars' => array('button_title' => 'Notify me','style' => 'button','href' => "javascript:if (checkEmailAddress(document.notifyform_".($this->_tpl_vars['products'][$this->_sections['product']['index']]['productid']).".notify_email, 'Y')) ".($this->_tpl_vars['ldelim'])."document.notifyform_".($this->_tpl_vars['products'][$this->_sections['product']['index']]['productid']).".submit()".($this->_tpl_vars['rdelim']))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</form>

</div>
<br />

	<?php else: ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/buy_now.tpl", 'smarty_include_vars' => array('product' => $this->_tpl_vars['products'][$this->_sections['product']['index']])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php endif; ?>

<?php endif;  endif; ?>

	</td>
</tr>
</table>
<?php if ($this->_tpl_vars['search_all_website'] != 'Y'): ?>
<br />
<?php endif;  if (! $this->_sections['product']['last']): ?>
<hr style="border-bottom: 1px dashed #CCCCCC; border-top: 0px; border-left: 0px; border-right: 0px;" />
<?php endif;  endfor; endif;  if ($this->_tpl_vars['active_modules']['Feature_Comparison'] != '' && $this->_tpl_vars['products'] && $this->_tpl_vars['printable'] != 'Y' && $this->_tpl_vars['products_has_fclasses']):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Feature_Comparison/compare_selected_button.tpl", 'smarty_include_vars' => array('no_form' => true)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>


<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/infinite_products.tpl", 'smarty_include_vars' => array('show_next_products' => 'N')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>


<?php else: ?>

<?php if ($this->_tpl_vars['e_search_data']['substring'] != "" && $this->_tpl_vars['e_search_data']['total'] == 0):  echo $this->_tpl_vars['lng']['lbl_nothing_found_home_page']; ?>

<?php else:  echo $this->_tpl_vars['lng']['txt_no_products_found']; ?>

<?php endif; ?>

<?php endif;  endif;  if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "customer/main/products.tpl"), $this); endif; ?>
<?php /* Smarty version 2.6.12, created on 2011-10-11 05:37:50
         compiled from customer/main/products.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'truncate', 'customer/main/products.tpl', 40, false),array('function', 'math', 'customer/main/products.tpl', 55, false),)), $this); ?>
<?php func_load_lang($this, "customer/main/products.tpl","lbl_see_details,lbl_sku,lbl_market_price,lbl_our_price,lbl_save_price,lbl_enter_your_price,txt_no_products_found"); ?><?php if ($this->_tpl_vars['active_modules']['Feature_Comparison'] != '' && $this->_tpl_vars['products'] && $this->_tpl_vars['printable'] != 'Y' && $this->_tpl_vars['products_has_fclasses']):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Feature_Comparison/compare_selected_button.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Feature_Comparison/products_check_js.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  if ($this->_tpl_vars['usertype'] == 'C' && $this->_tpl_vars['config']['Appearance']['products_per_row'] != "" && $this->_tpl_vars['config']['Appearance']['products_per_row'] > 0 && $this->_tpl_vars['config']['Appearance']['products_per_row'] < 4 && ( $this->_tpl_vars['featured'] == 'Y' || $this->_tpl_vars['config']['Appearance']['featured_only_multicolumn'] == 'N' )):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/products_t.tpl", 'smarty_include_vars' => array('products' => $this->_tpl_vars['products'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  else:  if ($this->_tpl_vars['products']):  unset($this->_sections['product']);
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
 $this->assign('discount', 0); ?>
<table width="100%">
<tr>
<td class="PListImgBox">
<div class="PListImgBox">
<a href="product.php?productid=<?php echo $this->_tpl_vars['products'][$this->_sections['product']['index']]['productid']; ?>
&amp;cat=<?php echo $this->_tpl_vars['cat']; ?>
&amp;path=alt&amp;page=<?php echo $this->_tpl_vars['navigation_page'];  if ($this->_tpl_vars['featured'] == 'Y'): ?>&amp;featured<?php endif; ?>"><?php $_smarty_tpl_vars = $this->_tpl_vars;
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
<a href="product.php?productid=<?php echo $this->_tpl_vars['products'][$this->_sections['product']['index']]['productid']; ?>
&amp;cat=<?php echo $this->_tpl_vars['cat']; ?>
&amp;path=alt&amp;page=<?php echo $this->_tpl_vars['navigation_page'];  if ($this->_tpl_vars['featured'] == 'Y'): ?>&amp;featured<?php endif; ?>" class="SeeDetails"><?php echo $this->_tpl_vars['lng']['lbl_see_details']; ?>
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
<a href="product.php?productid=<?php echo $this->_tpl_vars['products'][$this->_sections['product']['index']]['productid']; ?>
&amp;cat=<?php echo $this->_tpl_vars['cat']; ?>
&amp;path=alt&amp;page=<?php echo $this->_tpl_vars['navigation_page'];  if ($this->_tpl_vars['featured'] == 'Y'): ?>&amp;featured<?php endif; ?>"><font class="ProductTitle"><?php echo $this->_tpl_vars['products'][$this->_sections['product']['index']]['product']; ?>
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
	<?php echo ((is_array($_tmp=$this->_tpl_vars['products'][$this->_sections['product']['index']]['descr'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 225, "...", true) : smarty_modifier_truncate($_tmp, 225, "...", true)); ?>

</div>
</font>
<br>
<?php if ($this->_tpl_vars['products'][$this->_sections['product']['index']]['product_type'] == 'C'):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/details.tpl", 'smarty_include_vars' => array('href' => "product.php?productid=".($this->_tpl_vars['products'][$this->_sections['product']['index']]['productid'])."&amp;cat=".($this->_tpl_vars['cat'])."&amp;path=alt&amp;page=".($this->_tpl_vars['navigation_page']))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  else:  if ($this->_tpl_vars['active_modules']['Subscriptions'] != "" && ( $this->_tpl_vars['products'][$this->_sections['product']['index']]['catalogprice'] > 0 || $this->_tpl_vars['products'][$this->_sections['product']['index']]['sub_priceplan'] > 0 )):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Subscriptions/subscription_info_inlist.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  else:  if ($this->_tpl_vars['config']['General']['unlimited_products'] != 'Y' && ( $this->_tpl_vars['products'][$this->_sections['product']['index']]['avail'] <= 0 || $this->_tpl_vars['products'][$this->_sections['product']['index']]['avail'] < $this->_tpl_vars['products'][$this->_sections['product']['index']]['min_amount'] ) && $this->_tpl_vars['products'][$this->_sections['product']['index']]['variantid']): ?>
&nbsp;
<?php elseif ($this->_tpl_vars['products'][$this->_sections['product']['index']]['taxed_price'] != 0):  if ($this->_tpl_vars['products'][$this->_sections['product']['index']]['list_price'] > 0 && $this->_tpl_vars['products'][$this->_sections['product']['index']]['taxed_price'] < $this->_tpl_vars['products'][$this->_sections['product']['index']]['list_price']):  echo smarty_function_math(array('equation' => "100-(price/lprice)*100",'price' => $this->_tpl_vars['products'][$this->_sections['product']['index']]['taxed_price'],'lprice' => $this->_tpl_vars['products'][$this->_sections['product']['index']]['list_price'],'format' => "%3.0f",'assign' => 'discount'), $this);?>

<?php if ($this->_tpl_vars['discount'] > 0): ?>
<font class="MarketPrice"><?php echo $this->_tpl_vars['lng']['lbl_market_price']; ?>
:
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['products'][$this->_sections['product']['index']]['list_price'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</font><br />
<?php endif;  endif;  if ($this->_tpl_vars['active_modules']['Special_Offers'] != "" && $this->_tpl_vars['products'][$this->_sections['product']['index']]['use_special_price'] != ""): ?>
<s>
<?php endif; ?>
<font class="ProductPrice"><?php echo $this->_tpl_vars['lng']['lbl_our_price']; ?>
: <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['products'][$this->_sections['product']['index']]['taxed_price'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></font><font class="MarketPrice"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/alter_currency_value.tpl", 'smarty_include_vars' => array('alter_currency_value' => $this->_tpl_vars['products'][$this->_sections['product']['index']]['taxed_price'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></font><?php if ($this->_tpl_vars['discount'] > 0):  if ($this->_tpl_vars['config']['General']['alter_currency_symbol'] != ""): ?>,<?php endif; ?><font class="ProductPrice">, <?php echo $this->_tpl_vars['lng']['lbl_save_price']; ?>
 <?php echo $this->_tpl_vars['discount']; ?>
%</font><?php endif;  if ($this->_tpl_vars['active_modules']['Special_Offers'] != "" && $this->_tpl_vars['products'][$this->_sections['product']['index']]['use_special_price'] != ""): ?>
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
<?php endif;  endif;  if ($this->_tpl_vars['usertype'] == 'C' && $this->_tpl_vars['config']['Appearance']['buynow_button_enabled'] == 'Y'):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/buy_now.tpl", 'smarty_include_vars' => array('product' => $this->_tpl_vars['products'][$this->_sections['product']['index']])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  endif; ?>
	</td>
</tr>
</table>
<br />
<br />
<br />
<?php endfor; endif;  if ($this->_tpl_vars['active_modules']['Feature_Comparison'] != '' && $this->_tpl_vars['products'] && $this->_tpl_vars['printable'] != 'Y' && $this->_tpl_vars['products_has_fclasses']):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Feature_Comparison/compare_selected_button.tpl", 'smarty_include_vars' => array('no_form' => true)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  else:  echo $this->_tpl_vars['lng']['txt_no_products_found']; ?>

<?php endif;  endif; ?>
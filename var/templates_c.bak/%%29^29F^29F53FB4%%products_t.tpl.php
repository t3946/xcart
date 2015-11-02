<?php /* Smarty version 2.6.12, created on 2011-10-11 05:37:54
         compiled from customer/main/products_t.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'math', 'customer/main/products_t.tpl', 8, false),array('modifier', 'default', 'customer/main/products_t.tpl', 29, false),)), $this); ?>
<?php func_load_lang($this, "customer/main/products_t.tpl","lbl_sku,lbl_see_details,lbl_market_price,lbl_our_price,lbl_save_price,lbl_enter_your_price"); ?><table width="100%" cellpadding="0" cellspacing="0">
<tr>
	<td>

<table width="100%" cellpadding="5" cellspacing="1">

<?php echo smarty_function_math(array('equation' => "floor(100/x)",'x' => $this->_tpl_vars['config']['Appearance']['products_per_row'],'assign' => 'width'), $this);?>


<?php unset($this->_sections['product']);
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

<?php if (!($this->_sections['product']['index'] % $this->_tpl_vars['config']['Appearance']['products_per_row'])): ?>
<tr>
<?php $this->assign('cell_counter', 0);  endif; ?>

<?php echo smarty_function_math(array('equation' => "x+1",'x' => $this->_tpl_vars['cell_counter'],'assign' => 'cell_counter'), $this);?>


	<td width="<?php echo $this->_tpl_vars['width']; ?>
%" class="PListCell">

<a href="product.php?productid=<?php echo $this->_tpl_vars['products'][$this->_sections['product']['index']]['productid']; ?>
&amp;cat=<?php echo $this->_tpl_vars['cat']; ?>
&amp;page=<?php echo $this->_tpl_vars['navigation_page']; ?>
" class="ProductTitle<?php if ($this->_tpl_vars['flag'] == 'related'): ?>Related<?php endif; ?>"><font color=#0033CC><?php echo $this->_tpl_vars['products'][$this->_sections['product']['index']]['product']; ?>
</font></a><br />
<?php if ($this->_tpl_vars['config']['Appearance']['display_productcode_in_list'] == 'Y' && $this->_tpl_vars['products'][$this->_sections['product']['index']]['productcode'] != ""): ?>
<font color=#<?php if ($this->_tpl_vars['flag'] == 'related'): ?>006600<?php else: ?>000000<?php endif; ?> size=2><?php echo $this->_tpl_vars['lng']['lbl_sku']; ?>
: <?php echo $this->_tpl_vars['products'][$this->_sections['product']['index']]['productcode']; ?>
</font><br />
<?php endif; ?>
<table cellpadding="3" cellspacing="0" width="100%">
<tr>
	<td height="100" nowrap="nowrap">
<a href="product.php?productid=<?php echo $this->_tpl_vars['products'][$this->_sections['product']['index']]['productid']; ?>
&amp;cat=<?php echo $this->_tpl_vars['cat']; ?>
&amp;page=<?php echo $this->_tpl_vars['navigation_page']; ?>
"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "product_thumbnail.tpl", 'smarty_include_vars' => array('productid' => $this->_tpl_vars['products'][$this->_sections['product']['index']]['productid'],'image_x' => ((is_array($_tmp=@$this->_tpl_vars['products'][$this->_sections['product']['index']]['tmbn_x'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['config']['Appearance']['thumbnail_width']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['config']['Appearance']['thumbnail_width'])),'image_y' => $this->_tpl_vars['products'][$this->_sections['product']['index']]['tmbn_y'],'product' => $this->_tpl_vars['products'][$this->_sections['product']['index']]['product'],'tmbn_url' => $this->_tpl_vars['products'][$this->_sections['product']['index']]['tmbn_url'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></a>
<?php if ($this->_tpl_vars['active_modules']['Special_Offers'] != "" && $this->_tpl_vars['products'][$this->_sections['product']['index']]['have_offers']):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Special_Offers/customer/product_offer_thumb.tpl", 'smarty_include_vars' => array('product' => $this->_tpl_vars['products'][$this->_sections['product']['index']])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
	</td>
</tr>
</table>
<a href="product.php?productid=<?php echo $this->_tpl_vars['products'][$this->_sections['product']['index']]['productid']; ?>
&amp;cat=<?php echo $this->_tpl_vars['cat']; ?>
&amp;page=<?php echo $this->_tpl_vars['navigation_page']; ?>
" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_see_details']; ?>
</a>
<?php if ($this->_tpl_vars['products'][$this->_sections['product']['index']]['product_type'] != 'C'):  if ($this->_tpl_vars['flag'] != 'related'): ?><br /><?php endif; ?>
<br />
<?php if ($this->_tpl_vars['active_modules']['Subscriptions'] != "" && $this->_tpl_vars['products'][$this->_sections['product']['index']]['catalogprice']):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Subscriptions/subscription_info_inlist.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  else:  if ($this->_tpl_vars['config']['General']['unlimited_products'] != 'Y' && ( $this->_tpl_vars['products'][$this->_sections['product']['index']]['avail'] <= 0 || $this->_tpl_vars['products'][$this->_sections['product']['index']]['avail'] < $this->_tpl_vars['products'][$this->_sections['product']['index']]['min_amount'] ) && $this->_tpl_vars['products'][$this->_sections['product']['index']]['variantid']): ?>
&nbsp;
<?php elseif ($this->_tpl_vars['products'][$this->_sections['product']['index']]['taxed_price'] != 0):  if ($this->_tpl_vars['products'][$this->_sections['product']['index']]['list_price'] > 0 && $this->_tpl_vars['products'][$this->_sections['product']['index']]['taxed_price'] < $this->_tpl_vars['products'][$this->_sections['product']['index']]['list_price']):  echo smarty_function_math(array('equation' => "100-(price/lprice)*100",'price' => $this->_tpl_vars['products'][$this->_sections['product']['index']]['taxed_price'],'lprice' => $this->_tpl_vars['products'][$this->_sections['product']['index']]['list_price'],'format' => "%3.0f",'assign' => 'discount'), $this);?>

<?php if ($this->_tpl_vars['discount'] > 0): ?>
<font class="MarketPrice"><?php echo $this->_tpl_vars['lng']['lbl_market_price']; ?>
: <s>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['products'][$this->_sections['product']['index']]['list_price'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</s></font><br />
<?php endif;  endif; ?>
<font class="ProductPrice<?php if ($this->_tpl_vars['flag'] == 'related'): ?>Related<?php endif; ?>"><?php echo $this->_tpl_vars['lng']['lbl_our_price']; ?>
: <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['products'][$this->_sections['product']['index']]['taxed_price'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></font><br /><font class="MarketPrice"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/alter_currency_value.tpl", 'smarty_include_vars' => array('alter_currency_value' => $this->_tpl_vars['products'][$this->_sections['product']['index']]['taxed_price'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></font><?php if ($this->_tpl_vars['discount'] > 0):  if ($this->_tpl_vars['config']['General']['alter_currency_symbol'] != ""): ?>,<?php endif; ?> <font color=#000000 size=2><?php echo $this->_tpl_vars['lng']['lbl_save_price']; ?>
 <?php echo $this->_tpl_vars['discount']; ?>
%</font><?php endif;  if ($this->_tpl_vars['products'][$this->_sections['product']['index']]['taxes']): ?><br /><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/taxed_price.tpl", 'smarty_include_vars' => array('taxes' => $this->_tpl_vars['products'][$this->_sections['product']['index']]['taxes'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  if ($this->_tpl_vars['active_modules']['Special_Offers'] != "" && $this->_tpl_vars['products'][$this->_sections['product']['index']]['use_special_price'] != ""):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Special_Offers/customer/product_special_price.tpl", 'smarty_include_vars' => array('product' => $this->_tpl_vars['products'][$this->_sections['product']['index']])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  else: ?>
<font class="ProductPrice"><?php echo $this->_tpl_vars['lng']['lbl_enter_your_price']; ?>
</font>
<?php endif;  endif;  if ($this->_tpl_vars['active_modules']['Feature_Comparison'] != '' && $this->_tpl_vars['products'][$this->_sections['product']['index']]['fclassid'] > 0): ?>
<div align="center" style="width: 100%; padding-top: 10px;">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Feature_Comparison/compare_checkbox.tpl", 'smarty_include_vars' => array('id' => $this->_tpl_vars['products'][$this->_sections['product']['index']]['productid'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>
<?php endif;  endif; ?>
	</td>

<?php ob_start();  echo smarty_function_math(array('equation' => "index+x+1",'index' => $this->_sections['product']['index'],'x' => $this->_tpl_vars['config']['Appearance']['products_per_row']), $this);?>

<?php $this->_smarty_vars['capture']['prod_index'] = ob_get_contents(); ob_end_clean();  if (!($this->_smarty_vars['capture']['prod_index'] % $this->_tpl_vars['config']['Appearance']['products_per_row'])): ?>
</tr>
<?php endif; ?>

<?php endfor; endif; ?>

<?php if ($this->_tpl_vars['cell_counter'] < $this->_tpl_vars['config']['Appearance']['products_per_row']):  unset($this->_sections['rest_cells']);
$this->_sections['rest_cells']['name'] = 'rest_cells';
$this->_sections['rest_cells']['loop'] = is_array($_loop=$this->_tpl_vars['config']['Appearance']['products_per_row']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['rest_cells']['start'] = (int)$this->_tpl_vars['cell_counter'];
$this->_sections['rest_cells']['show'] = true;
$this->_sections['rest_cells']['max'] = $this->_sections['rest_cells']['loop'];
$this->_sections['rest_cells']['step'] = 1;
if ($this->_sections['rest_cells']['start'] < 0)
    $this->_sections['rest_cells']['start'] = max($this->_sections['rest_cells']['step'] > 0 ? 0 : -1, $this->_sections['rest_cells']['loop'] + $this->_sections['rest_cells']['start']);
else
    $this->_sections['rest_cells']['start'] = min($this->_sections['rest_cells']['start'], $this->_sections['rest_cells']['step'] > 0 ? $this->_sections['rest_cells']['loop'] : $this->_sections['rest_cells']['loop']-1);
if ($this->_sections['rest_cells']['show']) {
    $this->_sections['rest_cells']['total'] = min(ceil(($this->_sections['rest_cells']['step'] > 0 ? $this->_sections['rest_cells']['loop'] - $this->_sections['rest_cells']['start'] : $this->_sections['rest_cells']['start']+1)/abs($this->_sections['rest_cells']['step'])), $this->_sections['rest_cells']['max']);
    if ($this->_sections['rest_cells']['total'] == 0)
        $this->_sections['rest_cells']['show'] = false;
} else
    $this->_sections['rest_cells']['total'] = 0;
if ($this->_sections['rest_cells']['show']):

            for ($this->_sections['rest_cells']['index'] = $this->_sections['rest_cells']['start'], $this->_sections['rest_cells']['iteration'] = 1;
                 $this->_sections['rest_cells']['iteration'] <= $this->_sections['rest_cells']['total'];
                 $this->_sections['rest_cells']['index'] += $this->_sections['rest_cells']['step'], $this->_sections['rest_cells']['iteration']++):
$this->_sections['rest_cells']['rownum'] = $this->_sections['rest_cells']['iteration'];
$this->_sections['rest_cells']['index_prev'] = $this->_sections['rest_cells']['index'] - $this->_sections['rest_cells']['step'];
$this->_sections['rest_cells']['index_next'] = $this->_sections['rest_cells']['index'] + $this->_sections['rest_cells']['step'];
$this->_sections['rest_cells']['first']      = ($this->_sections['rest_cells']['iteration'] == 1);
$this->_sections['rest_cells']['last']       = ($this->_sections['rest_cells']['iteration'] == $this->_sections['rest_cells']['total']);
?>
	<td class="SectionBox">&nbsp;</td>
<?php endfor; endif; ?>
</tr>
<?php endif; ?>

</table>
	</td>
</tr>
</table>
<?php if ($this->_tpl_vars['active_modules']['Feature_Comparison'] != '' && $this->_tpl_vars['products'] && $this->_tpl_vars['printable'] != 'Y' && $this->_tpl_vars['products_has_fclasses']):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Feature_Comparison/compare_selected_button.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

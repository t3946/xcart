<?php /* Smarty version 2.6.12, created on 2011-10-11 06:23:05
         compiled from customer/main/cart_contents.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'math', 'customer/main/cart_contents.tpl', 28, false),array('modifier', 'truncate', 'customer/main/cart_contents.tpl', 49, false),array('modifier', 'trademark', 'customer/main/cart_contents.tpl', 70, false),)), $this); ?>
<?php func_load_lang($this, "customer/main/cart_contents.tpl","lbl_sku,lbl_product,lbl_tax,lbl_price,lbl_qty,lbl_total,lbl_shipping_cost,lbl_shipping,lbl_no_shipping_for_location,lbl_subtotal"); ?><table cellpadding="5" cellspacing="1" width="100%">

<tr class="TableHead">
<td><b><?php echo $this->_tpl_vars['lng']['lbl_sku']; ?>
</b></td>
<td><b><?php echo $this->_tpl_vars['lng']['lbl_product']; ?>
</b></td>
<?php if ($this->_tpl_vars['cart']['display_cart_products_tax_rates'] == 'Y'): ?>
<td align="center"><b><?php if ($this->_tpl_vars['cart']['product_tax_name'] != ""):  echo $this->_tpl_vars['cart']['product_tax_name'];  else:  echo $this->_tpl_vars['lng']['lbl_tax'];  endif; ?></b></td>
<?php endif; ?>
<td align="right"><b><?php echo $this->_tpl_vars['lng']['lbl_price']; ?>
</b></td>
<td align="right"><b><?php echo $this->_tpl_vars['lng']['lbl_qty']; ?>
</b></td>
<td align="right"><b><?php echo $this->_tpl_vars['lng']['lbl_total']; ?>
</b></td>
</tr>
<?php $this->assign('shipping_was_shown', 'N');  $this->assign('anyproducts_was_shown', 'N');  $this->assign('trstyle', "class='TableSubHead'");  $_from = $this->_tpl_vars['cart']['shipping_groups']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
 $this->assign('last_group_tax', ""); ?>
<tr>
<td colspan="6" class="DialogTitle">
<br />
<b><?php echo $this->_tpl_vars['v']['group_name']; ?>
</b></td>
</tr>
<?php $this->assign('deliv_subt', '0');  unset($this->_sections['prod_num']);
$this->_sections['prod_num']['name'] = 'prod_num';
$this->_sections['prod_num']['loop'] = is_array($_loop=$this->_tpl_vars['products']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['prod_num']['show'] = true;
$this->_sections['prod_num']['max'] = $this->_sections['prod_num']['loop'];
$this->_sections['prod_num']['step'] = 1;
$this->_sections['prod_num']['start'] = $this->_sections['prod_num']['step'] > 0 ? 0 : $this->_sections['prod_num']['loop']-1;
if ($this->_sections['prod_num']['show']) {
    $this->_sections['prod_num']['total'] = $this->_sections['prod_num']['loop'];
    if ($this->_sections['prod_num']['total'] == 0)
        $this->_sections['prod_num']['show'] = false;
} else
    $this->_sections['prod_num']['total'] = 0;
if ($this->_sections['prod_num']['show']):

            for ($this->_sections['prod_num']['index'] = $this->_sections['prod_num']['start'], $this->_sections['prod_num']['iteration'] = 1;
                 $this->_sections['prod_num']['iteration'] <= $this->_sections['prod_num']['total'];
                 $this->_sections['prod_num']['index'] += $this->_sections['prod_num']['step'], $this->_sections['prod_num']['iteration']++):
$this->_sections['prod_num']['rownum'] = $this->_sections['prod_num']['iteration'];
$this->_sections['prod_num']['index_prev'] = $this->_sections['prod_num']['index'] - $this->_sections['prod_num']['step'];
$this->_sections['prod_num']['index_next'] = $this->_sections['prod_num']['index'] + $this->_sections['prod_num']['step'];
$this->_sections['prod_num']['first']      = ($this->_sections['prod_num']['iteration'] == 1);
$this->_sections['prod_num']['last']       = ($this->_sections['prod_num']['iteration'] == $this->_sections['prod_num']['total']);
 if (( $this->_tpl_vars['products'][$this->_sections['prod_num']['index']]['manufacturerid'] == $this->_tpl_vars['k'] && $this->_tpl_vars['products'][$this->_sections['prod_num']['index']]['shipping_freight'] != '0' ) || ( $this->_tpl_vars['k'] == $this->_tpl_vars['artss_manufacturerid'] && $this->_tpl_vars['products'][$this->_sections['prod_num']['index']]['shipping_freight'] == '0' )):  echo smarty_function_math(array('equation' => "x+y",'x' => $this->_tpl_vars['deliv_subt'],'y' => $this->_tpl_vars['products'][$this->_sections['prod_num']['index']]['display_subtotal'],'assign' => 'deliv_subt'), $this);?>

<?php if ($this->_tpl_vars['shipping_was_shown'] == 'Y' || $this->_tpl_vars['has_zero_freight_products'] == 'N'):  endif;  if ($this->_tpl_vars['shipping_was_shown'] == 'N' && $this->_tpl_vars['products'][$this->_sections['prod_num']['index']]['shipping_freight'] != 0 && $this->_tpl_vars['anyproducts_was_shown'] == 'Y' && $this->_tpl_vars['has_zero_freight_products'] == 'Y'): ?>
<tr <?php echo $this->_tpl_vars['trstyle']; ?>
>
<td class="Green2">
<?php $_from = $this->_tpl_vars['shipping']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['s']):
 if ($this->_tpl_vars['cart']['shippingid'] == $this->_tpl_vars['s']['shippingid']):  echo $this->_tpl_vars['s']['shipping'];  endif;  endforeach; endif; unset($_from); ?>
</td>
<td class="Green2"><?php echo $this->_tpl_vars['lng']['lbl_shipping_cost']; ?>
</td>
<?php if ($this->_tpl_vars['cart']['display_cart_products_tax_rates'] == 'Y'): ?>
<td align="center">&nbsp;</td>
<?php endif; ?>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td class="Green2" align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['cart']['display_shipping_cost'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>
<?php $this->assign('shipping_was_shown', 'Y');  endif; ?>
<tr <?php echo $this->_tpl_vars['trstyle']; ?>
>
<td><?php echo $this->_tpl_vars['products'][$this->_sections['prod_num']['index']]['productcode']; ?>
</td>
<td><?php echo ((is_array($_tmp=$this->_tpl_vars['products'][$this->_sections['prod_num']['index']]['product'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 30, "...", true) : smarty_modifier_truncate($_tmp, 30, "...", true)); ?>
</td>
<?php if ($this->_tpl_vars['cart']['display_cart_products_tax_rates'] == 'Y'): ?>
<td align="center">
<?php $_from = $this->_tpl_vars['products'][$this->_sections['prod_num']['index']]['taxes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['tax_name'] => $this->_tpl_vars['tax']):
 if ($this->_tpl_vars['cart']['product_tax_name'] == ""): ?><span style="white-space: nowrap;"><?php echo $this->_tpl_vars['tax']['tax_display_name']; ?>
:</span><?php endif;  if ($this->_tpl_vars['tax']['rate_type'] == "%"):  echo $this->_tpl_vars['tax']['rate_value']; ?>
%<?php else:  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['tax']['rate_value'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?><br />
<?php endforeach; endif; unset($_from);  if ($this->_tpl_vars['last_group_tax'] == ""):  $this->assign('last_group_tax', $this->_tpl_vars['products'][$this->_sections['prod_num']['index']]['taxes']);  endif; ?>
</td>
<?php endif; ?>
<td class="ProductPriceSmall" style="color: black;" align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['products'][$this->_sections['prod_num']['index']]['display_price'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
<?php echo smarty_function_math(array('equation' => "x*y",'x' => $this->_tpl_vars['products'][$this->_sections['prod_num']['index']]['display_price'],'y' => $this->_tpl_vars['products'][$this->_sections['prod_num']['index']]['amount'],'assign' => 'total'), $this);?>

<td class="ProductPriceSmall" align="right"><?php if ($this->_tpl_vars['config']['Appearance']['allow_update_quantity_in_cart'] == 'N' || ( $this->_tpl_vars['active_modules']['Egoods'] && $this->_tpl_vars['products'][$this->_sections['prod_num']['index']]['distribution'] ) || ( $this->_tpl_vars['active_modules']['Subscriptions'] && $this->_tpl_vars['products'][$this->_sections['prod_num']['index']]['sub_plan'] )):  echo $this->_tpl_vars['products'][$this->_sections['prod_num']['index']]['amount'];  else:  if ($this->_tpl_vars['link_qty'] == 'Y'): ?><a href="cart.php"><?php echo $this->_tpl_vars['products'][$this->_sections['prod_num']['index']]['amount']; ?>
</a><?php else: ?><input type="text" size="3" name="productindexes[<?php echo $this->_tpl_vars['products'][$this->_sections['prod_num']['index']]['cartid']; ?>
]" value="<?php echo $this->_tpl_vars['products'][$this->_sections['prod_num']['index']]['amount']; ?>
" /><?php endif;  endif; ?></td>
<td class="ProductPriceSmall" style="color: black;"  align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['total'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>
<?php $this->assign('anyproducts_was_shown', 'Y');  endif;  endfor; endif; ?>
<tr <?php echo $this->_tpl_vars['trstyle']; ?>
>
<?php if ($this->_tpl_vars['cart']['groups_delivery'][$this->_tpl_vars['k']] != ''): ?>
<td class="Green2"><?php echo $this->_tpl_vars['lng']['lbl_shipping']; ?>
</td>
<td class="Green2"><?php echo ((is_array($_tmp=$this->_tpl_vars['cart']['groups_delivery'][$this->_tpl_vars['k']])) ? $this->_run_mod_handler('trademark', true, $_tmp, $this->_tpl_vars['insert_trademark']) : smarty_modifier_trademark($_tmp, $this->_tpl_vars['insert_trademark'])); ?>
</td>
<?php if ($this->_tpl_vars['cart']['display_cart_products_tax_rates'] == 'Y'): ?>
<td align="center">
<?php if ($this->_tpl_vars['last_group_tax'] != ""):  $_from = $this->_tpl_vars['last_group_tax']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['tax_name'] => $this->_tpl_vars['tax']):
 if ($this->_tpl_vars['cart']['product_tax_name'] == ""): ?><span style="white-space: nowrap;"><?php echo $this->_tpl_vars['tax']['tax_display_name']; ?>
:</span><?php endif;  if ($this->_tpl_vars['tax']['rate_type'] == "%"):  echo $this->_tpl_vars['tax']['rate_value']; ?>
%<?php else:  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['tax']['rate_value'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?><br />
<?php endforeach; endif; unset($_from);  endif; ?>
</td>
<?php endif; ?>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td class="Green2" align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['cart']['display_shipping_costs'][$this->_tpl_vars['k']])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
<?php $this->assign('deliv_subt', $this->_tpl_vars['cart']['display_shipping_costs'][$this->_tpl_vars['k']]+$this->_tpl_vars['deliv_subt']);  else: ?>
<td <?php if ($this->_tpl_vars['cart']['display_cart_products_tax_rates'] == 'Y'): ?>colspan="6"<?php else: ?>colspan="5"<?php endif; ?>><font class="ErrorMessage"><?php echo $this->_tpl_vars['lng']['lbl_no_shipping_for_location']; ?>
</font></td>
<?php endif; ?>
</tr>
<tr <?php echo $this->_tpl_vars['trstyle']; ?>
>
<td>&nbsp;</td>
<td>&nbsp;</td>
<?php if ($this->_tpl_vars['cart']['display_cart_products_tax_rates'] == 'Y'): ?><td>&nbsp;</td><?php endif; ?>
<td nowrap="nowrap" class="ProductPriceSmall" align="right">
<b><?php echo $this->_tpl_vars['lng']['lbl_subtotal']; ?>
:</b>
</td>
<td>&nbsp;</td>
<td nowrap="nowrap" align="right"><font class="ProductPriceSmall"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['deliv_subt'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></font></td>
</tr>
<?php endforeach; endif; unset($_from); ?>
<tr><td colspan="6"><hr size="1" noshade="noshade" /></td></tr>
<?php if ($this->_tpl_vars['active_modules']['Gift_Certificates'] != ""):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Gift_Certificates/gc_checkout.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
</table>
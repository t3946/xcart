<?php /* Smarty version 2.6.12, created on 2011-10-11 06:33:33
         compiled from main/products.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'main/products.tpl', 7, false),array('modifier', 'replace', 'main/products.tpl', 7, false),array('modifier', 'amp', 'main/products.tpl', 33, false),array('modifier', 'formatprice', 'main/products.tpl', 60, false),array('modifier', 'default', 'main/products.tpl', 75, false),array('function', 'cycle', 'main/products.tpl', 49, false),)), $this); ?>
<?php func_load_lang($this, "main/products.tpl","txt_pvariant_edit_note_list,lbl_products_more,lbl_sku,lbl_product,lbl_main_add_categories,lbl_pos,lbl_price,lbl_list_price,lbl_in_stock,lbl_weight,lbl_shipping_freight,lbl_avail,lbl_products_more,lbl_disabled"); ?><?php if ($this->_tpl_vars['products'] != ""):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/check_all_row.tpl", 'smarty_include_vars' => array('style' => "line-height: 170%;",'form' => 'processproductform','prefix' => 'productids')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<script type="text/javascript">
<!--
var txt_pvariant_edit_note_list = "<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['txt_pvariant_edit_note_list'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')))) ? $this->_run_mod_handler('replace', true, $_tmp, '"', '\"') : smarty_modifier_replace($_tmp, '"', '\"')); ?>
";

<?php echo '
function pvAlert(obj) {
 if (obj.pvAlertFlag)
  return false;

 alert(txt_pvariant_edit_note_list);
 obj.pvAlertFlag = true;
 return true;
}
'; ?>

-->
</script>

<table cellpadding="2" cellspacing="1" width="100%">

<?php if ($this->_tpl_vars['main'] == 'category_products'):  $this->assign('url_to', "category_products.php?cat=".($this->_tpl_vars['cat'])."&page=".($this->_tpl_vars['navpage']));  else:  $this->assign('url_to', "search.php?mode=search&page=".($this->_tpl_vars['navpage']));  endif; ?>

<tr class="TableHead">
 <td width="5">&nbsp;</td>
 <td><?php echo $this->_tpl_vars['lng']['lbl_products_more']; ?>
</td>
 <td width="7%" nowrap="nowrap"><?php if ($this->_tpl_vars['search_prefilled']['sort_field'] == 'productcode'):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/sort_pointer.tpl", 'smarty_include_vars' => array('dir' => $this->_tpl_vars['search_prefilled']['sort_direction'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>&nbsp;<?php endif; ?><a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['url_to'])) ? $this->_run_mod_handler('amp', true, $_tmp) : smarty_modifier_amp($_tmp)); ?>
&amp;sort=productcode&amp;sort_direction=<?php if ($this->_tpl_vars['search_prefilled']['sort_field'] == 'productcode'):  if ($this->_tpl_vars['search_prefilled']['sort_direction'] == 1): ?>0<?php else: ?>1<?php endif;  else:  echo $this->_tpl_vars['search_prefilled']['sort_direction'];  endif; ?>"><?php echo $this->_tpl_vars['lng']['lbl_sku']; ?>
</a></td>
 <td width="30%" nowrap="nowrap"><?php if ($this->_tpl_vars['search_prefilled']['sort_field'] == 'title'):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/sort_pointer.tpl", 'smarty_include_vars' => array('dir' => $this->_tpl_vars['search_prefilled']['sort_direction'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>&nbsp;<?php endif; ?><a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['url_to'])) ? $this->_run_mod_handler('amp', true, $_tmp) : smarty_modifier_amp($_tmp)); ?>
&amp;sort=title&amp;sort_direction=<?php if ($this->_tpl_vars['search_prefilled']['sort_field'] == 'title'):  if ($this->_tpl_vars['search_prefilled']['sort_direction'] == 1): ?>0<?php else: ?>1<?php endif;  else:  echo $this->_tpl_vars['search_prefilled']['sort_direction'];  endif; ?>"><?php echo $this->_tpl_vars['lng']['lbl_product']; ?>
</a></td>
<td width="10%"><?php echo $this->_tpl_vars['lng']['lbl_main_add_categories']; ?>
</td>
<?php if ($this->_tpl_vars['main'] == 'category_products'): ?>
 <td nowrap="nowrap"><?php if ($this->_tpl_vars['search_prefilled']['sort_field'] == 'orderby'):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/sort_pointer.tpl", 'smarty_include_vars' => array('dir' => $this->_tpl_vars['search_prefilled']['sort_direction'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>&nbsp;<?php endif; ?><a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['url_to'])) ? $this->_run_mod_handler('amp', true, $_tmp) : smarty_modifier_amp($_tmp)); ?>
&amp;sort=orderby&amp;sort_direction=<?php if ($this->_tpl_vars['search_prefilled']['sort_field'] == 'orderby'):  if ($this->_tpl_vars['search_prefilled']['sort_direction'] == 1): ?>0<?php else: ?>1<?php endif;  else:  echo $this->_tpl_vars['search_prefilled']['sort_direction'];  endif; ?>"><?php echo $this->_tpl_vars['lng']['lbl_pos']; ?>
</a></td>
<?php endif; ?>
 <td nowrap="nowrap"><?php if ($this->_tpl_vars['search_prefilled']['sort_field'] == 'price'):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/sort_pointer.tpl", 'smarty_include_vars' => array('dir' => $this->_tpl_vars['search_prefilled']['sort_direction'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>&nbsp;<?php endif; ?><a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['url_to'])) ? $this->_run_mod_handler('amp', true, $_tmp) : smarty_modifier_amp($_tmp)); ?>
&amp;sort=price&amp;sort_direction=<?php if ($this->_tpl_vars['search_prefilled']['sort_field'] == 'price'):  if ($this->_tpl_vars['search_prefilled']['sort_direction'] == 1): ?>0<?php else: ?>1<?php endif;  else:  echo $this->_tpl_vars['search_prefilled']['sort_direction'];  endif; ?>"><?php echo $this->_tpl_vars['lng']['lbl_price']; ?>
</a></td>
 <td><?php echo $this->_tpl_vars['lng']['lbl_list_price']; ?>
</td>
 <td><?php if ($this->_tpl_vars['search_prefilled']['sort_field'] == 'quantity'):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/sort_pointer.tpl", 'smarty_include_vars' => array('dir' => $this->_tpl_vars['search_prefilled']['sort_direction'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>&nbsp;<?php endif; ?><a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['url_to'])) ? $this->_run_mod_handler('amp', true, $_tmp) : smarty_modifier_amp($_tmp)); ?>
&amp;sort=quantity&amp;sort_direction=<?php if ($this->_tpl_vars['search_prefilled']['sort_field'] == 'quantity'):  if ($this->_tpl_vars['search_prefilled']['sort_direction'] == 1): ?>0<?php else: ?>1<?php endif;  else:  echo $this->_tpl_vars['search_prefilled']['sort_direction'];  endif; ?>"><?php echo $this->_tpl_vars['lng']['lbl_in_stock']; ?>
</a></td>
    <td nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_weight']; ?>
</td>
    <td><?php echo $this->_tpl_vars['lng']['lbl_shipping_freight']; ?>
</td>
    <td><?php echo $this->_tpl_vars['lng']['lbl_avail']; ?>
</td>
</tr>

<?php unset($this->_sections['prod']);
$this->_sections['prod']['name'] = 'prod';
$this->_sections['prod']['loop'] = is_array($_loop=$this->_tpl_vars['products']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['prod']['show'] = true;
$this->_sections['prod']['max'] = $this->_sections['prod']['loop'];
$this->_sections['prod']['step'] = 1;
$this->_sections['prod']['start'] = $this->_sections['prod']['step'] > 0 ? 0 : $this->_sections['prod']['loop']-1;
if ($this->_sections['prod']['show']) {
    $this->_sections['prod']['total'] = $this->_sections['prod']['loop'];
    if ($this->_sections['prod']['total'] == 0)
        $this->_sections['prod']['show'] = false;
} else
    $this->_sections['prod']['total'] = 0;
if ($this->_sections['prod']['show']):

            for ($this->_sections['prod']['index'] = $this->_sections['prod']['start'], $this->_sections['prod']['iteration'] = 1;
                 $this->_sections['prod']['iteration'] <= $this->_sections['prod']['total'];
                 $this->_sections['prod']['index'] += $this->_sections['prod']['step'], $this->_sections['prod']['iteration']++):
$this->_sections['prod']['rownum'] = $this->_sections['prod']['iteration'];
$this->_sections['prod']['index_prev'] = $this->_sections['prod']['index'] - $this->_sections['prod']['step'];
$this->_sections['prod']['index_next'] = $this->_sections['prod']['index'] + $this->_sections['prod']['step'];
$this->_sections['prod']['first']      = ($this->_sections['prod']['iteration'] == 1);
$this->_sections['prod']['last']       = ($this->_sections['prod']['iteration'] == $this->_sections['prod']['total']);
?>

<tr<?php echo smarty_function_cycle(array('values' => ', class="TableSubHead"'), $this);?>
>
 <td width="5"><input type="checkbox" name="productids[<?php echo $this->_tpl_vars['products'][$this->_sections['prod']['index']]['productid']; ?>
]" /></td>
 <td><a href="product_modify.php?productid=<?php echo $this->_tpl_vars['products'][$this->_sections['prod']['index']]['productid'];  if ($this->_tpl_vars['navpage']): ?>&page=<?php echo $this->_tpl_vars['navpage'];  endif; ?>"><?php echo $this->_tpl_vars['lng']['lbl_products_more']; ?>
</a></td>
 <td nowrap><input type="text" name="posted_data[<?php echo $this->_tpl_vars['products'][$this->_sections['prod']['index']]['productid']; ?>
][productcode]" value="<?php echo $this->_tpl_vars['products'][$this->_sections['prod']['index']]['productcode']; ?>
" size="20" /></td>
 <td><?php if ($this->_tpl_vars['products'][$this->_sections['prod']['index']]['main'] == 'Y' || $this->_tpl_vars['main'] != 'category_products'): ?><b><?php endif; ?><input type="text" size="45" name="posted_data[<?php echo $this->_tpl_vars['products'][$this->_sections['prod']['index']]['productid']; ?>
][product]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['products'][$this->_sections['prod']['index']]['product'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /><?php if ($this->_tpl_vars['products'][$this->_sections['prod']['index']]['main'] == 'Y' || $this->_tpl_vars['main'] != 'category_products'): ?></b><?php endif; ?></td>
<td align="center" nowrap="nowrap"><input type="text" size="5" name="posted_data[<?php echo $this->_tpl_vars['products'][$this->_sections['prod']['index']]['productid']; ?>
][main_category]" value="<?php echo $this->_tpl_vars['products'][$this->_sections['prod']['index']]['main_cat']; ?>
" />&nbsp;<input type="text" size="10" name="posted_data[<?php echo $this->_tpl_vars['products'][$this->_sections['prod']['index']]['productid']; ?>
][add_cats]" value="<?php echo $this->_tpl_vars['products'][$this->_sections['prod']['index']]['add_cats']; ?>
" /></td>
<?php if ($this->_tpl_vars['main'] == 'category_products'): ?>
 <td><input type="text" size="6" maxlength="10" name="posted_data[<?php echo $this->_tpl_vars['products'][$this->_sections['prod']['index']]['productid']; ?>
][orderby]" value="<?php echo $this->_tpl_vars['products'][$this->_sections['prod']['index']]['orderby']; ?>
" /></td>
<?php endif; ?>
 <td>
 <?php if ($this->_tpl_vars['products'][$this->_sections['prod']['index']]['product_type'] != 'C'): ?>
 <input type="text" size="6" maxlength="15" name="posted_data[<?php echo $this->_tpl_vars['products'][$this->_sections['prod']['index']]['productid']; ?>
][price]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['products'][$this->_sections['prod']['index']]['price'])) ? $this->_run_mod_handler('formatprice', true, $_tmp) : smarty_modifier_formatprice($_tmp)); ?>
"<?php if ($this->_tpl_vars['products'][$this->_sections['prod']['index']]['is_variants'] == 'Y'): ?> readonly="readonly" onclick="javascript: pvAlert(this);"<?php endif; ?> />
 <?php endif; ?>
 </td>
 <td>
 <?php if ($this->_tpl_vars['products'][$this->_sections['prod']['index']]['product_type'] != 'C'): ?>
 <input type="text" size="6" maxlength="15" name="posted_data[<?php echo $this->_tpl_vars['products'][$this->_sections['prod']['index']]['productid']; ?>
][list_price]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['products'][$this->_sections['prod']['index']]['list_price'])) ? $this->_run_mod_handler('formatprice', true, $_tmp) : smarty_modifier_formatprice($_tmp)); ?>
"<?php if ($this->_tpl_vars['products'][$this->_sections['prod']['index']]['is_variants'] == 'Y'): ?> readonly="readonly" onclick="javascript: pvAlert(this);"<?php endif; ?> />
 <?php endif; ?>
 </td>
 <td align="center">
<?php if ($this->_tpl_vars['products'][$this->_sections['prod']['index']]['product_type'] != 'C'): ?>
<input type="text" size="6" maxlength="10" name="posted_data[<?php echo $this->_tpl_vars['products'][$this->_sections['prod']['index']]['productid']; ?>
][avail]" value="<?php echo $this->_tpl_vars['products'][$this->_sections['prod']['index']]['avail']; ?>
"<?php if ($this->_tpl_vars['products'][$this->_sections['prod']['index']]['is_variants'] == 'Y'): ?> readonly="readonly" onclick="javascript: pvAlert(this);"<?php endif; ?> />
<?php endif; ?>
 </td>
 <td>
 <?php if ($this->_tpl_vars['products'][$this->_sections['prod']['index']]['product_type'] != 'C'): ?>
 <input type="text" name="posted_data[<?php echo $this->_tpl_vars['products'][$this->_sections['prod']['index']]['productid']; ?>
][weight]" size="6" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['products'][$this->_sections['prod']['index']]['weight'])) ? $this->_run_mod_handler('formatprice', true, $_tmp) : smarty_modifier_formatprice($_tmp)))) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['zero']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['zero'])); ?>
" <?php if ($this->_tpl_vars['products'][$this->_sections['prod']['index']]['is_variants'] == 'Y'): ?> readonly="readonly" onclick="javascript: pvAlert(this);"<?php endif; ?>/>
 <?php endif; ?>
 </td>
 <td>
 <?php if ($this->_tpl_vars['products'][$this->_sections['prod']['index']]['product_type'] != 'C'): ?>
 <input type="text" name="posted_data[<?php echo $this->_tpl_vars['products'][$this->_sections['prod']['index']]['productid']; ?>
][shipping_freight]" size="6" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['products'][$this->_sections['prod']['index']]['shipping_freight'])) ? $this->_run_mod_handler('formatprice', true, $_tmp) : smarty_modifier_formatprice($_tmp)))) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['zero']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['zero'])); ?>
" /> 
 <?php endif; ?>
 </td>
 <td>
  <select name="posted_data[<?php echo $this->_tpl_vars['products'][$this->_sections['prod']['index']]['productid']; ?>
][forsale]" width="10">
   <option value="Y"<?php if ($this->_tpl_vars['products'][$this->_sections['prod']['index']]['forsale'] == 'Y'): ?> selected="selected"<?php endif; ?>>Available</option>
   <option value="H"<?php if ($this->_tpl_vars['products'][$this->_sections['prod']['index']]['forsale'] == 'H'): ?> selected="selected"<?php endif; ?>>Hidden</option>
   <option value="N"<?php if ($this->_tpl_vars['products'][$this->_sections['prod']['index']]['forsale'] != 'Y' && $this->_tpl_vars['products'][$this->_sections['prod']['index']]['forsale'] != 'H' && ( $this->_tpl_vars['products'][$this->_sections['prod']['index']]['forsale'] != 'B' || ! $this->_tpl_vars['active_modules']['Product_Configurator'] )): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_disabled']; ?>
</option>
  <?php if ($this->_tpl_vars['active_modules']['Product_Configurator']): ?>
   <option value="B"<?php if ($this->_tpl_vars['products'][$this->_sections['prod']['index']]['forsale'] == 'B'): ?> selected="selected"<?php endif; ?>>Bundled</option>
  <?php endif; ?>
  </select>
 </td>

</tr>

<?php endfor; endif; ?>

</table>
<?php endif; ?>
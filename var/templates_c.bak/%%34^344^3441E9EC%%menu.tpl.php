<?php /* Smarty version 2.6.12, created on 2011-10-11 05:54:16
         compiled from provider/menu.tpl */ ?>
<?php func_load_lang($this, "provider/menu.tpl","lbl_manufacturers,lbl_brands,lbl_wish_lists,lbl_categories,lbl_search_products,lbl_add_new_product,lbl_product_modify,lbl_delete_product,lbl_import_export,lbl_update_inventory,lbl_extra_fields,lbl_products,lbl_summary,lbl_destination_zones,lbl_shipping_charges,lbl_shipping_markups,lbl_tax_rates,lbl_discounts,lbl_coupons,lbl_files,lbl_inventory"); ?><?php ob_start();  if ($this->_tpl_vars['active_modules']['Manufacturers'] && $this->_tpl_vars['active_modules']['Simple_Mode'] == ""): ?>
<a href="<?php echo $this->_tpl_vars['catalogs']['provider']; ?>
/manufacturers.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_manufacturers']; ?>
</a><br />
<?php endif;  if ($this->_tpl_vars['active_modules']['Brands']): ?>
<a href="<?php echo $this->_tpl_vars['catalogs']['provider']; ?>
/brands.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_brands']; ?>
</a><br />
<?php endif;  if ($this->_tpl_vars['active_modules']['Wishlist'] && $this->_tpl_vars['active_modules']['Simple_Mode'] == ""): ?>
<a href="<?php echo $this->_tpl_vars['catalogs']['provider']; ?>
/wishlists.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_wish_lists']; ?>
</a><br />
<?php endif;  if ($this->_tpl_vars['active_modules']['Feature_Comparison'] != "" && $this->_tpl_vars['active_modules']['Simple_Mode'] == ''):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Feature_Comparison/admin_menu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  if ($this->_tpl_vars['active_modules']['Product_Configurator'] != ""):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Product_Configurator/pconf_menu_provider.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><br />
<?php endif;  if ($this->_tpl_vars['category_staff'] == 'Y'): ?><a href="<?php echo $this->_tpl_vars['catalogs']['provider']; ?>
/categories.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_categories']; ?>
</a><br /><?php endif; ?>
<a href="<?php echo $this->_tpl_vars['catalogs']['provider']; ?>
/search.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_search_products']; ?>
</a><br />
<a href="<?php echo $this->_tpl_vars['catalogs']['provider']; ?>
/product_modify.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_add_new_product']; ?>
</a><br />
<a href="<?php echo $this->_tpl_vars['catalogs']['provider']; ?>
/search.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_product_modify']; ?>
</a><br />
<a href="<?php echo $this->_tpl_vars['catalogs']['provider']; ?>
/search.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_delete_product']; ?>
</a><br />
<?php if ($this->_tpl_vars['active_modules']['Simple_Mode'] == ''): ?>
<a href="<?php echo $this->_tpl_vars['catalogs']['provider']; ?>
/import.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_import_export']; ?>
</a><br />
<?php endif; ?>
<a href="<?php echo $this->_tpl_vars['catalogs']['provider']; ?>
/inv_update.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_update_inventory']; ?>
</a><br />
<?php if ($this->_tpl_vars['active_modules']['Extra_Fields'] != ""): ?>
<a href="<?php echo $this->_tpl_vars['catalogs']['provider']; ?>
/extra_fields.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_extra_fields']; ?>
</a><br />
<?php endif;  $this->_smarty_vars['capture']['menu'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "menu.tpl", 'smarty_include_vars' => array('dingbats' => "dingbats_products.gif",'menu_title' => $this->_tpl_vars['lng']['lbl_products'],'menu_content' => $this->_smarty_vars['capture']['menu'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<br />
<?php ob_start();  if ($this->_tpl_vars['active_modules']['Simple_Mode'] == ""): ?>
<a href="<?php echo $this->_tpl_vars['catalogs']['provider']; ?>
/general.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_summary']; ?>
</a><br />
<?php endif; ?>
<a href="<?php echo $this->_tpl_vars['catalogs']['provider']; ?>
/zones.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_destination_zones']; ?>
</a><br />
<?php if ($this->_tpl_vars['config']['Shipping']['disable_shipping'] != 'Y'): ?>
<a href="<?php echo $this->_tpl_vars['catalogs']['provider']; ?>
/shipping_rates.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_shipping_charges']; ?>
</a><br />
<?php if ($this->_tpl_vars['config']['Shipping']['realtime_shipping'] == 'Y'): ?>
<a href="<?php echo $this->_tpl_vars['catalogs']['provider']; ?>
/shipping_rates.php?type=R" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_shipping_markups']; ?>
</a><br />
<?php endif;  endif;  if ($this->_tpl_vars['active_modules']['Simple_Mode'] == ""): ?>
<a href="<?php echo $this->_tpl_vars['catalogs']['provider']; ?>
/taxes.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_tax_rates']; ?>
</a><br />
<?php endif; ?>
<a href="<?php echo $this->_tpl_vars['catalogs']['provider']; ?>
/discounts.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_discounts']; ?>
</a><br />
<?php if ($this->_tpl_vars['active_modules']['Discount_Coupons'] != ""): ?>
<a href="<?php echo $this->_tpl_vars['catalogs']['provider']; ?>
/coupons.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_coupons']; ?>
</a><br />
<?php endif;  if ($this->_tpl_vars['active_modules']['Special_Offers'] != ""):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Special_Offers/menu_provider.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><br />
<?php endif;  if ($this->_tpl_vars['active_modules']['Simple_Mode'] == ""): ?>
<a href="<?php echo $this->_tpl_vars['catalogs']['provider']; ?>
/file_manage.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_files']; ?>
</a><br />
<?php endif;  $this->_smarty_vars['capture']['menu'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "menu.tpl", 'smarty_include_vars' => array('dingbats' => "dingbats_products.gif",'menu_title' => $this->_tpl_vars['lng']['lbl_inventory'],'menu_content' => $this->_smarty_vars['capture']['menu'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
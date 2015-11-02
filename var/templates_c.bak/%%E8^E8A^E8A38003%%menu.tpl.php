<?php /* Smarty version 2.6.12, created on 2011-10-11 05:37:57
         compiled from admin/menu.tpl */ ?>
<?php func_load_lang($this, "admin/menu.tpl","lbl_users,lbl_categories,lbl_sf,lbl_manufacturers,lbl_brands,lbl_wish_lists,lbl_admin_menu_products,lbl_sf,lbl_product_reports,lbl_orders,lbl_order_reports,lbl_news_management,lbl_sf,lbl_statistics,lbl_sf,lbl_shipping_methods,lbl_tracking_links,lbl_taxing_system,lbl_edit_ratings,lbl_countries,lbl_states,lbl_ups_online_tools,lbl_update_inventory,lbl_sf,lbl_management"); ?><?php ob_start(); ?>
<a href="<?php echo $this->_tpl_vars['catalogs']['admin']; ?>
/users.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_users']; ?>
</a><br />
<a href="<?php echo $this->_tpl_vars['catalogs']['admin']; ?>
/categories.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_categories'];  if ($this->_tpl_vars['active_modules']['Multiple_Storefronts']): ?> <?php echo $this->_tpl_vars['lng']['lbl_sf'];  endif; ?></a><br />
<?php if ($this->_tpl_vars['active_modules']['Manufacturers']): ?>
<a href="<?php echo $this->_tpl_vars['catalogs']['admin']; ?>
/manufacturers.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_manufacturers']; ?>
</a><br />
<?php endif;  if ($this->_tpl_vars['active_modules']['Brands']): ?>
<a href="<?php echo $this->_tpl_vars['catalogs']['admin']; ?>
/brands.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_brands']; ?>
</a><br />
<?php endif;  if ($this->_tpl_vars['active_modules']['Wishlist']): ?>
<a href="<?php echo $this->_tpl_vars['catalogs']['admin']; ?>
/wishlists.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_wish_lists']; ?>
</a><br />
<?php endif;  if ($this->_tpl_vars['active_modules']['Simple_Mode'] == ""): ?><a href="<?php echo $this->_tpl_vars['catalogs']['admin']; ?>
/search.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_admin_menu_products'];  if ($this->_tpl_vars['active_modules']['Multiple_Storefronts']): ?> <?php echo $this->_tpl_vars['lng']['lbl_sf'];  endif; ?></a><br /><?php endif; ?>
<a href="<?php echo $this->_tpl_vars['catalogs']['admin']; ?>
/product_reports.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_product_reports']; ?>
</a><br />
<a href="<?php echo $this->_tpl_vars['catalogs']['admin']; ?>
/orders.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_orders']; ?>
</a><br />
<a href="<?php echo $this->_tpl_vars['catalogs']['admin']; ?>
/order_reports.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_order_reports']; ?>
</a><br />
<?php if ($this->_tpl_vars['active_modules']['News_Management']): ?>
<a href="<?php echo $this->_tpl_vars['catalogs']['admin']; ?>
/news.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_news_management'];  if ($this->_tpl_vars['active_modules']['Multiple_Storefronts']): ?> <?php echo $this->_tpl_vars['lng']['lbl_sf'];  endif; ?></a><br />
<?php endif; ?>
<a href="<?php echo $this->_tpl_vars['catalogs']['admin']; ?>
/statistics.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_statistics'];  if ($this->_tpl_vars['active_modules']['Multiple_Storefronts']): ?> <?php echo $this->_tpl_vars['lng']['lbl_sf'];  endif; ?></a><br />
<a href="<?php echo $this->_tpl_vars['catalogs']['admin']; ?>
/shipping.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_shipping_methods']; ?>
</a><br />
<a href="<?php echo $this->_tpl_vars['catalogs']['admin']; ?>
/track_links.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_tracking_links']; ?>
</a><br />
<a href="<?php echo $this->_tpl_vars['catalogs']['admin']; ?>
/taxes.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_taxing_system']; ?>
</a><br />
<?php if ($this->_tpl_vars['active_modules']['Customer_Reviews'] != ""): ?>
<a href="<?php echo $this->_tpl_vars['catalogs']['admin']; ?>
/ratings_edit.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_edit_ratings']; ?>
</a><br />
<?php endif; ?>
<a href="<?php echo $this->_tpl_vars['catalogs']['admin']; ?>
/countries.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_countries']; ?>
</a><br />
<a href="<?php echo $this->_tpl_vars['catalogs']['admin']; ?>
/states.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_states']; ?>
</a><br />
<?php if ($this->_tpl_vars['active_modules']['Stop_List'] != ""):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Stop_List/stop_list_menu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><br />
<?php endif;  if ($this->_tpl_vars['active_modules']['Benchmark'] != ""):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Benchmark/menu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><br />
<?php endif;  if ($this->_tpl_vars['active_modules']['Feature_Comparison'] != ""):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Feature_Comparison/admin_menu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  if ($this->_tpl_vars['active_modules']['RMA'] != ""):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/RMA/admin_menu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><br />
<?php endif;  if ($this->_tpl_vars['active_modules']['Gift_Certificates'] != ""):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Gift_Certificates/gc_admin_menu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><br />
<?php endif;  if ($this->_tpl_vars['active_modules']['Subscriptions'] != ""):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Subscriptions/subscriptions_menu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><br />
<?php endif;  if ($this->_tpl_vars['active_modules']['UPS_OnLine_Tools'] != ""): ?>
<a href="<?php echo $this->_tpl_vars['catalogs']['admin']; ?>
/ups.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_ups_online_tools']; ?>
</a><br />
<?php endif;  if ($this->_tpl_vars['active_modules']['Survey'] != ""):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Survey/admin_menu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
<a href="<?php echo $this->_tpl_vars['catalogs']['admin']; ?>
/inv_update_ex.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_update_inventory'];  if ($this->_tpl_vars['active_modules']['Multiple_Storefronts']): ?> <?php echo $this->_tpl_vars['lng']['lbl_sf'];  endif; ?></a><br />
<?php $this->_smarty_vars['capture']['menu'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "menu.tpl", 'smarty_include_vars' => array('dingbats' => "dingbats_categorie.gif",'menu_title' => $this->_tpl_vars['lng']['lbl_management'],'menu_content' => $this->_smarty_vars['capture']['menu'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
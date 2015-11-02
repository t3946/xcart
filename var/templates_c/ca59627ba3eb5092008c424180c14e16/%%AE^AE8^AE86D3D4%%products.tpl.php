<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:18
         compiled from customer/main/products.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'customer/main/products.tpl', 1, false),array('modifier', 'amp', 'customer/main/products.tpl', 44, false),array('modifier', 'truncate', 'customer/main/products.tpl', 45, false),array('modifier', 'escape', 'customer/main/products.tpl', 52, false),array('modifier', 'replace', 'customer/main/products.tpl', 106, false),)), $this); ?>
<?php func_load_lang($this, "customer/main/products.tpl","lbl_sku,lbl_enter_your_price,lbl_enter_your_price_note,lbl_in_stock_top,lbl_out_stock"); ?><?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "customer/main/products.tpl"), $this); endif;  if ($this->_tpl_vars['products']): ?>
  <?php if ($this->_tpl_vars['active_modules']['Feature_Comparison'] && $this->_tpl_vars['products_has_fclasses'] && ! $this->_tpl_vars['featured']): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Feature_Comparison/compare_selected_button.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <?php endif; ?>
  <div class="content-secondary">
    <ul data-role="listview" data-type="products-list" data-divider-theme="c">
      <?php if ($this->_tpl_vars['title']): ?>
        <li data-role="list-divider" role="heading">
          <h2><?php echo $this->_tpl_vars['title']; ?>
</h2>
        </li>
      <?php endif; ?>
      <?php $_from = $this->_tpl_vars['products']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['product']):
?>

<?php if ($this->_tpl_vars['product']['new_notify_in_stock_price'] != ""): ?>
        <?php $this->assign('current_price', $this->_tpl_vars['product']['new_notify_in_stock_price']);  else: ?>
        <?php if ($this->_tpl_vars['product']['map_price'] > $this->_tpl_vars['product']['taxed_price']): ?>
                <?php $this->assign('current_price', $this->_tpl_vars['product']['map_price']); ?>
        <?php else: ?>
                <?php $this->assign('current_price', $this->_tpl_vars['product']['taxed_price']); ?>
        <?php endif;  endif; ?>

        <li>
          <a href="<?php echo $this->_tpl_vars['current_location']; ?>
/product.php?productid=<?php echo $this->_tpl_vars['product']['productid']; ?>
">
            <span class="product-thumbnail">
              <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "product_thumbnail.tpl", 'smarty_include_vars' => array('productid' => $this->_tpl_vars['product']['productid'],'product' => $this->_tpl_vars['product']['product'],'tmbn_url' => $this->_tpl_vars['product']['tmbn_url'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
              <img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" class="leveler" alt="" />
              <span class="labels">
                <?php if ($this->_tpl_vars['active_modules']['On_Sale']): ?>
                  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/On_Sale/on_sale_icon_products_list.tpl", 'smarty_include_vars' => array('product' => $this->_tpl_vars['product'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                <?php endif; ?>
                <?php if ($this->_tpl_vars['active_modules']['Special_Offers'] && $this->_tpl_vars['product']['have_offers']): ?>
                  <span class="so-thumb" onclick="javascript: $.mobile.changePage('offers.php?mode=product&amp;productid=<?php echo $this->_tpl_vars['product']['productid']; ?>
');"></span>
                <?php endif; ?>
              </span>
            </span>
            <span class="product-details">
              <span class="ui-li-heading">
                <span class="list"><?php echo ((is_array($_tmp=$this->_tpl_vars['product']['product'])) ? $this->_run_mod_handler('amp', true, $_tmp) : smarty_modifier_amp($_tmp)); ?>
</span>
                <span class="grid"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['product']['product'])) ? $this->_run_mod_handler('amp', true, $_tmp) : smarty_modifier_amp($_tmp)))) ? $this->_run_mod_handler('truncate', true, $_tmp, 79, '...') : smarty_modifier_truncate($_tmp, 79, '...')); ?>
</span>
                <?php if ($this->_tpl_vars['active_modules']['New_Arrivals']): ?>
                  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/New_Arrivals/new_arrivals_show_date.tpl", 'smarty_include_vars' => array('product' => $this->_tpl_vars['product'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                <?php endif; ?>
              </span>
              <span class="ui-li-desc">
                <?php if ($this->_tpl_vars['config']['Appearance']['display_productcode_in_list'] == 'Y' && $this->_tpl_vars['product']['productcode']): ?>
                  <span class="sku"><?php echo $this->_tpl_vars['lng']['lbl_sku']; ?>
: <?php echo ((is_array($_tmp=$this->_tpl_vars['product']['productcode'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span>
                <?php endif; ?>
                <?php if ($this->_tpl_vars['product']['product_type'] != 'C'): ?>
                  <?php if ($this->_tpl_vars['product']['appearance']['is_auction']): ?>
                    <span class="price"><?php echo $this->_tpl_vars['lng']['lbl_enter_your_price']; ?>
</span><br />
                    <?php echo $this->_tpl_vars['lng']['lbl_enter_your_price_note']; ?>

                  <?php else: ?>

                      <span class="price">
                        Price: <span class="price-value"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['current_price'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></span>
                      </span>

<span class="sku">
            <?php if ($this->_tpl_vars['product']['avail'] > 0 || $this->_tpl_vars['config']['General']['unlimited_products'] == 'Y'): ?>
              <?php echo $this->_tpl_vars['lng']['lbl_in_stock_top']; ?>

            <?php else: ?>
              <?php echo $this->_tpl_vars['lng']['lbl_out_stock']; ?>

            <?php endif; ?>
</span>

                      <?php if ($this->_tpl_vars['product']['taxes']): ?>
                        <span class="taxes"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/taxed_price.tpl", 'smarty_include_vars' => array('taxes' => $this->_tpl_vars['product']['taxes'],'is_subtax' => true)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></span>
                      <?php endif; ?>
                    <?php if ($this->_tpl_vars['active_modules']['Special_Offers'] && $this->_tpl_vars['product']['use_special_price']): ?>
                      <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Special_Offers/customer/product_special_price.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                    <?php endif; ?>
                  <?php endif; ?>
                <?php endif; ?>
              </span>
            </span>
          </a>
          <?php if ($this->_tpl_vars['active_modules']['Feature_Comparison'] && $this->_tpl_vars['product']['fclassid'] > 0 && ! $this->_tpl_vars['featured']): ?>
            <?php ob_start();
$_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Feature_Comparison/compare_checkbox.tpl", 'smarty_include_vars' => array('id' => $this->_tpl_vars['product']['productid'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
$this->assign('fcomp_checkbox', ob_get_contents()); ob_end_clean();
 ?>
            <?php echo ((is_array($_tmp=$this->_tpl_vars['fcomp_checkbox'])) ? $this->_run_mod_handler('replace', true, $_tmp, "fcomp-checkbox-box", "fcomp-checkbox-box left-block") : smarty_modifier_replace($_tmp, "fcomp-checkbox-box", "fcomp-checkbox-box left-block")); ?>

            <div class="clearing"></div>
          <?php endif; ?>
        </li>
      <?php endforeach; endif; unset($_from); ?>
    </ul>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/infinite_products.tpl", 'smarty_include_vars' => array('show_next_products' => 'N')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

    <div class="clearing"></div>
  </div>

  <?php if ($this->_tpl_vars['active_modules']['Feature_Comparison'] && $this->_tpl_vars['products_has_fclasses'] && ! $this->_tpl_vars['featured']): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Feature_Comparison/compare_selected_button.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <?php endif; ?>

<?php endif;  if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "customer/main/products.tpl"), $this); endif; ?>
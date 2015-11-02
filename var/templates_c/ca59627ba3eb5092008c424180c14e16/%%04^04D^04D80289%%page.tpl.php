<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:06
         compiled from customer/page.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'customer/page.tpl', 1, false),array('function', 'func_mobile_get_page_title', 'customer/page.tpl', 35, false),array('modifier', 'amp', 'customer/page.tpl', 17, false),array('modifier', 'default', 'customer/page.tpl', 17, false),array('modifier', 'count', 'customer/page.tpl', 39, false),)), $this); ?>
<?php func_load_lang($this, "customer/page.tpl","lbl_back,lbl_back,lbl_manufacturers,lbl_products,lbl_categories"); ?><?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "customer/page.tpl"), $this); endif;  if ($this->_tpl_vars['mobile_mode'] == 'get_detailed_images'): ?>
  <?php if ($this->_tpl_vars['product'] && $this->_tpl_vars['images'] != ''): ?>
    <!-- dialog #detailed-images-<?php echo $this->_tpl_vars['product']['productid']; ?>
 -->

    <div data-role="page" data-url="<?php echo $this->_tpl_vars['php_url']['url'];  if ($this->_tpl_vars['php_url']['query_string']): ?>?<?php echo $this->_tpl_vars['php_url']['query_string'];  endif; ?>" data-add-back-btn="true" data-dom-cache="true" id="detailed-images-<?php echo $this->_tpl_vars['product']['productid']; ?>
" class="gallery-page">
      <div data-role="header" data-inline="true">
        <a href="#" class="custom-arrow" data-role="button" data-rel="back" data-icon="custom-arrow-l" data-iconpos="notext"><?php echo $this->_tpl_vars['lng']['lbl_back']; ?>
</a>
        <h1><?php echo $this->_tpl_vars['product']['product']; ?>
</h1>
      </div>
      <div data-role="content">
        <ul class="gallery">
          <?php $_from = $this->_tpl_vars['images']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i']):
?>
            <li><a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['i']['tmbn_url'])) ? $this->_run_mod_handler('amp', true, $_tmp) : smarty_modifier_amp($_tmp)); ?>
" rel="external"><img src="<?php echo ((is_array($_tmp=$this->_tpl_vars['i']['tmbn_url'])) ? $this->_run_mod_handler('amp', true, $_tmp) : smarty_modifier_amp($_tmp)); ?>
" alt="<?php echo ((is_array($_tmp=@$this->_tpl_vars['i']['alt'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['product']['product']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['product']['product'])); ?>
" /></a></li>
              <?php endforeach; endif; unset($_from); ?>
        </ul>
      </div>
    </div>
    <!-- dialog #detailed-images-<?php echo $this->_tpl_vars['product']['productid']; ?>
 -->
  <?php endif;  else: ?>
  <!-- page <?php echo $this->_tpl_vars['main']; ?>
 -->
  <div data-role="<?php echo ((is_array($_tmp=@$this->_tpl_vars['data_role'])) ? $this->_run_mod_handler('default', true, $_tmp, 'page') : smarty_modifier_default($_tmp, 'page')); ?>
"  class="page-holder<?php if ($this->_tpl_vars['page_container_class']): ?> <?php echo $this->_tpl_vars['page_container_class'];  endif;  if ($this->_tpl_vars['main'] == 'product'): ?> product-details-page<?php endif;  if ($this->_tpl_vars['main'] == 'cart' && ! $this->_tpl_vars['cart_empty']): ?> cart-page<?php endif;  if ($this->_tpl_vars['container_classes']): ?> <?php $_from = $this->_tpl_vars['container_classes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['c']):
 echo $this->_tpl_vars['c']; ?>
 <?php endforeach; endif; unset($_from);  endif; ?>" data-dom-cache="false" data-add-back-btn="true" data-url="<?php echo $this->_tpl_vars['data_url']; ?>
">
    <?php if (! $this->_tpl_vars['no_nav']): ?>
      <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/top_navigation.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php endif; ?>
    <?php if (! $this->_tpl_vars['no_header']): ?>
      <div data-role="header" data-inline="true">
        <?php if (! $this->_tpl_vars['no_nav']): ?>
          <a href="#" class="custom-arrow back-title-button" data-role="button" data-rel="back" data-icon="custom-arrow-l" data-iconpos="notext" data-theme="b" data-shadow="false" data-corners="false"><?php echo $this->_tpl_vars['lng']['lbl_back']; ?>
</a>
        <?php endif; ?>
        <h1><?php echo func_mobile_get_page_title(array(), $this);?>
</h1>
        <?php if ($this->_tpl_vars['main'] == 'catalog' && ( ( ( $this->_tpl_vars['cat_products'] || ( $this->_tpl_vars['f_products'] && $this->_tpl_vars['xcart_mobile_config']['cat_featured'] == 'Y' ) ) && $this->_tpl_vars['current_category']['subcategory_count'] > 0 ) || ( ! $this->_tpl_vars['cat'] && $this->_tpl_vars['manufacturers_menu'] && $this->_tpl_vars['mobile_mode'] == 'subcategories' ) )): ?>
          <?php $this->assign('show_subnav', true); ?>
          <?php if ($this->_tpl_vars['cat']): ?>
            <?php $this->assign('products_count', count(((is_array($_tmp=@$this->_tpl_vars['current_category']['top_product_count'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['f_products']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['f_products'])))); ?>
          <?php endif; ?>
        <?php endif; ?>
        <?php if (! $this->_tpl_vars['show_subnav']): ?>
          <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/search_sort_by.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php endif; ?>
      </div>
    <?php endif; ?>
    <div data-role="content">

      <?php if ($this->_tpl_vars['active_modules']['EU_Cookie_Law'] && $this->_tpl_vars['is_ajax_request'] != 'Y'): ?>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/EU_Cookie_Law/info_panel.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
      <?php endif; ?>
      <?php if (( $this->_tpl_vars['main'] == 'cart' && ! $this->_tpl_vars['cart_empty'] ) || $this->_tpl_vars['main'] == 'checkout' || $this->_tpl_vars['main'] == 'fast_lane_checkout'): ?>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/".($this->_tpl_vars['checkout_module'])."/content.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
      <?php else: ?>
        <?php if ($this->_tpl_vars['show_subnav']): ?>
          <div class="tabs-menu">
            <div data-role="navbar">
              <ul>
                <?php ob_start(); ?>
                  <li><a data-theme="a" data-mini="false" href="<?php echo $this->_tpl_vars['current_location']; ?>
/home.php?<?php if ($this->_tpl_vars['cat']): ?>cat=<?php echo $this->_tpl_vars['cat'];  else: ?>mobile_mode=subcategories<?php endif; ?>"<?php if (! $this->_tpl_vars['list_categories']): ?> class="ui-btn-active ui-state-persist"<?php endif; ?> data-prefetch><?php if (! $this->_tpl_vars['cat']):  echo $this->_tpl_vars['lng']['lbl_manufacturers'];  else:  echo $this->_tpl_vars['lng']['lbl_products']; ?>
 <span>(<?php echo $this->_tpl_vars['products_count']; ?>
)</span><?php endif; ?></a></li>
                <?php $this->_smarty_vars['capture']['prods_mans_point'] = ob_get_contents(); ob_end_clean(); ?>
                <?php ob_start(); ?>
                  <li><a data-theme="a" data-mini="false" href="<?php echo $this->_tpl_vars['current_location']; ?>
/home.php?<?php if ($this->_tpl_vars['cat']): ?>cat=<?php echo $this->_tpl_vars['cat'];  else: ?>mobile_mode=subcategories<?php endif; ?>&list_categories=1"<?php if ($this->_tpl_vars['list_categories']): ?> class="ui-btn-active ui-state-persist"<?php endif; ?> data-prefetch><?php echo $this->_tpl_vars['lng']['lbl_categories'];  if ($this->_tpl_vars['cat']): ?> <span>(<?php echo $this->_tpl_vars['current_category']['subcategory_count']; ?>
)</span><?php endif; ?></a></li>
                <?php $this->_smarty_vars['capture']['cats_list_point'] = ob_get_contents(); ob_end_clean(); ?>
                <?php if (! $this->_tpl_vars['cat'] && $this->_tpl_vars['manufacturers_menu']): ?>
                  <?php echo $this->_smarty_vars['capture']['cats_list_point']; ?>

                  <?php echo $this->_smarty_vars['capture']['prods_mans_point']; ?>

                <?php else: ?>
                  <?php echo $this->_smarty_vars['capture']['prods_mans_point']; ?>

                  <?php echo $this->_smarty_vars['capture']['cats_list_point']; ?>

                <?php endif; ?>
              </ul>
            </div>
          </div>
        <?php endif; ?>
        <?php if ($this->_tpl_vars['page_tabs'] != ''): ?>
          <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/top_links.tpl", 'smarty_include_vars' => array('tabs' => $this->_tpl_vars['page_tabs'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php endif; ?>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/bread_crumbs.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/dialog_message.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php if ($this->_tpl_vars['active_modules']['Special_Offers'] && ( $this->_tpl_vars['main'] == 'catalog' || $this->_tpl_vars['main'] == 'product' || $this->_tpl_vars['main'] == 'manufacturer_products' || $this->_tpl_vars['main'] == 'search' || $this->_tpl_vars['main'] == 'advanced_search' ) && $this->_tpl_vars['mobile_mode'] != 'more' && $this->_tpl_vars['mobile_mode'] != 'search'): ?>
          <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Special_Offers/customer/new_offers_message.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php endif; ?>
        <?php if ($this->_tpl_vars['mobile_mode']): ?>
          <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/".($this->_tpl_vars['mobile_mode']).".tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php else: ?>
          <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/home_main.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php endif; ?>
      <?php endif; ?>
    </div>
    <?php if (! $this->_tpl_vars['no_nav']): ?>
      <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/switch_view.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
      <div data-role="footer" data-inline="true">
        <h4 class="footer">
          <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/prnotice.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><br />
          <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "copyright.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        </h4>
      </div>
    <?php endif; ?>
  </div>
  <!-- /page <?php echo $this->_tpl_vars['main']; ?>
 -->
<?php endif;  if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "customer/page.tpl"), $this); endif; ?>
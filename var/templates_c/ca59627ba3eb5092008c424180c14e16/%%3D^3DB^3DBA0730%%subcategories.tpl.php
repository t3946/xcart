<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:18
         compiled from customer/main/subcategories.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'customer/main/subcategories.tpl', 1, false),array('modifier', 'is_array', 'customer/main/subcategories.tpl', 20, false),)), $this); ?>
<?php func_load_lang($this, "customer/main/subcategories.tpl","lbl_products,lbl_featured_products,lbl_products"); ?><?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "customer/main/subcategories.tpl"), $this); endif;  if ($this->_tpl_vars['active_modules']['Special_Offers']): ?>
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Special_Offers/customer/category_offers_short_list.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  if ($this->_tpl_vars['categories'] || ! $this->_tpl_vars['show_subnav']): ?>
  <?php if (! $this->_tpl_vars['cat']): ?>
    <?php $this->assign('mob_categories', $this->_tpl_vars['categories']); ?>
  <?php elseif ($this->_tpl_vars['cat'] && is_array($this->_tpl_vars['categories'])): ?>
    <?php $this->assign('mob_categories', $this->_tpl_vars['subcategories']); ?>
  <?php endif; ?>
  <?php if ($this->_tpl_vars['mob_categories']): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/subcategories_list.tpl", 'smarty_include_vars' => array('categories' => $this->_tpl_vars['mob_categories'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <?php endif;  endif;  if (! $this->_tpl_vars['list_categories']): ?>
  <?php if (! $this->_tpl_vars['cat'] && $this->_tpl_vars['manufacturers_menu']): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Manufacturers/menu_manufacturers.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <?php elseif ($this->_tpl_vars['cat'] != 0): ?>
    <?php if ($this->_tpl_vars['active_modules']['Bestsellers'] && $this->_tpl_vars['xcart_mobile_config']['cat_bestsellers'] == 'Y' && $this->_tpl_vars['bestsellers']): ?>
      <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/bestsellers.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php endif; ?>
    <?php if ($this->_tpl_vars['active_modules']['New_Arrivals']): ?>
      <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/New_Arrivals/new_arrivals.tpl", 'smarty_include_vars' => array('new_arrivals_main' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
      <?php if ($this->_tpl_vars['new_arrivals']): ?>
        <?php $this->assign('title', $this->_tpl_vars['lng']['lbl_products']); ?>
      <?php endif; ?>
    <?php endif; ?>
    <?php if ($this->_tpl_vars['f_products'] && $this->_tpl_vars['xcart_mobile_config']['cat_featured'] == 'Y'): ?>
      <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/products.tpl", 'smarty_include_vars' => array('products' => $this->_tpl_vars['f_products'],'title' => $this->_tpl_vars['lng']['lbl_featured_products'],'featured' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
      <?php if ($this->_tpl_vars['f_products']): ?>
        <?php $this->assign('title', $this->_tpl_vars['lng']['lbl_products']); ?>
      <?php endif; ?>
    <?php endif; ?>
    <?php if ($this->_tpl_vars['products']): ?>
      
      <?php if ($this->_tpl_vars['total_pages'] > 2 && $this->_tpl_vars['title']): ?>
        <div class="inner-gap"></div>
      <?php endif; ?>
      
      <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/navigation.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
      <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/products.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['title'],'products' => $this->_tpl_vars['products'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
      <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/navigation.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php endif; ?>
  <?php endif;  endif; ?>

<?php if ($this->_tpl_vars['categories'] || ! $this->_tpl_vars['show_subnav']): ?>
  <?php if ($this->_tpl_vars['current_category']['description'] != ""): ?>
<br />
    <div class="subcategory-descr"><?php echo $this->_tpl_vars['current_category']['description']; ?>
</div>
  <?php endif;  endif; ?>

<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "customer/main/subcategories.tpl"), $this); endif; ?>
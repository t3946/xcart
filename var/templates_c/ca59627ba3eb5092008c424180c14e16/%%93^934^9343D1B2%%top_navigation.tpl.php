<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:06
         compiled from customer/top_navigation.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'customer/top_navigation.tpl', 1, false),array('function', 'func_mobile_set_active_tab', 'customer/top_navigation.tpl', 13, false),)), $this); ?>
<?php func_load_lang($this, "customer/top_navigation.tpl","lbl_title_home,lbl_title_catalog,lbl_title_search,lbl_title_cart,lbl_title_more"); ?><?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "customer/top_navigation.tpl"), $this); endif;  if ($this->_tpl_vars['active_modules']['XMultiCurrency']): ?>
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/XMultiCurrency/customer/complex_selector.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  else: ?>
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/language_selector.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
<div data-role="navbar" data-iconpos="top" class="top-nav">
  <ul>
    <li>
      <a data-ajax="false" class="link-home <?php echo func_mobile_set_active_tab(array('mode' => 'home'), $this);?>
" data-theme="a" data-icon="custom" href="/home.php?top_btn=Y" data-prefetch><h3><?php echo $this->_tpl_vars['lng']['lbl_title_home']; ?>
</h3></a>
    </li>
    <li>
      <a  class="link-catalog <?php echo func_mobile_set_active_tab(array('mode' => 'catalog'), $this);?>
" data-theme="a" data-icon="custom" href="/home.php?top_btn=Y&mobile_mode=subcategories<?php if ($this->_tpl_vars['manufacturers_menu']): ?>&list_categories=1<?php endif; ?>" data-prefetch><h3><?php echo $this->_tpl_vars['lng']['lbl_title_catalog']; ?>
</h3></a>
    </li>
    <li>
      <a data-ajax="false" class="link-search <?php echo func_mobile_set_active_tab(array('mode' => 'search'), $this);?>
" data-theme="a" data-icon="custom" href="/home.php?mobile_mode=search" data-rel="dialog" data-transition="slidedown" data-prefetch><h3><?php echo $this->_tpl_vars['lng']['lbl_title_search']; ?>
</h3></a>
    </li>
    <li class="link-cart-wrapper">
      <a data-ajax="false" class="link-cart <?php echo func_mobile_set_active_tab(array('mode' => 'cart'), $this);?>
" data-theme="a" data-icon="custom" href="/cart.php?top_btn=Y" data-prefetch><h3><?php echo $this->_tpl_vars['lng']['lbl_title_cart']; ?>
</h3></a>
      <span class="minicart-total-items" style="display: inline;"><?php echo $this->_tpl_vars['minicart_total_items']; ?>
</span>
    </li>
    <li>
      <a data-ajax="false" class="link-more <?php echo func_mobile_set_active_tab(array('mode' => 'more'), $this);?>
" data-theme="a" data-icon="custom" href="/home.php?top_btn=Y&mobile_mode=more" data-prefetch><h3><?php echo $this->_tpl_vars['lng']['lbl_title_more']; ?>
</h3></a>
    </li>
  </ul>
</div>
<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "customer/top_navigation.tpl"), $this); endif; ?>
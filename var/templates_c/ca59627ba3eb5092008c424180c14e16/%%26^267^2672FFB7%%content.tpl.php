<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:17
         compiled from modules/Fast_Lane_Checkout/content.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'modules/Fast_Lane_Checkout/content.tpl', 1, false),array('function', 'currency', 'modules/Fast_Lane_Checkout/content.tpl', 16, false),array('modifier', 'cat', 'modules/Fast_Lane_Checkout/content.tpl', 20, false),)), $this); ?>
<?php func_load_lang($this, "modules/Fast_Lane_Checkout/content.tpl","lbl_checkout,lbl_checkout,lbl_continue_shopping"); ?><?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "modules/Fast_Lane_Checkout/content.tpl"), $this); endif;  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Fast_Lane_Checkout/tabs_menu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  if ($this->_tpl_vars['top_message'] || $this->_tpl_vars['alt_content']):  endif;  if ($this->_tpl_vars['main'] == 'cart' || $this->_tpl_vars['main'] == 'fast_lane_checkout'): ?>
  <?php if (! $this->_tpl_vars['std_checkout_disabled']): ?>
    <?php ob_start(); ?>
      
      <?php if ($this->_tpl_vars['cart']['display_discounted_subtotal'] != $this->_tpl_vars['cart']['display_subtotal']): ?>
        <?php echo smarty_function_currency(array('value' => $this->_tpl_vars['cart']['display_discounted_subtotal'],'assign' => 'checkout_sum'), $this);?>

      <?php else: ?>
        <?php echo smarty_function_currency(array('value' => $this->_tpl_vars['cart']['display_subtotal'],'assign' => 'checkout_sum'), $this);?>

      <?php endif; ?>
      <?php $this->assign('checkout_button_title', ((is_array($_tmp=$this->_tpl_vars['checkout_sum'])) ? $this->_run_mod_handler('cat', true, $_tmp, " ".($this->_tpl_vars['lng']['lbl_checkout'])) : smarty_modifier_cat($_tmp, " ".($this->_tpl_vars['lng']['lbl_checkout'])))); ?>
      
      <?php if ($this->_tpl_vars['gcheckout_enabled'] || $this->_tpl_vars['amazon_enabled'] || $this->_tpl_vars['paypal_express_active']): ?>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/buttons/button.tpl", 'smarty_include_vars' => array('button_title' => $this->_tpl_vars['checkout_button_title'],'style' => 'dropout','data_theme' => 'b','data_popup_theme' => 'e','data_icon' => "arrow-r",'data_iconpos' => 'right','data_inline' => 'false','prefix' => 'top_checkout_button','dropout_tpl' => "customer/main/cart_checkout_buttons.tpl")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
      <?php else: ?>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/buttons/button.tpl", 'smarty_include_vars' => array('button_title' => $this->_tpl_vars['checkout_button_title'],'style' => 'div_button','href' => "cart.php?mode=checkout&l=y",'data_theme' => 'b','data_icon' => "arrow-r",'data_iconpos' => 'right','data_inline' => 'false')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
      <?php endif; ?>
    <?php $this->_smarty_vars['capture']['checkout_button'] = ob_get_contents(); ob_end_clean(); ?>
  <?php endif; ?>
  <?php ob_start(); ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/buttons/button.tpl", 'smarty_include_vars' => array('button_title' => $this->_tpl_vars['lng']['lbl_continue_shopping'],'style' => 'div_button','href' => "home.php?cat=".($this->_tpl_vars['last_categoryid']),'data_inline' => 'false')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <?php $this->_smarty_vars['capture']['continue_button'] = ob_get_contents(); ob_end_clean();  endif;  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Fast_Lane_Checkout/home_main.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "modules/Fast_Lane_Checkout/content.tpl"), $this); endif; ?>
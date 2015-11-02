<?php /* Smarty version 2.6.12, created on 2011-10-11 05:37:51
         compiled from customer/menu_cart.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'amp', 'customer/menu_cart.tpl', 42, false),)), $this); ?>
<?php func_load_lang($this, "customer/menu_cart.tpl","lbl_view_cart,lbl_shipping_quote,lbl_checkout,lbl_friends_wish_list,lbl_wish_list,lbl_gift_registry,lbl_modify_profile,lbl_delete_profile,lbl_retrieve_orders,txt_javascript_disabled,txt_javascript_enabled,lbl_your_cart"); ?><div id="ajax_minicart">
<?php ob_start(); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/minicart.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<a href="cart.php" class="VertMenuItems"><b><?php echo $this->_tpl_vars['lng']['lbl_view_cart']; ?>
</b></a><br />
<?php if ($this->_tpl_vars['minicart_total_items'] > 0 && $this->_tpl_vars['js_enabled']): ?>
	<a href="#" onclick="javascript: window.open('popup_shipquote.php?short=Y','popup_shipquote','width=800,height=600,toolbar=no,status=no,scrollbars=yes,menubar=no,location=no,direction=no');" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_shipping_quote']; ?>
</a><br />
<?php endif; ?>
<?php if ($this->_tpl_vars['active_modules']['Google_Checkout'] == ""): ?>
<a href="cart.php?mode=checkout" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_checkout']; ?>
</a><br />
<?php endif; ?>
<?php if ($this->_tpl_vars['active_modules']['Wishlist'] != "" && $this->_tpl_vars['wlid'] != ""): ?>
<a href="cart.php?mode=friend_wl&amp;wlid=<?php echo $this->_tpl_vars['wlid']; ?>
" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_friends_wish_list']; ?>
</a><br />
<?php endif; ?>

<?php if ($this->_tpl_vars['active_modules']['Wishlist'] != ""): ?>
<a href="cart.php?mode=wishlist" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_wish_list']; ?>
</a><br />
<?php if ($this->_tpl_vars['active_modules']['Gift_Registry'] != ""): ?>
<a href="giftreg_manage.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_gift_registry']; ?>
</a><br />
<?php endif; ?>
<?php endif; ?>
<?php if ($this->_tpl_vars['anonymous_login'] == "" && $this->_tpl_vars['login'] != ""): ?>
<a href="register.php?mode=update" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_modify_profile']; ?>
</a><br />
<a href="register.php?mode=delete" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_delete_profile']; ?>
</a><br />
<?php endif; ?>
<br />
<a href="retrieve_orders.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_retrieve_orders']; ?>
</a><br />
<?php if ($this->_tpl_vars['user_subscription'] != ""): ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Subscriptions/subscriptions_menu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><br />
<?php endif; ?>
<?php if ($this->_tpl_vars['active_modules']['RMA'] != ""): ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/RMA/customer_menu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><br />
<?php endif; ?>
<?php if ($this->_tpl_vars['active_modules']['Special_Offers'] != ""): ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Special_Offers/menu_cart.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><br />
<?php endif; ?>
<br />
<?php if ($this->_tpl_vars['js_enabled']): ?>
	<a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['js_update_link'])) ? $this->_run_mod_handler('amp', true, $_tmp) : smarty_modifier_amp($_tmp)); ?>
" class="SmallNote"><?php echo $this->_tpl_vars['lng']['txt_javascript_disabled']; ?>
</a>
<?php else: ?>
	<a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['js_update_link'])) ? $this->_run_mod_handler('amp', true, $_tmp) : smarty_modifier_amp($_tmp)); ?>
" class="SmallNote"><?php echo $this->_tpl_vars['lng']['txt_javascript_enabled']; ?>
</a>
<?php endif; ?>
<?php $this->_smarty_vars['capture']['menu'] = ob_get_contents(); ob_end_clean(); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "menu.tpl", 'smarty_include_vars' => array('dingbats' => "dingbats_orders.gif",'menu_title' => $this->_tpl_vars['lng']['lbl_your_cart'],'menu_content' => $this->_smarty_vars['capture']['menu'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>
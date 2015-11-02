<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:18
         compiled from customer/main/more.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'customer/main/more.tpl', 1, false),array('modifier', 'escape', 'customer/main/more.tpl', 32, false),array('modifier', 'trim', 'customer/main/more.tpl', 44, false),array('modifier', 'amp', 'customer/main/more.tpl', 96, false),array('insert', 'gate', 'customer/main/more.tpl', 54, false),)), $this); ?>
<?php func_load_lang($this, "customer/main/more.tpl","lbl_friends_wish_list,lbl_wish_list,lbl_gift_registry,lbl_special,lbl_news,lbl_manufacturers,lbl_more_information,lbl_contact_us"); ?><?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "customer/main/more.tpl"), $this); endif;  if ($this->_tpl_vars['login']): ?>
  <?php ob_start(); ?>
    <?php if ($this->_tpl_vars['active_modules']['Wishlist'] && $this->_tpl_vars['wlid'] != ""): ?>
      <li><a href="cart.php?mode=friend_wl&amp;wlid=<?php echo ((is_array($_tmp=$this->_tpl_vars['wlid'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><?php echo $this->_tpl_vars['lng']['lbl_friends_wish_list']; ?>
</a></li>
      <?php endif; ?>
    <?php if ($this->_tpl_vars['active_modules']['Wishlist']): ?>
      <li><a href="cart.php?mode=wishlist"><?php echo $this->_tpl_vars['lng']['lbl_wish_list']; ?>
</a></li>
      <?php if ($this->_tpl_vars['active_modules']['Gift_Registry']): ?>
        <li><a href="giftreg_manage.php"><?php echo $this->_tpl_vars['lng']['lbl_gift_registry']; ?>
</a></li>
        <?php endif; ?>
    <?php endif; ?>
    <?php if ($this->_tpl_vars['active_modules']['Quick_Reorder']): ?>
      <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Quick_Reorder/quick_reorder_link_menu_cart.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php endif; ?>
  <?php $this->_smarty_vars['capture']['submenu'] = ob_get_contents(); ob_end_clean(); ?>
  <?php if (((is_array($_tmp=$this->_smarty_vars['capture']['submenu'])) ? $this->_run_mod_handler('trim', true, $_tmp) : trim($_tmp))): ?>
    <h2><?php echo $this->_tpl_vars['lng']['lbl_special']; ?>
</h2>
    <ul data-role="listview" data-inset="true">
      <?php echo $this->_smarty_vars['capture']['submenu']; ?>

    </ul>
  <?php endif;  endif;  ob_start(); ?>
  <?php if ($this->_tpl_vars['active_modules']['News_Management']): ?>
    <?php require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'gate', 'func' => 'news_exist', 'assign' => 'is_news_exist', 'lngcode' => $this->_tpl_vars['shop_language'])), $this); ?>

    <?php if ($this->_tpl_vars['is_news_exist']): ?>
      <li><a href="news.php"><?php echo $this->_tpl_vars['lng']['lbl_news']; ?>
</a></li>
      <?php endif; ?>
    <?php endif; ?>
  <?php if ($this->_tpl_vars['active_modules']['Manufacturers'] != "" && $this->_tpl_vars['manufacturers_menu']): ?>
    <li><a href="manufacturers.php"><?php echo $this->_tpl_vars['lng']['lbl_manufacturers']; ?>
</a></li>
    <?php endif; ?>
  <?php if ($this->_tpl_vars['active_modules']['Gift_Certificates'] != ""): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Gift_Certificates/gc_menu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <?php endif; ?>
  <?php if ($this->_tpl_vars['active_modules']['Gift_Registry'] != ""): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Gift_Registry/giftreg_menu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <?php endif; ?>
  <?php if ($this->_tpl_vars['active_modules']['Feature_Comparison'] != ""): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Feature_Comparison/customer_menu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <?php endif; ?>
  <?php if ($this->_tpl_vars['active_modules']['Survey'] != ""): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Survey/menu_special.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <?php endif; ?>
  <?php if ($this->_tpl_vars['active_modules']['Special_Offers'] != ""): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Special_Offers/menu_special.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <?php endif; ?>
  <?php if ($this->_tpl_vars['active_modules']['New_Arrivals'] != ""): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/New_Arrivals/new_arrivals_link.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <?php endif; ?>
  <?php if ($this->_tpl_vars['active_modules']['On_Sale'] != ""): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/On_Sale/on_sale_link.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <?php endif; ?>
  <?php if ($this->_tpl_vars['active_modules']['EU_Cookie_Law'] != ""): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/EU_Cookie_Law/menu_item_special.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <?php endif; ?>

<?php $this->_smarty_vars['capture']['submenu'] = ob_get_contents(); ob_end_clean(); ?>
<h2><?php echo $this->_tpl_vars['lng']['lbl_more_information']; ?>
</h2>
<ul data-role="listview" data-inset="true">
  <?php if (((is_array($_tmp=$this->_smarty_vars['capture']['submenu'])) ? $this->_run_mod_handler('trim', true, $_tmp) : trim($_tmp))): ?>
    <?php echo ((is_array($_tmp=$this->_smarty_vars['capture']['submenu'])) ? $this->_run_mod_handler('trim', true, $_tmp) : trim($_tmp)); ?>

  <?php endif; ?>
  <li><a href="help.php?section=contactus&amp;mode=update" rel="external"><?php echo $this->_tpl_vars['lng']['lbl_contact_us']; ?>
</a></li>
    <?php $_from = $this->_tpl_vars['pages_menu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['p']):
?>
      <?php if ($this->_tpl_vars['p']['show_in_menu'] == 'Y'): ?>
      <li><a href="pages.php?pageid=<?php echo $this->_tpl_vars['p']['pageid']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['p']['title'])) ? $this->_run_mod_handler('amp', true, $_tmp) : smarty_modifier_amp($_tmp)); ?>
</a></li>
      <?php endif; ?>
    <?php endforeach; endif; unset($_from); ?>
</ul>
<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "customer/main/more.tpl"), $this); endif; ?>
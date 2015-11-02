<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:17
         compiled from modules/Fast_Lane_Checkout/tabs_menu.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'modules/Fast_Lane_Checkout/tabs_menu.tpl', 1, false),array('modifier', 'amp', 'modules/Fast_Lane_Checkout/tabs_menu.tpl', 11, false),)), $this); ?>
<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "modules/Fast_Lane_Checkout/tabs_menu.tpl"), $this); endif;  if ($this->_tpl_vars['checkout_tabs']): ?>
  <div class="tabs-menu">
    <div data-role="navbar" data-iconpos="right">
      <ul>
        <?php $_from = $this->_tpl_vars['checkout_tabs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['checkout_tabs'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['checkout_tabs']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['step']):
        $this->_foreach['checkout_tabs']['iteration']++;
?>
          <li>
            <a data-icon="arrow-r" href="<?php if ($this->_tpl_vars['step']['link'] != "" && $this->_tpl_vars['step']['selected_before']):  echo $this->_tpl_vars['current_location']; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['step']['link'])) ? $this->_run_mod_handler('amp', true, $_tmp) : smarty_modifier_amp($_tmp));  else: ?>#<?php endif; ?>"<?php if ($this->_tpl_vars['step']['selected'] == 'Y'): ?> class="ui-btn-active ui-state-persist"<?php endif;  if (! ( $this->_tpl_vars['step']['link'] != "" && $this->_tpl_vars['step']['selected_before'] )): ?> class="ui-disabled"<?php endif; ?>><?php echo $this->_tpl_vars['step']['title']; ?>
</a>
          </li>
        <?php endforeach; endif; unset($_from); ?>
      </ul>
    </div>
  </div>
<?php endif;  if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "modules/Fast_Lane_Checkout/tabs_menu.tpl"), $this); endif; ?>
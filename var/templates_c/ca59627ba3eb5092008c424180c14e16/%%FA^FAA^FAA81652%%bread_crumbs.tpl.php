<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:06
         compiled from customer/bread_crumbs.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'customer/bread_crumbs.tpl', 1, false),array('modifier', 'count', 'customer/bread_crumbs.tpl', 5, false),array('modifier', 'amp', 'customer/bread_crumbs.tpl', 12, false),)), $this); ?>
<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "customer/bread_crumbs.tpl"), $this); endif;  if ($this->_tpl_vars['location'] && count($this->_tpl_vars['location']) > 2 && $this->_tpl_vars['main'] == 'catalog' || $this->_tpl_vars['main'] == 'product'): ?>
  <div class="location">
    <div data-role="navbar" data-iconpos="right">
      <ul>
        <?php $_from = $this->_tpl_vars['location']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['location'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['location']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['l']):
        $this->_foreach['location']['iteration']++;
?>
          <?php if ($this->_tpl_vars['l']['1'] && ! ($this->_foreach['location']['iteration'] == $this->_foreach['location']['total'])): ?>
            <li>
              <a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['l']['1'])) ? $this->_run_mod_handler('amp', true, $_tmp) : smarty_modifier_amp($_tmp)); ?>
" data-icon="arrow-r" data-theme="a"><?php echo ((is_array($_tmp=$this->_tpl_vars['l']['0'])) ? $this->_run_mod_handler('amp', true, $_tmp) : smarty_modifier_amp($_tmp)); ?>
</a>
            </li>
          <?php endif; ?>
        <?php endforeach; endif; unset($_from); ?>
      </ul>
    </div>
  </div>
<?php endif;  if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "customer/bread_crumbs.tpl"), $this); endif; ?>
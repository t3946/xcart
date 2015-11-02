<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:02
         compiled from customer/main/ajax_carousel_products.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'customer/main/ajax_carousel_products.tpl', 1, false),)), $this); ?>
<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "customer/main/ajax_carousel_products.tpl"), $this); endif;  ob_start(); ?>
            <div class="jcarousel-wrapper">
                <div class="jcarousel" id="jcarousel_<?php echo $this->_tpl_vars['section_name']; ?>
">
                    <div class="loading">Loading carousel items...</div>
                </div>

                <a href="#" class="jcarousel-control-prev" id="jcarousel-control-prev_<?php echo $this->_tpl_vars['section_name']; ?>
">&lsaquo;</a>
                <a href="#" class="jcarousel-control-next" id="jcarousel-control-next_<?php echo $this->_tpl_vars['section_name']; ?>
">&rsaquo;</a>
            </div>
<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['section_title'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%" class="recommends no_padding_bottom"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "customer/main/ajax_carousel_products.tpl"), $this); endif; ?>
<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:06
         compiled from customer/main/switch_view.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'customer/main/switch_view.tpl', 1, false),)), $this); ?>
<?php func_load_lang($this, "customer/main/switch_view.tpl","lbl_switch_to_desktop,lbl_close,txt_mobile_switch_view_dialog_header,txt_mobile_switch_view_dialog_content_mobile,lbl_cancel,lbl_switch"); ?><?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "customer/main/switch_view.tpl"), $this); endif; ?><a class="switch-mobile link-switch" data-theme="b" data-icon="switch" data-role="button" href="<?php echo $this->_tpl_vars['php_url']['url'];  if ($this->_tpl_vars['php_url']['query_string']): ?>?<?php echo $this->_tpl_vars['php_url']['query_string'];  endif; ?>#switch-view" data-rel="popup"><h3><?php echo $this->_tpl_vars['lng']['lbl_switch_to_desktop']; ?>
</h3></a>
<div data-role="popup" id="switch-view" data-overlay-theme="e" data-theme="e" style="max-width:400px;" class="ui-corner-all">
  <a href="<?php echo $this->_tpl_vars['php_url']['url'];  if ($this->_tpl_vars['php_url']['query_string']): ?>?<?php echo $this->_tpl_vars['php_url']['query_string'];  endif; ?>#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right"><?php echo $this->_tpl_vars['lng']['lbl_close']; ?>
</a>
  <h1><?php echo $this->_tpl_vars['lng']['txt_mobile_switch_view_dialog_header']; ?>
</h1>
  <?php echo $this->_tpl_vars['lng']['txt_mobile_switch_view_dialog_content_mobile']; ?>

  <div class="ui-grid-a">
    <div class="ui-block-a">
      <a href="<?php echo $this->_tpl_vars['php_url']['url'];  if ($this->_tpl_vars['php_url']['query_string']): ?>?<?php echo $this->_tpl_vars['php_url']['query_string'];  endif; ?>#" data-role="button" data-inline="false" onclick="javascript: $('#switch-view').popup('close');" data-theme="c"><?php echo $this->_tpl_vars['lng']['lbl_cancel']; ?>
</a>
    </div>
    <div class="ui-block-b">
      <a href="<?php echo $this->_tpl_vars['php_url']['url']; ?>
?<?php if ($this->_tpl_vars['php_url']['query_string']):  echo $this->_tpl_vars['php_url']['query_string']; ?>
&<?php endif; ?>switch_view=common" rel="external" data-role="button" data-inline="false" data-theme="b"><?php echo $this->_tpl_vars['lng']['lbl_switch']; ?>
</a>
    </div>
  </div>
</div><?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "customer/main/switch_view.tpl"), $this); endif; ?>
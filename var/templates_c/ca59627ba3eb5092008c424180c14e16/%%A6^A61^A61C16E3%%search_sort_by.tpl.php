<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:06
         compiled from customer/search_sort_by.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'customer/search_sort_by.tpl', 1, false),array('function', 'func_mobile_prepare_sort_fields', 'customer/search_sort_by.tpl', 11, false),array('modifier', 'count', 'customer/search_sort_by.tpl', 5, false),array('modifier', 'has_string', 'customer/search_sort_by.tpl', 14, false),)), $this); ?>
<?php func_load_lang($this, "customer/search_sort_by.tpl","lbl_sort_by"); ?><?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "customer/search_sort_by.tpl"), $this); endif;  if (count($this->_tpl_vars['products']) > 1 && $this->_tpl_vars['sort_fields'] && ( $this->_tpl_vars['url'] || $this->_tpl_vars['navigation_script'] )): ?>
  <div class="ui-select">
    <div class="ui-btn ui-btn-corner-all ui-shadow ui-btn-up-b">
      <span class="ui-btn-inner ui-btn-corner-all">
        <span class="ui-btn-text"><?php echo $this->_tpl_vars['lng']['lbl_sort_by']; ?>
</span>
      </span>
      <?php echo func_mobile_prepare_sort_fields(array('fields' => $this->_tpl_vars['sort_fields'],'assign' => 'prepared_fields'), $this);?>

      <select data-role="none" class="select-sort" onchange="javascript: $.mobile.changePage('<?php echo $this->_tpl_vars['current_location']; ?>
/'+this.value);">
        <?php $_from = $this->_tpl_vars['prepared_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['field'] => $this->_tpl_vars['name']):
?>
          <option value="<?php echo $this->_tpl_vars['field']; ?>
"<?php if (((is_array($_tmp=$this->_tpl_vars['field'])) ? $this->_run_mod_handler('has_string', true, $_tmp, $this->_tpl_vars['navigation_script']) : strpos($_tmp, $this->_tpl_vars['navigation_script']))): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['name']; ?>
</option>
        <?php endforeach; endif; unset($_from); ?>
      </select>
    </div>
  </div>
<?php endif;  if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "customer/search_sort_by.tpl"), $this); endif; ?>
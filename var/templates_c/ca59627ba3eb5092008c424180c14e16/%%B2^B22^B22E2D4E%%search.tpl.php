<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:18
         compiled from customer/main/search.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'customer/main/search.tpl', 1, false),array('modifier', 'stripslashes', 'customer/main/search.tpl', 30, false),array('modifier', 'escape', 'customer/main/search.tpl', 30, false),)), $this); ?>
<?php func_load_lang($this, "customer/main/search.tpl","lbl_submit"); ?><?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "customer/main/search.tpl"), $this); endif; ?><div class="search">
  <div class="valign-middle">
    <form method="post" action="home.php" name="productsearchform">

            <input type="hidden" name="e_mode" value="e_search" />
            <?php if ($this->_tpl_vars['cat'] > 0 || $this->_tpl_vars['clean_url_data']['resource_type'] == 'K'): ?>
            <input type="hidden" name="e_current_url" value="<?php if ($this->_tpl_vars['main'] == 'product'): ?>/home.php?cat=<?php echo $this->_tpl_vars['cat'];  else:  echo $this->_tpl_vars['action_notify_url'];  endif; ?>" />
            <?php endif; ?>
            <input type="hidden" name="cat" value="0" />


      <?php echo '';  echo '<input type="search" name="e_posted_data[substring]" class="text';  if (! $this->_tpl_vars['search_prefilled']['substring']):  echo ' default-value';  endif;  echo '" value="';  echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['e_search_data']['substring'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp));  echo '" title="Search For" id="twotabsearchtextbox" placeholder="';  echo $this->_tpl_vars['config']['Company']['cidev_header_code'];  echo '" autocomplete="off" />'; ?>

      <div class="ui-grid-a">
        <div class="ui-block-b">
          <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/buttons/button.tpl", 'smarty_include_vars' => array('button_title' => $this->_tpl_vars['lng']['lbl_submit'],'type' => 'input','data_inline' => 'false','data_theme' => 'b')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        </div>
      </div>
    </form>
  </div>
</div>
<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "customer/main/search.tpl"), $this); endif; ?>
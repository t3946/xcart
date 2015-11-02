<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:06
         compiled from onload_js.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'onload_js.tpl', 1, false),array('function', 'load_defer', 'onload_js.tpl', 34, false),array('modifier', 'wm_remove', 'onload_js.tpl', 32, false),array('modifier', 'escape', 'onload_js.tpl', 32, false),)), $this); ?>
<?php func_load_lang($this, "onload_js.tpl","txt_are_you_sure"); ?><?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "onload_js.tpl"), $this); endif;  ob_start(); ?>
  <?php if ($this->_tpl_vars['config']['SEO']['clean_urls_enabled'] == 'Y'): ?>
    <?php echo '
      //  Fix a.href if base url is defined for page
      function anchor_fix() {
      var links = document.getElementsByTagName(\'A\');
      var m;
      var _rg = new RegExp("(^|" + self.location.host + xcart_web_dir + "/)#([\\\\w\\\\d_]+)$")
      for (var i = 0; i < links.length; i++) {
      if (links[i].href && (m = links[i].href.match(_rg))) {
      links[i].href = \'javascript:void(self.location.hash = "\' + m[2] + \'");\';
      }
      }
      }
      if (window.addEventListener)
      window.addEventListener("load", anchor_fix, false);
      else if (window.attachEvent)
      window.attachEvent("onload", anchor_fix);
    '; ?>

  <?php endif; ?>
  <?php if ($this->_tpl_vars['products'] != "" || $this->_tpl_vars['free_products'] != "" || $this->_tpl_vars['product'] != ""): ?>
    <?php echo '
      if (products_data == undefined) {
      var products_data = [];
      }
    '; ?>

  <?php endif; ?>
  var txt_are_you_sure = '<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['txt_are_you_sure'])) ? $this->_run_mod_handler('wm_remove', true, $_tmp) : smarty_modifier_wm_remove($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
';
<?php $this->_smarty_vars['capture']['onload_js'] = ob_get_contents(); ob_end_clean();  echo smarty_function_load_defer(array('file' => 'onload_js','direct_info' => $this->_smarty_vars['capture']['onload_js'],'type' => 'js','queue' => '1'), $this);?>

<?php if ($this->_tpl_vars['active_modules']['EU_Cookie_Law'] != ""): ?>
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/EU_Cookie_Law/init.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  if ($this->_tpl_vars['active_modules']['Product_Options'] != ""): ?>
  <?php echo smarty_function_load_defer(array('file' => "modules/Product_Options/func.js",'type' => 'js'), $this);?>

<?php endif;  if ($this->_tpl_vars['products'] || $this->_tpl_vars['free_products']): ?>
  <?php echo smarty_function_load_defer(array('file' => "js/check_quantity.js",'type' => 'js'), $this);?>

  <?php if ($this->_tpl_vars['active_modules']['Feature_Comparison'] && ! $this->_tpl_vars['printable'] && $this->_tpl_vars['products_has_fclasses']): ?>
    <?php echo smarty_function_load_defer(array('file' => "modules/Feature_Comparison/products_check.js",'type' => 'js'), $this);?>

  <?php endif;  endif;  if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "onload_js.tpl"), $this); endif; ?>
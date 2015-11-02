<?php /* Smarty version 2.6.12, created on 2015-11-02 03:12:07
         compiled from modules/Fast_Lane_Checkout/home.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'modules/Fast_Lane_Checkout/home.tpl', 1, false),array('function', 'config_load', 'modules/Fast_Lane_Checkout/home.tpl', 7, false),array('function', 'func_mobile_clear_modules', 'modules/Fast_Lane_Checkout/home.tpl', 10, false),array('function', 'load_defer_code', 'modules/Fast_Lane_Checkout/home.tpl', 18, false),)), $this); ?>
<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "modules/Fast_Lane_Checkout/home.tpl"), $this); endif;  if (! $this->_tpl_vars['is_ajax_request']): ?>
<!DOCTYPE html>
  <?php echo smarty_function_config_load(array('file' => ($this->_tpl_vars['skin_config'])), $this);?>

<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <?php echo func_mobile_clear_modules(array(), $this);?>

    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/service_head_mobile.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<link rel="stylesheet" href="<?php echo $this->_tpl_vars['SkinDir']; ?>
/US_City_List/jquery.autocomplete.css" />
  </head>
  <body>
<?php endif; ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/page.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  if (! $this->_tpl_vars['is_ajax_request']): ?>
    <?php echo smarty_function_load_defer_code(array('type' => 'js'), $this);?>

    <?php echo smarty_function_load_defer_code(array('type' => 'css'), $this);?>

  </body>
</html>
<?php endif;  if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "modules/Fast_Lane_Checkout/home.tpl"), $this); endif; ?>
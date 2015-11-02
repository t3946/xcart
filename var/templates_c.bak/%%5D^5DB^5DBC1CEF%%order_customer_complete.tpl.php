<?php /* Smarty version 2.6.12, created on 2011-10-11 07:23:41
         compiled from mail/order_customer_complete.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'config_load', 'mail/order_customer_complete.tpl', 2, false),array('function', 'math', 'mail/order_customer_complete.tpl', 4, false),array('modifier', 'cat', 'mail/order_customer_complete.tpl', 4, false),array('modifier', 'substitute', 'mail/order_customer_complete.tpl', 6, false),array('modifier', 'truncate', 'mail/order_customer_complete.tpl', 10, false),array('modifier', 'string_format', 'mail/order_customer_complete.tpl', 10, false),array('modifier', 'date_format', 'mail/order_customer_complete.tpl', 11, false),)), $this); ?>
<?php func_load_lang($this, "mail/order_customer_complete.tpl","eml_dear,eml_order_complete,lbl_order_id,lbl_order_date,lbl_tracking_number"); ?><?php echo smarty_function_config_load(array('file' => ($this->_tpl_vars['skin_config'])), $this);?>

<?php $this->assign('max_truncate', $this->_tpl_vars['config']['Email']['max_truncate']);  echo smarty_function_math(array('assign' => 'max_space','equation' => "x+5",'x' => $this->_tpl_vars['max_truncate']), $this); $this->assign('max_space', ((is_array($_tmp=((is_array($_tmp="%-")) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['max_space'])))) ? $this->_run_mod_handler('cat', true, $_tmp, 's') : smarty_modifier_cat($_tmp, 's'))); ?>

<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['eml_dear'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'customer', ($this->_tpl_vars['customer']['firstname'])." ".($this->_tpl_vars['customer']['lastname'])) : smarty_modifier_substitute($_tmp, 'customer', ($this->_tpl_vars['customer']['firstname'])." ".($this->_tpl_vars['customer']['lastname']))); ?>
,

<?php echo $this->_tpl_vars['lng']['eml_order_complete']; ?>


<?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_order_id'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['order']['order_prefix'];  echo $this->_tpl_vars['order']['orderid']; ?>

<?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_order_date'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo ((is_array($_tmp=$this->_tpl_vars['order']['date'])) ? $this->_run_mod_handler('date_format', true, $_tmp, $this->_tpl_vars['config']['Appearance']['datetime_format']) : smarty_modifier_date_format($_tmp, $this->_tpl_vars['config']['Appearance']['datetime_format'])); ?>

<?php if ($this->_tpl_vars['order']['tracking']): ?> 
<?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_tracking_number'])) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['max_truncate'], "...", true) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['max_truncate'], "...", true)))) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")))) ? $this->_run_mod_handler('string_format', true, $_tmp, $this->_tpl_vars['max_space']) : smarty_modifier_string_format($_tmp, $this->_tpl_vars['max_space']));  echo $this->_tpl_vars['order']['tracking']; ?>
 
<?php endif; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "mail/order_data.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "mail/signature.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
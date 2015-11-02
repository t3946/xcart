<?php /* Smarty version 2.6.12, created on 2015-11-02 03:06:28
         compiled from main/register_states.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'main/register_states.tpl', 1, false),array('modifier', 'escape', 'main/register_states.tpl', 10, false),)), $this); ?>
<?php func_load_lang($this, "main/register_states.tpl","lbl_fill_in_examples_state"); ?><?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "main/register_states.tpl"), $this); endif;  if ($this->_tpl_vars['usertype'] == 'P' || $this->_tpl_vars['usertype'] == 'A'): ?>

<?php if ($this->_tpl_vars['country_id'] == ''):  $this->assign('country_id', $this->_tpl_vars['country_name']);  endif; ?>
<script type="text/javascript" language="JavaScript 1.2">
<!--

var cidev_placeholder_state = '<?php echo $this->_tpl_vars['lng']['lbl_fill_in_examples_state']; ?>
';

init_js_states(document.getElementById('<?php echo $this->_tpl_vars['country_id']; ?>
'), '<?php echo $this->_tpl_vars['state_name']; ?>
', '<?php echo $this->_tpl_vars['county_name']; ?>
', '<?php echo ((is_array($_tmp=$this->_tpl_vars['state_value'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
', '<?php echo ((is_array($_tmp=$this->_tpl_vars['county_value'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
');
-->
</script>

<?php endif;  if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "main/register_states.tpl"), $this); endif; ?>
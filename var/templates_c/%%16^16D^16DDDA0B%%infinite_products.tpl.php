<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:05
         compiled from customer/main/infinite_products.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'customer/main/infinite_products.tpl', 1, false),array('function', 'math', 'customer/main/infinite_products.tpl', 27, false),array('modifier', 'substitute', 'customer/main/infinite_products.tpl', 11, false),)), $this); ?>
<?php func_load_lang($this, "customer/main/infinite_products.tpl","txt_displaying_X_Y_results,txt_N_results_found,lb_LoadMore_button_text"); ?><?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "customer/main/infinite_products.tpl"), $this); endif;  if ($this->_tpl_vars['show_next_products'] == 'Y'): ?>

	Page: <?php echo $this->_tpl_vars['ajax_navigation_page']; ?>


	<br />
	<?php if ($this->_tpl_vars['total_items'] > '1'): ?>
				<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['txt_displaying_X_Y_results'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'first_item', $this->_tpl_vars['first_item'], 'last_item', $this->_tpl_vars['last_item']) : smarty_modifier_substitute($_tmp, 'first_item', $this->_tpl_vars['first_item'], 'last_item', $this->_tpl_vars['last_item'])); ?>

	<?php elseif ($this->_tpl_vars['total_items'] == '0'): ?>
		<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['txt_N_results_found'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'items', 0) : smarty_modifier_substitute($_tmp, 'items', 0)); ?>

	<?php endif; ?>

	<?php if ($this->_tpl_vars['products_template'] == 'products_new_style'): ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/products_new_style.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php else: ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/products.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php endif;  else: ?>

	<?php if ($this->_tpl_vars['ajax_navigation_page'] == ""): ?>
        	<?php $this->assign('ajax_navigation_page', '1'); ?>
	<?php endif; ?>

	<?php echo smarty_function_math(array('equation' => "page+1",'page' => $this->_tpl_vars['ajax_navigation_page'],'assign' => 'ajax_navigation_page_next'), $this);?>


	<?php if ($this->_tpl_vars['last_item'] < $this->_tpl_vars['total_items']): ?>
		<div id="show_next_products_block_<?php echo $this->_tpl_vars['ajax_navigation_page_next']; ?>
">

			<span style="width: 100%; height: 47px; font: 24px/100% Arial,Helvetica,sans-serif; padding: 0px; margin-top: 10px;" class="cidev_new_button cidev_new_white" onclick="javascript: $('#lb_LoadMore_button_text_<?php echo $this->_tpl_vars['ajax_navigation_page_next']; ?>
').html('Loading...'); func_load_more_products(<?php echo $this->_tpl_vars['ajax_navigation_page_next']; ?>
);"><div style="padding-top: 10px; font: 24px/100% Arial,Helvetica,sans-serif; padding: 0px; margin-top: 10px; text-shadow: 0 1px 1px rgba(0, 0, 0, 0.3); color: #606060;" id="lb_LoadMore_button_text_<?php echo $this->_tpl_vars['ajax_navigation_page_next']; ?>
"><?php echo $this->_tpl_vars['lng']['lb_LoadMore_button_text']; ?>
</div></span>

		</div>
	<?php endif; ?>

<?php endif;  if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "customer/main/infinite_products.tpl"), $this); endif; ?>
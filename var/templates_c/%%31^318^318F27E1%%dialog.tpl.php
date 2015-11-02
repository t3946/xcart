<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:02
         compiled from dialog.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'dialog.tpl', 1, false),array('modifier', 'substitute', 'dialog.tpl', 77, false),array('modifier', 'default', 'dialog.tpl', 101, false),)), $this); ?>
<?php func_load_lang($this, "dialog.tpl","lbl_sku,lbl_minimum_order_amount_message_product"); ?><?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "dialog.tpl"), $this); endif;  if ($this->_tpl_vars['printable'] != ''): ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog_printable.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php else: ?>
<?php if ($this->_tpl_vars['title'] || $this->_tpl_vars['product']): ?>

<?php if ($this->_tpl_vars['new_design'] == 'Y'): ?>

<table cellspacing="0" <?php echo $this->_tpl_vars['extra']; ?>
 style="margin-top: -10px;">
<tr>
<td class="DialogTitle valign-top" style="background-color: #FEF6F3;">
<?php if ($this->_tpl_vars['new_href'] != ""): ?><a href="<?php echo $this->_tpl_vars['new_href']; ?>
"><?php endif; ?><B><?php echo $this->_tpl_vars['title']; ?>
</B><?php if ($this->_tpl_vars['new_href'] != ""): ?></a><?php endif; ?>
</td>
</tr>
</table>


<?php else: ?>


<table cellspacing="0" <?php echo $this->_tpl_vars['extra']; ?>
 <?php if ($this->_tpl_vars['usertype'] == 'C'): ?>style="margin-top: -10px;"<?php endif; ?>>
<tr>

<?php if ($this->_tpl_vars['use_h1'] == 'Y'): ?>
<td <?php if ($this->_tpl_vars['usertype'] == 'C'): ?>style="background-color: #FEF6F3;"<?php endif; ?> class="DialogTitle valign-top"><?php if ($this->_tpl_vars['align'] == 'center'): ?><center><h1<?php if ($this->_tpl_vars['main'] == 'product'):  if ($this->_tpl_vars['use_schema_org'] == 'Y'): ?> itemprop="name"<?php endif;  endif; ?>><?php echo $this->_tpl_vars['title']; ?>
</h1></center><?php else: ?><h1<?php if ($this->_tpl_vars['main'] == 'product'):  if ($this->_tpl_vars['use_schema_org'] == 'Y'): ?> itemprop="name"<?php endif;  endif; ?>><?php echo $this->_tpl_vars['title']; ?>
</h1><?php endif; ?></td>
<?php else: ?>
 <td <?php if ($this->_tpl_vars['usertype'] == 'C'): ?>style="background-color: #FEF6F3;"<?php endif; ?> class="DialogTitle valign-top"><?php if ($this->_tpl_vars['align'] == 'center'): ?><center><b><?php echo $this->_tpl_vars['title']; ?>
</b></center><?php else:  if ($this->_tpl_vars['use_h2'] == 'Y'): ?><h2 class="h2_descr"><?php else: ?><b><?php endif;  echo $this->_tpl_vars['title'];  if ($this->_tpl_vars['use_h2'] == 'Y'): ?></h2><?php else: ?></b><?php endif;  endif; ?> 

	<?php if ($this->_tpl_vars['extra_link'] != ""): ?>
	<div style="float: right;">
		<?php echo $this->_tpl_vars['extra_link']; ?>

	</div>
	<?php endif; ?>
 </td> 
<?php endif; ?>


</tr>
</table>

<?php endif; ?>

<?php endif; ?>
<?php if ($this->_tpl_vars['product_sku'] != "" || $this->_tpl_vars['product_free_ship'] != "" || ( $this->_tpl_vars['lbl_minimum_order_amount_message_product'] == 'Y' && $this->_tpl_vars['d_minimum_order_amount_in_us'] != "" )): ?>
<table cellspacing="0" <?php echo $this->_tpl_vars['extra']; ?>
>
<tr>
<td class="DialogTitle valign-top">
<?php if ($this->_tpl_vars['product_sku'] != ""): ?><font color="#006600" class="DialogTitleT"><?php echo $this->_tpl_vars['lng']['lbl_sku']; ?>
: <?php echo $this->_tpl_vars['product_sku']; ?>
</font><?php endif; ?>
</td>

<?php if ($this->_tpl_vars['product_free_ship'] != "" || ( $this->_tpl_vars['lbl_minimum_order_amount_message_product'] == 'Y' && $this->_tpl_vars['d_minimum_order_amount_in_us'] != "" )): ?>
<td <?php if ($this->_tpl_vars['product_free_ship'] != ""): ?>align="right"<?php else: ?>align="center"<?php endif; ?> valign="center" nowrap="nowrap" style="padding-right: 10px;">
<table border="0" cellpadding="0" cellspacing="0">

<?php if ($this->_tpl_vars['lbl_minimum_order_amount_message_product'] == 'Y' && $this->_tpl_vars['d_minimum_order_amount_in_us'] != ""): ?>
<tr>
<td height="20" style="background: #F79647; font-size: 15px; font-weight: bold; padding-left: 4px; padding-right: 4px;" align="center">
<?php $this->assign('d_minimum_order_amount_in_us', "$".($this->_tpl_vars['d_minimum_order_amount_in_us'])); ?>
<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_minimum_order_amount_message_product'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'minimum_order_amount', $this->_tpl_vars['d_minimum_order_amount_in_us']) : smarty_modifier_substitute($_tmp, 'minimum_order_amount', $this->_tpl_vars['d_minimum_order_amount_in_us'])); ?>

</td>
</tr>
<?php endif; ?>

<?php if ($this->_tpl_vars['product_free_ship'] != ""): ?>
<tr>
<td height="20" style="background-color: #006600; color: white; font-size: 15px; font-weight: bold; padding-left: 4px; padding-right: 4px;" align="center">
<?php echo $this->_tpl_vars['product_free_ship']; ?>

</td>
</tr>
<?php endif; ?>

</table>
</td>
<?php endif; ?>



</tr>
</table>
<?php endif; ?>
<table cellspacing="0" <?php echo $this->_tpl_vars['extra']; ?>
>
<tr><td colspan="2" class="DialogBorder"><table cellspacing="1" class="DialogBox">
<tr><td class="DialogBox" valign="<?php echo ((is_array($_tmp=@$this->_tpl_vars['valign'])) ? $this->_run_mod_handler('default', true, $_tmp, 'top') : smarty_modifier_default($_tmp, 'top')); ?>
"><?php echo $this->_tpl_vars['content']; ?>

</td></tr>
</table></td></tr>
</table>
<?php endif; ?>
<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "dialog.tpl"), $this); endif; ?>
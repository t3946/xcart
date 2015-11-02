<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:06
         compiled from modules/Product_Options/customer_options.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'modules/Product_Options/customer_options.tpl', 1, false),array('modifier', 'default', 'modules/Product_Options/customer_options.tpl', 19, false),array('modifier', 'escape', 'modules/Product_Options/customer_options.tpl', 27, false),)), $this); ?>
<?php func_load_lang($this, "modules/Product_Options/customer_options.tpl","txt_product_options_combinations_warn"); ?><?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "modules/Product_Options/customer_options.tpl"), $this); endif;  if ($this->_tpl_vars['nojs'] != 'Y'): ?>
<tr style="display: none;"><td>
<script type="text/javascript" language="JavaScript 1.2">
<!--
var alert_msg = '<?php echo $this->_tpl_vars['alert_msg']; ?>
';
-->
</script>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Product_Options/check_options.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</td></tr>
<?php endif; ?>


<?php if ($this->_tpl_vars['product_options'] != ''):  $_from = $this->_tpl_vars['product_options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
 if ($this->_tpl_vars['v']['options'] != '' || $this->_tpl_vars['v']['is_modifier'] == 'T'): ?>
<tr>
	<td valign="middle" height="25"><?php if ($this->_tpl_vars['usertype'] == 'A'):  echo $this->_tpl_vars['v']['class'];  else:  echo ((is_array($_tmp=@$this->_tpl_vars['v']['classtext'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['v']['class']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['v']['class']));  endif; ?></td>
	<td valign="middle">
<?php if ($this->_tpl_vars['cname'] != ""):  $this->assign('poname', ($this->_tpl_vars['cname'])."[".($this->_tpl_vars['v']['classid'])."]");  else:  $this->assign('poname', "product_options[".($this->_tpl_vars['v']['classid'])."]");  endif;  if ($this->_tpl_vars['v']['is_modifier'] == 'T'): ?>
<input id="po<?php echo $this->_tpl_vars['v']['classid']; ?>
" type="text" name="<?php echo $this->_tpl_vars['poname']; ?>
" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['v']['default'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
<?php else: ?>
<select id="po<?php echo $this->_tpl_vars['v']['classid']; ?>
" name="<?php echo $this->_tpl_vars['poname']; ?>
"<?php if ($this->_tpl_vars['disable']): ?> disabled="disabled"<?php endif;  if ($this->_tpl_vars['nojs'] != 'Y'): ?> onchange="javascript: check_options(); check_wholesale($('#product_avail').val());"<?php endif; ?>>
<?php $_from = $this->_tpl_vars['v']['options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['o']):
?>
	<option value="<?php echo $this->_tpl_vars['o']['optionid']; ?>
"<?php if ($this->_tpl_vars['o']['selected'] == 'Y'): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['o']['option_name'];  if ($this->_tpl_vars['v']['is_modifier'] == 'Y' && $this->_tpl_vars['o']['price_modifier'] != 0): ?> (<?php if ($this->_tpl_vars['o']['modifier_type'] != '%'):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['o']['price_modifier'],'display_sign' => 1,'plain_text_message' => 1)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  else:  echo $this->_tpl_vars['o']['price_modifier']; ?>
%<?php endif; ?>)<?php endif; ?></option>
<?php endforeach; endif; unset($_from); ?>
</select>
<?php endif; ?>
	</td>
</tr>
<?php endif;  endforeach; endif; unset($_from);  endif; ?>


<?php if ($this->_tpl_vars['product_options_ex'] != ""): ?>
<tr>
    <td colspan="2"><font id="exception_msg" color="red"></font></td>
</tr>
<?php if ($this->_tpl_vars['err'] != ''): ?>
<tr>
	<td colspan="2"><font class="CustomerMessage"><?php echo $this->_tpl_vars['lng']['txt_product_options_combinations_warn']; ?>
:</font></td>
</tr>
<?php $_from = $this->_tpl_vars['product_options_ex']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
<tr>
	<td><?php $_from = $this->_tpl_vars['v']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['o']):
 if ($this->_tpl_vars['usertype'] == 'A'):  echo $this->_tpl_vars['o']['class'];  else:  echo $this->_tpl_vars['o']['classtext'];  endif; ?>: <?php echo $this->_tpl_vars['o']['option_name']; ?>
<br /><?php endforeach; endif; unset($_from); ?><br /></td>
</tr>
<?php endforeach; endif; unset($_from);  endif;  endif; ?>

<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "modules/Product_Options/customer_options.tpl"), $this); endif; ?>
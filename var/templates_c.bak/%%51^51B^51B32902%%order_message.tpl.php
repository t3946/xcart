<?php /* Smarty version 2.6.12, created on 2011-10-11 07:08:21
         compiled from customer/main/order_message.tpl */ ?>
<?php func_load_lang($this, "customer/main/order_message.tpl","txt_order_placed,txt_order_placed_msg,lbl_confirmation,lbl_print_invoice,lbl_continue_shopping,lbl_invoice"); ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "ga_code_sales.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> 
<?php if ($this->_tpl_vars['this_is_printable_version'] == ""): ?>
<br>
<?php ob_start(); ?>
<font class="ProductDetails"><?php echo $this->_tpl_vars['lng']['txt_order_placed']; ?>
</font>
<br /><br />
<font class="ProductDetails"><?php echo $this->_tpl_vars['lng']['txt_order_placed_msg']; ?>
</font>
<br />
<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean(); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_confirmation'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>
<br />
<?php ob_start(); ?>
<?php unset($this->_sections['oi']);
$this->_sections['oi']['name'] = 'oi';
$this->_sections['oi']['loop'] = is_array($_loop=$this->_tpl_vars['orders']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['oi']['show'] = true;
$this->_sections['oi']['max'] = $this->_sections['oi']['loop'];
$this->_sections['oi']['step'] = 1;
$this->_sections['oi']['start'] = $this->_sections['oi']['step'] > 0 ? 0 : $this->_sections['oi']['loop']-1;
if ($this->_sections['oi']['show']) {
    $this->_sections['oi']['total'] = $this->_sections['oi']['loop'];
    if ($this->_sections['oi']['total'] == 0)
        $this->_sections['oi']['show'] = false;
} else
    $this->_sections['oi']['total'] = 0;
if ($this->_sections['oi']['show']):

            for ($this->_sections['oi']['index'] = $this->_sections['oi']['start'], $this->_sections['oi']['iteration'] = 1;
                 $this->_sections['oi']['iteration'] <= $this->_sections['oi']['total'];
                 $this->_sections['oi']['index'] += $this->_sections['oi']['step'], $this->_sections['oi']['iteration']++):
$this->_sections['oi']['rownum'] = $this->_sections['oi']['iteration'];
$this->_sections['oi']['index_prev'] = $this->_sections['oi']['index'] - $this->_sections['oi']['step'];
$this->_sections['oi']['index_next'] = $this->_sections['oi']['index'] + $this->_sections['oi']['step'];
$this->_sections['oi']['first']      = ($this->_sections['oi']['iteration'] == 1);
$this->_sections['oi']['last']       = ($this->_sections['oi']['iteration'] == $this->_sections['oi']['total']);
?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "mail/html/order_invoice.tpl", 'smarty_include_vars' => array('is_nomail' => 'Y','products' => $this->_tpl_vars['orders'][$this->_sections['oi']['index']]['products'],'giftcerts' => $this->_tpl_vars['orders'][$this->_sections['oi']['index']]['giftcerts'],'userinfo' => $this->_tpl_vars['orders'][$this->_sections['oi']['index']]['userinfo'],'order' => $this->_tpl_vars['orders'][$this->_sections['oi']['index']]['order'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<br /><br /><br /><br />
<?php if ($this->_tpl_vars['active_modules']['Interneka'] != ""): ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Interneka/interneka_tags.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> 
<?php endif; ?>
<?php endfor; endif; ?>
<?php if ($this->_tpl_vars['this_is_printable_version'] == ""): ?>
<table width="100%">
<tr>
<td align="left"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/button.tpl", 'smarty_include_vars' => array('button_title' => $this->_tpl_vars['lng']['lbl_print_invoice'],'href' => "order.php?mode=invoice&orderid=".($this->_tpl_vars['orderids']),'target' => 'preview_invoice')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
<td align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/button.tpl", 'smarty_include_vars' => array('button_title' => $this->_tpl_vars['lng']['lbl_continue_shopping'],'style' => 'button','href' => "home.php")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>
</table>
<?php endif; ?>
<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean(); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_invoice'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
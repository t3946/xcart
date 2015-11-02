<?php /* Smarty version 2.6.12, created on 2011-10-11 06:32:03
         compiled from main/order_invoice_print.tpl */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "meta.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<style type="text/css">
<!--
BODY {
    FONT-FAMILY: Verdana, Arial, Helvetica, Sans-serif;
    FONT-SIZE: 11px;
    MARGIN: 10px;
    PADDING: 10px;
}
-->
</style>
</head>
<body>
<?php if ($this->_tpl_vars['config']['Appearance']['print_orders_separated'] == 'Y'):  $this->assign('separator', "<div style='page-break-after: always;'><!--[if IE 7]><br style='height: 0px; line-height: 0px;'><![endif]--></div>");  else:  $this->assign('separator', "<br /><hr size='1' noshade='noshade' /><br />");  endif;  unset($this->_sections['oi']);
$this->_sections['oi']['name'] = 'oi';
$this->_sections['oi']['loop'] = is_array($_loop=$this->_tpl_vars['orders_data']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
 $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "mail/html/order_invoice.tpl", 'smarty_include_vars' => array('order' => $this->_tpl_vars['orders_data'][$this->_sections['oi']['index']]['order'],'customer' => $this->_tpl_vars['orders_data'][$this->_sections['oi']['index']]['customer'],'products' => $this->_tpl_vars['orders_data'][$this->_sections['oi']['index']]['products'],'giftcerts' => $this->_tpl_vars['orders_data'][$this->_sections['oi']['index']]['giftcerts'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if (! $this->_sections['oi']['last']):  echo $this->_tpl_vars['separator']; ?>

<?php endif; ?>

<?php endfor; endif; ?>
</body>
</html>
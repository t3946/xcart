<?php /* Smarty version 2.6.12, created on 2011-10-11 05:37:50
         compiled from customer/main/product_prices.tpl */ ?>
<?php func_load_lang($this, "customer/main/product_prices.tpl","lbl_quantity,lbl_price,txt_note"); ?><?php if ($this->_tpl_vars['product_wholesale'] != ""): ?>
<BR>
<TABLE border="0" cellpadding="2" cellspacing="0" style="border-top: 1px solid black; border-left: 1px solid black;">
<TR bgcolor="#ffffff">
<?php unset($this->_sections['wi']);
$this->_sections['wi']['name'] = 'wi';
$this->_sections['wi']['loop'] = is_array($_loop=$this->_tpl_vars['product_wholesale']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['wi']['show'] = true;
$this->_sections['wi']['max'] = $this->_sections['wi']['loop'];
$this->_sections['wi']['step'] = 1;
$this->_sections['wi']['start'] = $this->_sections['wi']['step'] > 0 ? 0 : $this->_sections['wi']['loop']-1;
if ($this->_sections['wi']['show']) {
    $this->_sections['wi']['total'] = $this->_sections['wi']['loop'];
    if ($this->_sections['wi']['total'] == 0)
        $this->_sections['wi']['show'] = false;
} else
    $this->_sections['wi']['total'] = 0;
if ($this->_sections['wi']['show']):

            for ($this->_sections['wi']['index'] = $this->_sections['wi']['start'], $this->_sections['wi']['iteration'] = 1;
                 $this->_sections['wi']['iteration'] <= $this->_sections['wi']['total'];
                 $this->_sections['wi']['index'] += $this->_sections['wi']['step'], $this->_sections['wi']['iteration']++):
$this->_sections['wi']['rownum'] = $this->_sections['wi']['iteration'];
$this->_sections['wi']['index_prev'] = $this->_sections['wi']['index'] - $this->_sections['wi']['step'];
$this->_sections['wi']['index_next'] = $this->_sections['wi']['index'] + $this->_sections['wi']['step'];
$this->_sections['wi']['first']      = ($this->_sections['wi']['iteration'] == 1);
$this->_sections['wi']['last']       = ($this->_sections['wi']['iteration'] == $this->_sections['wi']['total']);
 if ($this->_sections['wi']['first']): ?><TD align="right"  style="border-right: 1px solid #000000; color: #006500; border-bottom: 1px solid black;font-size: 13px;" height="25">&nbsp;<?php echo $this->_tpl_vars['lng']['lbl_quantity']; ?>
:&nbsp;</TD><?php endif; ?>
<TD style="color: #006500; border-right: 1px solid black; border-bottom: 1px solid black; font-size: 13px;" align="center">&nbsp;<?php echo $this->_tpl_vars['product_wholesale'][$this->_sections['wi']['index']]['quantity'];  if ($this->_sections['wi']['last']): ?>+<?php else: ?>-<?php echo $this->_tpl_vars['product_wholesale'][$this->_sections['wi']['index']]['next_quantity'];  endif; ?>&nbsp;</TD>
<?php endfor; endif; ?>
</TR>
<?php if ($this->_tpl_vars['product']['taxes']):  ob_start();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/taxed_price.tpl", 'smarty_include_vars' => array('taxes' => $this->_tpl_vars['product']['taxes'],'display_info' => 'N')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  $this->_smarty_vars['capture']['taxdata'] = ob_get_contents(); ob_end_clean();  endif; ?>
<TR bgcolor="#ffffff">
<?php unset($this->_sections['wi']);
$this->_sections['wi']['name'] = 'wi';
$this->_sections['wi']['loop'] = is_array($_loop=$this->_tpl_vars['product_wholesale']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['wi']['show'] = true;
$this->_sections['wi']['max'] = $this->_sections['wi']['loop'];
$this->_sections['wi']['step'] = 1;
$this->_sections['wi']['start'] = $this->_sections['wi']['step'] > 0 ? 0 : $this->_sections['wi']['loop']-1;
if ($this->_sections['wi']['show']) {
    $this->_sections['wi']['total'] = $this->_sections['wi']['loop'];
    if ($this->_sections['wi']['total'] == 0)
        $this->_sections['wi']['show'] = false;
} else
    $this->_sections['wi']['total'] = 0;
if ($this->_sections['wi']['show']):

            for ($this->_sections['wi']['index'] = $this->_sections['wi']['start'], $this->_sections['wi']['iteration'] = 1;
                 $this->_sections['wi']['iteration'] <= $this->_sections['wi']['total'];
                 $this->_sections['wi']['index'] += $this->_sections['wi']['step'], $this->_sections['wi']['iteration']++):
$this->_sections['wi']['rownum'] = $this->_sections['wi']['iteration'];
$this->_sections['wi']['index_prev'] = $this->_sections['wi']['index'] - $this->_sections['wi']['step'];
$this->_sections['wi']['index_next'] = $this->_sections['wi']['index'] + $this->_sections['wi']['step'];
$this->_sections['wi']['first']      = ($this->_sections['wi']['iteration'] == 1);
$this->_sections['wi']['last']       = ($this->_sections['wi']['iteration'] == $this->_sections['wi']['total']);
 if ($this->_sections['wi']['first']): ?><TD align="right" style="border-right: 1px solid black; color: #CD3335; border-bottom: 1px solid black; font-size: 13px;" height="25"><?php echo $this->_tpl_vars['lng']['lbl_price'];  if ($this->_smarty_vars['capture']['taxdata']): ?>*<?php endif; ?>:&nbsp;</TD><?php endif; ?>
<TD style="color: #CD3335; border-right: 1px solid #000000; border-bottom: 1px solid black; font-size: 13px;" height="20" align="center"><SPAN id="wp<?php echo $this->_sections['wi']['index']; ?>
">&nbsp;<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency2.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['product_wholesale'][$this->_sections['wi']['index']]['taxed_price'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>&nbsp;</SPAN></TD>
<?php endfor; endif; ?>
</TR>
</TABLE>
<?php if ($this->_smarty_vars['capture']['taxdata']): ?>
<BR>
<TABLE border="0">
<TR>
<TD class="FormButton" valign="top">*<?php echo $this->_tpl_vars['lng']['txt_note']; ?>
:</B>&nbsp;</TD>
<TD nowrap valign="top"><?php echo $this->_smarty_vars['capture']['taxdata']; ?>
</TD>
</TR>
</TABLE>
<?php endif; ?>
<BR>
<?php endif; ?>
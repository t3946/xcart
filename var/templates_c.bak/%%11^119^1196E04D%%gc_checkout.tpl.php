<?php /* Smarty version 2.6.12, created on 2011-10-11 06:23:05
         compiled from modules/Gift_Certificates/gc_checkout.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'truncate', 'modules/Gift_Certificates/gc_checkout.tpl', 7, false),)), $this); ?>
<?php func_load_lang($this, "modules/Gift_Certificates/gc_checkout.tpl","lbl_gc_for"); ?><?php if ($this->_tpl_vars['cart']['giftcerts'] != ""):  unset($this->_sections['giftcert']);
$this->_sections['giftcert']['name'] = 'giftcert';
$this->_sections['giftcert']['loop'] = is_array($_loop=$this->_tpl_vars['cart']['giftcerts']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['giftcert']['show'] = true;
$this->_sections['giftcert']['max'] = $this->_sections['giftcert']['loop'];
$this->_sections['giftcert']['step'] = 1;
$this->_sections['giftcert']['start'] = $this->_sections['giftcert']['step'] > 0 ? 0 : $this->_sections['giftcert']['loop']-1;
if ($this->_sections['giftcert']['show']) {
    $this->_sections['giftcert']['total'] = $this->_sections['giftcert']['loop'];
    if ($this->_sections['giftcert']['total'] == 0)
        $this->_sections['giftcert']['show'] = false;
} else
    $this->_sections['giftcert']['total'] = 0;
if ($this->_sections['giftcert']['show']):

            for ($this->_sections['giftcert']['index'] = $this->_sections['giftcert']['start'], $this->_sections['giftcert']['iteration'] = 1;
                 $this->_sections['giftcert']['iteration'] <= $this->_sections['giftcert']['total'];
                 $this->_sections['giftcert']['index'] += $this->_sections['giftcert']['step'], $this->_sections['giftcert']['iteration']++):
$this->_sections['giftcert']['rownum'] = $this->_sections['giftcert']['iteration'];
$this->_sections['giftcert']['index_prev'] = $this->_sections['giftcert']['index'] - $this->_sections['giftcert']['step'];
$this->_sections['giftcert']['index_next'] = $this->_sections['giftcert']['index'] + $this->_sections['giftcert']['step'];
$this->_sections['giftcert']['first']      = ($this->_sections['giftcert']['iteration'] == 1);
$this->_sections['giftcert']['last']       = ($this->_sections['giftcert']['iteration'] == $this->_sections['giftcert']['total']);
?>
<tr>
<td class="ProductPriceSmall">1</td>
<td></td>
<td><?php echo $this->_tpl_vars['lng']['lbl_gc_for']; ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['cart']['giftcerts'][$this->_sections['giftcert']['index']]['recipient'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 30, "...", true) : smarty_modifier_truncate($_tmp, 30, "...", true)); ?>
</td>
<?php if ($this->_tpl_vars['cart']['display_cart_products_tax_rates'] == 'Y'): ?>
<td>&nbsp;</td>
<?php endif; ?>
<td class="ProductPriceSmall" align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['cart']['giftcerts'][$this->_sections['giftcert']['index']]['amount'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
<td class="ProductPriceSmall" align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['cart']['giftcerts'][$this->_sections['giftcert']['index']]['amount'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>
<?php endfor; endif;  endif; ?>
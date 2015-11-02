<?php /* Smarty version 2.6.12, created on 2011-10-11 06:22:21
         compiled from customer/main/dhl_ext_countries.tpl */ ?>
<?php func_load_lang($this, "customer/main/dhl_ext_countries.tpl","txt_dhl_ext_countries_note,lbl_please_select_one"); ?><?php if ($this->_tpl_vars['dhl_ext_countries']): ?>
<table cellspacing="1" cellpadding="2">
<tr>
    <td><label for="dhl_ext_country"><?php echo $this->_tpl_vars['lng']['txt_dhl_ext_countries_note']; ?>
</label>:</td>
    <td>
<select name="dhl_ext_country" id="dhl_ext_country"<?php if ($this->_tpl_vars['onchange']): ?> onchange="javascript: document.cartform.submit();"<?php endif; ?>>
    <option value=""><?php echo $this->_tpl_vars['lng']['lbl_please_select_one']; ?>
</option>
<?php $_from = $this->_tpl_vars['dhl_ext_countries']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['c']):
?>
    <option value="<?php echo $this->_tpl_vars['c']; ?>
"<?php if ($this->_tpl_vars['c'] == $this->_tpl_vars['dhl_ext_country']): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['c']; ?>
</option>
<?php endforeach; endif; unset($_from); ?>
</select>
    </td>
</tr>
</table>
<?php endif; ?>
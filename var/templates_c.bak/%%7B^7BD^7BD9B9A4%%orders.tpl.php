<?php /* Smarty version 2.6.12, created on 2011-10-11 06:30:26
         compiled from modules/QuickBooks/orders.tpl */ ?>
<?php func_load_lang($this, "modules/QuickBooks/orders.tpl","lbl_quickbooks_format"); ?><option value="qb"<?php if ($this->_tpl_vars['search_prefilled']['export_fmt'] == 'qb'): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_quickbooks_format']; ?>
</option>
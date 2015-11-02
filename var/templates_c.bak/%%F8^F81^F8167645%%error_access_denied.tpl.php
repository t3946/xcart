<?php /* Smarty version 2.6.12, created on 2011-10-11 06:54:06
         compiled from main/error_access_denied.tpl */ ?>
<?php func_load_lang($this, "main/error_access_denied.tpl","err_access_denied,err_access_denied_msg,lbl_error_id"); ?><h3><?php echo $this->_tpl_vars['lng']['err_access_denied']; ?>
</h3>
<?php echo $this->_tpl_vars['lng']['err_access_denied_msg']; ?>

<?php if ($this->_tpl_vars['id'] != ''): ?>
<br /><br />
<b><?php echo $this->_tpl_vars['lng']['lbl_error_id']; ?>
:</b> <?php echo $this->_tpl_vars['id']; ?>

<?php endif; ?>
<?php /* Smarty version 2.6.12, created on 2011-10-11 05:37:52
         compiled from form_validation_js.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'replace', 'form_validation_js.tpl', 18, false),)), $this); ?>
<?php func_load_lang($this, "form_validation_js.tpl","txt_out_of_stock"); ?><script type="text/javascript" language="JavaScript 1.2">
<!--
function FormValidation() {

    <?php if ($this->_tpl_vars['active_modules']['Product_Options'] != '' && $this->_tpl_vars['product_options'] != ''): ?>
    if(!check_exceptions()) {
        alert(exception_msg);
        return false;
    }
	<?php if ($this->_tpl_vars['product_options_js'] != ''): ?>
	<?php echo $this->_tpl_vars['product_options_js']; ?>

	<?php endif; ?>
    <?php endif; ?>

	if(document.getElementById('product_avail'))
	    if(document.getElementById('product_avail').value == 0) {
    	    alert("<?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['txt_out_of_stock'])) ? $this->_run_mod_handler('replace', true, $_tmp, "\n", "<br />") : smarty_modifier_replace($_tmp, "\n", "<br />")))) ? $this->_run_mod_handler('replace', true, $_tmp, "\r", ' ') : smarty_modifier_replace($_tmp, "\r", ' ')))) ? $this->_run_mod_handler('replace', true, $_tmp, '"', '\"') : smarty_modifier_replace($_tmp, '"', '\"')); ?>
");
        	return false;
	    }

    return true;
}
-->
</script>

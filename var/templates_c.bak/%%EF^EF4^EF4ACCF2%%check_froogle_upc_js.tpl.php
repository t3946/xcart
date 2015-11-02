<?php /* Smarty version 2.6.12, created on 2011-10-11 06:53:28
         compiled from check_froogle_upc_js.tpl */ ?>
<?php func_load_lang($this, "check_froogle_upc_js.tpl","err_froogle_wrong_upc"); ?>
<script type="text/javascript">
<!--

var txt_upc_error = '<?php echo $this->_tpl_vars['lng']['err_froogle_wrong_upc']; ?>
';

var upc_length = '<?php echo $this->_tpl_vars['UPC_LENGTH']; ?>
';
var isbn_length = '<?php echo $this->_tpl_vars['ISBN_LENGTH']; ?>
';
var ean_isbn_length = '<?php echo $this->_tpl_vars['EAN_ISBN_LENGTH']; ?>
';

-->
</script>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/include_js.tpl", 'smarty_include_vars' => array('src' => "check_froogle_upc.js")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
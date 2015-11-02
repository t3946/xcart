<?php /* Smarty version 2.6.12, created on 2011-10-11 05:38:06
         compiled from modules/Product_Options/check_options.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'modules/Product_Options/check_options.tpl', 27, false),array('modifier', 'escape', 'modules/Product_Options/check_options.tpl', 27, false),array('modifier', 'strip_tags', 'modules/Product_Options/check_options.tpl', 125, false),array('function', 'math', 'modules/Product_Options/check_options.tpl', 34, false),)), $this); ?>
<?php func_load_lang($this, "modules/Product_Options/check_options.tpl","txt_exception_warning,txt_exception_warning,txt_out_of_stock,lbl_no_items_available,txt_items_available,lbl_item,lbl_items,lbl_quantity,lbl_price_per_item,txt_note,lbl_including_tax"); ?><script type="text/javascript" language="JavaScript 1.2">
<!--

/*
variants array:
	0 - array:
		0 - taxed price
		1 - quantity
		2 - variantid if variant have thumbnail
		3 - weight
		4 - original price (without taxes)
		5 - productcode
	1 - array: variant options as classid => optionid
	2 - array: taxes as taxid => tax amount
	3 - wholesale prices array:
		0 - quantity
		1 - next quantity
		2 - taxed price
		3 - taxes array: as taxid => tax amount
		4 - original price (without taxes)
*/
var variants = [];
<?php if ($this->_tpl_vars['variants'] != ''):  $_from = $this->_tpl_vars['variants']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
variants[<?php echo $this->_tpl_vars['k']; ?>
] = [<?php echo '[';  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['v']['taxed_price'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['v']['price']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['v']['price'])))) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['product']['taxed_price']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['product']['taxed_price'])))) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['product']['price']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['product']['price']));  echo ', ';  echo ((is_array($_tmp=@$this->_tpl_vars['v']['avail'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0));  echo ', new Image(), \'';  echo ((is_array($_tmp=@$this->_tpl_vars['v']['weight'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0));  echo '\', ';  echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['v']['price'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['product']['price']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['product']['price'])))) ? $this->_run_mod_handler('default', true, $_tmp, '0') : smarty_modifier_default($_tmp, '0'));  echo ', "';  echo ((is_array($_tmp=$this->_tpl_vars['v']['productcode'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript'));  echo '"],'; ?>{<?php echo '';  $_from = $this->_tpl_vars['v']['options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['opts'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['opts']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['o']):
        $this->_foreach['opts']['iteration']++;
 echo '';  if ($this->_tpl_vars['o'] != ''):  echo '';  if (! ($this->_foreach['opts']['iteration'] <= 1)):  echo ', ';  endif;  echo '';  echo ((is_array($_tmp=@$this->_tpl_vars['o']['classid'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0));  echo ': ';  echo ((is_array($_tmp=@$this->_tpl_vars['o']['optionid'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0));  echo '';  endif;  echo '';  endforeach; endif; unset($_from);  echo ''; ?>}<?php echo ','; ?>{<?php echo '';  $_from = $this->_tpl_vars['v']['taxes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['taxes'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['taxes']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['id'] => $this->_tpl_vars['t']):
        $this->_foreach['taxes']['iteration']++;
 echo '';  if (! ($this->_foreach['taxes']['iteration'] <= 1)):  echo ', ';  endif;  echo '';  echo $this->_tpl_vars['id'];  echo ': ';  echo ((is_array($_tmp=@$this->_tpl_vars['t'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0));  echo '';  endforeach; endif; unset($_from);  echo ''; ?>}<?php echo ',[';  $_from = $this->_tpl_vars['v']['wholesale']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['whl'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['whl']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['p'] => $this->_tpl_vars['w']):
        $this->_foreach['whl']['iteration']++;
 echo '[';  echo ((is_array($_tmp=@$this->_tpl_vars['w']['quantity'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0));  echo ',';  if ($this->_tpl_vars['w']['next_quantity']):  echo '';  echo smarty_function_math(array('equation' => "x-1",'x' => $this->_tpl_vars['w']['next_quantity']), $this); echo '';  else:  echo '0';  endif;  echo ',';  echo ((is_array($_tmp=@$this->_tpl_vars['w']['taxed_price'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['product']['taxed_price']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['product']['taxed_price']));  echo ','; ?>{<?php echo '';  $_from = $this->_tpl_vars['w']['taxes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['whlt'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['whlt']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['kt'] => $this->_tpl_vars['t']):
        $this->_foreach['whlt']['iteration']++;
 echo '';  if (! ($this->_foreach['whlt']['iteration'] <= 1)):  echo ', ';  endif;  echo '';  echo $this->_tpl_vars['kt'];  echo ': ';  echo ((is_array($_tmp=@$this->_tpl_vars['t'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0));  echo '';  endforeach; endif; unset($_from);  echo ''; ?>}<?php echo ',';  echo ((is_array($_tmp=@$this->_tpl_vars['w']['price'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['product']['price']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['product']['price']));  echo ']';  if (! ($this->_foreach['whl']['iteration'] == $this->_foreach['whl']['total'])):  echo ',';  endif;  echo '';  endforeach; endif; unset($_from);  echo ']'; ?>
];
<?php if ($this->_tpl_vars['v']['is_image']): ?>
variants[<?php echo $this->_tpl_vars['k']; ?>
][0][2].src = "<?php if ($this->_tpl_vars['v']['image_url'] != ''):  echo $this->_tpl_vars['v']['image_url'];  else:  if ($this->_tpl_vars['full_url']):  echo $this->_tpl_vars['http_location'];  else:  echo $this->_tpl_vars['xcart_web_dir'];  endif; ?>/image.php?id=<?php echo $this->_tpl_vars['k']; ?>
&type=W<?php endif; ?>"; 
<?php endif;  endforeach; endif; unset($_from);  endif; ?>

/*
modifiers array: as clasid => array: as optionid => array:
	0 - price_modifier
	1 - modifier_type
	2 - taxes array: as taxid => tax amount
*/
var modifiers = [];
// names array: as classid => class name
var names = [];
<?php $_from = $this->_tpl_vars['product_options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
names[<?php echo $this->_tpl_vars['v']['classid']; ?>
] = {class_name: "<?php echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['v']['class_orig'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['v']['class']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['v']['class'])))) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
", options: []};
<?php $_from = $this->_tpl_vars['v']['options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['opts'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['opts']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['o']):
        $this->_foreach['opts']['iteration']++;
?>
names[<?php echo $this->_tpl_vars['v']['classid']; ?>
]['options'][<?php echo $this->_tpl_vars['o']['optionid']; ?>
] = "<?php echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['o']['option_name_orig'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['o']['option_name']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['o']['option_name'])))) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
";
<?php endforeach; endif; unset($_from);  if ($this->_tpl_vars['v']['is_modifier'] == 'Y'): ?>
modifiers[<?php echo $this->_tpl_vars['v']['classid']; ?>
] = {<?php echo '';  $_from = $this->_tpl_vars['v']['options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['opts'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['opts']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['o']):
        $this->_foreach['opts']['iteration']++;
 echo '';  echo $this->_tpl_vars['o']['optionid'];  echo ': [';  echo ((is_array($_tmp=@$this->_tpl_vars['o']['price_modifier'])) ? $this->_run_mod_handler('default', true, $_tmp, "0.00") : smarty_modifier_default($_tmp, "0.00"));  echo ',\'';  echo ((is_array($_tmp=@$this->_tpl_vars['o']['modifier_type'])) ? $this->_run_mod_handler('default', true, $_tmp, "$") : smarty_modifier_default($_tmp, "$"));  echo '\','; ?>{<?php echo '';  $_from = $this->_tpl_vars['o']['taxes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['optt'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['optt']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['id'] => $this->_tpl_vars['t']):
        $this->_foreach['optt']['iteration']++;
 echo '';  if (! ($this->_foreach['optt']['iteration'] <= 1)):  echo ', ';  endif;  echo '';  echo $this->_tpl_vars['id'];  echo ': ';  echo ((is_array($_tmp=@$this->_tpl_vars['t'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0));  echo '';  endforeach; endif; unset($_from);  echo ''; ?>}<?php echo ']';  if (! ($this->_foreach['opts']['iteration'] == $this->_foreach['opts']['total'])):  echo ',';  endif;  echo '';  endforeach; endif; unset($_from);  echo ''; ?>
};
<?php endif;  endforeach; endif; unset($_from); ?>

/*
taxes array: as taxid => array()
	0 - calculated tax value for default product price
	1 - tax name
	2 - tax type ($ or %)
	3 - tax value
*/
var taxes = [];
<?php if ($this->_tpl_vars['product']['taxes']):  $_from = $this->_tpl_vars['product']['taxes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['tax_name'] => $this->_tpl_vars['tax']):
 if ($this->_tpl_vars['tax']['display_including_tax'] == 'Y' && ( $this->_tpl_vars['tax']['display_info'] == 'A' || $this->_tpl_vars['tax']['display_info'] == 'V' )): ?>
taxes[<?php echo $this->_tpl_vars['tax']['taxid']; ?>
] = [<?php echo ((is_array($_tmp=@$this->_tpl_vars['tax']['tax_value'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
, "<?php echo $this->_tpl_vars['tax']['tax_display_name']; ?>
", "<?php echo $this->_tpl_vars['tax']['rate_type']; ?>
", "<?php echo $this->_tpl_vars['tax']['rate_value']; ?>
"];
<?php endif;  endforeach; endif; unset($_from);  endif; ?>

// exceptions array: as exctionid => array: as clasid => optionid
var exceptions = [];
<?php if ($this->_tpl_vars['product_options_ex'] != ''):  $_from = $this->_tpl_vars['product_options_ex']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
exceptions[<?php echo $this->_tpl_vars['k']; ?>
] = [];
<?php $_from = $this->_tpl_vars['v']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['o']):
?>
exceptions[<?php echo $this->_tpl_vars['k']; ?>
][<?php echo $this->_tpl_vars['o']['classid']; ?>
] = <?php echo $this->_tpl_vars['o']['optionid']; ?>
;
<?php endforeach; endif; unset($_from); ?> 
<?php endforeach; endif; unset($_from); ?> 
<?php endif; ?>

/*
_product_wholesale array: as id => array:
	0 - quantity
	1 - next quantity
	2 - taxed price
	3 - taxes array: as taxid => tax amount
	4 - original price (without taxes)
*/
var product_wholesale = [];
var _product_wholesale = [];
<?php if ($this->_tpl_vars['product_wholesale'] != ''):  $_from = $this->_tpl_vars['product_wholesale']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
_product_wholesale[<?php echo $this->_tpl_vars['k']; ?>
] = [<?php echo ((is_array($_tmp=@$this->_tpl_vars['v']['quantity'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
,<?php echo ((is_array($_tmp=@$this->_tpl_vars['v']['next_quantity'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
,<?php echo ((is_array($_tmp=@$this->_tpl_vars['v']['taxed_price'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['product']['taxed_price']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['product']['taxed_price'])); ?>
, [], <?php echo ((is_array($_tmp=@$this->_tpl_vars['v']['price'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['product']['price']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['product']['price'])); ?>
];
<?php if ($this->_tpl_vars['v']['taxes'] != ''):  $_from = $this->_tpl_vars['v']['taxes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['kt'] => $this->_tpl_vars['t']):
?>
_product_wholesale[<?php echo $this->_tpl_vars['k']; ?>
][3][<?php echo $this->_tpl_vars['kt']; ?>
] = <?php echo ((is_array($_tmp=@$this->_tpl_vars['t'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
;
<?php endforeach; endif; unset($_from);  endif;  endforeach; endif; unset($_from);  endif; ?>

var product_image = new Image();
product_image.src = "<?php if ($this->_tpl_vars['product']['tmbn_url']):  echo $this->_tpl_vars['product']['tmbn_url'];  else:  if ($this->_tpl_vars['full_url']):  echo $this->_tpl_vars['http_location'];  else:  echo $this->_tpl_vars['xcart_web_dir'];  endif; ?>/image.php?id=<?php echo $this->_tpl_vars['product']['productid']; ?>
&type=<?php if ($this->_tpl_vars['product']['is_image']): ?>P<?php else: ?>T<?php endif;  endif; ?>";
var exception_msg = "<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['txt_exception_warning'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
!";
var exception_msg_html = "<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['txt_exception_warning'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
!";
var txt_out_of_stock = "<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['txt_out_of_stock'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
";
var pconf_price = <?php echo ((is_array($_tmp=@$this->_tpl_vars['taxed_total_cost'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>

var default_price = <?php echo ((is_array($_tmp=@$this->_tpl_vars['product']['taxed_price'])) ? $this->_run_mod_handler('default', true, $_tmp, '0') : smarty_modifier_default($_tmp, '0')); ?>
;
var currency_symbol = "<?php echo ((is_array($_tmp=$this->_tpl_vars['config']['General']['currency_symbol'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
";
var alter_currency_symbol = "<?php echo ((is_array($_tmp=$this->_tpl_vars['config']['General']['alter_currency_symbol'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
";
var alter_currency_rate = <?php echo ((is_array($_tmp=@$this->_tpl_vars['config']['General']['alter_currency_rate'])) ? $this->_run_mod_handler('default', true, $_tmp, '0') : smarty_modifier_default($_tmp, '0')); ?>
;
var lbl_no_items_available = "<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_no_items_available'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
";
var txt_items_available = "<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['txt_items_available'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
";
var list_price = <?php echo ((is_array($_tmp=@$this->_tpl_vars['product']['list_price'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
;
var price = <?php echo ((is_array($_tmp=@$this->_tpl_vars['product']['taxed_price'])) ? $this->_run_mod_handler('default', true, $_tmp, '0') : smarty_modifier_default($_tmp, '0')); ?>
;
var orig_price = <?php echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['product']['price'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['product']['taxed_price']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['product']['taxed_price'])))) ? $this->_run_mod_handler('default', true, $_tmp, '0') : smarty_modifier_default($_tmp, '0')); ?>
;
var mq = <?php echo ((is_array($_tmp=@$this->_tpl_vars['config']['Appearance']['max_select_quantity'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
;
var dynamic_save_money_enabled = <?php if ($this->_tpl_vars['config']['Product_Options']['dynamic_save_money_enabled'] == 'Y'): ?>true<?php else: ?>false<?php endif; ?>;
var is_unlimit = <?php if ($this->_tpl_vars['config']['General']['unlimited_products'] == 'Y'): ?>true<?php else: ?>false<?php endif; ?>;

var lbl_item = "<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_item'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
";
var lbl_items = "<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_items'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
";
var lbl_quantity = "<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_quantity'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
";
var lbl_price = "<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_price_per_item'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
";
var txt_note = "<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['txt_note'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
";
var lbl_including_tax = "<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_including_tax'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
";

-->
</script>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/include_js.tpl", 'smarty_include_vars' => array('src' => "modules/Product_Options/func.js")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
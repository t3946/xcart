<?php /* Smarty version 2.6.12, created on 2011-10-11 14:57:37
         compiled from customer/main/search_result.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'stripslashes', 'customer/main/search_result.tpl', 41, false),array('modifier', 'escape', 'customer/main/search_result.tpl', 41, false),array('modifier', 'strip_tags', 'customer/main/search_result.tpl', 43, false),array('modifier', 'default', 'customer/main/search_result.tpl', 162, false),array('modifier', 'formatprice', 'customer/main/search_result.tpl', 162, false),array('modifier', 'substitute', 'customer/main/search_result.tpl', 238, false),)), $this); ?>
<?php func_load_lang($this, "customer/main/search_result.tpl","lbl_search_for_pattern,lbl_search,lbl_all_word,lbl_any_word,lbl_exact_phrase,lbl_search_in,lbl_product_title,lbl_short_description,lbl_det_description,lbl_sku,lbl_search_also_in,lbl_advanced_search_options,lbl_brands,lbl_manufacturers,lbl_price,lbl_weight,lbl_search,lbl_reset,lbl_search_products,txt_N_results_found,txt_displaying_X_Y_results,txt_N_results_found,lbl_search_again,lbl_search_results"); ?><br>
<!-- IN THIS SECTION -->

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog_tools.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<!-- IN THIS SECTION -->


<?php if ($this->_tpl_vars['mode'] != 'search' || $this->_tpl_vars['products'] == ""): ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/include_js.tpl", 'smarty_include_vars' => array('src' => "reset.js")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<script type="text/javascript">
<!--
var searchform_def = new Array();
searchform_def[0] = new Array('posted_data[category_main]', true);
searchform_def[1] = new Array('posted_data[search_in_subcategories]', true);
searchform_def[2] = new Array('posted_data[by_title]', true);
searchform_def[3] = new Array('posted_data[by_shortdescr]', true);
searchform_def[4] = new Array('posted_data[by_fulldescr]', true);
searchform_def[5] = new Array('posted_data[by_keywords]', true);
searchform_def[6] = new Array('posted_data[price_min]', '<?php echo $this->_tpl_vars['zero']; ?>
');
searchform_def[7] = new Array('posted_data[avail_min]', '0');
searchform_def[8] = new Array('posted_data[weight_min]', '<?php echo $this->_tpl_vars['zero']; ?>
');
-->
</script>

<?php ob_start(); ?>

<br>

<form name="searchform" action="search.php" method="post">
<input type="hidden" name="mode" value="search" />
<table cellpadding="1" cellspacing="5" width="100%">

<tr>
<td height="10" width="25%" class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_search_for_pattern']; ?>
:</td>
	<td width="10" height="10">&nbsp;</td>
	<td height="10" width="75%">
<input type="text" name="posted_data[substring]" size="30" style="width:68%" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['search_prefilled']['substring'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
&nbsp;
<input type="submit" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_search'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
	</td>
</tr>

<?php if ($this->_tpl_vars['config']['General']['allow_search_by_words'] == 'Y'): ?>
<tr>
	<td height="10" colspan="2"></td>
	<td>

<table cellpadding="0" cellspacing="0">
<tr>
	<td width="5"><input type="radio" id="including_all" name="posted_data[including]" value="all"<?php if ($this->_tpl_vars['search_prefilled'] == "" || $this->_tpl_vars['search_prefilled']['including'] == '' || $this->_tpl_vars['search_prefilled']['including'] == 'all'): ?> checked="checked"<?php endif; ?> /></td>
	<td nowrap="nowrap"><label for="including_all"><?php echo $this->_tpl_vars['lng']['lbl_all_word']; ?>
</label>&nbsp;&nbsp;</td>

	<td width="5"><input type="radio" id="including_any" name="posted_data[including]" value="any"<?php if ($this->_tpl_vars['search_prefilled']['including'] == 'any'): ?> checked="checked"<?php endif; ?> /></td>
	<td nowrap="nowrap"><label for="including_any"><?php echo $this->_tpl_vars['lng']['lbl_any_word']; ?>
</label>&nbsp;&nbsp;</td>

	<td width="5"><input type="radio" id="including_phrase" name="posted_data[including]" value="phrase"<?php if ($this->_tpl_vars['search_prefilled']['including'] == 'phrase'): ?> checked="checked"<?php endif; ?> /></td>
	<td nowrap="nowrap"><label for="including_phrase"><?php echo $this->_tpl_vars['lng']['lbl_exact_phrase']; ?>
</label></td>
</tr>
</table>

	</td>
</tr>
<?php endif; ?>

<tr>
	<td height="10" class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_search_in']; ?>
:</td>
	<td height="10"><font class="CustomerMessage"></font></td>
	<td>

<table cellpadding="0" cellspacing="0">
<tr>
	<td width="5"><input type="checkbox" id="posted_data_by_title" name="posted_data[by_title]"<?php if ($this->_tpl_vars['search_prefilled'] == "" || $this->_tpl_vars['search_prefilled']['by_title']): ?> checked="checked"<?php endif; ?> /></td>
	<td nowrap="nowrap"><label for="posted_data_by_title"><?php echo $this->_tpl_vars['lng']['lbl_product_title']; ?>
</label>&nbsp;&nbsp;</td>

	<td width="5"><input type="checkbox" id="posted_data_by_shortdescr" name="posted_data[by_shortdescr]"<?php if ($this->_tpl_vars['search_prefilled'] == "" || $this->_tpl_vars['search_prefilled']['by_shortdescr']): ?> checked="checked"<?php endif; ?> /></td>
	<td nowrap="nowrap"><label for="posted_data_by_shortdescr"><?php echo $this->_tpl_vars['lng']['lbl_short_description']; ?>
</label>&nbsp;&nbsp;</td>

	<td width="5"><input type="checkbox" id="posted_data_by_fulldescr" name="posted_data[by_fulldescr]"<?php if ($this->_tpl_vars['search_prefilled'] == "" || $this->_tpl_vars['search_prefilled']['by_fulldescr']): ?> checked="checked"<?php endif; ?> /></td>
	<td nowrap="nowrap"><label for="posted_data_by_fulldescr"><?php echo $this->_tpl_vars['lng']['lbl_det_description']; ?>
</label>&nbsp;&nbsp;</td>

	<td width="5"><input type="checkbox" id="posted_data_by_sku" name="posted_data[by_sku]"<?php if ($this->_tpl_vars['search_prefilled'] == "" || $this->_tpl_vars['search_prefilled']['by_sku']): ?> checked="checked"<?php endif; ?> /></td>
	<td nowrap="nowrap"><label for="posted_data_by_sku"><?php echo $this->_tpl_vars['lng']['lbl_sku']; ?>
</label></td>
</tr>
</table>

	</td>
</tr>

<?php if ($this->_tpl_vars['active_modules']['Extra_Fields'] && $this->_tpl_vars['extra_fields'] != ''): ?>
<tr>
	<td height="10" class="FormButton" nowrap="nowrap" valign="top"><?php echo $this->_tpl_vars['lng']['lbl_search_also_in']; ?>
:</td>
	<td height="10"><font class="CustomerMessage"></font></td>
	<td>

<table cellpadding="0" cellspacing="0">
<?php $_from = $this->_tpl_vars['extra_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
<tr>
	<td width="5"><input type="checkbox" id="ef_<?php echo $this->_tpl_vars['v']['fieldid']; ?>
" name="posted_data[extra_fields][<?php echo $this->_tpl_vars['v']['fieldid']; ?>
]"<?php if ($this->_tpl_vars['v']['selected'] == 'Y'): ?> checked="checked"<?php endif; ?> /></td>
	<td><label for="ef_<?php echo $this->_tpl_vars['v']['fieldid']; ?>
"><?php echo $this->_tpl_vars['v']['field']; ?>
</label></td>
</tr>
<?php endforeach; endif; unset($_from); ?>
</table>

	</td>
</tr>
<?php endif; ?>

</table>

<?php if ($this->_tpl_vars['config']['Search_products']['search_products_category'] == 'Y' || ( $this->_tpl_vars['active_modules']['Brands'] && $this->_tpl_vars['config']['Search_products']['search_products_manufacturers'] == 'Y' ) || ( $this->_tpl_vars['active_modules']['Manufacturers'] && $this->_tpl_vars['config']['Search_products']['search_products_manufacturers'] == 'Y' ) || $this->_tpl_vars['config']['Search_products']['search_products_price'] == 'Y' || $this->_tpl_vars['config']['Search_products']['search_products_weight'] == 'Y'): ?>

<table cellpadding="1" cellspacing="5" width="100%">

<tr>
	<td colspan="3"><br /><br /><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_advanced_search_options'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>

<?php if ($this->_tpl_vars['active_modules']['Brands'] && $this->_tpl_vars['brands'] != '' && $this->_tpl_vars['config']['Search_products']['search_products_manufacturers'] == 'Y'):  ob_start(); ?> 
<?php unset($this->_sections['mnf']);
$this->_sections['mnf']['name'] = 'mnf';
$this->_sections['mnf']['loop'] = is_array($_loop=$this->_tpl_vars['brands']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['mnf']['show'] = true;
$this->_sections['mnf']['max'] = $this->_sections['mnf']['loop'];
$this->_sections['mnf']['step'] = 1;
$this->_sections['mnf']['start'] = $this->_sections['mnf']['step'] > 0 ? 0 : $this->_sections['mnf']['loop']-1;
if ($this->_sections['mnf']['show']) {
    $this->_sections['mnf']['total'] = $this->_sections['mnf']['loop'];
    if ($this->_sections['mnf']['total'] == 0)
        $this->_sections['mnf']['show'] = false;
} else
    $this->_sections['mnf']['total'] = 0;
if ($this->_sections['mnf']['show']):

            for ($this->_sections['mnf']['index'] = $this->_sections['mnf']['start'], $this->_sections['mnf']['iteration'] = 1;
                 $this->_sections['mnf']['iteration'] <= $this->_sections['mnf']['total'];
                 $this->_sections['mnf']['index'] += $this->_sections['mnf']['step'], $this->_sections['mnf']['iteration']++):
$this->_sections['mnf']['rownum'] = $this->_sections['mnf']['iteration'];
$this->_sections['mnf']['index_prev'] = $this->_sections['mnf']['index'] - $this->_sections['mnf']['step'];
$this->_sections['mnf']['index_next'] = $this->_sections['mnf']['index'] + $this->_sections['mnf']['step'];
$this->_sections['mnf']['first']      = ($this->_sections['mnf']['iteration'] == 1);
$this->_sections['mnf']['last']       = ($this->_sections['mnf']['iteration'] == $this->_sections['mnf']['total']);
?>
	<option value="<?php echo $this->_tpl_vars['brands'][$this->_sections['mnf']['index']]['brandid']; ?>
"<?php if ($this->_tpl_vars['brands'][$this->_sections['mnf']['index']]['selected'] == 'Y'): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['brands'][$this->_sections['mnf']['index']]['brand']; ?>
</option>
<?php endfor; endif;  $this->_smarty_vars['capture']['brands_items'] = ob_get_contents(); ob_end_clean(); ?>
<tr>
	<td height="10" class="FormButton" nowrap="nowrap" width="25%"><?php echo $this->_tpl_vars['lng']['lbl_brands']; ?>
:</td>
	<td height="10" width="10">&nbsp;</td>
	<td height="10" width="75%">
	<select name="posted_data[brands][]" style="width: 70%;" multiple="multiple" size="<?php if ($this->_sections['mnf']['total'] > 10): ?>10<?php else:  echo $this->_sections['mnf']['total'];  endif; ?>">
<?php echo $this->_smarty_vars['capture']['brands_items']; ?>

	</select>
	</td>
</tr>
<?php elseif (! $this->_tpl_vars['active_modules']['Brands'] && $this->_tpl_vars['active_modules']['Manufacturers'] && $this->_tpl_vars['manufacturers'] != '' && $this->_tpl_vars['config']['Search_products']['search_products_manufacturers'] == 'Y'):  ob_start(); ?> 
<?php unset($this->_sections['mnf']);
$this->_sections['mnf']['name'] = 'mnf';
$this->_sections['mnf']['loop'] = is_array($_loop=$this->_tpl_vars['manufacturers']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['mnf']['show'] = true;
$this->_sections['mnf']['max'] = $this->_sections['mnf']['loop'];
$this->_sections['mnf']['step'] = 1;
$this->_sections['mnf']['start'] = $this->_sections['mnf']['step'] > 0 ? 0 : $this->_sections['mnf']['loop']-1;
if ($this->_sections['mnf']['show']) {
    $this->_sections['mnf']['total'] = $this->_sections['mnf']['loop'];
    if ($this->_sections['mnf']['total'] == 0)
        $this->_sections['mnf']['show'] = false;
} else
    $this->_sections['mnf']['total'] = 0;
if ($this->_sections['mnf']['show']):

            for ($this->_sections['mnf']['index'] = $this->_sections['mnf']['start'], $this->_sections['mnf']['iteration'] = 1;
                 $this->_sections['mnf']['iteration'] <= $this->_sections['mnf']['total'];
                 $this->_sections['mnf']['index'] += $this->_sections['mnf']['step'], $this->_sections['mnf']['iteration']++):
$this->_sections['mnf']['rownum'] = $this->_sections['mnf']['iteration'];
$this->_sections['mnf']['index_prev'] = $this->_sections['mnf']['index'] - $this->_sections['mnf']['step'];
$this->_sections['mnf']['index_next'] = $this->_sections['mnf']['index'] + $this->_sections['mnf']['step'];
$this->_sections['mnf']['first']      = ($this->_sections['mnf']['iteration'] == 1);
$this->_sections['mnf']['last']       = ($this->_sections['mnf']['iteration'] == $this->_sections['mnf']['total']);
?>
	<option value="<?php echo $this->_tpl_vars['manufacturers'][$this->_sections['mnf']['index']]['manufacturerid']; ?>
"<?php if ($this->_tpl_vars['manufacturers'][$this->_sections['mnf']['index']]['selected'] == 'Y'): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['manufacturers'][$this->_sections['mnf']['index']]['manufacturer']; ?>
</option>
<?php endfor; endif;  $this->_smarty_vars['capture']['manufacturers_items'] = ob_get_contents(); ob_end_clean(); ?>
<tr>
	<td height="10" class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_manufacturers']; ?>
:</td>
	<td height="10"></td>
	<td height="10">
	<select name="posted_data[manufacturers][]" style="width: 70%;" multiple="multiple" size="<?php if ($this->_sections['mnf']['total'] > 5): ?>5<?php else:  echo $this->_sections['mnf']['total'];  endif; ?>">
<?php echo $this->_smarty_vars['capture']['manufacturers_items']; ?>

	</select>
	</td>
</tr>
<?php endif; ?>

<?php if ($this->_tpl_vars['config']['Search_products']['search_products_price'] == 'Y'): ?>
<tr>
	<td height="10" class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_price']; ?>
 (<?php echo $this->_tpl_vars['config']['General']['currency_symbol']; ?>
):</td>
	<td height="10"><font class="CustomerMessage"></font></td>
	<td height="10">

<table cellpadding="0" cellspacing="0">
<tr>
	<td><input type="text" size="10" maxlength="15" name="posted_data[price_min]" value="<?php if ($this->_tpl_vars['search_prefilled'] == ""):  echo $this->_tpl_vars['zero'];  else:  echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['search_prefilled']['price_min'])) ? $this->_run_mod_handler('default', true, $_tmp, '0') : smarty_modifier_default($_tmp, '0')))) ? $this->_run_mod_handler('formatprice', true, $_tmp) : smarty_modifier_formatprice($_tmp));  endif; ?>" /></td>
	<td>&nbsp;-&nbsp;</td>
	<td><input type="text" size="10" maxlength="15" name="posted_data[price_max]" value="<?php echo $this->_tpl_vars['search_prefilled']['price_max']; ?>
" /></td>
</tr>
</table>

	</td>
</tr>
<?php endif; ?>

<?php if ($this->_tpl_vars['config']['Search_products']['search_products_weight'] == 'Y'): ?>
<tr>
	<td height="10" class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_weight']; ?>
 (<?php echo $this->_tpl_vars['config']['General']['weight_symbol']; ?>
):</td>
	<td height="10"><font class="CustomerMessage"></font></td>
	<td height="10">

<table cellpadding="0" cellspacing="0">
<tr>
	<td><input type="text" size="10" maxlength="10" name="posted_data[weight_min]" value="<?php if ($this->_tpl_vars['search_prefilled'] == ""):  echo $this->_tpl_vars['zero'];  else:  echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['search_prefilled']['weight_min'])) ? $this->_run_mod_handler('default', true, $_tmp, '0') : smarty_modifier_default($_tmp, '0')))) ? $this->_run_mod_handler('formatprice', true, $_tmp) : smarty_modifier_formatprice($_tmp));  endif; ?>" /></td>
	<td>&nbsp;-&nbsp;</td>
	<td><input type="text" size="10" maxlength="10" name="posted_data[weight_max]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['search_prefilled']['weight_max'])) ? $this->_run_mod_handler('formatprice', true, $_tmp) : smarty_modifier_formatprice($_tmp)); ?>
" /></td>
</tr>
</table>

	</td>
</tr>
<?php endif; ?>

<tr>
	<td colspan="2">&nbsp;</td>
	<td class="SubmitBox">
<br />
<input type="submit" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_search'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
&nbsp;&nbsp;&nbsp;
<input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_reset'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: reset_form('searchform', searchform_def);" />
	</td>
</tr>

</table>

<?php if ($this->_tpl_vars['search_prefilled']['need_advanced_options']): ?>
<script type="text/javascript" language="JavaScript 1.2"><!--
visibleBox('1');
--></script>
<?php endif;  endif; ?>
</form>

<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_search_products'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<!-- SEARCH FORM DIALOG END -->

<?php endif; ?>

<!-- SEARCH RESULTS SUMMARY -->
<?php if ($this->_tpl_vars['mode'] == 'search' && $this->_tpl_vars['products'] != ""): ?>

<!-- SEARCH RESULTS START -->

<?php ob_start(); ?>

<?php if ($this->_tpl_vars['mode'] == 'search'):  if ($this->_tpl_vars['total_items'] > '1'):  echo ((is_array($_tmp=$this->_tpl_vars['lng']['txt_N_results_found'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'items', $this->_tpl_vars['total_items']) : smarty_modifier_substitute($_tmp, 'items', $this->_tpl_vars['total_items'])); ?>
<br />
<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['txt_displaying_X_Y_results'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'first_item', $this->_tpl_vars['first_item'], 'last_item', $this->_tpl_vars['last_item']) : smarty_modifier_substitute($_tmp, 'first_item', $this->_tpl_vars['first_item'], 'last_item', $this->_tpl_vars['last_item'])); ?>

<?php elseif ($this->_tpl_vars['total_items'] == '0'):  echo ((is_array($_tmp=$this->_tpl_vars['lng']['txt_N_results_found'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'items', 0) : smarty_modifier_substitute($_tmp, 'items', 0)); ?>

<?php endif;  endif; ?>

<div align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/button.tpl", 'smarty_include_vars' => array('button_title' => $this->_tpl_vars['lng']['lbl_search_again'],'href' => "search.php")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></div>

<?php if ($this->_tpl_vars['total_pages'] > 2):  $this->assign('navpage', $this->_tpl_vars['navigation_page']);  endif; ?>


<table cellpadding="0" cellspacing="0" width="100%">
<?php if ($this->_tpl_vars['sort_fields']): ?>
<tr>
	<td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/search_sort_by.tpl", 'smarty_include_vars' => array('sort_fields' => $this->_tpl_vars['sort_fields'],'selected' => $this->_tpl_vars['search_prefilled']['sort_field'],'direction' => $this->_tpl_vars['search_prefilled']['sort_direction'],'url' => "search.php?mode=search".($this->_tpl_vars['args_url_sort'])."&")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<hr size="1" noshade="noshade" /> 
	<br />
	</td>
</tr>
<?php endif; ?>
<tr>
<td>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/navigation.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/products.tpl", 'smarty_include_vars' => array('products' => $this->_tpl_vars['products'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<br />

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/navigation.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

</td>
</tr>
</table>


<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_search_results'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php endif; ?>
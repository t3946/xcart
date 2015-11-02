<?php /* Smarty version 2.6.12, created on 2011-10-11 06:33:33
         compiled from main/search_result.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'strip_tags', 'main/search_result.tpl', 107, false),array('modifier', 'escape', 'main/search_result.tpl', 107, false),array('modifier', 'formatprice', 'main/search_result.tpl', 325, false),array('modifier', 'substitute', 'main/search_result.tpl', 602, false),array('modifier', 'cat', 'main/search_result.tpl', 710, false),)), $this); ?>
<?php func_load_lang($this, "main/search_result.tpl","lbl_products_management,lbl_search_in_category_id,lbl_as,lbl_main_category,lbl_additional_category,lbl_search_in_subcategories,lbl_search_for_pattern,lbl_search,lbl_all_word,lbl_any_word,lbl_exact_phrase,lbl_search_in,lbl_product_title,lbl_short_description,lbl_det_description,lbl_keywords,lbl_product_name_froogle,lbl_search_also_in,lbl_search_and_modify,lbl_search_and_export,lbl_click_to_open,lbl_click_to_close,lbl_advanced_search_options,lbl_advanced_search_options,lbl_manufacturers,lbl_brands,lbl_sku,lbl_productid,lbl_provider,lbl_price,lbl_quantity,lbl_weight,lbl_availability,lbl_avail_for_sale,lbl_hidden,lbl_disabled,lbl_bundled,lbl_product_feature_classes,lbl_discount_table_options,lbl_empty_discount_slope,lbl_discount_slope,lbl_discount_table,lbl_outdated_discount_table,lbl_additional_options,lbl_free_shipping,lbl_assigned,lbl_not_assigned,lbl_shipping_freight,lbl_assigned,lbl_not_assigned,lbl_global_discounts,lbl_assigned,lbl_not_assigned,lbl_tax_exempt,lbl_assigned,lbl_not_assigned,lbl_min_order_amount,lbl_assigned,lbl_not_assigned,lbl_lowlimit_in_stock,lbl_assigned,lbl_not_assigned,lbl_list_price,lbl_assigned,lbl_not_assigned,lbl_custom_options,lbl_find_multisku_only,lbl_empty_froogle_title,lbl_froogle_differs,lbl_no_thumbnail,lbl_no_product_image,lbl_no_detailed_images,lbl_products_with_broken_images,lbl_search,lbl_reset,lbl_generate_discounts,lbl_improve_froogle_titles,lbl_search_products,txt_N_results_found,txt_displaying_X_Y_results,txt_N_results_found,lbl_search_again,lbl_delete_selected,txt_delete_products_warning,lbl_update,lbl_modify_selected,lbl_generate_discounts,lbl_export,lbl_export_all_found,txt_operation_for_first_selected_only,lbl_preview_product,lbl_clone_product,lbl_generate_html_links,lbl_update,lbl_bulk_manage_all_fount_dbsr_products,lbl_instructions,lbl_instructions,lbl_txt_bulk_manage_text,lbl_csv_delimiter,lbl_comma,lbl_semicolon,lbl_tab,lbl_select_product_updation,lbl_warning,txt_max_file_size_that_can_be_uploaded,lbl_submit,lbl_search_results"); ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "page_title.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_products_management'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<!-- IN THIS SECTION -->

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog_tools.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<!-- IN THIS SECTION -->

<br />

<?php if ($this->_tpl_vars['mode'] != 'search' || $this->_tpl_vars['products'] == ""): ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/multirow.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  $_smarty_tpl_vars = $this->_tpl_vars;
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
searchform_def[9] = new Array('posted_data[discount_slope]', '<?php echo $this->_tpl_vars['zero']; ?>
');
searchform_def[10] = new Array('posted_data[by_froogle_title]', true);
searchform_def[11] = new Array('posted_data[empty_froogle_title]', false);
searchform_def[12] = new Array('posted_data[no_thumbnail]', false);
searchform_def[13] = new Array('posted_data[no_product_image]', false);
searchform_def[14] = new Array('posted_data[no_detailed_images]', false);
searchform_def[15] = new Array('posted_data[broken_images]', false);
searchform_def[16] = new Array('posted_data[outdated_discount_table]', false);
searchform_def[17] = new Array('posted_data[categoryid]', '');
searchform_def[18] = new Array('posted_data[substring]', '');
searchform_def[19] = new Array('posted_data[productid]', '');
searchform_def[20] = new Array('posted_data[provider]', '');
searchform_def[21] = new Array('posted_data[froogle_differs]', true);

<?php if ($this->_tpl_vars['current_area'] != 'C'): ?>
var extraSkuRows = [ 
<?php unset($this->_sections['extra_sku_array']);
$this->_sections['extra_sku_array']['name'] = 'extra_sku_array';
$this->_sections['extra_sku_array']['loop'] = is_array($_loop=$this->_tpl_vars['search_prefilled']['extra_sku']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['extra_sku_array']['show'] = true;
$this->_sections['extra_sku_array']['max'] = $this->_sections['extra_sku_array']['loop'];
$this->_sections['extra_sku_array']['step'] = 1;
$this->_sections['extra_sku_array']['start'] = $this->_sections['extra_sku_array']['step'] > 0 ? 0 : $this->_sections['extra_sku_array']['loop']-1;
if ($this->_sections['extra_sku_array']['show']) {
    $this->_sections['extra_sku_array']['total'] = $this->_sections['extra_sku_array']['loop'];
    if ($this->_sections['extra_sku_array']['total'] == 0)
        $this->_sections['extra_sku_array']['show'] = false;
} else
    $this->_sections['extra_sku_array']['total'] = 0;
if ($this->_sections['extra_sku_array']['show']):

            for ($this->_sections['extra_sku_array']['index'] = $this->_sections['extra_sku_array']['start'], $this->_sections['extra_sku_array']['iteration'] = 1;
                 $this->_sections['extra_sku_array']['iteration'] <= $this->_sections['extra_sku_array']['total'];
                 $this->_sections['extra_sku_array']['index'] += $this->_sections['extra_sku_array']['step'], $this->_sections['extra_sku_array']['iteration']++):
$this->_sections['extra_sku_array']['rownum'] = $this->_sections['extra_sku_array']['iteration'];
$this->_sections['extra_sku_array']['index_prev'] = $this->_sections['extra_sku_array']['index'] - $this->_sections['extra_sku_array']['step'];
$this->_sections['extra_sku_array']['index_next'] = $this->_sections['extra_sku_array']['index'] + $this->_sections['extra_sku_array']['step'];
$this->_sections['extra_sku_array']['first']      = ($this->_sections['extra_sku_array']['iteration'] == 1);
$this->_sections['extra_sku_array']['last']       = ($this->_sections['extra_sku_array']['iteration'] == $this->_sections['extra_sku_array']['total']);
?>
    [<?php echo $this->_sections['extra_sku_array']['index']; ?>
,"<?php echo $this->_tpl_vars['search_prefilled']['extra_sku'][$this->_sections['extra_sku_array']['index']]; ?>
"],               
<?php endfor; endif; ?>
];

var extraSkuCount = <?php echo $this->_sections['extra_sku_array']['total']; ?>
;
<?php endif; ?>   

-->
</script>

<?php ob_start(); ?>

<br />

<form name="searchform" action="search.php" method="post">
<input type="hidden" name="mode" value="search" />
<input type="hidden" name="froogle_titles" value="N" />

<table cellpadding="1" cellspacing="5" width="100%">

<tr>
	<td height="10" class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_search_in_category_id']; ?>
:</td>
	<td width="10" height="10">&nbsp;</td>
	<td height="10">
	<input name="posted_data[categoryid]" value="<?php echo $this->_tpl_vars['search_prefilled']['categoryid']; ?>
" style="width: 70%;" />
	</td>
</tr>

<tr>
	<td colspan="2" width="10" height="10">&nbsp;</td>
	<td height="10">
<table cellpadding="0" cellspacing="0">
<tr>
	<td width="5" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_as']; ?>
&nbsp;&nbsp;</td>
	<td width="5"><input type="checkbox" id="posted_data_category_main" name="posted_data[category_main]"<?php if ($this->_tpl_vars['search_prefilled'] == "" || $this->_tpl_vars['search_prefilled']['category_main']): ?> checked="checked"<?php endif; ?> /></td>
	<td nowrap="nowrap"><label for="posted_data_category_main"><?php echo $this->_tpl_vars['lng']['lbl_main_category']; ?>
</label>&nbsp;&nbsp;</td>
	<td width="5"><input type="checkbox" id="posted_data_category_extra" name="posted_data[category_extra]"<?php if ($this->_tpl_vars['search_prefilled']['category_extra']): ?> checked="checked"<?php endif; ?> /></td>
	<td nowrap="nowrap"><label for="posted_data_category_extra"><?php echo $this->_tpl_vars['lng']['lbl_additional_category']; ?>
</label></td>
</tr>
</table>
	</td>
</tr>

<tr>
	<td colspan="2" width="10" height="10">&nbsp;</td>
	<td height="10">
<table cellpadding="0" cellspacing="0">
<tr>
	<td width="5"><input type="checkbox" id="posted_data_search_in_subcategories" name="posted_data[search_in_subcategories]"<?php if ($this->_tpl_vars['search_prefilled'] == "" || $this->_tpl_vars['search_prefilled']['search_in_subcategories']): ?> checked="checked"<?php endif; ?> /></td>
	<td nowrap="nowrap"><label for="posted_data_search_in_subcategories"><?php echo $this->_tpl_vars['lng']['lbl_search_in_subcategories']; ?>
</label></td>
</tr>
</table>
	</td>
</tr>


<tr>
	<td height="10" width="20%" class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_search_for_pattern']; ?>
:</td>
	<td width="10" height="10">&nbsp;</td>
	<td height="10" width="80%">
<input type="text" name="posted_data[substring]" size="30" style="width:70%" value="<?php echo $this->_tpl_vars['search_prefilled']['substring']; ?>
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
	<td width="5"><input type="radio" name="posted_data[including]" value="all"<?php if ($this->_tpl_vars['search_prefilled'] == "" || $this->_tpl_vars['search_prefilled']['including'] == '' || $this->_tpl_vars['search_prefilled']['including'] == 'all'): ?> checked="checked"<?php endif; ?> /></td>
	<td nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_all_word']; ?>
&nbsp;&nbsp;</td>

	<td width="5"><input type="radio" name="posted_data[including]" value="any"<?php if ($this->_tpl_vars['search_prefilled']['including'] == 'any'): ?> checked="checked"<?php endif; ?> /></td>
	<td nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_any_word']; ?>
&nbsp;&nbsp;</td>

	<td width="5"><input type="radio" name="posted_data[including]" value="phrase"<?php if ($this->_tpl_vars['search_prefilled']['including'] == 'phrase'): ?> checked="checked"<?php endif; ?> /></td>
	<td nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_exact_phrase']; ?>
</td>
</tr>
</table>
</td>
</tr>
<?php endif; ?>

<tr>
	<td height="10" width="20%" class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_search_in']; ?>
:</td>
	<td width="10" height="10">&nbsp;</td>
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
	<td width="5"><input type="checkbox" id="posted_data_by_keywords" name="posted_data[by_keywords]"<?php if ($this->_tpl_vars['search_prefilled'] == "" || $this->_tpl_vars['search_prefilled']['by_keywords']): ?> checked="checked"<?php endif; ?> /></td>
	<td nowrap="nowrap"><label for="posted_data_by_keywords"><?php echo $this->_tpl_vars['lng']['lbl_keywords']; ?>
</label>&nbsp;&nbsp;</td>
</tr>
<tr>
	<td width="5"><input type="checkbox" id="posted_data_by_froogle_title" name="posted_data[by_froogle_title]"<?php if ($this->_tpl_vars['search_prefilled'] == "" || $this->_tpl_vars['search_prefilled']['by_froogle_title']): ?> checked="checked"<?php endif; ?> /></td>
	<td nowrap="nowrap"><label for="posted_data_by_froogle_title"><?php echo $this->_tpl_vars['lng']['lbl_product_name_froogle']; ?>
</label>&nbsp;&nbsp;</td>
	<td colspan="5">&nbsp;</td>
	
</tr>
</table>
	</td>
</tr>

<?php if ($this->_tpl_vars['active_modules']['Extra_Fields'] && $this->_tpl_vars['extra_fields'] != ''): ?>
<tr>
	<td height="10" width="20%" class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_search_also_in']; ?>
:</td>
	<td width="10" height="10">&nbsp;</td>
	<td>
<table cellpadding="0" cellspacing="0">
<?php $_from = $this->_tpl_vars['extra_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
<tr>
	<td width="5"><input type="checkbox" id="posted_data_extra_fields_<?php echo $this->_tpl_vars['v']['fieldid']; ?>
" name="posted_data[extra_fields][<?php echo $this->_tpl_vars['v']['fieldid']; ?>
]"<?php if ($this->_tpl_vars['v']['selected'] == 'Y'): ?> checked="checked"<?php endif; ?> /></td>
	<td><label for="posted_data_extra_fields_<?php echo $this->_tpl_vars['v']['fieldid']; ?>
"><?php echo $this->_tpl_vars['v']['field']; ?>
</label></td>
</tr>
<?php endforeach; endif; unset($_from); ?>
</table>
	</td>
</tr>
<?php endif; ?>

</tr>

<tr>
	<td colspan="2"></td>
	<td>
	<hr />
<table cellpadding="0" cellspacing="0">
<tr>
	<td><input type="checkbox" value='Y' id="posted_data_is_modify" name="posted_data[is_modify]" /></td>
	<td>&nbsp;</td>
	<td height="10" class="FormButton" nowrap="nowrap"><label for="posted_data_is_modify"><?php echo $this->_tpl_vars['lng']['lbl_search_and_modify']; ?>
</label></td>
</tr>
</table>
	</td>
</tr>

<tr> 
	<td colspan="2"></td>
	<td>
<table cellpadding="0" cellspacing="0">
<tr>
	<td><input type="checkbox" id="posted_data_is_export" name="posted_data[is_export]" value="Y" /></td>
	<td>&nbsp;</td>
	<td class="FormButton" nowrap="nowrap"><label for="posted_data_is_export"><?php echo $this->_tpl_vars['lng']['lbl_search_and_export']; ?>
</label></td>
</tr>
</table>
	</td>
</tr>

</table>

<br />

<table>
<tr>
	<td id="close1" style="cursor: hand;" onclick="visibleBox('1')"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/plus.gif" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_click_to_open'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td>
	<td id="open1" style="display: none; cursor: hand;" onclick="visibleBox('1')"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/minus.gif" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_click_to_close'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td>
	<td><a href="javascript:void(0);" onclick="visibleBox('1')"><b><?php echo $this->_tpl_vars['lng']['lbl_advanced_search_options']; ?>
</b></a></td>
</tr>
</table>

<br />

<table cellpadding="0" cellspacing="0" width="100%" style="display: none;" id="box1">
<tr>
	<td>

<table cellpadding="1" cellspacing="5" width="100%">

<tr>
	<td colspan="3"><br /><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_advanced_search_options'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>

<?php if ($this->_tpl_vars['active_modules']['Manufacturers'] && $this->_tpl_vars['manufacturers'] != ''):  ob_start();  unset($this->_sections['mnf']);
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
	<select name="posted_data[manufacturers][]" style="width:70%" multiple="multiple" size="<?php if ($this->_sections['mnf']['total'] > 5): ?>5<?php else:  echo $this->_sections['mnf']['total'];  endif; ?>">
<?php echo $this->_smarty_vars['capture']['manufacturers_items']; ?>

	</select>
	</td>
</tr>
<?php endif; ?>

<?php if ($this->_tpl_vars['active_modules']['Brands'] && $this->_tpl_vars['brands'] != ''):  ob_start();  unset($this->_sections['mnf']);
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
	<td height="10" class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_brands']; ?>
:</td>
	<td height="10"></td> 
	<td height="10">
	<select name="posted_data[brands][]" style="width:70%" multiple="multiple" size="<?php if ($this->_sections['mnf']['total'] > 5): ?>5<?php else:  echo $this->_sections['mnf']['total'];  endif; ?>">
<?php echo $this->_smarty_vars['capture']['brands_items']; ?>

	</select>
	</td>
</tr>
<?php endif; ?>

<tr>
	<td height="10" width="20%" class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_sku']; ?>
:</td>
	<td width="10" height="10">&nbsp;</td>
    <td height="10" width="80%" id="skuRow">
		<script type="text/javascript">
		<!--
		<?php echo '
			$(\'input[name^="posted_data[extra_sku]"]\').live(\'keydown\', function () {
				reset_form(\'searchform\', searchform_def);
			});
		'; ?>

		-->
		</script>
        <?php if ($this->_tpl_vars['current_area'] == 'C'): ?>
            <input type="text" maxlength="64" name="posted_data[productcode]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['search_prefilled']['productcode'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" style="width:70%" /><br/>
        <?php else: ?>
        <table width="70%" border="0">

            <tr id="sku_row_0">
                <td id="sku_box_1" align="left" width="90%">
                    <input 
                        name="posted_data[extra_sku][0]"  
                        type="text" 
                        style="width: 98%;"
                    >
                </td>
                <td align="left" width="10%">
                    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/multirow_add.tpl", 'smarty_include_vars' => array('mark' => 'sku','is_lined' => false)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                </td>
            </tr>
        </table>
        <script type="text/javascript">
            for (var i = 1 ; i < extraSkuCount; i++ ) {
                add_inputset("sku",document.getElementById("sku_box_1"),false);
            }
            for (var i = 0 ; i < extraSkuCount; i++ ) {
                var obj = document.getElementsByName("posted_data[extra_sku]["+i+"]");//.value=extraSkuRows[i][1];
                obj[0].value = extraSkuRows[i][1];
            }
        </script>

		<?php endif; ?>
    </td>
</tr>

<tr>
	<td height="10" width="20%" class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_productid']; ?>
#:</td>
	<td width="10" height="10">&nbsp;</td>
	<td height="10" width="80%"><input type="text" maxlength="64" name="posted_data[productid]" value="<?php echo $this->_tpl_vars['search_prefilled']['productid']; ?>
" style="width:70%" /></td>
</tr>

<?php if ($this->_tpl_vars['usertype'] == 'A'): ?>
<tr>
	<td height="10" width="20%" class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_provider']; ?>
:</td>
	<td width="10" height="10">&nbsp;</td>
	<td height="10" width="80%"><input type="text" maxlength="64" name="posted_data[provider]" value="<?php echo $this->_tpl_vars['search_prefilled']['provider']; ?>
" style="width:70%" /></td>
</tr>
<?php endif; ?>

<tr>
	<td height="10" width="20%" class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_price']; ?>
 (<?php echo $this->_tpl_vars['config']['General']['currency_symbol']; ?>
):</td>
	<td width="10" height="10">&nbsp;</td>
	<td height="10" width="80%">
<table cellpadding="0" cellspacing="0">
<tr>
	<td><input type="text" size="10" maxlength="15" name="posted_data[price_min]" value="<?php if ($this->_tpl_vars['search_prefilled'] == ""):  echo $this->_tpl_vars['zero'];  else:  echo ((is_array($_tmp=$this->_tpl_vars['search_prefilled']['price_min'])) ? $this->_run_mod_handler('formatprice', true, $_tmp) : smarty_modifier_formatprice($_tmp));  endif; ?>" /></td>
	<td>&nbsp;-&nbsp;</td>
	<td><input type="text" size="10" maxlength="15" name="posted_data[price_max]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['search_prefilled']['price_max'])) ? $this->_run_mod_handler('formatprice', true, $_tmp) : smarty_modifier_formatprice($_tmp)); ?>
" /></td>
</tr>
</table>
	</td>
</tr>

<tr>
	<td height="10" width="20%" class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_quantity']; ?>
:</td>
	<td width="10" height="10">&nbsp;</td>
	<td height="10" width="80%">
<table cellpadding="0" cellspacing="0">
<tr>
	<td><input type="text" size="10" maxlength="10" name="posted_data[avail_min]" value="<?php if ($this->_tpl_vars['search_prefilled'] == ""): ?>0<?php else:  echo $this->_tpl_vars['search_prefilled']['avail_min'];  endif; ?>" /></td>
	<td>&nbsp;-&nbsp;</td>
	<td><input type="text" size="10" maxlength="10" name="posted_data[avail_max]" value="<?php echo $this->_tpl_vars['search_prefilled']['avail_max']; ?>
" /></td>
</tr>
</table>
	</td>
</tr>

<tr>
	<td height="10" width="20%" class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_weight']; ?>
 (<?php echo $this->_tpl_vars['config']['General']['weight_symbol']; ?>
):</td>
	<td width="10" height="10">&nbsp;</td>
	<td height="10" width="80%">
<table cellpadding="0" cellspacing="0">
<tr>
	<td><input type="text" size="10" maxlength="10" name="posted_data[weight_min]" value="<?php if ($this->_tpl_vars['search_prefilled'] == ""):  echo $this->_tpl_vars['zero'];  else:  echo ((is_array($_tmp=$this->_tpl_vars['search_prefilled']['weight_min'])) ? $this->_run_mod_handler('formatprice', true, $_tmp) : smarty_modifier_formatprice($_tmp));  endif; ?>" /></td>
	<td>&nbsp;-&nbsp;</td>
	<td><input type="text" size="10" maxlength="10" name="posted_data[weight_max]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['search_prefilled']['weight_max'])) ? $this->_run_mod_handler('formatprice', true, $_tmp) : smarty_modifier_formatprice($_tmp)); ?>
" /></td>
</tr>
</table>
	</td>
</tr>

<tr>
	<td height="10" class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_availability']; ?>
:</td>
	<td height="10"></td>
	<td height="10">
	<select name="posted_data[forsale]" style="width:70%">
		<option value=""></option>
		<option value="Y"<?php if ($this->_tpl_vars['search_prefilled']['forsale'] == 'Y'): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_avail_for_sale']; ?>
</option>
		<option value="H"<?php if ($this->_tpl_vars['product']['forsale'] == 'H'): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_hidden']; ?>
</option>
		<option value="N"<?php if ($this->_tpl_vars['search_prefilled']['forsale'] == 'N'): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_disabled']; ?>
</option>
<?php if ($this->_tpl_vars['active_modules']['Product_Configurator']): ?>
		<option value="B"<?php if ($this->_tpl_vars['search_prefilled']['forsale'] == 'B'): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_bundled']; ?>
</option>
<?php endif; ?>
	</select>
	</td>
</tr>

<?php if ($this->_tpl_vars['usertype'] != 'C' && $this->_tpl_vars['usertype'] != 'B' && $this->_tpl_vars['active_modules']['Feature_Comparison'] && $this->_tpl_vars['fclasses'] != ''): ?>
<tr>
	<td height="10" class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_product_feature_classes']; ?>
:</td>
	<td height="10"></td>
	<td height="10">
	<select name="posted_data[fclassid]" style="width:70%">
		<option value=""></option>
<?php $_from = $this->_tpl_vars['fclasses']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
		<option value="<?php echo $this->_tpl_vars['v']['fclassid']; ?>
"<?php if ($this->_tpl_vars['search_prefilled']['fclassid'] == $this->_tpl_vars['v']['fclassid']): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['v']['class']; ?>
</option>
<?php endforeach; endif; unset($_from); ?>
	</select>
	</td>
</tr>
<?php endif; ?>

<tr>
	<td colspan="3"><br /><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_discount_table_options'],'class' => 'grey')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>

<tr>
	<td height="10" class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_empty_discount_slope']; ?>
:</td>
	<td height="10"></td>
	<td height="10"><input name="posted_data[empty_discount_slope]" value="Y" type="checkbox"<?php if ($this->_tpl_vars['search_prefilled']['empty_discount_slope'] == 'Y'): ?> checked="checked"<?php endif; ?> onclick="visibleBox('2',true)" /></td>
</tr>

<tbody<?php if ($this->_tpl_vars['search_prefilled']['empty_discount_slope'] == 'Y'): ?> style="display: none;"<?php endif; ?> id="box2">

<tr>
	<td height="10" class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_discount_slope']; ?>
:</td>
	<td height="10"></td>
	<td height="10"><input name="posted_data[discount_slope]" type="text" value="<?php if ($this->_tpl_vars['search_prefilled'] == ""):  echo $this->_tpl_vars['zero'];  else:  echo ((is_array($_tmp=$this->_tpl_vars['search_prefilled']['discount_slope'])) ? $this->_run_mod_handler('formatprice', true, $_tmp) : smarty_modifier_formatprice($_tmp));  endif; ?>"  size="10" maxlength="10" /></td>
</tr>

<tr>
	<td height="10" class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_discount_table']; ?>
:</td>
	<td height="10"></td>
	<td height="10"><input name="posted_data[discount_table]" type="text" value="<?php echo $this->_tpl_vars['search_prefilled']['discount_table']; ?>
" class="InputWidth" /></td>
</tr>

<tr>
	<td height="10" class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_outdated_discount_table']; ?>
:</td>
	<td height="10"></td>
	<td height="10"><input name="posted_data[outdated_discount_table]" value="Y" type="checkbox"<?php if ($this->_tpl_vars['search_prefilled']['outdated_discount_table'] == 'Y'): ?> checked="checked"<?php endif; ?> /></td>
</tr>

</tbody>

<tr>
	<td colspan="3"><br /><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_additional_options'],'class' => 'grey')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>
</table>

<table cellpadding="1" cellspacing="5">
<tr>
	<td class="FormButton"><?php echo $this->_tpl_vars['lng']['lbl_free_shipping']; ?>
:&nbsp;</td>
	<td>
	<select name="posted_data[flag_free_ship]">
		<option value=""></option>
		<option value="Y"<?php if ($this->_tpl_vars['search_prefilled']['flag_free_ship'] == 'Y'): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_assigned']; ?>
</option>
		<option value="N"<?php if ($this->_tpl_vars['search_prefilled']['flag_free_ship'] == 'N'): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_not_assigned']; ?>
</option>
	</select>
&nbsp;&nbsp;
</td>
	<td class="FormButton"><?php echo $this->_tpl_vars['lng']['lbl_shipping_freight']; ?>
:&nbsp;</td>
	<td>
	<select name="posted_data[flag_ship_freight]">
		<option value=""></option>
		<option value="Y"<?php if ($this->_tpl_vars['search_prefilled']['flag_ship_freight'] == 'Y'): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_assigned']; ?>
</option>
		<option value="N"<?php if ($this->_tpl_vars['search_prefilled']['flag_ship_freight'] == 'N'): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_not_assigned']; ?>
</option>
	</select>
	</td>
</tr>

<tr>
	<td class="FormButton"><?php echo $this->_tpl_vars['lng']['lbl_global_discounts']; ?>
:&nbsp;</td>
	<td>
	<select name="posted_data[flag_global_disc]">
		<option value=""></option>
		<option value="Y"<?php if ($this->_tpl_vars['search_prefilled']['flag_global_disc'] == 'Y'): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_assigned']; ?>
</option>
		<option value="N"<?php if ($this->_tpl_vars['search_prefilled']['flag_global_disc'] == 'N'): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_not_assigned']; ?>
</option>
	</select>
&nbsp;&nbsp;
	</td>
	<td class="FormButton"><?php echo $this->_tpl_vars['lng']['lbl_tax_exempt']; ?>
:&nbsp;</td>
	<td>
	<select name="posted_data[flag_free_tax]">
		<option value=""></option>
		<option value="Y"<?php if ($this->_tpl_vars['search_prefilled']['flag_free_tax'] == 'Y'): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_assigned']; ?>
</option>
		<option value="N"<?php if ($this->_tpl_vars['search_prefilled']['flag_free_tax'] == 'N'): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_not_assigned']; ?>
</option>
	</select>
	</td>
</tr>

<tr>
	<td class="FormButton"><?php echo $this->_tpl_vars['lng']['lbl_min_order_amount']; ?>
:&nbsp;</td>
	<td>
	<select name="posted_data[flag_min_amount]">
		<option value=""></option>
		<option value="Y"<?php if ($this->_tpl_vars['search_prefilled']['flag_min_amount'] == 'Y'): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_assigned']; ?>
</option>
		<option value="N"<?php if ($this->_tpl_vars['search_prefilled']['flag_min_amount'] == 'N'): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_not_assigned']; ?>
</option>
	</select>
&nbsp;&nbsp;
	</td>
	<td class="FormButton"><?php echo $this->_tpl_vars['lng']['lbl_lowlimit_in_stock']; ?>
:&nbsp;</td>
	<td>
	<select name="posted_data[flag_low_avail_limit]">
		<option value=""></option>
		<option value="Y"<?php if ($this->_tpl_vars['search_prefilled']['flag_low_avail_limit'] == 'Y'): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_assigned']; ?>
</option>
		<option value="N"<?php if ($this->_tpl_vars['search_prefilled']['flag_low_avail_limit'] == 'N'): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_not_assigned']; ?>
</option>
	</select>
	</td>
</tr>

<tr>
	<td class="FormButton"><?php echo $this->_tpl_vars['lng']['lbl_list_price']; ?>
:&nbsp;</td>
	<td>
	<select name="posted_data[flag_list_price]">
		<option value=""></option>
		<option value="Y"<?php if ($this->_tpl_vars['search_prefilled']['flag_list_price'] == 'Y'): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_assigned']; ?>
</option>
		<option value="N"<?php if ($this->_tpl_vars['search_prefilled']['flag_list_price'] == 'N'): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_not_assigned']; ?>
</option>
	</select>
&nbsp;&nbsp;
	</td>
	<td colspan="2">&nbsp;</td>
</tr>

</table>

<table cellpadding="0" cellspacing="0" width="100%">
<tr>
	<td colspan="3"><br /><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_custom_options'],'class' => 'grey')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>

<tr>
	<td height="10" class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_find_multisku_only']; ?>
:</td>
	<td height="10"></td>
	<td height="10" width="80%"><input name="posted_data[duplicate_sku]" value="Y" type="checkbox"<?php if ($this->_tpl_vars['search_prefilled']['duplicate_sku'] == 'Y'): ?> checked="checked"<?php endif; ?> /></td>
</tr>

<tr>
	<td height="10" class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_empty_froogle_title']; ?>
:</td>
	<td height="10"></td>
	<td height="10" width="80%"><input name="posted_data[empty_froogle_title]" value="Y" type="checkbox"<?php if ($this->_tpl_vars['search_prefilled']['empty_froogle_title'] == 'Y'): ?> checked="checked"<?php endif; ?> /></td>
</tr>

<tr>
	<td height="10" class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_froogle_differs']; ?>
:</td>
	<td height="10"></td>
	<td height="10" width="80%"><input name="posted_data[froogle_differs]" value="Y" type="checkbox"<?php if ($this->_tpl_vars['search_prefilled']['froogle_differs'] == 'Y'): ?> checked="checked"<?php endif; ?> /></td>
</tr>

<tr>
	<td height="10" class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_no_thumbnail']; ?>
:</td>
	<td height="10"></td>
	<td height="10" width="80%"><input name="posted_data[no_thumbnail]" value="Y" type="checkbox"<?php if ($this->_tpl_vars['search_prefilled']['no_thumbnail'] == 'Y'): ?> checked="checked"<?php endif; ?> /></td>
</tr>

<tr>
	<td height="10" class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_no_product_image']; ?>
:</td>
	<td height="10"></td>
	<td height="10" width="80%"><input name="posted_data[no_product_image]" value="Y" type="checkbox"<?php if ($this->_tpl_vars['search_prefilled']['no_product_image'] == 'Y'): ?> checked="checked"<?php endif; ?> /></td>
</tr>

<?php if ($this->_tpl_vars['active_modules']['Detailed_Product_Images'] != ""): ?>
<tr>
	<td height="10" class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_no_detailed_images']; ?>
:</td>
	<td height="10"></td>
	<td height="10" width="80%"><input name="posted_data[no_detailed_images]" value="Y" type="checkbox"<?php if ($this->_tpl_vars['search_prefilled']['no_detailed_images'] == 'Y'): ?> checked="checked"<?php endif; ?> /></td>
</tr>
<?php endif; ?>

<tr>
	<td height="10" class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_products_with_broken_images']; ?>
:</td>
	<td height="10"></td>
	<td height="10" width="80%"><input name="posted_data[broken_images]" value="Y" type="checkbox"<?php if ($this->_tpl_vars['search_prefilled']['broken_images'] == 'Y'): ?> checked="checked"<?php endif; ?> /></td>
</tr>

<tr>
	<td colspan="3">&nbsp;</td>
</tr>

<tr>
	<td>&nbsp;</td>
	<td colspan="2" class="SubmitBox">
	<input type="submit" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_search'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
	<input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_reset'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: reset_form('searchform', searchform_def);" />
<?php if ($this->_tpl_vars['active_modules']['Wholesale_Trading']): ?>
	<input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_generate_discounts'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: submitForm(document.searchform, 'search_gen_discounts');" />
	<input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_improve_froogle_titles'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: document.searchform.froogle_titles.value = 'Y'; document.searchform.submit();" />
<?php endif; ?>
	</td>
</tr>

</table>

	</td>
</tr>
</table>

</form>

<?php if ($this->_tpl_vars['search_prefilled']['need_advanced_options']): ?>
<script type="text/javascript" language="JavaScript 1.2">
<!--
visibleBox('1');
-->
</script>
<?php endif; ?>


<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_search_products'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<br />

<!-- SEARCH FORM DIALOG END -->

<?php endif; ?>

<!-- SEARCH RESULTS SUMMARY -->

<a name="results"></a>

<?php if ($this->_tpl_vars['mode'] == 'search'):  if ($this->_tpl_vars['total_items'] > '1'):  echo ((is_array($_tmp=$this->_tpl_vars['lng']['txt_N_results_found'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'items', $this->_tpl_vars['total_items']) : smarty_modifier_substitute($_tmp, 'items', $this->_tpl_vars['total_items'])); ?>
<br />
<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['txt_displaying_X_Y_results'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'first_item', $this->_tpl_vars['first_item'], 'last_item', $this->_tpl_vars['last_item']) : smarty_modifier_substitute($_tmp, 'first_item', $this->_tpl_vars['first_item'], 'last_item', $this->_tpl_vars['last_item'])); ?>

<?php elseif ($this->_tpl_vars['total_items'] == '0'):  echo ((is_array($_tmp=$this->_tpl_vars['lng']['txt_N_results_found'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'items', 0) : smarty_modifier_substitute($_tmp, 'items', 0)); ?>

<?php endif;  endif; ?>

<?php if ($this->_tpl_vars['mode'] == 'search' && $this->_tpl_vars['products'] != ""): ?>

<!-- SEARCH RESULTS START -->

<br /><br />

<?php ob_start(); ?>

<div align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/button.tpl", 'smarty_include_vars' => array('button_title' => $this->_tpl_vars['lng']['lbl_search_again'],'href' => "search.php")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></div>

<?php if ($this->_tpl_vars['total_pages'] > 2):  $this->assign('navpage', $this->_tpl_vars['navigation_page']);  endif; ?>

<form action="process_product.php" method="post" name="processproductform">
<input type="hidden" name="mode" value="update" />
<input type="hidden" name="froogle_titles" value="<?php echo $this->_tpl_vars['froogle_titles']; ?>
" />
<input type="hidden" name="navpage" value="<?php echo $this->_tpl_vars['navpage']; ?>
" />

<table cellpadding="0" cellspacing="0" width="100%">

<tr>
	<td>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/navigation.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if ($this->_tpl_vars['froogle_titles'] != 'Y'): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/products.tpl", 'smarty_include_vars' => array('products' => $this->_tpl_vars['products'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  else: ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/products_froogle_titles.tpl", 'smarty_include_vars' => array('products' => $this->_tpl_vars['products'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

<br />

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/navigation.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if ($this->_tpl_vars['froogle_titles'] != 'Y'): ?>
<br />

<input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_delete_selected'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: if (checkMarks(this.form, new RegExp('productids\[[0-9]+\]', 'gi'))) if (confirm('<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['txt_delete_products_warning'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)); ?>
')) submitForm(document.processproductform, 'delete');" />
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="submit" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_update'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_modify_selected'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: if (checkMarks(this.form, new RegExp('productids\[[0-9]+\]', 'gi'))) { document.processproductform.action='product_modify.php'; submitForm(document.processproductform, 'list'); }" />
<?php if ($this->_tpl_vars['active_modules']['Wholesale_Trading']): ?>
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_generate_discounts'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: if (checkMarks(this.form, new RegExp('productids\[[0-9]+\]', 'gi'))) { document.processproductform.action='product_modify.php'; submitForm(document.processproductform, 'gen_discounts'); }" />
<?php endif; ?>

<br /><br />
<input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_export'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: if (checkMarks(this.form, new RegExp('productids\[[0-9]+\]', 'gi'))) submitForm(document.processproductform, 'export');" />
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_export_all_found'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: self.location='search.php?mode=search&amp;export=export_found';" />

<br /><br /><br />

<?php echo $this->_tpl_vars['lng']['txt_operation_for_first_selected_only']; ?>


<br /><br />

<input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_preview_product'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: if (checkMarks(this.form, new RegExp('productids\[[0-9]+\]', 'gi'))) submitForm(document.processproductform, 'details');" />
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_clone_product'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: if (checkMarks(this.form, new RegExp('productids\[[0-9]+\]', 'gi'))) submitForm(document.processproductform, 'clone');" />
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_generate_html_links'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: if (checkMarks(this.form, new RegExp('productids\[[0-9]+\]', 'gi'))) submitForm(document.processproductform, 'links');" />
<?php else: ?>
        </td>
    </tr>
    <tr>
        <td align="center">
            <input type="submit" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_update'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
<?php endif; ?>

	</td>
</tr>

</table>
</form>

<?php if ($this->_tpl_vars['froogle_titles'] != 'Y'): ?>
<br />
<br />

<a name="bulk"></a>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_bulk_manage_all_fount_dbsr_products'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<form action="bulk_management.php" method="post" name="bulkform" enctype="multipart/form-data">
<input type="hidden" name="mode" value="compare" />

<div id="close1">
	<a onclick="javascript: visibleBox('1');" href="javascript: void(0);"><b><?php echo $this->_tpl_vars['lng']['lbl_instructions']; ?>
</b></a>&nbsp;<a onclick="javascript: visibleBox('1');" href="javascript: void(0);"><img alt="Click to open" src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/plus.gif"></a>
</div>
<div id="open1" style="display: none">
	<a onclick="javascript: visibleBox('1');" href="javascript: void(0);"><b><?php echo $this->_tpl_vars['lng']['lbl_instructions']; ?>
</b></a>&nbsp;<a onclick="javascript: visibleBox('1');" href="javascript: void(0);"><img alt="Click to open" src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/minus.gif"></a>
</div>
<div id="box1" style="display: none">
	<?php echo $this->_tpl_vars['lng']['lbl_txt_bulk_manage_text']; ?>

</div>
<br />
<div>
	<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_csv_delimiter'])) ? $this->_run_mod_handler('cat', true, $_tmp, ":") : smarty_modifier_cat($_tmp, ":")); ?>
&nbsp;
	<select name="bulk_delimiter">
		<option value=","><?php echo $this->_tpl_vars['lng']['lbl_comma']; ?>
</option>
		<option value=";"><?php echo $this->_tpl_vars['lng']['lbl_semicolon']; ?>
</option>
		<option value="tab"><?php echo $this->_tpl_vars['lng']['lbl_tab']; ?>
</option>
	</select>
	<br />
	<?php echo $this->_tpl_vars['lng']['lbl_select_product_updation']; ?>
:&nbsp;<input type="file" size="70" name="userfile" />

	<?php if ($this->_tpl_vars['upload_max_filesize']): ?>
		<br /><font class="Star"><?php echo $this->_tpl_vars['lng']['lbl_warning']; ?>
!</font> <?php echo $this->_tpl_vars['lng']['txt_max_file_size_that_can_be_uploaded']; ?>
: <?php echo $this->_tpl_vars['upload_max_filesize']; ?>
b.
	<?php endif; ?>
	<br />
	<br />
	<input type="submit" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_submit'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
</div>

</form>
<?php endif; ?>

<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_search_results'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php endif; ?>

<br /><br />
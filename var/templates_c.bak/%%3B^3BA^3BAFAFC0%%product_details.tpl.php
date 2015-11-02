<?php /* Smarty version 2.6.12, created on 2011-10-11 06:53:28
         compiled from main/product_details.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'main/product_details.tpl', 41, false),array('modifier', 'default', 'main/product_details.tpl', 82, false),array('modifier', 'strip_tags', 'main/product_details.tpl', 272, false),array('modifier', 'formatprice', 'main/product_details.tpl', 328, false),array('modifier', 'replace', 'main/product_details.tpl', 337, false),array('modifier', 'substitute', 'main/product_details.tpl', 348, false),)), $this); ?>
<?php func_load_lang($this, "main/product_details.tpl","lbl_current_product,lbl_note,txt_edit_product_group,lbl_product_owner,lbl_provider,lbl_availability,lbl_avail_for_sale,lbl_hidden,lbl_disabled,lbl_bundled,lbl_classification,lbl_manufacturer,lbl_no_manufacturer,lbl_brand,lbl_no_brand,lbl_main_category,lbl_main_category_id,lbl_additional_categories,lbl_additional_categories_ids,lbl_product_url,lbl_details,lbl_sku,lbl_froogle_upc,lbl_product_name,lbl_capitalize,lbl_product_name_froogle,lbl_copy,lbl_keywords,lbl_det_description,lbl_short_description,txt_short_descr,txt_html_tags_in_description,lbl_pricing,lbl_list_price,lbl_cost_to_us,lbl_copy_to_us_button,lbl_price,lbl_note,txt_pvariant_edit_note,lbl_price_button,lbl_inventory,lbl_quantity_in_stock,lbl_note,txt_pvariant_edit_note,lbl_lowlimit_in_stock,lbl_min_order_amount,lbl_return_time,lbl_taxes,lbl_tax_exempt,lbl_yes,lbl_no,lbl_apply_taxes,lbl_hold_ctrl_key,lbl_click_here_to_manage_taxes,lbl_shipping,lbl_weight,lbl_note,txt_pvariant_edit_note,lbl_shipping_dimensions,lbl_shipping_freight,lbl_free_ship_destination,lbl_no_free_ship,lbl_zone_default,lbl_free_ship_text,lbl_discount_settings,lbl_gcheckout_product_valid,lbl_discount_slope,lbl_discount_table,lbl_apply_global_discounts,lbl_save,lbl_product_details"); ?>
<?php ob_start(); ?>

<?php if ($this->_tpl_vars['taxes']): ?>
<script type="text/javascript" language="JavaScript 1.2">
<!--
function ChangeTaxesBoxStatus() {
	if (document.modifyform && document.modifyform.elements['taxes[]'])
		document.modifyform.elements['taxes[]'].disabled = (document.modifyform.free_tax.value == 'Y');
}

<?php echo '
function updateCategoryIds() {
	var elm = document.getElementById(\'categoryids_select\');
	if (elm) {
		txt = \'\';
		for (var i=0; i < elm.options.length; i++) {
			if (elm.options[i].selected) {
				if (txt) {
					txt = txt + \',\';
				}
				txt = txt + elm.options[i].value;
			}
		}
	}
	output = document.getElementById(\'categoryids_input\');
	if (output) {
		output.value = txt;
	}
}
'; ?>

-->
</script>
<?php endif; ?>

<script type="text/javascript" language="JavaScript 1.2">
<!--
var reps = Array();
<?php $_from = $this->_tpl_vars['replacements']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['r']):
?>
	reps['<?php echo $this->_tpl_vars['key']; ?>
'] = ['<?php echo ((is_array($_tmp=$this->_tpl_vars['r']['what'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
', '<?php echo ((is_array($_tmp=$this->_tpl_vars['r']['by'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
'];
<?php endforeach; endif; unset($_from); ?>

<?php echo '

function cap_first() {
	return arguments[0].toUpperCase();
}

function capitalize(id) {
	var text = $(\'#\' + id).val();
	text = text.replace(/\\b[a-z]/g, cap_first);
	for (i = 0; i < reps.length; i++) {
		pattern = new RegExp(reps[i][0], \'g\');
		text = text.replace(pattern, reps[i][1]);
	}
	$(\'#\' + id).val(text);
}

function copy_product_title_to_froogle() {
	var froogle_title = $(\'#product_name\').val().substring(0,70);
	/*if (froogle_title.length > 70) {
		var froogle_title = froogle_title.substring(0,67);
		froogle_title = froogle_title + \'...\';
	}*/
	$(\'#froogle_title\').val(froogle_title);
}

'; ?>


function generate_price(id) {
	var res = 0;
	var list_price = $('#list_price').val();
	if (list_price == '') {
		list_price = 0;
	}
	var cost_to_us = $('#cost_to_us').val();
	if (cost_to_us == '') {
		cost_to_us = 0;
	}
	if (id == 'cost_to_us') {
		res += <?php echo ((is_array($_tmp=@$this->_tpl_vars['product']['cost_to_us_coef_x'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
 * list_price;
	}
	if (id == 'price') {
		res += (<?php echo ((is_array($_tmp=@$this->_tpl_vars['product']['price_coef_x'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
 * cost_to_us + <?php echo ((is_array($_tmp=@$this->_tpl_vars['product']['price_coef_y'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
) / <?php echo ((is_array($_tmp=@$this->_tpl_vars['product']['price_coef_z'])) ? $this->_run_mod_handler('default', true, $_tmp, 1) : smarty_modifier_default($_tmp, 1)); ?>
;
	}
	$('#' + id).val(round(res, 2));
}
-->
</script>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "check_froogle_upc_js.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if ($this->_tpl_vars['product']): ?>
<table width="100%">

<tr>
	<td align="center" class="TopLabel">
        <?php if ($this->_tpl_vars['product']['forsale'] != 'N'): ?><a href="<?php echo $this->_tpl_vars['product']['customer_url']; ?>
" title="" target="_blank"><?php endif;  echo $this->_tpl_vars['lng']['lbl_current_product']; ?>
: "<?php echo $this->_tpl_vars['product']['product']; ?>
"<?php if ($this->_tpl_vars['product']['forsale'] != 'N'): ?></a><?php endif; ?>
	</td>
</tr>

</table>
<?php endif; ?>

<form action="process_product.php" method="post" name="cloneproductform">
<input type="hidden" name="mode" value="clone" />
<input type="hidden" name="clone_detailed" value="" />
<input type="hidden" name="productid" value="<?php echo $this->_tpl_vars['product']['productid']; ?>
" />
</form>

<form action="product_modify.php" method="post" name="modifyform">
<input type="hidden" name="productid" value="<?php echo $this->_tpl_vars['product']['productid']; ?>
" />
<input type="hidden" name="section" value="main" />
<input type="hidden" name="mode" value="product_modify" />
<input type="hidden" name="geid" value="<?php echo $this->_tpl_vars['geid']; ?>
" />

<table cellpadding="4" cellspacing="0" width="100%">

<?php if ($this->_tpl_vars['geid'] != ''): ?>
<tr>
	<td width="15" class="TableSubHead">&nbsp;</td>
	<td class="TableSubHead" colspan="2"><b>* <?php echo $this->_tpl_vars['lng']['lbl_note']; ?>
:</b> <?php echo $this->_tpl_vars['lng']['txt_edit_product_group']; ?>
</td>
</tr>
<?php endif; ?>

<tr>
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead">&nbsp;</td><?php endif; ?>
	<td colspan="2"><br /><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_product_owner'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>

<tr> 
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead">&nbsp;</td><?php endif; ?>
	<td class="FormButton" width="20%" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_provider']; ?>
:</td>
	<td class="ProductDetails" width="80%">
<?php if ($this->_tpl_vars['usertype'] == 'A' && $this->_tpl_vars['new_product'] == 1): ?>
	<select name="provider" class="InputWidth">
<?php unset($this->_sections['prov']);
$this->_sections['prov']['name'] = 'prov';
$this->_sections['prov']['loop'] = is_array($_loop=$this->_tpl_vars['providers']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['prov']['show'] = true;
$this->_sections['prov']['max'] = $this->_sections['prov']['loop'];
$this->_sections['prov']['step'] = 1;
$this->_sections['prov']['start'] = $this->_sections['prov']['step'] > 0 ? 0 : $this->_sections['prov']['loop']-1;
if ($this->_sections['prov']['show']) {
    $this->_sections['prov']['total'] = $this->_sections['prov']['loop'];
    if ($this->_sections['prov']['total'] == 0)
        $this->_sections['prov']['show'] = false;
} else
    $this->_sections['prov']['total'] = 0;
if ($this->_sections['prov']['show']):

            for ($this->_sections['prov']['index'] = $this->_sections['prov']['start'], $this->_sections['prov']['iteration'] = 1;
                 $this->_sections['prov']['iteration'] <= $this->_sections['prov']['total'];
                 $this->_sections['prov']['index'] += $this->_sections['prov']['step'], $this->_sections['prov']['iteration']++):
$this->_sections['prov']['rownum'] = $this->_sections['prov']['iteration'];
$this->_sections['prov']['index_prev'] = $this->_sections['prov']['index'] - $this->_sections['prov']['step'];
$this->_sections['prov']['index_next'] = $this->_sections['prov']['index'] + $this->_sections['prov']['step'];
$this->_sections['prov']['first']      = ($this->_sections['prov']['iteration'] == 1);
$this->_sections['prov']['last']       = ($this->_sections['prov']['iteration'] == $this->_sections['prov']['total']);
?>
		<option value="<?php echo $this->_tpl_vars['providers'][$this->_sections['prov']['index']]['login']; ?>
"><?php echo $this->_tpl_vars['providers'][$this->_sections['prov']['index']]['login']; ?>
 (<?php echo $this->_tpl_vars['providers'][$this->_sections['prov']['index']]['title']; ?>
 <?php echo $this->_tpl_vars['providers'][$this->_sections['prov']['index']]['lastname']; ?>
 <?php echo $this->_tpl_vars['providers'][$this->_sections['prov']['index']]['firstname']; ?>
)</option>
<?php endfor; endif; ?>
	</select>
<?php else:  echo $this->_tpl_vars['provider_info']['title']; ?>
 <?php echo $this->_tpl_vars['provider_info']['lastname']; ?>
 <?php echo $this->_tpl_vars['provider_info']['firstname']; ?>
 (<?php echo $this->_tpl_vars['provider_info']['login']; ?>
)
<?php endif; ?>
	</td>
</tr>

<tr> 
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[forsale]" /></td><?php endif; ?>
	<td class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_availability']; ?>
:</td>
	<td class="ProductDetails">
	<select name="forsale">
		<option value="Y"<?php if ($this->_tpl_vars['product']['forsale'] == 'Y' || ( $this->_tpl_vars['product']['forsale'] != 'N' && $this->_tpl_vars['product']['forsale'] != 'H' && ( $this->_tpl_vars['product']['forsale'] != 'B' || ! $this->_tpl_vars['active_modules']['Product_Configurator'] ) )): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_avail_for_sale']; ?>
</option>
		<option value="H"<?php if ($this->_tpl_vars['product']['forsale'] == 'H'): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_hidden']; ?>
</option>
		<option value="N"<?php if ($this->_tpl_vars['product']['forsale'] == 'N'): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_disabled']; ?>
</option>
<?php if ($this->_tpl_vars['active_modules']['Product_Configurator']): ?>
		<option value="B"<?php if ($this->_tpl_vars['product']['forsale'] == 'B'): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_bundled']; ?>
</option>
<?php endif; ?>
	</select>
	</td>
</tr>

<tr>
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead">&nbsp;</td><?php endif; ?>
	<td colspan="2"><br /><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_classification'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>

<?php if ($this->_tpl_vars['active_modules']['Manufacturers'] != ""): ?>
<tr>
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[manufacturer]" /></td><?php endif; ?>
    <td class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_manufacturer']; ?>
:</td>
    <td class="ProductDetails">
	<select name="manufacturerid">
	    <option value=''<?php if ($this->_tpl_vars['product']['manufacturerid'] == ''): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_no_manufacturer']; ?>
</option>
    <?php $_from = $this->_tpl_vars['manufacturers']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
    	<option value='<?php echo $this->_tpl_vars['v']['manufacturerid']; ?>
'<?php if ($this->_tpl_vars['v']['manufacturerid'] == $this->_tpl_vars['product']['manufacturerid']): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['v']['manufacturer']; ?>
</option>
    <?php endforeach; endif; unset($_from); ?>
    </select>
	</td>
</tr>
<?php endif; ?>

<?php if ($this->_tpl_vars['active_modules']['Brands'] != ""): ?>
<tr>
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[brand]" /></td><?php endif; ?>
    <td class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_brand']; ?>
:</td>
    <td class="ProductDetails">
	<select name="brandid">
	    <option value=''<?php if ($this->_tpl_vars['product']['brandid'] == ''): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_no_brand']; ?>
</option>
    <?php $_from = $this->_tpl_vars['brands']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
    	<option value='<?php echo $this->_tpl_vars['v']['brandid']; ?>
'<?php if ($this->_tpl_vars['v']['brandid'] == $this->_tpl_vars['product']['brandid']): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['v']['brand']; ?>
</option>
    <?php endforeach; endif; unset($_from); ?>
    </select>
	</td>
</tr>
<?php endif; ?>

<tr> 
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[categoryid]" /></td><?php endif; ?>
	<td class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_main_category']; ?>
:</td>
	<td class="ProductDetails"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/category_selector.tpl", 'smarty_include_vars' => array('field' => 'categoryid_text','extra' => ' style="width: 100%;"','categoryid' => ((is_array($_tmp=@$this->_tpl_vars['product']['categoryid'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['default_categoryid']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['default_categoryid'])),'override_onchange' => "javascript: document.getElementById('categoryid_input').value=this.options[this.selectedIndex].value;",'display_only_selected' => ((is_array($_tmp=@$this->_tpl_vars['product']['productid'])) ? $this->_run_mod_handler('default', true, $_tmp, 'Y') : smarty_modifier_default($_tmp, 'Y')))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	</td>
</tr>

<tr> 
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead">&nbsp;</td><?php endif; ?>
	<td nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_main_category_id']; ?>
:</td>
	<td class="ProductDetails">
	<input type="text" name="categoryid" id="categoryid_input" size="8" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['product']['categoryid'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['default_categoryid']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['default_categoryid'])); ?>
" />
	<?php if ($this->_tpl_vars['top_message']['fillerror'] != "" && ( $this->_tpl_vars['product']['categoryid'] == "" || $this->_tpl_vars['category_exists'] == 'N' )): ?><font class="Star">&lt;&lt;</font><?php endif; ?>
	</td>
</tr>

<tr>
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[categoryids]" /></td><?php endif; ?>
	<td class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_additional_categories']; ?>
:</td>
	<td class="ProductDetails">
	<select name="categoryids_text[]" id="categoryids_select" style="width: 100%;" multiple="multiple" size="8" onchange="javascript: updateCategoryIds();">
<?php $_from = $this->_tpl_vars['allcategories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['catid'] => $this->_tpl_vars['c']):
 if ($this->_tpl_vars['c']['productid'] == $this->_tpl_vars['product']['productid'] && $this->_tpl_vars['product']['productid'] != ""): ?>
		<option value="<?php echo $this->_tpl_vars['catid']; ?>
"<?php if (( $this->_tpl_vars['c']['productid'] == $this->_tpl_vars['product']['productid'] && $this->_tpl_vars['product']['productid'] != "" ) || ( $this->_tpl_vars['product']['productid'] == '' && $this->_tpl_vars['product']['add_categoryids'] && $this->_tpl_vars['product']['add_categoryids'][$this->_tpl_vars['catid']] )): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['c']['category_path']; ?>
</option>
<?php endif;  endforeach; endif; unset($_from); ?>
	</select>
	</td>
</tr>

<tr>
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead">&nbsp;</td><?php endif; ?>
	<td nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_additional_categories_ids']; ?>
:</td>
	<td class="ProductDetails">
	<input type="text" name="categoryids" id="categoryids_input" size="40" value="<?php echo '';  $this->assign('need_comma', false);  echo '';  $_from = $this->_tpl_vars['allcategories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['catid'] => $this->_tpl_vars['c']):
 echo '';  if (( $this->_tpl_vars['c']['productid'] == $this->_tpl_vars['product']['productid'] && $this->_tpl_vars['product']['productid'] != "" ) || ( $this->_tpl_vars['product']['productid'] == '' && $this->_tpl_vars['product']['add_categoryids'] && $this->_tpl_vars['product']['add_categoryids'][$this->_tpl_vars['catid']] )):  echo '';  if ($this->_tpl_vars['need_comma']):  echo ',';  else:  echo '';  $this->assign('need_comma', true);  echo '';  endif;  echo '';  echo $this->_tpl_vars['c']['categoryid'];  echo '';  endif;  echo '';  endforeach; endif; unset($_from);  echo ''; ?>
" style="width: 100%;"/>
	</td>
</tr>

<?php if ($this->_tpl_vars['product']['forsale'] == 'H'): ?>
<tr>
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[categoryids]" /></td><?php endif; ?>
	<td class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_product_url']; ?>
:</td>
	<td class="ProductDetails"><?php echo $this->_tpl_vars['catalogs']['customer']; ?>
/product.php?productid=<?php echo $this->_tpl_vars['product']['productid']; ?>
&cat=<?php echo $this->_tpl_vars['product']['categoryid']; ?>
</td>
</tr>
<?php endif; ?>

<tr> 
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead">&nbsp;</td><?php endif; ?>
	<td colspan="2"><br /><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_details'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>

<tr> 
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead">&nbsp;</td><?php endif; ?>
	<td class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_sku']; ?>
:</td>
	<td class="ProductDetails"><input type="text" name="productcode" size="20" value="<?php echo $this->_tpl_vars['product']['productcode']; ?>
" class="InputWidth" /></td>
</tr>

<tr> 
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead">&nbsp;</td><?php endif; ?>
	<td class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_froogle_upc']; ?>
:</td>
	<td class="ProductDetails"><input type="text" name="upc" size="20" maxlength="13" value="<?php echo $this->_tpl_vars['product']['upc']; ?>
" class="InputWidth" /></td>
</tr>

<tr> 
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[product]" /></td><?php endif; ?>
	<td class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_product_name']; ?>
:</td>
	<td class="ProductDetails"> 
	<input type="text" name="product" id="product_name" size="45" class="InputWidth" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['product']['product'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
	<?php if ($this->_tpl_vars['top_message']['fillerror'] != "" && $this->_tpl_vars['product']['product'] == ""): ?><font class="Star">&lt;&lt;</font><?php endif; ?>
	&nbsp;<input type="button" value=" <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_capitalize'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 " onclick="javascript: capitalize('product_name');" />
	</td>
</tr>

<tr>
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[product]" /></td><?php endif; ?>
	<td class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_product_name_froogle']; ?>
:</td>
	<td class="ProductDetails"> 
		<input type="text" name="product_froogle" id="froogle_title" size="45" maxlength="70" class="InputWidth" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['product']['product_froogle'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
		&nbsp;<input type="button" value=" <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_copy'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 " onclick="javascript: copy_product_title_to_froogle();" />
	</td>
</tr>

<tr>
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[keywords]" /></td><?php endif; ?>
	<td class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_keywords']; ?>
:</td>
	<td class="ProductDetails"><input type="text" name="keywords" class="InputWidth" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['product']['keywords'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" /></td>
</tr>

<?php if ($this->_tpl_vars['active_modules']['Egoods'] != ""):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Egoods/egoods.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

<tr> 
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[fulldescr]" /></td><?php endif; ?>
	<td class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_det_description']; ?>
* :</td>
	<td class="ProductDetails">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/textarea.tpl", 'smarty_include_vars' => array('name' => 'fulldescr','cols' => 45,'rows' => 12,'class' => 'InputWidth','data' => $this->_tpl_vars['product']['fulldescr'],'width' => "80%",'btn_rows' => 4)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php if ($this->_tpl_vars['top_message']['fillerror'] != "" && $this->_tpl_vars['product']['fulldescr'] == ""): ?><font class="Star">&lt;&lt;</font><?php endif; ?>
	</td>
</tr>

<tr> 
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[descr]" /></td><?php endif; ?>
	<td class="FormButton" nowrap="nowrap">
		<?php echo $this->_tpl_vars['lng']['lbl_short_description']; ?>
* :<br />
		<font style="font-weight: normal"><?php echo $this->_tpl_vars['lng']['txt_short_descr']; ?>
</font>
	</td>
	<td class="ProductDetails">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/textarea.tpl", 'smarty_include_vars' => array('name' => 'descr','cols' => 45,'rows' => 8,'class' => 'InputWidth','data' => $this->_tpl_vars['product']['descr'],'width' => "80%",'btn_rows' => 4)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	</td>
</tr>

<tr>
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead">&nbsp;</td><?php endif; ?>
	<td colspan="2"><?php echo $this->_tpl_vars['lng']['txt_html_tags_in_description']; ?>
</td>
</tr>

<tr>
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead">&nbsp;</td><?php endif; ?>
	<td colspan="2"><br /><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_pricing'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>

<tr>
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[list_price]" /></td><?php endif; ?>
	<td class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_list_price']; ?>
 <span class="Text">(<?php echo $this->_tpl_vars['config']['General']['currency_symbol']; ?>
)</span></td>
	<td class="ProductDetails"><input type="text" name="list_price" id="list_price" size="18" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['product']['list_price'])) ? $this->_run_mod_handler('formatprice', true, $_tmp) : smarty_modifier_formatprice($_tmp)))) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['zero']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['zero'])); ?>
" /></td>
</tr>

<tr>
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[cost_to_us]" /></td><?php endif; ?>
	<td class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_cost_to_us']; ?>
 (<?php echo $this->_tpl_vars['config']['General']['currency_symbol']; ?>
)</td>
	<td class="ProductDetails">
		<input type="text" name="cost_to_us" id="cost_to_us" size="18" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['product']['cost_to_us'])) ? $this->_run_mod_handler('formatprice', true, $_tmp) : smarty_modifier_formatprice($_tmp)))) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['zero']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['zero'])); ?>
" />&nbsp;
		<?php if ($this->_tpl_vars['product']['cost_to_us_coef_x'] != 0): ?>
			<input type="button" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_copy_to_us_button'])) ? $this->_run_mod_handler('replace', true, $_tmp, 'X', ($this->_tpl_vars['product']['cost_to_us_coef_x'])) : smarty_modifier_replace($_tmp, 'X', ($this->_tpl_vars['product']['cost_to_us_coef_x']))); ?>
" onclick="javascript: generate_price('cost_to_us');" />&nbsp;
		<?php endif; ?>	
		<?php if ($this->_tpl_vars['top_message']['fillerror'] != "" && $this->_tpl_vars['product']['cost_to_us'] == ""): ?><font class="Star">&lt;&lt;</font><?php endif; ?>
	</td>
</tr>

<tr>
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead"><?php if ($this->_tpl_vars['product']['is_variants'] == 'Y'): ?>&nbsp;<?php else: ?><input type="checkbox" value="Y" name="fields[price]" /><?php endif; ?></td><?php endif; ?>
	<td class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_price']; ?>
 (<?php echo $this->_tpl_vars['config']['General']['currency_symbol']; ?>
)</td>
	<td class="ProductDetails">
<?php if ($this->_tpl_vars['product']['is_variants'] == 'Y'): ?>
<b><?php echo $this->_tpl_vars['lng']['lbl_note']; ?>
:</b> <?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['txt_pvariant_edit_note'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'href', $this->_tpl_vars['variant_href']) : smarty_modifier_substitute($_tmp, 'href', $this->_tpl_vars['variant_href'])); ?>

<?php else: ?>
	<input type="text" name="price" id="price" size="18" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['product']['price'])) ? $this->_run_mod_handler('formatprice', true, $_tmp) : smarty_modifier_formatprice($_tmp)))) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['zero']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['zero'])); ?>
" />&nbsp;
	<?php if ($this->_tpl_vars['product']['price_coef_x'] != 0 && $this->_tpl_vars['product']['price_coef_y'] != 0 && $this->_tpl_vars['product']['price_coef_z'] != 0): ?>
		<input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_price_button'])) ? $this->_run_mod_handler('replace', true, $_tmp, 'X', ($this->_tpl_vars['product']['price_coef_x'])) : smarty_modifier_replace($_tmp, 'X', ($this->_tpl_vars['product']['price_coef_x']))))) ? $this->_run_mod_handler('replace', true, $_tmp, 'Y', ($this->_tpl_vars['product']['price_coef_y'])) : smarty_modifier_replace($_tmp, 'Y', ($this->_tpl_vars['product']['price_coef_y']))))) ? $this->_run_mod_handler('replace', true, $_tmp, 'Z', ($this->_tpl_vars['product']['price_coef_z'])) : smarty_modifier_replace($_tmp, 'Z', ($this->_tpl_vars['product']['price_coef_z']))); ?>
" onclick="javascript: generate_price('price');" />&nbsp;
	<?php endif; ?>	
	<?php if ($this->_tpl_vars['top_message']['fillerror'] != "" && $this->_tpl_vars['product']['price'] == ""): ?><font class="Star">&lt;&lt;</font><?php endif;  endif; ?>
	</td>
</tr>

<tr> 
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead">&nbsp;</td><?php endif; ?>
	<td colspan="2"><br /><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_inventory'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>

<tr> 
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead"><?php if ($this->_tpl_vars['product']['is_variants'] == 'Y'): ?>&nbsp;<?php else: ?><input type="checkbox" value="Y" name="fields[avail]" /><?php endif; ?></td><?php endif; ?>
	<td class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_quantity_in_stock']; ?>
</td>
	<td class="ProductDetails">
<?php if ($this->_tpl_vars['product']['is_variants'] == 'Y'): ?>
<b><?php echo $this->_tpl_vars['lng']['lbl_note']; ?>
:</b> <?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['txt_pvariant_edit_note'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'href', $this->_tpl_vars['variant_href']) : smarty_modifier_substitute($_tmp, 'href', $this->_tpl_vars['variant_href'])); ?>

<?php else: ?>
	<input type="text" name="avail" size="18" value="<?php if ($this->_tpl_vars['product']['productid'] == ""):  echo ((is_array($_tmp=@$this->_tpl_vars['product']['avail'])) ? $this->_run_mod_handler('default', true, $_tmp, 1000000) : smarty_modifier_default($_tmp, 1000000));  else:  echo $this->_tpl_vars['product']['avail'];  endif; ?>" />
	<?php if ($this->_tpl_vars['top_message']['fillerror'] != "" && $this->_tpl_vars['product']['avail'] == ""): ?><font class="Star">&lt;&lt;</font><?php endif;  endif; ?>
	</td>
</tr>

<tr> 
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[low_avail_limit]" /></td><?php endif; ?>
	<td class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_lowlimit_in_stock']; ?>
</td>
	<td class="ProductDetails"> 
	<input type="text" name="low_avail_limit" size="18" value="<?php if ($this->_tpl_vars['product']['productid'] == ""): ?>1000<?php else:  echo $this->_tpl_vars['product']['low_avail_limit'];  endif; ?>" />
	<?php if ($this->_tpl_vars['top_message']['fillerror'] != "" && $this->_tpl_vars['product']['low_avail_limit'] <= 0): ?><font class="Star">&lt;&lt;</font><?php endif; ?>
	</td>
</tr>

<tr>
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[min_amount]" /></td><?php endif; ?>
	<td class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_min_order_amount']; ?>
</td>
	<td class="ProductDetails"><input type="text" name="min_amount" size="18" value="<?php if ($this->_tpl_vars['product']['productid'] == ""): ?>1<?php else:  echo $this->_tpl_vars['product']['min_amount'];  endif; ?>" /></td>
</tr>

<?php if ($this->_tpl_vars['active_modules']['RMA'] != ''): ?>
<tr> 
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[return_time]" /></td><?php endif; ?>
	<td class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_return_time']; ?>
</td>
	<td class="ProductDetails"><input type="text" name="return_time" size="18" value="<?php echo $this->_tpl_vars['product']['return_time']; ?>
" /></td>
</tr>
<?php endif; ?>


<tr> 
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead">&nbsp;</td><?php endif; ?>
	<td colspan="2"><br /><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_taxes'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>

<tr> 
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[free_tax]" /></td><?php endif; ?>
	<td class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_tax_exempt']; ?>
</td>
	<td class="ProductDetails">
	<select name="free_tax"<?php if ($this->_tpl_vars['taxes']): ?> onchange="javascript: ChangeTaxesBoxStatus();"<?php endif; ?>>
		<option value='Y'<?php if ($this->_tpl_vars['product']['free_tax'] == 'Y'): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_yes']; ?>
</option>
		<option value='N'<?php if ($this->_tpl_vars['product']['free_tax'] == 'N'): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_no']; ?>
</option>
	</select> 
	</td>
</tr>

<?php if ($this->_tpl_vars['taxes']): ?>
<tr> 
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[taxes]" /></td><?php endif; ?>
	<td class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_apply_taxes']; ?>
</td>
	<td class="ProductDetails"> 
	<select name="taxes[]" multiple="multiple"<?php if ($this->_tpl_vars['product']['free_tax'] == 'Y'): ?> disabled="disabled"<?php endif; ?>>
	<?php unset($this->_sections['tax']);
$this->_sections['tax']['name'] = 'tax';
$this->_sections['tax']['loop'] = is_array($_loop=$this->_tpl_vars['taxes']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['tax']['show'] = true;
$this->_sections['tax']['max'] = $this->_sections['tax']['loop'];
$this->_sections['tax']['step'] = 1;
$this->_sections['tax']['start'] = $this->_sections['tax']['step'] > 0 ? 0 : $this->_sections['tax']['loop']-1;
if ($this->_sections['tax']['show']) {
    $this->_sections['tax']['total'] = $this->_sections['tax']['loop'];
    if ($this->_sections['tax']['total'] == 0)
        $this->_sections['tax']['show'] = false;
} else
    $this->_sections['tax']['total'] = 0;
if ($this->_sections['tax']['show']):

            for ($this->_sections['tax']['index'] = $this->_sections['tax']['start'], $this->_sections['tax']['iteration'] = 1;
                 $this->_sections['tax']['iteration'] <= $this->_sections['tax']['total'];
                 $this->_sections['tax']['index'] += $this->_sections['tax']['step'], $this->_sections['tax']['iteration']++):
$this->_sections['tax']['rownum'] = $this->_sections['tax']['iteration'];
$this->_sections['tax']['index_prev'] = $this->_sections['tax']['index'] - $this->_sections['tax']['step'];
$this->_sections['tax']['index_next'] = $this->_sections['tax']['index'] + $this->_sections['tax']['step'];
$this->_sections['tax']['first']      = ($this->_sections['tax']['iteration'] == 1);
$this->_sections['tax']['last']       = ($this->_sections['tax']['iteration'] == $this->_sections['tax']['total']);
?>
	<option value="<?php echo $this->_tpl_vars['taxes'][$this->_sections['tax']['index']]['taxid']; ?>
"<?php if ($this->_tpl_vars['taxes'][$this->_sections['tax']['index']]['selected'] > 0): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['taxes'][$this->_sections['tax']['index']]['tax_name']; ?>
</option>
	<?php endfor; endif; ?>
	</select>
	<br /><?php echo $this->_tpl_vars['lng']['lbl_hold_ctrl_key']; ?>

	<?php if ($this->_tpl_vars['usertype'] == 'P' || $this->_tpl_vars['active_modules']['Simple_Mode'] != ""): ?><br /><a href="<?php echo $this->_tpl_vars['catalogs']['provider']; ?>
/taxes.php" class="SmallNote" target="_new"><?php echo $this->_tpl_vars['lng']['lbl_click_here_to_manage_taxes']; ?>
</a><?php endif; ?>
	</td>
</tr>
<?php endif; ?>


<tr> 
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead">&nbsp;</td><?php endif; ?>
	<td colspan="2"><br /><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_shipping'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>

<tr> 
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead"><?php if ($this->_tpl_vars['product']['is_variants'] == 'Y'): ?>&nbsp;<?php else: ?><input type="checkbox" value="Y" name="fields[weight]" /><?php endif; ?></td><?php endif; ?>
	<td class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_weight']; ?>
 (<?php echo $this->_tpl_vars['config']['General']['weight_symbol']; ?>
)</td>
	<td class="ProductDetails"> 
<?php if ($this->_tpl_vars['product']['is_variants'] == 'Y'): ?>
<b><?php echo $this->_tpl_vars['lng']['lbl_note']; ?>
:</b> <?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['txt_pvariant_edit_note'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'href', $this->_tpl_vars['variant_href']) : smarty_modifier_substitute($_tmp, 'href', $this->_tpl_vars['variant_href'])); ?>

<?php else: ?>
	<input type="text" name="weight" size="18" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['product']['weight'])) ? $this->_run_mod_handler('formatprice', true, $_tmp) : smarty_modifier_formatprice($_tmp)))) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['zero']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['zero'])); ?>
" />
<?php endif; ?>
	</td>
</tr>

<tr> 
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[dimensions]" /></td><?php endif; ?>
	<td class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_shipping_dimensions']; ?>
</td>
	<td class="ProductDetails"><input type="text" name="dimensions" size="18" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['product']['dim_x'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
,<?php echo ((is_array($_tmp=@$this->_tpl_vars['product']['dim_y'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
,<?php echo ((is_array($_tmp=@$this->_tpl_vars['product']['dim_z'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
" /></td>
</tr>

<tr>
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[shipping_freight]" /></td><?php endif; ?>
	<td class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_shipping_freight']; ?>
 (<?php echo $this->_tpl_vars['config']['General']['currency_symbol']; ?>
)</td>
	<td class="ProductDetails">
	<input type="text" name="shipping_freight" size="18" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['product']['shipping_freight'])) ? $this->_run_mod_handler('formatprice', true, $_tmp) : smarty_modifier_formatprice($_tmp)))) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['zero']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['zero'])); ?>
" />
	</td>
</tr>

<tr> 
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[free_ship_zone]" /></td><?php endif; ?>
	<td class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_free_ship_destination']; ?>
</td>
	<td class="ProductDetails">
	<select name="free_ship_zone">
	<option value="-1"<?php if ($this->_tpl_vars['product']['free_ship_zone'] == '-1'): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_no_free_ship']; ?>
</option>
	<option value="0"<?php if ($this->_tpl_vars['product']['free_ship_zone'] == '0'): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_zone_default']; ?>
</option>
	<?php unset($this->_sections['zid']);
$this->_sections['zid']['name'] = 'zid';
$this->_sections['zid']['loop'] = is_array($_loop=$this->_tpl_vars['shipping_zones']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['zid']['show'] = true;
$this->_sections['zid']['max'] = $this->_sections['zid']['loop'];
$this->_sections['zid']['step'] = 1;
$this->_sections['zid']['start'] = $this->_sections['zid']['step'] > 0 ? 0 : $this->_sections['zid']['loop']-1;
if ($this->_sections['zid']['show']) {
    $this->_sections['zid']['total'] = $this->_sections['zid']['loop'];
    if ($this->_sections['zid']['total'] == 0)
        $this->_sections['zid']['show'] = false;
} else
    $this->_sections['zid']['total'] = 0;
if ($this->_sections['zid']['show']):

            for ($this->_sections['zid']['index'] = $this->_sections['zid']['start'], $this->_sections['zid']['iteration'] = 1;
                 $this->_sections['zid']['iteration'] <= $this->_sections['zid']['total'];
                 $this->_sections['zid']['index'] += $this->_sections['zid']['step'], $this->_sections['zid']['iteration']++):
$this->_sections['zid']['rownum'] = $this->_sections['zid']['iteration'];
$this->_sections['zid']['index_prev'] = $this->_sections['zid']['index'] - $this->_sections['zid']['step'];
$this->_sections['zid']['index_next'] = $this->_sections['zid']['index'] + $this->_sections['zid']['step'];
$this->_sections['zid']['first']      = ($this->_sections['zid']['iteration'] == 1);
$this->_sections['zid']['last']       = ($this->_sections['zid']['iteration'] == $this->_sections['zid']['total']);
?>
	<option value="<?php echo $this->_tpl_vars['shipping_zones'][$this->_sections['zid']['index']]['zoneid']; ?>
"<?php if ($this->_tpl_vars['product']['free_ship_zone'] == $this->_tpl_vars['shipping_zones'][$this->_sections['zid']['index']]['zoneid']): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['shipping_zones'][$this->_sections['zid']['index']]['zone_name']; ?>
</option>
	<?php endfor; endif; ?>
	</select> 
	</td>
</tr>

<tr> 
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[free_ship_text]" /></td><?php endif; ?>
	<td class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_free_ship_text']; ?>
</td>
	<td class="ProductDetails"><input type="text" name="free_ship_text" size="45" class="InputWidth" value="<?php echo $this->_tpl_vars['product']['free_ship_text']; ?>
" /></td>
</tr>

<tr>
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead">&nbsp;</td><?php endif; ?>
	<td colspan="2"><br /><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_discount_settings'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>

<?php if ($this->_tpl_vars['gcheckout_enabled']): ?>

<input type="hidden" name="valid_for_gcheckout" value="N" />
<tr>
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[valid_for_gcheckout]" /></td><?php endif; ?>
	<td class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_gcheckout_product_valid']; ?>
</td>
	<td class="ProductDetails">
	<input type="checkbox" name="valid_for_gcheckout" value="Y"<?php if ($this->_tpl_vars['product']['productid'] == "" || $this->_tpl_vars['product']['valid_for_gcheckout'] == 'Y'): ?> checked="checked"<?php endif; ?> />
	</td>
</tr>

<?php endif; ?>

<?php if ($this->_tpl_vars['active_modules']['Extra_Fields'] != ""):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Extra_Fields/product_modify.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

<tr>
        <?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[discount_slope]" /></td><?php endif; ?>
        <td class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_discount_slope']; ?>
:</td>
        <td class="ProductDetails"><input type="text" name="discount_slope" size="18" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['product']['discount_slope'])) ? $this->_run_mod_handler('formatprice', true, $_tmp) : smarty_modifier_formatprice($_tmp)))) ? $this->_run_mod_handler('default', true, $_tmp, '0.40') : smarty_modifier_default($_tmp, '0.40')); ?>
" /></td>
</tr>

<tr>
        <?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[discount_table]" /></td><?php endif; ?>
        <td class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_discount_table']; ?>
:</td>
        <td class="ProductDetails"><input type="text" name="discount_table" size="45" class="InputWidth" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['product']['discount_table'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('default', true, $_tmp, '2,3,4,6,8,12') : smarty_modifier_default($_tmp, '2,3,4,6,8,12')); ?>
" /></td>
</tr>

<tr>
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[discount_avail]" /></td><?php endif; ?>
	<td class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_apply_global_discounts']; ?>
</td>
	<td class="ProductDetails">
	<input type="checkbox" name="discount_avail" value="Y"<?php if ($this->_tpl_vars['product']['productid'] == "" || $this->_tpl_vars['product']['discount_avail'] == 'Y'): ?> checked="checked"<?php endif; ?> />
	</td>
</tr>

<tr>
	<td<?php if ($this->_tpl_vars['geid'] != ''): ?> colspan="2"<?php endif; ?>>&nbsp;</td>
	<td><br />
			<input type="button" value=" <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_save'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 " onclick="javascript: if (check_froogle_upc_field(document.modifyform.upc)) document.modifyform.submit(); else return false;" />
		</td>
</tr>

</table>
</form>

<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_product_details'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
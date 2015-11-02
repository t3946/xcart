<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:05
         compiled from customer/categories.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'customer/categories.tpl', 1, false),array('function', 'math', 'customer/categories.tpl', 218, false),array('modifier', 'escape', 'customer/categories.tpl', 106, false),)), $this); ?>
<?php func_load_lang($this, "customer/categories.tpl","lbl_category_title,lbl_information"); ?><?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "customer/categories.tpl"), $this); endif;  if ($this->_tpl_vars['menu_brands'] != ""): ?>


<table cellspacing="1" width="100%" class="VertMenuBorder">
<tr>
<td class="VertMenuTitle">
<table cellspacing="0" cellpadding="0" width="100%"><tr>
<td></td>
<td width="100%" valign="middle"><div class="seo_brands_link"><a href="#"><font class="VertMenuTitle" style="color: #0033cc;">Brands</font></a> <img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/br_down.png" alt="" /></div></td>
</tr></table>
</td>
</tr>
<tr id="seo_brands_1">
<td class="VertMenuBox">
<table cellpadding="5" cellspacing="0" width="100%">
<tr><td>

<?php $_from = $this->_tpl_vars['menu_brands']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>

<?php if ($this->_tpl_vars['k'] < $this->_tpl_vars['show_count_before_see_more']): ?>
<a href="brands.php?brandid=<?php echo $this->_tpl_vars['v']['brandid']; ?>
"><?php echo $this->_tpl_vars['v']['brand']; ?>
</a><br />
<?php endif; ?>

<?php endforeach; endif; unset($_from); ?>

<?php if ($this->_tpl_vars['show_see_more'] == 'Y'): ?>
<div id="div_id_brands_see_more" align="right"><a id="brands_see_more" href="brands.php">see more...</a></div>
<?php endif; ?>

</td></tr>
</table>
</td></tr>

<?php if ($this->_tpl_vars['show_see_more'] == 'Y'): ?>
<tr id="seo_brands_2">
<td class="VertMenuBox">
<?php $_from = $this->_tpl_vars['menu_brands']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>

<?php if ($this->_tpl_vars['k'] >= $this->_tpl_vars['show_count_before_see_more']): ?>
<a href="brands.php?brandid=<?php echo $this->_tpl_vars['v']['brandid']; ?>
"><?php echo $this->_tpl_vars['v']['brand']; ?>
</a><br />
<?php endif; ?>

<?php endforeach; endif; unset($_from); ?>
</td>
</tr>
<?php endif; ?>

</table>



<br />
<?php endif; ?>



<?php if ($this->_tpl_vars['active_modules']['Fancy_Categories'] != ""):  ob_start();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Fancy_Categories/categories.tpl", 'smarty_include_vars' => array('cat_start' => 0,'cat_end' => 500)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  $this->assign('fc_cellpadding', '0');  $this->_smarty_vars['capture']['menu'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "menu.tpl", 'smarty_include_vars' => array('menu_title' => $this->_tpl_vars['lng']['lbl_category_title'],'menu_content' => $this->_smarty_vars['capture']['menu'],'cellpadding' => $this->_tpl_vars['fc_cellpadding'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  else: ?>


<?php ob_start(); ?>

<?php if ($this->_tpl_vars['config']['General']['root_categories'] == 'Y'): ?>

  <?php $this->assign('show_category_filter', 'N'); ?>
  <?php if ($this->_tpl_vars['active_modules']['CIDEV_Best_Search_Filter'] != "" && ( ( $this->_tpl_vars['current_category']['categoryid'] > 0 && $this->_tpl_vars['current_category']['main_order_by'] <= 500 ) || $this->_tpl_vars['brandid'] > 0 )): ?>
	<?php $this->assign('show_category_filter', 'Y'); ?>
  <?php endif; ?>

  <?php if ($this->_tpl_vars['show_category_filter'] == 'Y'): ?>
    <?php if ($this->_tpl_vars['cidev_subcategories_products_count'] != ""): ?>
	<table width="100%" cellpadding="2" cellspacing="2" style="background-color: #FFFFFF;">
	<?php $_from = $this->_tpl_vars['subcategories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['subcat']):
?>
	 <?php $_from = $this->_tpl_vars['cidev_subcategories_products_count']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
	  <?php if ($this->_tpl_vars['v']['categoryid'] == $this->_tpl_vars['subcat']['categoryid'] && $this->_tpl_vars['v']['count_products'] > 0): ?>
	<tr>
	<td style="background-color: #FEF6F3; padding-left: 10px; padding-right: 10px;"><font class="CategoriesList"><a class="VertMenuItems" href="/home.php?cat=<?php echo $this->_tpl_vars['subcat']['categoryid']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['subcat']['category'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</font></a> (<?php echo $this->_tpl_vars['v']['count_products']; ?>
)</td>
	</tr>
	  <?php endif; ?>
	 <?php endforeach; endif; unset($_from); ?>
	<?php endforeach; endif; unset($_from); ?>
	</table>
    <?php endif; ?>

  <?php elseif ($this->_tpl_vars['keyword_subcategories'] != ""): ?>

        <table width="100%" cellpadding="2" cellspacing="2" style="background-color: #FFFFFF;">
        <?php $_from = $this->_tpl_vars['keyword_subcategories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['subcat']):
?>
        <tr>
        <td style="background-color: #FEF6F3; padding-left: 10px; padding-right: 10px;"><font class="CategoriesList"><a class="VertMenuItems" href="/home.php?cat=<?php echo $this->_tpl_vars['subcat']['categoryid']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['subcat']['category'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</font></a> (<?php echo $this->_tpl_vars['subcat']['count']; ?>
)</td>
        </tr>
        <?php endforeach; endif; unset($_from); ?>
        </table>

  <?php else: ?>

	<table width="100%" cellpadding="2" cellspacing="2" style="background-color: #FFFFFF;">
	<?php $_from = $this->_tpl_vars['categories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['c']):
?>
	<?php if ($this->_tpl_vars['c']['order_by'] >= 0 && $this->_tpl_vars['c']['order_by'] <= 500 && $this->_tpl_vars['c']['product_count'] > 0): ?>
	<tr>
	<td style="background-color: #FEF6F3; padding-left: 10px; padding-right: 10px;">
	    <?php if ($this->_tpl_vars['c']['categoryid'] == ''): ?>
        	<font class="CategoriesList">
	            <a href="/home.php?scatid=<?php echo $this->_tpl_vars['c']['scatid']; ?>
&amp;keyphrase=<?php echo $this->_tpl_vars['c']['keyphrase']; ?>
" class="VertMenuItems"><?php if ($this->_tpl_vars['c']['is_bold'] == 'Y'): ?><b><?php echo $this->_tpl_vars['c']['category']; ?>
</b><?php else:  echo $this->_tpl_vars['c']['category'];  endif; ?></a>
	        </font>
        	<br />
	    <?php else: ?>
        	<font class="CategoriesList"><?php if ($this->_tpl_vars['c']['categoryid'] != $GLOBALS['_GET']['cat']): ?><a href="/home.php?cat=<?php echo $this->_tpl_vars['c']['categoryid']; ?>
" class="VertMenuItems"><?php endif;  if ($this->_tpl_vars['c']['is_bold'] == 'Y'): ?><b><?php echo $this->_tpl_vars['c']['category']; ?>
</b><?php else:  echo $this->_tpl_vars['c']['category'];  endif;  if ($this->_tpl_vars['c']['categoryid'] != $GLOBALS['_GET']['cat']): ?></a><?php endif; ?></font><br />
	    <?php endif; ?>
	</td>
	</tr>
	<?php endif; ?>
	<?php endforeach; endif; unset($_from); ?>
	</table>
  <?php endif; ?>

<?php else: ?> <?php $_from = $this->_tpl_vars['subcategories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['catid'] => $this->_tpl_vars['c']):
 if ($this->_tpl_vars['c']['order_by'] >= 0 && $this->_tpl_vars['c']['order_by'] <= 500): ?>
    <?php if ($this->_tpl_vars['c']['categoryid'] == ''): ?>
        <font class="CategoriesList">
            <a href="/home.php?scatid=<?php echo $this->_tpl_vars['c']['scatid']; ?>
&amp;keyphrase=<?php echo $this->_tpl_vars['c']['keyphrase']; ?>
" class="VertMenuItems"><?php if ($this->_tpl_vars['c']['is_bold'] == 'Y'): ?><b><?php echo $this->_tpl_vars['c']['category']; ?>
</b><?php else:  echo $this->_tpl_vars['c']['category'];  endif; ?></a>
        </font>
        <br />
    <?php else: ?>
        <font class="CategoriesList"><a href="/home.php?cat=<?php echo $this->_tpl_vars['catid']; ?>
" class="VertMenuItems"><?php if ($this->_tpl_vars['c']['is_bold'] == 'Y'): ?><b><?php echo $this->_tpl_vars['c']['category']; ?>
</b><?php else:  echo $this->_tpl_vars['c']['category'];  endif; ?></a></font><br />
    <?php endif;  endif;  endforeach; endif; unset($_from);  endif; ?>


<?php $this->_smarty_vars['capture']['menu'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "menu.tpl", 'smarty_include_vars' => array('menu_title' => 'Departments','menu_content' => $this->_smarty_vars['capture']['menu'],'cellpadding' => $this->_tpl_vars['fc_cellpadding'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>


<?php endif; ?>




<?php if (( $this->_tpl_vars['current_category']['categoryid'] > 0 && $this->_tpl_vars['current_category']['main_order_by'] <= 500 ) || $this->_tpl_vars['brandid'] > 0): ?>

<?php if ($this->_tpl_vars['filter_found_fv_ids'] != "" || $this->_tpl_vars['filter_selected_and_found_brands'] != "" || $this->_tpl_vars['filter_prices'] != ""):  $this->assign('show_clear_all_button', 'N'); ?>

<!-- igor_async <script type="text/javascript" src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/customer/popup_open.js"></script> -->

<form name="f_searchform" action="<?php echo $this->_tpl_vars['canonical_url']; ?>
/" method="GET">
<input type="hidden" name="f_mode" value="f_search" id="f_mode" >

<br />
<?php ob_start(); ?>
 <?php $this->assign('filter_name', ""); ?>
 <table border="0" cellpadding="0" cellspacing="0" width="100%">

 <?php if ($this->_tpl_vars['cidev_filters_tree_sorted'] != ""): ?>
  <?php $_from = $this->_tpl_vars['cidev_filters_tree_sorted']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
    <?php if ($this->_tpl_vars['v']['filter_values'] != ""): ?>

     <?php $this->assign('row_conter', '0'); ?>

     <?php $_from = $this->_tpl_vars['v']['filter_values']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['tree_filter_values']):
?>

      <?php if ($this->_tpl_vars['tree_filter_values']['found'] == 'Y' || $this->_tpl_vars['tree_filter_values']['selected'] == 'Y'): ?>

	    <?php if ($this->_tpl_vars['filter_name'] != $this->_tpl_vars['v']['f_name']): ?>
		    <?php if ($this->_tpl_vars['filter_name'] != ""): ?>
		        <tr><td colspan="2">&nbsp;</td><tr>
		    <?php endif; ?>
        	<tr><td colspan="2"><B><?php echo $this->_tpl_vars['v']['f_name']; ?>
:</B></td><tr>
	        <?php $this->assign('filter_name', $this->_tpl_vars['v']['f_name']); ?>
	    <?php endif; ?>

        <?php if ($this->_tpl_vars['row_conter'] < $this->_tpl_vars['v']['show_N_fvalues']): ?>
	<tr>
	<td width="5">
		<input name="fv_ids[<?php echo $this->_tpl_vars['tree_filter_values']['fv_id']; ?>
]" id="fv_id_<?php echo $this->_tpl_vars['tree_filter_values']['fv_id']; ?>
" value="Y" type="checkbox"
		<?php if ($this->_tpl_vars['tree_filter_values']['selected'] == 'Y'): ?>
			checked="checked"
			<?php $this->assign('show_clear_all_button', 'Y'); ?>
		<?php endif; ?>
		>
	</td>
	<td <?php if ($this->_tpl_vars['tree_filter_values']['selected'] == 'Y' && $this->_tpl_vars['tree_filter_values']['selected_and_found'] != 'Y'): ?>style="color: #cccccc;"<?php endif; ?>><?php echo $this->_tpl_vars['tree_filter_values']['fv_name']; ?>
 <?php if ($this->_tpl_vars['filter_found_fv_ids_count'][$this->_tpl_vars['tree_filter_values']['fv_id']] != ""): ?>(<?php echo $this->_tpl_vars['filter_found_fv_ids_count'][$this->_tpl_vars['tree_filter_values']['fv_id']]; ?>
)<?php endif; ?></td>
	</tr>
	<?php endif; ?>

	<?php echo smarty_function_math(array('equation' => "x+1",'x' => $this->_tpl_vars['row_conter'],'assign' => 'row_conter'), $this);?>


      <?php endif; ?>
     <?php endforeach; endif; unset($_from); ?>

     <?php if ($this->_tpl_vars['row_conter'] > $this->_tpl_vars['v']['show_N_fvalues']): ?>
        <tr>
        <td colspan="2" align="right">

<a class="simple-button" target="_blank" title="Show more" onclick="javascript: popupOpen('cidev_show_more_filters.php?target=show_more&filter=fvalues&f_id=<?php echo $this->_tpl_vars['v']['f_id']; ?>
', '<?php echo $this->_tpl_vars['v']['f_name']; ?>
'); return false;" href="/cidev_show_more_filters.php?target=show_more&filter=fvalues&f_id=<?php echo $this->_tpl_vars['v']['f_id']; ?>
"><span>Show more</span></a>

        </td>
        <tr>
     <?php endif; ?>

    <?php endif; ?>
  <?php endforeach; endif; unset($_from); ?>
 <?php endif; ?>


 <?php if ($this->_tpl_vars['filter_selected_and_found_brands'] != ""): ?>
  <?php if ($this->_tpl_vars['filter_name'] != ""): ?>
  <tr><td colspan="2">&nbsp;</td><tr>
  <?php endif; ?>
  <tr><td colspan="2"><B>Brand:</B></td><tr>

  <?php $this->assign('row_conter', '0'); ?>

  <?php $_from = $this->_tpl_vars['filter_selected_and_found_brands']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>

   <?php if ($this->_tpl_vars['row_conter'] < $this->_tpl_vars['show_N_brands']): ?>
   <tr>
    <td width="5">
	<input name="b_ids[<?php echo $this->_tpl_vars['v']['brandid']; ?>
]" id="b_id_<?php echo $this->_tpl_vars['v']['brandid']; ?>
" value="Y" type="checkbox"
                <?php if ($this->_tpl_vars['v']['selected'] == 'Y'): ?>
                        checked="checked"
			<?php $this->assign('show_clear_all_button', 'Y'); ?>
                <?php endif; ?>
	>
    </td>
    <td <?php if ($this->_tpl_vars['v']['selected'] == 'Y' && $this->_tpl_vars['v']['selected_and_found'] != 'Y'): ?>style="color: #cccccc;"<?php endif; ?>><?php echo $this->_tpl_vars['v']['brand']; ?>
 (<?php echo $this->_tpl_vars['v']['count_products']; ?>
)</td>
   </tr>
   <?php endif; ?>

   <?php echo smarty_function_math(array('equation' => "x+1",'x' => $this->_tpl_vars['row_conter'],'assign' => 'row_conter'), $this);?>


  <?php endforeach; endif; unset($_from); ?>

  <?php if ($this->_tpl_vars['row_conter'] > $this->_tpl_vars['show_N_brands']): ?>
	<tr>
	<td colspan="2" align="right">

<a class="simple-button" target="_blank" title="Show more" onclick="javascript: popupOpen('cidev_show_more_filters.php?target=show_more&filter=brand', 'Brand'); return false;" href="/cidev_show_more_filters.php?target=show_more&filter=brand"><span>Show more</span></a>

	</td>
	<tr>
  <?php endif; ?>

 <?php endif; ?>


 <?php if ($this->_tpl_vars['filter_prices'] != ""): ?>
  <tr><td colspan="2">&nbsp;</td><tr>
  <tr><td colspan="2"><B>Price:</B></td><tr>

  <?php if ($this->_tpl_vars['filter_max_price_selected'] > '0'): ?>


<script language="JavaScript" type="text/javascript">
<!--
<?php echo '
function uncheckAll_prices(flag, form, prefix) {
        if (!form)
                return;

        if (prefix)
                var reg = new RegExp("^"+prefix, "");
        for (var i = 0; i < form.elements.length; i++) {
                if (form.elements[i].type == "checkbox" && (!prefix || form.elements[i].name.search(reg) == 0) && !form.elements[i].disabled){
                        form.elements[i].checked = false;
                }
        }
}
'; ?>

-->
</script>


	<tr>
	<td>
		<input name="price_ids_range" id="price_ids_range" value="Y" type="checkbox" checked="checked" onclick="javascript: uncheckAll_prices(true, document.f_searchform, 'p_ids');" >
		<input name="filter_min_price_selected" value="<?php echo $this->_tpl_vars['filter_min_price_selected']; ?>
" type="hidden" >
		<input name="filter_max_price_selected" value="<?php echo $this->_tpl_vars['filter_max_price_selected']; ?>
" type="hidden" >
	</td>
	<td>
		<?php echo $this->_tpl_vars['config']['General']['currency_symbol'];  echo $this->_tpl_vars['filter_min_price_selected']; ?>
 - <?php echo $this->_tpl_vars['config']['General']['currency_symbol'];  echo $this->_tpl_vars['filter_max_price_selected']; ?>

		<?php $this->assign('show_clear_all_button', 'Y'); ?>
	</td>
	</tr>
	<tr><td colspan="2">&nbsp;</td><tr>
  <?php endif; ?>

  <?php $_from = $this->_tpl_vars['filter_prices']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
   <tr>
    <td width="5">
        <input name="p_ids[<?php echo $this->_tpl_vars['k']; ?>
]" id="p_id_<?php echo $this->_tpl_vars['k']; ?>
" value="Y" type="checkbox"
                <?php if ($this->_tpl_vars['v']['selected'] == 'Y'): ?>
                        checked="checked"
                        <?php $this->assign('show_clear_all_button', 'Y'); ?>
                <?php endif; ?>

		<?php if ($this->_tpl_vars['v']['count_products'] == '0'): ?>disabled="disabled"
		<?php else: ?>
			<?php if ($this->_tpl_vars['filter_max_price_selected'] > '0'): ?>
				onclick="javascript: document.getElementById('price_ids_range').checked=false;"
			<?php endif; ?>
		<?php endif; ?>
        >
    </td>
    <td <?php if ($this->_tpl_vars['v']['count_products'] == '0'): ?>style="color: #cccccc;"<?php endif; ?>><?php echo $this->_tpl_vars['config']['General']['currency_symbol'];  echo $this->_tpl_vars['v']['min_price']; ?>
 - <?php echo $this->_tpl_vars['config']['General']['currency_symbol'];  echo $this->_tpl_vars['v']['max_price']; ?>
 (<?php echo $this->_tpl_vars['v']['count_products']; ?>
)</td>
   </tr>
  <?php endforeach; endif; unset($_from); ?>
 <?php endif; ?>


<tr>
<td colspan="2"><br />
<table width="100%" cellpadding="0" cellspacing="0">
<tr>
	<td align="left">
<?php if (( $this->_tpl_vars['filter_found_fv_ids'] != "" || $this->_tpl_vars['filter_selected_and_found_brands'] != "" ) || $this->_tpl_vars['brandid'] > 0): ?>
	<input type="submit" value="Show" >
	</td>
<?php endif; ?>
	<td align="right">
	<?php if ($this->_tpl_vars['show_clear_all_button'] == 'Y'): ?>
	<input type="submit" value="Clear All" onclick="javascript: $('#f_mode').val('clear');" >
	<?php endif; ?>
	</td>
<tr>
</table>
</td>
</tr>

 </table>
<?php $this->_smarty_vars['capture']['menu_filter'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "menu.tpl", 'smarty_include_vars' => array('menu_title' => 'Shop By','menu_content' => $this->_smarty_vars['capture']['menu_filter'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

</form>
<?php endif;  endif; ?>


<?php ob_start();  if ($this->_tpl_vars['active_modules']['Fancy_Categories'] != ""):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Fancy_Categories/categories.tpl", 'smarty_include_vars' => array('cat_start' => 501,'cat_end' => 50000)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  $this->assign('fc_cellpadding', '0');  else:  if ($this->_tpl_vars['config']['General']['root_categories'] == 'Y'):  $_from = $this->_tpl_vars['categories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['c']):
 if ($this->_tpl_vars['c']['order_by'] > 500): ?>
    <?php if ($this->_tpl_vars['c']['categoryid'] == ''): ?>
        <font class="CategoriesList">
            <a href="/home.php?scatid=<?php echo $this->_tpl_vars['c']['scatid']; ?>
&amp;keyphrase=<?php echo $this->_tpl_vars['c']['keyphrase']; ?>
" class="VertMenuItems"><?php if ($this->_tpl_vars['c']['is_bold'] == 'Y'): ?><b><?php echo $this->_tpl_vars['c']['category']; ?>
</b><?php else:  echo $this->_tpl_vars['c']['category'];  endif; ?></a>
        </font>
        <br />
    <?php else: ?>
        <font class="CategoriesList"><?php if ($this->_tpl_vars['c']['categoryid'] != $GLOBALS['_GET']['cat']): ?><a href="/home.php?cat=<?php echo $this->_tpl_vars['c']['categoryid']; ?>
" class="VertMenuItems"><?php endif;  if ($this->_tpl_vars['c']['is_bold'] == 'Y'): ?><b><?php echo $this->_tpl_vars['c']['category']; ?>
</b><?php else:  echo $this->_tpl_vars['c']['category'];  endif;  if ($this->_tpl_vars['c']['categoryid'] != $GLOBALS['_GET']['cat']): ?></a><?php endif; ?></font><br />
    <?php endif;  endif;  endforeach; endif; unset($_from);  else: ?> <?php $_from = $this->_tpl_vars['subcategories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['catid'] => $this->_tpl_vars['c']):
 if ($this->_tpl_vars['c']['order_by'] > 500): ?>
    <?php if ($this->_tpl_vars['c']['categoryid'] == ''): ?>
        <font class="CategoriesList">
            <a href="/home.php?scatid=<?php echo $this->_tpl_vars['c']['scatid']; ?>
&amp;keyphrase=<?php echo $this->_tpl_vars['c']['keyphrase']; ?>
" class="VertMenuItems"><?php if ($this->_tpl_vars['c']['is_bold'] == 'Y'): ?><b><?php echo $this->_tpl_vars['c']['category']; ?>
</b><?php else:  echo $this->_tpl_vars['c']['category'];  endif; ?></a>
        </font>
        <br />
    <?php else: ?>
        <font class="CategoriesList"><a href="/home.php?cat=<?php echo $this->_tpl_vars['catid']; ?>
" class="VertMenuItems"><?php echo $this->_tpl_vars['c']['category']; ?>
</a></font><br />
    <?php endif;  endif;  endforeach; endif; unset($_from);  endif;  endif;  $this->_smarty_vars['capture']['menu'] = ob_get_contents(); ob_end_clean(); ?>
<br />
<?php if ($this->_smarty_vars['capture']['menu'] != ""):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "menu.tpl", 'smarty_include_vars' => array('menu_title' => $this->_tpl_vars['lng']['lbl_information'],'menu_content' => $this->_smarty_vars['capture']['menu'],'cellpadding' => $this->_tpl_vars['fc_cellpadding'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "customer/categories.tpl"), $this); endif; ?>
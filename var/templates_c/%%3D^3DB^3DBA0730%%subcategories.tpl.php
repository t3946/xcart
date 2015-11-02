<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:05
         compiled from customer/main/subcategories.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'customer/main/subcategories.tpl', 1, false),array('function', 'math', 'customer/main/subcategories.tpl', 46, false),array('modifier', 'escape', 'customer/main/subcategories.tpl', 54, false),array('modifier', 'lower', 'customer/main/subcategories.tpl', 57, false),)), $this); ?>
<?php func_load_lang($this, "customer/main/subcategories.tpl","lbl_products,lbl_in,lbl_subcat_no_products,lbl_in,lbl_categories,lbl_this_category,lbl_empty_category,txt_no_products_in_cat,lbl_nothing_found_cat_page,lbl_SEO_related_categories_title"); ?><?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "customer/main/subcategories.tpl"), $this); endif;  if ($this->_tpl_vars['active_modules']['Bestsellers'] != "" && $this->_tpl_vars['config']['Bestsellers']['bestsellers_menu'] != 'Y'): ?>
<p />
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Bestsellers/bestsellers.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
<p />
<?php if ($this->_tpl_vars['active_modules']['Special_Offers']):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Special_Offers/customer/category_offers_short_list.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

<?php if ($this->_tpl_vars['current_category']['SEO_category_name'] != ""): ?>
    <?php $this->assign('capture_title', $this->_tpl_vars['current_category']['SEO_category_name']);  elseif ($this->_tpl_vars['keyphrase'] == ''): ?>
    <?php $this->assign('capture_title', $this->_tpl_vars['current_category']['category']);  elseif ($this->_tpl_vars['current_seed_category'] != ''): ?>
    <?php $this->assign('capture_title', $this->_tpl_vars['current_seed_category']);  else: ?>
    <?php $this->assign('capture_title', $this->_tpl_vars['keyphrase']);  endif;  ob_start(); ?>

<?php if ($this->_tpl_vars['current_category']['SEO_h2'] != ""): ?>
<br />
<?php echo $this->_tpl_vars['current_category']['SEO_h2']; ?>

<br />
<?php endif; ?>

<?php $this->assign('tmp', '0');  $_from = $this->_tpl_vars['subcategories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['catid'] => $this->_tpl_vars['c']):
 if ($this->_tpl_vars['c']['category']):  $this->assign('tmp', '1');  endif;  endforeach; endif; unset($_from);  if ($this->_tpl_vars['subcategories']):  if ($this->_tpl_vars['current_category']['main_order_by'] > 500): ?>
<br />
<?php endif;  if ($this->_tpl_vars['current_category']['main_order_by'] > 500): ?>
<table cellspacing="0" width="100%">
<tr>
<td class="DialogTitle" colspan="3" style="PADDING-LEFT: 0px">Further information:<br /><br /></td>
</tr>
</table>
<?php endif; ?>

<?php if ($this->_tpl_vars['current_category']['main_order_by'] > 500 || $this->_tpl_vars['active_modules']['CIDEV_Best_Search_Filter'] == ""): ?>
<table cellspacing="5" width="100%">
<?php echo smarty_function_math(array('assign' => 'hcol','equation' => "y+x",'y' => $this->_tpl_vars['qsubcats'],'x' => 1), $this);?>

<?php $_from = $this->_tpl_vars['subcategories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['subcat']):
?>
<tr>
<?php if ($this->_tpl_vars['tmp'] && $this->_tpl_vars['first_subcat'] != 'Y' && $this->_tpl_vars['current_category']['main_order_by'] <= 500): ?>
	<td valign="top" rowspan="<?php echo $this->_tpl_vars['hcol']; ?>
"><?php if ($this->_tpl_vars['current_category']['icon_url']): ?><img src="<?php echo $this->_tpl_vars['current_category']['icon_url']; ?>
" alt="" /><?php endif; ?></td>
<?php $this->assign('first_subcat', 'Y');  endif; ?>
	<td class="SubcatTitle"<?php if ($this->_tpl_vars['current_category']['main_order_by'] > 500): ?> style="padding-left: 5px"<?php endif; ?>><a href="/home.php?cat=<?php echo $this->_tpl_vars['subcat']['categoryid']; ?>
"> <font class="<?php if (( $this->_tpl_vars['subcat']['parentid'] == $this->_tpl_vars['cat'] && $this->_tpl_vars['subcat']['is_bold'] == 'Y' ) || ( $this->_tpl_vars['subcat']['parentid'] != $this->_tpl_vars['cat'] && $this->_tpl_vars['subcat']['add_is_bold'] == 'Y' )): ?>ItemsList<?php else: ?>ItemsList1<?php endif; ?>"<?php if ($this->_tpl_vars['current_category']['main_order_by'] > 500): ?> face="Verdana"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['subcat']['category'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</font></a><br /></td>
	<td class="SubcatInfo"><?php if ($this->_tpl_vars['config']['Appearance']['count_products'] <= 'Y' && $this->_tpl_vars['current_category']['main_order_by'] <= 500): ?>
	<?php if ($this->_tpl_vars['subcat']['product_count_global'] || $this->_tpl_vars['subcat']['subcategory_count']): ?>
		<?php if ($this->_tpl_vars['subcat']['product_count_global']):  echo $this->_tpl_vars['subcat']['product_count_global']; ?>
&nbsp;<?php echo $this->_tpl_vars['lng']['lbl_products']; ?>
&nbsp;<?php echo $this->_tpl_vars['lng']['lbl_in'];  else:  echo $this->_tpl_vars['lng']['lbl_subcat_no_products']; ?>
&nbsp;<?php echo $this->_tpl_vars['lng']['lbl_in'];  endif; ?>&nbsp;<?php if ($this->_tpl_vars['subcat']['subcategory_count']):  echo $this->_tpl_vars['subcat']['subcategory_count']; ?>
&nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_categories'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp));  else:  echo $this->_tpl_vars['lng']['lbl_this_category'];  endif; ?>
	<?php else: ?>
		<?php echo $this->_tpl_vars['lng']['lbl_empty_category']; ?>

	<?php endif;  endif; ?></td>
</tr>
<?php endforeach; endif; unset($_from); ?>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
</table>
<?php endif; ?>

<?php endif;  if ($this->_tpl_vars['tmp'] && $this->_tpl_vars['products'] != "" && $this->_tpl_vars['current_category']['main_order_by'] <= 500): ?>
<br clear="left" />
<hr size="1" noshade="noshade" />
<?php endif;  if ($this->_tpl_vars['products']):  if ($this->_tpl_vars['sort_fields']): ?>

<div align="right">

<?php if ($this->_tpl_vars['cidev_orig_dispatched_request'] != ""):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/search_sort_by.tpl", 'smarty_include_vars' => array('sort_fields' => $this->_tpl_vars['sort_fields'],'selected' => $this->_tpl_vars['search_prefilled']['sort_field'],'direction' => $this->_tpl_vars['search_prefilled']['sort_direction'],'url' => ($this->_tpl_vars['cidev_orig_dispatched_request'])."/?".($this->_tpl_vars['fv_ids_navigation_url_amp'])."&amp;path=alt&amp;page=".($this->_tpl_vars['navigation_page'])."&amp;")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  else:  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/search_sort_by.tpl", 'smarty_include_vars' => array('sort_fields' => $this->_tpl_vars['sort_fields'],'selected' => $this->_tpl_vars['search_prefilled']['sort_field'],'direction' => $this->_tpl_vars['search_prefilled']['sort_direction'],'url' => "home.php?cat=".($this->_tpl_vars['cat']).($this->_tpl_vars['fv_ids_navigation_url_amp']).($this->_tpl_vars['b_ids_navigation_url_amp'])."&amp;path=alt&amp;page=".($this->_tpl_vars['navigation_page'])."&amp;")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

</div>
<?php endif;  if ($this->_tpl_vars['total_pages'] > 2):  if ($this->_tpl_vars['current_category']['main_order_by'] <= 500): ?>
<hr size="1" width="100%" />
<br />

<?php endif;  else:  if ($this->_tpl_vars['current_category']['main_order_by'] <= 500): ?>
<hr size="1" width="100%" />
<?php endif;  endif;  if ($this->_tpl_vars['current_category']['main_order_by'] <= 500): ?>

<?php if ($this->_tpl_vars['current_storefront'] == '34'): ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/products_new_style.tpl", 'smarty_include_vars' => array('products' => $this->_tpl_vars['products'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  else: ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/products.tpl", 'smarty_include_vars' => array('products' => $this->_tpl_vars['products'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

<?php endif;  endif;  if ($this->_tpl_vars['products'] == "" && $this->_tpl_vars['tmp'] == '0'):  if ($this->_tpl_vars['current_category']['main_order_by'] <= 500):  echo $this->_tpl_vars['lng']['txt_no_products_in_cat']; ?>

<?php endif;  endif; ?>

<?php if ($this->_tpl_vars['e_search_data']['substring'] != "" && $this->_tpl_vars['e_search_data']['total'] == 0):  echo $this->_tpl_vars['lng']['lbl_nothing_found_cat_page']; ?>

<?php endif; ?>

<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['capture_title'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"','use_h1' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  if ($this->_tpl_vars['products'] == ""):  if ($this->_tpl_vars['f_products'] != ""):  if ($this->_tpl_vars['current_category']['main_order_by'] <= 500): ?>
<p />
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/featured.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  endif;  endif;  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/navigation.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if (( ( $this->_tpl_vars['navigation_page'] == "" ) || ( $this->_tpl_vars['navigation_page'] == '1' ) ) && $this->_tpl_vars['cat_with_one_brand_filter'] != 'Y'): ?><p><span class="SPItems-description"><?php echo $this->_tpl_vars['current_category']['description']; ?>
</span><p /><?php endif; ?>

<?php if ($this->_tpl_vars['current_storefront'] == '34'): ?>

<?php $this->assign('show_SEO_related_categories_title', 'Y'); ?>

<?php $_from = $this->_tpl_vars['linked_out_category_indexes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
        <?php $this->assign('linked_out_category_name', "linked_out_category_name_".($this->_tpl_vars['v'])); ?>
        <?php $this->assign('linked_out_category_id', "linked_out_category_id_".($this->_tpl_vars['v'])); ?>

        <?php if ($this->_tpl_vars['current_category'][$this->_tpl_vars['linked_out_category_name']] != ""): ?>

                <?php $this->assign('linked_out_category_keyphrase_selected', "linked_out_category_keyphrase_selected_".($this->_tpl_vars['v'])); ?>

                <?php if ($this->_tpl_vars['current_category'][$this->_tpl_vars['linked_out_category_keyphrase_selected']] != ""): ?>

			<?php if ($this->_tpl_vars['show_SEO_related_categories_title'] == 'Y'): ?>
				<B><?php echo $this->_tpl_vars['lng']['lbl_SEO_related_categories_title']; ?>
</B>
				<br />  
				<?php $this->assign('show_SEO_related_categories_title', 'N'); ?>
			<?php endif; ?>


<div style="float: left; <?php if ($this->_tpl_vars['k'] > 0): ?>padding-left: 15px;<?php endif; ?>">
                        <a target="_blank" style="color: blue;" href="/home.php?cat=<?php echo $this->_tpl_vars['current_category'][$this->_tpl_vars['linked_out_category_id']]; ?>
"><?php echo $this->_tpl_vars['current_category'][$this->_tpl_vars['linked_out_category_keyphrase_selected']]; ?>
</a>
</div>
                <?php endif; ?>
        <?php endif;  endforeach; endif; unset($_from); ?>

<?php endif;  if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "customer/main/subcategories.tpl"), $this); endif; ?>
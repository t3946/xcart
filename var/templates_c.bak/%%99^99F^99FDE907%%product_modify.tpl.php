<?php /* Smarty version 2.6.12, created on 2011-10-11 06:53:28
         compiled from main/product_modify.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'main/product_modify.tpl', 41, false),array('modifier', 'escape', 'main/product_modify.tpl', 43, false),)), $this); ?>
<?php func_load_lang($this, "main/product_modify.tpl","lbl_sku,lbl_product,lbl_product_list,txt_add_product_options_note,lbl_product_options_help,txt_cant_create_product_warning,lbl_register_provider,lbl_warning"); ?><a name="main"></a>
<?php if ($this->_tpl_vars['product']):  $this->assign('page_title', ($this->_tpl_vars['page_title'])."</span>");  endif; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "page_title.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['page_title'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<script type="text/javascript" language="JavaScript 1.2">
<!--
window.name="prodmodwin";
-->
</script>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/include_js.tpl", 'smarty_include_vars' => array('src' => "main/popup_image_selection.js")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/multirow.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  if ($this->_tpl_vars['active_modules']['HTML_Editor']):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/HTML_Editor/editor.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

<!-- IN THIS SECTION -->

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog_tools.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<!-- IN THIS SECTION -->

<?php if ($this->_tpl_vars['products'] && $this->_tpl_vars['geid']): ?>
<br />
<?php ob_start();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/navigation.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<table cellpadding="3" cellspacing="1" width="100%">

<tr class="TableHead">
	<td><?php echo $this->_tpl_vars['lng']['lbl_sku']; ?>
</td>
	<td><?php echo $this->_tpl_vars['lng']['lbl_product']; ?>
</td>
</tr>

<?php $_from = $this->_tpl_vars['products']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>

<tr<?php echo smarty_function_cycle(array('name' => 'ge','values' => ', class="TableSubHead"'), $this);?>
>
	<td><?php if ($this->_tpl_vars['productid'] == $this->_tpl_vars['v']['productid']): ?><b><?php else: ?><a href="product_modify.php?productid=<?php echo $this->_tpl_vars['v']['productid'];  if ($this->_tpl_vars['section'] != 'main'): ?>&amp;section=<?php echo $this->_tpl_vars['section'];  endif; ?>&amp;geid=<?php echo $this->_tpl_vars['geid']; ?>
"><?php endif;  echo ((is_array($_tmp=$this->_tpl_vars['v']['productcode'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

<?php if ($this->_tpl_vars['productid'] == $this->_tpl_vars['v']['productid']): ?></b><?php else: ?></a><?php endif; ?>
</td>
	<td><?php if ($this->_tpl_vars['productid'] == $this->_tpl_vars['v']['productid']): ?><b><?php else: ?><a href="product_modify.php?productid=<?php echo $this->_tpl_vars['v']['productid'];  if ($this->_tpl_vars['section'] != 'main'): ?>&amp;section=<?php echo $this->_tpl_vars['section'];  endif; ?>&amp;geid=<?php echo $this->_tpl_vars['geid']; ?>
"><?php endif;  echo ((is_array($_tmp=$this->_tpl_vars['v']['product'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

<?php if ($this->_tpl_vars['productid'] == $this->_tpl_vars['v']['productid']): ?></b><?php else: ?></a><?php endif; ?>
</td>
</tr>

<?php endforeach; endif; unset($_from); ?>

</table>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/navigation.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('content' => $this->_smarty_vars['capture']['dialog'],'title' => $this->_tpl_vars['lng']['lbl_product_list'],'extra' => "width='100%'")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

<br />

<?php if ($this->_tpl_vars['section'] == 'main' || $this->_tpl_vars['config']['General']['display_all_products_on_1_page'] == 'Y'): ?>
<a name="section_main"></a>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/product_details.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<br />
<?php endif; ?>

<?php if ($this->_tpl_vars['section'] == 'lng' || ( $this->_tpl_vars['config']['General']['display_all_products_on_1_page'] == 'Y' && $this->_tpl_vars['config']['Product_Page']['show_intl_descriptions'] == 'Y' )): ?>
<a name="section_lng"></a>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/products_lng.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<br />
<?php endif; ?>

<?php if ($this->_tpl_vars['section'] == 'subscr' || $this->_tpl_vars['config']['General']['display_all_products_on_1_page'] == 'Y'): ?>
<a name="section_subscr"></a>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Subscriptions/subscription_plans.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<br />
<?php endif; ?>

<?php if ($this->_tpl_vars['section'] == 'options' || $this->_tpl_vars['config']['General']['display_all_products_on_1_page'] == 'Y'): ?>
<a name="section_options"></a>
<?php echo $this->_tpl_vars['lng']['txt_add_product_options_note']; ?>
<br />
<br />
<div align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/button.tpl", 'smarty_include_vars' => array('button_title' => $this->_tpl_vars['lng']['lbl_product_options_help'],'href' => "javascript:window.open('popup_info.php?action=OPT','OPT_HELP','width=600,height=460,toolbar=no,status=no,scrollbars=yes,resizable=no,menubar=no,location=no,direction=no');")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></div>
<br />
<?php if (( $this->_tpl_vars['submode'] == 'product_options_add' || $this->_tpl_vars['product_options'] == '' || $this->_tpl_vars['product_option'] != '' )): ?>

<?php if ($this->_tpl_vars['config']['General']['display_all_products_on_1_page'] == 'Y'):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Product_Options/product_options.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<br />
<?php endif; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Product_Options/add_product_options.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  else:  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Product_Options/product_options.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
<br />
<?php endif; ?>

<?php if ($this->_tpl_vars['product']['is_variants'] == 'Y'):  if ($this->_tpl_vars['section'] == 'variants' || $this->_tpl_vars['config']['General']['display_all_products_on_1_page'] == 'Y'): ?>
<a name="section_variants"></a>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Product_Options/product_variants.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<br />
<?php endif;  endif; ?>

<?php if ($this->_tpl_vars['active_modules']['Product_Configurator'] != ""):  if ($this->_tpl_vars['section'] == 'pclass' || $this->_tpl_vars['config']['General']['display_all_products_on_1_page'] == 'Y'): ?>
<a name="section_pclass"></a>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Product_Configurator/pconf_classification.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<br />
<?php endif;  endif; ?>

<?php if ($this->_tpl_vars['product']['is_variants'] != 'Y' && ( $this->_tpl_vars['section'] == 'wholesale' || $this->_tpl_vars['config']['General']['display_all_products_on_1_page'] == 'Y' )): ?>
<a name="section_wholesale"></a>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Wholesale_Trading/product_wholesale.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<br />
<?php endif; ?>

<?php if ($this->_tpl_vars['section'] == 'upselling' || $this->_tpl_vars['config']['General']['display_all_products_on_1_page'] == 'Y'): ?>
<a name="section_upselling"></a>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Upselling_Products/product_links.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<br />
<?php endif; ?>

<?php if ($this->_tpl_vars['section'] == 'images' || $this->_tpl_vars['config']['General']['display_all_products_on_1_page'] == 'Y'):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Detailed_Product_Images/product_images_modify.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<br />
<?php endif; ?>

<?php if ($this->_tpl_vars['section'] == 'thumb' || $this->_tpl_vars['config']['General']['display_all_products_on_1_page'] == 'Y'): ?>
<a name="section_thumb"></a>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/product_thumb_image.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<br />
<?php endif; ?>

<?php if ($this->_tpl_vars['section'] == 'clone' || $this->_tpl_vars['config']['General']['display_all_products_on_1_page'] == 'Y'): ?>
<a name="section_clone"></a>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/product_clone.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<br />
<?php endif; ?>

<?php if ($this->_tpl_vars['active_modules']['Magnifier'] != ""):  if ($this->_tpl_vars['section'] == 'zoomer' || $this->_tpl_vars['config']['General']['display_all_products_on_1_page'] == 'Y'): ?>
<a name="section_zoomer"></a>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Magnifier/product_magnifier_modify.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<br />
<?php endif;  endif; ?>

<?php if ($this->_tpl_vars['section'] == 'reviews' || $this->_tpl_vars['config']['General']['display_all_products_on_1_page'] == 'Y'): ?>
<a name="section_reviews"></a>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Customer_Reviews/admin_reviews.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<br />
<?php endif; ?>

<?php if ($this->_tpl_vars['active_modules']['Feature_Comparison'] != ""):  if ($this->_tpl_vars['section'] == 'feature_class' || $this->_tpl_vars['config']['General']['display_all_products_on_1_page'] == 'Y'): ?>
<a name="section_feature_class"></a>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Feature_Comparison/product_class.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<br />
<?php endif;  endif; ?>

<?php if ($this->_tpl_vars['section'] == 'error'):  ob_start(); ?>
<br />
<?php echo $this->_tpl_vars['lng']['txt_cant_create_product_warning']; ?>

<br /><br />
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/button.tpl", 'smarty_include_vars' => array('button_title' => $this->_tpl_vars['lng']['lbl_register_provider'],'href' => "user_add.php?usertype=P")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<br />
<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('content' => $this->_smarty_vars['capture']['dialog'],'title' => $this->_tpl_vars['lng']['lbl_warning'],'extra' => "width='100%'")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php endif; ?>
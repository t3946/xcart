<?php /* Smarty version 2.6.12, created on 2015-11-02 03:19:31
         compiled from modules/Brands/customer_brand_products.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'modules/Brands/customer_brand_products.tpl', 1, false),array('modifier', 'escape', 'modules/Brands/customer_brand_products.tpl', 10, false),)), $this); ?>
<?php func_load_lang($this, "modules/Brands/customer_brand_products.tpl","txt_no_products_in_brand"); ?><?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "modules/Brands/customer_brand_products.tpl"), $this); endif; ?><br>
<?php ob_start(); ?>


<?php if ($this->_tpl_vars['brand']['is_image'] == 'Y' || $this->_tpl_vars['brand']['descr'] != ''): ?><table>
<tr>
<?php if ($this->_tpl_vars['brand']['is_image'] == 'Y'): ?>
	<td valign="top"><img src="<?php if ($this->_tpl_vars['brand']['image_path'] != ''):  echo $this->_tpl_vars['brand']['image_path'];  else:  echo $this->_tpl_vars['xcart_web_dir']; ?>
/image.php?id=<?php echo $this->_tpl_vars['brand']['brandid']; ?>
&amp;type=B<?php endif; ?>" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['brand']['brand'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td>
</tr>
<tr>
<?php endif; ?>
	<td valign="top"><span class="SPItems-description"><?php echo $this->_tpl_vars['brand']['descr']; ?>
</span></td>
</tr>
</table>
<br />
<?php endif; ?>


<?php if ($this->_tpl_vars['products'] != ''):  if ($this->_tpl_vars['sort_fields']): ?>
<div align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/search_sort_by.tpl", 'smarty_include_vars' => array('url' => "brands.php?brandid=".($this->_tpl_vars['brand']['brandid'])."&page=".($this->_tpl_vars['navigation_page'])."&",'sort_fields' => $this->_tpl_vars['sort_fields'],'selected' => $this->_tpl_vars['sort'],'direction' => $this->_tpl_vars['sort_direction'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></div>
<hr size="1" noshade="noshade" />
<br />
<?php endif; ?>

<?php if ($this->_tpl_vars['brand']['SEO_h2'] != ""): ?>
<h2><?php echo $this->_tpl_vars['brand']['SEO_h2']; ?>
</h2>
<?php endif; ?>
<br />

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/products.tpl", 'smarty_include_vars' => array('products' => $this->_tpl_vars['products'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  else:  echo $this->_tpl_vars['lng']['txt_no_products_in_brand']; ?>

<?php endif;  $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean(); ?>

<?php if ($this->_tpl_vars['brand']['SEO_brand_name_h1'] != ""): ?>
        <?php $this->assign('brand_name_title', $this->_tpl_vars['brand']['SEO_brand_name_h1']);  else: ?>
        <?php $this->assign('brand_name_title', $this->_tpl_vars['brand']['brand']);  endif; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['brand_name_title'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"','use_h1' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/navigation.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "modules/Brands/customer_brand_products.tpl"), $this); endif; ?>
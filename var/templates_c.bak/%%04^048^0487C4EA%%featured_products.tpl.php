<?php /* Smarty version 2.6.12, created on 2011-10-11 05:54:16
         compiled from admin/main/featured_products.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'admin/main/featured_products.tpl', 33, false),array('modifier', 'strip_tags', 'admin/main/featured_products.tpl', 44, false),array('modifier', 'escape', 'admin/main/featured_products.tpl', 44, false),)), $this); ?>
<?php func_load_lang($this, "admin/main/featured_products.tpl","txt_featured_products,lbl_product_name,lbl_pos,lbl_active,lbl_delete_selected,lbl_update,txt_no_featured_products,lbl_add_product,lbl_sku,lbl_add_new,lbl_featured_products"); ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/include_js.tpl", 'smarty_include_vars' => array('src' => "main/popup_product.js")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<a name="featured"></a>

<?php echo $this->_tpl_vars['lng']['txt_featured_products']; ?>


<br /><br />

<?php ob_start(); ?>

<?php if ($this->_tpl_vars['products'] != ""):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/check_all_row.tpl", 'smarty_include_vars' => array('style' => "line-height: 170%;",'form' => 'featuredproductsform','prefix' => "posted_data.+to_delete")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

<form action="categories.php" method="post" name="featuredproductsform">
<input type="hidden" name="mode" value="update" />
<input type="hidden" name="cat" value="<?php echo $this->_tpl_vars['f_cat']; ?>
" />

<table cellpadding="3" cellspacing="1" width="100%">

<tr class="TableHead">
	<td width="10">&nbsp;</td>
	<td width="70%"><?php echo $this->_tpl_vars['lng']['lbl_product_name']; ?>
</td>
	<td width="15%" align="center"><?php echo $this->_tpl_vars['lng']['lbl_pos']; ?>
</td>
	<td width="15%" align="center"><?php echo $this->_tpl_vars['lng']['lbl_active']; ?>
</td>
</tr>

<?php if ($this->_tpl_vars['products']): ?>

<?php unset($this->_sections['prod_num']);
$this->_sections['prod_num']['name'] = 'prod_num';
$this->_sections['prod_num']['loop'] = is_array($_loop=$this->_tpl_vars['products']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['prod_num']['show'] = true;
$this->_sections['prod_num']['max'] = $this->_sections['prod_num']['loop'];
$this->_sections['prod_num']['step'] = 1;
$this->_sections['prod_num']['start'] = $this->_sections['prod_num']['step'] > 0 ? 0 : $this->_sections['prod_num']['loop']-1;
if ($this->_sections['prod_num']['show']) {
    $this->_sections['prod_num']['total'] = $this->_sections['prod_num']['loop'];
    if ($this->_sections['prod_num']['total'] == 0)
        $this->_sections['prod_num']['show'] = false;
} else
    $this->_sections['prod_num']['total'] = 0;
if ($this->_sections['prod_num']['show']):

            for ($this->_sections['prod_num']['index'] = $this->_sections['prod_num']['start'], $this->_sections['prod_num']['iteration'] = 1;
                 $this->_sections['prod_num']['iteration'] <= $this->_sections['prod_num']['total'];
                 $this->_sections['prod_num']['index'] += $this->_sections['prod_num']['step'], $this->_sections['prod_num']['iteration']++):
$this->_sections['prod_num']['rownum'] = $this->_sections['prod_num']['iteration'];
$this->_sections['prod_num']['index_prev'] = $this->_sections['prod_num']['index'] - $this->_sections['prod_num']['step'];
$this->_sections['prod_num']['index_next'] = $this->_sections['prod_num']['index'] + $this->_sections['prod_num']['step'];
$this->_sections['prod_num']['first']      = ($this->_sections['prod_num']['iteration'] == 1);
$this->_sections['prod_num']['last']       = ($this->_sections['prod_num']['iteration'] == $this->_sections['prod_num']['total']);
?>

<tr<?php echo smarty_function_cycle(array('values' => ", class='TableSubHead'"), $this);?>
>
	<td><input type="checkbox" name="posted_data[<?php echo $this->_tpl_vars['products'][$this->_sections['prod_num']['index']]['productid']; ?>
][to_delete]" /></td>
	<td><b><a href="<?php echo $this->_tpl_vars['catalogs']['customer']; ?>
/product.php?productid=<?php echo $this->_tpl_vars['products'][$this->_sections['prod_num']['index']]['productid']; ?>
" target="_blank"><?php echo $this->_tpl_vars['products'][$this->_sections['prod_num']['index']]['product']; ?>
</a></b></td>
	<td align="center"><input type="text" name="posted_data[<?php echo $this->_tpl_vars['products'][$this->_sections['prod_num']['index']]['productid']; ?>
][product_order]" size="5" value="<?php echo $this->_tpl_vars['products'][$this->_sections['prod_num']['index']]['product_order']; ?>
" /></td>
	<td align="center"><input type="checkbox" name="posted_data[<?php echo $this->_tpl_vars['products'][$this->_sections['prod_num']['index']]['productid']; ?>
][avail]"<?php if ($this->_tpl_vars['products'][$this->_sections['prod_num']['index']]['avail'] == 'Y'): ?> checked="checked"<?php endif; ?> /></td>
</tr>

<?php endfor; endif; ?>

<tr>
	<td colspan="4" class="SubmitBox">
	<input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_delete_selected'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: document.featuredproductsform.mode.value = 'delete'; document.featuredproductsform.submit();" />
	<input type="submit" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_update'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
	</td>
</tr>


<?php else: ?>

<tr>
<td colspan="4" align="center"><?php echo $this->_tpl_vars['lng']['txt_no_featured_products']; ?>
</td>
</tr>

<?php endif; ?>

<tr>
<td colspan="4"><br /><br /><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_add_product'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>

<tr>
	<td>&nbsp;</td>
	<td>
	<input type="hidden" name="newproductid" />
		<?php echo $this->_tpl_vars['lng']['lbl_sku']; ?>
:&nbsp;<input type="text" name="newproductcodes" value="" size="55"/>
	</td>
	<td align="center"><input type="text" name="neworder" size="5" /></td>
	<td align="center"><input type="checkbox" name="newavail" checked="checked" /></td>
</tr>

<tr>
	<td colspan="4" class="SubmitBox">
	<input type="submit" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_add_new'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: document.featuredproductsform.mode.value = 'add'; document.featuredproductsform.submit();"/>
	</td>
</tr>

</table>
</form>

<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_featured_products'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php /* Smarty version 2.6.12, created on 2011-10-11 06:53:29
         compiled from modules/Upselling_Products/product_links.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'modules/Upselling_Products/product_links.tpl', 39, false),array('modifier', 'escape', 'modules/Upselling_Products/product_links.tpl', 44, false),array('modifier', 'truncate', 'modules/Upselling_Products/product_links.tpl', 44, false),array('modifier', 'strip_tags', 'modules/Upselling_Products/product_links.tpl', 74, false),)), $this); ?>
<?php func_load_lang($this, "modules/Upselling_Products/product_links.tpl","txt_upselling_links_top_text,lbl_top,lbl_note,txt_edit_product_group,lbl_pos,lbl_sku,lbl_product,lbl_bl,lbl_no_products,lbl_add_new_link,lbl_product,lbl_browse_,lbl_bidirectional_link,lbl_or,lbl_sku_skus,lbl_add_update,lbl_delete_selected,lbl_upselling_links"); ?><?php if ($this->_tpl_vars['active_modules']['Upselling_Products'] != ""):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/include_js.tpl", 'smarty_include_vars' => array('src' => "main/popup_product.js")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php echo $this->_tpl_vars['lng']['txt_upselling_links_top_text']; ?>


<br /><br />

<?php ob_start();  if ($this->_tpl_vars['config']['General']['display_all_products_on_1_page'] == 'Y'): ?><div align="right"><a href="#main"><?php echo $this->_tpl_vars['lng']['lbl_top']; ?>
</a></div><?php endif; ?>

<form action="product_modify.php" name="upsales" method="post">

<input type="hidden" name="productid" value="<?php echo $this->_tpl_vars['product']['productid']; ?>
" />
<input type="hidden" name="selected_productid" value="" />
<input type="hidden" name="mode" value="upselling_links" />
<input type="hidden" name="geid" value="<?php echo $this->_tpl_vars['geid']; ?>
" />

<table <?php if ($this->_tpl_vars['geid'] != ''): ?>cellspacing="0" cellpadding="4"<?php else: ?>cellspacing="1" cellpadding="2"<?php endif; ?> width="100%">
<?php if ($this->_tpl_vars['geid'] != ''): ?>
<tr>
    <td width="15" class="TableSubHead">&nbsp;</td>
    <td class="TableSubHead" colspan="5"><b>* <?php echo $this->_tpl_vars['lng']['lbl_note']; ?>
:</b> <?php echo $this->_tpl_vars['lng']['txt_edit_product_group']; ?>
</td>
</tr>
<?php endif; ?>
<tr class="TableHead">
<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead">&nbsp;</td><?php endif; ?>
	<td width="5%" class="DataTable">&nbsp;</td>
	<td width="5%" class="DataTable"><?php echo $this->_tpl_vars['lng']['lbl_pos']; ?>
</td>
	<td width="15%" class="DataTable"><?php echo $this->_tpl_vars['lng']['lbl_sku']; ?>
&nbsp;&nbsp;&nbsp;</td>
	<td width="70%"><?php echo $this->_tpl_vars['lng']['lbl_product']; ?>
</td>
	<td width="5%"><?php echo $this->_tpl_vars['lng']['lbl_bl']; ?>
BL&nbsp;&nbsp;&nbsp;</td>
</tr>

<?php if ($this->_tpl_vars['product_links']): ?>

<?php unset($this->_sections['cat_num']);
$this->_sections['cat_num']['name'] = 'cat_num';
$this->_sections['cat_num']['loop'] = is_array($_loop=$this->_tpl_vars['product_links']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['cat_num']['show'] = true;
$this->_sections['cat_num']['max'] = $this->_sections['cat_num']['loop'];
$this->_sections['cat_num']['step'] = 1;
$this->_sections['cat_num']['start'] = $this->_sections['cat_num']['step'] > 0 ? 0 : $this->_sections['cat_num']['loop']-1;
if ($this->_sections['cat_num']['show']) {
    $this->_sections['cat_num']['total'] = $this->_sections['cat_num']['loop'];
    if ($this->_sections['cat_num']['total'] == 0)
        $this->_sections['cat_num']['show'] = false;
} else
    $this->_sections['cat_num']['total'] = 0;
if ($this->_sections['cat_num']['show']):

            for ($this->_sections['cat_num']['index'] = $this->_sections['cat_num']['start'], $this->_sections['cat_num']['iteration'] = 1;
                 $this->_sections['cat_num']['iteration'] <= $this->_sections['cat_num']['total'];
                 $this->_sections['cat_num']['index'] += $this->_sections['cat_num']['step'], $this->_sections['cat_num']['iteration']++):
$this->_sections['cat_num']['rownum'] = $this->_sections['cat_num']['iteration'];
$this->_sections['cat_num']['index_prev'] = $this->_sections['cat_num']['index'] - $this->_sections['cat_num']['step'];
$this->_sections['cat_num']['index_next'] = $this->_sections['cat_num']['index'] + $this->_sections['cat_num']['step'];
$this->_sections['cat_num']['first']      = ($this->_sections['cat_num']['iteration'] == 1);
$this->_sections['cat_num']['last']       = ($this->_sections['cat_num']['iteration'] == $this->_sections['cat_num']['total']);
?>

<tr<?php echo smarty_function_cycle(array('values' => ", class='TableSubHead'"), $this);?>
>
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td  class="TableSubHead"><input type="checkbox" value="Y" name="fields[u_product][<?php echo $this->_tpl_vars['product_links'][$this->_sections['cat_num']['index']]['productid']; ?>
]" /></td><?php endif; ?>
	<td><input type="checkbox" value="Y" name="uids[<?php echo $this->_tpl_vars['product_links'][$this->_sections['cat_num']['index']]['productid']; ?>
]" /></td>
	<td class="DataTable"><input type="text" value="<?php echo $this->_tpl_vars['product_links'][$this->_sections['cat_num']['index']]['orderby']; ?>
" name="upselling[<?php echo $this->_tpl_vars['product_links'][$this->_sections['cat_num']['index']]['productid']; ?>
]" size="4" /></td>
	<td class="DataTable"><?php echo $this->_tpl_vars['product_links'][$this->_sections['cat_num']['index']]['productcode']; ?>
</td>
	<td class="DataTable"><a href="../product.php?productid=<?php echo $this->_tpl_vars['product_links'][$this->_sections['cat_num']['index']]['productid']; ?>
" class="ItemsList" target="_blank"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['product_links'][$this->_sections['cat_num']['index']]['product'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('truncate', true, $_tmp, 100, "...", false) : smarty_modifier_truncate($_tmp, 100, "...", false)); ?>
</a></td>
	<td class="DataTable" align="center"><input type="checkbox" value="Y" name="blids[<?php echo $this->_tpl_vars['product_links'][$this->_sections['cat_num']['index']]['productid']; ?>
]" <?php if ($this->_tpl_vars['product_links'][$this->_sections['cat_num']['index']]['bl'] == 'Y'): ?>checked="checked" <?php endif; ?>/></td>
</tr>
<?php endfor; endif; ?>

<?php else: ?>

<tr>
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead">&nbsp;</td><?php endif; ?>
	<td colspan="5" align="center"><?php echo $this->_tpl_vars['lng']['lbl_no_products']; ?>
</td>
</tr>

<?php endif; ?>

</table>

<table <?php if ($this->_tpl_vars['geid'] != ''): ?>cellspacing="0" cellpadding="4"<?php else: ?>cellspacing="1" cellpadding="2"<?php endif; ?> width="100%">

<tr>
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead">&nbsp;</td><?php endif; ?>
	<td>&nbsp;</td>
</tr>
<tr>
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead">&nbsp;</td><?php endif; ?>
	<td colspan="5"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_add_new_link'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>

<tr>
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[new_u_product]" /></td><?php endif; ?>
	<td colspan="3"><?php echo $this->_tpl_vars['lng']['lbl_product']; ?>
: <input type="text" name="prod_name" size="40" style="width=50%" disabled="disabled" />
<input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_browse_'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: popup_product('upsales.selected_productid', 'upsales.prod_name');" /><br />
<?php echo $this->_tpl_vars['lng']['lbl_bidirectional_link']; ?>
<input type="checkbox" checked="checked" name="bi_directional" />
	</td>

    <td valign="top" align="center"><br/><?php echo $this->_tpl_vars['lng']['lbl_or']; ?>
</td>
    <td valign="top">
        <?php echo $this->_tpl_vars['lng']['lbl_sku_skus']; ?>
<br/>
        <input type="text" size="40" value="" name="selected_sku">
    </td>


</tr>
<tr>
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead">&nbsp;</td><?php endif; ?>
	<td>&nbsp;</td>
</tr>
<tr>
	<?php if ($this->_tpl_vars['geid'] != ''): ?><td width="15" class="TableSubHead">&nbsp;</td><?php endif; ?>
	<td nowrap="nowrap" colspan="5"><input type="submit" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_add_update'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />&nbsp;&nbsp;&nbsp;
	<input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_delete_selected'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: document.upsales.mode.value='del_upsale_link'; document.upsales.submit();" />
	</td>
</tr>
</table>
</form>

<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_upselling_links'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
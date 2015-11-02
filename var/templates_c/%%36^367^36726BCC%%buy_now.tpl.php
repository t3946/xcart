<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:05
         compiled from customer/main/buy_now.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'customer/main/buy_now.tpl', 1, false),array('function', 'math', 'customer/main/buy_now.tpl', 61, false),array('modifier', 'escape', 'customer/main/buy_now.tpl', 10, false),array('modifier', 'substitute', 'customer/main/buy_now.tpl', 129, false),)), $this); ?>
<?php func_load_lang($this, "customer/main/buy_now.tpl","lbl_more_info,lbl_quantity,txt_out_of_stock,txt_need_min_amount_mult,txt_need_min_amount,txt_out_of_stock,lbl_added,lbl_error,lbl_buy_now,lbl_more_info"); ?><?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "customer/main/buy_now.tpl"), $this); endif;  if ($this->_tpl_vars['product']['price'] > 0):  if ($this->_tpl_vars['config']['Product_Options']['buynow_with_options_enabled'] == 'Y' || ( $this->_tpl_vars['product']['avail'] == 0 && $this->_tpl_vars['product']['variantid'] && $this->_tpl_vars['product']['product_type'] != 'C' )):  $this->assign('buynow_enabled', false);  else:  $this->assign('buynow_enabled', true);  endif; ?>
<form name="orderform_<?php echo $this->_tpl_vars['product']['productid']; ?>
_<?php echo $this->_tpl_vars['product']['add_date']; ?>
" method="post" action="<?php if ($this->_tpl_vars['product']['is_product_options'] == 'Y' && ! $this->_tpl_vars['buynow_enabled']): ?>product.php?productid=<?php echo $this->_tpl_vars['product']['productid'];  else: ?>cart.php?mode=add<?php endif; ?>">
<input type="hidden" name="productid" value="<?php echo $this->_tpl_vars['product']['productid']; ?>
" />
<input type="hidden" name="cat" value="<?php echo ((is_array($_tmp=$GLOBALS['_GET']['cat'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" />
<input type="hidden" name="page" value="<?php echo ((is_array($_tmp=$GLOBALS['_GET']['page'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" />
<?php endif; ?>

<table width="100%" cellpadding="0" cellspacing="0">
<?php if ($this->_tpl_vars['product']['price'] == 0): ?>
<tr>
	<td height="25">
		<table cellpadding="0" cellspacing="0">
		<tr>
			<td>
				<?php $this->assign('button_href', ((is_array($_tmp=$GLOBALS['_GET']['page'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'))); ?>
				<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/buy_now.tpl", 'smarty_include_vars' => array('style' => 'button','href' => "/product.php?productid=".($this->_tpl_vars['product']['productid']),'b' => 1)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
			</td>

			<?php if ($this->_tpl_vars['products'][$this->_sections['product']['index']]['clean_url'] != ""): ?>
                                <?php $this->assign('button_href', ($this->_tpl_vars['products'][$this->_sections['product']['index']]['clean_url'])); ?>
                        <?php else: ?>
                                <?php $this->assign('button_href', "product.php?productid=".($this->_tpl_vars['products'][$this->_sections['product']['index']]['productid'])); ?>
                        <?php endif; ?>

			<td style="padding-left: 20px;"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/button.tpl", 'smarty_include_vars' => array('style' => 'button','href' => $this->_tpl_vars['button_href'],'button_title' => $this->_tpl_vars['lng']['lbl_more_info'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
		</tr>
		</table>
	</td>
</tr>
<?php else:  if ($this->_tpl_vars['product']['is_product_options'] != 'Y' || $this->_tpl_vars['buynow_enabled']): ?>
<tr>
<?php if ($this->_tpl_vars['config']['General']['unlimited_products'] != 'Y' && ( $this->_tpl_vars['product']['avail'] <= 0 || $this->_tpl_vars['product']['avail'] < $this->_tpl_vars['product']['min_amount'] ) && $this->_tpl_vars['product']['variantid']): ?>

<?php elseif ($this->_tpl_vars['product']['distribution'] == "" && ! ( $this->_tpl_vars['active_modules']['Subscriptions'] != "" && $this->_tpl_vars['products'][$this->_sections['product']['index']]['catalogprice'] )): ?>

    <?php if ($this->_tpl_vars['new_three_columns_template'] != 'Y'): ?>
	<td class="BuyNowQuantity"><?php echo $this->_tpl_vars['lng']['lbl_quantity']; ?>
:</td>
    <?php endif; ?>

	<td <?php if ($this->_tpl_vars['new_three_columns_template'] != 'Y'): ?> class="BuyNowMiddle" <?php endif; ?> nowrap="nowrap">
<?php if ($this->_tpl_vars['config']['General']['unlimited_products'] != 'Y' && ( $this->_tpl_vars['product']['avail'] <= 0 || $this->_tpl_vars['product']['avail'] < $this->_tpl_vars['product']['min_amount'] )): ?>
<b><?php echo $this->_tpl_vars['lng']['txt_out_of_stock']; ?>
</b>
<?php else:  if ($this->_tpl_vars['config']['General']['unlimited_products'] == 'Y'):  $this->assign('mq', $this->_tpl_vars['config']['Appearance']['max_select_quantity']);  else:  echo smarty_function_math(array('equation' => "x/y",'x' => $this->_tpl_vars['config']['Appearance']['max_select_quantity'],'y' => $this->_tpl_vars['product']['min_amount'],'assign' => 'tmp'), $this);?>
 
<?php $this->assign('minamount', $this->_tpl_vars['product']['min_amount']); ?> 
 
 
<?php $this->assign('step', '1'); ?>
<?php if ($this->_tpl_vars['product']['mult_order_quantity'] == 'Y'): ?>
<?php $this->assign('step', $this->_tpl_vars['product']['min_amount']); ?>
<?php endif; ?> 
<?php echo smarty_function_math(array('equation' => "min(maxquantity*step+minamount, productquantity+1)",'assign' => 'mq','maxquantity' => $this->_tpl_vars['config']['Appearance']['max_select_quantity'],'minamount' => $this->_tpl_vars['minamount'],'productquantity' => $this->_tpl_vars['product']['avail'],'step' => $this->_tpl_vars['step']), $this);?>

<?php endif;  if ($this->_tpl_vars['product']['min_amount'] <= 1):  $this->assign('start_quantity', 1);  else:  $this->assign('start_quantity', $this->_tpl_vars['product']['min_amount']);  endif;  if ($this->_tpl_vars['config']['General']['unlimited_products'] == 'Y'):  echo smarty_function_math(array('equation' => "x+y",'assign' => 'mq','x' => $this->_tpl_vars['mq'],'y' => $this->_tpl_vars['start_quantity']), $this);?>

<?php endif; ?>
<select name="amount">
<?php unset($this->_sections['quantity']);
$this->_sections['quantity']['name'] = 'quantity';
$this->_sections['quantity']['loop'] = is_array($_loop=$this->_tpl_vars['mq']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['quantity']['start'] = (int)$this->_tpl_vars['start_quantity'];
$this->_sections['quantity']['step'] = ((int)$this->_tpl_vars['step']) == 0 ? 1 : (int)$this->_tpl_vars['step'];
$this->_sections['quantity']['show'] = true;
$this->_sections['quantity']['max'] = $this->_sections['quantity']['loop'];
if ($this->_sections['quantity']['start'] < 0)
    $this->_sections['quantity']['start'] = max($this->_sections['quantity']['step'] > 0 ? 0 : -1, $this->_sections['quantity']['loop'] + $this->_sections['quantity']['start']);
else
    $this->_sections['quantity']['start'] = min($this->_sections['quantity']['start'], $this->_sections['quantity']['step'] > 0 ? $this->_sections['quantity']['loop'] : $this->_sections['quantity']['loop']-1);
if ($this->_sections['quantity']['show']) {
    $this->_sections['quantity']['total'] = min(ceil(($this->_sections['quantity']['step'] > 0 ? $this->_sections['quantity']['loop'] - $this->_sections['quantity']['start'] : $this->_sections['quantity']['start']+1)/abs($this->_sections['quantity']['step'])), $this->_sections['quantity']['max']);
    if ($this->_sections['quantity']['total'] == 0)
        $this->_sections['quantity']['show'] = false;
} else
    $this->_sections['quantity']['total'] = 0;
if ($this->_sections['quantity']['show']):

            for ($this->_sections['quantity']['index'] = $this->_sections['quantity']['start'], $this->_sections['quantity']['iteration'] = 1;
                 $this->_sections['quantity']['iteration'] <= $this->_sections['quantity']['total'];
                 $this->_sections['quantity']['index'] += $this->_sections['quantity']['step'], $this->_sections['quantity']['iteration']++):
$this->_sections['quantity']['rownum'] = $this->_sections['quantity']['iteration'];
$this->_sections['quantity']['index_prev'] = $this->_sections['quantity']['index'] - $this->_sections['quantity']['step'];
$this->_sections['quantity']['index_next'] = $this->_sections['quantity']['index'] + $this->_sections['quantity']['step'];
$this->_sections['quantity']['first']      = ($this->_sections['quantity']['iteration'] == 1);
$this->_sections['quantity']['last']       = ($this->_sections['quantity']['iteration'] == $this->_sections['quantity']['total']);
?>
	<option value="<?php echo $this->_sections['quantity']['index']; ?>
"<?php if ($GLOBALS['_GET']['quantity'] == $this->_sections['quantity']['index']): ?> selected="selected"<?php endif; ?>><?php echo $this->_sections['quantity']['index']; ?>
</option>
<?php endfor; endif; ?>
</select>
<?php endif; ?>
	</td>

    <?php if ($this->_tpl_vars['new_three_columns_template'] == 'Y'): ?>

      <?php if ($this->_tpl_vars['config']['General']['unlimited_products'] == 'Y' || ( $this->_tpl_vars['product']['avail'] > 0 && $this->_tpl_vars['product']['avail'] >= $this->_tpl_vars['product']['min_amount'] ) || $this->_tpl_vars['product']['variantid']): ?>

        <?php if ($this->_tpl_vars['special_offers_add_to_cart'] == 'Y'): ?>
                <td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/add_to_cart.tpl", 'smarty_include_vars' => array('style' => 'button','href' => "javascript: document.orderform_".($this->_tpl_vars['product']['productid'])."_".($this->_tpl_vars['product']['add_date']).".submit();",'b' => 1)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
        <?php else: ?>
                <td id="add2cart_<?php echo $this->_tpl_vars['product']['productid']; ?>
" <?php if ($this->_tpl_vars['new_three_columns_template'] == 'Y'): ?>align="right"<?php endif; ?>>

                <?php if ($this->_tpl_vars['product']['lead_time_message'] != ""): ?>
                        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/buy_now.tpl", 'smarty_include_vars' => array('style' => 'button','href' => "javascript:  if ('".($this->_tpl_vars['config']['General']['opt_ajax_cart'])."' == 'Y' &amp;&amp; !('".($this->_tpl_vars['product']['is_product_options'])."' == 'Y' &amp;&amp; !'".($this->_tpl_vars['buynow_enabled'])."')) if (confirm('".($this->_tpl_vars['product']['lead_time_message'])."')) ajax_add_to_cart(".($this->_tpl_vars['product']['productid']).", ".($this->_tpl_vars['product']['add_date']).", 'list'); if (!('".($this->_tpl_vars['config']['General']['opt_ajax_cart'])."' == 'Y' &amp;&amp; !('".($this->_tpl_vars['product']['is_product_options'])."' == 'Y' &amp;&amp; !'".($this->_tpl_vars['buynow_enabled'])."'))) document.orderform_".($this->_tpl_vars['product']['productid'])."_".($this->_tpl_vars['product']['add_date']).".submit();",'b' => 1,'class' => 'ajax_button','add_to_cart_btn' => 'small')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                <?php else: ?>
                        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/buy_now.tpl", 'smarty_include_vars' => array('style' => 'button','href' => "javascript: if ('".($this->_tpl_vars['config']['General']['opt_ajax_cart'])."' == 'Y' &amp;&amp; !('".($this->_tpl_vars['product']['is_product_options'])."' == 'Y' &amp;&amp; !'".($this->_tpl_vars['buynow_enabled'])."')) ajax_add_to_cart(".($this->_tpl_vars['product']['productid']).", ".($this->_tpl_vars['product']['add_date']).", 'list'); else document.orderform_".($this->_tpl_vars['product']['productid'])."_".($this->_tpl_vars['product']['add_date']).".submit();",'b' => 1,'class' => 'ajax_button','add_to_cart_btn' => 'small')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                <?php endif; ?>

                </td>
        <?php endif; ?>
      <?php endif; ?>
    <?php endif; ?>

<?php else: ?>
<tr style="display: none;">
	<td><input type="hidden" name="amount" value="1" /></td>
</tr>
<?php endif; ?>
	<td <?php if ($this->_tpl_vars['new_three_columns_template'] != 'Y'): ?>class="BuyNowPrices"<?php endif; ?>>
<input type="hidden" name="mode" value="add" />

    <?php if ($this->_tpl_vars['new_three_columns_template'] != 'Y'): ?>
	<img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" width="150px" height="1px" border="0" />
    <?php endif; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/product_prices.tpl", 'smarty_include_vars' => array('no_span' => true)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	</td>
</tr>
<?php if ($this->_tpl_vars['product']['min_amount'] > 1): ?>
<tr>
	<td colspan="3"><font class="ProductDetailsTitleWithoutBold"><?php if ($this->_tpl_vars['product']['mult_order_quantity'] == 'Y'):  echo ((is_array($_tmp=$this->_tpl_vars['lng']['txt_need_min_amount_mult'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'items', $this->_tpl_vars['product']['min_amount']) : smarty_modifier_substitute($_tmp, 'items', $this->_tpl_vars['product']['min_amount']));  else:  echo ((is_array($_tmp=$this->_tpl_vars['lng']['txt_need_min_amount'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'items', $this->_tpl_vars['product']['min_amount']) : smarty_modifier_substitute($_tmp, 'items', $this->_tpl_vars['product']['min_amount']));  endif; ?></font></td>
</tr>
<?php endif; ?>
<?php elseif ($this->_tpl_vars['product']['distribution'] == "" && ! ( $this->_tpl_vars['active_modules']['Subscriptions'] != "" && $this->_tpl_vars['products'][$this->_sections['product']['index']]['catalogprice'] ) && $this->_tpl_vars['config']['General']['unlimited_products'] != 'Y' && ( $this->_tpl_vars['product']['avail'] <= 0 || $this->_tpl_vars['product']['avail'] < $this->_tpl_vars['product']['min_amount'] ) && ! $this->_tpl_vars['product']['variantid']): ?>
<tr>
	<td colspan="3" height="25"><b><?php echo $this->_tpl_vars['lng']['txt_out_of_stock']; ?>
</b></td>
</tr>
<?php endif; ?>
<tr>
	<td colspan="3">
<?php if ($this->_tpl_vars['config']['General']['unlimited_products'] == 'Y' || ( $this->_tpl_vars['product']['avail'] > 0 && $this->_tpl_vars['product']['avail'] >= $this->_tpl_vars['product']['min_amount'] ) || $this->_tpl_vars['product']['variantid']):  if ($this->_tpl_vars['new_three_columns_template'] != 'Y'): ?>
<br />
<?php endif; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/include_js.tpl", 'smarty_include_vars' => array('src' => "ajax_add_to_cart.js")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<script type="text/javascript">
<!--
	var lbl_added = "<?php echo $this->_tpl_vars['lng']['lbl_added']; ?>
";
	var lbl_error = "<?php echo $this->_tpl_vars['lng']['lbl_error']; ?>
";
-->
</script>
<table cellpadding="0" cellspacing="0">
<tr>
<?php if ($this->_tpl_vars['js_enabled']): ?>

    <?php if ($this->_tpl_vars['new_three_columns_template'] != 'Y'): ?>

	<?php if ($this->_tpl_vars['special_offers_add_to_cart'] == 'Y'): ?>
		<td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/add_to_cart.tpl", 'smarty_include_vars' => array('style' => 'button','href' => "javascript: document.orderform_".($this->_tpl_vars['product']['productid'])."_".($this->_tpl_vars['product']['add_date']).".submit();",'b' => 1)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
	<?php else: ?>
		<td id="add2cart_<?php echo $this->_tpl_vars['product']['productid']; ?>
">

		<?php if ($this->_tpl_vars['product']['lead_time_message'] != ""): ?>
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/buy_now.tpl", 'smarty_include_vars' => array('style' => 'button','href' => "javascript:  if ('".($this->_tpl_vars['config']['General']['opt_ajax_cart'])."' == 'Y' &amp;&amp; !('".($this->_tpl_vars['product']['is_product_options'])."' == 'Y' &amp;&amp; !'".($this->_tpl_vars['buynow_enabled'])."')) if (confirm('".($this->_tpl_vars['product']['lead_time_message'])."')) ajax_add_to_cart(".($this->_tpl_vars['product']['productid']).", ".($this->_tpl_vars['product']['add_date']).", 'list'); if (!('".($this->_tpl_vars['config']['General']['opt_ajax_cart'])."' == 'Y' &amp;&amp; !('".($this->_tpl_vars['product']['is_product_options'])."' == 'Y' &amp;&amp; !'".($this->_tpl_vars['buynow_enabled'])."'))) document.orderform_".($this->_tpl_vars['product']['productid'])."_".($this->_tpl_vars['product']['add_date']).".submit();",'b' => 1,'class' => 'ajax_button','add_to_cart_btn' => 'small')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<?php else: ?>
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/buy_now.tpl", 'smarty_include_vars' => array('style' => 'button','href' => "javascript: if ('".($this->_tpl_vars['config']['General']['opt_ajax_cart'])."' == 'Y' &amp;&amp; !('".($this->_tpl_vars['product']['is_product_options'])."' == 'Y' &amp;&amp; !'".($this->_tpl_vars['buynow_enabled'])."')) ajax_add_to_cart(".($this->_tpl_vars['product']['productid']).", ".($this->_tpl_vars['product']['add_date']).", 'list'); else document.orderform_".($this->_tpl_vars['product']['productid'])."_".($this->_tpl_vars['product']['add_date']).".submit();",'b' => 1,'class' => 'ajax_button','add_to_cart_btn' => 'small')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<?php endif; ?>

		</td>
	<?php endif; ?>
    <?php endif; ?>


<?php if (( $this->_tpl_vars['login'] != "" || $this->_tpl_vars['config']['Wishlist']['add2wl_unlogged_user'] == 'Y' ) && $this->_tpl_vars['active_modules']['Wishlist'] != "" && $this->_tpl_vars['special_offers_add_to_cart'] == "" && ( $this->_tpl_vars['product']['is_product_options'] != 'Y' || $this->_tpl_vars['buynow_enabled'] )): ?>
	<td style="padding-left: 20px;">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/add_to_wishlist.tpl", 'smarty_include_vars' => array('style' => 'button','href' => "javascript:document.orderform_".($this->_tpl_vars['product']['productid'])."_".($this->_tpl_vars['product']['add_date']).".mode.value='add2wl'; document.orderform_".($this->_tpl_vars['product']['productid'])."_".($this->_tpl_vars['product']['add_date']).".submit()")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	</td>
<?php endif;  else: ?>
	<td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "submit_wo_js.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['lng']['lbl_buy_now'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
<?php endif; ?>

    <?php if ($this->_tpl_vars['new_three_columns_template'] != 'Y'): ?>

        <?php if ($this->_tpl_vars['products'][$this->_sections['product']['index']]['clean_url'] != ""): ?>
	        <?php $this->assign('button_href', ($this->_tpl_vars['products'][$this->_sections['product']['index']]['clean_url'])); ?>
        <?php else: ?>
		<?php $this->assign('button_href', "product.php?productid=".($this->_tpl_vars['products'][$this->_sections['product']['index']]['productid'])); ?>
        <?php endif; ?>

	<td style="padding-left: 20px;"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/button.tpl", 'smarty_include_vars' => array('style' => 'button','href' => ($this->_tpl_vars['button_href']),'button_title' => $this->_tpl_vars['lng']['lbl_more_info'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
    <?php endif; ?>

</tr>
</table>

<?php endif; ?>
	</td>
</tr>
<?php endif; ?>
</table>
<?php if ($this->_tpl_vars['product']['price'] > 0): ?>
</form>
<?php endif;  if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "customer/main/buy_now.tpl"), $this); endif; ?>
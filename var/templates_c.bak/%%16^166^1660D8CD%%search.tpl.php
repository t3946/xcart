<?php /* Smarty version 2.6.12, created on 2011-10-11 05:37:57
         compiled from main/search.tpl */ ?>
<?php func_load_lang($this, "main/search.tpl","lbl_product_sku,lbl_search,lbl_order_id,lbl_search"); ?>
<div class="SearchContainer">
<table class="SearchTable">
<tr>	
	<?php if ($this->_tpl_vars['login'] && ( ( $this->_tpl_vars['usertype'] == 'A' && $this->_tpl_vars['current_membership_flag'] != 'FS' ) || $this->_tpl_vars['usertype'] == 'P' )): ?>
	<td class="SearchTableLeftColumn">

		<form method="post" action="search.php" name="skusearchform">
		<input type="hidden" name="mode" value="search" />
		<input type="hidden" name="fast_search" value="Y" />
		<input type="hidden" name="posted_data[including]" value="all" />
		
		<table cellpadding="0" cellspacing="0">
		<tr>
			<td nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_product_sku']; ?>
:&nbsp;</td>
			<td><input type="text" id="skusearch" name="posted_data[extra_sku][0]" value="" size="25" /></td>
			<td style="padding-left: 1px;">
				<input type="submit" value="<?php echo $this->_tpl_vars['lng']['lbl_search']; ?>
" />
			</td>
		</tr>
		</table>

		</form>

	</td>
	<?php endif; ?>
	<?php if ($this->_tpl_vars['usertype'] == 'A' && $this->_tpl_vars['login']): ?>
	<td<?php if ($this->_tpl_vars['current_membership_flag'] != 'FS'): ?> align="right"<?php else: ?> align="left"<?php endif; ?>>

		<form method="post" action="orders.php" name="productsearchform">
		<input type="hidden" name="fast_search" value="Y" />
		<input type="hidden" name="mode" value="" />

		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>	
			<td nowrap="nowrap">
				<table cellpadding="0" cellspacing="0">
				<tr>
					<td nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_order_id']; ?>
:&nbsp;</td>
					<td>
						<input type="text" id="searchstring" name="posted_data[orderid]" size="12" value="" />
					</td>
					<td>
						<input type="submit" value="<?php echo $this->_tpl_vars['lng']['lbl_search']; ?>
" />
					</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
		</form>

	</td>
	<?php endif; ?>
</tr>
</table>
</div>
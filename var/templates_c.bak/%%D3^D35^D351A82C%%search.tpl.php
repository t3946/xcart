<?php /* Smarty version 2.6.12, created on 2011-10-11 05:37:50
         compiled from customer/search.tpl */ ?>
<?php func_load_lang($this, "customer/search.tpl","lbl_search_by_sku,lbl_search_by_sku,lbl_search_by_sku,lbl_go,lbl_go,lbl_search_by_keyword,lbl_search_by_keyword,lbl_search_by_keyword,lbl_go,lbl_go,lbl_advanced_search"); ?><script type="text/javascript">
<!--
<?php echo '

function search_focus(id, str) {
	el = document.getElementById(id);
	if (el.value == str) {
		el.value = \'\';
	}
}

function search_blur(id, str) {
	el = document.getElementById(id);
	if (el.value == \'\') {
		el.value = str;
	}
}

'; ?>

-->
</script>

<div class="SearchContainer">
<table class="SearchTable">
<tr>	
	<td class="SearchTableLeftColumn" width="172">

		<form method="get" action="product.php" name="skusearchform">
		
		<table cellpadding="0" cellspacing="0">
		<tr>	
			<td><input type="text" id="skusearch" name="sku" value="<?php echo $this->_tpl_vars['lng']['lbl_search_by_sku']; ?>
" onfocus="javascript:search_focus('skusearch', '<?php echo $this->_tpl_vars['lng']['lbl_search_by_sku']; ?>
');" onblur="javascript: search_blur('skusearch', '<?php echo $this->_tpl_vars['lng']['lbl_search_by_sku']; ?>
');" style="background-color: #FFFFFF; width: 146px;" /></td>
			<td style="padding-left: 1px;"><a title="<?php echo $this->_tpl_vars['lng']['lbl_go']; ?>
" href="javascript: document.skusearchform.submit();" class="VertMenuItems"><b><?php echo $this->_tpl_vars['lng']['lbl_go']; ?>
</b></a></td>
		</tr>
		</table>
		</form>

	</td>
	<td align="right">

		<form method="post" action="search.php" name="productsearchform">
		<input type="hidden" name="simple_search" value="Y" />
		<input type="hidden" name="mode" value="search" />
		<input type="hidden" name="posted_data[by_title]" value="Y" />
		<input type="hidden" name="posted_data[by_shortdescr]" value="Y" />
		<input type="hidden" name="posted_data[by_fulldescr]" value="Y" />
		<input type="hidden" name="posted_data[by_sku]" value="Y" />
		<input type="hidden" name="posted_data[including]" value="all" />

		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>	
			<td align="center" nowrap="nowrap">
				<table cellpadding="0" cellspacing="0">
				<tr>
					<td>
						<input type="text" id="searchstring" name="posted_data[substring]" size="80%" value="<?php echo $this->_tpl_vars['lng']['lbl_search_by_keyword']; ?>
" onfocus="javascript:search_focus('searchstring', '<?php echo $this->_tpl_vars['lng']['lbl_search_by_keyword']; ?>
');" onblur="javascript: search_blur('searchstring', '<?php echo $this->_tpl_vars['lng']['lbl_search_by_keyword']; ?>
');" style="background-color: #FFFFFF; margin-right: 0px; padding-right: 0px" />
					</td>
					<td>
						<a href="javascript: document.productsearchform.submit();" class="VertMenuItems" title="<?php echo $this->_tpl_vars['lng']['lbl_go']; ?>
"><b><?php echo $this->_tpl_vars['lng']['lbl_go']; ?>
</b></a>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
		</form>

	</td>
	<td class="SearchTableRightColumn" nowrap="nowrap" align="center" width="172">
		<a href="search.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_advanced_search']; ?>
</a>
	</td>
</tr>
</table>
</div>
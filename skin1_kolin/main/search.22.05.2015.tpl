{* $Id: search.tpl,v 1.0 2010/11/19 14:10:38 kate Exp $ *}

<div class="SearchContainer" {if $usertype eq 'A' && $login}style="width: 100%;"{/if}>
<table class="SearchTable">
<tr>	
	{if $login && (($usertype eq 'A' && $current_membership_flag ne 'FS') || $usertype eq 'P')}
	<td class="SearchTableLeftColumn">

		<form method="post" action="search.php" name="skusearchform">
		<input type="hidden" name="mode" value="search" />
		<input type="hidden" name="fast_search" value="Y" />
		<input type="hidden" name="posted_data[including]" value="all" />
		
		<table cellpadding="0" cellspacing="0">
		<tr>
			<td nowrap="nowrap">{$lng.lbl_product_sku}:&nbsp;</td>
			<td><input type="text" id="skusearch" name="posted_data[extra_sku][0]" value="" size="25" /></td>
			<td style="padding-left: 1px;">
				<input type="submit" value="{$lng.lbl_search}" />
			</td>
		</tr>
		</table>

		</form>

	</td>
	{/if}
	{if $usertype eq 'A' && $login}
	<td width="70">&nbsp;</td>
	<td{if $current_membership_flag neq 'FS'} align="right"{else} align="left"{/if}>

		<form method="post" action="orders.php" name="productsearchform">
		<input type="hidden" name="fast_search" value="Y" /> 
		<input type="hidden" name="mode" value="" />

<script type="text/javascript">
//<![CDATA[
{literal}

$(document).ready(function() {
        $('#select_searchstring_by').change(function() {
		var select_searchstring_by = $('#select_searchstring_by').val();
			$('#searchstring').attr("name", "posted_data["+select_searchstring_by+"]");
        });
});

{/literal}
//]]>
</script>

		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>	
			<td nowrap="nowrap">
				<table cellpadding="0" cellspacing="0">
				<tr>
					<td>
						<select name="select_searchstring_by" id ="select_searchstring_by">
							<option value="orderid">Order #</option>
							<option value="po_number">PO #</option>
							<option value="s_zipcode">Zip code</option>
						</select>
					</td>
					<td>
						<input type="text" id="searchstring" name="posted_data[orderid]" size="12" value="" />
					</td>
					<td>
						<input type="submit" value="{$lng.lbl_search}" />
					</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
		</form>

	</td>
	{/if}
</tr>
</table>
</div>

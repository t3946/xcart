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
</tr>
</table>
</div>

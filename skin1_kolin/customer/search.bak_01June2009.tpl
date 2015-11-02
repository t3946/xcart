{* $Id: search.tpl,v 1.9.2.1 2006/11/27 14:28:38 max Exp $ *}
<form method="post" action="search.php" name="productsearchform">
<input type="hidden" name="simple_search" value="Y" />
<input type="hidden" name="mode" value="search" />
<input type="hidden" name="posted_data[by_title]" value="Y" />
<input type="hidden" name="posted_data[by_shortdescr]" value="Y" />
<input type="hidden" name="posted_data[by_fulldescr]" value="Y" />
<input type="hidden" name="posted_data[by_sku]" value="Y" />
<input type="hidden" name="posted_data[including]" value="all" />
<table cellpadding="0" cellspacing="0">
<tr>
	<td class="VertMenuItems"  style="padding-left: 3px;"><font color="#000000"><b>Find a Product</b></font></td>
</tr>

<tr>
	<td><br></td>
</tr>

<tr>	
	<td style="padding-left: 6px;"><input type="text" name="posted_data[substring]" size="16" value="{$search_prefilled.substring|escape}" style="background-color: #FFFFFF;"></td>
</tr>

<tr>
	<td style="padding-left: 6px;"><a href="javascript: document.productsearchform.submit();" class="VertMenuItems">Search</a></td>
</tr>

<tr>	
	<td style="padding-left: 6px;"><a href="search.php" class="VertMenuItems">{$lng.lbl_advanced_search}</a></td>
</tr>
</table>
</form>

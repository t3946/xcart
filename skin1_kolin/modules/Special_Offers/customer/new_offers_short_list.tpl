{* $Id: new_offers_short_list.tpl,v 1.11 2006/03/28 08:21:09 max Exp $ *}
{if $new_offers}
{math equation="floor(100/x)" x=$config.Special_Offers.offers_per_row assign="tmp_width"}
<div align="center">
<table cellpadding="5" cellspacing="0" width="95%">
{foreach name=offers from=$new_offers item=offer}
{if $config.Special_Offers.offers_per_row eq 1 or ($smarty.foreach.offers.iteration % $config.Special_Offers.offers_per_row) eq 1}
<tr>
{assign var="cell_counter" value=0}
{/if}
{math equation="x+1" x=$cell_counter assign="cell_counter" }
	<td align="center" width="{$tmp_width}%">
{if $offer.promo_short_img eq "Y"}
<a href="offers.php?mode=offer&amp;offerid={$offer.offerid}"><img vspace="5" hspace="5" src="image.php?id={$offer.promo_lng_code}{$offer.offerid}&amp;type=S" alt="" /></a>
{elseif $offer.promo_short ne ""}
{if $offer.html_short}{$offer.promo_short}{else}<a href="offers.php?mode=offer&amp;offerid={$offer.offerid}"><b>{$offer.promo_short|escape}</b></a>{/if}
{else}
{$lng.lbl_sp_new_offers_generic}
{/if}
	</td>
{if $config.Special_Offers.offers_per_row eq 1 or ($smarty.foreach.offers.iteration % $config.Special_Offers.offers_per_row) eq 0}
</tr>
{/if}
{/foreach}
{if $cell_counter lt $config.Special_Offers.offers_per_row}
{section name=rest_cells loop=$config.Special_Offers.offers_per_row start=$cell_counter}
	<td>&nbsp;</td>
{/section}
</tr>
{/if}
<tr>
	<td align="right" colspan="{$config.Special_Offers.offers_per_row}">
<hr class="Line" size="1" />
<table cellspacing="0" cellpadding="0">
<tr>
	<td><a class="NavigationPath" href="offers.php">{$lng.lbl_sp_more_info}</a></td>
	<td><a class="NavigationPath" href="offers.php"><img src="{$ImagesDir}/offerbox_arrow.gif" alt="" /></a></td>
</tr>
</table>
&nbsp;
	</td>
</tr>
</table><!-- border -->
</div>
<br />
{/if}

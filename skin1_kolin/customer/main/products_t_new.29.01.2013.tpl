{* $Id: products_t.tpl,v 1.30.2.4 2006/11/27 11:40:25 max Exp $ *}
<table width="100%" cellpadding="0" cellspacing="0">
<tr>
	<td>

<table width="100%" cellpadding="5" cellspacing="1">

{math equation="floor(100/x)" x=$config.Appearance.products_per_row assign="width"}

{section name=product loop=$products}
{if $products[product].map_price gt $products[product].taxed_price}
{assign var="current_price" value=$products[product].map_price}
{else}
{assign var="current_price" value=$products[product].taxed_price}
{/if}
{assign var="discount" value=0}

{if %product.index% is div by $config.Appearance.products_per_row}
<tr>
{assign var="cell_counter" value=0}
{/if}

{math equation="x+1" x=$cell_counter assign="cell_counter" }

	<td width="{$width}%" class="PListCell">

<table cellpadding="3" cellspacing="0" width="100%">
<tr>
	<td height="100" nowrap="nowrap">
<a href="product.php?productid={$products[product].productid}">{include file="product_thumbnail.tpl" productid=$products[product].productid image_x=$products[product].tmbn_x|default:$config.Appearance.thumbnail_width image_y=$products[product].tmbn_y product=$products[product].product tmbn_url=$products[product].tmbn_url}</a>
{if $active_modules.Special_Offers ne "" and $products[product].have_offers}
{include file="modules/Special_Offers/customer/product_offer_thumb.tpl" product=$products[product]}
{/if}
	</td>
</tr>
</table>

<a href="product.php?productid={$products[product].productid}" class="ProductTitle{if $flag eq "related"}Related{/if}" style="font-weight: normal;"><font color=#0033CC>{$products[product].product}</font></a><br />


{if $products[product].product_type ne "C"}
{if $active_modules.Subscriptions ne "" and $products[product].catalogprice}
{include file="modules/Subscriptions/subscription_info_inlist.tpl"}
{else}
{if $config.General.unlimited_products ne "Y" && ($products[product].avail le 0 or $products[product].avail lt $products[product].min_amount) && $products[product].variantid}
&nbsp;
{elseif $current_price ne 0}
{if $products[product].list_price gt 0 and $current_price lt $products[product].list_price}
{math equation="100-(price/lprice)*100" price=$current_price lprice=$products[product].list_price format="%3.0f" assign=discount}
{if $discount gt 0}

<span class="btn btn-price btn-price_big btn-price_not-available">
<del>{include file="currency.tpl" value=$products[product].list_price}</del>
</span>

{/if}
{/if}



<span class="btn btn-price btn-price_big btn-price_stock-in">
{include file="currency.tpl" value=$current_price}
</span>

{* {if $discount gt 0}{if $config.General.alter_currency_symbol ne ""},{/if} <font color=#000000 size=2>{$lng.lbl_save_price} {$discount}%</font>{/if} *}

{if $products[product].map_price gt $products[product].taxed_price}
<br /><span class="map_price_help">{$config.Product_Page.map_bridge_text}</span>
{/if}
{if $products[product].taxes}<br />{include file="customer/main/taxed_price.tpl" taxes=$products[product].taxes}{/if}
{if $active_modules.Special_Offers ne "" and $products[product].use_special_price ne ""}
{include file="modules/Special_Offers/customer/product_special_price.tpl" product=$products[product]}
{/if}
{else}
<font class="ProductPrice">{$lng.lbl_enter_your_price}</font>
{/if}
{/if}

{if $active_modules.Feature_Comparison ne '' && $products[product].fclassid > 0}
<div align="center" style="width: 100%; padding-top: 10px;">
{include file="modules/Feature_Comparison/compare_checkbox.tpl" id=$products[product].productid}
</div>
{/if}
{*** Uncomment it if you need 'Buy Now' button ***
{if $usertype eq "C" and $config.Appearance.buynow_button_enabled eq "Y"}
{include file="customer/main/buy_now.tpl" product=$products[product]}
{/if}
*** Uncomment it if you need 'Buy Now' button ***}
{/if}
	</td>

{capture name=prod_index}
{math equation="index+x+1" index=%product.index% x=$config.Appearance.products_per_row}
{/capture}
{if $smarty.capture.prod_index is div by $config.Appearance.products_per_row }
</tr>
{/if}

{/section}

{if $cell_counter lt $config.Appearance.products_per_row}
{section name=rest_cells loop=$config.Appearance.products_per_row start=$cell_counter}
	<td class="SectionBox">&nbsp;</td>
{/section}
</tr>
{/if}

</table>
	</td>
</tr>
</table>
{if $active_modules.Feature_Comparison ne '' && $products && $printable ne 'Y' && $products_has_fclasses}
{include file="modules/Feature_Comparison/compare_selected_button.tpl"}
{/if}


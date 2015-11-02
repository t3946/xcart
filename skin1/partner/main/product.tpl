{* $Id: product.tpl,v 1.22.2.2 2004/12/13 14:05:36 max Exp $ *}
{include file="page_title.tpl" title=$lng.lbl_product_html_code}
{$lng.txt_product_html_code_note}<BR><BR>
 
<!-- IN THIS SECTION -->
 
{include file="dialog_tools.tpl"}
 
<!-- IN THIS SECTION -->
<BR>
 
{capture name=dialog}
<TABLE border="0" width="100%">
<TR>
<TD valign="top" align="left" rowspan="2" width="100">
{include file="product_thumbnail.tpl" productid=$product.productid image_x=$product.image_x image_y=$product.image_y product=$product.product}&nbsp;
</TD>
<TD valign="top">
{if $product.fulldescr ne ""}{$product.fulldescr}{else}{$product.descr}{/if}
<P>
<TABLE width="100%" cellpadding="0" cellspacing="0" border="0">
<TR><TD colspan="2"><B><FONT class="ProductDetailsTitle">{$lng.lbl_details}</FONT></B></TD></TR>
<TR><TD class="Line" height="1" colspan="2"><IMG src="{$ImagesDir}/spacer.gif" width="1" height="1" border="0"></TD></TR>
<TR><TD colspan="2">&nbsp;</TD></TR>
{if $config.Appearance.show_in_stock eq "Y" and $product.distribution eq ""}
<TR><TD width="30%">{$lng.lbl_quantity}</TD><TD nowrap>{if $product.avail gt 0}{$product.avail}{else}{$lng.txt_no}{/if} {$lng.txt_items_available}</TD></TR>
{/if}
<TR><TD width="30%">{$lng.lbl_weight}</TD><TD nowrap>{$product.weight} {$config.General.weight_symbol}</TD></TR>
{if $active_modules.Extra_Fields ne ""}
{include file="modules/Extra_Fields/product.tpl"}
{/if}
{if $active_modules.Subscriptions ne "" and $subscription}
{include file="modules/Subscriptions/subscription_info.tpl"}
{else}
<TR><TD class="ProductPriceConverting">{$lng.lbl_price}:</TD>
<TD>
{if $product.taxed_price ne 0}
<FONT class="ProductDetailsTitle">{include file="currency.tpl" value=$product.taxed_price}</FONT><FONT class="MarketPrice"> {include file="customer/main/alter_currency_value.tpl" alter_currency_value=$product.taxed_price}</FONT>
{if $product.taxes}<BR>{include file="customer/main/taxed_price.tpl" taxes=$product.taxes}{/if}
{else}
<INPUT type="text" size="7" name="price">
{/if}
</TD>
</TR>
{/if}
</TABLE>
</TD>
</TR>
</TABLE>
{/capture}
{include file="dialog.tpl" title=$product.producttitle content=$smarty.capture.dialog extra="width=100%"}
{if $active_modules.Detailed_Product_Images ne ""}
<BR>
{include file="modules/Detailed_Product_Images/product_images.tpl" }
{/if}
<P>
{capture name=dialog}
<P>
{$lng.txt_product_html_code_comment}
<P align="center"><B>{$catalogs.customer}/product.php?productid={$product.productid}&partner={$login}</B></P>

{if $banners ne ''}
<TABLE cellpadding="2" cellspacing="3" border="0" width="100%" align="center">
{foreach from=$banners item=v}
<TR>
	<TH class="TableHead">{$v.banner}</TH>
</TR>
<TR>
	<TD align="center">
{capture name="html_1"}{include file="main/display_banner.tpl" banner=$v type="js" partner=$login productid=$product.productid}{/capture}
<P>
{$smarty.capture.html_1}
</P>
</TD>
</TR>
<TR> 
    <TD align="center"><B>{$lng.lbl_iframe_code}:</B></TD>
</TR> 
<TR>
    <TD align="center"><TEXTAREA cols="65" rows="6">{include file="main/display_banner.tpl" banner=$v type="iframe" partner=$login productid=$product.productid current_location=$http_location}</TEXTAREA>
</TR>
<TR>
	<TD align="center"><B>{$lng.lbl_javascript_version}:</B></TD>
</TR>
<TR>
	<TD align="center"><TEXTAREA cols="65" rows="6">{$smarty.capture.html_1}</TEXTAREA>
</TR>
<TR>
	<TD><HR size="1" noshade align="center"></TD>
</TR>
{/foreach}
</TABLE>
{/if}
{/capture}
{include file="dialog.tpl" title=$lng.lbl_product_html_code content=$smarty.capture.dialog extra="width=100%"}
<P>
{if $active_modules.Upselling_Products ne ""}
<BR>
{include file="modules/Upselling_Products/related_products.tpl" }
{/if}

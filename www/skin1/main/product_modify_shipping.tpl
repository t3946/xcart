{* $Id: product_modify_shipping.tpl,v 1.9 2004/05/28 12:21:03 max Exp $ *}
<TABLE border="0" cellspacing="0" cellpadding="0" width="100%">
<TR>
  <TD valign="top" class="ProductDetailsTitle">Shipping</TD>
  <TD>&nbsp;</TD>
</TR>
<TR>
  <TD class="Line" height="1"><IMG src="{$ImagesDir}/spacer.gif" width="1" height="1" border="0"></TD>
  <TD class="Line" height="1"><IMG src="{$ImagesDir}/spacer.gif" width="1" height="1"></TD>
</TR>
<TR>
  <TD></TD>
  <TD></TD>
</TR>
<TR valign="top">
  <TD class="ProductDetails"><BR>
Select shipping<BR>methods <BR>this product<BR>can be shipped.
</TD>
<TD>
<BR>
{if $smarty.get.productid ne "" and $fillerror eq ""}
{section name=ship_num loop=$product.delivery}
<INPUT type="checkbox" name="{$product.delivery[ship_num].shippingid}" {if $product.delivery[ship_num].avail ne 0}checked{/if}>
{$product.delivery[ship_num].shipping|trademark}{if $product.delivery[ship_num].shipping_time ne ""} ({$product.delivery[ship_num].shipping_time}){/if}<BR>
{/section}
{else}
{section name=ship_num loop=$shipping}
<INPUT type="checkbox" name="{$shipping[ship_num].shippingid}" checked>
{$shipping[ship_num].shipping|trademark}{if $shipping[ship_num].shipping_time ne ""} ({$shipping[ship_num].shipping_time}){/if}<BR>
{/section}
{/if}
<BR>
</TD>
</TR>
</TABLE>

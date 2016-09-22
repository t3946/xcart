{* $Id: order_message.tpl,v 1.35 2005/11/28 14:19:29 max Exp $ *}
{ include file="ga_code_sales.tpl" } 
{if $this_is_printable_version eq ""}
<br>
{capture name=dialog}
<font class="ProductDetails">{$lng.txt_order_placed}</font>
{* <br /><br /> *}
<font class="ProductDetails">{$lng.txt_order_placed_msg}</font>
<br />
{/capture}
{include file="dialog.tpl" title=$lng.lbl_confirmation content=$smarty.capture.dialog extra='width="100%"'}
{/if}
<br />
{capture name=dialog}
{section name=oi loop=$orders}
{include file="mail/html/order_invoice.tpl" is_nomail='Y' products=$orders[oi].products giftcerts=$orders[oi].giftcerts userinfo=$orders[oi].userinfo order=$orders[oi].order}
{* <br /><br /><br /><br /> *}
{if $active_modules.Interneka ne ""}
{ include file="modules/Interneka/interneka_tags.tpl" } 
{/if}
{/section}
{if $this_is_printable_version eq ""}
<table width="100%">
<tr>
<td align="left" width="150" nowrap="nowrap">{include file="buttons/button.tpl" button_title=$lng.lbl_print_invoice href="order.php?mode=invoice&orderid=`$orderids`" target="preview_invoice"}</td>

{* -------------- *}
<td align="left" width="200" nowrap="nowrap">
{assign var=access_key value=""}
{if $orders}
  {if $orders[0].order.access_key}
    {assign var=access_key value="&amp;access_key=`$orders[0].order.access_key`"}
  {/if}
{elseif $order}
  {if $order.access_key}
    {assign var=access_key value="&amp;access_key=`$order.access_key`"}
  {/if}
  {assign var=orderids value=$order.orderid}
{/if}

{include file="buttons/button.tpl" button_title=$lng.lbl_xpdf_pdf_invoice href="xpdf.php?mode=invoice&orderid=`$orderids``$access_key`" target="preview_invoice"}
</td>
{* -------------- *}

<td width="*" align="right">{include file="buttons/button.tpl" button_title=$lng.lbl_continue_shopping style="button" href="home.php"}</td>
</tr>
</table>
{/if}
{/capture}
{include file="dialog.tpl" title=$lng.lbl_invoice content=$smarty.capture.dialog extra='width="100%"'}
{assign var=aRetailTrustProductDetails value=$oOrder->getOrderDetailsWithProductsRetailTrust()}
{assign var=aRetailTrustOrderDetails value=$oOrder->getOrderDetailsWithRetailTrust()}
{if !empty($aRetailTrustProductDetails) && empty($aRetailTrustOrderDetails)}
  {assign var=oOrder value=$oOrder}
  {include file="customer/main/retail_trust.tpl"}
{/if}
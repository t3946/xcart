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



<!-- Google Code for Conversion Tracking: Order Conversion Page -->
<script type="text/javascript">
/* <![CDATA[ */
{literal}
var google_conversion_id = 1072406910;
var google_conversion_language = "en";
var google_conversion_format = "3";
var google_conversion_color = "ffffff";
var google_conversion_label = "9T_YCJXjmXMQ_sKu_wM";
var google_conversion_value = {/literal}{$orders[0].order.total}{literal};
var google_conversion_currency = "USD";
var google_remarketing_only = false;
{/literal}
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/1072406910/?value={$orders[0].order.total}&amp;currency_code=USD&amp;label=9T_YCJXjmXMQ_sKu_wM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
<!-- Google Code for Conversion Tracking: Order Conversion Page -->



{include file="buttons/button.tpl" button_title=$lng.lbl_xpdf_pdf_invoice href="xpdf.php?mode=invoice&orderid=`$orderids``$access_key`" target="preview_invoice"}
</td>
{* -------------- *}

<td width="*" align="right">{include file="buttons/button.tpl" button_title=$lng.lbl_continue_shopping style="button" href="home.php"}</td>
</tr>
</table>
{/if}
{/capture}
{include file="dialog.tpl" title=$lng.lbl_invoice content=$smarty.capture.dialog extra='width="100%"'}
{if $GTS_order_confirmation_module_code ne ""}
  <script>
    gts_code=$('{$GTS_order_confirmation_module_code|escape:javascript}');
  </script>
{/if}

{assign var=aRetailTrustProductDetails value=$oOrder->getOrderDetailsWithProductsRetailTrust()}
{assign var=aRetailTrustOrderDetails value=$oOrder->getOrderDetailsWithRetailTrust()}
{if !empty($aRetailTrustProductDetails) && empty($aRetailTrustOrderDetails)}
  {include file="customer/main/retail_trust.tpl"}
{else}
{if $GTS_order_confirmation_module_code ne ""}
<script>
  $("body").append(gts_code);
</script>
{/if}
{/if}
{section name=oi loop=$orders}
    <IMG src="https://shareasale.com/sale.cfm?amount={$orders[oi].order.subtotal}&tracking={$orders[oi].order.order_prefix}{$orders[oi].order.orderid}&transtype=sale&merchantID=69373" width="1" height=1>
{/section}



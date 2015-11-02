{* $Id: order_message.tpl,v 1.35 2005/11/28 14:19:29 max Exp $ *}
{ include file="ga_code_sales.tpl" } 
{if $this_is_printable_version eq ""}
<br>
{capture name=dialog}
<font class="ProductDetails">{$lng.txt_order_placed}</font>
<br /><br />
<font class="ProductDetails">{$lng.txt_order_placed_msg}</font>
<br />
{/capture}
{include file="dialog.tpl" title=$lng.lbl_confirmation content=$smarty.capture.dialog extra='width="100%"'}
{/if}
<br />
{capture name=dialog}
{section name=oi loop=$orders}
{include file="mail/html/order_invoice.tpl" is_nomail='Y' products=$orders[oi].products giftcerts=$orders[oi].giftcerts userinfo=$orders[oi].userinfo order=$orders[oi].order}
<br /><br /><br /><br />
{if $active_modules.Interneka ne ""}
{ include file="modules/Interneka/interneka_tags.tpl" } 
{/if}
{/section}
{if $this_is_printable_version eq ""}
<table width="100%">
<tr>
<td align="left">{include file="buttons/button.tpl" button_title=$lng.lbl_print_invoice href="order.php?mode=invoice&orderid=`$orderids`" target="preview_invoice"}</td>
<td align="right">{include file="buttons/button.tpl" button_title=$lng.lbl_continue_shopping style="button" href="home.php"}</td>
</tr>
</table>

<!-- Google Code for Order Conversion Page -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 1072406910;
var google_conversion_language = "en";
var google_conversion_format = "1";
var google_conversion_color = "ffffff";
var google_conversion_label = "deNACOfH5gQQ_sKu_wM";
var google_conversion_value = 0;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js"></script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/1072406910/?value=0&amp;label=deNACOfH5gQQ_sKu_wM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>

{/if}
{/capture}
{include file="dialog.tpl" title=$lng.lbl_invoice content=$smarty.capture.dialog extra='width="100%"'}

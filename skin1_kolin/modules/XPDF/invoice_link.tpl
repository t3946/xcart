{*
$Id: invoice_link.tpl 188 2011-06-04 16:36:19Z max $
vim: set ts=2 sw=2 sts=2 et:
*}

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

{include file="customer/buttons/button.tpl" button_title=$lng.lbl_xpdf_pdf_invoice href="xpdf.php?mode=invoice&orderid=`$orderids``$access_key`" style="link" additional_button_class="xpdf-link"}

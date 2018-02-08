{*
$Id: history_order.tpl 63 2012-10-30 11:56:13Z skot $
vim: set ts=2 sw=2 sts=2 et:
*}
<h1>{$lng.lbl_order_details_label}</h1>
<p class="text-block">{$lng.txt_order_details_top_text}</p>
<div class="ui-grid-a">
  {if $orderid_prev ne ""}
    <div class="ui-block-a">
      <a href="order.php?orderid={$orderid_prev}" data-role="button" data-theme="b">&lt;&lt;&nbsp;{$lng.lbl_order} #{$orderid_prev}</a>
    </div>
  {/if}
  {if $orderid_next ne ""}
    <div class="ui-block-b">
      <a href="order.php?orderid={$orderid_next}" data-role="button" data-theme="b">{$lng.lbl_order} #{$orderid_next}&nbsp;&gt;&gt;</a>
    </div>
  {/if}
</div>

{if $active_modules.RMA ne ''} 
  <div class="ui-grid-a">
    {if $return_products ne ''}
      <div class="ui-block-a">
        {include file="customer/buttons/button.tpl" button_title=$lng.lbl_create_return href="javascript: $.mobile.silentScroll($('a[name=returns]').offset().top);" style="link"}
      </div>
    {/if}
    {if $order.is_returns}
      <div class="ui-block-b">
        {include file="customer/buttons/button.tpl" button_title=$lng.lbl_order_returns href="returns.php?mode=search&search[orderid]=`$order.orderid`" style="link"}
      </div>
    {/if}
  </div>
{/if}
{assign var=order_url value="order.php?orderid=`$order.orderid`"}
{if $order.access_key}
  {assign var=order_url value="`$order_url`&amp;access_key=`$order.access_key`"}
{/if}
{if $active_modules.Advanced_Order_Management and $order.history ne ""}
  <div>
    {include file="customer/buttons/button.tpl" button_title=$lng.lbl_aom_show_history href="javascript:popupOpen('`$order_url`&amp;mode=history','`$lng.lbl_aom_show_history`')" style="link" link_href="`$order_url`&mode=history" target="_blank" data_theme="e"}
  </div>
{/if}

<hr />
{include file="customer/main/order_invoice.tpl"}
{if $active_modules.Order_Tracking and $order.tracking}
  <br />
  <br />
  <br />
  {include file="customer/subheader.tpl" title=$lng.lbl_tracking_order}
  {assign var="postal_service" value=$order.shipping|truncate:3:"":true}
  {$lng.lbl_tracking_number}: {$order.tracking}<br />
  <br />
  {if $postal_service eq "UPS"}
    {include file="modules/Order_Tracking/ups.tpl"}
  {elseif $postal_service eq "USP"}
    {include file="modules/Order_Tracking/usps.tpl"}
  {elseif $postal_service eq "Fed"}
    {include file="modules/Order_Tracking/fedex.tpl"}
  {elseif $postal_service eq "Aus"}
    {include file="modules/Order_Tracking/australia_post.tpl"}
  {elseif $postal_service eq "DHL"}
    {include file="modules/Order_Tracking/dhl.tpl"}
  {/if}
{/if}
{if $active_modules.RMA}
  <a name="returns"></a>
  {include file="modules/RMA/customer/add_returns.tpl"}
{/if}

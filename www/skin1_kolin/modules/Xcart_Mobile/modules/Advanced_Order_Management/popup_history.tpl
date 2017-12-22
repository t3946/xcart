{* 
$Id: popup_history.tpl 63 2012-10-30 11:56:13Z skot $ 
vim: set ts=2 sw=2 sts=2 et:
*}
{capture name="order_cahges_history"}
  {foreach from=$history item=rec}
    {include file="modules/Advanced_Order_Management/event_message.tpl" record=$rec}
  {/foreach}
{/capture}
{include file="customer/help/popup_info.tpl" pre=$smarty.capture.order_cahges_history popup_title=$lng.lbl_aom_show_history|default:"&nbsp;"}

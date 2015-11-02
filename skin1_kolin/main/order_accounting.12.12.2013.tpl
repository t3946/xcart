{*
$Id: order_accounting.tpl, v 1.0.0 2010/03/25 17:29:59 random Exp $
vim: set ts=2 sw=2 sts=2 et:
*}
{capture name=dialog}
<form action="order.php" method="post" name="accountingform">
<input type="hidden" name="mode" value="accounting_apply" />
<input type="hidden" name="orderid" value="{$order.orderid}" />

{include file="main/order_accounting_table.tpl" order=$order static=$static}

{if !$static}
<br />
<input type="submit" value="{$lng.lbl_update}" />
{/if}
</form>
{/capture}
{include file="dialog.tpl" title=$lng.lbl_accounting content=$smarty.capture.dialog extra='width="100%"'}

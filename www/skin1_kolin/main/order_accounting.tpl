{*
$Id: order_accounting.tpl, v 1.0.0 2010/03/25 17:29:59 random Exp $
vim: set ts=2 sw=2 sts=2 et:
*}
{include file="main/subheader.tpl" title="Accounting"}
<a name="accounting"></a>

<form action="order.php" method="post" name="accountingform">

{* <input type="hidden" name="mode" value="accounting_apply" id="mode_accounting_page" /> *}
<input type="hidden" name="mode" value="" id="mode_accounting_page" />

<input type="hidden" name="certain_mid" value="" id="certain_mid" />
<input type="hidden" name="certain_invoice_number" value="" id="certain_invoice_number" />

<input type="hidden" name="orderid" value="{$order.orderid}" />

{include file="main/order_accounting_table_new.tpl" order=$order static=$static}

</form>

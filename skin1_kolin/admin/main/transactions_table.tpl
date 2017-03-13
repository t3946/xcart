<table width="100%">
    <tr>
        <td width="12%"><B>Type</B></td>
        <td width="10%"><B>Date</B></td>
        <td width="15%"><B>Name</B></td>
        <td width="15%"><B>Transaction</B></td>
        <td align="center" width="15%"><B>Status</B></td>
        <td width="15%"><B>Amount</B></td>
        <td width="*%"><B>Log</B></td>
    </tr>
    {foreach from=$order_transactions item=v key=k}
        {include file="admin/main/transaction_log_row.tpl"}
    {/foreach}

</table>
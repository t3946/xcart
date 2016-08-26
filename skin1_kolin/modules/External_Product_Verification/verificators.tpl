<h1 style="text-align: center">{$lng.list_of_verificators}</h1>
{capture name=dialog}
<table width="100%">
    <tr>
        <td colspan="7">
            
        </td>
    </tr>
    <tr class="TableHead">
        <td width="10">Login</td>
        <td width="100">Full Name</td>
        <td width="10">Current batches</td>
        <td width="10">Batches completed</td>
        <td width="10">Batches paid</td>
        <td width="100" align="center">Average 1 product time spent on all batches</td>
    </tr>
    {if ($aCustomers)}
        {foreach from=$aCustomers item=oCustomer}
            <tr>
                <td>{if $oCustomer->getCustomerModifyLink()}<a href="{$oCustomer->getCustomerModifyLink()}" target="_blank">{/if}{$oCustomer->getCustomerLogin()}</a></td>
                <td>{if $oCustomer->getCustomerURL()}<a target="_blank" href="{$oCustomer->getCustomerURL()}">{/if}{$oCustomer->getCustomerFullName()}{if $oCustomer->getCustomerURL()}</a>{/if}</td>
                <td align="center"><a target="_blank" href="/admin/operators_batches.php?operator={$oCustomer->getCustomerLogin()}">{$oCustomer->getAmazonBatchesInProgressCount()}</a></td>
                <td align="center"><a target="_blank" href="/admin/operators_batches.php?operator={$oCustomer->getCustomerLogin()}">{$oCustomer->getAmazonBatchesCompletedCount()}</a></td>
                <td align="center"><a target="_blank" href="/admin/operators_batches.php?operator={$oCustomer->getCustomerLogin()}">{$oCustomer->getAmazonBatchesPaidCount()}</a></td>
                <td align="center">{$oCustomer->getAmazonBatchesAverageSpeed()} sec.</td>
            </tr>
        {/foreach}
    {/if}
</table>
{/capture}

{include file="dialog.tpl" title='Operators' content=$smarty.capture.dialog extra='width="100%"'}
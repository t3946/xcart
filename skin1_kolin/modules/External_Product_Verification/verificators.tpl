<link rel="stylesheet" href="{$SkinDir}/css/semantic/semantic.css">
<link rel="stylesheet" href="{$SkinDir}/verificator/css/main.css"/>
<script src="{$SkinDir}/js/semantic/components/form.min.js" type="text/javascript"></script>

<h1 style="text-align: center">{$lng.list_of_verificators}</h1>
{capture name=dialog}
    <div id="batches-filter" class="ui buttons left floated">
        <button data-status="all" class="ui left button {if $active=='all'}active{/if}">All</button>
        <button data-status="Y" class="ui button {if $active == 'Y'}active{/if}">Active</button>
        <button data-status="N" class="ui button {if $active == 'N'}active{/if}">Inactive</button>
    </div>
<table width="100%">
    <tr>
        <td colspan="7">
            
        </td>
    </tr>
    <tr class="TableHead">
        <td width="10">Login / Edit ver profile</td>
        <td width="100">Full Name / Link to Upwork</td>
        <td width="10">Current batches</td>
        <td width="10">Completed batches</td>
        <td width="10">Paid batches</td>
        <td width="100" align="center">Average time spent per product</td>
    </tr>
    {if ($aCustomers)}
        {foreach from=$aCustomers item=oCustomer}
            {assign var=aBatchesInProgress value=$oCustomer->getAmazonBatches('in progress')}
            <tr>
                <td>{if $oCustomer->getCustomerModifyLink()}<a href="{$oCustomer->getCustomerModifyLink()}" target="_blank">{/if}{$oCustomer->getCustomerLogin()}</a></td>
                <td>{if $oCustomer->getCustomerURL()}<a target="_blank" href="{$oCustomer->getCustomerURL()}">{/if}{$oCustomer->getCustomerFullName()}{if $oCustomer->getCustomerURL()}</a>{/if}</td>
                <td align="center">
                    {if $aBatchesInProgress}
                        {foreach from=$aBatchesInProgress item=oBatchInProgress name=batchInProgress}
                        <a target="_blank" href="/admin/operators_batches.php?operator={$oCustomer->getCustomerLogin()}&batch_status=in_progress">{$smarty.foreach.batchInProgress.iteration} ({$oBatchInProgress->getProductsInBatchCompletedCount()}/{$oBatchInProgress->getBatchAmount()})</a>
                        <br/>
                        {/foreach}
                    {else}
                    <a target="_blank" href="/admin/operators_batches.php?operator={$oCustomer->getCustomerLogin()}&batch_status=in_progress">0</a>
                    {/if}
                </td>
                <td align="center"><a target="_blank" href="/admin/operators_batches.php?operator={$oCustomer->getCustomerLogin()}&batch_status=completed">{$oCustomer->getAmazonBatchesCompletedCount()}</a></td>
                <td align="center"><a target="_blank" href="/admin/operators_batches.php?operator={$oCustomer->getCustomerLogin()}&batch_status=paid">{$oCustomer->getAmazonBatchesPaidCount()}</a></td>
                <td align="center">{$oCustomer->getAmazonBatchesAverageSpeed()} sec.</td>
            </tr>
        {/foreach}
    {/if}
</table>
{/capture}

{include file="dialog.tpl" title='Verificators' content=$smarty.capture.dialog extra='width="100%"'}

<script>
    {literal}
    $(document).ready(function () {
        $('#batches-filter > button').on('click', '', function () {
            location.href = "operators.php?active=" + $(this).data('status');
        });
    });
    {/literal}
</script>
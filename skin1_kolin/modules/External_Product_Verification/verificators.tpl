<link rel="stylesheet" href="{$SkinDir}/css/semantic/semantic.css">
<link rel="stylesheet" href="{$SkinDir}/verificator/css/main.css"/>
<script src="{$SkinDir}/js/semantic/components/form.min.js" type="text/javascript"></script>

<h1 style="text-align: center">{$lng.list_of_verificators}</h1>
{capture name=dialog}
    <div id="batches-filter" class="ui buttons left floated">
        <button data-status="all" class="ui left button {if $active=='all'}active{/if}">All</button>
        <button data-status="Y" class="ui button {if $active == 'Y'}active{/if}">Active</button>
        <button data-status="N" class="ui button {if $active == 'N'}active{/if}">Inactive</button>
        <button data-status="B" class="ui button {if $active == 'B'}active{/if}">Blocked</button>
    </div>
    <table width="100%" id="table_verificators" cellpadding="3" cellspacing="1">
        <tr>
            <td colspan="7">

            </td>
        </tr>
        <tr class="TableHead">
            <td width="100">Full Name / Link to Upwork</td>
            <td width="10">Login / Edit ver profile</td>
            <td width="10">Current batches</td>
            <td width="10">Completed batches</td>
            <td width="10">Paid batches</td>
            <td width="100" align="center">Average time spent per product</td>
        </tr>
        {if ($aCustomers)}
            {foreach from=$aCustomers item=oCustomer}
                {assign var=aBatchesInProgress value=$oCustomer->getAmazonBatches('in progress')}
                <tr>
                    <td data-customer-id="{$oCustomer->getCustomerLogin()}">
                        {if $oCustomer->getCustomerURL()}
                        <a target="_blank"
                           href="{$oCustomer->getCustomerURL()}">{/if}{$oCustomer->getCustomerFullName()}{if $oCustomer->getCustomerURL()}</a>{/if}
                        {if $oCustomer->isAmazonAccountSuspended()}
                            <span style="white-space: nowrap;">
                                <a class="verificator_status" style="color:red;" href="#">Blocked</a>
                            </span>
                        {/if}
                    </td>
                    <td>{if $oCustomer->getCustomerModifyLink()}<a href="{$oCustomer->getCustomerModifyLink()}"
                                                                   target="_blank">{/if}{$oCustomer->getCustomerLogin()}</a>
                    </td>
                    <td align="center">
                        {if $aBatchesInProgress}
                            {foreach from=$aBatchesInProgress item=oBatchInProgress name=batchInProgress}
                                <a target="_blank"
                                   href="/admin/operators_batches.php?operator={$oCustomer->getCustomerLogin()}&batch_status=in_progress">{$oBatchInProgress->getBatchNumber()}{if $oBatchInProgress->isTest()}T{/if}
                                    ({$oBatchInProgress->getProductsInBatchCompletedCount()}
                                    /{$oBatchInProgress->getBatchAmount()})</a>
                                <br/>
                            {/foreach}
                        {else}
                            <a target="_blank"
                               href="/admin/operators_batches.php?operator={$oCustomer->getCustomerLogin()}&batch_status=in_progress">0</a>
                        {/if}
                    </td>
                    <td align="center"><a target="_blank"
                                          href="/admin/operators_batches.php?operator={$oCustomer->getCustomerLogin()}&batch_status=completed">{$oCustomer->getAmazonBatchesCompletedCount()}</a>
                    </td>
                    <td align="center"><a target="_blank"
                                          href="/admin/operators_batches.php?operator={$oCustomer->getCustomerLogin()}&batch_status=paid">{$oCustomer->getAmazonBatchesPaidCount()}</a>
                    </td>
                    <td align="center">{$oCustomer->getAmazonBatchesAverageSpeed()} sec.</td>
                </tr>
            {/foreach}
        {/if}
    </table>
{/capture}

{capture name=verification_results}
    {include file="modules/External_Product_Verification/verification_results.tpl"}
{/capture}

{include file="dialog.tpl" title='Verificators' content=$smarty.capture.dialog extra='width="100%"'}
<br/>
{include file="dialog.tpl" title="Verification results: `$foundRows`" content=$smarty.capture.verification_results extra='width="100%"'}

<script>
    {literal}
    function getSelectStatusHTML() {
        var select_html = $('<select class="change_user_status_select"><option value="blocked">Blocked</option><option value="unblocked">Unblocked</option></select>' + '&nbsp;<button class="ui button button_save_status"><i class="save icon"></i>Ok</button>');
        return select_html;
    }
    $(document).ready(function () {
        $('#batches-filter > button').on('click', '', function () {
            location.href = "operators.php?active=" + $(this).data('status');
        });

        $('#table_verificators').on('click', 'a.verificator_status', function () {
            var select_html = getSelectStatusHTML();
            $(this).parent().html(select_html);
            return false;
        }).on('click', '.button_save_status', function () {
            var clickbutton = $(this);
            var new_status = clickbutton.siblings('select.change_user_status_select').val();
            var customer_id = clickbutton.parent().parent().data('customer-id');
            $(this).addClass('loading');
            $.post('ajax_admin.php', {
                        user_status_id: new_status,
                        customer_id: customer_id,
                        ajax_action: 'change_verificator_status'
                    },
                    function (data) {
                        clickbutton.removeClass('loading');
                        clickbutton.parent().empty();
                    }, 'json');
        })
    });
    {/literal}
</script>
<link rel="stylesheet" href="{$SkinDir}/css/semantic/semantic.css">
<link rel="stylesheet" href="{$SkinDir}/verificator/css/main.css"/>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js" type="text/javascript"></script>
<script src="{$SkinDir}/js/semantic/components/progress.min.js" type="text/javascript"></script>
<script src="{$SkinDir}/js/semantic/components/modal.min.js" type="text/javascript"></script>
<script src="{$SkinDir}/js/semantic/components/transition.min.js" type="text/javascript"></script>
<script src="{$SkinDir}/js/semantic/components/dimmer.min.js" type="text/javascript"></script>


<h1 style="text-align: center">{$lng.txt_verificator_batches}</h1>
{capture name=dialog}
    <div id="batches-filter" class="ui buttons left floated">
        <button data-status="all" class="ui left button {if !$batch_status}active{/if}">All</button>
        <button data-status="in_progress" class="ui button {if $batch_status == 'in progress'}active{/if}">In progress
        </button>
        <button data-status="completed" class="ui button {if $batch_status == 'completed'}active{/if}">Completed
        </button>
        <button data-status="paid" class="ui button {if $batch_status == 'paid'}active{/if}">Paid</button>
    </div>
    <div class="ui buttons right floated">
        <button id="add_batch_button" class="ui icon button left right">
            <i class="icon plus"></i> Add batch
        </button>
    </div>

    <table width="100%" id="operators_batches_table">
        <tr>
            <td colspan="7">

            </td>
        </tr>
        <tr class="TableHead">
            <td width="10">Batch #</td>
            <td width="10">Batch number</td>
            <td width="10">Start date</td>
            <td width="10">Batch products processed</td>
            <td width="10">Average 1 product time spent</td>
            <td width="100" align="center">Status</td>
        </tr>
        {assign var=aBatches value=$oCustomer->getAmazonBatches($batch_status)}
        {if ($aBatches)}
            {foreach from=$aBatches item=oCurrentBatch}
                <tr data-batch-id="{$oCurrentBatch->getBatchId()}">
                    <td align="center">{$oCurrentBatch->getBatchLogin()}_{$oCurrentBatch->getBatchNumber()}
                        _{$oCurrentBatch->getBatchAmount()}</td>
                    <td align="center">{$oCurrentBatch->getBatchNumber()}</td>
                    {assign var=oStartDate value=$oCurrentBatch->getStartDate()}
                    <td align="center">{$oStartDate->format('d-M-Y H:i')}</td>
                    <td align="left">
                        <div class="ui indicating progress" data-value="{$oCurrentBatch->getProductsInBatchCompletedCount()}" data-total="{$oCurrentBatch->getBatchAmount()}" id="example5">
                            <div class="bar">
                                <div class="progress"></div>
                            </div>
                            <div class="label">{$oCurrentBatch->getProductsInBatchCompletedCount()}/{$oCurrentBatch->getBatchAmount()}</div>
                        </div>
                        </td>
                    <td align="center">{$oCurrentBatch->getAverageVerifySpeed()} sec.</td>
                    <td align="center"><a data-status="{$oCurrentBatch->getBatchStatus()}" class="batch_status_link" href="#">{$oCurrentBatch->getBatchStatus()}</a></td>
                </tr>
            {/foreach}
        {else}
            {$lng.txt_verificator_batches_not_found}
        {/if}
    </table>
    <div class="ui inverted dimmer transition hidden segment dimmable">
    </div>
{/capture}

{include file="dialog.tpl" title=$lng.txt_verificator_batches content=$smarty.capture.dialog extra='width="100%"'}

<div class="ui small test modal">
    <div class="header">
        Add new batch
    </div>
    <div class="content">
        <div class="ui form">
            <h4 class="ui dividing header">Enter batch amount</h4>

            <div class="ui input focus">
                <input data-login="{$oCustomer->getCustomerLogin()}" autocomplite="off" id="batch_amount" value="" />
            </div>
        </div>
    </div>
    <div class="actions">
        <div class="ui negative button">
            Cancel
        </div>
        <div class="ui positive right labeled icon button">
            Add
            <i class="checkmark icon"></i>
        </div>
    </div>
</div>

{literal}
<script>
    function getSelectStatusHTML(selected_status) {
        var select_html = $('<select class="change_batch_status_select">{/literal}{foreach from=$batch_statuses item=s_batch_status}<option value="{$s_batch_status}">{$s_batch_status}</option>{/foreach}{literal}  </select>' + '&nbsp;<button class="ui button button_save_status"><i class="save icon"></i>Ok</button>');
        return select_html;
    }

    $(document).ready(function () {
        $('#batches-filter > button').on('click', '', function () {
            location.href = "operators_batches.php?operator={/literal}{$oCustomer->getCustomerLogin()}{literal}&batch_status=" + $(this).data('status');
        });
        $('.indicating.progress').progress();
        $('#add_batch_button').on('click','',function() {
            $('.ui.modal').modal({
                onApprove: function () {
                    var batch_input = $('#batch_amount');
                    $('.ui.segment.dimmable').dimmer('show');
                    $.post('ajax_admin.php',{
                                batch_amount: batch_input.val(),
                                login: batch_input.data('login'),
                                ajax_action: 'add_new_batch'
                            },
                            function (data) {
                                location.reload();
                            }, 'json');
                }
            }).modal('show');
        });
        $('#operators_batches_table').delegate('a.batch_status_link', 'click', function () {
            var cur_status = $(this).data('status'),
                    select_html = getSelectStatusHTML(cur_status);
            $(this).parent().html(select_html).find('.change_batch_status_select').val(cur_status);
            return false;
        }).delegate('.button_save_status', 'click', function () {
            var clickbutton = $(this);
            var new_status = clickbutton.siblings('select.change_batch_status_select').val();
            var batch_id = clickbutton.parent().parent().data('batch-id');
            $(this).addClass('loading');
            $.post('ajax_admin.php', {
                        verify_status_id: new_status,
                        batch_id: batch_id,
                        ajax_action: 'change_verify_batch_status'
                    },
                    function (data) {
                        clickbutton.removeClass('loading');
                        clickbutton.parent().html($('<a data-status="'+new_status+'" href="#" class="batch_status_link">'+new_status+'</a>'));
                    }, 'json');
        })
    });
</script>
{/literal}
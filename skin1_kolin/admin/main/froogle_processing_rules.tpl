<link rel="stylesheet" href="{$SkinDir}/css/semantic/semantic.css">
<script src="{$SkinDir}/js/semantic/components/form.min.js" type="text/javascript"></script>
<br>
<br>
{capture name=dialog_processing}
    <table width="100%" id="processing_rules_table">
        <tr>
            <td colspan="3">

            </td>
        </tr>
        <tr class="TableHead">
            <td width="100">Issue GMC ID</td>
            <td width="*">Name</td>
            <td width="160">Processing</td>
        </tr>
        {if $aProcessingRules}
            {foreach from=$aProcessingRules item=aProcessingRule}
                <tr data-rule-id="{$aProcessingRule->getIssueId()}">
                    <td>{$aProcessingRule->getIssueGMCId()}</td>
                    <td>{$aProcessingRule->getIssueName()}</td>
                    <td align="center"><a data-status="{$aProcessingRule->getIssueProcessing()}" class="issue_processing" href="#">{$aProcessingRule->getIssueProcessing()}</a>
                    </td>
                </tr>
            {/foreach}
        {/if}
    </table>
{/capture}
{include file="dialog.tpl" title='Quality Issues Processing Rules' content=$smarty.capture.dialog_processing extra='width="100%"'}
<script>
    {literal}
    function getSelectStatusHTML(selected_status) {
        return $('<select class="change_status_select">{/literal}{foreach from=$statuses item=s_status}<option value="{$s_status}">{$s_status}</option>{/foreach}{literal}  </select>' + '&nbsp;<button class="ui button button_save_status"><i class="save icon"></i>Ok</button>');
    }
    $(document).ready(function () {
        $('#processing_rules_table').delegate('a.issue_processing', 'click', function () {
            var cur_status = $(this).data('status'),
                    select_html = getSelectStatusHTML(cur_status);
            $(this).parent().html(select_html).find('.change_status_select').val(cur_status);
            return false;
        }).delegate('.button_save_status', 'click', function () {
            var clickbutton = $(this),
            new_status = clickbutton.siblings('select.change_status_select').val(),
            rule_id = clickbutton.parent().parent().data('rule-id');
            $(this).addClass('loading');
            $.post('ajax_admin.php', {
                        status_id: new_status,
                        rule_id: rule_id,
                        ajax_action: 'change_processing_rules'
                    },
                    function (data) {
                        clickbutton.removeClass('loading');
                        clickbutton.parent().html($('<a data-status="' + new_status + '" href="#" class="issue_processing">' + new_status + '</a>'));
                    }, 'json');
        });
    });
    {/literal}
</script>
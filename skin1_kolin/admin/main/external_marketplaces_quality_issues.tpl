<br>
<br>
{if $main=='external_marketplaces_quality_issues'}
    {include file="admin/main/froogle_processing_rules.tpl" is_edit='N'}
{elseif $main=='external_marketplaces_quality_issues_view'}
    {capture name=dialog_processing}
        { include file="customer/main/navigation.tpl" }

        { include file="customer/main/navigation.tpl" }
    {/capture}
    {include file="dialog.tpl" title=$oIssueProcessingRule->getIssueName() content=$smarty.capture.dialog_processing extra='width="100%"'}
{/if}
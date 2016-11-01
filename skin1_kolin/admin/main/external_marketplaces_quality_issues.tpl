<br>
<br>
{if $main=='external_marketplaces_quality_issues'}
    {capture name=dialog_processing_search}
        <form method="GET" name="search_issue" target="_blank">
            <table cellpadding="3" cellspacing="1" width="100%">
                <tr>
                    <td width="100">
                        <input name="search" type="text"/>
                    </td>
                    <td><input type="submit" value="Search"/></td>
                </tr>
            </table>
        </form>
    {/capture}
    {include file="dialog.tpl" title='Search' content=$smarty.capture.dialog_processing_search extra='width="100%"'}
    <br>
    <br>
    {include file="admin/main/froogle_processing_rules.tpl" is_edit='N'}
{elseif $main=='external_marketplaces_quality_issues_view'}

    {capture name=dialog_processing}
        {include file="customer/main/navigation.tpl"}
        <form method="POST">
            <table width="100%" id="processing_rules_table">
                <tr>
                    <td colspan="3">

                    </td>
                </tr>
                <tr class="TableHead">
                    <td width="100">SKU</td>
                    <td width="*">Product</td>
                    <td width="400">Issue details</td>
                    <td width="160">Action</td>
                </tr>
                {foreach from=$aImpactedProducts item=oImpactedProduct}
                    {assign var=oProduct value=$oImpactedProduct->getProductEntity()}
                    {assign var=oIssueEntity value=$oImpactedProduct->getIssueEntity()}
                    <tr {cycle values=', class="TableSubHead"'}>
                        <td><a target="_blank" href="{$oProduct->getProductModifyURL()}">{$oProduct->getSKU()}</a></td>
                        <td><a target="_blank"
                               href="{$oProduct->getProductFrontURL()}">{$oProduct->getProductName()}</a></td>
                        <td style="word-break: break-all">{$oImpactedProduct->getIssueDataHuman()}</td>
                        <td class="action_processing_button" style="text-align: center"
                            data-issue-id="{$oImpactedProduct->getIssueId()}"
                            data-product-id="{$oImpactedProduct->getProductId()}">
                            {if $oIssueEntity->getIssueProcessing() == 'manual'}
                                <input data-action="fixed" class="issue_button_processing"
                                       name="action_fix_issue[{$oImpactedProduct->getProductId()}:{$oImpactedProduct->getIssueId()}]"
                                       type="button" value="Fixed"/>
                                <br/>
                                <input data-action="exclude" class="issue_button_processing"
                                       name="action_exclude_from_gmc[{$oImpactedProduct->getProductId()}:{$oImpactedProduct->getIssueId()}]"
                                       type="button" value="Exclude from marketplace GMC"/>
                            {/if}
                        </td>
                    </tr>
                {/foreach}
            </table>
        </form>
        {include file="customer/main/navigation.tpl"}
    {/capture}
    {if ($oIssueProcessingRule && $oIssueProcessingRule->getIssueName())}
        {assign var=sIssueTitle value=$oIssueProcessingRule->getIssueName()}
    {else}
        {assign var=sIssueTitle value='Search results'}
    {/if}
    {include file="dialog.tpl" title=$sIssueTitle content=$smarty.capture.dialog_processing extra='width="100%"'}
    <script type="text/javascript">
        {literal}
        $(document).ready(function () {
            $('#processing_rules_table').find('input.issue_button_processing').click(
                    function () {
                        if (confirm("Are you sure?")) {
                            var action_value = $(this).data('action');
                            var button = $(this);
                            var issue_row = $(this).closest('td.action_processing_button');
                            issue_row.closest('tr').css('opacity',0.4);
                            $.post('ajax_admin.php', {
                                        action: action_value,
                                        issue_id: issue_row.data('issue-id'),
                                        product_id: issue_row.data('product-id'),
                                        ajax_action: 'issue_processing'
                                    },
                                    function (data) {
                                        if (data.result)
                                            button.closest('tr').fadeOut(300, function () {
                                                $(this).remove();
                                            });
                                        else alert(data.error);
                                    }
                                    ,
                                    'json');
                        }
                    }
            )
        });
        {/literal}
    </script>
{/if}
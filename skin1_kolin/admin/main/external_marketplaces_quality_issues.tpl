<br>
<br>
{if $main=='external_marketplaces_quality_issues'}
    {capture name=dialog_processing_search}
        <form method="GET" name="search_issue" target="_blank">
            <table cellpadding="3" cellspacing="1" width="100%">
                <tr>
                    <td width="100">
                        <input name="search" type="text" />
                    </td>
                    <td> <input type="submit" value="Search"/></td>
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
            <tr>
                <td><a target="_blank" href="{$oProduct->getProductModifyURL()}">{$oProduct->getSKU()}</a></td>
                <td><a target="_blank" href="{$oProduct->getProductFrontURL()}">{$oProduct->getProductName()}</a></td>
                <td style="word-break: break-all">{$oImpactedProduct->getIssueData()}<br/>{$oImpactedProduct->getIssueDestination()}</td>
                <td style="text-align: center">
                    {if $oIssueEntity->getIssueProcessing() == 'manual'}
                        <input name="action_fix_issue[{$oImpactedProduct->getProductId()}:{$oImpactedProduct->getIssueId()}]" type="submit" value="Fixed"/>
                        <br/><input name="action_exclude_from_gmc[{$oImpactedProduct->getProductId()}:{$oImpactedProduct->getIssueId()}]" type="submit" value="Exclude from marketplace GMC"/>
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
{/if}
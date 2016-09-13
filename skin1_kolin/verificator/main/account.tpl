{include file="page_title.tpl" title=$lng.lbl_welcome_to_the_verificator_zone}

{capture name=dialog1}
    <h3>{$lng.txt_personal_verificator_area}</h3>
    <p align="justify">
        {$lng.txt_verificator_promotion_note}
    </p>
    <table width="100%" cellspacing="1" cellpadding="3">
        <tr>
            <td colspan="2">
                <table cellspacing="0" class="SubHeaderGrey">
                    <tr>
                        <td class="SubHeaderGrey">{$lng.txt_verificator_information}</td>
                    </tr>
                    <tr>
                        <td class="SubHeaderGreyLine"><img alt="" class="Spc" src="{$SkinDir}/images/spacer.gif"></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td width="30%">
                <b>Username:</b>
            </td>
            <td>
                {$oCustomer->getCustomerLogin()}
            </td>
        </tr>
        <tr class="TableSubHead">
            <td>
                <b>Full Name:</b>
            </td>
            <td>
                {$oCustomer->getCustomerFullName()}
            </td>
        </tr>
        <tr>
            <td>
                <b>Date and time when the last product was processed:</b>
            </td>
            <td>
                {assign var=oLastDate value=$oCustomer->getAmazonLastVerifyDate()}
                {if ($oLastDate)}
                    {$oLastDate->format('d-M-Y H:i')}
                {/if}
            </td>
        </tr>
        <tr class="TableSubHead">
            <td>
                <b>Total number of products processed including 'Match', 'Not sure', and 'Does not match':</b>
            </td>
            <td>
                {$oCustomer->getAmazonProductProcessedCount()}
            </td>
        </tr>
        <tr>
            <td>
                <b>Total number of 'Not sure' products processed:</b>
            </td>
            <td>
                {$oCustomer->getAmazonProductNotSureCount()}
            </td>
        </tr>
    </table>
    <br/>
    <br/>
    {if ($aPreviousBatches)}
        {foreach from=$aPreviousBatches item=oPreviousBatch}
            {if $oPreviousBatch->isTest() && $oPreviousBatch->isTestFailed()}
                {assign var=bAccountBlocked value="Y"}
            {/if}
        {/foreach}
    {/if}
    {if $bAccountBlocked && $bAccountBlocked == 'Y'}
        <div style="font-size: 1.5em; text-align: center; color:red; font-weight: bold;">
            You've made too many mistakes on control products. Therefore your account has been suspended. <br>Please contact the manager.
        </div>
    {else}
    <table width="100%">
        <tr>
            <td colspan="5">
                <table cellspacing="0" class="SubHeaderGrey">
                    <tr>
                        <td class="SubHeaderGrey">Current batches</td>
                    </tr>
                    <tr>
                        <td class="SubHeaderGreyLine"><img alt="" class="Spc" src="{$SkinDir}/images/spacer.gif"></td>
                    </tr>
                </table>
            </td>
        </tr>
        {if ($aCurrentBatches)}
            <tr class="TableHead">
                <td width="10">Batch #</td>
                <td width="60" nowrap="nowrap" align="center">BATCH SIZE<br>(# OF PRODUCTS)</td>
                <td width="100" align="center">Products processed</td>
                <td width="100" align="center">BATCH STATUS</td>
                <td width="200" align="center">BATCH PRODUCT VERIFICATION LINK</td>
            </tr>
            {foreach from=$aCurrentBatches item=oCurrentBatch}
                <tr>
                    <td align="center">{$oCurrentBatch->getBatchNumber()}</td>
                    <td align="center">{$oCurrentBatch->getBatchAmount()}</td>
                    <td align="center">{$oCurrentBatch->getProductsInBatchCompletedCount()}</td>
                    <td align="center">{$oCurrentBatch->getBatchStatus()}</td>
                    <td align="center"><a class="verification_link" target="_blank" href="{$oCurrentBatch->getBatchVerifyLink()}">continue the processing of batch ID:
                            {$oCurrentBatch->getBatchLogin()}_{$oCurrentBatch->getBatchNumber()}
                            _{$oCurrentBatch->getBatchAmount()}</a></td>
                </tr>
            {/foreach}
        {else}
            <tr>
                <td align="center" colspan="5">
                    {$lng.no_current_in_progress_batches}
                </td>
            </tr>
        {/if}


    </table>
    {/if}
    <br/>
    <br/>
    <table width="100%">
        <tr>
            <td colspan="8">
                <table cellspacing="0" class="SubHeaderGrey">
                    <tr>
                        <td class="SubHeaderGrey">Previous batches</td>
                    </tr>
                    <tr>
                        <td class="SubHeaderGreyLine"><img alt="" class="Spc" src="{$SkinDir}/images/spacer.gif"></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr class="TableHead">
            <td nowrap="nowrap" width="10">Batch #</td>
            <td width="100" nowrap="nowrap" align="center">BATCH SIZE</td>
            <td width="100" align="center">STARTING DATE</td>
            <td width="100" align="center">Match</td>
            <td width="100" align="center">DOES NOT MATCH</td>
            <td width="100" align="center">Not sure</td>
            <td width="100" align="center">PER PRODUCT TIME</td>
            <td width="100" align="center">BATCH STATUS</td>
        </tr>
        {if ($aPreviousBatches)}
            {foreach from=$aPreviousBatches item=oPreviousBatch}
                {assign var=oStartDate value=$oPreviousBatch->getStartDate()}
                <tr>
                    <td align="center">{$oPreviousBatch->getBatchNumber()}</td>
                    <td align="center">{$oPreviousBatch->getBatchAmount()}</td>
                    <td align="center">{$oStartDate->format('d-M-Y H:i')}</td>
                    <td align="center">{$oPreviousBatch->getProductsInBatchMatchedCount()}</td>
                    <td align="center">{$oPreviousBatch->getProductsInBatchNotMatchedCount()}</td>
                    <td align="center">{$oPreviousBatch->getProductsInBatchNotSureCount()}</td>
                    <td align="center">{$oPreviousBatch->getAverageVerifySpeed()} sec.</td>
                    <td align="center">{$oPreviousBatch->getBatchStatus()}</td>
                </tr>
            {/foreach}
        {/if}
    </table>
{/capture}

{include file="dialog.tpl" title='Verificator panel' content=$smarty.capture.dialog1 extra='width="100%"'}
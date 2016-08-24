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
                        <td class="SubHeaderGrey">Verificator information</td>
                    </tr>
                    <tr>
                        <td class="SubHeaderGreyLine"><img alt="" class="Spc" src="{$SkinDir}/images/spacer.gif"></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td width="30%">
                <b>Login:</b>
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
                <b>Last produсt verified time:</b>
            </td>
            <td>
                {assign var=oLastDate value=$oCustomer->getAmazonLastVerifyDate()}
                {$oLastDate->format('d-M-Y H:i')}
            </td>
        </tr>
        <tr class="TableSubHead">
            <td>
                <b>Products processed (matched, not matched and not sure):</b>
            </td>
            <td>
                {$oCustomer->getAmazonProductProcessedCount()}
            </td>
        </tr>
        <tr>
            <td>
                <b>Products (not sure):</b>
            </td>
            <td>
                {$oCustomer->getAmazonProductNotSureCount()}
            </td>
        </tr>
    </table>
    <br/>
    <br/>
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
                <td width="60" nowrap="nowrap" align="center">Products amount</td>
                <td width="100" align="center">Products processed</td>
                <td width="100" align="center">Status</td>
                <td width="200" align="center"></td>
            </tr>
            {foreach from=$aCurrentBatches item=oCurrentBatch}
                <tr>
                    <td align="center">{$oCurrentBatch->getBatchNumber()}</td>
                    <td align="center">{$oCurrentBatch->getBatchAmount()}</td>
                    <td align="center">{$oCurrentBatch->getProductsInBatchCompletedCount()}</td>
                    <td align="center">{$oCurrentBatch->getBatchStatus()}</td>
                    <td align="center"><a target="_blank" href="{$oCurrentBatch->getBatchVerifyLink()}">continue batch #
                            [{$oCurrentBatch->getBatchLogin()}_{$oCurrentBatch->getBatchNumber()}
                            _{$oCurrentBatch->getBatchAmount()}] processing</a></td>
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
            <td width="100" nowrap="nowrap" align="center">Batch amount</td>
            <td width="100" align="center">Start date</td>
            <td width="100" align="center">Match</td>
            <td width="100" align="center">Not match</td>
            <td width="100" align="center">Not sure</td>
            <td width="100" align="center">1 item speed</td>
            <td width="100" align="center">Status</td>
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

{include file="dialog.tpl" title='General information' content=$smarty.capture.dialog1 extra='width="100%"'}
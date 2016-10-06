
<table width="100%" id="table_verificators" cellpadding="3" cellspacing="1">
    <tr>
        <td colspan="7">

        </td>
    </tr>
    <tr class="TableHead">
        <td rowspan="2" width="100">SKU</td>
        <td style="width: 250px; white-space: nowrap;" rowspan="2" width="10">Product name</td>
        <td style="width: 130px; white-space: nowrap;" rowspan="2" width="10">Verificator</td>
        <td rowspan="2" width="10">Date and time</td>
        <td colspan="5" width="10">Verification questions</td>
        <td rowspan="2" width="100">Conclusion</td>
    </tr>
    <tr class="TableHead">
        <td>ASIN</td>
        <td>Image</td>
        <td>Name</td>
        <td>Desc</td>
        <td>Pack qty</td>
    </tr>
    {if $aVerifiactionResults}
        {foreach from=$aVerifiactionResults item=oVerificationResult}
            {assign var=oProduct value=$oVerificationResult->getProductEntity()}
            {assign var=aVerificatorResults value=$oVerificationResult->getVerificatorsResults($batch_id)}
            {if $aVerificatorResults}
                {foreach from=$aVerificatorResults item=oVerificatorResult name=ver_rows}
                    {if $aVerificatorResults|@count > 1}
                        {assign var=oCustomer value=$oVerificatorResult->getCustomerEntity()}
                        {assign var=oVerifyDate value=$oVerificatorResult->getValueAsDateTime()}
                        {if $smarty.foreach.ver_rows.iteration == 1}
                            {cycle assign=classVar name=$type values=", class='TableSubHead'"}
                        {/if}
                        <tr {$classVar}>
                            {if $smarty.foreach.ver_rows.iteration == 1}
                                <td rowspan="{$aVerificatorResults|@count}"><a href="{$oProduct->getProductModifyURL()}"
                                                                               target="_blank">{$oProduct->getSKU()}</a></td>
                                <td rowspan="{$aVerificatorResults|@count}"><a target="_blank"
                                                                               href="{$oProduct->getProductFrontURL()}">{$oProduct->getProductName()}</a>
                                </td>
                            {/if}
                            <td>{if $oCustomer->getCustomerURL()}
                                <a target="_blank"
                                   href="{$oCustomer->getCustomerURL()}">{/if}{$oCustomer->getCustomerFullName()}{if $oCustomer->getCustomerURL()}</a>{/if}
                                <br/>{if $oCustomer->getCustomerModifyLink()}<a
                                href="{$oCustomer->getCustomerModifyLink()}"
                                target="_blank">{/if}{$oCustomer->getCustomerLogin()}</td>
                            <td align="center">{$oVerifyDate->format('d-M-Y<\b\r>H:i')}</td>
                            <td align="center"
                                    {if $aVerificatorResults[0]->getAsin() != $aVerificatorResults[1]->getAsin()}
                                        class="question_not_same"
                                    {/if}
                            >{$oVerificatorResult->getAsin()}</td>
                            <td align="center" {if $aVerificatorResults[0]->getProductImage() != $aVerificatorResults[1]->getProductImage()}
                                class="question_not_same"
                                    {/if}>{$oVerificatorResult->getProductImage()}</td>
                            <td align="center" {if $aVerificatorResults[0]->getProductName() != $aVerificatorResults[1]->getProductName()}
                                class="question_not_same"
                                    {/if}>{$oVerificatorResult->getProductName()}</td>
                            <td align="center" {if $aVerificatorResults[0]->getProductDescription() != $aVerificatorResults[1]->getProductDescription()}
                                class="question_not_same"
                                    {/if}>{$oVerificatorResult->getProductDescription()}</td>
                            <td align="center" {if $aVerificatorResults[0]->getQtyOnAmazon() != $aVerificatorResults[1]->getQtyOnAmazon() || $aVerificatorResults[0]->getQtyOnOurWebSite() != $aVerificatorResults[1]->getQtyOnOurWebSite()}
                                class="question_not_same"
                                    {/if}>{$oVerificatorResult->getQtyOnAmazon()}
                                <br/>{$oVerificatorResult->getQtyOnOurWebSite()}</td>
                            <td align="center"
                                    {if $aVerificatorResults[0]->getAction() != $aVerificatorResults[1]->getAction()}
                                class="action_not_same"
                                    {/if}>
                                <b>{$oVerificatorResult->getActionDisplayName()}</b>
                                {if $oVerificatorResult->getComment()}
                                    <span class="verificator_comments_icon"><img src="{$ImagesDir}/comment.png" /></span>
                                {/if}
                            </td>
                        </tr>
                    {/if}
                {/foreach}
            {/if}
        {/foreach}
    {/if}
</table>
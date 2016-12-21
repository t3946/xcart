<link rel="stylesheet" href="{$SkinDir}/css/semantic/semantic.css">
<script src="{$SkinDir}/js/semantic/components/popup.min.js" type="text/javascript"></script>
<script src="{$SkinDir}/js/semantic/components/transition.min.js" type="text/javascript"></script>
<table width="100%" id="table_verificators" cellpadding="3" cellspacing="1">
    <tr>
        <td colspan="11">

        </td>
    </tr>
    <tr class="TableHead">
        <td rowspan="2" width="100">SKU</td>
        <td style="width: 250px; white-space: nowrap;" rowspan="2" width="10">Product name</td>
        <td style="width: 130px; white-space: nowrap;" rowspan="2" width="10">Verificator</td>
        <td style="width: 70px; white-space: nowrap;" rowspan="2" width="10">Date and time</td>
        <td colspan="5" width="10">Verification questions</td>
        <td style="width: 160px; white-space: nowrap;" rowspan="2" colspan="2" width="100">Conclusion</td>
    </tr>
    <tr class="TableHead head_buttons">
        <td>
            <button data-filter="asin" class="ui button grey toggle {if "asin"|in_array:$filter}active{/if}">ASIN
            </button>
        </td>
        <td>
            <button data-filter="image" class="ui button grey toggle {if "image"|in_array:$filter}active{/if}">Image
            </button>
        </td>
        <td>
            <button data-filter="name" class="ui button grey toggle {if "name"|in_array:$filter}active{/if}">Name
            </button>
        </td>
        <td>
            <button data-filter="desc" class="ui button grey toggle {if "desc"|in_array:$filter}active{/if}">Desc
            </button>
        </td>
        <td>
            <button data-filter="qty" class="ui button grey toggle {if "qty"|in_array:$filter}active{/if}">Pack qty
            </button>
        </td>
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
                        {assign var=Asin value=$oVerificatorResult->getAsin()}
                        {assign var=Batch value=$aVerificatorResults[0]->getBatchEntity()}
                        {if $smarty.foreach.ver_rows.iteration == 1}
                            {cycle assign=classVar name=$type values=", class='TableSubHead'"}
                        {/if}
                        <tr data-arbitrage-asin-batch="{$oVerificatorResult->getBatchId()}" data-login="{$oCustomer->getCustomerLogin()}" data-productid="{$oProduct->getProductId()}" {$classVar}>
                            {if $smarty.foreach.ver_rows.iteration == 1}
                                <td rowspan="{$aVerificatorResults|@count}">
                                    <p><a target="_blank" href="{$oVerificatorResult->getSearchByUPCOnAmazonLink()}">Amazon
                                            UPC search</a></p>
                                    <a href="{$oProduct->getProductModifyURL()}"
                                       target="_blank">{$oProduct->getSKU()}</a>
                                </td>
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
                            {assign var=notsameASIN value=false}
                            {if (is_array($aVerificatorResults[0]->getAsin()))}
                                {if (!in_array($aVerificatorResults[1]->getAsin(), $aVerificatorResults[0]->getAsin()))}
                                    {assign var=notsameASIN value=true}
                                {/if}
                            {elseif ($aVerificatorResults[0]->getAsin() != $aVerificatorResults[1]->getAsin()) }
                                {assign var=notsameASIN value=true}
                            {/if}
                            {if $aVerificatorResults[0]->getProductImage() != $aVerificatorResults[1]->getProductImage()}
                                {assign var=notsameImage value=true}
                            {else}
                                {assign var=notsameImage value=false}
                            {/if}
                            {if $aVerificatorResults[0]->getProductName() != $aVerificatorResults[1]->getProductName()}
                                {assign var=notsameName value=true}
                            {else}
                                {assign var=notsameName value=false}
                            {/if}
                            {if $aVerificatorResults[0]->getProductDescription() != $aVerificatorResults[1]->getProductDescription()}
                                {assign var=notsameDesc value=true}
                            {else}
                                {assign var=notsameDesc value=false}
                            {/if}
                            {if $aVerificatorResults[0]->getQtyOnAmazon() != $aVerificatorResults[1]->getQtyOnAmazon() || $aVerificatorResults[0]->getQtyOnOurWebSite() != $aVerificatorResults[1]->getQtyOnOurWebSite()}
                                {assign var=notsameQty value=true}
                            {else}
                                {assign var=notsameQty value=false}
                            {/if}
                            {if ((is_null($Batch->getBatchId()) && $aVerificatorResults[1]->checkAnswerCorrect($oVerificationResult) < 0) ||
                            ($Batch->getBatchId() && $Batch->isTest() === false && $aVerificatorResults[0]->getAction() != $aVerificatorResults[1]->getAction()))}
                                {assign var=notsameAction value=true}
                            {/if}

                            {if ($Batch->getBatchId() && $Batch->isTest() === false && $aVerificatorResults[0]->getAsin() != $aVerificatorResults[1]->getAsin())}
                                {assign var=arbitrageAction value=true}
                            {else}
                                {assign var=arbitrageAction value=false}
                            {/if}

                            <td align="center" {if $notsameASIN}class="question_not_same"{/if}>
                                {if (is_array($Asin))}
                                    {foreach from=$Asin item=sAsin}
                                        <a target="_blank" href="https://www.amazon.com/dp/{$sAsin}/">{$sAsin}</a>
                                    {/foreach}
                                {else}
                                    <a target="_blank" href="{$oVerificatorResult->getAmazonProductLink()}">{$Asin}</a>
                                {/if}

                            </td>


                            <td align="center" {if $notsameImage}class="question_not_same"{/if}>
                                {if $notsameImage && (empty($filter) || "image"|in_array:$filter)}
                                    <button data-type="image" class="ui button arbitrage_switch_button toggle">
                                {/if}
                                    {$oVerificatorResult->getProductImage()}
                                {if $notsameImage && (empty($filter) || "image"|in_array:$filter)}
                                    </button>
                                {/if}
                            </td>
                            <td align="center" {if $notsameName}class="question_not_same"{/if}>
                                {if $notsameName && (empty($filter) || "name"|in_array:$filter)}
                                    <button data-type="name" class="ui button arbitrage_switch_button toggle">
                                {/if}
                                {$oVerificatorResult->getProductName()}
                                {if $notsameImage && (empty($filter) || "name"|in_array:$filter)}
                                    </button>
                                {/if}
                            </td>
                            <td align="center" {if $notsameDesc}class="question_not_same"{/if}>
                                {if $notsameDesc && (empty($filter) || "desc"|in_array:$filter)}
                                    <button data-type="desc" class="ui button arbitrage_switch_button toggle">
                                {/if}
                                {$oVerificatorResult->getProductDescription()}
                                {if $notsameDesc && (empty($filter) || "desc"|in_array:$filter)}
                                    </button>
                                {/if}
                            </td>
                            <td align="center" {if $notsameQty}class="question_not_same"{/if}>
                                {if $notsameQty && (empty($filter) || "qty"|in_array:$filter)}
                                    <button data-type="qty" class="ui button arbitrage_switch_button toggle">
                                {/if}
                                    {$oVerificatorResult->getQtyOnAmazon()}<br/>{$oVerificatorResult->getQtyOnOurWebSite()}
                                {if $notsameQty && (empty($filter) || "qty"|in_array:$filter)}
                                    </button>
                                {/if}
                            </td>
                            <td  align="center" class="conclusion_action {if $notsameAction}action_not_same{/if}">
                                {if $arbitrageAction && (empty($filter) || "asin"|in_array:$filter)}
                                    <button data-type="action" class="ui button arbitrage_action toggle">
                                {/if}
                                    <b>{$oVerificatorResult->getActionDisplayName()}</b>
                                {if $arbitrageAction && (empty($filter) || "asin"|in_array:$filter)}
                                    </button>
                                {/if}
                                {if $oVerificatorResult->getComment()}
                                    <span data-html="{$oVerificatorResult->getComment()}"
                                          class="verificator_comments_icon"><img src="{$ImagesDir}/comment.png"/></span>
                                {/if}

                            </td>
                            {if $smarty.foreach.ver_rows.iteration == 1}
                                    <td rowspan="{$aVerificatorResults|@count}">
                                        {if $arbitrageAction && (empty($filter) || "asin"|in_array:$filter)}
                                            <p><input id="asin_arbitrage" placeholder="ASIN" size="10" type="text" /></p>
                                        {/if}
                                        {if $notsameQty && (empty($filter) || "qty"|in_array:$filter)}
                                            <p><input id="amz_qty_arbitrage" placeholder="Amazon qty" size="10" type="text" /></p>
                                            <p><input id="our_qty_arbitrage" placeholder="Qty on our site" size="10" type="text" /></p>
                                         {/if}
                                        <p style="text-align: center">
                                            <button class="ui button arbitrage_asin_button">Submit</button>
                                        </p>
                                    </td>
                            {/if}
                        </tr>
                    {/if}
                {/foreach}
            {/if}
        {/foreach}
    {/if}
</table>
<script type="text/javascript">
    {literal}
    $('.verificator_comments_icon').popup({on: 'click', inline: true}).click(function () {
        return false;
    });
    $('.arbitration_radio').change(function () {
        var tr = $(this).closest('tr');
        var iProduct = tr.data('productid');
        var iBatchId = $(this).data('arbitrage-confirmation-batch');
        var sLogin = $(this).data('login');

        if (confirm('Are You Sure?')) {
            var trpair = tr.siblings('tr[data-productid=' + iProduct + ']').andSelf().css({opacity: 0.4});
            $.post('ajax_admin.php', {
                        product_id: iProduct,
                        batch_id: iBatchId,
                        login: sLogin,
                        ajax_action: 'verification_arbitrage_confirmation'
                    },
                    function (data) {
                        if (data && data.result) {
                            trpair.fadeOut(function () {
                                $(this).remove()
                            });
                        }
                    }, 'json');
        }
    });
    $('.arbitrage_asin_button').click(function () {
        const err_validation_message = 'Required fields are not selected!';
        var asin_batch_id, asin_login,
        arrCheckType = [], errors = [], arbitrageArr = [],
        error = false;

        var tr = $(this).closest('tr');
        var iProduct = tr.data('productid');
        var trpair = tr.siblings('tr[data-productid=' + iProduct + ']').andSelf();
        var asin_arbitrage = trpair.find('input#asin_arbitrage').val();
        var amazon_qty_arbitrage = trpair.find('input#amz_qty_arbitrage').val();
        var our_qty_arbitrage = trpair.find('input#our_qty_arbitrage').val();



        tr.find('button.arbitrage_switch_button, button.arbitrage_action').each(function() {
            arrCheckType.push($(this).data('type'));
        });

        if (arrCheckType.length > 0) {
            for (i = 0; i < arrCheckType.length; i++) {
                var anButtons = trpair.find('button[data-type='+arrCheckType[i]+'].active');
                if (!anButtons.length) {

                    if (
                        (arrCheckType[i] == 'action' && asin_arbitrage == '')||
                        (arrCheckType[i] == 'qty' && (amazon_qty_arbitrage == '' || our_qty_arbitrage == '')) ||
                        ($.inArray(arrCheckType[i], ['action', 'qty']) < 0)
                       )
                    {
                        error = true;
                        alert(err_validation_message);
                        break;
                    }
                } else {
                    var buttonactive;
                    if ($.isArray(anButtons)) {
                        buttonactive = anButtons[0];
                    } else {
                        buttonactive = anButtons;
                    }
                    if (buttonactive.length > 0) {
                        asin_batch_id = buttonactive.closest('tr').data('arbitrage-asin-batch');
                        asin_login = buttonactive.closest('tr').data('login');
                        arbitrageArr.push({
                            'action': arrCheckType[i],
                            'batch_id': asin_batch_id,
                            'login': asin_login
                        });
                    }
                }
            }

        }
        if (!error) {
            trpair.css({opacity: 0.4});
            $.post('ajax_admin.php', {
                        product_id: iProduct,
                        arbitrage: arbitrageArr,
                        asin_arbitrage: asin_arbitrage,
                        amazon_qty_arbitrage: amazon_qty_arbitrage,
                        our_qty_arbitrage: our_qty_arbitrage,
                        ajax_action: 'verification_arbitrage_full'
                    },
                    function (data) {
                        if (data && data.result) {
                            trpair.fadeOut(function () {
                                $(this).remove()
                            });
                        }
                        trpair.css({opacity: 1});
                    }, 'json');
        }
    });

    $('#table_verificators button.button.arbitrage_switch_button, #table_verificators button.button.arbitrage_action').click(function () {
        if (!$(this).hasClass('active')) {
            var tr = $(this).closest('tr');
            tr.siblings('tr[data-productid=' + tr.data('productid') + ']').find('button[data-type='+$(this).data('type')+']').removeClass('active');
            $(this).addClass('active');
        } else {
            $(this).removeClass('active');
        }
    });

    $('#table_verificators .head_buttons button.button').click(function () {
        $(this).toggleClass('active');
        var key = 'filter[]';
        var kvp = document.location.search.substr(1).split('&');
        var kvp2 = [];
        var i = kvp.length;
        var x;
        while (i--) {
            x = kvp[i].split('=');
            if (x[0] != key) {
                kvp2[kvp2.length] = x.join('=');
            }
        }

        $('#table_verificators button.active').each(function (val) {
            {
                kvp2[kvp2.length] = [key, $(this).data('filter')].join('=');
            }
        });

        document.location.search = kvp2.join('&');
    });
    {/literal}
</script>

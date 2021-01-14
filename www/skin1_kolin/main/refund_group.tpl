{assign var=refundGroup value=$oOrderGroup->getRefundsModel()}
{assign var=disable_refund value=$oOrderGroup->dc_status|in_array:['C', 'L', 'DA', 'G', 'S', 'Z']}
{if $refundGroup}
    <tr class="refund-distr-totals-line">
        <td style="font-size: 10px;" colspan="11">
            {if $oOrderGroup->cb_status === 'AP'}Adjust{else}Refund{/if} # {$oOrder->getOrderNumber()}-REF
        </td>
    </tr>

    <tr class="refund-distr-totals-line">
        <td style="font-size: 12px;">
            {if $oOrderGroup->cb_status === 'AP'}Adjust for{else}{$lng.lbl_refund_for}{/if} {$oOrderGroup} {$lng.lbl_items}</td>
        <td style="font-size: 12px;">{$oOrderGroup->manufacturer->code}</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td align="right" nowrap="nowrap" style="font-size: 12px;">
            {if $refundGroup->total_net != 0}({/if}{include file="currency2.tpl" value=$refundGroup->total_net}{if $refundGroup->total_net != 0}){/if}
        </td>
        <td align="right" nowrap="nowrap" style="font-size: 12px;">
            {if $refundGroup->total_gst != 0}({/if}{include file="currency2.tpl" value=$refundGroup->total_gst hide_zero='Y'}{if $refundGroup->total_gst != 0}){/if}
        </td>
        <td align="right" nowrap="nowrap" style="font-size: 12px;">
            {if $refundGroup->total_gross != 0}({/if}{include file="currency2.tpl" value=$refundGroup->total_gross}{if $refundGroup->total_gross != 0}){/if}
        </td>
        <td>&nbsp;</td>
    </tr>

    {foreach from=$refundGroup->products item=ref_product}
        {assign var=product value=$ref_product->product}
        <tr class="refund-distr-values-line{cycle values=", TableSubHead" name="cycle_`$mid`"}">
            <td class="refund-prod-title">
                <a href="{$product->getAbsoluteUrl(true)}" title="{$product->getFrontendName()}" target="_blank">
                    {$product->getFrontendName()}
                </a>
                ({if $ref_product->getRestockingFee() eq '0'}{$lng.lbl_no_restocking_fee}{else}{$lng.lbl_x_percents_restocking_fee|substitute:"X":$ref_product->getRestockingFee()}{/if})

                {if $product->options->count()}
                    {foreach from=$product->options item=option key=key}
                        {if $option->active}
                            <br/>
                            {assign var="p_option" value=$option->option->title}
                            {assign var="orderDetail" value=$ref_product->getOrderDetail()}
                            {$p_option}
                            <select name="items[{$ref_product->itemid}][classid_optionid][{$option->id}]">
                                <option>No options selected</option>
                                {foreach from=$option->variants item=variant}
                                    <option value="{$variant->id}"
                                            {if $orderDetail && $orderDetail->product_options && $orderDetail->product_options[$p_option] === $variant->variant->name}
                                                selected="selected"
                                            {/if}
                                    >{$variant}</option>
                                {/foreach}
                            </select>
                        {/if}
                    {/foreach}
                {/if}
            </td>
            <td>
                {if $current_membership_flag ne 'FS'}
                    <a href="{$product->getAdminUrl()}" title="" target="_blank">{$product->productcode}</a>
                {else}
                    {$product->productcode}
                {/if}
            </td>
            <td align="right">
                {if !$static}
                    <input type="text" size="8" name="ref_products[{$mid}][{$ref_product->itemid}][ref_price]"
                           value="{$ref_product->ref_price|price_format}"/>
                {else}
                    {include file="currency2.tpl" value=$ref_product->ref_price|price_format}
                {/if}
                <input type="hidden" name="ref_products[{$mid}][{$ref_product->itemid}][productid]"
                       value="{$product->productid}"/>
            </td>
            <td align="right" nowrap="nowrap">
                {if $ref_product->ref_qty ne 0}({/if}{if !$static}
                    <input type="text" size="5" name="ref_products[{$mid}][{$ref_product->itemid}][ref_qty]"
                           value="{$ref_product->ref_qty}" />{else}{$ref_product->ref_qty}{/if}{if $ref_product->ref_qty ne 0}){/if}
            </td>
            <td align="right">&nbsp;</td>
            <td align="right">&nbsp;</td>
            <td align="right">&nbsp;</td>
            <td align="right" nowrap="nowrap">
                {if $ref_product->ref_qty ne 0 && $ref_product->ref_price ne 0}({/if}{include file="currency2.tpl" value=$ref_product->getSubtotal()}{if $ref_product->ref_qty ne 0 && $ref_product->ref_price ne 0}){/if}
            </td>
            <td align="right" nowrap="nowrap">
            </td>
            <td align="right" nowrap="nowrap">
                {if $ref_product->getSubtotal() > 0}({/if}{include file="currency2.tpl" value=$ref_product->getSubtotal()}{if $ref_product->getSubtotal() > 0}){/if}
            </td>
            <td align="center">
                {if !$static}
                    <input type="checkbox" value="Y" name="ref_delete[{$mid}][{$ref_product->itemid}]"/>
                {else}
                {/if}
            </td>
        </tr>
    {/foreach}

    <tr class="refund-distr-values-line {cycle values=", TableSubHead" name="cycle_`$mid`"}">
        <td>
            {if !$refundGroup->shipping}
                {assign var=shipping value="`$lng.lbl_adjustment_to` `$refundGroup->shipping`"}
            {else}
                {assign var=shipping value="`$refundGroup->shipping`"}
            {/if}

            {if !$static}
                <input type="text" maxlength="255" name="ref_groups[{$mid}][shipping]" value="{$shipping|trademark:''}"
                       style="width: 99%;"/>
            {else}
                {$shipping}
            {/if}
        </td>
        <td colspan="6">
        </td>
        <td align="right" nowrap="nowrap">
            {if $refundGroup->ref_ship != 0}({/if}
            {if !$static}
                <input type="text" size="8" name="ref_groups[{$mid}][ref_ship]"
                       value="{$refundGroup->ref_ship|price_format}"/>
            {else}
                {$refundGroup->ref_ship|price_format}{/if}{if $refundGroup->ref_ship != 0})
            {/if}
        </td>
        <td align="right" nowrap="nowrap">
            {if $refundGroup->shipping_gst != 0}({/if}{include file="currency2.tpl" value=$refundGroup->shipping_gst hide_zero='Y'}{if $refundGroup->shipping_gst != 0}){/if}
        </td>
        <td align="right" nowrap="nowrap">
            {if $refundGroup->shipping_gross != 0}({/if}{include file="currency2.tpl" value=$refundGroup->shipping_gross}{if $refundGroup->shipping_gross != 0}){/if}
        </td>
        <td align="center">
            <input type="checkbox" value="Y" name="ref_groups[{$mid}][delete]"/>
        </td>
    </tr>

    {if $disable_refund}
        <tr>
            <td colspan="11">
                <div class="enter_on_site">
                    <div class="enter_on_site__content">
                        {$lng.refund_confirmation_text}
                    </div>
                    <div style="text-align: center; padding: 0 0 10px 0">
                        <button onclick="$('#refund_disclaimer').hide();$('#refund_reason_{$mid}').attr('disabled', false).siblings('input').attr('disabled', false);return false;">
                            Yes
                        </button>
                        <button onclick="$('#refund_disclaimer').show();$('#refund_reason_{$mid}').attr('disabled', true).siblings('input').attr('disabled', true);return false;">
                            No
                        </button>
                    </div>
                    <div id="refund_disclaimer" class="enter_on_site__content" style="padding: 0 0 10px 0; color: red; display: none;">
                        <b>{$lng.refund_not_confirmed_text|default:'refund_confirmation_no_text'}</b>
                    </div>
                </div>
            </td>
        </tr>
    {/if}

    <tr id="refund_group_{$mid}"
            {if $oOrderGroup->cb_status|in_array:["3","V"]}
                style="background: #F4CCCC;"
            {elseif $oOrderGroup->cb_status|in_array:["H","R"]}
                style="background: #fff2cc;"
            {else}
                style="display: none;"
            {/if}>
        <td colspan="11">
            {if $oOrderGroup->cb_status|in_array:["3","V"]}
                <b>Refund reason:</b>
                <br/>
                <textarea {if $disable_refund}disabled{/if} id="refund_reason_{$mid}" name="ref_groups[{$mid}][refund_reason]" cols="60" rows="2" style="width: 98%;">{$refundGroup->refund_reason|escape:"html"}</textarea>
                <input {if $disable_refund}disabled{/if} type="button"
                       value="Issue refund and Send refund notification"
                       onclick="if ($('#refund_reason_{$mid}').val() !== ''){ldelim} $('#ref_notify_button_clicked').val('Update_C2B_status_and_Send_refund_notification');
                               $('#ordereditform_mode').val('ref_notify'); $('#ordereditform_mid').val('{$mid}'); this.form.submit(); {rdelim} else {ldelim} func_refund_reason_message(); {rdelim}"/>
            {elseif $oOrderGroup->cb_status|in_array:["H","R"]}
                {if $refundGroup->refund_reason}
                    <b>Refund reason:</b>
                    {$refundGroup->refund_reason|escape:"html"}
                    <br/>
                    <br/>
                {/if}
                <input type="button"
                       value="Send refund notification"
                       onclick="$('#ref_notify_button_clicked').val('Send_refund_notification');
                                $('#ordereditform_mode').val('ref_notify');
                                $('#ordereditform_mid').val('{$mid}');
                                this.form.submit();"/>
            {/if}

            {$lng.lbl_ref_notify_status|cat:":"}

            {if $refundGroup->notify_status === 'S'}
                <span style="color: green; font-weight: bold">{$lng.lbl_sent}</span>
            {else}
                <span style="color: green;">{$lng.lbl_queued}</span>
            {/if}
        </td>
    </tr>
    <tr>
        <td colspan="11">
            <hr/>
        </td>
    </tr>
{/if}

{assign var=refundGroup value=$oOrderGroup->getRefundsModel()}
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
    <a href="{$product->getAbsoluteUrl(true)}" title="{$product->getFrontendName()}" target="_blank">{$product->getFrontendName()}</a>
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
      <input type="text" size="8" name="ref_products[{$mid}][{$ref_product->itemid}][ref_price]" value="{$ref_product->ref_price|price_format}" />
    {else}
      {include file="currency2.tpl" value=$ref_product->ref_price|price_format}
    {/if}
      <input type="hidden" name="ref_products[{$mid}][{$ref_product->itemid}][productid]" value="{$product->productid}" />
  </td>
  <td align="right" nowrap="nowrap">
    {if $ref_product->ref_qty ne 0}({/if}{if !$static}
      <input type="text" size="5" name="ref_products[{$mid}][{$ref_product->itemid}][ref_qty]" value="{$ref_product->ref_qty}" />{else}{$ref_product->ref_qty}{/if}{if $ref_product->ref_qty ne 0}){/if}
  </td>
  <td align="right">&nbsp;</td>
  <td align="right">&nbsp;</td>
  <td align="right">&nbsp;</td>
  <td align="right" nowrap="nowrap">
    {if $ref_product->ref_qty ne 0 && $ref_product->ref_price ne 0}({/if}{include file="currency2.tpl" value=$ref_product->getSubtotal()}{if $ref_product->ref_qty ne 0 && $ref_product->ref_price ne 0}){/if}
  </td>
  <td align="right" nowrap="nowrap">
    {*{if $product.extra_data.taxes.GST.tax_value && $product.extra_data.taxes.HST.tax_value}
      {math equation="x+y" assign="gst_taxes" x=$product.extra_data.taxes.GST.tax_value y=$product.extra_data.taxes.HST.tax_value}
      {if $gst_taxes ne 0}({/if}{include file="currency2.tpl" value=$gst_taxes hide_zero='Y'}{if $gst_taxes ne 0}){/if}
    {/if}*}
  </td>
  <td align="right" nowrap="nowrap">
    {if $ref_product->getSubtotal() > 0}({/if}{include file="currency2.tpl" value=$ref_product->getSubtotal()}{if $ref_product->getSubtotal() > 0}){/if}
  </td>
  <td align="center">
    {if !$static}
      <input type="checkbox" value="Y" name="ref_delete[{$mid}][{$ref_product->itemid}]" />
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
      <input type="text" maxlength="255" name="ref_groups[{$mid}][shipping]" value="{$shipping|trademark:''}" style="width: 99%;" />
    {else}
      {$shipping}
    {/if}
  </td>
  <td colspan="6">
  </td>
  <td align="right" nowrap="nowrap">
    {if $refundGroup->ref_ship != 0}({/if}
    {if !$static}
      <input type="text" size="8" name="ref_groups[{$mid}][ref_ship]" value="{$refundGroup->ref_ship|price_format}"/>
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

<tr id="refund_group_{$mid}" {if $oOrderGroup->cb_status eq "3" || $oOrderGroup->cb_status eq "V"}style="background: #F4CCCC;"{elseif $oOrderGroup->cb_status eq "H" || $oOrderGroup->cb_status eq "R"}style="background: #fff2cc;"{else}style="display: none;"{/if}>
  <td colspan="11">

  {if $oOrderGroup->cb_status eq "3" || $oOrderGroup->cb_status eq "V"}
    <B>Refund reason:</B><br />
    <textarea id="refund_reason_{$mid}"  name="ref_groups[{$mid}][refund_reason]" cols="60" rows="2" style="width: 98%;">{$refundGroup->refund_reason|escape:"html"}</textarea>
    <input type="button" value="Issue refund and Send refund notification"
           onclick="if ($('#refund_reason_{$mid}').val() != ''){ldelim} $('#ref_notify_button_clicked').val('Update_C2B_status_and_Send_refund_notification');
           $('#ordereditform_mode').val('ref_notify'); $('#ordereditform_mid').val('{$mid}'); this.form.submit(); {rdelim} else {ldelim} func_refund_reason_message(); {rdelim}" />&nbsp;&nbsp;
  {elseif $oOrderGroup->cb_status eq "H" || $oOrderGroup->cb_status eq "R"}
    {if $refundGroup->refund_reason != ''}
      <B>Refund reason:</B> {$refundGroup->refund_reason|escape:"html"}
      <br />
      <br />
    {/if}
    <input type="button" value="Send refund notification" onclick="$('#ref_notify_button_clicked').val('Send_refund_notification');
    $('#ordereditform_mode').val('ref_notify'); $('#ordereditform_mid').val('{$mid}'); this.form.submit();" />&nbsp;&nbsp;
  {/if}

    {$lng.lbl_ref_notify_status|cat:":"}
    {if $refundGroup->notify_status === 'S'}
      <span style="color: green; font-weight: bold">{$lng.lbl_sent}</span>
    {else}
      <span style="color: green;">{$lng.lbl_queued}</span>
    {/if}
  </td>
</tr>

<tr><td colspan="11"><hr /></td></tr>
{/if}

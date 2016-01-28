{*
$Id: refund_groups.tpl, v 1.0.0 2011/11/07 17:00:00 kate Exp $
vim: set ts=2 sw=2 sts=2 et:
*}

{if $order.refund_groups[$mid]}
<tr class="refund-distr-totals-line"><td style="font-size: 10px;" colspan="11">
Refund # {$order.order_prefix}{$order.orderid}-REF
</td></tr>

<tr class="refund-distr-totals-line">
  <td style="font-size: 12px;">
  {$lng.lbl_refund_for} {$group.group_name} {$lng.lbl_items}</td>
  <td style="font-size: 12px;">{$group.code}</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td align="right" nowrap="nowrap" style="font-size: 12px;">
    {if $order.refund_groups[$mid].total_net ne 0}({/if}{include file="currency2.tpl" value=$order.refund_groups[$mid].total_net}{if $order.refund_groups[$mid].total_net ne 0}){/if}
  </td>
  <td align="right" nowrap="nowrap" style="font-size: 12px;">
    {if $order.refund_groups[$mid].total_gst ne 0}({/if}{include file="currency2.tpl" value=$order.refund_groups[$mid].total_gst hide_zero='Y'}{if $order.refund_groups[$mid].total_gst ne 0}){/if}
  </td>
{*
  <td align="right" nowrap="nowrap" style="font-size: 12px;">
    {if $order.refund_groups[$mid].total_pst ne 0}({/if}{include file="currency2.tpl" value=$order.refund_groups[$mid].total_pst hide_zero='Y'}{if $order.refund_groups[$mid].total_pst ne 0}){/if}
  </td>
*}
  <td align="right" nowrap="nowrap" style="font-size: 12px;">
    {if $order.refund_groups[$mid].total_gross ne 0}({/if}{include file="currency2.tpl" value=$order.refund_groups[$mid].total_gross}{if $order.refund_groups[$mid].total_gross ne 0}){/if}
  </td>
  <td>&nbsp;</td>
</tr>

{foreach from=$order.refund_groups[$mid].products item=product key=prod_num}
<tr class="refund-distr-values-line{cycle values=", TableSubHead" name="cycle_`$mid`"}">
  <td class="refund-prod-title">
    <a href="{$product.links.customer}&cat={$cats[$product.productid]}" title="" target="_blank">{$product.product}</a>
    ({if $product.fee eq '0'}{$lng.lbl_no_restocking_fee}{else}{$lng.lbl_x_percents_restocking_fee|substitute:"X":$product.fee}{/if})

{* --------------------- *}

{if $order.shipping_groups.$mid.products ne ""}
 {foreach from=$order.shipping_groups.$mid.products item=product_main key=key_main}

    {if $product_main.orig_product_classes ne "" && $product_main.itemid eq $product.itemid}
      {foreach from=$product_main.orig_product_classes item=item key=key_s}
        {if $item.options ne ""}
          <br /> {$item.classtext}
          <select name="items[{$product_main.itemid}][classid_optionid][{$item.classid}]" disabled="disabled">
          {foreach from=$item.options key=optionid item=option_values}
          {assign var="tmp_optionid_key" value=`$option_values.classid`}
          {assign var="tmp_optionid" value=`$product_main.product_options[$tmp_optionid_key].optionid`}
            <option value="{$optionid}"
              {if $tmp_optionid eq $optionid}
                selected="selected"
              {/if}
            >{$option_values.option_name}</option>
          {/foreach}
          </select>
        {elseif $product_main.product_options_txt ne ""}
          <br />Options: {$product_main.product_options_txt}
        {/if}
      {/foreach}
    {/if}

 {/foreach}
{/if}
{* --------------------- *}

  </td>
  <td>
    {if $current_membership_flag ne 'FS'}
      <a href="{$product.links.admin}" title="" target="_blank">{$product.productcode}</a>
    {else}
      {$product.productcode}
    {/if}
  </td>
  <td align="right">
    {if !$static}
{*      <input type="text" size="8" name="ref_products[{$mid}][{$product.productid}][ref_price]" value="{$product.ref_price|price_format}" /> *}
      <input type="text" size="8" name="ref_products[{$mid}][{$product.itemid}][ref_price]" value="{$product.ref_price|price_format}" />
    {else}
      {include file="currency2.tpl" value=$product.ref_price|price_format}
    {/if}

      <input type="hidden" name="ref_products[{$mid}][{$product.itemid}][productid]" value="{$product.productid}" />

  </td>
  <td align="right" nowrap="nowrap">
{*    {if $product.ref_qty ne 0}({/if}{if !$static}<input type="text" size="5" name="ref_products[{$mid}][{$product.productid}][ref_qty]" value="{$product.ref_qty}" />{else}{$product.ref_qty}{/if}{if $product.ref_qty ne 0}){/if} *}
    {if $product.ref_qty ne 0}({/if}{if !$static}<input type="text" size="5" name="ref_products[{$mid}][{$product.itemid}][ref_qty]" value="{$product.ref_qty}" />{else}{$product.ref_qty}{/if}{if $product.ref_qty ne 0}){/if}

  </td>
  <td align="right">&nbsp;</td>
  <td align="right">&nbsp;</td>
  <td align="right">&nbsp;</td>
  <td align="right" nowrap="nowrap">
    {if $product.ref_qty ne 0 && $product.ref_price ne 0}({/if}{include file="currency2.tpl" value=$product.ref_price*$product.ref_qty}{if $product.ref_qty ne 0 && $product.ref_price ne 0}){/if}
  </td>
  <td align="right" nowrap="nowrap">
    {if $product.extra_data.taxes.GST.tax_value && $product.extra_data.taxes.HST.tax_value}
      {math equation="x+y" assign="gst_taxes" x=$product.extra_data.taxes.GST.tax_value y=$product.extra_data.taxes.HST.tax_value}
      {if $gst_taxes ne 0}({/if}{include file="currency2.tpl" value=$gst_taxes hide_zero='Y'}{if $gst_taxes ne 0}){/if}
    {/if}
  </td>
{*
  <td align="right" nowrap="nowrap">
    {if $product.extra_data.taxes.PST.tax_value ne 0}({/if}{include file="currency2.tpl" value=$product.extra_data.taxes.PST.tax_value hide_zero='Y'}{if $product.extra_data.taxes.PST.tax_value ne 0}){/if}
  </td>
*}
  <td align="right" nowrap="nowrap">
    {if $product.extra_data.display_subtotal ne 0}({/if}{include file="currency2.tpl" value=$product.extra_data.display_subtotal}{if $product.extra_data.display_subtotal ne 0}){/if}
  </td>
  <td align="center">
    {if !$static}
{*      <input type="checkbox" value="Y" name="ref_delete[{$mid}][{$product.productid}]" /> *}
      <input type="checkbox" value="Y" name="ref_delete[{$mid}][{$product.itemid}]" />
    {else}
      &nbsp;
    {/if}
  </td>
</tr>
{/foreach}

<tr class="refund-distr-values-line {cycle values=", TableSubHead" name="cycle_`$mid`"}">
  <td>
    {if $order.refund_groups[$mid].shipping eq ""}
      {assign var=shipping value="`$lng.lbl_adjustment_to` `$order.refund_groups[$mid].shipping`"}
    {else}
      {assign var=shipping value="`$order.refund_groups[$mid].shipping`"}
    {/if}

    {if !$static}
      <input type="text" maxlength="255" name="ref_groups[{$mid}][shipping]" value="{$shipping|trademark:''}" style="width: 99%;" />
    {else}
      {$shipping}
    {/if}
  </td>
  <td colspan="6">
    {*if $order.refund_groups[$mid].tracking}
      {foreach from=$order.refund_groups[$mid].tracking item=t}
        {if $t.tracknum ne ""}
          <a href="{$tracking_links[$t.linkid].link|substitute:"tracknum":$t.tracknum}" target="_blank">{$tracking_links[$t.linkid].shipping}: {$t.tracknum}</a>
        {else}
          {$tracking_links[$t.linkid].shipping}: {$tracking_links[$t.linkid].link}
        {/if}
        <br />
      {/foreach}
    {else}
      &nbsp;
    {/if*}
    &nbsp;
  </td>
  <td align="right" nowrap="nowrap">
    {if $order.refund_groups[$mid].ref_ship ne 0}({/if}{if !$static}<input type="text" size="8" name="ref_groups[{$mid}][ref_ship]" value="{$order.refund_groups[$mid].ref_ship|price_format}" />{else}{$order.refund_groups[$mid].ref_ship|price_format}{/if}{if $order.refund_groups[$mid].ref_ship ne 0}){/if}
  </td>
  <td align="right" nowrap="nowrap">
    {if $order.refund_groups[$mid].shipping_gst ne 0}({/if}{include file="currency2.tpl" value=$order.refund_groups[$mid].shipping_gst hide_zero='Y'}{if $order.refund_groups[$mid].shipping_gst ne 0}){/if}
  </td>
{*
  <td align="right" nowrap="nowrap">
    {if $order.refund_groups[$mid].shipping_pst ne 0}({/if}{include file="currency2.tpl" value=$order.refund_groups[$mid].shipping_pst hide_zero='Y'}{if $order.refund_groups[$mid].shipping_pst ne 0}){/if}
  </td>
*}
  <td align="right" nowrap="nowrap">
    {if $order.refund_groups[$mid].shipping_gross ne 0}({/if}{include file="currency2.tpl" value=$order.refund_groups[$mid].shipping_gross}{if $order.refund_groups[$mid].shipping_gross ne 0}){/if}
  </td>
  <td>
<input type="checkbox" value="Y" name="ref_groups[{$mid}][delete]" />
  </td>
</tr>

<tr id="refund_group_{$mid}" {if $group.cb_status eq "3" || $group.cb_status eq "V"}style="background: #F4CCCC;"{elseif $group.cb_status eq "H" || $group.cb_status eq "R"}style="background: #fff2cc;"{else}style="display: none;"{/if}>
  <td colspan="{$colspan}">

{*
    {if $group.cb_status ne "3" && $group.cb_status ne "V"}
    <input type="button" value="Update C2B status" onclick="javascript: $('#ref_notify_button_clicked').val('Update_C2B_status'); $('#ordereditform_mode').val('ref_notify'); $('#ordereditform_mid').val('{$mid}'); this.form.submit();" />&nbsp;&nbsp;
    {/if}
*}

  {if $group.cb_status eq "3" || $group.cb_status eq "V"}
    <input type="button" value="Update C2B status and Send refund notification" onclick="javascript: ga_onRefundClick('{$mid}'); $('#ref_notify_button_clicked').val('Update_C2B_status_and_Send_refund_notification'); $('#ordereditform_mode').val('ref_notify'); $('#ordereditform_mid').val('{$mid}'); this.form.submit();" />&nbsp;&nbsp;
  {elseif $group.cb_status eq "H" || $group.cb_status eq "R"}
    <input type="button" value="Send refund notification" onclick="javascript: $('#ref_notify_button_clicked').val('Send_refund_notification'); $('#ordereditform_mode').val('ref_notify'); $('#ordereditform_mid').val('{$mid}'); this.form.submit();" />&nbsp;&nbsp;
  {/if}

    {$lng.lbl_ref_notify_status|cat:":"}
    {if $order.refund_groups[$mid].notify_status eq 'S'}
      <span style="color: green; font-weight: bold">{$lng.lbl_sent}</span>
    {else}
      <span style="color: green;">{$lng.lbl_queued}</span>
    {/if}
  </td>
</tr>

<tr><td colspan="{$colspan}"><hr /></td></tr>
{/if}

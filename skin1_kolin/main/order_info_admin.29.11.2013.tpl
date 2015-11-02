{*
$Id: order_info_admin.tpl, v 1.0.0 2010/03/23 15:16:14 random Exp $
vim: set ts=2 sw=2 sts=2 et:
*}
<script type="text/javascript" language="JavaScript 1.2" src="{$SkinDir}/lib/jqueryui/jquery-ui.custom.min.js"></script>

{include file="main/subheader.tpl" title=$lng.lbl_order_info show_order_help_links="Y"}
{include file="change_states_js.tpl"}

<form action="order.php" method="post" name="ordereditform">
<input type="hidden" name="mode" value="order_edit_apply" id="ordereditform_mode" />
<input type="hidden" name="orderid" value="{$order.orderid}" />
<input type="hidden" name="notify_mid" value="" id="ordereditform_mid" />
<input type="hidden" name="send_email" id="send_email1" value="N" />

<table cellpadding="3" cellspacing="1" width="100%">
<tr class="TableHead">
  <td width="35%">{$lng.lbl_product}</td>
  <td width="17%">{$lng.lbl_sku}</td>
  <td width="7%">{$lng.lbl_price}</td>
  <td width="5%"><font class="Star">R</font>{$lng.lbl_qty}</td>
  <td width="5%">{$lng.lbl_back}</td>
  <td width="7%" nowrap="nowrap">ETA date<br />(mm/dd/yyyy)</td>
  <td width="7%"><font class="Star">R</font>{$lng.lbl_net}</td>
  <td width="7%">{$lng.lbl_gst}</td>
{*  <td width="7%">{$lng.lbl_pst}</td> *}
  <td width="7%">{$lng.lbl_gross}</td>
  {if !$static}<td width="5%">{$lng.lbl_remove}{else}<td>&nbsp;{/if}</td>
</tr>


<tr class="TableHead" style="BACKGROUND-COLOR: #FFD44C;">
  <td width="35%"></td>
  <td width="17%"></td>
  <td width="7%" nowrap="nowrap"><font style="font-size: 9px;">Cost to us</font></td>
  <td width="5%"></td>
  <td width="5%"></td>
  <td width="7%"></td>
  <td width="7%" nowrap="nowrap"><font style="font-size: 9px;">Cost to us</font></td>
  <td width="7%"></td>
{*  <td width="7%"></td> *}
  <td width="7%" nowrap="nowrap"><font style="font-size: 9px;">Cost to us</font></td>
  {if !$static}<td width="5%">{else}<td>{/if}</td>
</tr>

{foreach from=$order.shipping_groups item=v key=m_id}
<tr class="distributor-totals-line">
  <td><a target="_blank" style="color: green;" href="manufacturers.php?manufacturerid={$m_id}&distributor_section=3">{$v.group_name} items</a></td>
  <td>{$v.code}</td>
  <td colspan="4">
    {if $order_manufacturers[$m_id].d_link_to_order_distributors_website ne ""}
    <a style="color: #3A3AFF; font-weight: normal;" href='{$order_manufacturers[$m_id].d_link_to_order_distributors_website}' target="_blank">Order on distributor's website</a>
    {/if}
  </td>
  <td align="right">{include file="currency2.tpl" value=$v.total.net}</td>
  <td align="right">{include file="currency2.tpl" value=$v.total.gst hide_zero='Y'}</td>
{*  <td align="right">{include file="currency2.tpl" value=$v.total.pst hide_zero='Y'}</td> *}
  <td align="right">{include file="currency2.tpl" value=$v.total.gross}</td>
</tr>
{foreach from=$v.products item=product key=prod_num}
<tr{cycle values=", class='TableSubHead'" name="cycle_`$m_id`"}>
  <td>
    <a href="{$product.links.customer}&cat={$cats[$product.productid]}" title="" target="_blank">{$product.product}</a>
{* --------------------- *}
    {if $product.orig_product_classes ne ""}
      {foreach from=$product.orig_product_classes item=item key=key}
        {if $item.options ne ""}
          <br />
          <select name="items[{$product.itemid}][classid_optionid][{$item.classid}]">
          {foreach from=$item.options key=optionid item=option_values}
          {assign var="tmp_optionid_key" value=`$option_values.classid`}
          {assign var="tmp_optionid" value=`$product.product_options[$tmp_optionid_key].optionid`} 
            <option value="{$optionid}"
              {if $tmp_optionid eq $optionid} 
                selected="selected"
              {/if}
            >{$option_values.option_name}</option>
          {/foreach}
          </select>
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

    {assign var="mpn" value=`$product.mpn`}
    {if $order_manufacturers[$m_id].d_website_search_for_sku_url ne ""}
    <br />
    <a style="color: #3A3AFF;" href='{$order_manufacturers[$m_id].d_website_search_for_sku_url|replace:"---mpn---":"$mpn"}' target="_blank">{$mpn}</a>
    {/if}

  </td>
  <td align="right">{if !$static}<input type="text" size="8" name="items[{$product.itemid}][price]" value="{$product.price|price_format}" />{else}{include file="currency2.tpl" value=$product.price|price_format}{/if}

{* --- *}
<div style="BACKGROUND-COLOR: #FFD44C; color: #000000" align="right">
{include file="currency2.tpl" value=$product.cost_to_us|price_format}
</div>

{if $product.item_cost_to_us ne ""}
<div style="BACKGROUND-COLOR: #F2A3A8; color: #000000" align="right">
{if $product.item_cost_to_us ne $product.cost_to_us}
{include file="currency2.tpl" value=$product.item_cost_to_us|price_format}
{else}
Cost to us accurate
{/if}
</div>
{/if}
{* --- *}

  </td>
  <td align="right" valign="top">{if !$static}<input type="text" size="5" name="items[{$product.itemid}][amount]" value="{$product.amount}" />{else}{$product.amount}{/if}</td>
  <td align="right" valign="top">{if !$static}<input type="text" size="5" name="items[{$product.itemid}][back]" value="{$product.back}" />{else}{$product.back}{/if}</td>

<script type="text/javascript" language="JavaScript 1.2">
<!--
{literal}
  $(function() {
    $("#eta_date_mm_dd_yyyy_{/literal}{$product.itemid}{literal}").datepicker();
  });
{/literal}
-->
</script>

  <input type="hidden" name="items[{$product.itemid}][productid]" value="{$product.productid}" />
  <td align="right" valign="top">{if !$static}<input id="eta_date_mm_dd_yyyy_{$product.itemid}" type="text" size="9" style="width: 98%;" name="items[{$product.itemid}][eta_date_mm_dd_yyyy]" value="{$product.eta_date_mm_dd_yyyy}" />{else}{$product.eta_date_mm_dd_yyyy}{/if}</td>

  <td align="right">{include file="currency2.tpl" value=$product.price*$product.amount}

{* --- *}
<div style="BACKGROUND-COLOR: #FFD44C; color: #000000" align="right">
{include file="currency2.tpl" value=$product.cost_to_us*$product.amount}
</div>
{* --- *}
  </td>
  <td align="right">{include file="currency2.tpl" value=$product.extra_data.taxes.GST.tax_value+$product.extra_data.taxes.HST.tax_value hide_zero='Y'}</td>
{*  <td align="right">{include file="currency2.tpl" value=$product.extra_data.taxes.PST.tax_value hide_zero='Y'}</td> *}
  <td align="right">{include file="currency2.tpl" value=$product.display_subtotal}

{* --- *}
<div style="BACKGROUND-COLOR: #FFD44C; color: #000000" align="right">
{include file="currency2.tpl" value=$product.cost_to_us*$product.amount}
</div>
{* --- *}

  </td>
  <td align="center">{if !$static}<input type="checkbox" value="Y" name="items[{$product.itemid}][delete]" />{else}&nbsp;{/if}</td>
</tr>
{/foreach}
<tr{cycle values=", class='TableSubHead'" name="cycle_`$m_id`"}>
  <td>{if !$static}<input type="text" maxlength="255" name="groups[{$m_id}][shipping]" value="{$v.shipping|trademark:''}" style="width: 99%;" />{else}{$v.shipping}{/if}</td>
  <td colspan="5">
    {if $v.tracking}
      {foreach from=$v.tracking item=t}

        {if $t.tracknum ne ""}

          <div id="tracknum_{$m_id}_{$t.linkid}_{$t.tracknum}">
          <a href="{$tracking_links[$t.linkid].link|substitute:"tracknum":$t.tracknum}" target="_blank">{$tracking_links[$t.linkid].shipping}: {$t.tracknum}</a>

          <a href="javascript: void(0);" onclick="javascript: $('#tracknum_val_{$m_id}_{$t.linkid}_{$t.tracknum}').val(''); $('#tracknum_{$m_id}_{$t.linkid}_{$t.tracknum}').hide();"><img src="{$ImagesDir}/minus.gif" /></a>

          <input type="hidden" name="tracknums[{$m_id}][{$t.linkid}]" value="{$t.tracknum}" id="tracknum_val_{$m_id}_{$t.linkid}_{$t.tracknum}" />
          <br />
          </div>

        {else}
          {$tracking_links[$t.linkid].shipping}: {$tracking_links[$t.linkid].link}
          <br />
        {/if}

      {/foreach}
    {else}
      &nbsp;
    {/if}
  </td>
  <td align="right">
    {if !$static}
      <input type="hidden" name="groups[{$m_id}][shipping_cost_net_orig]" value="{$v.shipping_cost.net|price_format}" />
      <input type="text" size="8" name="groups[{$m_id}][shipping_cost_net]" value="{$v.shipping_cost.net|price_format}" />
    {else}
      {$v.shipping_cost.net|price_format}
    {/if}
  </td>
  <td align="right">{include file="currency2.tpl" value=$v.shipping_cost.gst hide_zero='Y'}</td>
{*  <td align="right">{include file="currency2.tpl" value=$v.shipping_cost.pst hide_zero='Y'}</td> *}
  <td align="right">{include file="currency2.tpl" value=$v.shipping_cost.gross}</td>
  <td>&nbsp;</td>
</tr>

{* ----------------------- *}
<tr{cycle values=", class='TableSubHead'" name="cycle_`$m_id`"} style="BACKGROUND-COLOR: #FFD44C;">
  <td colspan="6">

{*
  {math equation="x/y" x=$v.actual_shipping_cost.net y=$order_manufacturers.$m_id.required_shipping_charge assign="default_required_shipping_charge"}
*}

  <select name="groups[{$m_id}][shipping_value_selectbox]" id="shipping_value_selectbox_{$m_id}" 
  onchange="javascript: {literal}
  if ($('#shipping_value_selectbox_{/literal}{$m_id}{literal}').val() == 'actual_shipping_cost'){ 
/*
    $('#actual_shipping_cost_net_{/literal}{$m_id}{literal}').val('{/literal}{$v.actual_shipping_cost.net|price_format}{literal}');

    $('#cidev_actual_shipping_cost_gross_{/literal}{$m_id}{literal}').show();
    $('#cidev_required_shipping_cost_gross_{/literal}{$m_id}{literal}').hide();

    $('#cidev_actual_shipping_cost_gst_{/literal}{$m_id}{literal}').show();
    $('#cidev_required_shipping_cost_gst_{/literal}{$m_id}{literal}').hide();

    $('#cidev_actual_shipping_cost_pst_{/literal}{$m_id}{literal}').show();
    $('#cidev_required_shipping_cost_pst_{/literal}{$m_id}{literal}').hide();
*/

  } else { 
/*
    $('#actual_shipping_cost_net_{/literal}{$m_id}{literal}').val('{/literal}{$default_required_shipping_charge|price_format}{literal}');

    $('#cidev_actual_shipping_cost_gross_{/literal}{$m_id}{literal}').hide();
    $('#cidev_required_shipping_cost_gross_{/literal}{$m_id}{literal}').show();

    $('#cidev_actual_shipping_cost_gst_{/literal}{$m_id}{literal}').hide();
    $('#cidev_required_shipping_cost_gst_{/literal}{$m_id}{literal}').show();

    $('#cidev_actual_shipping_cost_pst_{/literal}{$m_id}{literal}').hide();
    $('#cidev_required_shipping_cost_pst_{/literal}{$m_id}{literal}').show();
*/

  }
  {/literal};">
  <option value="actual_shipping_cost" {if $v.shipping_value_selectbox eq "actual_shipping_cost" || $v.shipping_value_selectbox eq ""} selected="selected"{/if}>Actual shipping cost (include drop-ship fee)</option>
  <option value="required_shipping_charge" {if $v.shipping_value_selectbox eq "required_shipping_charge"} selected="selected"{/if}>Required shipping charge from our website shipping quote</option>
  </select>

  </td>
  <td align="right">
      <input id="actual_shipping_cost_net_{$m_id}" type="text" size="8" name="groups[{$m_id}][actual_shipping_cost_net]" value="{$v.actual_shipping_cost.net|price_format}" />
  </td>

  <td align="right">
{*  {if $v.shipping_value_selectbox eq "actual_shipping_cost" || $v.shipping_value_selectbox eq ""} *}
    <span id="cidev_actual_shipping_cost_gst_{$m_id}">{include file="currency2.tpl" value=$v.actual_shipping_cost.gst hide_zero='Y'}</span>
{*  {else}
    <span id="cidev_required_shipping_cost_gst_{$m_id}">{include file="currency2.tpl" value=$v.actual_shipping_cost.gst|default:$default_required_shipping_charge hide_zero='Y'}</span>
  {/if}
*}
  </td>
{*
  <td align="right">
    <span id="cidev_actual_shipping_cost_pst_{$m_id}">{include file="currency2.tpl" value=$v.actual_shipping_cost.pst hide_zero='Y'}</span>
  </td>
*}
  <td align="right">
{*  {if $v.shipping_value_selectbox eq "actual_shipping_cost" || $v.shipping_value_selectbox eq ""} *}
    <span id="cidev_actual_shipping_cost_gross_{$m_id}">{include file="currency2.tpl" value=$v.actual_shipping_cost.gross}</span>
{*  {else}
    <span id="cidev_required_shipping_cost_gross_{$m_id}">{include file="currency2.tpl" value=$v.actual_shipping_cost.gross|default:$default_required_shipping_charge}</span>
  {/if}
*}
  </td>
  <td>&nbsp;</td>
</tr>
{* ----------------------- *}

<tr>
<td colspan="10">
<script type="text/javascript">
<!--
multirowInputSets['track_{$m_id}'] = [];
multirowInputSets['track_{$m_id}'].noCloneContent = 1;
-->
</script>
<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<td style="padding-right: 10px;"><b>{$lng.lbl_shipper}:</b></td>
	<td colspan="2"><b>{$lng.lbl_tracking_number}:</b></td>
</tr>

<tr id="track_{$m_id}_tr">
	<td id="track_{$m_id}_box_1" style="padding-right: 10px;">
	<select name="groups[{$m_id}][tracking_shipper][0]">
	<option value=""></option>
{foreach from=$tracking_links item=vvv key=linkid}
	<option value="{$linkid}">{$vvv.shipping}</option>
{/foreach}
	</select>
	</td>
	<td id="track_{$m_id}_box_2" style="padding-right: 5px;">
	<input type="text" name="groups[{$m_id}][tracking_number][0]" value="" size="40" />
	</td>
	<td width="50%">{include file="buttons/multirow_add.tpl" mark="track_`$m_id`"}</td>
</tr>

</table>

</td>
</tr>


{if $active_modules.Google_Checkout eq '' or $order.extra.goid eq ''}
<tr style="background-color: #d9ead3;">
  <td colspan="10">
    <table cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr>
      <td style="vertical-align: top; padding-right: 10px; padding-bottom: 4px;">
        <b>{$lng.lbl_cust_bus_payment_status}:</b><br />
        {include file="main/order_status.tpl" status=$v.cb_status mode="select" name="groups[`$m_id`][cb_status]" status_type="CB"}
      </td>
      <td style="vertical-align: top; padding-right: 10px; padding-bottom: 4px;">
        <b>{$lng.lbl_distr_cust_shipping_status}:</b><br />

{assign var="hide_dispatched_status" value=""}
{if $order_manufacturers[$m_id].submit_to_operator eq "through_distributor_website"}
{assign var="hide_dispatched_status" value="Y"}
{/if}

{assign var="hide_pending_availability_check_status" value="Y"}
{if $order_manufacturers[$m_id].d_availability_must_be_checked eq "Y"}
{assign var="hide_pending_availability_check_status" value=""}
{/if}

        {include file="main/order_status.tpl" status=$v.dc_status mode="select" name="groups[`$m_id`][dc_status]" status_type="DC" hide_pending_availability_check_status=$hide_pending_availability_check_status hide_dispatched_status=$hide_dispatched_status}
      </td>
      <td style="vertical-align: top; padding-right: 10px; padding-bottom: 4px;">
        <b>{$lng.lbl_bus_distr_payment_status}:</b><br />
        {include file="main/order_status.tpl" status=$v.bd_status mode="select" name="groups[`$m_id`][bd_status]" status_type="BD"}
      </td>
    </tr>
    </table>
  </td>
</tr>
{/if}


<tr><td colspan="10"><hr /></td></tr>
{include file="main/refund_group.tpl" mid=$m_id group=$order.shipping_groups[$m_id]}
{/foreach}

<tr{cycle values=", class='TableSubHead'" name="cycle_totals"}>
  <td>Total Product Price

<div style="BACKGROUND-COLOR: #FFD44C; color: #000000" align="left">
Total Product Cost to us
</div>

  </td>
  <td colspan="5">&nbsp;</td>
  <td align="right">{include file="currency2.tpl" value=$order.extra.product_total.net}

{* --- *}
<div style="BACKGROUND-COLOR: #FFD44C; color: #000000" align="right">
{include file="currency2.tpl" value=$cost_to_us_total|price_format}
</div>
{* --- *}

  </td>
  <td align="right">{include file="currency2.tpl" value=$order.extra.product_total.gst hide_zero='Y'}</td>
{*   <td align="right">{include file="currency2.tpl" value=$order.extra.product_total.pst hide_zero='Y'}</td> *}
  <td align="right">{include file="currency2.tpl" value=$order.display_subtotal}

{* --- *}
<div style="BACKGROUND-COLOR: #FFD44C; color: #000000" align="right">
{include file="currency2.tpl" value=$cost_to_us_total|price_format}
</div>
{* --- *}

  </td>
  <td>&nbsp;</td>
</tr>

<tr{cycle values=", class='TableSubHead'" name="cycle_totals"}>
  <td>{$lng.lbl_discount}</td>
  <td colspan="5">&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
{*  <td>&nbsp;</td> *}
  <td align="right">{include file="currency2.tpl" value=$order.discount hide_zero='Y'}</td>
  <td>&nbsp;</td>
</tr>

{capture name=coup_saving}
<tr{cycle values=", class='TableSubHead'" name="cycle_totals"}>
  <td>{$lng.lbl_coupon_saving}</td>
  <td colspan="5">&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
{*  <td>&nbsp;</td> *}
  <td align="right">{include file="currency2.tpl" value=$order.coupon_discount hide_zero='Y'}{if $order.coupon_discount gt 0} ({$order.coupon}){/if}</td>
  <td>&nbsp;</td>
</tr>
{/capture}

{if $order.coupon_type ne "free_ship"}
{$smarty.capture.coup_saving}
{/if}

<tr{cycle values=", class='TableSubHead'" name="cycle_totals"}>
  <td>{$lng.lbl_total_shipping_cost}</td>
  <td colspan="5">&nbsp;</td>
  <td align="right">{include file="currency2.tpl" value=$order.extra.shipping_total.net hide_zero='Y'}</td>
  <td align="right">{include file="currency2.tpl" value=$order.extra.shipping_total.gst hide_zero='Y'}</td>
{*  <td align="right">{include file="currency2.tpl" value=$order.extra.shipping_total.pst hide_zero='Y'}</td> *}
  <td align="right">{include file="currency2.tpl" value=$order.shipping_cost hide_zero='Y'}</td>
  <td>&nbsp;</td>
</tr>

{if $order.coupon and $order.coupon_type eq "free_ship"}
{$smarty.capture.coup_saving}
{/if}

<tr{cycle values=", class='TableSubHead'" name="cycle_totals"} style="font-weight: bold;">
  <td style="font-size: 12px;">{$lng.lbl_grand_total}</td>
  <td colspan="5">&nbsp;</td>
  <td align="right" style="font-size: 12px;">{include file="currency2.tpl" value=$order.extra.total.net}</td>
  <td align="right" style="font-size: 12px;">{include file="currency2.tpl" value=$order.extra.total.gst hide_zero='Y'}</td>
{*  <td align="right" style="font-size: 12px;">{include file="currency2.tpl" value=$order.extra.total.pst hide_zero='Y'}</td> *}
  <td align="right" style="font-size: 12px;">{include file="currency2.tpl" value=$order.total}</td>
  <td>&nbsp;</td>
</tr>

<tr>
  <td colspan="10">
  <hr />
{if !$static}
<script type="text/javascript">
<!--
multirowInputSets['add_to_order'] = [];
multirowInputSets['add_to_order'].noCloneContent = 1;
multirowInputSets['add_to_order'].noCloneHTMLId = 'add_to_order_box_0';
-->
</script>
{/if}
  </td>
</tr>

{if !$static}
<tr id="add_to_order_tr">
  <td id="add_to_order_box_0"><strong>{$lng.lbl_add_to_order}:</strong></td>
  <td id="add_to_order_box_1" colspan="2"><input type="text" name="add_productcode[0]" value="" size="16" style="width: 100%;" /></td>
  <td id="add_to_order_box_2"><input type="text" name="add_amount[0]" value="" size="5" /></td>
  <td colspan="6">{include file="buttons/multirow_add.tpl" mark="add_to_order"}</td>
</tr>
{/if}

</table>

<br />
<input type="submit" value="{$lng.lbl_apply_changes|escape}" />
{if $current_membership_flag ne 'FS'}
  &nbsp;&nbsp;&nbsp;&nbsp;
  <input type="button" value="{$lng.lbl_apply_changes_send_email|escape}" onclick="javascript: $('#send_email1').val('Y'); this.form.submit();" />
{/if}
<br />

</form>

<form action="order.php" method="post" name="ordereditform">
<input type="hidden" name="mode" value="order_edit_apply" />
<input type="hidden" name="orderid" value="{$order.orderid}" />
<input type="hidden" name="send_email" id="send_email2" value="N" />

<br /><br />

{include file="main/subheader.tpl" title=$lng.lbl_customer_info}

<table cellspacing="0" cellpadding="0" width="100%" border="0">
<tr>
  <td width="47%" height="25"><b>{$lng.lbl_contact_information}</b></td>
  <td width="5%">&nbsp;</td>
  <td width="47%" height="25">

{if $order.po_details}<b>{$lng.lbl_po_info}</b>

  <input type="text" name="orig_po" id="orig_po" value="{$order.orig_po|escape}" />
    {if $order.orig_po ne ""}<a target="_blank" href="{$order.orig_po}" style="color: #1F08F8;">{/if}View original PO{if $order.orig_po ne ""}</a>{/if}

{/if}
  </td>
</tr>
<tr>
  <td bgcolor="#000000" height="2"><img height="2" src="{$ImagesDir}/spacer_black.gif" width="100%" alt="" /></td>
  <td><img height="2" src="{$ImagesDir}/spacer.gif" width="1" alt="" /></td>
  <td{if $order.po_details} bgcolor="#000000" height="2"{/if}>{if $order.po_details}<img height="2" src="{$ImagesDir}/spacer_black.gif" width="100%" alt="" />{/if}</td>
</tr>
<tr>
  <td colspan="3"><img height="2" src="{$ImagesDir}/spacer.gif" width="1" alt="" /></td>
</tr>
<tr>
  <td width="47%">
  <table cellspacing="0" cellpadding="0" class="customer-info-edit">
{if $customer.default_fields.company}
<tr>
  <td>{$lng.lbl_company}:</td>
  <td width="100%">{if !$static}<input type="text" name="customer_info[company]" value="{$customer.company}" />{else}{$customer.company}{/if}</td>
</tr>
{/if}
{if $customer.default_fields.tax_number}
<tr>
  <td><b>{$lng.lbl_tax_number}:</b></td>
  <td width="100%">{if !$static}<input type="text" name="customer_info[tax_number]" value="{$customer.tax_number}" />{else}{$customer.tax_number}{/if}</td>
</tr>
{/if}
{if $customer.default_fields.title}
<tr> 
<td><b>{$lng.lbl_title}:</b></td>
<td width="100%">{if !$static}<input type="text" name="customer_info[title]" value="{$customer.title}" />{else}{$customer.title}{/if}</td>
</tr>
{/if}
{if $customer.default_fields.firstname}
<tr>
  <td nowrap="nowrap"><b>{$lng.lbl_first_name}:</b></td>
  <td width="100%">{if !$static}<input type="text" name="customer_info[firstname]" value="{$customer.firstname}" />{else}{$customer.firstname}{/if}</td>
</tr>
{/if}
{if $customer.default_fields.lastname}
<tr>
  <td nowrap="nowrap"><b>{$lng.lbl_last_name}:</b></td>
  <td width="100%">{if !$static}<input type="text" name="customer_info[lastname]" value="{$customer.lastname}" />{else}{$customer.lastname}{/if}</td>
</tr>
{/if}
{if $customer.default_fields.phone}
<tr>
  <td><b>{$lng.lbl_phone}:</b></td>
  <td width="100%">
  {if !$static}<input type="text" name="customer_info[phone]" value="{$customer.phone}" style="width: 29%;" />{else}{$customer.phone}{/if}
  <b>{$lng.lbl_phone_ext}</b> {if !$static}<input type="text" name="customer_info[phone_ext]" value="{$customer.phone_ext}" style="width: 10%;" maxlength="6" />{else}{$customer.phone_ext}{/if}&nbsp;&nbsp;&nbsp;<a target="_blank" href="https://www.google.com/#q={$google_phone}" style="color: #1F08F8;">Google this phone</a>
  </td>
</tr>

{if $Telephone_area_code_info ne ""}
<tr>
  <td nowrap="nowrap"><b>Area code:</b></td>
  <td width="100%">{$Telephone_area_code_info}</td>
</tr>
{/if}

{/if}
{if $customer.default_fields.fax}
<tr>
  <td><b>{$lng.lbl_fax}:</b></td>
  <td width="100%">{if !$static}<input type="text" name="customer_info[fax]" value="{$customer.fax}" />{else}{$customer.fax}{/if}</td>
</tr>
{/if}
{if $customer.default_fields.email}
<tr>
  <td><b>{$lng.lbl_email}:</b></td>
  <td width="100%" nowrap="nowrap">
  {if !$static}<input type="text" name="customer_info[email]" value="{$customer.email}" style="width: 55%;" />{else}{$customer.email}{/if}
  <a target="_blank" href="https://www.google.com/#q={$customer.email}" style="color: #1F08F8;">Google email</a>
  &nbsp;&nbsp;<a target="_blank" href="{$userinfo_site}" style="color: #1F08F8;">Website</a>
 </td>
</tr>
{/if}
{if $customer.default_fields.url}
<tr>
  <td><b>{$lng.lbl_url}:</b></td>
  <td width="100%">{if !$static}<input type="text" name="customer_info[url]" value="{$customer.url}" />{else}{$customer.url}{/if}</td>
</tr>
{/if}
{foreach from=$customer.additional_fields item=v}
{if $v.section eq 'C' || $v.section eq 'P'}
<tr>
  <td><b>{$v.title}:</b></td>
      <td>{$v.value}</td>
</tr>
{/if}
{/foreach}
</table>
  </td>
  <td width="5%">&nbsp;</td>
  <td width="47%" style="vertical-align: top;">
  {if $order.po_details}
  <input type="hidden" name="po_update" value="1" />
  <table cellspacing="0" cellpadding="0" class="customer-info-edit">
  <tr>
    <td width="24%"><b>{$lng.lbl_po_number}:</b> </td>
    <td width="76%"><input type="text" name="po_number" id="po_number" value="{$order.po_details.po_number|escape}" /></td>
  </tr>
  <tr>
    <td width="24%"><b>{$lng.lbl_company_name}:</b> </td>
    <td width="76%"><input type="text" name="po_company_name" id="po_company_name" value="{$order.po_details.company_name|escape}" /></td>
  </tr>
  <tr>
    <td width="24%"><b>Fax:</b> </td>
    <td width="76%"><input type="text" name="po_fax" id="po_fax" value="{$order.po_details.po_fax|escape}" /></td>
  </tr>
  <tr>
    <td width="24%"><b>{$lng.lbl_name_of_purchaser}:</b> </td>
    <td width="76%"><input type="text" name="name_of_purchaser" id="name_of_purchaser" value="{$order.po_details.name_of_purchaser|escape}" /></td>
  </tr>
  <tr>
    <td width="24%"><b>{*{$lng.lbl_position}*}Position:</b> </td>
    <td width="76%"><input type="text" name="po_position" id="po_position" value="{$order.po_details.position|escape}" /></td>
  </tr>
  </table>
  {/if}
  </td>
</tr>
</table>
<br />
<table cellspacing="0" cellpadding="0" width="100%" border="0">
<tr>
  <td width="47%" height="25"><b>{$lng.lbl_shipping_address}</b> &nbsp;&nbsp;&nbsp;<a target="_blank" href="https://www.google.com/#q={$google_shipping_address}" style="color: #1F08F8;">Google this address</a>&nbsp;&nbsp;&nbsp;<a target="_blank" href="http://www.spokeo.com/search?q={$google_shipping_address}" style="color: #1F08F8;">Spokeo this address</a></td>
  <td width="5%">&nbsp;</td>
  <td width="47%" height="25"><b>{$lng.lbl_billing_address}</b> &nbsp;&nbsp;&nbsp;<a target="_blank" href="https://www.google.com/#q={$google_billing_address}" style="color: #1F08F8;">Google this address</a>&nbsp;&nbsp;&nbsp;<a target="_blank" href="http://www.spokeo.com/search?q={$google_billing_address}" style="color: #1F08F8;">Spokeo this address</a></td>
</tr>
<tr>
  <td bgcolor="#000000" height="2"><img height="2" src="{$ImagesDir}/spacer_black.gif" width="100%" alt="" /></td>
  <td><img height="2" src="{$ImagesDir}/spacer.gif" width="1" alt="" /></td>
  <td bgcolor="#000000" height="2"><img height="2" src="{$ImagesDir}/spacer_black.gif" width="100%" alt="" /></td>
</tr>
<tr>
  <td colspan="3"><img height="2" src="{$ImagesDir}/spacer.gif" width="1" alt="" /></td>
</tr>
<tr>
  <td>
  <table cellspacing="0" cellpadding="0" class="customer-info-edit">
{if $customer.default_fields.s_firstname}
  <tr>
    <td><b>{$lng.lbl_first_name}:</b> </td>
    <td width="100%">{if !$static}<input type="text" name="customer_info[s_firstname]" value="{$customer.s_firstname}" />{else}{$customer.s_firstname}{/if}</td>
  </tr>
{/if}
{if $customer.default_fields.s_lastname}
  <tr>
    <td><b>{$lng.lbl_last_name}:</b> </td>
    <td width="100%">{if !$static}<input type="text" name="customer_info[s_lastname]" value="{$customer.s_lastname}" />{else}{$customer.s_lastname}{/if}</td>
  </tr>
{/if}
{foreach from=$customer.additional_fields item=v}
{if $v.section eq 'S'}
  <tr>
    <td>{if $v.title ne "Company"}<b>{/if}{$v.title}:{if $v.title ne "Company"}</b>{/if}</td>
        <td width="100%">{if !$static}<input type="text" name="additional_fields[{$v.fieldid}]" value="{$v.value}" />{else}{$v.value}{/if}</td>
  </tr>
{/if}
{/foreach}
{if $customer.default_fields.s_address}
  <tr>
    <td><b>{$lng.lbl_address}:</b> </td>
    <td width="100%">{if !$static}<input type="text" name="customer_info[s_address]" value="{$customer.s_address}" />{else}{$customer.s_address}{/if}</td>
  </tr>
  <tr>
    <td nowrap="nowrap">{$lng.lbl_address_2}: </td>
    <td width="100%">{if !$static}<input type="text" name="customer_info[s_address_2]" value="{$order.s_address_2}" />{else}{$order.s_address_2}{/if}</td>
  </tr>
{/if}
{if $customer.default_fields.s_city}
  <tr>
    <td><b>{$lng.lbl_city}:</b> </td>
    <td width="100%">{if !$static}<input type="text" name="customer_info[s_city]" value="{$customer.s_city}" />{else}{$customer.s_city}{/if}</td>
  </tr>
{/if}
{if $customer.default_fields.s_county && $config.General.use_counties eq 'Y'}
  <tr>
    <td><b>{$lng.lbl_county}:</b> </td>
    <td width="100%">{if !$static}<input type="text" name="customer_info[s_county]" value="{$customer.s_county}" />{else}{$customer.s_countyname}{/if}</td>
  </tr>
{/if}
{if $customer.default_fields.s_state}
  <tr>
    <td><b>{$lng.lbl_state}:</b> </td>
    <td width="100%">{if !$static}
{include file="main/states.tpl" states=$states name="customer_info[s_state]" default=$customer.s_state default_country=$customer.s_country|default:$config.General.default_country country_name="customer_info[s_country]"}
{else}{$customer.s_statename}{/if}</td>
  </tr>
{/if}
{if $customer.default_fields.s_country}
  <tr>
    <td><b>{$lng.lbl_country}:</b> </td>
    <td width="100%">{if !$static}
<select name="customer_info[s_country]" id="customer_info_s_country" size="1">
{section name=country_idx loop=$countries}
<option value="{$countries[country_idx].country_code}"{if $customer.s_country eq $countries[country_idx].country_code} selected="selected"{elseif $countries[country_idx].country_code eq $config.General.default_country and $customer.s_country eq ""} selected="selected"{/if}>{$countries[country_idx].country|amp}</option>
{/section}
{if $customer.default_fields.s_state}
{include file="main/register_states.tpl" state_name="customer_info[s_state]" country_name="customer_info[s_country]" county_name="customer_info[s_county]" state_value=$customer.s_state county_value=$customer.s_county country_id="customer_info_s_country"}
{/if}
</select>
{else}{$customer.s_countryname}{/if}</td>
  </tr>
{/if}
{if $customer.default_fields.s_zipcode}
  <tr>
    <td><b>{$lng.lbl_zip_code}:</b> </td>
    <td width="100%">{if !$static}<input type="text" name="customer_info[s_zipcode]" value="{$customer.s_zipcode}" />{else}{$customer.s_zipcode}{/if}</td>
  </tr>
{/if}
  </table>
  </td>
  <td>&nbsp;</td>
  <td>
  <table cellspacing="0" cellpadding="0" class="customer-info-edit">
{if $customer.default_fields.b_firstname}
  <tr>
    <td><b>{$lng.lbl_first_name}:</b> </td>
    <td width="100%">{if !$static}<input type="text" name="customer_info[b_firstname]" value="{$customer.b_firstname}" />{else}{$customer.b_firstname}{/if}</td>
  </tr>
{/if}
{if $customer.default_fields.b_lastname}
  <tr>
    <td><b>{$lng.lbl_last_name}:</b> </td>
    <td width="100%">{if !$static}<input type="text" name="customer_info[b_lastname]" value="{$customer.b_lastname}" />{else}{$customer.b_lastname}{/if}</td>
  </tr>
{/if}
{foreach from=$customer.additional_fields item=v}
{if $v.section eq 'B'}
  <tr>
    <td>{if $v.title ne "Company"}<b>{/if}{$v.title}:{if $v.title ne "Company"}</b>{/if}</td>
        <td width="100%">{if !$static}<input type="text" name="additional_fields[{$v.fieldid}]" value="{$v.value}" />{else}{$v.value}{/if}</td>
  </tr>
{/if}
{/foreach}
{if $customer.default_fields.b_address}
  <tr>
    <td><b>{$lng.lbl_address}:</b> </td>
    <td width="100%">{if !$static}<input type="text" name="customer_info[b_address]" value="{$customer.b_address}" />{else}{$customer.b_address}{/if}</td>
  </tr>
  <tr>
    <td nowrap="nowrap">{$lng.lbl_address_2}: </td>
    <td width="100%">{if !$static}<input type="text" name="customer_info[b_address_2]" value="{$customer.b_address_2}" />{else}{$customer.b_address_2}{/if}</td>
  </tr>
{/if}
{if $customer.default_fields.b_city}
  <tr>
    <td><b>{$lng.lbl_city}:</b> </td>
    <td width="100%">{if !$static}<input type="text" name="customer_info[b_city]" value="{$customer.b_city}" />{else}{$customer.b_city}{/if}</td>
  </tr>
{/if}
{if $customer.default_fields.b_county && $config.General.use_counties eq 'Y'}
  <tr>
    <td><b>{$lng.lbl_county}:</b> </td>
    <td width="100%">{if !$static}<input type="text" name="customer_info[b_county]" id="customer_info_b_county" value="{$customer.b_county}" />{else}{$customer.b_countyname}{/if}</td>
  </tr>
{/if}
{if $customer.default_fields.b_state}
  <tr>
    <td><b>{$lng.lbl_state}:</b> </td>
    <td width="100%">{if !$static}
{include file="main/states.tpl" states=$states name="customer_info[b_state]" default=$customer.b_state default_country=$customer.b_country|default:$config.General.default_country country_name="customer_info[b_country]"}
{else}{$customer.b_statename}{/if}</td>
  </tr>
{/if}
{if $customer.default_fields.b_country}
  <tr>
    <td><b>{$lng.lbl_country}:</b> </td>
    <td width="100%">{if !$static}
<select name="customer_info[b_country]" id="customer_info_b_country" size="1">
{section name=country_idx loop=$countries}
<option value="{$countries[country_idx].country_code}"{if $customer.b_country eq $countries[country_idx].country_code} selected="selected"{elseif $countries[country_idx].country_code eq $config.General.default_country and $customer.b_country eq ""} selected="selected"{/if}>{$countries[country_idx].country|amp}</option>
{/section}
</select>
{if $customer.default_fields.b_state}
{include file="main/register_states.tpl" state_name="customer_info[b_state]" country_name="customer_info[b_country]" county_name="customer_info[b_county]" state_value=$customer.b_state county_value=$customer.b_county country_id="customer_info_b_country"}
{/if}
{else}{$customer.b_countryname}{/if}</td>
  </tr>
{/if}
{if $customer.default_fields.b_zipcode}
  <tr>
    <td><b>{$lng.lbl_zip_code}:</b> </td>
    <td width="100%">{if !$static}<input type="text" name="customer_info[b_zipcode]" value="{$customer.b_zipcode}" />{else}{$customer.b_zipcode}{/if}</td>
  </tr>
{/if}
  </table>
      </td>
</tr>

{assign var="is_header" value=""}
{foreach from=$customer.additional_fields item=v}
{if $v.section eq 'A'}
{if $is_header eq ''}
<tr>
<td colspan="3">&nbsp;</td>
</tr>
<tr>
<td width="45%" height="25"><b>{$lng.lbl_additional_information}</b></td>
<td colspan="2" width="55%">&nbsp;</td>
</tr>
<tr>
<td bgcolor="#000000" height="2"><img height="2" src="{$ImagesDir}/spacer_black.gif" width="100%" alt="" /></td>
<td colspan="2" width="55%"><img height="2" src="{$ImagesDir}/spacer.gif" width="1" alt="" /></td>
</tr>
<tr>
<td colspan="3"><img height="2" src="{$ImagesDir}/spacer.gif" width="1" alt="" /></td>
</tr>
<tr>
<td><table cellspacing="0" cellpadding="0" width="100%" border="0">
{assign var="is_header" value="E"}
{/if}
<tr valign="top">
<td>{if $v.title ne "Company"}<b>{/if}{$v.title}{if $v.title ne "Company"}</b>{/if}</td>
  <td width="100%">{if !$static}<input type="text" name="additional_fields[{$v.fieldid}]" value="{$v.value}" />{else}{$v.value}{/if}</td>
</tr>
{/if}
{/foreach}
{if $is_header eq 'E'}
</table></td>
<td colspan="2" width="55%">&nbsp;</td>
</tr>
{/if}

</table>

<br />
{if !$static}
<input type="submit" value="{$lng.lbl_apply_changes|escape}" />
{if $current_membership_flag ne 'FS'}
  &nbsp;&nbsp;&nbsp;&nbsp;
  <input type="button" value="{$lng.lbl_apply_changes_send_email|escape}" onclick="javascript: $('#send_email2').val('Y'); this.form.submit();" />
{/if}
<br />
{/if}

</form>

<br />

{*
$Id: order_info_admin.tpl, v 1.0.0 2010/03/23 15:16:14 random Exp $
vim: set ts=2 sw=2 sts=2 et:
*}
<script type="text/javascript" language="JavaScript 1.2" src="{$SkinDir}/lib/jqueryui/jquery-ui.custom.min.js"></script>
{include file="check_zipcode_js.tpl"}

<a name="order_info"></a>

{include file="main/subheader.tpl" title=$lng.lbl_order_info show_order_help_links="Y"}
{include file="change_states_js.tpl"}

<script type="text/javascript" language="JavaScript 1.2">
<!--
{literal}

function func_check_dc_statuses(m_id){

/*
  var dc_status = $('#groups_dc_status_'+m_id).val();
  var items_amount_id;

{/literal}
{foreach from=$order.shipping_groups item=v key=k_m_id}
{literal}

  if (m_id == '{/literal}{$k_m_id}{literal}'){
    {/literal}
    {foreach from=$v.products item=product key=prod_num}
    {literal}

      items_amount_id = "items_amount_"+m_id+"_{/literal}{$product.itemid}{literal}";

      if (dc_status == "C" || dc_status == "L" || dc_status == "S"){
        $('#'+items_amount_id).attr("readonly", "readonly");
        $('#groups_shipping_cost_net_'+m_id).attr("readonly", "readonly");
      } else {
        $('#'+items_amount_id).removeAttr("readonly");
        $('#groups_shipping_cost_net_'+m_id).removeAttr("readonly");
      }

    {/literal}
    {/foreach}
    {literal}
  }

{/literal}
{/foreach}
{literal}
*/
}

function func_check_additional_shipping_status(m_id){

  var additional_shipping_status = $("#additional_shipping_status_"+m_id).val(); 

  if (additional_shipping_status == "P"){
    $("#additional_vt_info_"+m_id).show();
  }
  else {
    $("#additional_vt_info_"+m_id).hide();
  }
}

function func_check_cb_statuses(){

  var all_cb_status_eq_P = true;
  var cb_status_eq_P_found = false;
  var cb_status;

{/literal}
{foreach from=$order.shipping_groups item=v key=m_id}
{literal}

  cb_status = $('#groups_cb_status_{/literal}{$m_id}{literal}').val();

  if (document.getElementById('refund_group_{/literal}{$m_id}{literal}')){
    if (cb_status == "3" || cb_status == "V"){
      $("#refund_group_{/literal}{$m_id}{literal}").show();
    } else {
      $("#refund_group_{/literal}{$m_id}{literal}").hide();
    }
  }

  if (cb_status == "P"){
    cb_status_eq_P_found = true;
  } else {
    all_cb_status_eq_P = false;
  }

{/literal}
{/foreach}
{literal}

  if (cb_status_eq_P_found == true && all_cb_status_eq_P == true){
    $("#vt_info").show();
  } else {
    $("#vt_info").hide();
  }
}

function js_explode (delimiter, string, limit) {

  if ( arguments.length < 2 || typeof delimiter === 'undefined' || typeof string === 'undefined' ) return null;
  if ( delimiter === '' || delimiter === false || delimiter === null) return false;
  if ( typeof delimiter === 'function' || typeof delimiter === 'object' || typeof string === 'function' || typeof string === 'object'){
    return { 0: '' };
  }
  if ( delimiter === true ) delimiter = '1';

  // Here we go...
  delimiter += '';
  string += '';

  var s = string.split( delimiter );


  if ( typeof limit === 'undefined' ) return s;

  // Support for limit
  if ( limit === 0 ) limit = 1;

  // Positive limit
  if ( limit > 0 ){
    if ( limit >= s.length ) return s;
    return s.slice( 0, limit - 1 ).concat( [ s.slice( limit - 1 ).join( delimiter ) ] );
  }

  // Negative limit
  if ( -limit >= s.length ) return [];

  s.splice( s.length + limit );
  return s;
}

function check_r_field(form, prefix, key_of_arr) {

  var reg = new RegExp("^"+prefix, "");

  for (var i = 0; i < form.elements.length; i++) {

    if (form.elements[i].type == "text" && (!prefix || form.elements[i].id.search(reg) == 0)){

      var field_id = form.elements[i].id;
      var field = $('#'+field_id).val();
      var first_letter = field[0];

      if (first_letter == "R" || first_letter == "r"){

       var field_id_arr = js_explode("_", field_id);
       var mid = field_id_arr[key_of_arr];

       var cb_status = $('#groups_cb_status_'+mid).val();

       if (cb_status != "P" && cb_status != "V" && cb_status != "H" && cb_status != "3"){
          return "N";
       }
      }
    }
  }

  return "Y";
}


function check_r_fields(){

  var form = document.ordereditform1;

  var check_amount = check_r_field(form, "items_amount_", 2);
  var check_shipping_cost_net = check_r_field(form, "groups_shipping_cost_net_", 4);


  if (check_amount == "Y" && check_shipping_cost_net == "Y"){
    form.submit();
    return true;
  } else {

    alert("{/literal}{$lng.lbl_refunds_apply_order}{literal}");
    return false;
  }

}
{/literal}
-->
</script>


<form action="order.php" method="post" name="ordereditform1" onsubmit="javascript: check_r_fields(); return false;">

<input type="hidden" name="mode" value="order_edit_apply" id="ordereditform_mode" />
<input type="hidden" name="ref_notify_button_clicked" value="" id="ref_notify_button_clicked" />

<input type="hidden" name="orderid" value="{$order.orderid}" />
<input type="hidden" name="notify_mid" value="" id="ordereditform_mid" />
<input type="hidden" name="send_email" id="send_email1" value="N" />

<table cellpadding="3" cellspacing="1" width="100%">
<tr class="TableHead">
  <td width="35%">{$lng.lbl_product}</td>
  <td width="17%">{$lng.lbl_sku}</td>
  <td width="7%">{$lng.lbl_price}</td>
  <td width="5%"><span onmouseout="javascript: $('#header_lbl_qty').hide();" onmouseover="javascript: cidev_showNote('header_lbl_qty', this);" style="text-decoration: none;"><font class="Star">R</font>{$lng.lbl_qty}</span>
    <div id="header_lbl_qty" class="cidev_NoteBox" style="display: none; width: 600px; margin-left: -640px;">{$lng.lbl_order_edit_info_1}</div>
  </td>
  <td width="5%">{$lng.lbl_back}</td>
  <td width="7%" nowrap="nowrap">ETA date<br />(mm/dd/yyyy)</td>
  <td width="7%"><span onmouseout="javascript: $('#header_lbl_net').hide();" onmouseover="javascript: cidev_showNote('header_lbl_net', this);" style="text-decoration: none;"><font class="Star">R</font>{$lng.lbl_net}</span>
    <div id="header_lbl_net" class="cidev_NoteBox" style="display: none; width: 600px; margin-left: -640px;">{$lng.lbl_order_edit_info_2}</div>
  </td>
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
  <td>
    <table cellpadding="0" cellspacing="0" width="100%">
    <tr>
      <td>
          <a target="_blank" style="color: green;" href="manufacturers.php?manufacturerid={$m_id}&distributor_section=3">{$v.group_name}</a>
          {if $order_manufacturers[$m_id].d_shipping_methods_usps eq "Y"}
            <span style="color: #000000; font-weight: normal;">ships by USPS</span>
          {/if}
      </td>
      {if $v.all_distributor_info.d_specific_instructions ne ""}
      <td align="right"><a target="_blank" onmouseout="javascript: $('#d_specific_instructions_note_{$m_id}').hide();" onmouseover="javascript: cidev_showNote('d_specific_instructions_note_{$m_id}', this);" style="color: #FF0000;" href="manufacturers.php?manufacturerid={$m_id}&distributor_section=1">?</a></td>
      {/if}
    </tr>
    </table>

    <div id="d_specific_instructions_note_{$m_id}" class="cidev_NoteBox" style="display: none;">
      <table><tr><td nowrap="nowrap">{$v.all_distributor_info.d_specific_instructions}</td></tr></table>
    </div>

  </td>
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
          <br /> {$item.classtext}
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
        {elseif $product.product_options_txt ne ""}
          <br />Options: {$product.product_options_txt}
        {/if}
      {/foreach}
    {/if}
{* --------------------- *}
  </td>
  <td>
    {if $current_membership_flag ne 'FS'}<a href="{$product.links.admin}" title="" target="_blank">{$product.productcode}</a>{else}{$product.productcode}{/if}
{assign var="mpn" value=`$product.mpn`}
{if $order_manufacturers[$m_id].d_website_search_for_sku_url ne ""}<br />
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
  <td align="right" valign="top">{if !$static}<input type="text" size="5" id="items_amount_{$m_id}_{$product.itemid}" name="items[{$product.itemid}][amount]" value="{$product.amount}" {* {if $v.dc_status eq 'C' || $v.dc_status eq 'L' || $v.dc_status eq 'S'}readonly="readonly"{/if} *} />{else}{$product.amount}{/if}</td>
  <td align="right" valign="top">{if !$static}<input {if $v.dc_status eq 'K' || $v.dc_status eq 'E'}readonly="readonly"{/if} type="text" size="5" name="items[{$product.itemid}][back]" value="{$product.back}" />{else}{$product.back}{/if}</td>

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
  <td align="right" valign="top">{if !$static}<input {if $v.dc_status eq 'K' || $v.dc_status eq 'E'}readonly="readonly"{else}id="eta_date_mm_dd_yyyy_{$product.itemid}"{/if} type="text" size="9" style="width: 98%;" name="items[{$product.itemid}][eta_date_mm_dd_yyyy]" value="{$product.eta_date_mm_dd_yyyy}" />{else}{$product.eta_date_mm_dd_yyyy}{/if}</td>

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

      {assign var="row_conter" value="0"}
      {foreach from=$v.tracking item=t}

        {if $t.tracknum ne ""}
          {math equation="x+1" x=$row_conter assign="row_conter"}

          <div id="tracknum_{$row_conter}">
          <a href="{$tracking_links[$t.linkid].link|substitute:"tracknum":$t.tracknum}" target="_blank">{$tracking_links[$t.linkid].shipping}: {$t.tracknum}</a>

          <a href="javascript: void(0);" onclick="javascript: $('#tracknum_val_{$row_conter}').val(''); $('#tracknum_link_{$row_conter}').val(''); $('#tracknum_{$row_conter}').hide();"><img src="{$ImagesDir}/minus.gif" /></a>

          <input type="hidden" name="tracknums[{$m_id}][{$row_conter}][tracknum]" value="{$t.tracknum}" id="tracknum_val_{$row_conter}" />
          <input type="hidden" name="tracknums[{$m_id}][{$row_conter}][linkid]" value="{$t.linkid}" id="tracknum_link_{$row_conter}" />
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
      <input id="groups_shipping_cost_net_{$m_id}" type="text" size="8" name="groups[{$m_id}][shipping_cost_net]" value="{$v.shipping_cost.net|price_format}" {* {if $v.dc_status eq 'C' || $v.dc_status eq 'L' || $v.dc_status eq 'S'}readonly="readonly"{/if} *} />
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

{*
{if $order_manufacturers[$m_id].additional_shipping_charge gt 0}
&nbsp;
<span style="color: #FF0000; font-weight: bold;">Additional shipping required: ${$order_manufacturers[$m_id].additional_shipping_charge}</span>
{/if}
*}

{if $order_manufacturers[$m_id].d_drop_ship_fee_select eq "applies_to_all_orders"}
Drop-ship fee: {include file="currency.tpl" value=$order_manufacturers[$m_id].d_drop_ship_fee_in_us hide_zero='Y'} applies to all orders
{elseif $order_manufacturers[$m_id].d_drop_ship_fee_select eq "applies_to_orders_below_minimum_order_amount_only"}
Drop-ship fee: {include file="currency.tpl" value=$order_manufacturers[$m_id].d_drop_ship_fee_in_us hide_zero='Y'} applies to orders below {include file="currency.tpl" value=$order_manufacturers[$m_id].d_minimum_order_amount_in_us hide_zero='Y'}
{/if}

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


{if $order_manufacturers[$m_id].additional_shipping_charge gt 0}
<tr {cycle values=", class='TableSubHead'" name="cycle_`$m_id`"} style="BACKGROUND-COLOR: #FFD44C;">

<td colspan="6">
<span style="color: #FF0000; font-weight: bold;">Additional shipping required: ${$order_manufacturers[$m_id].additional_shipping_charge}</span>
</td>

<td>
{if $additional_shipping_statuses ne ""}

<script type="text/javascript" language="JavaScript 1.2">
<!--
{literal}
  $(function() {
    $("#additional_shipping_status_{/literal}{$m_id}{literal}").change(function(){
      func_check_additional_shipping_status({/literal}{$m_id}{literal});
    });
  });
{/literal}
-->
</script>

<select name="groups[{$m_id}][additional_shipping_status]" id='additional_shipping_status_{$m_id}'>
{foreach from=$additional_shipping_statuses item=v_s key=k_s}
<option {if $v.additional_shipping_status eq $k_s}selected="selected"{/if} value="{$k_s}">{$v_s}</option>
{/foreach}
</select>
{/if}
</td>

<td colspan="3">

</td>
</tr>

 {if $all_vt_processors ne ""}
 <tr style="background-color: #F4CCCC; display: none;" id="additional_vt_info_{$m_id}" >
 <td colspan="10">
   <table>
     <tr>
       <td>
         <b>Payment method:</b><br />
         <select name="groups[{$m_id}][additional_vt_paymentid]" id="additional_vt_paymentid_{$m_id}">
         <option value="0"></option>
         {foreach from=$all_vt_processors item=item_vt key=key_vt}
         <option {if $v.additional_vt_paymentid eq $item_vt.paymentid} selected="selected"{/if} value="{$item_vt.paymentid}">{$item_vt.payment_method}</option>
         {/foreach}
         </select>
       </td>
       <td width="20">&nbsp;</td>
       <td>
           <b>Virtual terminal transaction ID:</b><br />
           <input type="text" name="groups[{$m_id}][additional_transaction_id_link]" id="additional_transaction_id_link_{$m_id}" value="{$v.additional_transaction_id_link}" size="40" />
       </td>
       <td width="20">&nbsp;</td>
       <td>
           <b>AVS code:</b><br />
           <input type="text" name="groups[{$m_id}][additional_avs_code]" id="additional_avs_code_{$m_id}" value="{$v.additional_avs_code}" size="1" maxlength="1" />
       </td>
     </tr>
   </table>
 </td>
 </tr>
 {/if}

{/if}

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

<script type="text/javascript" language="JavaScript 1.2">
<!--
{literal}
  $(function() {
    $("#groups_cb_status_{/literal}{$m_id}{literal}").change(function(){
      func_check_cb_statuses();
    });
  });
{/literal}
-->
</script>

        <b>{$lng.lbl_cust_bus_payment_status}:</b><br />
        {include file="main/order_status.tpl" status=$v.cb_status mode="select" name="groups[`$m_id`][cb_status]" status_type="CB" extra=" id='groups_cb_status_`$m_id`' "}
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


<script type="text/javascript" language="JavaScript 1.2">
<!--
{literal}
  $(function() {
    $("#groups_dc_status_{/literal}{$m_id}{literal}").change(function(){
      func_check_dc_statuses('{/literal}{$m_id}{literal}');
    });
  });
{/literal}
-->
</script>


        {include file="main/order_status.tpl" status=$v.dc_status mode="select" name="groups[`$m_id`][dc_status]" status_type="DC" hide_pending_availability_check_status=$hide_pending_availability_check_status hide_dispatched_status=$hide_dispatched_status extra=" id='groups_dc_status_`$m_id`' "}
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

{if $all_vt_processors ne ""}
<tr style="background-color: #F4CCCC; {* {if $all_cb_status_eq_P ne "Y"} *} display: none; {* {/if} *}" id="vt_info" >
<td colspan="10">
  <table>
    <tr>
      <td>
        <b>Payment method:</b><br />
        <select name="vt_paymentid" id="vt_paymentid">
        <option value="0"></option>
        {foreach from=$all_vt_processors item=item_vt key=key_vt}
        <option {if $order.vt_paymentid eq $item_vt.paymentid} selected="selected"{/if} value="{$item_vt.paymentid}">{$item_vt.payment_method}</option>
        {/foreach}
        </select>
      </td>
      <td width="20">&nbsp;</td>
      <td>
          <b>Virtual terminal transaction ID:</b><br />
          <input type="text" name="transaction_id_link" id="transaction_id_link" value="{$order.transaction_id_link}" size="40" />
      </td>
      <td width="20">&nbsp;</td>
      <td>
          <b>AVS code:</b><br />
          <input type="text" name="avs_code" id="avs_code" value="{$order.avs_code}" size="1" maxlength="1" />
      </td>
    </tr>
  </table>
</td>
</tr>
{/if}

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
  <td>Total Shipping Charge</td>
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

{if $order.additional_fee ne ""}
{foreach from=$order.additional_fee item=v_f key=k_f}
<tr{cycle values=", class='TableSubHead'" name="cycle_totals"}>
  <td><input type="text" name="edit_additional_fee_name[{$v_f.id}][additional_fee_name]" value="{$v_f.additional_fee_name}" size="16" style="width: 99%;" /></td>
  <td colspan="5">&nbsp;</td>
  <td align="right"><input type="text" name="edit_additional_fee_name[{$v_f.id}][additional_fee_value]" value="{$v_f.additional_fee_value}" size="8" /></td>
  <td>&nbsp;</td>
  <td align="right">{$v_f.additional_fee_value}</td>
  <td><input type="checkbox" value="Y" name="delete_additional_fee[{$v_f.id}]" /></td>
</tr>
{/foreach}
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
<br />
{include file="main/subheader.tpl" title=$lng.lbl_add_to_order}

<script type="text/javascript">
<!--
multirowInputSets['add_to_order'] = [];
multirowInputSets['add_to_order'].noCloneContent = 1;
multirowInputSets['add_to_order'].noCloneHTMLId = 'add_to_order_box_0';

multirowInputSets['add_additional_fee_to_order'] = [];
multirowInputSets['add_additional_fee_to_order'].noCloneContent = 1;
-->
</script>

</td>
</tr>

<tr class="TableHead">
  <td width="27%" style="background-color: #cfe2f3;">additional fee / sales tax name</td>
  <td width="14%" style="background-color: #fff2cc;">product sku</td>
  <td width="10%"></td>
  <td width="7%" style="background-color: #fff2cc;">Qty</td>
  <td width="7%"></td>
  <td width="8%"></td>
  <td width="7%" style="background-color: #cfe2f3;">amount</td>
  <td width="7%"></td>
  <td width="7%"></td>
  <td width="5%"></td>
</tr>

<tr id="add_to_order_tr">
  <td align="center" id="add_to_order_box_0"></td>
  <td align="center" id="add_to_order_box_1"><input type="text" name="add_productcode[0]" value="" style="width: 96%;" /></td>
  <td align="center" id="add_to_order_box_2"></td>
  <td align="center" id="add_to_order_box_3"><input type="text" name="add_amount[0]" value="" style="width: 96%;" /></td>
  <td align="center" id="add_to_order_box_4"></td>
  <td align="center" id="add_to_order_box_5"></td>
  <td align="center" id="add_to_order_box_6"></td>
  <td align="center" id="add_to_order_box_7"></td>
  <td align="center" id="add_to_order_box_8"></td>
  <td>{include file="buttons/multirow_add.tpl" mark="add_to_order"}</td>
</tr>

<tr id="add_additional_fee_to_order_tr" style="background-color: #EEEEEE;">
  <td align="center" id="add_additional_fee_to_order_box_0"><input type="text" name="add_additional_fee_name[0]" value="" style="width: 96%;" /></td>
  <td align="center" id="add_additional_fee_to_order_box_1"></td>
  <td align="center" id="add_additional_fee_to_order_box_2"></td>
  <td align="center" id="add_additional_fee_to_order_box_3"></td>
  <td align="center" id="add_additional_fee_to_order_box_4"></td>
  <td align="center" id="add_additional_fee_to_order_box_5"></td>
  <td align="center" id="add_additional_fee_to_order_box_6"><input type="text" name="add_additional_fee_value[0]" value="" style="width: 96%;" /></td>
  <td align="center" id="add_additional_fee_to_order_box_7"></td>
  <td align="center" id="add_additional_fee_to_order_box_8"></td>
  <td>{include file="buttons/multirow_add.tpl" mark="add_additional_fee_to_order"}</td>
</tr>

</table>
<br />



<input type="submit" value="{$lng.lbl_apply_changes|escape}" />
{if $current_membership_flag ne 'FS'}
  &nbsp;&nbsp;&nbsp;&nbsp;
  <input type="button" value="{$lng.lbl_apply_changes_send_email|escape}" onclick="javascript: $('#send_email1').val('Y'); this.form.submit();" />
{/if}

</form>

<form action="order.php" method="post" name="ordereditform2">
<input type="hidden" name="mode" value="order_edit_apply" />
<input type="hidden" name="orderid" value="{$order.orderid}" />
<input type="hidden" name="send_email" id="send_email2" value="N" />

<a name="customer_info"></a>
<br />

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
  <td width="100%" nowrap="nowrap">{if !$static}<input type="text" name="customer_info[firstname]" value="{$customer.firstname}" style="width: 55%;" />{else}{$customer.firstname}{/if}
&nbsp;&nbsp;&nbsp;<a target="_blank" href="https://www.google.com/#q={$customer.firstname|replace:' ':'+'}" style="color: #1F08F8;">Google full name</a>
  </td>
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
  <b>{$lng.lbl_phone_ext}</b> {if !$static}<input type="text" name="customer_info[phone_ext]" value="{$customer.phone_ext}" style="width: 10%;" maxlength="6" />{else}{$customer.phone_ext}{/if}&nbsp;&nbsp;&nbsp;<a target="_blank" href="https://www.google.com/#q={$google_phone}" style="color: #1F08F8;">Google phone</a>
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
  <a target="_blank" href="https://www.google.com/#q={$customer.email}{$fraud_Google_email_search_exclusions}" style="color: #1F08F8;">Google email</a>
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
    <td width="24%"><b>{if $count_po_number gt 1}<font style="color: #FF0000;">{/if}{$lng.lbl_po_number}:{if $count_po_number gt 1}</font>{/if}</b> </td>
    <td width="76%"><input type="text" name="po_number" id="po_number" value="{$order.po_details.po_number|escape}" /></td>
  </tr>
{if $count_po_number gt 1 && $used_po_for_the_same_order ne ""}
  <tr>
    <td colspan="2"><b>Orders with the same PO Number:</b> 
      {foreach from=$used_po_for_the_same_order item=v_po key=k_po}
        <a style="color: #1F08F8;" target="_blank" href="order.php?orderid={$v_po.orderid}">{$v_po.order_prefix}{$v_po.orderid}</a>{if $k_po ne $last_index_used_po_for_the_same_order},{/if}
      {/foreach}
    </td>
  </tr>
{/if}
  <tr>
    <td width="24%"><b>{$lng.lbl_company_name}:</b> </td>
    <td width="76%"><input type="text" name="po_company_name" id="po_company_name" value="{$order.po_details.company_name|escape}" /></td>
  </tr>
  <tr>
    <td width="24%"><b>Fax:</b> </td>
    <td width="76%"><input type="text" name="po_fax" id="po_fax" value="{$order.po_details.po_fax|escape}" /></td>
  </tr>

  {if $po_fax_area_code_info ne ""}
  <tr>
    <td nowrap="nowrap"><b>Area code:</b></td>
    <td width="100%">{$po_fax_area_code_info}</td>
  </tr>
  {/if}

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
  <td width="47%" height="25"><b>{$lng.lbl_shipping_address}</b> &nbsp;&nbsp;&nbsp;<a target="_blank" href="https://www.google.com/#q={$google_shipping_address}" style="color: #1F08F8;">Google this address</a>&nbsp;&nbsp;&nbsp;<a target="_blank" href="http://www.spokeo.com/search?q={$spokeo_shipping_address}" style="color: #1F08F8;">Spokeo this address</a></td>
  <td width="5%">&nbsp;</td>
  <td width="47%" height="25"><b>{$lng.lbl_billing_address}</b> &nbsp;&nbsp;&nbsp;<a target="_blank" href="https://www.google.com/#q={$google_billing_address}" style="color: #1F08F8;">Google this address</a>&nbsp;&nbsp;&nbsp;<a target="_blank" href="http://www.spokeo.com/search?q={$spokeo_billing_address}" style="color: #1F08F8;">Spokeo this address</a></td>
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
    <td width="100%" nowrap="nowrap">{if !$static}<input style="width: 55%;" type="text" name="customer_info[s_firstname]" value="{$customer.s_firstname}" />{else}{$customer.s_firstname}{/if}
&nbsp;&nbsp;&nbsp;<a target="_blank" href="https://www.google.com/#q={$customer.s_firstname|replace:' ':'+'}+{$customer.s_zipcode|replace:' ':'+'}" style="color: #1F08F8;">Google FN + zip code</a>
    </td>
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
        <td width="100%" nowrap="nowrap">{if !$static}<input style="width: 55%;" type="text" name="additional_fields[{$v.fieldid}]" value="{$v.value}" />{else}{$v.value}{/if}
{if $v.title eq "Company"}&nbsp;&nbsp;&nbsp;<a target="_blank" href="https://www.google.com/#q={$s_company_company|replace:' ':'+'}" style="color: #1F08F8;">Google company</a>{/if}
        </td>
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
{else}{$customer.s_statename}{/if}

&nbsp; <B>Abbreviation:</B> {$customer.s_state}

    </td>
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
    <td width="100%" nowrap="nowrap">{if !$static}<input style="width: 55%;" type="text" name="customer_info[b_firstname]" value="{$customer.b_firstname}" />{else}{$customer.b_firstname}{/if}
&nbsp;&nbsp;&nbsp;<a target="_blank" href="https://www.google.com/#q={$customer.b_firstname|replace:' ':'+'}+{$customer.b_zipcode|replace:' ':'+'}" style="color: #1F08F8;">Google FN + zip code</a>
    </td>
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
        <td width="100%" nowrap="nowrap">{if !$static}<input style="width: 55%;" type="text" name="additional_fields[{$v.fieldid}]" value="{$v.value}" />{else}{$v.value}{/if}
{if $v.title eq "Company"}&nbsp;&nbsp;&nbsp;<a target="_blank" href="https://www.google.com/#q={$b_company_company|replace:' ':'+'}" style="color: #1F08F8;">Google company</a>{/if}
    </td>
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
{else}{$customer.b_statename}{/if}

&nbsp; <B>Abbreviation:</B> {$customer.b_state}

    </td>
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

  <tr>
    <td><b>Customer's IP:</b> </td>
    <td width="100%">&nbsp;{$order.extra.ip_info}</td>
  </tr>

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
{/if}

{*
{if $cidev_order_details_TransID ne ""}
  &nbsp;&nbsp;&nbsp;&nbsp; <a target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_view-a-trans&id={$cidev_order_details_TransID}" style="color: #1411FF;">Link to PayPal transaction</a>
{/if}
*}

</form>

<br />

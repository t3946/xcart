{*
$Id: order_info_admin.tpl, v 1.0.0 2010/03/23 15:16:14 random Exp $
vim: set ts=2 sw=2 sts=2 et:
Use $product.oProduct classProduct
*}
<script type="text/javascript" src="{$SkinDir}/lib/jqueryui/jquery-ui.custom.min.js"></script>
{include file="check_zipcode_js.tpl"}

<a name="order_info"></a>

{include file="main/subheader.tpl" title=$lng.lbl_order_info show_order_help_links="Y"}

{$lng.txt_order_details_top_text}

{include file="change_states_js.tpl"}

<script type="text/javascript">
<!--

{literal}

function func_check_for_paypal_vt(m_id){

  var cb_status = $('#groups_cb_status_'+m_id).val();
  var additional_shipping_status = $('#additional_shipping_status_'+m_id).val();
  var orig_additional_shipping_status = additional_shipping_status;


  if (cb_status == "AP" && additional_shipping_status == "A"){

    var additional_shipping_charge = 0;

    {/literal}
    {foreach from=$order_manufacturers item=v key=k}
     {literal}
      if (m_id == '{/literal}{$k}{literal}'){
        additional_shipping_charge = {/literal}{$v.additional_shipping_charge}{literal};
        orig_additional_shipping_status = {/literal}{if $v.additional_shipping_status ne ""}{$v.additional_shipping_status}{else}''{/if}{literal};
      }
     {/literal}
    {/foreach}
    {literal}

    additional_shipping_charge = parseFloat(additional_shipping_charge);

    if (additional_shipping_charge > 0){

        $('#main_order_tabs-container').tabs(
                    {/literal}
                        {if $found_show_stock_availability_form eq "Y"}
                                {literal}
                                        {selected: 3}
                                {/literal}
                        {else}
                                {literal}
                                        {selected: 2}
                                {/literal}
                        {/if}
                    {literal}
        );

        $("#additional_shipping_status_"+m_id).val(orig_additional_shipping_status);
        $("#m_id_for_additional_shipping_status").val(m_id);

        $("#paypal_vt_grand_total").val(additional_shipping_charge);


//        $("#VT_OPENED_FROM_func_check_for_paypal_vt_function").val("Y");
        $("#default_Authorize_button").hide();
        $("#AJAX_Authorize_button").show();
        $("#AJAX_Authorize_button_text").show();
//        $("#btn_Authorize").focus();
        $("#paypal_vt_card_number").focus();

    } // if (additional_shipping_charge > 0)
  } // if (cb_status == "AP" && additional_shipping_status == "A")
  else {
//    $("#VT_OPENED_FROM_func_check_for_paypal_vt_function").val("");
        $("#default_Authorize_button").show();
        $("#AJAX_Authorize_button").hide();
        $("#AJAX_Authorize_button_text").hide();
  }
} // function


function func_check_for_pending_order_message1(m_id){
 var cb_status = $('#groups_cb_status_'+m_id).val();
 var dc_status = $('#groups_dc_status_'+m_id).val();

 if ((cb_status == "AP" || cb_status == "P") && dc_status == "E"){
  $('#pending_order_message1_'+m_id).show();
 } else {
  $('#pending_order_message1_'+m_id).hide();
 }
}

function func_check_for_pending_order_message2(m_id){
 var cb_status = $('#groups_cb_status_'+m_id).val();
 var dc_status = $('#groups_dc_status_'+m_id).val();

 if (cb_status == "P" && dc_status == "L"){
  $('#pending_order_message2_'+m_id).show();
 } else {
  $('#pending_order_message2_'+m_id).hide();
 }
}

{/literal}

function func_set_tracking_shipping(obj, m_id, invoice_number){ldelim}

  var tracking_carrier_id = obj.id;
  var carrier_id = parseInt($("#"+tracking_carrier_id).val());

  var tracking_carrier_id_arr;
  if (invoice_number == 0){ldelim}
   tracking_carrier_id_arr = tracking_carrier_id.split("tracking_carrier_"+m_id+"_box_");
  {rdelim}
  else
  {ldelim}
   tracking_carrier_id_arr = tracking_carrier_id.split("tracking_carrier_"+m_id+"_"+invoice_number+"_box_");
  {rdelim}

  var box_id = tracking_carrier_id_arr[1];

  var tracking_shipping_id;
  if (invoice_number == 0){ldelim}
    tracking_shipping_id = "tracking_shipping_"+m_id+"_box_"+box_id;
  {rdelim}
  else
  {ldelim}
    tracking_shipping_id = "tracking_shipping_"+m_id+"_"+invoice_number+"_box_"+box_id;
  {rdelim}

  $("#"+tracking_shipping_id).empty();
  var carrier_id_in_arr = 0;
  {foreach from=$tracking_links item=link_info key=linkid}

    carrier_id_in_arr = parseInt({$link_info.carrier_id});
    
    if (carrier_id == carrier_id_in_arr){ldelim}

      $("#"+tracking_shipping_id).append($('<option value="{$linkid}">{$link_info.shipping|escape}</option>'));

    {rdelim}

  {/foreach}

{rdelim}


{literal}
//function func_check_dc_statuses(m_id)
function func_check_dc_statuses(){

var dc_status;

{/literal}
{foreach from=$order.shipping_groups item=v key=m_id}

{if $v.all_distributor_info.submit_to_operator eq "through_distributor_website"}
  {literal}
  func_check_for_pending_order_message1('{/literal}{$m_id}{literal}');
  {/literal}

  {if $v.order_entry_flag eq "Y"}
    {literal}
    func_check_for_pending_order_message2('{/literal}{$m_id}{literal}');
    {/literal}
  {/if}

{/if}

{literal}

  dc_status = $('#groups_dc_status_{/literal}{$m_id}{literal}').val();

  if (dc_status == "S" || dc_status == "L" || dc_status == "G" || dc_status == "C"){
    $("#tracking_number_tr_id_{/literal}{$m_id}{literal}").show();
  } else {
    $("#tracking_number_tr_id_{/literal}{$m_id}{literal}").hide();
  }

{/literal}
{/foreach}
{literal}

/*
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

/*
function func_check_additional_shipping_status(m_id){

  var additional_shipping_status = $("#additional_shipping_status_"+m_id).val(); 

  if (additional_shipping_status == "P"){
    $("#additional_vt_info_"+m_id).show();
  }
  else {
    $("#additional_vt_info_"+m_id).hide();
  }
}
*/

function func_check_cb_status(m_id){

  var cb_status = $("#groups_cb_status_"+m_id).val(); 

  if (cb_status == "P" || cb_status == "3" || cb_status == "V" || cb_status == "H" || cb_status == "R"){
    $("#groups_shipping_cost_net_"+m_id).prop("readonly",true);
  } else {
    $("#groups_shipping_cost_net_"+m_id).prop("readonly",false);
  }

}

function func_check_cb_statuses(){

/*
  var all_cb_status_eq_P = true;
  var cb_status_eq_P_found = false;

  var all_cb_status_eq_AP = true;
  var cb_status_eq_AP_found = false;

  var all_cb_status_eq_3 = true;
  var cb_status_eq_3_found = false;

  var all_cb_status_eq_V = true;
  var cb_status_eq_V_found = false;

  var all_cb_status_eq_H = true;
  var cb_status_eq_H_found = false;

  var all_cb_status_eq_R = true;
  var cb_status_eq_R_found = false;
*/

  var cb_status;

{/literal}
{foreach from=$order.shipping_groups item=v key=m_id}

{if $v.all_distributor_info.submit_to_operator eq "through_distributor_website"}
  {literal}
  func_check_for_pending_order_message1('{/literal}{$m_id}{literal}');
  {/literal}

  {if $v.order_entry_flag eq "Y"}
    {literal}
    func_check_for_pending_order_message2('{/literal}{$m_id}{literal}');
    {/literal}
  {/if}

{/if}

{literal}

  cb_status = $('#groups_cb_status_{/literal}{$m_id}{literal}').val();

  if (document.getElementById('refund_group_{/literal}{$m_id}{literal}')){
    if (cb_status == "3" || cb_status == "V"){
      $("#refund_group_{/literal}{$m_id}{literal}").show();
    } else {
      $("#refund_group_{/literal}{$m_id}{literal}").hide();
    }
  }

/*
  if (cb_status == "P"){
    cb_status_eq_P_found = true;
  } else {
    all_cb_status_eq_P = false;
  }

  if (cb_status == "AP"){
    cb_status_eq_AP_found = true;
  } else {
    all_cb_status_eq_AP = false;
  }

  if (cb_status == "3"){
    cb_status_eq_3_found = true;
  } else {
    all_cb_status_eq_3 = false;
  }

  if (cb_status == "V"){
    cb_status_eq_V_found = true;
  } else {
    all_cb_status_eq_V = false;
  }

  if (cb_status == "H"){
    cb_status_eq_H_found = true;
  } else {
    all_cb_status_eq_H = false;
  }

  if (cb_status == "R"){
    cb_status_eq_R_found = true;
  } else {
    all_cb_status_eq_R = false;
  }
*/

  if (cb_status == "O"){
    $("#po_status_{/literal}{$m_id}{literal}_tr").show();
  } else {
    $("#po_status_{/literal}{$m_id}{literal}_tr").hide();
  }

{/literal}
{/foreach}
{literal}

/*
  if ((cb_status_eq_P_found == true && all_cb_status_eq_P == true) || (cb_status_eq_AP_found == true && all_cb_status_eq_AP == true)){
    $("#vt_info").show();
  } else {
    $("#vt_info").hide();
  }
*/

/*
  if (
      (cb_status_eq_P_found == true && all_cb_status_eq_P == true) || 
      (cb_status_eq_3_found == true && all_cb_status_eq_3 == true) || 
      (cb_status_eq_V_found == true && all_cb_status_eq_V == true) || 
      (cb_status_eq_H_found == true && all_cb_status_eq_H == true) || 
      (cb_status_eq_R_found == true && all_cb_status_eq_R == true)
  ) {
    for (var i = 0; i < 20; i++) { 
      if (document.getElementById('sku_add_to_order_box_'+i)){
        $("#sku_add_to_order_box_"+i).prop("disabled",true);
      }
    }
  } else {
    for (var i = 0; i < 20; i++) { 
      if (document.getElementById('sku_add_to_order_box_'+i)){
        $("#sku_add_to_order_box_"+i).prop("disabled",false);
      }
    }
  }
*/

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

       if (cb_status != "P" && cb_status != "V" && cb_status != "H" && cb_status != "3" && cb_status != "AP"){
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
  <td width="*">FBA<br />qty</td>
  <td width="5%">BO/<br />DROPPED</td>
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
  <td width="*"></td>
  <td width="5%"></td>
  <td width="7%"></td>
  <td width="7%" nowrap="nowrap"><font style="font-size: 9px;">Cost to us</font></td>
  <td width="7%"></td>
{*  <td width="7%"></td> *}
  <td width="7%" nowrap="nowrap"><font style="font-size: 9px;">Cost to us</font></td>
  {if !$static}<td width="5%">{else}<td>{/if}</td>
</tr>

{foreach from=$order.shipping_groups item=v key=m_id}
{if $m_id gt 0}
    <tr class="distributor-totals-line">
        <td>
            <a target="_blank" style="color: green;"
               href="manufacturers.php?manufacturerid={$m_id}&distributor_section=3">{$v.group_name}</a>
            {if $order_manufacturers[$m_id].d_shipping_methods_usps eq "Y"}
                <span style="color: #000000; font-weight: normal;">ships by USPS</span>
            {/if}
        </td>
        <td>
            <table cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td width="*">
                        {$v.code}
                    </td>
                    {if $v.all_distributor_info.d_specific_instructions ne ""}
                        <td align="right" width="5" nowrap="nowrap">
                            <div>
                                <a onclick="javascript: $('#d_specific_instructions_note_{$m_id}').toggle();"
                                   style="color: blue; border-bottom:1px dotted; text-decoration: none;"
                                   href="javascript: void(0);">Dx&nbsp;notes</a>

                                <div id="d_specific_instructions_note_{$m_id}" class="cidev_NoteBox"
                                     style="display: none; margin-left: 0px; color: #550000; text-align: left; border: 1px solid #ff6600;">
                                    {$v.all_distributor_info.d_specific_instructions}
                                </div>
                            </div>

                        </td>
                    {/if}
                </tr>
            </table>
        </td>
        <td colspan="5">
            {if $order_manufacturers[$m_id].d_link_to_order_distributors_website ne ""}
                <a style="color: #3A3AFF; font-weight: normal;"
                   href='{$order_manufacturers[$m_id].d_link_to_order_distributors_website}' target="_blank">Order on
                    distributor's website</a>
            {/if}
        </td>
        <td align="right">
            <a class="group_total_link" href="#">{include file="currency2.tpl" value=$v.total.net}</a>
        </td>
        <td align="right">{include file="currency2.tpl" value=$v.total.gst hide_zero='Y'}</td>
        {*  <td align="right">{include file="currency2.tpl" value=$v.total.pst hide_zero='Y'}</td> *}
        <td align="right">
            <a class="group_total_link" href="#">{include file="currency2.tpl" value=$v.total.gross}</a>
        </td>
        <td>
            {if $v.empty_products_list eq "Y"}
                <input type="checkbox" value="Y" name="distributors_to_delete[{$m_id}][delete]"
                {if $order.amazonorderid ne "" || $v.allow_dispatch_off_working_hours_functionality_enabled eq "Y"}disabled="disabled"{/if} />{else}&nbsp;{/if}
        </td>
    </tr>
    <tr class="group_total_price_row">
        <td><b>Dx Totals</b></td>
        <td></td>
        <td></td>
        <td align="center"><b>{$v.oOrderGroup->getTotalProductAmount()}</b></td>
        <td></td>
        <td></td>
        <td></td>
        <td><div style="BACKGROUND-COLOR: #cccccc; color: #000000" align="right">{include file="currency2.tpl" value=$v.oOrderGroup->getTotalProductPrice()}</div></td>
        <td></td>
        <td><div style="BACKGROUND-COLOR: #cccccc; color: #000000" align="right">{include file="currency2.tpl" value=$v.oOrderGroup->getTotalProductPrice()}</td>
        <td></td>
    </tr>
    <tr class="group_total_price_row">
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td><div style="BACKGROUND-COLOR: #FFD44C; color: #000000" align="right">{include file="currency2.tpl" value=$v.oOrderGroup->getTotalCostToUs()}</div></td>
        <td></td>
        <td><div style="BACKGROUND-COLOR: #FFD44C; color: #000000" align="right">{include file="currency2.tpl" value=$v.oOrderGroup->getTotalCostToUs()}</div></td>
        <td></td>
    </tr>

{assign var="GROUP_cost_to_us" value="0"}

{foreach from=$v.products item=product key=prod_num}
<tr{cycle values=", class='TableSubHead'" name="cycle_`$m_id`"}>
  <td>
    <a href="{$product.oProduct->getUrl()}{if $cats[$product.productid]}&cat={$cats[$product.productid]}{/if}" title="" target="_blank">{$product.product}</a>
    {assign var='oHTMLShot' value = $product.oProduct->getHTMLShot($order.orderid)}
    {if (!empty($oHTMLShot) && $oHTMLShot->getId())}
      <a title="View HTML-Shot" target="_blanks" style="float:right; margin-top:3px;" href="/admin/view_html_shot.php?id={$oHTMLShot->getId()}" class="html-shot-view">
        <img src="{$ImagesDir}/html-shot.png" />
      </a>
    {/if}
{* --------------------- *}
    {if $product.orig_product_classes ne ""}

      {assign var="refunded_option_found" value="N"}
      {if $order.refund_groups[$m_id].products ne ""}
        {foreach from=$order.refund_groups[$m_id].products item=product_ref key=prod_num_ref}

          {if $product_ref.itemid eq $product.itemid}
            {assign var="refunded_option_found" value="Y"}
          {/if}

        {/foreach}
      {/if}


      {foreach from=$product.orig_product_classes item=item key=key}
        {if $item.options ne ""}
          <br /> {$item.classtext}
          <select name="items[{$product.itemid}][classid_optionid][{$item.classid}]" {if $refunded_option_found eq "Y" || $order.amazonorderid ne "" || $v.allow_dispatch_off_working_hours_functionality_enabled eq "Y"}disabled="disabled"{/if}>
              {if (isset($product.extra_data.product_options[$item.classid].option_name) && !$product.extra_data.product_options[$item.classid].optionid|array_key_exists:$item.options)}
                  <option value="{$product.extra_data.product_options[$item.classid].optionid}" selected="selected">{$product.extra_data.product_options[$item.classid].option_name}</option>
              {/if}
          {foreach from=$item.options key=optionid item=option_values}
              {assign var="tmp_optionid_key" value=`$option_values.classid`}
              {if $product.product_options[$tmp_optionid_key]}
                  {assign var="tmp_optionid" value=`$product.product_options[$tmp_optionid_key].optionid`}
              {/if}
                <option value="{$optionid}"
                  {if $tmp_optionid eq $optionid}
                    selected="selected"
                  {/if}
                >{$option_values.option_name}</option>

          {/foreach}
          </select>
        {elseif $item.is_modifier eq "T"}
          <br /> {$item.classtext}
          <input type="text" name="items[{$product.itemid}][classid_optionid][{$item.classid}]" {if $refunded_option_found eq "Y" || $order.amazonorderid ne "" || $v.allow_dispatch_off_working_hours_functionality_enabled eq "Y"}readonly="readonly"{/if} value="{$product.product_options[$item.classid].option_name}">
        {elseif $product.product_options_txt ne ""}
          <br />Options: {$product.product_options_txt}
        {/if}
      {/foreach}
    {/if}
{* --------------------- *}
  </td>
  <td>
    {if $current_membership_flag ne 'FS'}<a href="{$product.links.admin}" title="" target="_blank">{$product.productcode}</a>{else}{$product.productcode}{/if}
    {if $order_manufacturers[$m_id].d_website_search_for_sku_url ne ""}<br />
      <a style="color: #3A3AFF;" href='{$product.oProduct->getProductURLOnDistributorWebSite()}' target="_blank">{$product.oProduct->getMPN()}</a>
    {/if}
    {if $product.verification_statusid == 3}
        <img title="This product is verified" style="float: right;" src="{$SkinDir}/images/green-verify.png" />
    {/if}

  </td>
  <td align="right">{if !$static}<input type="text" size="8" name="items[{$product.itemid}][price]" value="{$product.price|price_format}" {if $order.amazonorderid ne "" || $v.allow_dispatch_off_working_hours_functionality_enabled eq "Y"}readonly="readonly"{/if} />{else}{include file="currency2.tpl" value=$product.price|price_format}{/if}

{* --- *}
{math equation="x+y" x=$GROUP_cost_to_us y=$product.cost_to_us assign="GROUP_cost_to_us"}

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
  <td align="right" {* valign="top" *}>{if !$static}<input type="text" size="5" id="items_amount_{$m_id}_{$product.itemid}" name="items[{$product.itemid}][amount]" value="{$product.amount}" {* {if $v.dc_status eq 'C' || $v.dc_status eq 'L' || $v.dc_status eq 'S'}readonly="readonly"{/if} *} {if $order.amazonorderid ne "" || $v.allow_dispatch_off_working_hours_functionality_enabled eq "Y"}readonly="readonly"{/if} />{else}{$product.amount}{/if}</td>

  <td align="center">{$product.oProduct->getAmazonFBAAvail()}</td>

  <td align="right" {* valign="top" *}>
{if !$static}
  {if $v.dc_status eq 'K' || $v.dc_status eq 'E'}
    {$product.back}
    <input type="hidden" name="items[{$product.itemid}][back]" value="{$product.back}" {if $order.amazonorderid ne "" || $v.allow_dispatch_off_working_hours_functionality_enabled eq "Y"}readonly="readonly"{/if} />
  {else}
    <input type="text" size="5" name="items[{$product.itemid}][back]" value="{$product.back}" {if $order.amazonorderid ne "" || $v.allow_dispatch_off_working_hours_functionality_enabled eq "Y"}readonly="readonly"{/if} />
  {/if}
{else}
  {$product.back}
{/if}

{if $product.dropped eq "Y"}
  <br />Dropped
{/if}
  </td>

{if !($order.amazonorderid ne "" || $v.allow_dispatch_off_working_hours_functionality_enabled eq "Y")}

<script type="text/javascript">
<!--
{literal}
  $(function() {
    $("#eta_date_mm_dd_yyyy_{/literal}{$product.itemid}{literal}").datepicker();
  });
{/literal}
-->
</script>

{/if}

  <input type="hidden" name="items[{$product.itemid}][productid]" value="{$product.productid}" />
  <td align="right" {* valign="top" *}>
{if !$static}

  {if $v.dc_status eq 'K' || $v.dc_status eq 'E'}
  {$product.eta_date_mm_dd_yyyy|date_format:'%m/%d/%Y'}
  <input id="eta_date_mm_dd_yyyy_{$product.itemid}" type="hidden" name="items[{$product.itemid}][eta_date_mm_dd_yyyy]" value="{$product.eta_date_mm_dd_yyyy|date_format:'%m/%d/%Y'}" />
  {else}
  <input id="eta_date_mm_dd_yyyy_{$product.itemid}" type="text" size="9" style="width: 98%;" name="items[{$product.itemid}][eta_date_mm_dd_yyyy]" value="{if $product.eta_date_mm_dd_yyyy ne "0"}{$product.eta_date_mm_dd_yyyy|date_format:'%m/%d/%Y'}{/if}" {if $order.amazonorderid ne "" || $v.allow_dispatch_off_working_hours_functionality_enabled eq "Y"}readonly="readonly"{/if} />
  {/if}
{else}
  {$product.eta_date_mm_dd_yyyy|date_format:'%m/%d/%Y'}
{/if}
  </td>

  <td align="right">{include file="currency2.tpl" value=$product.oOrderDetail->getTotalProductPrice()}

{* --- *}
<div style="BACKGROUND-COLOR: #FFD44C; color: #000000" align="right">
{include file="currency2.tpl" value=$product.oOrderDetail->getCostToUs()}
</div>
{* --- *}
  </td>
  <td align="right">
{if $product.extra_data ne ""}
 {include file="currency2.tpl" value=$product.extra_data.taxes.GST.tax_value+$product.extra_data.taxes.HST.tax_value hide_zero='Y'}
{/if}
  </td>
{*  <td align="right">{include file="currency2.tpl" value=$product.extra_data.taxes.PST.tax_value hide_zero='Y'}</td> *}
  <td align="right">

{include file="currency2.tpl" value=$product.oOrderDetail->getTotalProductPrice()}

{* --- *}
<div style="BACKGROUND-COLOR: #FFD44C; color: #000000" align="right">
{include file="currency2.tpl" value=$product.oOrderDetail->getCostToUs()}
</div>
{* --- *}

  </td>
  <td align="center">{if !$static}<input type="checkbox" value="Y" name="items[{$product.itemid}][delete]" {if $order.amazonorderid ne "" || $v.allow_dispatch_off_working_hours_functionality_enabled eq "Y"}disabled="disabled"{/if} />{else}&nbsp;{/if}</td>
</tr>
{/foreach}
    {assign var="oOrderGroup" value=$v.oOrderGroup}
    {assign var="oOrderShipping" value= $oOrderGroup->getShippingInstance()}
<tr{cycle values=", class='TableSubHead'" name="cycle_`$m_id`"}>
  <td nowrap="nowrap">
    <div>
        <p>Carrier: {if $v.shipping_code ne ""}{$v.shipping_code}{else}Flat rate{/if}</p>
        <p>Customer's choice: <input style="width:92px" type="text" maxlength="255" name="groups[{$m_id}][shipping]" value="{$v.shipping|trademark:''}"/>
        </p>
        <p>Method:
            {if !$static}
                {if ($v.real_shipping_method eq '')}
                    {assign var="shipping_method" value=$oOrderShipping->getName()}
                {else}
                    {assign var="shipping_method" value=$v.real_shipping_method}
                {/if}

                <input type="text" maxlength="255" name="groups[{$m_id}][real_shipping_method]" value="{$shipping_method|trademark:''}"
                       {if $order.amazonorderid ne "" || $v.allow_dispatch_off_working_hours_functionality_enabled eq "Y"}readonly="readonly"{/if}
                />
            {else}
                {$v.real_shipping_method}
            {/if}

            {if ($v.real_shipping_method ne '') and ($v.real_shipping_method != $oOrderShipping->getName())}
                <span style="margin-left: 50px; display: block;">{$oOrderShipping->getName()}</span>
            {/if}
        </p>
    </div>
  </td>

  {assign var="oOrder" value=$oOrderGroup->getOrderInstance()}
  {if ((($oOrder->isOrderAmazon() == false) || ($oOrder->isOrderAmazon() && $oOrder->amazon_fulfillment_channel == 'MFN'))
        && $oOrder->fraud_status == 'C'
        && ($oOrderGroup->cb_status == 'P' || $oOrderGroup->cb_status =='O' || ($oOrderGroup->cb_status =='AP' && ($order_transactions_totals.authorized_PLUS_captured_totals == $oOrder->getOrderTotalGross() || $oOrder->getAmazonChanell() == 'MFN')))
        && ($oOrderGroup->dc_status == 'E' || $oOrderGroup->dc_status == 'M' || $oOrderGroup->dc_status == 'T' || $oOrderGroup->dc_status == 'K')
        && $oOrderGroup->checkFBAProductsAvailToShipping()
        && $oOrderGroup->amz_fullfilment_order_placed !='Y'
  )}
    <td colspan="2" align="center">
      <input data-orderid="{$oOrderGroup->getOrderId()}" data-manufacturerid="{$oOrderGroup->getManufacturerId()}" id="submit_amazon_shipment" name="submit_amazon_shipment" type="button"  value="{if ($oOrderGroup->getField('cb_status') =='AP' && $order_transactions_totals.authorized_PLUS_captured_totals == $oOrder->getOrderTotalGross())}Capture & {/if}Ship now by Amazon" />
      <select {if $oOrderShipping->isAmazonShipping()} disabled="disabled" {/if}style="margin-top: 7px; width: 88%;" name="amazon_shipping_method_select" id="amazon_shipping_method_select">
        <option value=""></option>
        {html_options options=$aAmazonShippingMethods selected=$oOrderGroup->getShippingMethodName()}
      </select>

    <td colspan="4" style="vertical-align: top;">
      <input style="margin-bottom: 5px;" id="submit_amazon_shipment_with_notes" name="submit_amazon_shipment_with_notes" type="checkbox" />
      <label style="position:relative; top:-2px;" for="submit_amazon_shipment_with_notes">with customer notes</label> <br/>
      <textarea id="submit_amazon_shipment_notes" style="margin-left: 4px;width: 97%; height:26px;" name="submit_amazon_shipment_notes" type="text">{$oOrder->getOrderCustomerNotes()}</textarea>
    </td>
  {else}
  <td colspan="6">
    {if $v.tracking}
      {assign var="row_conter" value="0"}
      {foreach from=$v.tracking item=t}

        {assign var="current_carrier_id" value=$t.carrier_id}
        {math equation="x+1" x=$row_conter assign="row_conter"}

        <div id="tracknum_{$m_id}_{$row_conter}">
          {if $t.tracknum ne ""}
            <a href="{$tracking_links_carrier[$current_carrier_id].link|substitute:"tracknum":$t.tracknum}" target="_blank">Shipped{if $t.ship_date ne ""} on {$t.ship_date}{/if} by {$tracking_links_carrier[$current_carrier_id].carrier}{if $tracking_links[$t.linkid].shipping ne ""} {$tracking_links[$t.linkid].shipping}{/if}: {$t.tracknum}</a>
          {else}
            Shipped{if $t.ship_date ne ""} on {$t.ship_date}{/if} by {$tracking_links_carrier[$current_carrier_id].carrier}{if $tracking_links[$t.linkid].shipping ne ""} {$tracking_links[$t.linkid].shipping}{/if}: {$tracking_links_carrier[$current_carrier_id].link}
          {/if}

          <a href="javascript: void(0);" onclick="javascript: $('#tracknum_val_{$m_id}_{$row_conter}').val(''); $('#tracknum_link_{$m_id}_{$row_conter}').val(''); $('#tracknum_invoice_number_{$m_id}_{$row_conter}').val(''); $('#tracknum_carrier_id_{$m_id}_{$row_conter}').val(''); $('#tracknum_{$m_id}_{$row_conter}').hide();"><img src="{$ImagesDir}/minus.gif" /></a>

          <input type="hidden" name="tracknums[{$m_id}][{$row_conter}][tracknum]" value="{$t.tracknum}" id="tracknum_val_{$m_id}_{$row_conter}" />
          <input type="hidden" name="tracknums[{$m_id}][{$row_conter}][linkid]" value="{$t.linkid}" id="tracknum_link_{$m_id}_{$row_conter}" />
          <input type="hidden" name="tracknums[{$m_id}][{$row_conter}][invoice_number]" value="{$t.invoice_number|default:'1'}" id="tracknum_invoice_number_{$m_id}_{$row_conter}" />
          <input type="hidden" name="tracknums[{$m_id}][{$row_conter}][ship_date]" value="{$t.ship_date}" id="tracknum_ship_date_{$m_id}_{$row_conter}" />
          <input type="hidden" name="tracknums[{$m_id}][{$row_conter}][carrier_id]" value="{$t.carrier_id}" id="tracknum_carrier_id_{$m_id}_{$row_conter}" />

        </div>

      {/foreach}
    {/if}
  </td>
  {/if}
  <td align="right">
    {if !$static}
      <input type="hidden" name="groups[{$m_id}][shipping_cost_net_orig]" value="{$v.shipping_cost.net|price_format}" {if $order.amazonorderid ne "" || $v.allow_dispatch_off_working_hours_functionality_enabled eq "Y"}readonly="readonly"{/if} />
      <input id="groups_shipping_cost_net_{$m_id}" type="text" size="8" name="groups[{$m_id}][shipping_cost_net]" value="{$v.shipping_cost.net|price_format}" {* {if $v.cb_status eq 'P' || $v.cb_status eq '3' || $v.cb_status eq 'V' || $v.cb_status eq 'H' || $v.cb_status eq 'R'}readonly="readonly"{/if} *}  {if $order.amazonorderid ne "" || $v.allow_dispatch_off_working_hours_functionality_enabled eq "Y"}readonly="readonly"{/if} />
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
  <td colspan="7">

<div style="float: left;">
  {if $order.amazonorderid ne "" || $v.allow_dispatch_off_working_hours_functionality_enabled eq "Y"}
    <input type="hidden" name="groups[{$m_id}][shipping_value_selectbox]" id="shipping_value_selectbox_{$m_id}" value="{$v.shipping_value_selectbox}" />
  {/if}
  <select name="groups[{$m_id}][shipping_value_selectbox]" id="shipping_value_selectbox_{$m_id}" {if $order.amazonorderid ne "" || $v.allow_dispatch_off_working_hours_functionality_enabled eq "Y"}disabled="disabled"{/if} >
    <option value="actual_shipping_cost" {if $v.shipping_value_selectbox eq "actual_shipping_cost" || $v.shipping_value_selectbox eq ""} selected="selected"{/if}>Actual shipping cost (do NOT include drop-ship fee)</option>
    <option value="required_shipping_charge" {if $v.shipping_value_selectbox eq "required_shipping_charge"} selected="selected"{/if}>Required shipping charge from our website shipping quote</option>
  </select>
</div>

<div style="float: left;">


 {if $v.all_distributor_info.distributor_offers_free_shipping eq "on_orders_over" AND $GROUP_cost_to_us gt $v.all_distributor_info.free_shipping_on_orders_over_value}
 <div>
&nbsp;
<span style="color: #FF0000; font-weight: bold;">THIS ORDER QUALIFIES FOR FREE SHIPPING!</span>
 <div>
 {/if}

 <div>
&nbsp;
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
 </div>

</div>

  </td>
  <td align="right">
      <input id="actual_shipping_cost_net_{$m_id}" type="text" size="8" name="groups[{$m_id}][actual_shipping_cost_net]" value="{$v.actual_shipping_cost.net|price_format}" {if $v.allow_dispatch_off_working_hours_functionality_enabled eq "Y"}readonly="readonly"{/if}  />
  </td>
  <td align="right">
    <span id="cidev_actual_shipping_cost_gst_{$m_id}">{include file="currency2.tpl" value=$v.actual_shipping_cost.gst hide_zero='Y'}</span>
  </td>
  <td align="right">
    <span id="cidev_actual_shipping_cost_gross_{$m_id}">{include file="currency2.tpl" value=$v.actual_shipping_cost.gross}</span>
  </td>
  <td>&nbsp;</td>
</tr>


{* {if $order_manufacturers[$m_id].additional_shipping_status eq "W" && $v.actual_shipping_cost.net gt 0} *}
{if $order_manufacturers[$m_id].additional_shipping_charge gt 0 || $v.actual_shipping_cost.net gt 0}
<tr>
<td colspan="6"><B>Estimated profit</B></td>
<td></td>
<td colspan="2">
{*
{if $v.actual_shipping_cost.net gt 0}
<B>Estimated profit</B>
{/if}
*}
</td>
<td align="right"><B>{if $order_manufacturers[$m_id].estimated_profit_abs ne ""}<span style="color: #FF0000;">(${$order_manufacturers[$m_id].estimated_profit_abs|price_format})</span>{else}${$order_manufacturers[$m_id].estimated_profit|price_format}{/if}</B></td>
<td align="right"><B>{if $order_manufacturers[$m_id].estimated_profit_margin_percent_abs ne ""}<span style="color: #FF0000;">({$order_manufacturers[$m_id].estimated_profit_margin_percent_abs}%)</span>{else}{$order_manufacturers[$m_id].estimated_profit_margin_percent}%{/if}</B></td>
</tr>
{/if}


{if $order_manufacturers[$m_id].additional_shipping_charge gt 0}
<tr {cycle values=", class='TableSubHead'" name="cycle_`$m_id`"} style="BACKGROUND-COLOR: #FFD44C;">

<td colspan="2">
<span style="color: #FF0000; font-weight: bold;">Additional shipping required: ${$order_manufacturers[$m_id].additional_shipping_charge}</span>
</td>

<td colspan="4" align="right">
<B>Additional payment status:</B>
</td>

<td>
{if $additional_shipping_statuses ne ""}

{*
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
*}

{*
{if $order_manufacturers[$m_id].additional_shipping_status eq "W"}
<span style="color: red;">Waived</span>
{else}
  {if $order.amazonorderid ne "" || $v.allow_dispatch_off_working_hours_functionality_enabled eq "Y"}
<input type="hidden" name="groups[{$m_id}][additional_shipping_status]" id='additional_shipping_status_{$m_id}' value="{$v.additional_shipping_status}" />
  {/if}
<select name="groups[{$m_id}][additional_shipping_status]" id='additional_shipping_status_{$m_id}' {if $order.amazonorderid ne "" || $v.allow_dispatch_off_working_hours_functionality_enabled eq "Y"}disabled="disabled"{/if}>
{foreach from=$additional_shipping_statuses item=v_s key=k_s}
<option {if $v.additional_shipping_status eq $k_s}selected="selected"{/if} value="{$k_s}">{if $order_manufacturers[$m_id].cb_status eq "O" && $k_s eq "P"}Agreed{else}{$v_s}{/if}</option>
{/foreach}
</select>
{/if}
*}

  {if $order.amazonorderid ne "" || $v.allow_dispatch_off_working_hours_functionality_enabled eq "Y"}
<input type="hidden" name="groups[{$m_id}][additional_shipping_status]" id='additional_shipping_status_{$m_id}' value="{$v.additional_shipping_status}" />
  {/if}

  {if $v.additional_shipping_status eq "W"}
   <select name="groups[{$m_id}][additional_shipping_status]" id='additional_shipping_status_{$m_id}' {if $order.amazonorderid ne "" || $v.allow_dispatch_off_working_hours_functionality_enabled eq "Y"}disabled="disabled"{/if}>
    {foreach from=$additional_shipping_statuses item=v_s key=k_s}
     {if $k_s eq "U" || $k_s eq "W"}
      <option {if $v.additional_shipping_status eq $k_s}selected="selected"{/if} value="{if $k_s eq "U"}G{else}{$k_s}{/if}">{if $k_s eq "W"}<span style="color: red;">Waived</span>{else}{$v_s}{/if}</option>
     {/if}
    {/foreach}
   </select>
  {else}

   <select name="groups[{$m_id}][additional_shipping_status]" id='additional_shipping_status_{$m_id}' {if $order.amazonorderid ne "" || $v.allow_dispatch_off_working_hours_functionality_enabled eq "Y"}disabled="disabled"{/if} onchange="javascript: func_check_for_paypal_vt('{$m_id}');">
    {foreach from=$additional_shipping_statuses item=v_s key=k_s}
     {if $k_s ne "U"}
      <option {if $v.additional_shipping_status eq $k_s}selected="selected"{/if} value="{$k_s}">{if $order_manufacturers[$m_id].cb_status eq "O" && ($k_s eq "P" || $k_s eq "A")}Agreed{else}{$v_s}{/if}</option>
     {/if}
    {/foreach}
   </select>

 {/if}


{/if}
</td>

<td colspan="4">

</td>
</tr>

{*
 {if $all_vt_processors ne ""}
 <tr style="background-color: #F4CCCC; display: none;" id="additional_vt_info_{$m_id}" >
 <td colspan="11">
   <table>
     <tr>
       <td>
         <b>Payment method:</b><br />
  {if $order.amazonorderid ne "" || $v.allow_dispatch_off_working_hours_functionality_enabled eq "Y"}
<input type="hidden" name="groups[{$m_id}][additional_vt_paymentid]" id="additional_vt_paymentid_{$m_id}" value="{$v.additional_vt_paymentid}" />
  {/if}
         <select name="groups[{$m_id}][additional_vt_paymentid]" id="additional_vt_paymentid_{$m_id}" {if $order.amazonorderid ne "" || $v.allow_dispatch_off_working_hours_functionality_enabled eq "Y"}disabled="disabled"{/if}>
         <option value="0"></option>
         {foreach from=$all_vt_processors item=item_vt key=key_vt}
         <option {if $v.additional_vt_paymentid eq $item_vt.paymentid} selected="selected"{/if} value="{$item_vt.paymentid}">{$item_vt.payment_method}</option>
         {/foreach}
         </select>
       </td>
       <td width="20">&nbsp;</td>
       <td>
           <b>Virtual terminal transaction ID:</b><br />
           <input type="text" name="groups[{$m_id}][additional_transaction_id_link]" id="additional_transaction_id_link_{$m_id}" value="{$v.additional_transaction_id_link}" size="40" {if $order.amazonorderid ne "" || $v.allow_dispatch_off_working_hours_functionality_enabled eq "Y"}readonly="readonly"{/if} />
       </td>
       <td width="20">&nbsp;</td>
       <td>
           <b>AVS code:</b><br />
           <input type="text" name="groups[{$m_id}][additional_avs_code]" id="additional_avs_code_{$m_id}" value="{$v.additional_avs_code}" size="1" maxlength="1" {if $order.amazonorderid ne "" || $v.allow_dispatch_off_working_hours_functionality_enabled eq "Y"}readonly="readonly"{/if} />
       </td>
     </tr>
   </table>
 </td>
 </tr>
 {/if}
*}

{* {if $order_manufacturers[$m_id].additional_shipping_status eq "W" && $v.actual_shipping_cost.net gt 0} *}
{if $order_manufacturers[$m_id].additional_shipping_charge gt 0}
<tr>
<td colspan="6"><B>Estimated profit after additional payment</B></td>
<td colspan="3"></td>
<td align="right"><B>{if $order_manufacturers[$m_id].estimated_profit_after_additional_payment_abs ne ""}<span style="color: #FF0000;">(${$order_manufacturers[$m_id].estimated_profit_after_additional_payment_abs})</span>{else}${$order_manufacturers[$m_id].estimated_profit_after_additional_payment}{/if}</B></td>
<td align="right"><B>{if $order_manufacturers[$m_id].estimated_profit_margin_after_additional_payment_percent_abs ne ""}<span style="color: #FF0000;">({$order_manufacturers[$m_id].estimated_profit_margin_after_additional_payment_percent_abs}%)</span>{else}{$order_manufacturers[$m_id].estimated_profit_margin_after_additional_payment_percent}%{/if}</B></td>
</tr>
{/if}

{/if}

{* ----------------------- *}


<tr id="tracking_number_tr_id_{$m_id}" style="{if !($v.dc_status eq 'S' || $v.dc_status eq 'L' || $v.dc_status eq 'G' || $v.dc_status eq 'C')}display: none;{/if}">
<td colspan="9">
<script type="text/javascript">
<!--
multirowInputSets['track_{$m_id}'] = [];
multirowInputSets['track_{$m_id}'].noCloneContent = 1;
-->
</script>
<table cellpadding="0" cellspacing="0" border="0">
<tr>
  <td><B>Ship date:</B></td>
  <td><B>Carrier:</B></td>
  <td width="250"><B>Shipping method:</B></td>
	<td colspan="2"><b>Tracking number</b> (is put on the invoice)<b>:</b></td>
</tr>

<tr id="track_{$m_id}_tr">

  <td id="track_{$m_id}_box_3" style="padding-right: 5px;">
  <input type="text" id="tracking_ship_date_{$m_id}_box_0" name="groups[{$m_id}][tracking_ship_date][0]" value="" size="15" {* {if $v.allow_dispatch_off_working_hours_functionality_enabled eq "Y"}readonly="readonly" {/if} *} onclick="javascript: $(this).datepicker(); /*$(this).datepicker('option', 'dateFormat', 'MM d, yy'); */ $(this).datepicker('show');" onchange="javascript: $(this).datepicker('hide');" />
  </td>

  <td id="track_{$m_id}_box_4" style="padding-right: 10px;">
  <select id="tracking_carrier_{$m_id}_box_0" name="groups[{$m_id}][tracking_carrier][0]" {* {if $v.allow_dispatch_off_working_hours_functionality_enabled eq "Y"}disabled="disabled"{/if} *} onchange="func_set_tracking_shipping(this, '{$m_id}', '0');">
  <option value=""></option>
{foreach from=$tracking_links_carrier item=vvv key=carrier_id}
  <option value="{$carrier_id}">{$vvv.carrier}</option>
{/foreach}
  </select>
  </td>

	<td id="track_{$m_id}_box_1" style="padding-right: 10px;">
	<select id="tracking_shipping_{$m_id}_box_0" name="groups[{$m_id}][tracking_shipper][0]" {if $v.allow_dispatch_off_working_hours_functionality_enabled eq "Y"}disabled="disabled"{/if} style="width: 100%;">
	<option value="">select carrier</option>

{*
{foreach from=$tracking_links item=vvv key=linkid}
	<option value="{$linkid}">{$vvv.shipping}</option>
{/foreach}
*}

	</select>
	</td>
	<td id="track_{$m_id}_box_2" style="padding-right: 5px;">
	<input type="text" name="groups[{$m_id}][tracking_number][0]" value="" size="40" {if $v.allow_dispatch_off_working_hours_functionality_enabled eq "Y"}readonly="readonly" {/if} />
	</td>

	<td width="30">
{if !($v.allow_dispatch_off_working_hours_functionality_enabled eq "Y")}
{include file="buttons/multirow_add.tpl" mark="track_`$m_id`"}
{/if}
  </td>
</tr>

</table>

</td>

<td colspan="2" valign="top">
</td>
</tr>
{assign var=aOrderDetailWithRetailTrust value=$oOrderGroup->getOrderDetailsWithRetailTrust()}
{if ($aOrderDetailWithRetailTrust)}
<tr>
  <td colspan="7">
    {$lng.retail_trust_order_group_line_title}
  </td>
  <td align="right">{$oOrderGroup->getRetailTrustTotalNet()|price_format}</td>
  <td align="right"></td>
  <td align="right">{$oOrderGroup->getRetailTrustTotalGross()|price_format}</td>
</tr>
{/if}

{if $active_modules.Google_Checkout eq '' or $order.extra.goid eq ''}
<tr style="background-color: #d9ead3;">
  <td colspan="11">
    <table cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr>
      <td style="vertical-align: top; padding-right: 10px; padding-bottom: 4px;">

<script type="text/javascript">
<!--
{literal}
  $(function() {
    $("#groups_cb_status_{/literal}{$m_id}{literal}").change(function(){
      func_check_cb_statuses();

//      func_check_cb_status('{/literal}{$m_id}{literal}');
    });
  });
{/literal}
-->
</script>

        <b>{$lng.lbl_cust_bus_payment_status}:</b><br />

        {if $order.amazonorderid ne "" || $v.allow_dispatch_off_working_hours_functionality_enabled eq "Y" || ( ($v.cb_status eq "IO" || $v.cb_status eq "O") && $allowed_to_modify_cb_status_IO_O ne "Y")}
          <input type="hidden" name="groups[{$m_id}][cb_status]" id="groups_cb_status_{$m_id}" value="{$v.cb_status}" />
          {include file="main/order_status.tpl" status=$v.cb_status mode="select" name="groups[`$m_id`][cb_status]" status_type="CB" extra=" id='groups_cb_status_`$m_id`' disabled='disabled'"}
        {else}
          {include file="main/order_status.tpl" status=$v.cb_status mode="select" name="groups[`$m_id`][cb_status]" status_type="CB" extra=" id='groups_cb_status_`$m_id`'"}
        {/if}


<br />
<B>Payment date:</B>&nbsp;{if $v.paid_date eq "0"}<span style="color: red;">Not yet paid</span>{else}{$v.paid_date|date_format:'%d-%b-%Y&nbsp; %H:%M'}{/if}

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
/*      func_check_dc_statuses('{/literal}{$m_id}{literal}'); */
      func_check_dc_statuses();
    });
  });
{/literal}
-->
</script>


        {include file="main/order_status.tpl" status=$v.dc_status mode="select" name="groups[`$m_id`][dc_status]" status_type="DC" hide_pending_availability_check_status=$hide_pending_availability_check_status hide_dispatched_status=$hide_dispatched_status extra=" id='groups_dc_status_`$m_id`' "}

<br />
<B>Dispatch date:</B>&nbsp;{if $v.dc_dispatched_time eq "0"}<span style="color: red;">Not yet dispatched</span>{else}{$v.dc_dispatched_time|date_format:'%d-%b-%Y&nbsp; %H:%M'}{/if}

<br />
<B>Received by distributor date:</B>&nbsp;{if $v.dc_received_by_distributor_time eq "0"}<span style="color: red;"> </span>{else}{$v.dc_received_by_distributor_time|date_format:'%d-%b-%Y&nbsp; %H:%M'}{/if}


      </td>
      <td style="vertical-align: top; padding-right: 10px; padding-bottom: 4px;">
{*
        <b>{$lng.lbl_bus_distr_payment_status}:</b><br />

        {if $order.amazonorderid ne "" || $v.allow_dispatch_off_working_hours_functionality_enabled eq "Y"}
          <input type="hidden" name="groups[{$m_id}][bd_status]" id="groups_bd_status_{$m_id}" value="{$v.bd_status}" />
          {include file="main/order_status.tpl" status=$v.bd_status mode="select" name="groups[`$m_id`][bd_status]" status_type="BD" extra=" disabled='disabled'"}
        {else}
          {include file="main/order_status.tpl" status=$v.bd_status mode="select" name="groups[`$m_id`][bd_status]" status_type="BD"}
        {/if}
*}

<B>Business to distributor invoice status:</B><br />
{if $v.invoices ne ""}
{foreach from=$v.invoices item=item_invoice key=key_invoice}

I-{$key_invoice}: {$invoice_memo_statuses[$item_invoice.status]}<br />

{/foreach}
{else}
{$invoice_memo_statuses.N}<br />
{/if}

<br />
<B>Business to distributor credit memo status:</B><br />
{if $v.memos ne ""}
{foreach from=$v.memos item=item_memos key=key_memos}

C-{$key_memos}: {$invoice_memo_statuses[$item_memos.status]}<br />

{/foreach}
{else}
{$invoice_memo_statuses.N}
{/if}


      </td>
    </tr>
    </table>
  </td>
</tr>

{* --- *}
<tr id="po_status_{$m_id}_tr" {if $v.cb_status ne "O"}style="display: none;"{else}style="background-color: #B6D7A8;"{/if}>
<td colspan="11">
        <b>Check transit status:</b><br />
        {include file="main/order_status.tpl" status=$v.po_status mode="select" name="groups[`$m_id`][po_status]" status_type="PO" extra=" id='groups_po_status_`$m_id`' "}
</td>
</tr>
{* --- *}

{/if}

  {if ($v.cb_status eq "AP" || $v.cb_status eq "P") && $v.dc_status eq "E" && $v.all_distributor_info.submit_to_operator eq "through_distributor_website"}
    {if ($order.customer_notes ne "")}
      <tr>
        <td colspan="11">
          {include file="dialog.tpl" title="Customer notes" content=$order.customer_notes extra='width="100%"'}
        </td>
      </tr>
    {/if}
  {/if}

<tr id="pending_order_message1_{$m_id}" style='background-color: #F4CCCC; {if ($v.cb_status eq "AP" || $v.cb_status eq "P") && $v.dc_status eq "E" && $v.all_distributor_info.submit_to_operator eq "through_distributor_website"} {else} display: none; {/if}'>
<td colspan="11">{$lng.lbl_pending_order_message1}</td>
</tr>

<tr id="pending_order_message2_{$m_id}" style='background-color: #F4CCCC; {if $v.cb_status eq "P" && $v.dc_status eq "L" && $v.all_distributor_info.submit_to_operator eq "through_distributor_website" && $v.order_entry_flag eq "Y"} {else} display: none; {/if}'>
<td colspan="11">
{$lng.lbl_pending_order_message2}

<input type="button" value="Done" onclick="javascript: $('#ordereditform_mode').val('pending_order_message2_done_clicked'); $('#ordereditform_mid').val('{$m_id}'); this.form.submit();" />

</td>
</tr>


<tr><td colspan="11"><hr /></td></tr>
{include file="main/refund_group.tpl" mid=$m_id group=$order.shipping_groups[$m_id]}
{/if}
{/foreach}
  {if ($oOrder)}
    {if $oOrder->getOrderDetailsWithRetailTrust()}
      <tr class="TableHead">
        <td width="35%">{$lng.lbl_product}</td>
        <td width="17%">{$lng.lbl_sku}</td>
        <td></td>
        <td>{$lng.lbl_qty}</td>
        <td colspan="3"></td>
        <td width="7%">{$lng.lbl_price}</td>
        <td width="7%"></td>
        <td width="7%">{$lng.lbl_gross}</td>
        <td width="7%">{$lng.lbl_remove}</td>
      </tr>


      <tr class="distributor-totals-line">
        <td colspan="7"></td>
        <td align="right">{$oOrder->getOrderRetailTrustPrice()|price_format}</td>
        <td></td>
        <td align="right">{$oOrder->getOrderRetailTrustGross()|price_format}</td>
        <td></td>
      </tr>

      {assign var=aOrderGroups value=$oOrder->getOrderGroups()}
      {foreach from=$aOrderGroups item=oOrderGroupL}
        {assign var=oManufacturer value=$oOrderGroupL->getManufacturerEntity()}
        {assign var=aRetailTrustDetails value=$oOrderGroupL->getOrderDetailsWithRetailTrust()}
        {if $aRetailTrustDetails}
        <tr class="distributor-totals-line">
          <td>
            <a href="{$oManufacturer->getAdminUrl()}" target="_blank" style="color: green;">{$oManufacturer->getManufacturerName()}</a>
          </td>
          <td>
            {$oManufacturer->getManufacturerCode()}
          </td>
        </tr>
        {assign var=aRetailTrustDetails value=$oOrderGroupL->getOrderDetailsWithRetailTrust()}

        {foreach from=$aRetailTrustDetails item=oRetailTrustDetail}
          {assign var=oOrderDetailProduct value=$oRetailTrustDetail->getOrderDetailProduct()}
        <tr {cycle values=", class='TableSubHead'" name="cycle_totals"}>
          <td>
            <a href="{$oOrderDetailProduct->getURL()}">{$oOrderDetailProduct->getProductName()}</a>
          </td>
          <td>
            <a href="{$oOrderDetailProduct->getAdminUrl()}">{$oOrderDetailProduct->getSKURetailTrust()}</a>
          </td>
          <td></td>
          <td align="center">
            {$oRetailTrustDetail->getAmount()}
          </td>
          <td colspan="3"></td>
          <td align="right">
            {$oRetailTrustDetail->getRetailTrustPrice()|price_format}
          </td>
          <td></td>
          <td align="right">
            {$oRetailTrustDetail->getRetailTrustGross()|price_format}
          </td>
          <td>
            {if $oOrder->getOrderStatusDC() != 'L' && $oOrder->getOrderStatusDC() != 'E' && $oOrder->getOrderStatusDC() != 'DP' && $oOrder->getOrderStatusDC() != 'C' && $oOrder->getOrderStatusDC() != 'G'}
              <input type="checkbox" value="Y" name="retail_trust_to_delete[{$oRetailTrustDetail->getOrderDetailId()}]" />
            {/if}
          </td>
        </tr>
        {/foreach}
        {/if}
      {/foreach}
      <tr>
        <td colspan="11">
          <hr/>
        </td>
      </tr>
    {/if}
  {/if}

<tr{cycle values=", class='TableSubHead'" name="cycle_totals"}>
  <td>Total Product Price

<div style="BACKGROUND-COLOR: #FFD44C; color: #000000" align="left">
Total Product Cost to us
</div>

  </td>
  <td colspan="6">&nbsp;</td>
  <td align="right">{include file="currency2.tpl" value=$oOrder->getProductPriceNet()}

{* --- *}
<div style="BACKGROUND-COLOR: #FFD44C; color: #000000" align="right">
{include file="currency2.tpl" value=$oOrder->getOrderCostToUs()|price_format}
</div>
{* --- *}

  </td>
  <td align="right">{include file="currency2.tpl" value=$oOrder->getProductPriceHSTPST() hide_zero='Y'}</td>
{*   <td align="right">{include file="currency2.tpl" value=$order.extra.product_total.pst hide_zero='Y'}</td> *}
  <td align="right">{include file="currency2.tpl" value=$oOrder->getProductPriceGross()}

{* --- *}
<div style="BACKGROUND-COLOR: #FFD44C; color: #000000" align="right">
{include file="currency2.tpl" value=$oOrder->getOrderCostToUs()|price_format}
</div>
{* --- *}

  </td>
  <td>&nbsp;</td>
</tr>
{if $oOrder->getOrderRetailTrustPrice() != 0}
<tr{cycle values=", class='TableSubHead'" name="cycle_totals"}>
  <td>
      Retail Trust Total
  </td>
  <td colspan="6">&nbsp;</td>
  <td align="right">{$oOrder->getOrderRetailTrustPrice()|price_format}</td>
  <td></td>
  <td align="right">{$oOrder->getOrderRetailTrustGross()|price_format}</td>
</tr>
{/if}
<tr{cycle values=", class='TableSubHead'" name="cycle_totals"}>
  <td>Total Shipping Charge</td>
  <td colspan="6">&nbsp;</td>
  <td align="right">{include file="currency2.tpl" value=$oOrder->getOrderShippingNet() hide_zero='Y'}</td>
  <td align="right">{include file="currency2.tpl" value=$oOrder->getOrderShippingHST() hide_zero='Y'}</td>
{*  <td align="right">{include file="currency2.tpl" value=$order.extra.shipping_total.pst hide_zero='Y'}</td> *}
  <td align="right">{include file="currency2.tpl" value=$oOrder->getOrderShippingGross() hide_zero='Y'}</td>
  <td>&nbsp;</td>
</tr>

{if $order.coupon and $order.coupon_type eq "free_ship"}
{$smarty.capture.coup_saving}
{/if}

{if $order.additional_fee ne ""}
{foreach from=$order.additional_fee item=v_f key=k_f}
<tr{cycle values=", class='TableSubHead'" name="cycle_totals"}>
  <td><input type="text" name="edit_additional_fee_name[{$v_f.id}][additional_fee_name]" value="{$v_f.additional_fee_name}" size="16" style="width: 99%;" {if $order.amazonorderid ne ""}readonly="readonly"{/if} /></td>
  <td colspan="6">&nbsp;</td>
  <td align="right"><input type="text" name="edit_additional_fee_name[{$v_f.id}][additional_fee_value]" value="{$v_f.additional_fee_value}" size="8" {if $order.amazonorderid ne ""}readonly="readonly"{/if} /></td>
  <td>&nbsp;</td>
  <td align="right">{$v_f.additional_fee_value}</td>
  <td><input type="checkbox" value="Y" name="delete_additional_fee[{$v_f.id}]" {if $order.amazonorderid ne ""}disabled="disabled"{/if} /></td>
</tr>
{/foreach}
{/if}

<tr{cycle values=", class='TableSubHead'" name="cycle_totals"} style="font-weight: bold;">
  <td style="font-size: 12px;">{$lng.lbl_grand_total}</td>
  <td colspan="6">&nbsp;</td>
  <td align="right" style="font-size: 12px;">{include file="currency2.tpl" value=$oOrder->getOrderTotalNet()}</td>
  <td align="right" style="font-size: 12px;">{include file="currency2.tpl" value=$oOrder->getOrderTotalHST() hide_zero='Y'}</td>
{*  <td align="right" style="font-size: 12px;">{include file="currency2.tpl" value=$order.extra.total.pst hide_zero='Y'}</td> *}
  <td align="right" style="font-size: 12px;">{include file="currency2.tpl" value=$oOrder->getOrderTotalGross()}</td>
  <td>&nbsp;</td>
</tr>
<tr>
    <td colspan="10">
        {include file="admin/main/transactions_summary.tpl" order_store=$order_store}
    </td>
</tr>

<tr>
<td colspan="11">
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
  <td width="10%">retail trust</td>
  <td width="5%" style="background-color: #fff2cc;">Qty</td>
  <td width="*"></td>
  <td width="5%"></td>
  <td width="7%"></td>
  <td width="7%" style="background-color: #cfe2f3;">amount</td>
  <td width="7%"></td>
  <td width="7%"></td>
  <td width="5%"></td>
</tr>

<tr id="add_to_order_tr">
  <td align="center" id="add_to_order_box_0"></td>
  <td align="center" id="add_to_order_box_1"><input type="text" name="add_productcode[0]" value="" style="width: 94%;" id="sku_add_to_order_box_0" {* {if $all_cb_status_eq_P eq "Y" || $all_cb_status_eq_3 eq "Y" || $all_cb_status_eq_V eq "Y" || $all_cb_status_eq_H eq "Y" || $all_cb_status_eq_R eq "Y"}disabled="disabled" {/if} *} {if $order.amazonorderid ne ""}readonly="readonly"{/if} /></td>
  <td align="center" id="add_to_order_box_2"></td>
  <td align="center" id="add_to_order_box_3"><input type="text" name="add_amount[0]" value="" {* style="width: 94%;" *} size="5" {if $order.amazonorderid ne ""}readonly="readonly"{/if} /></td>
  <td align="center" id="add_to_order_box_4"></td>
  <td align="center" id="add_to_order_box_5"></td>
  <td align="center" id="add_to_order_box_6"></td>
  <td align="center" id="add_to_order_box_7"></td>
  <td align="center" id="add_to_order_box_8"></td>
  <td align="center" id="add_to_order_box_9"></td>
  <td>{include file="buttons/multirow_add.tpl" mark="add_to_order"}</td>
</tr>

<tr id="add_additional_fee_to_order_tr" style="background-color: #EEEEEE;">
  <td align="center" id="add_additional_fee_to_order_box_0"><input type="text" name="add_additional_fee_name[0]" value="" style="width: 94%;" {if $order.amazonorderid ne ""}readonly="readonly"{/if} /></td>
  <td align="center" id="add_additional_fee_to_order_box_1"></td>
  <td align="center" id="add_additional_fee_to_order_box_2"></td>
  <td align="center" id="add_additional_fee_to_order_box_3"></td>
  <td align="center" id="add_additional_fee_to_order_box_4"></td>
  <td align="center" id="add_additional_fee_to_order_box_5"></td>
  <td align="center" id="add_additional_fee_to_order_box_6"></td>
  <td align="center" id="add_additional_fee_to_order_box_7"><input type="text" name="add_additional_fee_value[0]" value="" style="width: 94%;" {if $order.amazonorderid ne ""}readonly="readonly"{/if} /></td>
  <td align="center" id="add_additional_fee_to_order_box_8"></td>
  <td align="center" id="add_additional_fee_to_order_box_9"></td>
  <td>{include file="buttons/multirow_add.tpl" mark="add_additional_fee_to_order"}</td>
</tr>
{if $oOrder->getOrderStatusDC() != 'L' && $oOrder->getOrderStatusDC() != 'E' && $oOrder->getOrderStatusDC() != 'DP' && $oOrder->getOrderStatusDC() != 'C' && $oOrder->getOrderStatusDC() != 'G'}
  <tr id="add_retailtrust">
    <td align="center" id="add_retailtrust_box_0"></td>
    <td align="center" id="add_retailtrust_box_1"></td>
    <td align="center" id="add_retailtrust_box_2"><input type="text" name="add_retail_trust[0]" value="" style="width: 94%;" {if $order.amazonorderid ne ""}readonly="readonly"{/if} /></td>
    <td align="center" id="add_retailtrust_box_3"></td>
    <td align="center" id="add_retailtrust_box_4"></td>
    <td align="center" id="add_retailtrust_box_5"></td>
    <td align="center" id="add_retailtrust_box_6"></td>
    <td align="center" id="add_retailtrust_box_7"></td>
    <td align="center" id="add_retailtrust_box_8"></td>
    <td align="center" id="add_retailtrust_box_9"></td>
    <td>{include file="buttons/multirow_add.tpl" mark="add_retailtrust"}</td>
  </tr>
{/if}

</table>
<br />



<input type="submit" value="{$lng.lbl_apply_changes|escape}" />
{if $current_membership_flag ne 'FS'}
  &nbsp;&nbsp;&nbsp;&nbsp;
  <input type="button" value="{$lng.lbl_apply_changes_send_email|escape}" onclick="javascript: $('#send_email1').val('Y'); this.form.submit();" {if $order.amazonorderid ne ""}disabled="disabled" style="border: 1px solid #ff0000" {/if} />
{/if}

</form>

{literal}
<script type="text/javascript">
  $( document ).ready(function() {
    $('#submit_amazon_shipment').on('click', function () {
      var submit_amazon_shipping_method = $('#amazon_shipping_method_select').val();
      if (submit_amazon_shipping_method == '') {
        alert('Please, select Amazon shipping method!');
      } else {

        if (confirm('Are You Sure?')) {
          $(this).prop('disabled', true);
          var orderid = $(this).data('orderid'),
                  manufacturerid = $(this).data('manufacturerid'),
                  submit_amazon_shipment_with_notes = $('#submit_amazon_shipment_with_notes').is(':checked'),
                  submit_amazon_shipment_notes = $('#submit_amazon_shipment_notes').val();
          $.post(
                  "ajax_admin.php", {
                    orderid: orderid,
                    manufacturerid: manufacturerid,
                    submit_amazon_shipment_with_notes: submit_amazon_shipment_with_notes,
                    submit_amazon_shipment_notes: submit_amazon_shipment_notes,
                    ajax_action: 'ship_order_by_amazon',
                    amazon_shipping_method_select:  submit_amazon_shipping_method
                  }
          ).done(function (data) {
            //$('#submit_amazon_shipment').prop('disabled', false);
             window.location.reload();
          })
        }
      }
    });
    $('.group_total_link').on('click', function(){
        $(this).closest('tr').siblings('.group_total_price_row').toggle();
        return false;
    })
  });
</script>
{/literal}
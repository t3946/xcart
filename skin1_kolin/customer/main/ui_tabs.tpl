{include file="check_email_script.tpl"}

{*
{include file="check_zipcode_js.tpl"}
<script src="{$SkinDir}/cidev_ajax.js" type="text/javascript"></script>
*}

<script type="text/javascript">
//<![CDATA[
$(function() {ldelim}
  $('#{$prefix}container').tabs();
{rdelim});

{literal}
function check_question_email_form() {

	if ($("#email").val()!="" && $("#phone").val()!="" && $("#question").val()!="" && $("#firstname").val()!=""){

		$("#button_submit_question_id").hide();

		send_question_email_form();

	} else {
		alert("Please fill in all fields");
		return false;
	}
}

function send_question_email_form(){

	cidev_xmlHttp=cidev_createHttpRequestObject();
	if (cidev_xmlHttp.readyState==4 || cidev_xmlHttp.readyState==0){

		var cidev_parameters = 'cidev_mode=send&email=' + $("#email").val() + '&phone=' + $("#phone").val() + '&question=' + $("#question").val() + '&productid=' + $('#question_productid').val() + '&firstname=' + $('#firstname').val();

		cidev_xmlHttp.onreadystatechange=function(){
			if(cidev_xmlHttp.readyState==4){
				if(cidev_xmlHttp.status==200){
                	        	cidev_id$("product_question_after").innerHTML=cidev_xmlHttp.responseText;
					$("#product_question_pre").hide();
                        	}else{
                        		cidev_Error('no_server', 'Y');
	                        }
			}
		};

                cidev_xmlHttp.open('POST','product_question.php',true);
                cidev_xmlHttp.setRequestHeader('Content-type','application/x-www-form-urlencoded');
                cidev_xmlHttp.setRequestHeader('Content-length',cidev_parameters.length);
                cidev_xmlHttp.setRequestHeader('Connection','close');
                cidev_xmlHttp.send(cidev_parameters);
	}
	else {
		setTimeout('send_question_email_form()', 1000);
	}
}


{/literal}

//]]>
</script>

<script type="text/javascript" language="JavaScript 1.2">
//<![CDATA[
{literal}
  $(document).ready(function() {
        $('#email').focusout(function() {

                if ($('#email').val() != ""){
                        checkEmailAddress(document.product_question_email_form.email, 'Y');
                }
        });
  });
{/literal}
//]]>
</script>


<div id="{$prefix}container">

  <ul>
  {foreach from=$tabs item=tab key=ind}
{*    {inc value=$ind assign="ti"} *}
    <li><a {if $count_product_tabs gte "7"}style="padding: 0.5em 10px;"{/if} href="{if $tab.url}{$tab.url|amp}{else}#{$prefix}{$tab.anchor|default:$ti}{/if}">{$tab.title}</a></li>
  {/foreach}
  </ul>

  {foreach from=$tabs item=tab key=ind}
    {if $tab.tpl}
{*      {inc value=$ind assign="ti"} *}
      <div id="{$prefix}{$tab.anchor|default:$ti}">

	{if $tab.tpl eq "_product_description_"}
<br />
{capture name=dialog}
<div style="padding-left: 8px;">
<span style="font-size: 13px; color: #000000;" class="SPItems-description">{if $use_schema_org eq "Y"}<span id="so_description" itemprop="description">{/if}{if $product.fulldescr ne ""}{$product.fulldescr}{else}{$product.descr}{/if}{if $use_schema_org eq "Y"}</span>{/if}</span>

{if $product.weight ne "0.00" || $variants ne '' || $show_dimensions || $product.upc_ean_isbn}
{* <br /> *}
<br />
{/if}

<br />
{if $active_modules.Extra_Fields ne ""}
<table width="100%" cellpadding="0" cellspacing="0">
{include file="modules/Extra_Fields/product.tpl"}
</table>
{/if}

<div id="so_brand" itemprop="brand" itemscope="" itemtype="http://schema.org/Organization">
	<meta itemprop="name" content="{$product.cidev_brand_name}"/>
</div>
<div id="so_manuf" itemprop="manufacturer" itemscope="" itemtype="http://schema.org/Organization">
	<meta itemprop="name" content="{$product.manufacturer}"/>
</div>

{if $cidev_mpn ne ""}
<meta id="so_mpn" itemprop="mpn" content="{$cidev_mpn}"/>
{/if}

<meta id="so_offer" itemprop="offers" itemscope="" itemtype="http://schema.org/Offer" itemref="so_o_stock so_o_condition so_o_currency so_o_price so_o_function so_o_delivery so_o_seller pm_1 pm_2 pm_3 pm_4"/>
<div id="so_weight" itemprop="weight" itemscope="" itemtype="http://schema.org/QuantitativeValue" itemref="so_weight_value">
	<meta itemprop="unitCode" content="lbs">
</div>
{if $cat_name_for_itemprop ne ""}
<meta id="so_category" itemprop="category" content="{$cat_name_for_itemprop}"/>
{/if}

<meta id="so_o_condition" itemprop="itemCondition" content="NewCondition"/>
<meta id="so_o_currency" itemprop="priceCurrency" content="USD">

<meta id="so_o_function" itemprop="businessFunction" href="http://purl.org/goodrelations/v1#Sell"/>
<div id="so_o_delivery" itemprop="deliveryLeadTime"  itemscope="" itemtype="http://schema.org/QuantitativeValue">
	<meta itemprop="value" content="6">
	<meta itemprop="unitText" content="days">
</div>

{if $product_wholesale.0.price ne "" && $product.new_notify_in_stock_price eq "" && $product.map_price lte $product.taxed_price}
	{assign var="current_price" value=$product_wholesale.0.price}
{/if}

{*
{if $product.min_amount gt 1 && $product.mult_order_quantity eq "Y"}
	{math assign="itemprop_price" equation="y*x" y=$product.min_amount x=$current_price}
{else}
	{assign var="itemprop_price" value=$current_price}
{/if}
*}

<div id="so_o_seller" itemprop="seller" itemscope="" itemtype="http://schema.org/Organization">
	<meta itemprop="logo" content="http://www.artistsupplysource.com/skin1_kolin/images/S3-Stores-Logo-S2.png"/>
	<meta itemprop="url" content="http://www.s3stores.com/"/>
	<meta itemprop="name" content="S3 Stores Inc."/>
</div>


</div>
{/capture}

{if $product.seo_h2 ne ""}
	{assign var=product_description_title value=$product.seo_h2}
{else}
	{if $current_storefront_info.storefrontid eq "50"}
		{assign var=product_description_title value="`$product.mpn` `$lng.lbl_product_description`"}
	{else}
		{assign var=product_description_title value=$lng.lbl_product_description}
	{/if}
{/if}

{include file="dialog.tpl" title=$product_description_title content=$smarty.capture.dialog extra='width="100%"' use_h2="Y" }

        {elseif $tab.tpl eq "_Brand_"}

<br />
{capture name=dialog}

{if $brand_image.filename ne ""}
<img src="images/B/{$brand_image.filename}" style="float: left; margin: 10px 10px 10px 0;" />
{/if}

<p align="justify">
{$brandid_brands_info[$product.brandid].descr}
<br />
<a href="/brands.php?brandid={$product.brandid}" class="NavigationPath">All {$brandid_brands_info[$product.brandid].brand} products</a>
</p>

{/capture}
{include file="dialog.tpl" title=$brandid_brands_info[$product.brandid].brand content=$smarty.capture.dialog extra='width="100%"' use_h2="Y" }

	{elseif $tab.tpl eq "_product_queries_tpl_"}

{$lng.lbl_product_queries_pre_instructions}
<br >
<br >
{if $productqueries_page_arr ne ""}

{foreach from=$productqueries_page_arr item=v key=k}

[{$v.time}] User {$v.username} asks <a href="{$v.url}" target="_blank">{$v.name}</a> (click on link to review detailed question)<br />
<br />
{if $v.answers ne ""}
        {foreach from=$v.answers item=vv key=kk}
                &nbsp;&nbsp;&nbsp;&nbsp;[{$vv.date}] User {$vv.username} wrote: {$vv.content}<br />
                {if $vv.comments ne ""}
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Comments:<br />
                        {foreach from=$vv.comments item=vvv key=kkk}
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$vvv.content}<br /><br />
                        {/foreach}
                        <br />
                {/if}
        {/foreach}
{/if}
<br />
{/foreach}
{/if}

{$lng.lbl_product_queries_after_instructions}
<br />
<br />

{if $product_form_info ne ""}
	{$product_form_info}
{/if}

	{elseif $tab.tpl eq "_product_question_tpl_"}
{* --------------------------------------------------*}
<div id="product_question_pre">
{$lng.lbl_product_question_pre_instructions}
<br />
<br />
<form name="product_question_email_form" action="" method="POST" >
<table cellpadding="1" cellspacing="3" width="100%">

 <tr>
  <td class="cidev_padding_top" align="right">Your First name:</td>
  <td><font class="Star">*</font></td>
  <td nowrap="nowrap">
        <input type="text" id="firstname" name="firstname" size="32" maxlength="32" value="" onkeyup="cidev_check_field_name('firstname')" />
  </td>
 </tr>

 <tr>
  <td align="right" class="cidev_padding_top">Your email:</td>
  <td><font class="Star">*</font></td>
  <td nowrap="nowrap">
	<input type="text" id="email" name="email" size="32" maxlength="128" value="" />
	<input type="hidden" id="question_productid" name="question_productid" value="{$productid}" />
  </td>
 </tr>

 <tr>
  <td class="cidev_padding_top" align="right">Your phone:</td>
  <td><font class="Star">*</font></td>
  <td nowrap="nowrap">
	<input type="text" id="phone" name="phone" size="32" maxlength="32" value="" onkeyup="cidev_check_field_phone('phone')" />
  </td>
 </tr>

 <tr>
  <td class="cidev_padding_top" align="right">Product question:
	<div class="cidev_checkout_descr">{$lng.lbl_FIELD_DESCRIPTION_Product_question}</div>
  </td>
  <td><font class="Star">*</font></td>
  <td>
	<textarea style="width: 98%" name="question" id="question" cols="60" rows="10"></textarea>
  </td>
 </tr>

 <tr>
  <td colspan="3" align="center" id="button_submit_question_id">
	{* <input type="button" name="Submit question" value="Submit question" onclick="javasript: check_question_email_form();" /> *}
	{include file="buttons/button.tpl" button_title="Submit question" type="input" href="javascript: check_question_email_form();" js_to_href="Y" b="1"}

  </td>
 </tr>

</table>
</form>
</div>

<div id="product_question_after">


{if $product.product_questions ne ""}
<br />
<br />
<hr />
{foreach from=$product.product_questions item=v_q key=k_q}

	<span style="color: #cc0000; font-weight: bold; font-size: 12px;">QUESTION</span><br />
	{$v_q.question}<br />
	<span style="color: #aaaaaa;"><I>asked {if $v_q.firstname ne ""}by {$v_q.firstname} {/if}on {$v_q.date|date_format:'%b %d, %Y'}</I></span>

	{if $v_q.answer ne ""}
        <div style="padding-left: 50px; padding-top: 10px;">
            <span style="color: #006500; font-weight: bold; font-size: 12px;">BEST ANSWER</span><br/>
            {$v_q.answer}<br/>

            {if $v_q.operator_name ne ""}
                <span style="color: #aaaaaa;"><I>answered by {$v_q.operator_first_name} (Staff)
                        on {$v_q.answered_date|date_format:'%b %d, %Y'}</I></span>
            {/if}
        </div>
	{/if}
<br />
{/foreach}
{/if}
{else}

{if $tab.title eq "Shipping"}
	{if $product.weight ne "0.00" || $variants ne '' || $show_dimensions}
	<br />
	{/if}

<table width="100%" cellpadding="0" cellspacing="0">

{if $product.weight ne "0.00" || $variants ne ''}
    <tr id="product_weight_box">
            <td width="22%">{$lng.lbl_product_weight}:</td>
            <td nowrap="nowrap"><span id="so_weight_value" itemprop="value">{$product.weight|formatprice}</span> {$config.General.weight_symbol}</td>
    </tr>
{/if}
{if $show_dimensions}
<tr>
        <td width="22%" nowrap="nowrap">{$lng.lbl_product_dimensions}:</td>
        <td nowrap="nowrap">
		<span id="product_weight">

{if $show_dimensions_orderby_str ne ""}
{$show_dimensions_orderby_str}
{else}
{$product.dim_x}" x {$product.dim_y}" x {$product.dim_z}"
{/if}
		</span>
	</td>
</tr>
{/if}
<tr><td colspan="2">&nbsp;</td></tr>
    {if ($product.shipping_weight ne "0.00" && $product.shipping_weight !="")  || $variants ne ''}
        <tr id="product_weight_box">
            <td width="22%">{$lng.lbl_shipping_weight}:</td>
            <td nowrap="nowrap"><span id="so_weight_value" itemprop="value">{$product.shipping_weight|formatprice}</span> {$config.General.weight_symbol}</td>
        </tr>
    {/if}
    {if $show_shipping_dimensions}
        <tr>
            <td width="22%" nowrap="nowrap">{$lng.lbl_shipping_dimensions}:</td>
            <td nowrap="nowrap">
		<span id="product_weight">

{if $show_shipping_dimensions_orderby ne ""}
    {$show_shipping_dimensions_orderby}
{else}
    {$product.shipping_dim_x}" x {$product.shipping_dim_y}" x {$product.shipping_dim_z}"
{/if}
		</span>
            </td>
        </tr>
    {/if}
</table>

	{if $product.weight ne "0.00" || $variants ne '' || $show_dimensions}
	<br />
	{/if}
{/if}

		{$tab.tpl}
	{/if}
      </div>
    {/if}
  {/foreach}

</div>

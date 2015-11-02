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
    <li><a href="{if $tab.url}{$tab.url|amp}{else}#{$prefix}{$tab.anchor|default:$ti}{/if}">{$tab.title}</a></li> 
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
<span style="font-size: 13px; color: #000000;" class="SPItems-description">{if $use_schema_org eq "Y"}<span itemprop="description">{/if}{if $product.fulldescr ne ""}{$product.fulldescr}{else}{$product.descr}{/if}{if $use_schema_org eq "Y"}</span>{/if}</span>

{if $product.weight ne "0.00" || $variants ne '' || $show_dimensions || $product.upc_ean_isbn}
{* <br /> *}
<br />
{/if}

{if $use_schema_org eq "Y"}
{* <div itemprop="name" itemscope itemtype="http://schema.org/Product"> *}
{/if}

<br />
{if $active_modules.Extra_Fields ne ""}
<table width="100%" cellpadding="0" cellspacing="0">
{include file="modules/Extra_Fields/product.tpl"}
</table>
{/if}

{if $use_schema_org eq "Y"}
{if $current_storefront eq "0"}
<meta itemprop="logo" content="http://www.artistsupplysource.com/image.php?type=P&id={$product.productid}"/>
{else}
<meta itemprop="logo" content="http://{$cidev_store_domain}/image.php?type=P&id={$product.productid}"/>
{/if}

<meta itemprop="brand" content="{$product.cidev_brand_name}"/>
<meta itemprop="manufacturer" content="{$product.manufacturer}"/>
<meta itemprop="sku" content="{$product.productcode}"/>
{if $cidev_mpn ne ""}
<meta itemprop="mpn" content="{$cidev_mpn}"/>
{/if}

<div itemprop="offers" itemscope itemtype="http://schema.org/Offer"/>

{if $cat_name_for_itemprop ne ""}
<meta itemprop="category" content="{$cat_name_for_itemprop}"/>
{/if}

{if $product.product_availability eq "in stock"}
<meta itemprop="availability" itemtype="http://schema.org/ItemAvailability" href="http://schema.org/InStock" content="In Stock"/>
{else}
<meta itemprop="availability" itemtype="http://schema.org/ItemAvailability" href="http://schema.org/OutOfStock" content="Out of stock"/>
{/if}

<meta itemprop="itemCondition" itemtype="http://schema.org/OfferItemCondition" content="http://schema.org/NewCondition"/>

<meta itemprop="businessFunction" content="sell"/>
<meta itemprop="deliveryLeadTime" content="6"/>
<meta itemprop="price" content="{$current_price|price_format}"/>
<meta itemprop="priceCurrency" content="USD"/>
<meta itemprop="seller" content="S3 Stores Inc."/>
</div>

{* </div> *} {* end http://schema.org/Product  *}
{/if}

</div>
{/capture}


{if $current_storefront_info.storefrontid eq "50"}
	{assign var=product_description_title value="`$product.mpn` `$lng.lbl_product_description`"}
{else}
	{assign var=product_description_title value=$lng.lbl_product_description}
{/if}


{include file="dialog.tpl" title=$product_description_title content=$smarty.capture.dialog extra='width="100%"' use_h2="Y" }



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
	<input type="hidden" id="question_productid" name="question_productid" size="32" maxlength="128" value="{$productid}" />
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
  <td colspan="3" align="center">
	{* <input type="button" name="Submit question" value="Submit question" onclick="javasript: check_question_email_form();" /> *}
	{include file="buttons/button.tpl" button_title="Submit question" type="input" href="javascript: check_question_email_form();" js_to_href="Y" b="1"}

  </td>
 </tr>

</table>
</form>
</div>

<div id="product_question_after"></div>


{if $product.product_questions ne ""}
<br />
<br />
<hr />
{foreach from=$product.product_questions item=v_q key=k_q}

	<span style="color: #cc0000; font-weight: bold; font-size: 12px;">QUESTION</span><br />
	{$v_q.question}<br />
	<span style="color: #aaaaaa;"><I>asked {if $v_q.firstname ne ""}by {$v_q.firstname} {/if}on {$v_q.date|date_format:'%b %d, %Y'}</I></a>
	
	{if $v_q.answer ne ""}
		<div style="padding-left: 50px; padding-top: 10px;">
		<span style="color: #006500; font-weight: bold; font-size: 12px;">BEST ANSWER</span><br />
		{$v_q.answer}<br />

		{if $v_q.operator_name ne ""}
		<span style="color: #aaaaaa;"><I>answered by {$v_q.operator_first_name} (Staff) on {$v_q.answered_date|date_format:'%b %d, %Y'}</I></a>
		{/if}
		</div>
	{/if}
<br />
{/foreach}
{/if}
{* --------------------------------------------------*}

        {elseif $tab.tpl eq "_product_discussions_tpl_"}
{* --------------------------------------------------*}

<div id="disqus_thread"></div>
<script type="text/javascript">
//<![CDATA[
{literal}
    /* * * CONFIGURATION VARIABLES * * */
    var disqus_shortname = 's3stores';
    
    /* * * DON'T EDIT BELOW THIS LINE * * */
    (function() {
        var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
        dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
        (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
    })();
{/literal}
//]]>
</script>
<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript" rel="nofollow">comments powered by Disqus.</a></noscript>

{* --------------------------------------------------*}

	{else}

{if $tab.title eq "Shipping"}
	{if $product.weight ne "0.00" || $variants ne '' || $show_dimensions}
	<br />
	{/if}

<table width="100%" cellpadding="0" cellspacing="0">

{if $product.weight ne "0.00" || $variants ne ''}
<tr id="product_weight_box">
        <td width="22%">Shipping weight:</td>
        <td nowrap="nowrap"><span id="product_weight">{$product.weight|formatprice}</span> {$config.General.weight_symbol}</td>
</tr>
{if $use_schema_org eq "Y"}
<meta itemprop="weight" content="{$product.weight|formatprice} {$config.General.weight_symbol}" />
{/if}
{/if}
{if $show_dimensions}
<tr>
        <td width="22%" nowrap="nowrap">{$lng.lbl_shipping_dimensions}:</td>
        <td nowrap="nowrap"><span id="product_weight">{$product.dim_x}" x {$product.dim_y}" x {$product.dim_z}"</span></td>
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

{* $Id: product_details.tpl,v 1.44.2.8 2006/10/13 10:41:21 svowl Exp $ *}

{capture name=dialog}
{include file="check_clean_url.tpl"}

<script type="text/javascript" language="JavaScript 1.2" src="{$SkinDir}/lib/jqueryui/jquery-ui.custom.min.js"></script>


{if $taxes}
<script type="text/javascript" language="JavaScript 1.2">
<!--
function ChangeTaxesBoxStatus() {ldelim}
	if (document.modifyform && document.modifyform.elements['taxes[]'])
		document.modifyform.elements['taxes[]'].disabled = (document.modifyform.free_tax.value == 'Y');
{rdelim}

{literal}
function updateCategoryIds() {
	var elm = document.getElementById('categoryids_select');
	if (elm) {
		txt = '';
		for (var i=0; i < elm.options.length; i++) {
			if (elm.options[i].selected) {
				if (txt) {
					txt = txt + ',';
				}
				txt = txt + elm.options[i].value;
			}
		}
	}
	output = document.getElementById('categoryids_input');
	if (output) {
		output.value = txt;
	}
}



function cidev_change_discount_table() {

	var min_amount = document.getElementById('min_amount').value;

	if (min_amount > 1){
		txt = '';
		for (var i=1; i < 6; i++) {
			txt = txt + i*min_amount;
			
			if (i < 5){
				txt = txt + ',';
			}
		}

		document.getElementById('discount_table').value = txt;
	}

        if (min_amount == 1){
                document.getElementById('discount_table').value = '2,3,4,6,8,12';
        }
}


{/literal}
-->
</script>
{/if}

<script type="text/javascript" language="JavaScript 1.2">
<!--
{literal}

function copy_product_title_to_froogle() {
	var froogle_title = $('#product_name').val().substring(0,70);
	/*if (froogle_title.length > 70) {
		var froogle_title = froogle_title.substring(0,67);
		froogle_title = froogle_title + '...';
	}*/
	$('#froogle_title').val(froogle_title);
}

{/literal}

function generate_price(id) {ldelim}
	var res = 0;
	var list_price = $('#list_price').val();
	if (list_price == '') {ldelim}
		list_price = 0;
	{rdelim} else {ldelim}
		list_price = list_price.replace(/\,/g, '');
	{rdelim}
	var cost_to_us = $('#cost_to_us').val();
	if (cost_to_us == '') {ldelim}
		cost_to_us = 0;
	{rdelim} else {ldelim}
		cost_to_us = cost_to_us.replace(/\,/g, '');
	{rdelim}
	if (id == 'cost_to_us') {ldelim}
		res += {$product.cost_to_us_coef_x|default:0} * list_price;
	{rdelim}
	if (id == 'price') {ldelim}
		res += ({$product.price_coef_x|default:0} * cost_to_us + {$product.price_coef_y|default:0}) / {$product.price_coef_z|default:1};
	{rdelim}

        if (id == 'map_price') {ldelim}
                res += {$product.map_price_coef_x|default:0} * list_price;
        {rdelim}

        if (id == 'new_map_price') {ldelim}
                res += {$product.new_map_price_coef_x|default:0} * list_price;
        {rdelim}


	$('#' + id).val(round(res, 2));
{rdelim}
-->
</script>



{if $manufacturer_feed_fields.eta_date_mm_dd_yyyy.disable eq "Y"}
<script type="text/javascript" language="JavaScript 1.2">
<!--
{literal}
  $(function() {
    $("#eta_date_mm_dd_yyyy").datepicker({maxDate: "+2w",minDate: "-1d"});
  });
{/literal}
-->
</script>
{else}
<script type="text/javascript" language="JavaScript 1.2">
 <!--
{literal}
  $(function() {
    $("#eta_date_mm_dd_yyyy").datepicker();
  });
{/literal}
-->
</script>
{/if}

{include file="check_froogle_upc_js.tpl"}

{if $product}
<table width="100%">

<tr>
	<td align="left" class="TopLabel">
        {if $product.forsale neq "N"}
        <span class="detail-title" style="font-weight: normal;"><a href="{$product.customer_url}" title="" target="_blank">{$product.product}</a></span>
        {else}
        <span class="detail-title" style="font-weight: normal;">{$product.product}</span>
        {/if}
	</td>

	{if $product.d_website_search_for_sku_url ne ""}
	<td align="right">
		{if $product.forsale neq "N"}<span class="detail-title" style="font-weight: normal; font-size: 12px;">{/if}
		<a href="{$product.d_website_search_for_sku_url}" title="" target="_blank">{if $product.forsale neq "N"}Product on distributor's website: {/if}{$product.mpn}</a>
		{if $product.forsale neq "N"}</span>{/if}
	</td>
	{/if}
</tr>

</table>
{/if}

<form action="process_product.php" method="post" name="cloneproductform">
<input type="hidden" name="mode" value="clone" />
<input type="hidden" name="clone_detailed" value="" />
<input type="hidden" name="productid" value="{$product.productid}" />
</form>

<form action="product_modify.php" method="post" name="modifyform" {* {if $config.SEO.clean_urls_enabled eq "Y"}onsubmit="javascript: return checkCleanUrl(document.modifyform.clean_url)"{/if} *} >
<input type="hidden" name="productid" value="{$product.productid}" />
<input type="hidden" name="section" value="main" />
<input type="hidden" name="mode" value="product_modify" />
<input type="hidden" name="geid" value="{$geid}" />

<table cellpadding="4" cellspacing="0" width="100%">

{if $geid ne ''}
<tr>
	<td width="15" class="TableSubHead">&nbsp;</td>
	<td class="TableSubHead" colspan="2"><b>* {$lng.lbl_note}:</b> {$lng.txt_edit_product_group}</td>
</tr>
{/if}

<tr>
	{if $geid ne ''}<td width="15" class="TableSubHead">&nbsp;</td>{/if}
	<td colspan="2"><br />{include file="main/subheader.tpl" title=$lng.lbl_product_owner}</td>
</tr>

<tr> 
	{if $geid ne ''}<td width="15" class="TableSubHead">&nbsp;</td>{/if}
	<td class="FormButton" width="20%" nowrap="nowrap">{if $usertype eq "A" and $new_product eq 1}{$lng.lbl_provider}{else}Last modified by{/if}:</td>
	<td class="ProductDetails" width="80%">
{if $usertype eq "A" and $new_product eq 1}
	<select name="provider" class="InputWidth">
{section name=prov loop=$providers}
		<option value="{$providers[prov].login}">{$providers[prov].login} ({if $providers[prov].title ne "" }{$providers[prov].title} {/if}{$providers[prov].lastname} {$providers[prov].firstname})</option>
{/section}
	</select>
{else}
  {if $new_product eq 1}
    {assign var=mod_date value=$smarty.now|date_format:"%d-%b-%Y"}
    {assign var=mod_time value=$smarty.now|date_format:"%H:%M"}
  {else}
    {assign var=mod_date value=$product.mod_date|date_format:"%d-%b-%Y"}
    {assign var=mod_time value=$product.mod_date|date_format:"%H:%M"}
  {/if}
{assign var=provider_login value="`$provider_info.title` `$provider_info.firstname` `$provider_info.lastname` (`$provider_info.login`)"}
{*
{$lng.txt_last_modified|substitute:login:$provider_login:date:$mod_date:time:$mod_time}
*}
{$provider_info.firstname} ({$provider_info.login}) on {$mod_date} at {$mod_time}

{/if}
	</td>
</tr>

{if $product.controlled_by_feed ne ""}
<tr>
        {if $geid ne ''}<td width="15" class="TableSubHead"></td>{/if}
        <td class="FormButton" nowrap="nowrap">Product controlled by feed:</td>
        <td class="ProductDetails">{$product.controlled_by_feed}</td>
</tr>
{/if}

<tr> 
	{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[forsale]" /></td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_availability}:</td>
	<td class="ProductDetails">

  {if $product.lock_forsale eq "Y"}
	<input type="hidden" name="forsale" id="forsale" value="{$product.forsale}" />
	{if $product.forsale eq "Y" || ($product.forsale ne "N" && $product.forsale ne "H" && ($product.forsale ne "B" || not $active_modules.Product_Configurator))}{$lng.lbl_avail_for_sale}{/if}
	{if $product.forsale eq "N"}{$lng.lbl_disabled}{/if}
  {else}
	<select name="forsale">
		<option value="Y"{if $product.forsale eq "Y" || ($product.forsale ne "N" && $product.forsale ne "H" && ($product.forsale ne "B" || not $active_modules.Product_Configurator))} selected="selected"{/if}>{$lng.lbl_avail_for_sale}</option>
{*		<option value="H"{if $product.forsale eq "H"} selected="selected"{/if}>{$lng.lbl_hidden}</option> *}
		<option value="N"{if $product.forsale eq "N"} selected="selected"{/if}>{$lng.lbl_disabled}</option>
{if $active_modules.Product_Configurator}
		<option value="B"{if $product.forsale eq "B"} selected="selected"{/if}>{$lng.lbl_bundled}</option>
{/if}
	</select>
  {/if}
	</td>
</tr>

{if $membership_code ne ""}
        <input type="hidden" name="title_tag" value="{$product.lock_forsale}" />
{else}
<tr>
        {if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[lock_forsale]" /></td>{/if}
        <td class="FormButton" nowrap="nowrap">Lock 'Availability' status:</td>
        <td class="ProductDetails">
        <select name="lock_forsale">
                <option value="N"{if $product.lock_forsale eq "N"} selected="selected"{/if}>Unlocked</option>
                <option value="Y"{if $product.lock_forsale eq "Y"} selected="selected"{/if}>Locked forever</option>
        </select>
        </td>
</tr>
{/if}

{* ----------------------- *}
<tr>
        {if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[eta_date_mm_dd_yyyy]" /></td>{/if}
        <td class="FormButton" nowrap="nowrap">ETA date (mm/dd/yyyy):</td>
        <td class="ProductDetails">
                <input type="text" name="eta_date_mm_dd_yyyy" id="eta_date_mm_dd_yyyy" size="18" value="{if $product.eta_date_mm_dd_yyyy} {$product.eta_date_mm_dd_yyyy|date_format:'%m/%d/%Y'} {/if}" {if $manufacturer_feed_fields.eta_date_mm_dd_yyyy.disable eq "Y"}readonly="readonly"{/if} />
                {if $manufacturer_feed_fields.eta_date_mm_dd_yyyy.disable eq "Y"}
                        <label style="margin-left:30px;"><input style="vertical-align: bottom;" type="checkbox" name="eta_date_locked_checkbox" {if $product.eta_date_lock=="Y"}checked="checked"{/if}/> <b>Lock</b></label>
                {/if}
        </td>
</tr>
{* ----------------------- *}

<tr>
        {if $geid ne ''}<td width="15" class="TableSubHead">&nbsp;</td>{/if}
        <td colspan="2"><br />{include file="main/subheader.tpl" title="Classification"}</td>
</tr>

<tr>
        {if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[pc_mc_operator]" /></td>{/if}
        <td class="FormButton" nowrap="nowrap">Classified by:</td>
        <td class="ProductDetails">
        <select name="pc_mc_operator" disabled="disabled">
                <option value=""></option>
{section name=prov loop=$providers}
                <option value="{$providers[prov].login}" {if $product.pc_mc_operator eq $providers[prov].login}selected="selected"{/if}>{$providers[prov].login} ({if $providers[prov].title ne "" }{$providers[prov].title} {/if}{$providers[prov].lastname} {$providers[prov].firstname})</option>
{/section}
        </select>
        </td>
</tr>

<tr>
        {if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[pc_acc_operator]" /></td>{/if}
        <td class="FormButton" nowrap="nowrap">Automatic classification approved by:</td>
        <td class="ProductDetails">
        <select name="pc_acc_operator" disabled="disabled">
                <option value=""></option>
{section name=prov loop=$providers}
                <option value="{$providers[prov].login}" {if $product.pc_acc_operator eq $providers[prov].login}selected="selected"{/if}>{$providers[prov].login} ({if $providers[prov].title ne "" }{$providers[prov].title} {/if}{$providers[prov].lastname} {$providers[prov].firstname})</option>
{/section}
        </select>
        </td>
</tr>

<tr>
        {if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[pc_classify_status]" /></td>{/if}
        <td class="FormButton" nowrap="nowrap">Product classify status:</td>
        <td class="ProductDetails">
        <select name="pc_classify_status" disabled="disabled">
        <option value="NC"{if $product.pc_classify_status eq 'NC' || $product.pc_classify_status eq ''} selected="selected"{/if}>not classified</option>
        <option value="MC"{if $product.pc_classify_status eq 'MC'} selected="selected"{/if}>manually classified</option>
        <option value="AC"{if $product.pc_classify_status eq 'AC'} selected="selected"{/if}>automatically classified</option>
        <option value="ACC"{if $product.pc_classify_status eq 'ACC'} selected="selected"{/if}>automatically classified and confirmed by operator</option>
        </select>
        </td>
</tr>

<tr>
	{if $geid ne ''}<td width="15" class="TableSubHead">&nbsp;</td>{/if}
	<td colspan="2"><br />{include file="main/subheader.tpl" title=$lng.lbl_classification}</td>
</tr>

{if $active_modules.Manufacturers ne ""}
<tr>
	{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[manufacturer]" /></td>{/if}
    <td class="FormButton" nowrap="nowrap">{$lng.lbl_manufacturer}:</td>
    <td class="ProductDetails">
	<select name="manufacturerid">
	    <option value=''{if $product.manufacturerid eq ''} selected="selected"{/if}>{$lng.lbl_no_manufacturer}</option>
    {foreach from=$manufacturers item=v}
    	<option value='{$v.manufacturerid}'{if $v.manufacturerid eq $product.manufacturerid} selected="selected"{/if}>{$v.manufacturer}</option>
    {/foreach}
    </select>

{if $usertype eq "A"}
<a href="manufacturers.php?manufacturerid={$product.manufacturerid}" target="_blank">Link to Distributor's page on back-end ({$product.manufacturer})</a>
{/if}

	</td>
</tr>
{/if}

{if $active_modules.Brands ne ""}
<tr>
	{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[brand]" /></td>{/if}
    <td class="FormButton" nowrap="nowrap">{$lng.lbl_brand}:</td>
    <td class="ProductDetails">
	<select name="brandid">
	    <option value=''{if $product.brandid eq ''} selected="selected"{/if}>{$lng.lbl_no_brand}</option>
    {foreach from=$brands item=v}
    	<option value='{$v.brandid}'{if $v.brandid eq $product.brandid} selected="selected" {assign var="product_brand" value=$v.brand} {/if}>{$v.brand}</option>
    {/foreach}
    </select>

{if $usertype eq "A"}
<a href="brands.php?brandid={$product.brandid}" target="_blank">Link to Brand's page on back-end ({$product_brand})</a>
{/if}

	</td>
</tr>
{/if}

<tr> 
	{if $geid ne ''}<td width="15" class="TableSubHead">&nbsp;{* <input type="checkbox" value="Y" name="fields[categoryid]" /> *}</td>{/if}
	<td {* class="FormButton" *} nowrap="nowrap">{$lng.lbl_main_category}:</td>
	<td class="ProductDetails">{include file="main/category_selector.tpl" field="categoryid_text" extra=' style="width: 100%;"' categoryid=$product.categoryid|default:$default_categoryid override_onchange="javascript: document.getElementById('categoryid_input').value=this.options[this.selectedIndex].value;" display_only_selected=$product.productid|default:"Y"}
	</td>
</tr>

<tr> 
	{if $geid ne ''}<td width="15" class="TableSubHead">{* &nbsp; *}<input type="checkbox" value="Y" name="fields[categoryid]" /></td>{/if}
	<td nowrap="nowrap" class="FormButton">{$lng.lbl_main_category_id}:</td>
	<td class="ProductDetails">
	<input type="text" name="categoryid" id="categoryid_input" size="8" value="{$product.categoryid|default:$default_categoryid}" />
	{if $top_message.fillerror ne "" and ($product.categoryid eq "" || $category_exists eq 'N')}<font class="Star">&lt;&lt;</font>{/if}
	</td>
</tr>

<tr>
	{if $geid ne ''}<td width="15" class="TableSubHead">{* <input type="checkbox" value="Y" name="fields[categoryids]" /> *}&nbsp;</td>{/if}
	<td {* class="FormButton" *} nowrap="nowrap">{$lng.lbl_additional_categories}:</td>
	<td class="ProductDetails">
	<select name="categoryids_text[]" id="categoryids_select" style="width: 100%;" multiple="multiple" size="8" onchange="javascript: updateCategoryIds();">
{foreach from=$allcategories item=c key=catid}
{if $c.productid eq $product.productid && $product.productid ne ""}
		<option value="{$catid}"{if ($c.productid eq $product.productid && $product.productid ne "") || ($product.productid eq '' && $product.add_categoryids && $product.add_categoryids[$catid])} selected="selected"{/if}>{$c.category_path}</option>
{/if}
{/foreach}
	</select>
	</td>
</tr>

<tr>
	{if $geid ne ''}<td width="15" class="TableSubHead">{* &nbsp; *}<input type="checkbox" value="Y" name="fields[categoryids]" /></td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_additional_categories_ids}:</td>
	<td class="ProductDetails">
	<input type="text" name="categoryids" id="categoryids_input" size="40" value="{strip}
{if $geid eq ''}
{assign var="need_comma" value=false}
{foreach from=$allcategories item=c key=catid}
{if ($c.productid eq $product.productid && $product.productid ne "") || ($product.productid eq '' && $product.add_categoryids && $product.add_categoryids[$catid])}{if $need_comma},{else}{assign var="need_comma" value=true}{/if}{$c.categoryid}{/if}
{/foreach}
{/if}
{/strip}" style="width: 100%;"/>
	</td>
</tr>

{if $product.forsale eq 'H'}
<tr>
	{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[categoryids]" /></td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_product_url}:</td>
	<td class="ProductDetails">{$catalogs.customer}/product.php?productid={$product.productid}&cat={$product.categoryid}</td>
</tr>
{/if}


{if $membership_code ne ""}
        <input type="hidden" name="title_tag" value="{$product.title_tag}" />
        <input type="hidden" name="seo_product_name" value="{$product.seo_product_name}" />
        <input type="hidden" name="seo_meta_descr" value="{$product.seo_meta_descr}" />
        <input type="hidden" name="seo_meta_descr" value="{$product.prevent_search_indexing_this_product_page}" />
{else}
<tr>
        {if $geid ne ''}<td width="15" class="TableSubHead">&nbsp;</td>{/if}
        <td colspan="2"><br />{include file="main/subheader.tpl" title="SEO options"}</td>
</tr>

<tr>
        {if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[prevent_search_indexing_this_product_page]" /></td>{/if}
        <td class="FormButton" nowrap="nowrap">Prevent search indexing this product page:</td>
        <td class="ProductDetails"><input type="checkbox" name="prevent_search_indexing_this_product_page" id="prevent_search_indexing_this_product_page" value="Y" {if $product.prevent_search_indexing_this_product_page eq "Y"}checked="checked"{/if} /></td>
</tr>

<tr>
        {if $geid ne ''}<td width="15" class="TableSubHead">&nbsp;</td>{/if}
        <td class="FormButton" nowrap="nowrap">Title (&lt;title&gt;):</td>
        <td class="ProductDetails"><input type="text" name="title_tag" size="20" value="{$product.title_tag}" class="InputWidth" /></td>
</tr>

<tr>
        {if $geid ne ''}<td width="15" class="TableSubHead">&nbsp;</td>{/if}
        <td class="FormButton" nowrap="nowrap">SEO product name (&lt;H1&gt;):</td>
        <td class="ProductDetails"><input type="text" name="seo_product_name" size="20" value="{$product.seo_product_name}" class="InputWidth" /></td>
</tr>

<tr>
        {if $geid ne ''}<td width="15" class="TableSubHead">&nbsp;</td>{/if}
        <td class="FormButton" nowrap="nowrap">SEO (&lt;H2&gt;):</td>
        <td class="ProductDetails"><input type="text" name="seo_h2" size="20" value="{$product.seo_h2}" class="InputWidth" /></td>
</tr>

<tr>
        {if $geid ne ''}<td width="15" class="TableSubHead">&nbsp;</td>{/if}
        <td class="FormButton" nowrap="nowrap">SEO meta 'Description':</td>
        <td class="ProductDetails">
                <textarea style="width: 80%" name="seo_meta_descr" cols="60" rows="4">{$product.seo_meta_descr}</textarea>
        </td>
</tr>
{/if}


<tr> 
	{if $geid ne ''}<td width="15" class="TableSubHead">&nbsp;</td>{/if}
	<td colspan="2"><br />{include file="main/subheader.tpl" title=$lng.lbl_details}</td>
</tr>

<tr> 
	{if $geid ne ''}<td width="15" class="TableSubHead">&nbsp;</td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_sku}:</td>
	<td class="ProductDetails"><input type="text" name="productcode" size="20" value="{$product.productcode}" class="InputWidth" /></td>
</tr>

<tr> 
	{if $geid ne ''}<td width="15" class="TableSubHead">&nbsp;</td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_froogle_upc}:</td>
	<td class="ProductDetails"><input type="text" name="upc" size="20" value="{$product.upc}" class="InputWidth" {if $manufacturer_feed_fields.upc.disable eq "Y"}readonly="readonly"{/if} /></td>
</tr>

<tr> 
	{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[product]" /></td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_product_name}:</td>
	<td class="ProductDetails"> 
	<input type="text" name="product" id="product_name" size="45" class="InputWidth" value="{$product.product|escape}" {* {if $config.SEO.clean_urls_enabled eq "Y"}onchange="javascript: if (this.form.clean_url.value == '') copy_clean_url(this, this.form.clean_url)"{/if} *} {if $manufacturer_feed_fields.product.disable eq "Y"}readonly="readonly"{/if} />
	{if $top_message.fillerror ne "" and $product.product eq ""}<font class="Star">&lt;&lt;</font>{/if}
	&nbsp;{include file="capitalize_js.tpl" id="product_name"}
	</td>
</tr>

{if $product.productid ne ""}
{include file="main/clean_url_field.tpl" clean_url=$product.clean_url clean_urls_history=$product.clean_urls_history clean_url_fill_error=$top_message.clean_url_fill_error tooltip_id='clean_url_tooltip_link'}
{/if}


{if $product.product|strlen > $FROOGLE_TITLE_LENGTH || $new_product eq 1}
<tr>
	{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[product]" /></td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_product_name_froogle}:</td>
	<td class="ProductDetails"> 
		<input type="text" name="product_froogle" id="froogle_title" size="45" maxlength="70" class="InputWidth" value="{$product.product_froogle|escape}" {if $manufacturer_feed_fields.product_froogle.disable eq "Y"}readonly="readonly"{/if} />
		&nbsp;<input type="button" value=" {$lng.lbl_copy|strip_tags:false|escape} " onclick="javascript: copy_product_title_to_froogle();" />
	</td>
</tr>
{/if}

<tr>
	{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[google_search_term]" /></td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_google_prod_search_term}:</td>
	<td class="ProductDetails">
        <input type="text" name="google_search_term" class="InputWidth" value="{$product.google_search_term|escape:"html"}" {if $manufacturer_feed_fields.google_search_term.disable eq "Y"}readonly="readonly"{/if} />
        {if $config.Product_Page.google_prod_link_pattern && $product.google_search_term}
            &nbsp;<a href="{$config.Product_Page.google_prod_link_pattern|substitute:searchterm:$product.google_search_link}" target="_blank" title="">{$lng.lbl_google_prod_link}</a>
        {/if}
    </td>
</tr>

{if $active_modules.Egoods ne ""}
{include file="modules/Egoods/egoods.tpl"}
{/if}

<tr>
        {if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[amazon_enabled]" /></td>{/if}
        <td class="FormButton" nowrap="nowrap">Amazon enabled:</td>
        <td class="ProductDetails">
<input type="checkbox" name="amazon_enabled" value="Y"{if $product.amazon_enabled eq "Y"} checked="checked"{/if} {if $manufacturer_feed_fields.amazon_enabled.disable eq "Y"}disabled="disabled"{/if} />
{if $manufacturer_feed_fields.amazon_enabled.disable eq "Y"}
<input type="hidden" name="amazon_enabled" value="{$product.amazon_enabled}" />
{/if}
	</td>
</tr>

<tr>
        {if $geid ne ''}<td width="15" class="TableSubHead"></td>{/if}
        <td class="FormButton" nowrap="nowrap">Amazon specific details:</td>
        <td class="ProductDetails"><a style="color: blue;" href="amazon_specific_details.php?productid={$productid}" target="_blank">Opened in new window...</a></td>
</tr>

<tr> 
	{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[fulldescr]" /></td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_det_description}* :</td>
	<td class="ProductDetails">
{if $manufacturer_feed_fields.fulldescr.disable eq "Y"}
	{include file="main/textarea.tpl" name="fulldescr" cols=45 rows=12 class="InputWidth" data=$product.fulldescr width="80%" btn_rows=4 readonly="Y"}
{else}
	{include file="main/textarea.tpl" name="fulldescr" cols=45 rows=12 class="InputWidth" data=$product.fulldescr width="80%" btn_rows=4}
{/if}
	{if $top_message.fillerror ne "" and $product.fulldescr eq ""}<font class="Star">&lt;&lt;</font>{/if}
	</td>
</tr>

<tr {if $usertype eq "P"}style="display: none;"{/if}> 
	{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[descr]" /></td>{/if}
	<td class="FormButton" nowrap="nowrap">
		{$lng.lbl_short_description}* :<br />
		<font style="font-weight: normal">{$lng.txt_short_descr}</font>
	</td>
	<td class="ProductDetails">
{if $manufacturer_feed_fields.descr.disable eq "Y"}
	{include file="main/textarea.tpl" name="descr" cols=45 rows=8 class="InputWidth" data=$product.descr width="80%" btn_rows=4 readonly="Y"}
{else}
	{include file="main/textarea.tpl" name="descr" cols=45 rows=8 class="InputWidth" data=$product.descr width="80%" btn_rows=4}
{/if}
	</td>
</tr>

<tr>
	{if $geid ne ''}<td width="15" class="TableSubHead">&nbsp;</td>{/if}
	<td colspan="2">{$lng.txt_html_tags_in_description}</td>
</tr>

<tr>
        {if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[lead_time_message]" /></td>{/if}
        <td class="FormButton" nowrap="nowrap">Lead time message:</td>
        <td class="ProductDetails"><input type="text" class="InputWidth" name="lead_time_message" id="lead_time_message" size="20" value="{$product.lead_time_message}" {if $manufacturer_feed_fields.lead_time_message.disable eq "Y"}readonly="readonly"{/if} /></td>
</tr>


<tr>
        {if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[supplier_internal_id]" /></td>{/if}
        <td class="FormButton" nowrap="nowrap">Supplier internal id:</td>
        <td class="ProductDetails">
	<input type="text" name="supplier_internal_id" id="supplier_internal_id" size="20" value="{$product.supplier_internal_id}" {if $manufacturer_feed_fields.supplier_internal_id.disable eq "Y"}readonly="readonly"{/if} />
	{if $product.supplier_internal_id_last_parsed_update gt 0}
		(last_parsed_update: {$product.supplier_internal_id_last_parsed_update|date_format:$config.Appearance.datetime_format})
	{/if}
	</td>
</tr>

<tr>
	{if $geid ne ''}<td width="15" class="TableSubHead">&nbsp;</td>{/if}
	<td colspan="2"><br />{include file="main/subheader.tpl" title=$lng.lbl_pricing}</td>
</tr>

<tr>
	{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[list_price]" /></td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_list_price} <span class="Text">({$config.General.currency_symbol})</span></td>
	<td class="ProductDetails"><input type="text" name="list_price" id="list_price" size="18" value="{$product.list_price|formatprice|default:$zero}" {if $manufacturer_feed_fields.list_price.disable eq "Y"}readonly="readonly"{/if} /></td>
</tr>

<tr>
	{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[cost_to_us]" /></td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_cost_to_us} ({$config.General.currency_symbol})</td>
	<td class="ProductDetails">
		<input type="text" name="cost_to_us" id="cost_to_us" size="18" value="{$product.cost_to_us|formatprice|default:$zero}" {if $manufacturer_feed_fields.cost_to_us.disable eq "Y"}readonly="readonly"{/if} />&nbsp;
		{if $product.cost_to_us_coef_x ne 0}
			<input type="button" value="{$lng.lbl_copy_to_us_button|replace:"X":"`$product.cost_to_us_coef_x`"}" onclick="javascript: generate_price('cost_to_us');" />&nbsp;
		{/if}	
		{if $top_message.fillerror ne "" and $product.cost_to_us eq ""}<font class="Star">&lt;&lt;</font>{/if}
	</td>
</tr>


{* {if $smarty.get.mode_add_product eq "y"} *}
{if $product.productid eq "" || $product.price eq 0}
<input type="hidden" name="calculate_price_for_new_product" value="Y" />
{else}
<tr>
        {if $geid ne ''}<td width="15" class="TableSubHead">{if $product.is_variants eq 'Y'}&nbsp;{else}<input type="checkbox" value="Y" name="fields[price]" />{/if}</td>{/if}
        <td class="FormButton" nowrap="nowrap">{$lng.lbl_price} ({$config.General.currency_symbol})</td>
        <td {if $usertype eq "A"}class="ProductDetails"{else}{/if}>
{if $product.is_variants eq 'Y'}
<b>{$lng.lbl_note}:</b> {$lng.txt_pvariant_edit_note|substitute:"href":$variant_href}
{else}

{if $usertype eq "A"}
<div id="cidev_box1">
&nbsp;<a style="text-decoration: none; border-bottom: 1px dashed #000000;" href="javascript: void(0)"  onclick="javasctip: document.getElementById('cidev_box2').style.display=''; document.getElementById('cidev_box1').style.display='none';">{ $product.price|formatprice|default:$zero}</a>
</div>

<div id="cidev_box2" style="display: none;">
        <input type="text" name="price" id="price" size="18" value="{ $product.price|formatprice|default:$zero}" {if $manufacturer_feed_fields.price.disable eq "Y"}readonly="readonly"{/if} />&nbsp;
{else}
<font style="color: #580404">&nbsp;{ $product.price|formatprice|default:$zero}</font>
        <input type="hidden" name="price" id="price" value="{ $product.price|formatprice|default:$zero}" />
{/if}


        {if $product.price_coef_x ne 0 &&  $product.price_coef_y ne 0 &&  $product.price_coef_z ne 0 && $usertype eq "A"}
                <input type="button" value="{$lng.lbl_price_button|replace:"X":"`$product.price_coef_x`"|replace:"Y":"`$product.price_coef_y`"|replace:"Z":"`$product.price_coef_z`"}" onclick="javascript: generate_price('price');" />&nbsp;
        {/if}   
        {if $top_message.fillerror ne "" and $product.price eq ""}<font class="Star">&lt;&lt;</font>{/if}

{if $usertype eq "A"}
</div>
{/if}

{/if}
        </td>
</tr>
{/if}

<tr>
        {if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[product_price_multiplier]" /></td>{/if}
        <td class="FormButton" nowrap="nowrap">Price multiplier</td>
        <td class="ProductDetails">

{*
                <input type="text" name="product_price_multiplier" id="product_price_multiplier" size="18" value="{$product.product_price_multiplier|formatprice|default:$zero}" />&nbsp;
*}

{if $usertype eq "A"}
<div id="cidev_box3">
&nbsp;<a style="text-decoration: none; border-bottom: 1px dashed #000000;" href="javascript: void(0)"  onclick="javasctip: document.getElementById('cidev_box4').style.display=''; document.getElementById('cidev_box3').style.display='none';">{$product.product_price_multiplier|formatprice|default:$zero}</a>
</div>

<div id="cidev_box4" style="display: none;">
        <input type="text" name="product_price_multiplier" id="product_price_multiplier" size="18" value="{$product.product_price_multiplier|formatprice|default:$zero}" {if $manufacturer_feed_fields.product_price_multiplier.disable eq "Y"}readonly="readonly"{/if} />&nbsp;
{else}
<font style="color: #580404">&nbsp;{$product.product_price_multiplier|formatprice|default:$zero}</font>
        <input type="hidden" name="product_price_multiplier" id="product_price_multiplier" value="{$product.product_price_multiplier|formatprice|default:$zero}" />
{/if}

{if $usertype eq "A"}
</div>
{/if}

        </td>
</tr>

{* ----------------- *}
{*
<tr>
        {if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[new_map_price]" /></td>{/if}
        <td class="FormButton" nowrap="nowrap">{$lng.lbl_new_map_price} <span class="Text">({$config.General.currency_symbol})</span></td>
        <td class="ProductDetails"><input type="text" name="new_map_price" id="new_map_price" size="18" value="{$product.new_map_price|formatprice|default:$zero}" {if $manufacturer_feed_fields.new_map_price.disable eq "Y"}readonly="readonly"{/if} /></td>
</tr>
*}

<tr>
        {if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[new_map_price]" /></td>{/if}
        <td class="FormButton" nowrap="nowrap">{$lng.lbl_new_map_price} <span class="Text">({$config.General.currency_symbol})</span></td>
        <td class="ProductDetails">
                <input type="text" name="new_map_price" id="new_map_price" size="18" value="{$product.new_map_price|formatprice|default:$zero}" {if $manufacturer_feed_fields.new_map_price.disable eq "Y"}readonly="readonly"{/if} />&nbsp;
                {if $product.new_map_price_coef_x ne 0}
                        <input type="button" value="{$lng.lbl_copy_to_us_button|replace:"X":"`$product.new_map_price_coef_x`"}" onclick="javascript: generate_price('new_map_price');" />&nbsp;
                {/if}
                {if $top_message.fillerror ne "" and $product.new_map_price eq ""}<font class="Star">&lt;&lt;</font>{/if}
        </td>
</tr>

{* ----------------- *}

<tr {if $usertype eq "P"}style="display: none;"{/if}> 
	{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[map_price]" /></td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_map_price} <span class="Text">({$config.General.currency_symbol})</span></td>
{*	<td class="ProductDetails"><input type="text" name="map_price" id="map_price" size="18" value="{$product.map_price|formatprice|default:$zero}" /></td>
 *}

        <td class="ProductDetails">
                <input type="text" name="map_price" id="map_price" size="18" value="{$product.map_price|formatprice|default:$zero}" {if $manufacturer_feed_fields.map_price.disable eq "Y"}readonly="readonly"{/if} />&nbsp;
                {if $product.map_price_coef_x ne 0}
                        <input type="button" value="{$lng.lbl_copy_to_us_button|replace:"X":"`$product.map_price_coef_x`"}" onclick="javascript: generate_price('map_price');" />&nbsp;
                {/if}
                {if $top_message.fillerror ne "" and $product.map_price eq ""}<font class="Star">&lt;&lt;</font>{/if}
        </td>


</tr>

<tr> 
	{if $geid ne ''}<td width="15" class="TableSubHead">&nbsp;</td>{/if}
	<td colspan="2"><br />{include file="main/subheader.tpl" title=$lng.lbl_inventory}</td>
</tr>

<tr> 
	{if $geid ne ''}<td width="15" class="TableSubHead">{if $product.is_variants eq 'Y'}&nbsp;{else}<input type="checkbox" value="Y" name="fields[avail]" />{/if}</td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_quantity_in_stock} (real)</td>
	<td class="ProductDetails">
{if $product.is_variants eq 'Y'}
<b>{$lng.lbl_note}:</b> {$lng.txt_pvariant_edit_note|substitute:"href":$variant_href}
{else}
	<input type="text" name="r_avail" size="18" value="{if $product.productid eq ""}{$product.r_avail|default:1000000}{else}{$product.r_avail}{/if}" {if $manufacturer_feed_fields.r_avail.disable eq "Y"}readonly="readonly"{/if} />

	{if $product.productid eq ""}<input type="hidden" name="avail" value="1000000" />{/if}

	{if $top_message.fillerror ne "" and $product.avail eq ""}<font class="Star">&lt;&lt;</font>{/if}
{/if}
	</td>
</tr>

<tr {if $usertype eq "P"}style="display: none;"{/if}> 
	{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[low_avail_limit]" /></td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_lowlimit_in_stock}</td>
	<td class="ProductDetails"> 
	<input type="text" name="low_avail_limit" size="18" value="{if $product.productid eq ""}1000{else}{ $product.low_avail_limit }{/if}" {if $manufacturer_feed_fields.low_avail_limit.disable eq "Y"}readonly="readonly"{/if} />
	{if $top_message.fillerror ne "" and $product.low_avail_limit le 0}<font class="Star">&lt;&lt;</font>{/if}
	</td>
</tr>

<tr>
	{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[min_amount]" /></td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_min_order_amount}</td>
	<td class="ProductDetails"><input type="text" name="min_amount" size="18" value="{if $product.productid eq ""}1{else}{$product.min_amount}{/if}" id="min_amount" onBlur="javascript: cidev_change_discount_table();" onKeyUp="javascript: cidev_change_discount_table();" {if $manufacturer_feed_fields.min_amount.disable eq "Y"}readonly="readonly"{/if} /></td>
</tr>

<tr>
	{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[mult_order_quantity]" /></td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_mult_order_quantity}</td>
	<td class="ProductDetails">

	<input type="checkbox" name="mult_order_quantity" value="Y"{if $product.mult_order_quantity eq "Y"} checked="checked"{/if} {if $manufacturer_feed_fields.mult_order_quantity.disable eq "Y"}disabled="disabled"{/if} />

	{if $manufacturer_feed_fields.mult_order_quantity.disable eq "Y"}
	<input type="hidden" name="mult_order_quantity" value="{$product.mult_order_quantity}" />
	{/if}

	</td>
</tr>

{if $active_modules.RMA ne ''}
<tr> 
	{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[return_time]" /></td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_return_time}</td>
	<td class="ProductDetails"><input type="text" name="return_time" size="18" value="{$product.return_time}" {if $manufacturer_feed_fields.return_time.disable eq "Y"}readonly="readonly"{/if} /></td>
</tr>
{/if}

{*<tr> 
	{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[membershipids]" /></td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_membership}</td>
	<td class="ProductDetails">{include file="main/membership_selector.tpl" data=$product}</td>
</tr>
*}

<tr> 
	{if $geid ne ''}<td width="15" class="TableSubHead">&nbsp;</td>{/if}
	<td colspan="2"><br />{include file="main/subheader.tpl" title=$lng.lbl_taxes}</td>
</tr>

<tr> 
	{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[free_tax]" /></td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_tax_exempt}</td>
	<td class="ProductDetails">
	<select name="free_tax"{if $taxes} onchange="javascript: ChangeTaxesBoxStatus();"{/if} {if $manufacturer_feed_fields.free_tax.disable eq "Y"}disabled="disabled"{/if}>
		<option value='Y'{if $product.free_tax eq 'Y'} selected="selected"{/if}>{$lng.lbl_yes}</option>
		<option value='N'{if $product.free_tax eq 'N'} selected="selected"{/if}>{$lng.lbl_no}</option>
	</select> 
	{if $manufacturer_feed_fields.free_tax.disable eq "Y"}
		<input type="hidden" name="free_tax" value="{$product.free_tax}" />
	{/if}
	</td>
</tr>

{if $taxes}
<tr> 
	{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[taxes]" /></td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_apply_taxes}</td>
	<td class="ProductDetails"> 
	<select name="taxes[]" multiple="multiple"{if $product.free_tax eq "Y" || $manufacturer_feed_fields.taxes.disable eq "Y"} disabled="disabled"{/if}>
	{section name=tax loop=$taxes}
	<option value="{$taxes[tax].taxid}"{if $taxes[tax].selected gt 0} selected="selected"{/if}>{$taxes[tax].tax_name}</option>
	{/section}
	</select>
	<br />{$lng.lbl_hold_ctrl_key}
	{if $usertype eq "P" or $active_modules.Simple_Mode ne ""}<br /><a href="{$catalogs.provider}/taxes.php" class="SmallNote" target="_new">{$lng.lbl_click_here_to_manage_taxes}</a>{/if}
	</td>
</tr>
{/if}

{*
<tr> 
	{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[free_shipping]" /></td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_free_shipping}</td>
	<td class="ProductDetails">
	<select name="free_shipping">
		<option value='N'{if $product.free_shipping eq 'N'} selected="selected"{/if}>{$lng.lbl_no}</option>
		<option value='Y'{if $product.free_shipping eq 'Y'} selected="selected"{/if}>{$lng.lbl_yes}</option>
	</select> 
	</td>
</tr>
*}

<tr> 
	{if $geid ne ''}<td width="15" class="TableSubHead">&nbsp;</td>{/if}
	<td colspan="2"><br />{include file="main/subheader.tpl" title=$lng.lbl_shipping}
{if $product.count_shipping_rates_for_canada eq "0"}
<br />
<span style="color: red;">
{$lng.lbl_we_dont_ship_to_Canada_product_page}
</span>
<br />
{/if}
	</td>
</tr>

<tr> 
	{if $geid ne ''}<td width="15" class="TableSubHead">{if $product.is_variants eq 'Y'}&nbsp;{else}<input type="checkbox" value="Y" name="fields[weight]" />{/if}</td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_weight} ({$config.General.weight_symbol})</td>
	<td class="ProductDetails"> 
{if $product.is_variants eq 'Y'}
<b>{$lng.lbl_note}:</b> {$lng.txt_pvariant_edit_note|substitute:"href":$variant_href}
{else}
	<input type="text" name="weight" size="18" value="{ $product.weight|formatprice|default:$zero }" {if $manufacturer_feed_fields.weight.disable eq "Y"}readonly="readonly"{/if} />
{/if}
	</td>
</tr>

<tr> 
	{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[dimensions]" /></td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_shipping_dimensions} x:</td>
	<td class="ProductDetails"><input type="text" name="dimensionx" size="18" value="{$product.dim_x|default:0}" {if $manufacturer_feed_fields.dimensionx.disable eq "Y" || $manufacturer_feed_fields.dim_x.disable eq "Y"}readonly="readonly"{/if} /></td>
</tr>
    <tr>
        {if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[dimensions]" /></td>{/if}
        <td class="FormButton" nowrap="nowrap">{$lng.lbl_shipping_dimensions} y:</td>
        <td class="ProductDetails"><input type="text" name="dimensiony" size="18" value="{$product.dim_y|default:0}" {if $manufacturer_feed_fields.dimensiony.disable eq "Y" || $manufacturer_feed_fields.dim_y.disable eq "Y"}readonly="readonly"{/if} /></td>
    </tr>
    <tr>
        {if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[dimensions]" /></td>{/if}
        <td class="FormButton" nowrap="nowrap">{$lng.lbl_shipping_dimensions} z:</td>
        <td class="ProductDetails"><input type="text" name="dimensionz" size="18" value="{$product.dim_z|default:0}" {if $manufacturer_feed_fields.dimensionz.disable eq "Y" || $manufacturer_feed_fields.dim_z.disable eq "Y"}readonly="readonly"{/if} /></td>
    </tr>
<tr {if $usertype eq "P"}style="display: none;"{/if}>
	{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[shipping_freight]" /></td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_shipping_freight} ({$config.General.currency_symbol})</td>
	<td class="ProductDetails">
	<input type="text" name="shipping_freight" size="18" value="{$product.shipping_freight|formatprice|default:0.01}" {if $manufacturer_feed_fields.shipping_freight.disable eq "Y"}readonly="readonly"{/if} />
	</td>
</tr>

<tr> 
	{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[free_ship_zone]" /></td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_free_ship_destination}</td>
	<td class="ProductDetails">
	<select name="free_ship_zone" {if $manufacturer_feed_fields.free_ship_zone.disable eq "Y"} disabled="disabled"{/if}>
	<option value="-1"{if $product.free_ship_zone eq '-1'} selected="selected"{/if}>{$lng.lbl_no_free_ship}</option>
	<option value="0"{if $product.free_ship_zone eq '0'} selected="selected"{/if}>{$lng.lbl_zone_default}</option>
	{section name=zid loop=$shipping_zones}
	<option value="{$shipping_zones[zid].zoneid}"{if $product.free_ship_zone eq $shipping_zones[zid].zoneid} selected="selected"{/if}>{$shipping_zones[zid].zone_name}</option>
	{/section}
	</select> 

	{if $manufacturer_feed_fields.free_ship_zone.disable eq "Y"}
                <input type="hidden" name="free_ship_zone" value="{$product.free_ship_zone}" />
	{/if}
	</td>
</tr>

<tr> 
	{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[free_ship_text]" /></td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_free_ship_text}</td>
	<td class="ProductDetails"><input type="text" name="free_ship_text" size="45" class="InputWidth" value="{$product.free_ship_text}" {if $manufacturer_feed_fields.free_ship_text.disable eq "Y"}readonly="readonly"{/if} /></td>
</tr>

<tr {if $usertype eq "P"}style="display: none;"{/if}>
	{if $geid ne ''}<td width="15" class="TableSubHead">&nbsp;</td>{/if}
	<td colspan="2"><br />{include file="main/subheader.tpl" title=$lng.lbl_discount_settings}</td>
</tr>

{if $gcheckout_enabled}

<input type="hidden" name="valid_for_gcheckout" value="N" />
<tr {if $usertype eq "P"}style="display: none;"{/if}>
	{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[valid_for_gcheckout]" /></td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_gcheckout_product_valid}</td>
	<td class="ProductDetails">
	<input type="checkbox" name="valid_for_gcheckout" value="Y"{if $product.productid eq "" || $product.valid_for_gcheckout eq "Y"} checked="checked"{/if} {if $manufacturer_feed_fields.valid_for_gcheckout.disable eq "Y"}disabled="disabled"{/if} />

        {if $manufacturer_feed_fields.valid_for_gcheckout.disable eq "Y"}
                <input type="hidden" name="valid_for_gcheckout" value="{$product.valid_for_gcheckout}" />
        {/if}

	</td>
</tr>

{/if}

{if $active_modules.Extra_Fields ne ""}
{include file="modules/Extra_Fields/product_modify.tpl"}
{/if}

<tr {if $usertype eq "P"}style="display: none;"{/if}>
        {if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[discount_slope]" /></td>{/if}
        <td class="FormButton" nowrap="nowrap">{$lng.lbl_discount_slope}:</td>
        <td class="ProductDetails"><input type="text" name="discount_slope" size="18" value="{$product.discount_slope|formatprice|default:'0.60'}" {if $manufacturer_feed_fields.discount_slope.disable eq "Y"}readonly="readonly"{/if} /></td>
</tr>

<tr {if $usertype eq "P"}style="display: none;"{/if}>
        {if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[discount_table]" /></td>{/if}
        <td class="FormButton" nowrap="nowrap">{$lng.lbl_discount_table}:</td>
        <td class="ProductDetails"><input type="text" name="discount_table" id="discount_table" size="45" class="InputWidth" value="{if $smarty.get.mode_add_product eq 'y'}{$product.discount_table|escape|default:'2,3,4,6,8,12'}{else}{$product.discount_table|escape}{/if}" {if $manufacturer_feed_fields.discount_table.disable eq "Y"}readonly="readonly"{/if} /></td>
</tr>

<tr style="display: none;">
	{if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[discount_avail]" /></td>{/if}
	<td class="FormButton" nowrap="nowrap">{$lng.lbl_apply_global_discounts}</td>
	<td class="ProductDetails">
	<input type="checkbox" name="discount_avail" value="Y"{if $product.productid eq "" || $product.discount_avail eq "Y"} checked="checked"{/if} {if $manufacturer_feed_fields.discount_avail.disable eq "Y"}disabled="disabled"{/if} />
        {if $manufacturer_feed_fields.discount_avail.disable eq "Y"}
                <input type="hidden" name="discount_avail" value="{$product.discount_avail}" />
        {/if}
	</td>
</tr>

<tr {if $usertype eq "P"}style="display: none;"{/if}>
        {if $geid ne ''}<td width="15" class="TableSubHead"><input type="checkbox" value="Y" name="fields[generate_similar_products]" /></td>{/if}
        <td class="FormButton" nowrap="nowrap">Generate similar products:</td>
        <td class="ProductDetails">
        <input type="checkbox" name="generate_similar_products" value="Y"{if $product.productid eq "" || $product.generate_similar_products eq "Y"} checked="checked"{/if} {if $manufacturer_feed_fields.generate_similar_products.disable eq "Y"}disabled="disabled"{/if} />
        {if $manufacturer_feed_fields.generate_similar_products.disable eq "Y"}
                <input type="hidden" name="generate_similar_products" value="{$product.generate_similar_products}" />
        {/if}
        </td>
</tr>
        {if  $product.clone_parent_productid > 0}
        <tr>
                <td colspan="2"><p style="border: 1px solid threedlightshadow; background-color: #f4cccc; padding:4px;">This product is cloned from {$product.parent_product.productcode}</p></td>
        </tr>
        {/if}
        {if $product.child_products}
           <tr>
              <td colspan="2"><p style="border: 1px solid threedlightshadow; background-color: #f4cccc; padding:4px;">This product is parent for
                      {foreach from=$product.child_products item=child name=childproducts}
                        {$child.productcode}{if !$smarty.foreach.childproducts.last},&nbsp;{/if}
                      {/foreach}
                  </p>
              </td>
           </tr>
        {/if}

<tr>
	<td{if $geid ne ''} colspan="2"{/if}>&nbsp;</td>
	<td><br />
			<input type="button" value=" {$lng.lbl_save|strip_tags:false|escape} " onclick="javascript: if (check_froogle_upc_field(document.modifyform.upc)) document.modifyform.submit(); else return false;" />
		</td>
</tr>

</table>
</form>

{/capture}
{include file="dialog.tpl" title=$lng.lbl_product_details content=$smarty.capture.dialog extra='width="100%"'}

{*
{if $new_product ne "1" and $geid eq ''}
  <br />
  {include file="main/clean_urls.tpl" resource_name="productid" resource_id=$productid clean_url_action="product_modify.php" clean_urls_history_mode="clean_urls_history" clean_urls_history=$product.clean_urls_history}
{/if}
*}

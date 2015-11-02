{* brands.tpl, random *}
{include file="page_title.tpl" title=$lng.lbl_brands}

{include file="check_clean_url.tpl"}

{if $active_modules.HTML_Editor}
{include file="modules/HTML_Editor/editor.tpl"}
{/if}

{if $usertype eq "A" or $usertype eq "P"}
{assign var="administrate" value="Y"}
{/if}

<script type="text/javascript">
//<![CDATA[
var txt_manufacturers_delete_msg = "{$lng.txt_manufacturers_delete_msg|wm_remove|escape:javascript}";
var requiredFields = [
{if $config.SEO.clean_urls_enabled eq "Y"}, ['clean_url', "{$lng.lbl_clean_url|strip_tags|wm_remove|escape:javascript}", false]{/if}
]
//]]>
</script>



<br /><br />

{if $mode ne "brand_info"}

{capture name=dialog}

<table>
    <tr>
        <td>{$lng.lbl_alphabetic}:</td>
        <td>
            {if $word eq "num"}
                <span class="alp_selected">#</span>
            {else}
                <a href="brands.php?word=num">#</a>
            {/if}
            {foreach from=$words item=w}
                {if $word eq $w}
                    <span class="alp_selected">{$w|strtoupper}</span>
                {else}
                    <a href="brands.php?word={$w}">{$w|strtoupper}</a>
                {/if}
            {/foreach}
        </td>
    </tr>
</table>
 
<br />

{include file="customer/main/navigation.tpl"}

{if $brands ne ""}

<script type="text/javascript" language="JavaScript 1.2">
<!--
checkboxes_form = 'brandsform';
checkboxes = new Array({foreach from=$brands item=v key=k}{if $k > 0},{/if}'{if !($administrate eq "" and ($v.provider ne $login or $v.used_by_others gt 0))}to_delete[{$v.brandid}]{/if}'{/foreach});
 
-->
</script>
{include file="main/include_js.tpl" src="change_all_checkboxes.js"}

<div style="line-height:170%"><a href="javascript:change_all(true);">{$lng.lbl_check_all}</a> / <a href="javascript:change_all(false);">{$lng.lbl_uncheck_all}</a></div>

{/if}

<form action="brands.php" method="post" name="brandsform">
<input type="hidden" name="mode" value="update" />
<input type="hidden" name="page" value="{$page}" />
<input type="hidden" name="word" value="{$word}" />

<table cellpadding="3" cellspacing="1" width="100%">

<tr class="TableHead">
	{if $brands ne ""}<td width="10">&nbsp;</td>{/if}
	<td width="40%">{$lng.lbl_brand}</td>
	<td width="30%">{$lng.lbl_provider}</td>
	<td width="20%" align="center">{$lng.lbl_products}</td>
	<td width="30" align="center">{$lng.lbl_orderby}</td>
	<td width="30" align="center">{$lng.lbl_active}</td>
</tr>

{if $brands ne ""}

{foreach from=$brands item=v}

<tr{cycle values=", class='TableSubHead'"}>
	<td align="center"><input type="checkbox" name="to_delete[{$v.brandid}]"{if $administrate eq "" and ($v.provider ne $login or $v.used_by_others gt 0)} disabled="disabled"{/if} /></td>
	<td><b><a href="brands.php?brandid={$v.brandid}{if $page}&amp;page={$page}{/if}">{$v.brand}</a></b></td>
	<td>{if $v.is_provider eq 'Y'}{$v.provider_name}{else}{$lng.lbl_manuf_owner_lost}{/if}{if $administrate} ({$v.provider}){/if}</td>
	<td align="center">{$v.products_count|default:$lng.txt_not_available}{if $v.used_by_others gt 0}*{assign var="show_note" value="Y"}{/if}</td>
	<td align="center"><input type="text" name="records[{$v.brandid}][orderby]" size="5" value="{$v.orderby}"{if $administrate eq ""} disabled="disabled"{/if} /></td>
	<td align="center"><input type="checkbox" name="records[{$v.brandid}][avail]" value="Y"{if $v.avail eq "Y"} checked="checked"{/if}{if $administrate eq ""} disabled="disabled"{/if} /></td>
</tr>

{/foreach}

{if $show_note eq "Y"}
<tr>
	<td colspan="6"><br />{$lng.txt_brands_special_note}</td>
</tr>
{/if}

<tr>
	<td colspan="6" class="SubmitBox">
	<input type="button" value="{$lng.lbl_delete_selected|strip_tags:false|escape}" onclick="javascript: if (checkMarks(this.form, new RegExp('^to_delete\\[.+\\]', 'gi'))) if (confirm('{$lng.txt_brands_delete_msg|strip_tags}')) {ldelim} document.brandsform.mode.value='delete'; document.brandsform.submit(); {rdelim}" />
	<input type="submit" value="{$lng.lbl_update|strip_tags:false|escape}" />
	</td>
</tr>

{else}

<tr>
	<td colspan="6" align="center"><br />{$lng.txt_no_brands}</td>
</tr>

{/if}

<tr>
<td colspan="6"><br /><input type="button" value="{$lng.lbl_add_new_|strip_tags:false|escape}" onclick="javascript: self.location = 'brands.php?mode=add';" /></td>
</tr>

</table>

</form>

{include file="customer/main/navigation.tpl"}

{/capture}
{include file="dialog.tpl" title=$lng.lbl_brands_list content=$smarty.capture.dialog extra='width="100%"'}

{else}

{include file="main/include_js.tpl" src="main/popup_image_selection.js"}

{capture name=dialog}

<div align="right">
<table cellspacing="0" cellpadding="0">
<tr>
	<td>{include file="buttons/button.tpl" button_title=$lng.lbl_brands_list href="brands.php?page=`$page`&word=num"}</td>
{if $brand.brandid}
	<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
	<td>{include file="buttons/button.tpl" button_title=$lng.lbl_add_brand href="brands.php?mode=add&page=`$page`"}</td>
{/if}
</tr>
</table>
</div>

{if $administrate eq "" and $brand.used_by_others gt 0}
<br />
<font class="ErrorMessage">{$lng.txt_brands_warning}</font>
<br />
{/if}

<br />

{if $administrate eq "" and $login ne $brand.provider and $smarty.get.mode ne "add"}
{assign var="disabled" value=" disabled"}
{/if}

{if $brand.brandid ne ''}
{include file="main/language_selector.tpl" script="brands.php?brandid=`$brand.brandid`&"}
{/if}
<form action="brands.php" method="post" enctype="multipart/form-data" name="brand" {* onsubmit='javascript: return checkRequired(requiredFields){if $config.SEO.clean_urls_enabled eq "Y"} &amp;&amp;checkCleanUrl(document.brand.clean_url){/if};' *}>
<input type="hidden" name="mode" value="details" />
<input type="hidden" name="brandid" value="{$brand.brandid}" />
<input type="hidden" name="page" value="{$page}" />

<table cellpadding="3" cellspacing="1" width="100%">

<tr>
    <td align="center" class="TopLabel" colspan="3">
        {if $brand.customer_url ne ""}
        <span class="detail-title">
            <a href="{$brand.customer_url}" title="" target="_blank">{$lng.lbl_current_brand}: "{$brand.brand}"</a>
        </span>
        {else}{$lng.lbl_current_brand}: "{$brand.brand}"
        {/if}
    </td>
</tr>

<tr>
	<td width="20%" class="FormButton">{$lng.lbl_brand}:</td>
	<td><font class="Star">*</font></td>
	<td width="80%"><input type="text" name="brand" size="50" value="{$brand.brand}" style="width:80%"{$disabled} {* {if $config.SEO.clean_urls_enabled eq "Y"}onchange="javascript: if (this.form.clean_url.value == '') copy_clean_url(this, this.form.clean_url)"{/if} *} /></td>
</tr>

{if $brand.brandid ne ""}
  {include file="main/clean_url_field.tpl" clean_url=$brand.clean_url show_req_fields="Y" clean_urls_history=$brand.clean_urls_history clean_url_fill_error=$top_message.clean_url_fill_error}
{/if}

<tr>
	<td class="FormButton">{$lng.lbl_logo}:</td>
	<td>&nbsp;</td>
	{if $brand.is_image eq 'Y'}{assign var="no_delete" value=""}{else}{assign var="no_delete" value="Y"}{/if}
	<td>{include file="main/edit_image.tpl" type="B" id=$brand.brandid delete_url="brands.php?mode=delete_image&brandid=`$brand.brandid`" button_name=$lng.lbl_save no_delete=$no_delete source="PD"}</td>
</tr>

<tr>
	<td class="FormButton">{$lng.lbl_description}:</td>
	<td>&nbsp;</td>
	<td>
{include file="main/textarea.tpl" name="descr" cols=55 rows=10 class="InputWidth" data=$brand.descr width="80%" btn_rows=3}
	</td>
</tr>

<tr>
	<td class="FormButton">{$lng.lbl_url} (include http://):</td>
	<td>&nbsp;</td>
	<td><input type="text" size="50" name="url" value="{$brand.url}" style="width:80%"{$disabled} /> {if $brand.url ne ""}<a target="_blank" href="{$brand.url}" style="color: #1F08F8;">Website</a>{/if}</td>
</tr>

<tr>
        <td class="FormButton">Link to us URL (include http://):</td>
        <td>&nbsp;</td>
        <td><input type="text" size="50" name="link_to_us_url" value="{$brand.link_to_us_url}" style="width:80%"{$disabled} /> {if $brand.link_to_us_url ne ""}<a target="_blank" href="{$brand.link_to_us_url}" style="color: #1F08F8;">Link to us webpage</a>{/if}</td>
</tr>

<tr>
        <td class="FormButton">Customer service name:</td>
        <td>&nbsp;</td>
        <td><input type="text" size="50" name="customer_service_name" value="{$brand.customer_service_name}" style="width:80%"{$disabled} /></td>
</tr>

<tr>
        <td class="FormButton">Customer service phone:</td>
        <td>&nbsp;</td>
        <td><input type="text" size="50" name="customer_service_phone" value="{$brand.customer_service_phone}" style="width:80%"{$disabled} /></td>
</tr>

<tr>
        <td class="FormButton">Customer service email:</td>
        <td>&nbsp;</td>
        <td><input type="text" size="50" name="customer_service_email" value="{$brand.customer_service_email}" style="width:80%"{$disabled} /></td>
</tr>

<tr>
        <td class="FormButton">Brand disclaimer:</td>
        <td>&nbsp;</td>
        <td>
<textarea style="width: 80%" name="disclaimer_text" cols="60" rows="4">{$brand.disclaimer_text}</textarea>
        </td>
</tr>


{if $administrate eq "Y"}
<tr>
	<td class="FormButton">{$lng.lbl_orderby}:</td>
	<td>&nbsp;</td>
	<td><input type="text" name="orderby" size="5" value="{$brand.orderby|default:"10"}" /></td>
</tr>

<tr>
	<td class="FormButton">{$lng.lbl_availability}:</td>
	<td>&nbsp;</td>
	<td><input type="checkbox" name="avail" value="Y"{if $brand.avail eq 'Y' || $brand.brandid eq ''} checked="checked"{/if} /></td>
</tr>
{/if}

<tr>
        <td colspan="3"><br /><br />{include file="main/subheader.tpl" title="SEO options"}</td>
</tr>

<tr>
        <td class="FormButton">Title (&lt;title&gt;):</td>
        <td>&nbsp;</td>
        <td><input type="text" name="title" style="width: 80%;" value="{$brand.title}" /></td>
<tr>

<tr>
        <td class="FormButton">SEO brand name (&lt;H1&gt;):</td>
        <td>&nbsp;</td>
        <td><input type="text" name="SEO_brand_name_h1" style="width: 80%;" value="{$brand.SEO_brand_name_h1}" /></td>
<tr>
       
<tr>
        <td class="FormButton">SEO (&lt;H2&gt;):</td>
        <td>&nbsp;</td>
        <td><input type="text" name="SEO_h2" style="width: 80%;" value="{$brand.SEO_h2}" /></td>
<tr>

<tr>
        <td class="FormButton">SEO meta description:</td>
        <td>&nbsp;</td>
        <td><textarea cols="65" rows="4" name="meta_descr">{$brand.meta_descr}</textarea></td>
</tr>
 
<tr>
	<td colspan="2">&nbsp;</td>
	<td><input type="submit" value=" {$lng.lbl_save|strip_tags:false|escape} "{$disabled} /></td>
</tr>

</table>
</form>

{/capture}
{include file="dialog.tpl" title=$lng.lbl_brand_details content=$smarty.capture.dialog extra='width="100%"'}

{*
{if $mode eq "brand_info" and $brand.brandid ne ''}
  <br />
  {include file="main/clean_urls.tpl" resource_name="brandid" resource_id=$brand.brandid clean_url_action="brands.php" clean_urls_history_mode="clean_urls_history" clean_urls_history=$brand.clean_urls_history}
{/if}
*}

{/if}


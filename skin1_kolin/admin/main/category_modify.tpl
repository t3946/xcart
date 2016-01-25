{* $Id: category_modify.tpl,v 1.45.2.2 2006/07/11 08:39:26 svowl Exp $ *}
<script type="text/javascript" language="JavaScript 1.2">
<!--
window.name = "catmodwin";

{literal}
function updateCategoryIds() {
	var elm = document.getElementById('additional_parent_select');
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
	output = document.getElementById('additional_parent_input');
	if (output) {
		output.value = txt;
	}
}
{/literal}
-->
</script>

{include file="main/include_js.tpl" src="main/popup_image_selection.js"}



{*
{if $active_modules.HTML_Editor}
{include file="modules/HTML_Editor/editor.tpl"}
{/if}
*}

<script src="{$SkinDir}/tinymce/js/tinymce/tinymce.min.js" type="text/javascript"></script>

<script type="text/javascript">
//<![CDATA[
{literal}

tinymce.init({
    selector: "textarea.new_editor",
    resize: "both",
    plugins: [
        "advlist autolink lists link image charmap print preview anchor",
        "searchreplace visualblocks code fullscreen",
        "insertdatetime media table contextmenu paste"
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
    forced_root_block : false,
    force_br_newlines : true,
    force_p_newlines : false,
    convert_urls: false,
    relative_urls: false
});

{/literal}
//]]>
</script>


{if $section ne 'lng'}

{include file="check_clean_url.tpl"}

<script type="text/javascript">
//<![CDATA[
var requiredFields = [
  ['category_name', "{$lng.lbl_category|strip_tags|wm_remove|escape:javascript}", false]{if $config.SEO.clean_urls_enabled eq "Y"}, ['clean_url', "{$lng.lbl_clean_url|strip_tags|wm_remove|escape:javascript}", false]{/if}
]
//]]>
</script>

{if $mode eq "add"}
{assign var="title" value=$lng.lbl_add_category}
{else}
{assign var="title" value=$lng.lbl_modify_category}
{/if}

{include file="page_title.tpl" title=$title}
<br />

{include file="dialog_tools.tpl"}
<br />

{capture name=dialog}
{include file="admin/main/location.tpl"}
<table width="100%">

<tr>
	<td align="center" class="TopLabel">
		{if $current_category.avail neq "N"}
		<span class="detail-title">
			<a href="{$current_category.customer_url}" title="" target="_blank">{$lng.lbl_current_category}: "{$current_category.category|default:$lng.lbl_root_level}"</a>
		</span>
		{else}
	    {$lng.lbl_current_category}: "{$current_category.category|default:$lng.lbl_root_level}"
		<div class="ErrorMessage">{$lng.txt_category_disabled}</div>
		{/if}
	</td>
</tr>

</table>

<br /><br />

<form name="addform" action="category_modify.php" method="post" enctype="multipart/form-data" {* onsubmit="javascript: return checkRequired(requiredFields){if $config.SEO.clean_urls_enabled eq "Y"} &amp;&amp;checkCleanUrl(document.addform.clean_url){/if}" *} >
<input type="hidden" name="mode" value="{$mode}" />
{if $mode eq "add"}
<input type="hidden" name="parent" value="{$cat}" />
{else}
<input type="hidden" name="cat" value="{$cat}" />
{/if}

<table cellpadding="3" cellspacing="1" width="100%">

<tr>
	<td height="10" class="FormButton" nowrap="nowrap">{$lng.lbl_category_icon}:</td>
	<td width="10" height="10">&nbsp;</td>
	<td height="10">
    {if $mode eq 'add'}
        {include file="main/edit_image.tpl" type="C" delete_url="category_modify.php?mode=delete_icon&amp;cat=`$cat`" button_name=$lng.lbl_save source='PD'}
    {else}
        {include file="main/edit_image.tpl" type="C" id=$cat delete_url="category_modify.php?mode=delete_icon&amp;cat=`$cat`" button_name=$lng.lbl_save source='PD'}
    {/if}
	</td>
</tr>

<tr>
	<td height="10" class="FormButton" nowrap="nowrap">{$lng.lbl_position}:</td>
	<td width="10" height="10">&nbsp;</td>
	<td height="10">
<input type="text" name="order_by" size="5" value="{if $category_error ne ""}{$smarty.post.order_by}{elseif $mode ne "add"}{$current_category.order_by}{/if}" />
</td>
</tr>

<tr>
	<td height="10" class="FormButton" nowrap="nowrap">Category name:</td>
	<td width="10" height="10"><font class="CustomerMessage">*</font></td>
	<td height="10">
<input type="text" name="category_name" id="category_name" maxlength="255" size="94" value="{if $category_error ne ""}{$smarty.post.category_name|escape:"html"}{elseif $mode ne "add"}{$current_category.category|escape:"html"}{/if}" {* {if $config.SEO.clean_urls_enabled eq "Y"}onchange="javascript: if (this.form.clean_url.value == '') copy_clean_url(this, this.form.clean_url)"{/if} *} />
{if $category_error ne ""}
{if $category_error eq "2"}
<font color="red">&lt;&lt; {$lng.lbl_category_already_exists}</font>
{else}
<font color="red">&lt;&lt; {$lng.lbl_category_wrong_value}</font>
{/if}
{/if}
&nbsp;{include file="capitalize_js.tpl" id="category_name"}
	</td>
</tr>

{if $mode ne "add"}
  {include file="main/clean_url_field.tpl" clean_url=$current_category.clean_url show_req_fields="Y" clean_urls_history=$current_category.clean_urls_history clean_url_fill_error=$top_message.clean_url_fill_error}
{else}
{*  {include file="main/clean_url_field.tpl" clean_url="" show_req_fields="Y" clean_urls_history=""} *}
{/if}

<tr>
	<td height="10" class="FormButton" nowrap="nowrap" valign="middle">{$lng.lbl_description}:</td>
	<td width="10" height="10"><font class="CustomerMessage"></font></td>
	<td height="10">
{if $category_error ne ""}{assign var="data" value=$smarty.post.description}{elseif $mode ne "add"}{assign var="data" value=$current_category.description}{/if}

{*
{include file="main/textarea.tpl" name="description" cols=65 rows=15}
*}

  <textarea rows="15" cols="65" name="description" style="width: 80%;" class="new_editor">{$current_category.description}</textarea>
	</td>
</tr>

<tr>
    <td height="10" class="FormButton" nowrap="nowrap">{$lng.lbl_is_bold}:</td>
	<td width="10" height="10"><font class="CustomerMessage"></font></td>
	<td height="10"><input type="checkbox" name="is_bold" value="Y"{if $current_category.is_bold eq 'Y'} checked="checked"{/if} /></td>
</tr>

<tr>
	<td height="10" class="FormButton" nowrap="nowrap">{$lng.lbl_availability}:</td>
	<td width="10" height="10"><font class="CustomerMessage"></font></td>
	<td height="10">
<select name="avail">
	<option value='Y' {if ($current_category.avail eq 'Y')} selected="selected"{/if}>{$lng.lbl_enabled}</option>
	<option value='N' {if ($current_category.avail eq 'N')} selected="selected"{/if}>{$lng.lbl_disabled}</option>
</select>
	</td>
</tr>

<tr>
    <td height="10" class="FormButton" nowrap="nowrap">Supplemental category:</td>
        <td width="10" height="10"><font class="CustomerMessage"></font></td>
        <td height="10"><input type="checkbox" name="supplemental_category" value="Y"{if $current_category.supplemental_category eq 'Y' || $supplemental_category_section eq "Y"} checked="checked"{/if} /></td>
</tr>

<tr {if $current_category.supplemental_category eq 'Y' || $supplemental_category_section eq "Y"}style="display: none;"{/if}>
    <td height="10" class="FormButton" nowrap="nowrap">Ready to classify:</td>
        <td width="10" height="10"><font class="CustomerMessage"></font></td>
        <td height="10"><input type="checkbox" name="pc_ready_to_classify" value="Y"{if $current_category.pc_ready_to_classify eq 'Y'} checked="checked"{/if} /></td>
</tr>

<tr {if $current_category.supplemental_category eq 'Y' || $supplemental_category_section eq "Y"}style="display: none;"{/if}>
        <td height="10" class="FormButton" nowrap="nowrap">Category classify weight:</td>
        <td width="10" height="10">&nbsp;</td>
        <td height="10">
<input type="text" name="pc_category_weight" size="15" value="{$current_category.pc_category_weight}" disabled="disabled" />
</td>
</tr>

<tr {if $current_category.supplemental_category eq 'Y' || $supplemental_category_section eq "Y"}style="display: none;"{/if}>
        <td height="10" class="FormButton" nowrap="nowrap">Category Z parameter:</td>
        <td width="10" height="10">&nbsp;</td>
        <td height="10">
<input type="text" name="pc_z" size="15" value="{$current_category.pc_z}" disabled="disabled" />
</td>
</tr>



<tr>
        <td colspan="3"><br /><br />{include file="main/subheader.tpl" title="SEO options"}</td>
</tr>

<tr>
    <td height="10" class="FormButton" nowrap="nowrap">Prevent index products:</td>
        <td width="10" height="10"><font class="CustomerMessage"></font></td>
        <td height="10"><input type="checkbox" name="prevent_index_products" value="Y"{if $current_category.prevent_index_products eq 'Y'} checked="checked"{/if} /></td>
</tr>

<tr>
    <td height="10" class="FormButton" nowrap="nowrap">Prevent index category page:</td>
        <td width="10" height="10"><font class="CustomerMessage"></font></td>
        <td height="10"><input type="checkbox" name="prevent_index_category_page" value="Y"{if $current_category.prevent_index_category_page eq 'Y'} checked="checked"{/if} /></td>
</tr>

<tr>
        <td height="10" class="FormButton" nowrap="nowrap">Title (&lt;title&gt;):</td>
        <td width="10" height="10">&nbsp;</td>
        <td height="10">
<input type="text" name="title_tag" style="width: 80%;" value="{if $category_error ne ""}{$smarty.post.title_tag}{elseif $mode ne "add"}{$current_category.title_tag}{/if}" />
</td>
</tr>

<tr>
        <td height="10" class="FormButton" nowrap="nowrap">SEO category name (&lt;H1&gt;):</td>
        <td width="10" height="10">&nbsp;</td>
        <td height="10">
<input type="text" name="SEO_category_name" size="45" style="width: 98%;" value="{$current_category.SEO_category_name}" />
</td>
</tr>

<tr>
{*        <td height="10" class="FormButton" nowrap="nowrap">SEO (&lt;H2&gt;):</td> *}
        <td height="10" class="FormButton" nowrap="nowrap">SEO Description Category:</td>
        <td width="10" height="10">&nbsp;</td>
        <td height="10">
{*
<input type="text" name="SEO_h2" size="45" style="width: 98%;" value="{$current_category.SEO_h2}" />
<textarea rows="5" cols="65" name="SEO_h2" style="width: 80%;" class="new_editor">{$current_category.SEO_h2}</textarea>
*}
<textarea cols="65" rows="5" name="SEO_h2" style="width: 80%;">{$current_category.SEO_h2}</textarea>

</td>
</tr>

<tr>
	<td height="10" class="FormButton" nowrap="nowrap">{$lng.lbl_meta_keywords}:</td>
	<td width="10" height="10"><font class="FormButtonOrange"></font></td>
	<td height="10">
<textarea cols="65" rows="4" name="meta_keywords">{if $category_error ne ""}{$smarty.post.meta_keywords|escape:"html"}{elseif $mode ne "add"}{$current_category.meta_keywords|escape:"html"}{/if}</textarea>
	</td>
</tr>

<tr>
	<td height="10" class="FormButton" nowrap="nowrap">{$lng.lbl_meta_description}:</td>
	<td width="10" height="10"><font class="FormButtonOrange"></font></td>
	<td height="10">
<textarea cols="65" rows="4" name="meta_descr">{if $category_error ne ""}{$smarty.post.meta_descr|escape:"html"}{elseif $mode ne "add"}{$current_category.meta_descr|escape:"html"}{/if}</textarea>
	</td>
</tr>


<tr>
        <td height="10" class="FormButton" nowrap="nowrap">Google product category:</td>
        <td width="10" height="10">&nbsp;</td>
        <td height="10">
<input type="text" name="google_product_category" size="45" style="width: 98%;" value="{$current_category.google_product_category}" {if $current_category.pc_ready_to_classify ne 'Y'}readonly="readonly"{/if}/>
</td>
</tr>


<tr>
	<td colspan="2" class="FormButton">&nbsp;</td>
	<td class="SubmitBox"><input type="submit" value=" {$lng.lbl_save|strip_tags:false|escape} " /></td>
</tr>

{if $mode ne "add"}

<tr>
	<td colspan="3"><br /><br />{include file="main/subheader.tpl" title=$lng.lbl_category_location_title}</td>
</tr>

<tr>
	<td height="10" class="FormButtonNotBold" nowrap="nowrap">{$lng.lbl_main_parent_category}:</td>
	<td width="10" height="10"><font class="FormButtonOrange"></font></td>
	<td height="10">
<select name="cat_location_text" style="width: 80%;" onchange="javascript: document.getElementById('main_parent_input').value=this.options[this.selectedIndex].value;">
	<option value="0">{$lng.lbl_root_level}</option>
{foreach from=$allcategories item=c key=catid}
{if $c.moving_enabled && $catid eq $current_category.parentid}
	<option value="{$catid}"{if $catid eq $current_category.parentid} selected="selected"{/if}>{$c.category_path}</option>
{/if}
{/foreach}
</select>
	</td>
</tr>

<tr>
	<td height="10" class="FormButton" nowrap="nowrap">{$lng.lbl_main_parent_category_id}:</td>
	<td width="10" height="10"><font class="FormButtonOrange"></font></td>
	<td height="10"><input type="text" name="cat_location" id="main_parent_input" value="{$current_category.parentid}" /></td>
</tr>

<tr {if $current_category.supplemental_category eq 'Y' || $supplemental_category_section eq "Y"}style="display: none;"{/if}>
	<td height="10" class="FormButtonNotBold" nowrap="nowrap">{$lng.lbl_additional_parent_categories}:</td>
	<td width="10" height="10"><font class="FormButtonOrange"></font></td>
	<td height="10">
<select name="additional_cat_location_text" id="additional_parent_select" multiple="multiple" size="8" style="width: 80%;" onchange="javascript: updateCategoryIds();">
{foreach from=$allcategories item=c key=catid}
{if $c.moving_enabled && $c.additional_parent_selected}
	<option value="{$catid}" selected="selected">{$c.category_path}</option>
{/if}
{/foreach}
</select>
	</td>
</tr>

<tr {if $current_category.supplemental_category eq 'Y' || $supplemental_category_section eq "Y"}style="display: none;"{/if}>
	<td height="10" class="FormButton" nowrap="nowrap">{$lng.lbl_additional_parent_categories_ids}:</td>
	<td width="10" height="10"><font class="FormButtonOrange"></font></td>
	<td height="10"><input type="text" name="additional_cat_location" id="additional_parent_input" style="width: 80%;" value="{strip}
{assign var="need_comma" value=false}
{foreach from=$allcategories item=c key=catid}
{if $c.moving_enabled && $c.additional_parent_selected}{if $need_comma},{else}{assign var="need_comma" value=true}{/if}{$c.categoryid}{/if}
{/foreach}
{/strip}" /></td>
</tr>
<tr>
	<td colspan="2" class="FormButton">&nbsp;</td>
	<td class="SubmitBox"><input type="button" value="{$lng.lbl_update|strip_tags:false|escape}" onclick="javascript: submitForm(this, 'move');" /></td>
</tr>

{/if}

</table>
</form>

{/capture}
{include file="dialog.tpl" title=$title content=$smarty.capture.dialog extra='width="100%"'}

{*
{if $section ne "lng" and $mode ne "add" and $cat gt 0}
  <br />
  {include file="main/clean_urls.tpl" resource_name="cat" resource_id=$cat clean_url_action="category_modify.php" clean_urls_history_mode="clean_urls_history" clean_urls_history=$current_category.clean_urls_history}
{/if}
*}

<br />
<a name="seo_module"></a>
{capture name=dialog}
<form name="addform2" action="category_modify.php" method="post" >
<input type="hidden" name="mode" value="mode_add_one_keyphrase_per_line" />
<input type="hidden" name="cat" value="{$cat}" />

<table cellpadding="3" cellspacing="1" width="100%" border="0">
<tr>
        <td colspan="3"><br /><br />{include file="main/subheader.tpl" title="Add semantic keyphrases"}</td>
</tr>
<tr>
<td width="10" class="FormButton" nowrap="nowrap">Enter one keyphrase per line:</td>
<td width="10" height="10"><font class="FormButtonOrange"></font></td>
<td align="left"><textarea style="width: 98%;" cols="65" rows="4" name="add_one_keyphrase_per_line"></textarea></td>
</tr>
<tr>
<td colspan="3" align="right">
<input type="submit" name="Save" value="Save">
</td>
</tr>
</table>

</form>

{if $seo_categories_keyphrases ne ""}
<form name="addform3" action="category_modify.php" method="post" >
<input type="hidden" name="mode" value="" />
<input type="hidden" name="cat" value="{$cat}" />

<table cellpadding="3" cellspacing="1" width="100%" border="0">
<tr>
<td colspan="4"><br /><br />{include file="main/subheader.tpl" title="List of semantic keyphrases / Linked-in categories"}</td>
</tr>
<tr class="TableHead">
<td><b>Id</b></td><td><b>Keyphrase</b></td><td><b>Linked-in category</b></td><td><b>Select</b></td>
</tr>

{foreach from=$seo_categories_keyphrases item=v key=k}
<tr {cycle values=', class="TableSubHead"'}>
<td width="20">
{$v.id}
</td>
<td width="47%">
<input type="text" name="post_seo_categories_keyphrases[keyphrases][{$v.id}]" value="{$v.keyphrase|escape}" style="width: 98%;" />
</td>
<td width="49%">
{if $v.cat_id_name_arr ne ""}
	{foreach from=$v.cat_id_name_arr item=vv key=kk}
	        <a target="_blank" style="color: blue;" href="category_modify.php?cat={$kk}">{$vv}</a><br />
	{/foreach}
{else}
not used
{/if}
</td>
<td width="*" align="center">
<input type="checkbox" name="post_seo_categories_keyphrases[select][{$v.id}]" value="Y" />
</td>
</tr>
{/foreach}

<tr>
<td colspan="4">
<input type="button" value="{$lng.lbl_update|strip_tags:false|escape}" onclick="javascript: submitForm(this, 'mode_update_categories_keyphrases');" />
<input type="button" value="Delete" onclick="javascript: submitForm(this, 'mode_delete_categories_keyphrases');" />
</td>
</tr>

</table>

</form>
{/if}


<form name="addform4" action="category_modify.php" method="post" >
<input type="hidden" name="mode" value="" />
<input type="hidden" name="cat" value="{$cat}" />

<table cellpadding="3" cellspacing="1" width="100%" border="0">
<tr>
        <td colspan="3"><br /><br />{include file="main/subheader.tpl" title="Linked-out categories"}</td>
</tr>
<tr class="TableHead">
<td width="48%"><b>Linked-out category</b></td><td width="48%"><b>Linked-out category keyphrase</b></td><td><b>Select</b></td>
</tr>

{foreach from=$linked_out_category_indexes item=v key=k}
<tr {cycle values=', class="TableSubHead"'}>
<td>
	{assign var="linked_out_category_name" value="linked_out_category_name_`$v`"}
	{assign var="linked_out_category_id" value="linked_out_category_id_`$v`"}

	{if $current_category.$linked_out_category_name eq ""}
	<input type="text" name="linked_out_category_id_{$v}" value="{$current_category.$linked_out_category_id}" />
	{else}
	<a target="_blank" style="color: blue;" href="category_modify.php?cat={$current_category.$linked_out_category_id}">{$current_category.$linked_out_category_name}</a>
{*
	<input type="hidden" name="linked_out_category_id_{$v}" value="{$current_category.$linked_out_category_id}" />
*}
	{/if}
</td>
<td>
	{if $current_category.$linked_out_category_name ne ""}

	        {assign var="linked_out_category_keyphrase_selected" value="linked_out_category_keyphrase_selected_`$v`"}

		{if $current_category.$linked_out_category_keyphrase_selected ne ""}
			{$current_category.$linked_out_category_keyphrase_selected}
{*
			{foreach from=$current_category.$linked_out_category_keyphrase_selected_arr item=vv key=kk}
				{$vv}<br />
			{/foreach}
*}
		{else}

	        	{assign var="linked_out_category_keyphrase" value="linked_out_category_keyphrase_`$v`"}

			{if $current_category.$linked_out_category_keyphrase ne ""}
		         <select name="linked_out_category_keyphrase_id_{$v}[]" size="5" style="width: 98%;">
         		   {foreach from=$current_category.$linked_out_category_keyphrase item=vv key=kk}
                		<option value="{$vv.id}">{$vv.keyphrase}</option>
		            {/foreach}
        		 </select>
			{/if}
		{/if}
	{else}
		not set
	{/if}
</td>
<td align="center">
	<input type="checkbox" name="post_linked_out_category_clear_{$v}" value="Y" />
</td>
</tr>
{/foreach}

<tr>
<td colspan="3">
<input type="button" value="{$lng.lbl_update|strip_tags:false|escape}" onclick="javascript: submitForm(this, 'mode_update_linked_out_category');" />
<input type="button" value="Clear selected" onclick="javascript: submitForm(this, 'mode_clear_linked_out_category');" />
</td>
</tr>

</table>

</form>



{/capture}
{include file="dialog.tpl" title="SEO module" content=$smarty.capture.dialog extra='width="100%"'}


{elseif $section eq 'lng' && $mode ne "add" && $cat > 0}

{include file="admin/main/category_lng.tpl"}

{/if}


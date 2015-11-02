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
{if $active_modules.HTML_Editor}
{include file="modules/HTML_Editor/editor.tpl"}
{/if}

{if $section ne 'lng'}

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
	<td align="center" class="TopLabel">{if $current_category.avail neq "N"}<a href="{$current_category.customer_url}" title="" target="_blank">{/if}{$lng.lbl_current_category}: "{$current_category.category|default:$lng.lbl_root_level}"
{if $current_category.avail eq "N"}
<div class="ErrorMessage">{$lng.txt_category_disabled}</div>
{else}
</a>
{/if}
	</td>
</tr>

</table>

<br /><br />

<form name="addform" action="category_modify.php" method="post" enctype="multipart/form-data">
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
{include file="main/edit_image.tpl" type="C" id=$cat delete_url="category_modify.php?mode=delete_icon&amp;cat=`$cat`" button_name=$lng.lbl_save}
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
	<td height="10" class="FormButton" nowrap="nowrap">{$lng.lbl_category}:</td>
	<td width="10" height="10"><font class="CustomerMessage">*</font></td>
	<td height="10">
<input type="text" name="category_name" id="category_name" maxlength="255" size="94" value="{if $category_error ne ""}{$smarty.post.category_name|escape:"html"}{elseif $mode ne "add"}{$current_category.category|escape:"html"}{/if}" />
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

<tr>
	<td height="10" class="FormButton" nowrap="nowrap" valign="top">{$lng.lbl_description}:</td>
	<td width="10" height="10"><font class="CustomerMessage"></font></td>
	<td height="10">
{if $category_error ne ""}{assign var="data" value=$smarty.post.description}{elseif $mode ne "add"}{assign var="data" value=$current_category.description}{/if}
{include file="main/textarea.tpl" name="description" cols=65 rows=15}
	</td>
</tr>

<tr>
	<td height="10" class="FormButton" nowrap="nowrap">{$lng.lbl_membership}:</td>
	<td width="10" height="10"><font class="FormButtonOrange"></font></td>
	<td height="10">{include file="main/membership_selector.tpl" data=$current_category}</td>
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
{*
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
*}
<tr>
	<td colspan="2" class="FormButton">&nbsp;</td>
	<td class="SubmitBox"><input type="submit" value=" {$lng.lbl_save|strip_tags:false|escape} " /></td>
</tr>

{if $mode ne "add"}

<tr>
	<td colspan="3"><br /><br />{include file="main/subheader.tpl" title=$lng.lbl_category_location_title}</td>
</tr>

<tr>
	<td height="10" class="FormButton" nowrap="nowrap">{$lng.lbl_main_parent_category}:</td>
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

<tr>
	<td height="10" class="FormButton" nowrap="nowrap">{$lng.lbl_additional_parent_categories}:</td>
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

<tr>
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

{elseif $section eq 'lng' && $mode ne "add" && $cat > 0}

{include file="admin/main/category_lng.tpl"}

{/if}


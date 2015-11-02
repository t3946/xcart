{* $Id: page_edit.tpl,v 1.16.2.2 2006/09/18 08:17:54 twice Exp $ *}

<script type="text/javascript" language="JavaScript 1.2">
<!--
window.name = "catmodwin";
-->
</script>


{include file="main/include_js.tpl" src="main/popup_image_selection.js"}


{include file="page_title.tpl" title=$lng.lbl_static_pages}
{if $active_modules.HTML_Editor}
{include file="modules/HTML_Editor/editor.tpl"}
{/if}

<script type="text/javascript" language="JavaScript 1.2">
<!--
var txt_fill_page_name_field = "{$lng.txt_fill_page_name_field|strip_tags}";
var txt_fill_page_content_field = "{$lng.txt_fill_page_content_field|strip_tags}";
var txt_fill_page_file_field = "{$lng.txt_fill_page_file_field|strip_tags}";
var is_empty_filename = {if $page_data.filename eq ""}true{else}false{/if};
{literal}
function formSubmit() {
	if (document.pagesform.pagetitle.value == "") {
		document.pagesform.pagetitle.focus();
		alert(txt_fill_page_name_field);
		return false;

     } else if (document.pagesform.pagecontent.value == "") {
		document.pagesform.pagecontent.focus();
		alert(txt_fill_page_content_field);
		return false;

	} else if (is_empty_filename && document.pagesform.filename && document.pagesform.filename.value == "") {
		document.pagesform.filename.focus();
		alert(txt_fill_page_file_field);
		return false;
	}

  {/literal}
  {if $config.SEO.clean_urls_enabled eq "Y"}
  if (!checkCleanUrl(document.pagesform.clean_url))
    return false;
  {/if}
  {literal}


	return true;
}
{/literal}
-->
</script>

{include file="check_clean_url.tpl"}

{$lng.txt_edit_static_page_top_text}

<br /><br />

{capture name=dialog}

<div align="right">{include file="buttons/button.tpl" button_title=$lng.lbl_pages_list href="pages.php"}</div>

<form action="pages.php" method="post" name="pagesform" onsubmit="javascript: return formSubmit();" enctype="multipart/form-data">
<input type="hidden" name="mode" value="modified" />
<input type="hidden" name="pageid" value="{$smarty.get.pageid|escape:"html"}" />
<input type="hidden" name="level" value="{$level}" />

<table cellpadding="3" cellspacing="1" width="100%">
<tr>
	<td align="center" class="TopLabel" colspan="3">
		{if $page_data.active eq "Y"}
		<span class="detail-title">
		<a href="{if $page_data.orderby gt 500}http://www.s3stores.com/index.php?pageid={$smarty.get.pageid|escape:'html'}{else}{$page_data.customer_url}{/if}" target="_blank">{$lng.lbl_current_static_page}: "{$page_data.title}"</a>
		</span>
		{else}
		{$lng.lbl_current_static_page}: "{$page_data.title}"
		<div class="ErrorMessage">{$lng.txt_static_page_disabled}</div>
		{/if}
	</td>
</tr>
<tr>
	<td>{$lng.lbl_level}:</td>
	<td><font class="Star"></font></td>
	<td>{if $level eq "E"}{$lng.lbl_embedded}{elseif $level eq "R"}{$lng.lbl_root}{/if}</td>
</tr>

<tr>
	<td>{$lng.lbl_page_file}:</td>
	<td><font class="Star"></font></td>
	<td><i>{$page_path}</i>{if $page_data.filename eq ""}<input type="text" size="25" name="filename" value="{$default_filename}" />{/if}</td>
</tr>

<tr>
	<td>{$lng.lbl_page_name}:</td>
	<td><font class="Star">*</font></td>
	<td><input type="text" name="pagetitle" value="{$page_data.title|default:"Page$default_index"}" size="45" {* {if $config.SEO.clean_urls_enabled eq "Y"}onchange="javascript: if (this.form.clean_url.value == '') copy_clean_url(this, this.form.clean_url)"{/if} *} /></td>
</tr>

{if $level eq "E" && $pageid ne ""}
  {include file="main/clean_url_field.tpl" clean_url=$page_data.clean_url|default:$default_clean_url show_req_fields="Y" clean_urls_history=$page_data.clean_urls_history clean_url_fill_error=$top_message.clean_url_fill_error}
{/if}

<tr>
	<td>{$lng.lbl_page_content}:</td>
	<td><font class="Star">*</font></td>
	<td>
{if $page_content eq ''}{assign var="page_content" value="Page$default_index content"}{/if}
{include file="main/textarea.tpl" name="pagecontent" cols=50 rows=30 data=$page_content btn_rows=4 html_editor_mode="XHTML"}
	</td>
</tr>

<tr>
<td>{$lng.lbl_status}:</td>
	<td><font class="Star">*</font></td>
	<td>
<select name="active">
<option value="Y"{if $page_data.active eq "Y"} selected="selected"{/if}>{$lng.lbl_enabled}</option>
<option value="N"{if $page_data.active eq "N"} selected="selected"{/if}>{$lng.lbl_disabled}</option>
</select>
	</td>
</tr>

<tr>
	<td>{$lng.lbl_position}:</td>
	<td><font class="Star"></font></td>
	<td><input type="text" name="orderby" value='{$page_data.orderby|default:"$default_orderby"}' size="5" /></td>
</tr>


{* ---------------------- *}
<tr>
        <td>Header POS:</td>
        <td><font class="Star"></font></td>
        <td><input type="text" name="header_pos" value='{$page_data.header_pos|default:""}' size="5" /></td>
</tr>

<tr>
        <td>Icon Image:</td>
	<td><font class="Star"></font></td>
        <td>
{*
{if $mode ne "add"}
{include file="main/edit_image.tpl" type="A" id=$current_category.categoryid delete_url="category_modify.php?mode=delete_icon&amp;cat=`$cat`" button_name=$lng.lbl_save idtag="2"}
{else}
{include file="main/edit_image.tpl" type="A" id=0 delete_url="category_modify.php?mode=delete_icon&amp;cat=`$cat`" button_name=$lng.lbl_save idtag="2"}
{/if}
*}

    {if $mode eq 'add'}

	{assign var="get_pageid" value=$smarty.get.pageid|escape:'html'}

        {include file="main/edit_image.tpl" type="A" delete_url="pages.php?mode=delete_icon&amp;pageid=`$get_pageid`" button_name=$lng.lbl_save source='PD'}
    {else}
        {include file="main/edit_image.tpl" type="A" id=$pageid delete_url="pages.php?mode=delete_icon&amp;pageid=`$pageid`" button_name=$lng.lbl_save source='PD'}
    {/if}


        </td>
</tr>
{* ---------------------- *}



<tr>
	<td colspan="2">&nbsp;</td>
	<td class="SubmitBox"><input type="submit" value=" {$lng.lbl_save|strip_tags:false|escape} " /></td>
</tr>

</table>
</form>

{/capture}
{include file="dialog.tpl" title=$lng.lbl_static_page_details content=$smarty.capture.dialog extra='width="100%"'}

{*
{if $level eq "E" and $pageid ne 0}
  <br />
  {include file="main/clean_urls.tpl" resource_name="pageid" resource_id=$pageid clean_url_action="pages.php" clean_urls_history_mode="clean_urls_history" clean_urls_history=$page_data.clean_urls_history}
{/if}
*}

{if $level eq "E" and $config.SEO.clean_urls_enabled eq "Y"}
<script type="text/javascript">
//<![CDATA[
{literal}

function clean_url_page_updater() {
  if (document.pagesform.clean_url) {
    if (document.pagesform.clean_url.value == '')
      copy_clean_url(document.pagesform.pagetitle, document.pagesform.clean_url);

    document.pagesform.clean_url.onfocus = function() {
      if (this.value == '')
        copy_clean_url(this.form.pagetitle, this);

      return true;
    }
  }
}

if (window.addEventListener)
  window.addEventListener("load", clean_url_page_updater, false);

else if (window.attachEvent)
  window.attachEvent("onload", clean_url_page_updater);
{/literal}
//]]>
</script>
{/if}


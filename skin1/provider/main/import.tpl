{* $Id: import.tpl,v 1.30 2004/07/06 13:52:06 mclap Exp $ *}
{if $import_pass ne ""}
{include file="provider/main/import_results.tpl"}
{else}
{include file="page_title.tpl" title=$lng.lbl_import_products}

<BR><BR>

{assign var="columns2" value=$columns}

{$lng.txt_import_products_note} 

<BR><BR>

{capture name=dialog}

<TABLE border="0" cellpadding="5" cellspacing="1" width="100%">

<FORM action="import.php" method="POST" enctype="multipart/form-data" name="import_form">

<INPUT type="hidden" name="mode" value="">

<TR>
<TD colspan="2"><FONT class="AdminTitle">{$lng.lbl_column_order}:</FONT>
<BR><BR>
{$lng.txt_import_products_column_order_note}
<BR><BR>
</TD>
</TR>

{math equation="ceil(x/3)" x=$number_of_columns assign="rows"}

<TR>
<TD colspan="2">

<TABLE border="0" cellpadding="1" cellspacing="1" width="100%">

<TR>
{section name=cols loop=3}
<TD width="30%" valign="top">

{math equation="x*y" x=$rows y=%cols.index% assign="start_row"}

<TABLE border="0" cellpadding="1" cellspacing="1">

{section name=col loop=$columns start=$start_row max=$rows}

{assign var="idx" value=$smarty.section.col.index}

<TR>
<TD width="1">{$smarty.section.col.index}:</TD>
<TD width="99%">
<SELECT name="import_columns[{$smarty.section.col.index}]">
<OPTION value=""{if $layout[$idx] eq ""} selected{/if}>{$lng.lbl_null}</OPTION>
{section name=col2 loop=$columns2}
<OPTION value="{$columns2[col2]}"{if $layout[$idx] eq $columns2[col2]} selected{/if}>{$columns2[col2]}</OPTION>
{/section}
</SELECT>
</TD>
</TR>

{/section}

</TABLE>

</TD>

{/section}
</TR>

</TABLE>

</TD>
</TR>

<TR>
<TD colspan="2"><BR>
<INPUT type="checkbox" name="read_csv_header" value="Y">
{$lng.lbl_get_columns_names_from_first_row}
</TD>
</TR>

<TR>
<TD colspan="2"><BR>
{$lng.lbl_csv_delimiter}:<BR>{include file="provider/main/ie_delimiter.tpl"}
</TD>
</TR>

<TR>
<TD colspan="2"><BR>
{$lng.txt_default_category}:
<BR>
<SELECT name="categoryid">
{section name=cat_num loop=$allcategories}
<OPTION value="{$allcategories[cat_num].categoryid}" {if $allcategories[cat_num].categoryid eq $product.categoryid}selected{/if}>{$allcategories[cat_num].category_path}</OPTION>
{/section}
</SELECT>
</TD>
</TR>

<TR>
<TD colspan="2"><BR>
{$lng.txt_category_path_sep}:
<BR>
<INPUT type="text" name="category_sep" value="///">
</TD>
</TR>

<TR>
<TD colspan="2"><BR>
{$lng.txt_directory_where_images_are_located|substitute:"my_files_location":$my_files_location}:
<BR>
<INPUT type="text" size="32" name="images_directory" value="{if $default_imagepath}{$default_imagepath}{/if}">
</TD>
</TR>

<TR>
<TD colspan="2"><BR>
{$lng.txt_csv_file_is_located_on_the_server|substitute:"my_files_location":$my_files_location}:
<BR>
<INPUT type="text" size="32" name="localfile">
</TD>
</TR>

<TR>
<TD colspan="2"><BR>
{$lng.lbl_csv_file_for_upload}:<BR><INPUT type="file" size="32" name="userfile">
{if $upload_max_filesize}
<BR><FONT class="Star">{$lng.lbl_warning}!</FONT> {$lng.txt_max_file_size_that_can_be_uploaded}: {$upload_max_filesize}b.
{/if}

<BR><BR><BR>

{if $active_modules.Simple_Mode}
<B>{$lng.lbl_note}:</B> {$lng.txt_import_products_backup_note_general}
{else}
<B>{$lng.lbl_note}:</B> {$lng.txt_import_products_backup_note}
{/if}

<BR><BR><BR>

{include file="buttons/rarrow.tpl"} <INPUT type="submit" value="{$lng.lbl_import_products}">&nbsp;&nbsp;<INPUT type="checkbox" value="yes" name="delete_products" onClick='javascript:if(document.import_form.delete_products.checked && confirm("{$lng.txt_are_you_sure|strip_tags}")) document.import_form.delete_products.checked=1; else document.import_form.delete_products.checked=0;'>{$lng.lbl_drop_all_products_before_import}

<BR><BR><BR>

{include file="buttons/rarrow.tpl"} <INPUT type="button" value="{$lng.lbl_store_layout}" onClick='javascript: document.import_form.mode.value="layout"; document.import_form.submit();'>
</TD>
</TR>

</FORM>

</TABLE>

{/capture}
{include file="dialog.tpl" title=$lng.lbl_import_products content=$smarty.capture.dialog extra="width=100%"}
{/if}

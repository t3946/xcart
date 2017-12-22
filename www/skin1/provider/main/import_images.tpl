{* $Id: import_images.tpl,v 1.13 2004/05/28 12:21:20 max Exp $ *}
{include file="page_title.tpl" title=$lng.lbl_import_detailed_images}

{if $smarty.get.err eq "csv_header_error"}

{capture name=dialog}
<P>{$lng.err_recognition_first_row}
{/capture}
{include file="dialog.tpl" title=$lng.lbl_import_images_error content=$smarty.capture.dialog extra="width=100%"}

<BR>
{/if}

{if $report}

{capture name=dialog}

<BR>

{$lng.lbl_result_report}:

<BR><BR>

<TABLE border="0" cellpadding="1" cellspacing="1" width="100%">

<TR>
<TD class="TableHead" width="10%" nowrap>{$lng.lbl_product_id}</TD>
<TD class="TableHead" width="70%" nowrap>{$lng.lbl_image_file}</TD>
<TD class="TableHead" width="20%">{$lng.lbl_status}</TD>
</TR>

{section name=rep loop=$report}

<TR{cycle values=", class='TableSubHead'"}>
<TD>#{$report[rep].productid}</TD>
<TD>{$report[rep].imagefile}</TD>
<TD>{if $report[rep].status eq "Y"}{$lng.lbl_success}{else}<FONT class="ErrorMessage">{$lng.lbl_failed}</FONT>{/if}</TD>
</TR>

{/section}

</TABLE>

{/capture}
{include file="dialog.tpl" title=$lng.lbl_import_results content=$smarty.capture.dialog extra="width=100%"}

<BR><BR>

{/if}

{assign var="columns2" value=$columns}

{if $smarty.get.mode ne "imported"}
{$lng.txt_import_images_note} 
{/if}

<BR><BR>

{capture name=dialog}

<TABLE border="0" cellpadding="5" cellspacing="1">

<FORM action="import_images.php" method="POST" enctype="multipart/form-data" name="import_form">

<TR>
<TD colspan="2"><FONT class="AdminTitle">{$lng.lbl_column_order}</FONT>

<BR><BR>

{$lng.txt_import_images_list_columns_note}

<BR><BR>

</TD>
</TR>

{section name=col loop=$columns}

<TR>
<TD width="1%">{$smarty.section.col.index}:</TD>
<TD width="99%">
<SELECT name="cols[{$smarty.section.col.index}]">
<OPTION value="">{$lng.lbl_null}</OPTION>
<OPTION value="productcode">productcode</OPTION>
{section name=col2 loop=$columns2}
<OPTION value="{$columns2[col2]}" {if $smarty.section.col.index eq $smarty.section.col2.index }selected{/if}>{$columns2[col2]}</OPTION>
{/section}
</SELECT>
</TD>
</TR>

{/section}

<TR>
<TD colspan="2"><BR>
<INPUT type="checkbox" name="read_csv_header" value="Y">
{$lng.lbl_get_columns_names_from_first_row}
</TD>
</TR>

<TR>
<TD colspan="2"><BR>
{$lng.lbl_csv_delimiter}:<BR>{ include file="provider/main/ie_delimiter.tpl" }
</TD>
</TR>

<TR>
<TR>
<TD colspan="2"><BR>
{$lng.txt_directory_where_images_are_located|substitute:"my_files_location":$my_files_location}:<BR>
<INPUT type="text" size="32" name="images_directory" value="{if $default_imagepath}{$default_imagepath}{/if}">
</TD>
</TR>

<TR>
<TD colspan="2"><BR>
{$lng.txt_csv_file_is_located_on_the_server|substitute:"my_files_location":$my_files_location}:<BR>
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

{include file="buttons/rarrow.tpl"} <INPUT type="submit" value="{$lng.lbl_import_images}">
</TD>
</TR>

</FORM>

</TABLE>

{/capture}
{include file="dialog.tpl" title=$lng.lbl_import_detailed_images content=$smarty.capture.dialog extra="width=100%"}

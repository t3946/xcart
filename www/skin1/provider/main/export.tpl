{* $Id: export.tpl,v 1.17.2.1 2004/08/06 10:45:57 max Exp $ *}
{include file="page_title.tpl" title=$lng.lbl_export_products}

{assign var="columns2" value=$columns}

{$lng.txt_export_products_note}

<BR><BR>

{capture name=dialog}

<FORM action="export.php" method="POST">

<INPUT type="hidden" name="mode" value="Export products">

{$lng.lbl_csv_delimiter}: { include file="provider/main/ie_delimiter.tpl" }

{if $config.Images.thumbnails_location eq "DB" || $config.Images.thumbnails_location eq "FS"}

<BR><BR><BR>

{$lng.txt_export_products_image_note|substitute:"my_files_location":$my_files_location}

<BR>

<INPUT type="text" name="images_dir" value="" size="55">

<BR><BR><BR>

<INPUT type="checkbox" name="donot_backup_images" value="Y">
{$lng.lbl_do_not_backup_product_thumbnails_and_detailed_images}

{/if}

<BR><BR><BR>

{include file="buttons/rarrow.tpl"} <INPUT type="submit" value="{$lng.lbl_export_products}">

<BR><BR>

{$lng.txt_export_products_comment}

</FORM>

{/capture}
{include file="dialog.tpl" title=$lng.lbl_export_products content=$smarty.capture.dialog extra="width=100%"}

{if $active_modules.Froogle}
<BR>
{include file="modules/Froogle/froogle.tpl"}
{/if}


{* $Id: payment_upload.tpl,v 1.9 2004/05/28 12:20:58 max Exp $ *}
{include file="page_title.tpl" title=$lng.lbl_payment_upload}
{$lng.txt_payment_upload_note}<BR>
{$lng.txt_payment_upload_example}<BR><BR>

<!-- IN THIS SECTION -->

{include file="dialog_tools.tpl"}
<BR>

<!-- IN THIS SECTION -->

{capture name=dialog}
<FORM method="POST" action="payment_upload.php" enctype="multipart/form-data">
<INPUT type="hidden" name="mode" value="upload">
<TABLE border="0" cellpadding="0" cellspacing="5" width="100%">
<TR>
<TD width="20%"><B>{$lng.lbl_csv_delimiter}:</B></TD>
<TD width="80%">{include file="provider/main/ie_delimiter.tpl"}</TD>
</TD>
<TR>
<TD width="20%"><B>{$lng.lbl_csv_file}:</B></TD>
<TD width="80%"><INPUT type="file" name="userfile"></TD>
</TR>
<TR>
<TD colspan="2"><INPUT type="submit" value="{$lng.lbl_upload}"></TD>
</TR>
</TABLE>
</FORM>
{/capture}
{include file="dialog.tpl" content=$smarty.capture.dialog title=$lng.lbl_payment_upload extra="width=100%"}

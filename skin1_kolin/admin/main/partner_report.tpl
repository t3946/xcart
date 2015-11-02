{* $Id: partner_report.tpl,v 1.24 2004/05/28 12:20:58 max Exp $ *}
{include file="page_title.tpl" title=$lng.lbl_partner_accounts}
{$lng.txt_partner_accounts_note}<BR><BR>

<!-- IN THIS SECTION -->

{include file="dialog_tools.tpl"}
<BR>

<!-- IN THIS SECTION -->

{capture name=dialog}
{$lng.txt_partner_accounts_comment}<BR>
<BR>
<A href="partner_report.php">{$lng.lbl_all_accounts}</A>&nbsp;&nbsp;&nbsp;&nbsp;<A href="partner_report.php?use_limit=Y">{$lng.lbl_accounts_ready_to_be_paid}</A><BR>
<BR>
{if $result ne ''}
<FORM action="partner_report.php" method="post">
<INPUT type="hidden" name="mode" value="paid">
<TABLE border="0" cellpadding="2" cellspacing="2" width="100%">
<TR class="TableHead">
	<TD rowspan="2">{$lng.lbl_partner}</TD>
    <TD colspan="4" align="center">{$lng.lbl_commissions}</TD>
{if $is_paid eq 'Y'}
    <TD rowspan="2" align="center">{$lng.lbl_ready_to_be_paid}</TD>
{/if}
</TR>
<TR class="TableHead">
    <TD align="center">{$lng.lbl_paid}</TD>
    <TD align="center">{$lng.lbl_approved}</TD>
    <TD align="center">{$lng.lbl_pending}</TD>
	<TD align="center">{$lng.lbl_min_limit}</TD>
</TR>
{foreach from=$result item=v}
<TR>
	<TD>{$v.firstname} {$v.lastname}</TD>
	<TD align="right" nowrap>{include file="currency.tpl" value=$v.sum_paid}</TD>
	<TD align="right" nowrap>{include file="currency.tpl" value=$v.sum_nopaid}</TD>
    <TD align="right" nowrap>{include file="currency.tpl" value=$v.sum}</TD>
    <TD align="right" nowrap>{include file="currency.tpl" value=$v.min_paid}</TD>
{if $is_paid eq 'Y'}
	<TD align="center">{if $v.is_paid eq 'Y'}<INPUT type="checkbox" name="paid[{$v.login}]" value="Y">{/if}</TD>
{/if}
</TR>
{/foreach}
</TABLE>
{if $is_paid eq 'Y'}
<INPUT type="submit" value="{$lng.lbl_paid}"><BR>
{/if}
</FORM>
<BR>
<FORM action="partner_report.php" method="post">
<INPUT type="hidden" name="mode" value="export">
<TABLE border="0" cellpadding="0" cellspacing="0" width="100%">
<TR>
	<TD colspan="2"><A name="products"><B><FONT class="ProductDetailsTitle">{$lng.lbl_export_partner_account}</FONT></B></TD>
</TR>
<TR>
	<TD class="Line" height="1" colspan="2"><IMG src="{$ImagesDir}/spacer.gif" width="1" height="1" border="0"></TD>
</TR>
<TR>
	<TD colspan="2">&nbsp;</TD>
</TR>
</TABLE>
<TABLE border="0" cellpadding="2" cellspacing="2">
<TR>
	<TD height="10" class="FormButton">{$lng.lbl_csv_delimiter}:</TD>
	<TD height="10" width="10">&nbsp;</TD>
	<TD height="10">{include file="provider/main/ie_delimiter.tpl"}</TD>
</TR>
<TR>
    <TD height="10" class="FormButton">&nbsp;</TD>
    <TD height="10" width="10">&nbsp;</TD>
    <TD height="10"><INPUT type="submit" name="export" value="{$lng.lbl_export}"></TD>
</TR>
</TABLE>
</FORM>
{/if}
{/capture}
{include file="dialog.tpl" content=$smarty.capture.dialog title=$lng.lbl_partner_accounts extra="width=100%"} 

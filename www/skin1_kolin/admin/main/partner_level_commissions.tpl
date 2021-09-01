{* $Id: partner_level_commissions.tpl,v 1.5 2004/05/19 06:29:39 max Exp $ *}
{include file="page_title.tpl" title=$lng.lbl_multi_tier_affiliates}
{$lng.txt_partnership_commissions_note}<BR><BR>

<!-- IN THIS SECTION -->

{include file="dialog_tools.tpl"}

<!-- IN THIS SECTION -->
<BR>

{capture name=dialog}
<FORM action="partner_level_commissions.php" method="post">
<INPUT type="hidden" name="mode" value="edit">
<TABLE border="0" cellspacing="2" cellpadding="2">
<TR class="TableHead">
	<TD><B>{$lng.lbl_level}</B></TD>
	<TD><B>{$lng.lbl_commission}</B></TD>
</TR>
{foreach from=$levels item=v key=k}
<TR>
    <TD>{$k}</TD>
    <TD><INPUT size="6" type="text" name="level[{$k}]" value="{$v.commission|default:"0.00"}">%</TD>
</TR>
{/foreach}
</TABLE>
<BR>
<INPUT type="submit" value="{$lng.lbl_update}">
</FORM>
{/capture}
{include file="dialog.tpl" content=$smarty.capture.dialog title=$lng.lbl_multi_tier_affiliates extra="width=100%"} 


{* $Id: partner_commissions.tpl,v 1.11.2.2 2006/05/16 07:02:55 max Exp $ *}
{include file="page_title.tpl" title=$lng.lbl_commissions}
{$lng.txt_partner_commissions_note}<BR><BR>

<!-- IN THIS SECTION -->

{include file="dialog_tools.tpl"}

<!-- IN THIS SECTION -->
<BR>

<SCRIPT type="text/javascript" language="JavaScript 1.2">
var txt = '';
function change_filter(obj) {ldelim}
	document.getElementById('partner').disabled = !document.getElementById('use_filter').checked;
	if(document.getElementById('use_filter').checked) {ldelim}
		if(txt == "{$lng.lbl_all_partners}")
			txt = '';
		if(document.getElementById('partner').value == "{$lng.lbl_all_partners}")
			document.getElementById('partner').value = txt;
	{rdelim} else {ldelim}
		txt = document.getElementById('partner').value;
		document.getElementById('partner').value = "{$lng.lbl_all_partners}";
	{rdelim}
{rdelim}
</SCRIPT>

{capture name=dialog}
<FORM action="partner_commissions.php" method="POST" name="searchform">
<INPUT type="hidden" name="mode" value="go">
<TABLE border="0" cellspacing="2" cellpadding="2">
<TR> 
    <TD>{$lng.lbl_plan}</TD>
    <TD colspan="3"><SELECT name="pc">
<OPTION value="">{$lng.lbl_select_affiliate_plan}</OPTION>
{section name=plan loop=$partner_plans}
<OPTION value="{$partner_plans[plan].plan_id}">{$partner_plans[plan].plan_title}</OPTION>
{/section}
</SELECT></TD>
</TR> 
<TR>
	<TD>{$lng.lbl_partner}</TD>
	<TD><INPUT type="text" value="{$partner}" name="partner" id="partner"></TD>
	<TD><INPUT type="checkbox" id="use_filter" name="use_filter" value="Y"{if $use_filter eq 'Y'} checked{/if} onclick="javascript: change_filter();"></TD>
	<TD>{$lng.lbl_use_filter}</TD>
</TR>
<TR>
	<TD>&nbsp;</TD>
	<TD colspan="3"><INPUT type="button" value="{$lng.lbl_apply}" onclick="javascript: if(confirm('{$lng.txt_apply_aff_plan_to_partners|strip_tags}')) {ldelim} document.searchform.mode.value='apply_global'; document.searchform.submit(); {rdelim}">&nbsp;<INPUT type="submit" name="go" value="{$lng.lbl_show}"></FORM></TD>
</TR>
</TABLE>
<SCRIPT type="text/javascript" language="JavaScript 1.2">
change_filter();
</SCRIPT>
</FORM>
{/capture}
{include file="dialog.tpl" content=$smarty.capture.dialog title=$lng.lbl_search extra="width=100%"}
{if $mode eq "go"}
<BR>
{capture name=dialog}
{if $partner_info eq ""}
{$lng.lbl_no_partners}
{else}
<FORM method="POST" action="partner_commissions.php">
<INPUT type="hidden" name="mode" value="apply">
<INPUT type="hidden" name="partner" value="{$partner}">
<INPUT type="hidden" name="page" value="{$smarty.get.page|escape:"html"}">
<TABLE border="0" cellpadding="2" cellspacing="2" width="100%">
<TR class="TableHead">
<TD width="10%"><B>{$lng.lbl_login}</B></TD>
<TD width="70%"><B>{$lng.lbl_name}</B></TD>
<TD width="20%"><B>{$lng.lbl_affiliate_plan}</B></TD>
</TR>
{section name=pid loop=$partner_info}
<TR>
<TD><A href="user_modify.php?user={$partner_info[pid].login|escape:"url"}&usertype=B">{$partner_info[pid].login}</A></TD>
<TD><A href="user_modify.php?user={$partner_info[pid].login|escape:"url"}&usertype=B">{$partner_info[pid].firstname}&nbsp;{$partner_info[pid].lastname}</A></TD>
<TD>
<SELECT name="plans[{$partner_info[pid].login}]">
<OPTION value="">{$lng.lbl_no_plans_assigned}</OPTION>
{section name=plan loop=$partner_plans}
<OPTION value="{$partner_plans[plan].plan_id}"{if $partner_plans[plan].plan_id eq $partner_info[pid].plan_id} selected{/if}>{$partner_plans[plan].plan_title}</OPTION>
{/section}
</SELECT>
</TD>
</TR>
{/section}
<TR>
<TD colspan="3"><INPUT type="submit" value="{$lng.lbl_apply}"></TD>
</TR>
</TABLE>
</FORM>
{/if}
{/capture}
{include file="dialog.tpl" content=$smarty.capture.dialog title=$lng.lbl_search_result extra="width=100%"}
{/if}

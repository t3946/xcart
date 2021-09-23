{* $Id: partner_plans.tpl,v 1.10 2004/05/28 12:20:58 max Exp $ *}
{include file="page_title.tpl" title=$lng.lbl_affiliate_plans}
{$lng.txt_affiliate_plan_note}<BR><BR>

<!-- IN THIS SECTION -->

{include file="dialog_tools.tpl"}

<!-- IN THIS SECTION -->
<BR>

{capture name=dialog}
<TABLE border="0" cellpadding="2" cellspacing="2" width="100%">
<FORM action="partner_plans.php" name="plansform" method="POST">
<INPUT type="hidden" name="mode" value="update">
<TR>
<TD class="TableHead" width="5">{$lng.lbl_sel}</TD>
<TD class="TableHead" width="20" nowrap>{$lng.lbl_plan_number}</TD>
<TD class="TableHead" width="70%" nowrap>{$lng.lbl_plan_title}</TD>
<TD class="TableHead" width="30%" nowrap>{$lng.lbl_status}</TD>
</TR>
{if $partner_plans}
{section name=plan loop=$partner_plans}
<TR>
<TD><INPUT type="radio" name="pid" value="{$partner_plans[plan].plan_id}"{if %plan.first%} checked{/if}></TD>
<TD>{$partner_plans[plan].plan_id}</TD>
<TD><INPUT type="text" name="plans[{$partner_plans[plan].plan_id}][plan_title]" size="45" maxlength="64" value="{$partner_plans[plan].plan_title}"></TD>
<TD><SELECT name="plans[{$partner_plans[plan].plan_id}][status]">
<OPTION value="A"{if $partner_plans[plan].status eq "A"} selected{/if}>{$lng.lbl_active}</OPTION>
<OPTION value="D"{if $partner_plans[plan].status eq "D"} selected{/if}>{$lng.lbl_disabled}</OPTION>
</SELECT>
</TD>
</TR>
{/section}
<TR>
<TD colspan="4"><INPUT type="button" value="{$lng.lbl_modify_selected}" onclick="document.plansform.mode.value='modify'; document.plansform.submit();">&nbsp;&nbsp;&nbsp;
<INPUT type="button" value="{$lng.lbl_update}" onclick="document.plansform.mode.value='edit'; document.plansform.submit();">&nbsp;&nbsp;&nbsp;
<INPUT type="button" value="{$lng.lbl_delete_selected}" onclick="document.plansform.mode.value='delete'; document.plansform.submit();"></TD>
</TR>
{else}
<TR>
<TD colspan="4" align="center">{$lng.lbl_no_affiliate_plans_defined}</TD>
</TR>
{/if}

<TR><TD colspan="4">&nbsp;</TD></TR>

<TR>
<TD colspan="2">{$lng.lbl_new_plan}:</TD>
<TD><INPUT type="text" name="new_plan_title" size="45" maxlength="64"></TD>
<TD><SELECT name="new_status">
<OPTION value="A" selected>{$lng.lbl_active}</OPTION>
<OPTION value="D">{$lng.lbl_disabled}</OPTION>
</SELECT>
</TD>
</TR>

<TR>
<TD colspan="4">
<BR>
<INPUT type="hidden" name="redirect_to_modify">
<INPUT type="submit" value="{$lng.lbl_add}">&nbsp;&nbsp;&nbsp;
<INPUT type="button" value="{$lng.lbl_add_and_modify}" onclick="document.plansform.redirect_to_modify.value='on'; document.plansform.submit();"><BR><BR>
</TD>
</TR>
</FORM>
</TABLE>
{/capture}
{include file="dialog.tpl" content=$smarty.capture.dialog title=$lng.lbl_affiliate_plans extra="width=100%"}

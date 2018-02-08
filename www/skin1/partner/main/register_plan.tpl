{* $Id: register_plan.tpl,v 1.2.2.1 2005/03/01 13:37:20 max Exp $ *}
{if $plans}

<TR> 
<TD height="20" colspan="3"><B>{$lng.lbl_partner_plans}</B><HR size="1" noshade></TD>
</TR>

{if $usertype eq "A" or ($usertype eq "P" and $active_modules.Simple_Mode ne "")}

<TR>
<TD align="right">{$lng.lbl_partner_plan}</TD>
<TD>&nbsp;</TD>
<TD nowrap><SELECT name="plan_id">
{foreach from=$plans item=v}
<OPTION value="{$v.plan_id}"{if $userinfo.plan_id eq $v.plan_id} selected{/if}>{$v.plan_title}</OPTION>
{/foreach}
</SELECT></TD>
</TR>

{else}

<INPUT type="hidden" name="plan_id" value="{$userinfo.plan_id}">

{/if}

<TR>
<TD align="right">{$lng.lbl_signup_for_partner_plan}</TD>
<TD>&nbsp;</TD>
<TD nowrap><SELECT name="pending_plan_id">
{foreach from=$plans item=v}
<OPTION value="{$v.plan_id}"{if $userinfo.pending_plan_id eq $v.plan_id} selected{/if}>{$v.plan_title}</OPTION>
{/foreach}
</SELECT></TD>
</TR>

{/if}

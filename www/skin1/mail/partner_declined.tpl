{* $Id: partner_declined.tpl,v 1.2 2004/05/31 10:52:01 max Exp $ *}
{include file="mail/mail_header.tpl"}
{assign var=max_truncate value=$config.Email.max_truncate}{math assign="max_space" equation="x+5" x=$max_truncate}{assign var="max_space" value="%-"|cat:$max_space|cat:"s"}

{$lng.lbl_dear} {$userinfo.title} {$userinfo.firstname} {$userinfo.lastname},

{$lng.txt_profile_declined}

{if $reason ne ""}
{$lng.eml_reason}:
{$reason}
{/if}


{include file="mail/signature.tpl"}

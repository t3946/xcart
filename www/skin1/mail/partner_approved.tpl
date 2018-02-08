{* $Id: partner_approved.tpl,v 1.2.2.1 2004/11/18 06:15:03 max Exp $ *}
{include file="mail/mail_header.tpl"}
{assign var="max_truncate" value=$config.Email.max_truncate}{math assign="max_space" equation="x+5" x=$max_truncate}{assign var="max_space" value="%-"|cat:$max_space|cat:"s"}

{$lng.lbl_dear} {$userinfo.title} {$userinfo.firstname} {$userinfo.lastname},

{$lng.txt_profile_approved}

{$lng.lbl_profile_details}:
---------------------
{include file="mail/profile_data.tpl" userinfo=$userinfo}

{include file="mail/signature.tpl"}

{* $Id: signin_partner_notif.tpl,v 1.6 2004/05/31 10:52:01 max Exp $ *}
{include file="mail/mail_header.tpl"}
{assign var=max_truncate value=$config.Email.max_truncate}{math assign="max_space" equation="x+5" x=$max_truncate}{assign var="max_space" value="%-"|cat:$max_space|cat:"s"}

{$lng.lbl_dear} {$userinfo.title} {$userinfo.firstname} {$userinfo.lastname},

{$lng.txt_partner_created}

{$lng.lbl_profile_details}:
---------------------
{include file="mail/profile_data.tpl" show_pwd="Y"}


{include file="mail/signature.tpl"}

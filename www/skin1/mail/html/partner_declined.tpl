{* $Id: partner_declined.tpl,v 1.1 2004/06/21 13:41:58 max Exp $ *}
{include file="mail/html/mail_header.tpl"}

{$lng.lbl_dear} {$userinfo.title} {$userinfo.firstname} {$userinfo.lastname},<BR>
<BR>
{$lng.txt_profile_declined}<BR>
<BR>
{if $reason ne ""}
<B>{$lng.eml_reason}:</B><BR>
{$reason}<BR>
<BR>
{/if}


{include file="mail/html/signature.tpl"}

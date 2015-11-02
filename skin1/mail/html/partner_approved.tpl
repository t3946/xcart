{* $Id: partner_approved.tpl,v 1.1.2.2 2004/11/18 06:35:55 max Exp $ *}
{include file="mail/html/mail_header.tpl"}

{$lng.lbl_dear} {$userinfo.title} {$userinfo.firstname} {$userinfo.lastname},<BR>
<BR>
{$lng.txt_profile_approved}<BR>
<BR>
{$lng.lbl_profile_details}:<BR>
{include file="mail/html/profile_data.tpl" userinfo=$userinfo}
<BR>

{include file="mail/html/signature.tpl"}

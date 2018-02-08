{* $Id: signin_partner_notif.tpl,v 1.3 2004/05/28 12:21:02 max Exp $ *}
{include file="mail/html/mail_header.tpl"}

<P>{$lng.lbl_dear} {$userinfo.title} {$userinfo.firstname} {$userinfo.lastname},

<P>{$lng.txt_partner_created}

<P>{$lng.lbl_profile_details}:

{include file="mail/html/profile_data.tpl" show_pwd="Y"}

{include file="mail/html/signature.tpl"}


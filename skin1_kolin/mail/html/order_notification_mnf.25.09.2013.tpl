{* order_notification_mnf.tpl, random *}
{config_load file="$skin_config"}
{*include file="mail/html/mail_header.tpl"*}
{$message_body}
<br /><br />
<hr width="100%" />

{include file="mail/html/order_invoice_mnf.tpl"}

{include file="mail/html/signature.tpl"}

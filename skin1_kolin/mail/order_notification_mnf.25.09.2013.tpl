{* order_notification_mnf.tpl, random *}
{config_load file="$skin_config"}
{*include file="mail/mail_header.tpl"*}
{assign var=max_truncate value=$config.Email.max_truncate}{math assign="max_space" equation="x+5" x=$max_truncate}{assign var="max_space" value="%-"|cat:$max_space|cat:"s"}
{$message_body|strip_tags:false}

-------------------------------------------------

{include file="mail/order_invoice_mnf.tpl"}

{include file="mail/signature.tpl"}

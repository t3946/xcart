{* order_customer_shipped.tpl, random *}
{if $customer ne ''}{assign var="_userinfo" value=$customer}{else}{assign var="_userinfo" value=$userinfo}{/if}
{config_load file="$skin_config"}
{*include file="mail/mail_header.tpl"*}
{assign var=max_truncate value=$config.Email.max_truncate}{math assign="max_space" equation="x+5" x=$max_truncate}{assign var="max_space" value="%-"|cat:$max_space|cat:"s"}

{if $retrieve ne 'Y'}{$lng.eml_dear|substitute:"customer":"`$_userinfo.firstname` `$_userinfo.lastname`"},{/if}

{include file="mail/order_invoice.tpl" show_shipping_groups='Y'}

{if $retrieve ne 'Y'}
{include file="mail/signature.tpl"}
{/if}

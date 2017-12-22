{* $Id: rma_request_created.tpl,v 1.4 2006/03/31 05:51:43 svowl Exp $ *}
{include file="mail/mail_header.tpl"}
{assign var=max_truncate value=$config.Email.max_truncate}{math assign="max_space" equation="x+5" x=$max_truncate}{assign var="max_space" value="%-"|cat:$max_space|cat:"s"}

{$lng.eml_rma_request_created|substitute:"creator":"`$userinfo.firstname` `$userinfo.lastname`"}

{$lng.eml_return_requests}:
---------------------
{foreach from=$returns item=v}
{$lng.lbl_returnid|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$v.returnid}
{$lng.lbl_product|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$v.product.product}
{if $v.product.product_options ne ''}
{$lng.lbl_product_options}:
{include file="modules/Product_Options/display_options.tpl" options=$v.product.product_options is_plain="Y"}
{/if}
{$lng.lbl_quantity|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$v.amount}

{/foreach}

{include file="mail/signature.tpl"}

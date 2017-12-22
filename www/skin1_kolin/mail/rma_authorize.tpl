{* $Id: rma_authorize.tpl,v 1.5 2006/03/31 05:51:43 svowl Exp $ *}
{config_load file="$skin_config"}
{include file="mail/mail_header.tpl"}
{assign var=max_truncate value=$config.Email.max_truncate}{math assign="max_space" equation="x+5" x=$max_truncate}{assign var="max_space" value="%-"|cat:$max_space|cat:"s"}

{$lng.eml_dear|substitute:"customer":"`$userinfo.firstname` `$userinfo.lastname`"},

{$lng.eml_rma_return_authorized|substitute:"returnid":$return.returnid}

{$lng.eml_rma_return_auth_note}

{$lng.eml_return_request}:
{$lng.lbl_returnid|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$return.returnid}
{$lng.lbl_product|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$return.product.product}
{$lng.lbl_product_options|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{include file="modules/Product_Options/display_options.tpl" options=$return.product.product_options is_plain="Y"}
{$lng.lbl_quantity|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$return.amount}

{include file="mail/signature.tpl"}

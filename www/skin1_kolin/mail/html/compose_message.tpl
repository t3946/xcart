{config_load file="$skin_config"}
{$body}

{if $attach_pdf_invoice eq "Y"}
<hr size="1" noshade="noshade" />

{include file="mail/html/order_invoice.tpl"}
{/if}

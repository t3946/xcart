{$body}

{if $attach_pdf_invoice eq "Y"}
    {assign var="oOrder" value=$order.oOrder}
    {include file="mail/order_invoice.tpl"}
{/if}

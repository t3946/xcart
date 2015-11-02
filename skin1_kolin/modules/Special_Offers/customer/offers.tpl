{* $Id: offers.tpl,v 1.3 2004/12/01 15:25:14 mclap Exp $ *}

{if $mode eq "add_free"}
{include file="modules/Special_Offers/customer/checkout_free_products.tpl"}
{else}
{include file="modules/Special_Offers/customer/offers_list.tpl"}
{/if}

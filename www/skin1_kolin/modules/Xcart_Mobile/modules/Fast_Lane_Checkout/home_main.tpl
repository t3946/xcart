{if $checkout_step eq 0}
{include file="modules/Fast_Lane_Checkout/checkout_0_enter.tpl"}

{elseif $checkout_step eq 1}
{include file="modules/Fast_Lane_Checkout/checkout_1_profile.tpl"}

{elseif $checkout_step eq 2}
{include file="modules/Fast_Lane_Checkout/checkout_2_method.tpl"}

{elseif $checkout_step eq 3}
{include file="modules/Fast_Lane_Checkout/checkout_3_place.tpl"}

{else}
{if $last_categoryid ne 0}
{assign var=last_categoryid value="?cat=`$last_categoryid`"}
{else}
{assign var=last_categoryid value=""}
{/if}

{include file="customer/main/cart.tpl"}

{/if}

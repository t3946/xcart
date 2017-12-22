{* $Id: checkout_free_products.tpl,v 1.7 2006/03/28 08:21:09 max Exp $ *}

{capture name=dialog}
{if $free_products ne ""}
{if $sort_fields}
{assign var="enc_offers_return_url" value=$offers_return_url|escape}
<div align="right">{include file="main/search_sort_by.tpl" sort_fields=$sort_fields selected=$search_prefilled.sort_field direction=$search_prefilled.sort_direction url="offers.php?mode=add_free&offers_return_url=`$enc_offers_return_url`&"}</div>
{/if}
{if $total_pages gt 2}
<br />
{ include file="customer/main/navigation.tpl" }
{/if}
<hr class="Line" size="1" />
{include file="customer/main/products.tpl" products=$free_products}
<hr class="Line" size="1" />
{ include file="customer/main/navigation.tpl" }
{else}{* $free_products ne "" *}
<br />

<center>{$lng.msg_sp_no_free_products_to_add}</center>

<br />
<hr class="Line" size="1" />
{/if}
<div align="right">
{if $offers_return_url ne ""}
{include file="buttons/button.tpl" button_title=$lng.lbl_continue_shopping href=$offers_return_url}
{else}
{include file="buttons/button.tpl" button_title=$lng.lbl_continue_shopping href="cart.php"}
{/if}
</div>
{/capture}

{include file="dialog.tpl" title=$lng.lbl_sp_add_free_products_title content=$smarty.capture.dialog extra='width="100%"'}

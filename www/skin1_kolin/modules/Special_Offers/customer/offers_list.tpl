{* $Id: offers_list.tpl,v 1.6 2005/12/07 14:07:31 max Exp $ *}

{if $action ne "popup"}
{capture name="dialog"}
{/if}

{if $offers eq ""}
<br />
{$lng.lbl_sp_offers_not_avail}
<br />
{else}{* $offers eq "" *}
<table cellpadding="10" cellspacing="3" width="100%">
<tr>
	<td width="100%">
{foreach name=offers from=$offers item=offer}
{if $offer.promo_long ne ""}
   {if $offer.html_long}{$offer.promo_long}{else}<tt>{$offer.promo_long|escape}</tt>{/if}
{elseif $offer.promo_short ne ""}
   {if $offer.html_short}{$offer.promo_short}{else}<tt>{$offer.promo_short|escape}</tt>{/if}
{else}
   {$lng.lbl_sp_promo_not_avail}
{/if}
   <br /><br />
{/foreach}
	</td>
</tr>

{if $action ne "popup"}
<tr>
	<td>
{if $mode ne ""}
{include file="buttons/button.tpl" button_title=$lng.lbl_sp_show_all_offers href="offers.php?offers_return_url=`$offers_return_url`"}
&nbsp;&nbsp;&nbsp;&nbsp;
{/if}
{if $mode ne "cart"}
{include file="buttons/button.tpl" button_title=$lng.lbl_sp_show_offers_for_cart href="offers.php?mode=cart&offers_return_url=`$offers_return_url`"}
&nbsp;&nbsp;&nbsp;&nbsp;
{/if}
{if $offers_return_url ne ""}
{include file="buttons/button.tpl" button_title=$lng.lbl_continue_shopping href=$offers_return_url}
{/if}
	</td>
</tr>
{/if}
</table>

{/if}{* $offers eq "" *}

{if $action ne "popup"}
{/capture}
{include file="dialog.tpl" title=$lng.lbl_sp_offers_of_shop content=$smarty.capture.dialog extra='width="100%"'}
{/if}

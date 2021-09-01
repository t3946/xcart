{* $Id: special_offers_order_bonuses.tpl,v 1.3 2005/02/11 16:46:13 mclap Exp $ *}
{if $bonuses.points ne 0 or $bonuses.memberships ne ""}
{$lng.lbl_sp_order_bonuses}:
---------
{if $bonuses.points ne 0}
{$lng.lbl_sp_customer_bonus_points|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{$bonuses.points}
{/if}
{if $bonuses.memberships ne ""}
{$lng.lbl_sp_customer_bonus_memberships|truncate:$max_truncate:"...":true|cat:":"|string_format:$max_space}{foreach name=memberships from=$bonuses.memberships item=membership}{$membership}{if $smarty.foreach.memberships.last ne "1"}, {/if}{/foreach}
{/if}


{/if}

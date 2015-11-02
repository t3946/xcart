{* $Id: bonus_names.tpl,v 1.5 2005/12/07 14:07:30 max Exp $ *}
{if $item_type eq "D"}
{assign var="tmp_title" value=$lng.lbl_sp_bonus_discount}
{assign var="tmp_file" value="bonus_discount.tpl"}
{elseif $item_type eq "B"}
{assign var="tmp_title" value=$lng.lbl_sp_bonus_points}
{assign var="tmp_file" value="bonus_points.tpl"}
{elseif $item_type eq "S"}
{assign var="tmp_title" value=$lng.lbl_sp_bonus_shipping}
{assign var="tmp_file" value="bonus_shipping.tpl"}
{elseif $item_type eq "N"}
{assign var="tmp_title" value=$lng.lbl_sp_bonus_noprice}
{assign var="tmp_file" value="bonus_noprice.tpl"}
{elseif $item_type eq "M"}
{assign var="tmp_title" value=$lng.lbl_sp_bonus_membership}
{assign var="tmp_file" value="bonus_membership.tpl"}
{/if}
 
{if $action eq "subheader"}
{include file="main/subheader.tpl" title=$tmp_title class="black"}
{elseif $action eq "subheader2"}
{include file="main/subheader.tpl" title=$tmp_title class="grey"}
{elseif $action eq "include"}
{if ($item_mode ne "edit") and ($item_mode ne "view")}
{assign var="item_mode" value="edit"}
{/if}
{include file="modules/Special_Offers/`$item_mode`/`$tmp_file`" bonus=$item}
{else}
{$tmp_title}
{/if}

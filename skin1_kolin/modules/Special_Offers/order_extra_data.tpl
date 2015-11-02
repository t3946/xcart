{* $Id: order_extra_data.tpl,v 1.5 2005/12/07 14:07:30 max Exp $ *}

{include file="main/subheader.tpl" title=$lng.lbl_sp_order_offers_applied}

{if $data.applied_offers eq ""}
{$lng.lbl_sp_no_order_offers_applied}
{else}{* $data.applied_offers eq "" *}
{assign var="num" value=1}
{foreach name=offers from=$data.applied_offers item=offer}
{$num}. <b>{$offer.offer_name}</b><br />
<table width="100%" cellspacing="2" cellpadding="2">
<tr>
	<td width="1%">&nbsp;&nbsp;&nbsp;</td>
	<td><u>{$lng.lbl_sp_nav_conditions}:</u><br />

<table width="100%">
{assign var="cnum" value=1}
{foreach name=conditions from=$offer.conditions item=condition}
<tr>
	<td width="1%">&nbsp;</td>
	<td>{$cnum}) {include file="modules/Special_Offers/condition_names.tpl" item_type=$condition.condition_type}</u><br />

<table width="100%">
<tr>
	<td width="1%">&nbsp;&nbsp;&nbsp;</td>
	<td>{include file="modules/Special_Offers/condition_names.tpl" item_type=$condition.condition_type action="include" item=$condition item_mode="view"}</td>
</tr>
</table>

	<td>
</tr>
{math assign="cnum" equation="cnum+1" cnum=$cnum}
{/foreach}
</table>

	</td>
</tr>
<tr>
	<td width="1%">&nbsp;&nbsp;&nbsp;</td>
	<td><u>{$lng.lbl_sp_nav_bonuses}:</u><br />

<table width="100%">
{assign var="bnum" value=1}
{foreach name=bonuses from=$offer.bonuses item=bonus}
<tr>
	<td width="1%">&nbsp;</td>
	<td>{$bnum}) {include file="modules/Special_Offers/bonus_names.tpl" item_type=$bonus.bonus_type}</u><br />

<table width="100%">
<tr>
	<td width="1%">&nbsp;&nbsp;&nbsp;</td>
	<td>{include file="modules/Special_Offers/bonus_names.tpl" item_type=$bonus.bonus_type action="include" item=$bonus item_mode="view"}</td>
</tr>
</table>

	</td>
</tr>
{math assign="bnum" equation="x+1" x=$bnum}
{/foreach}
</table>

	</td>
</tr>
</table>
{math assign="num" equation="num+1" num=$num}
{/foreach}

{/if}{* $data.applied_offers eq "" *}

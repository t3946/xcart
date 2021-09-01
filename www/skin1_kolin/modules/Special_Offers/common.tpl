{* $Id: common.tpl,v 1.6 2005/11/17 06:55:56 max Exp $ *}
{include file="page_title.tpl" title=$lng.lbl_special_offers}
{*
{$lng.txt_special_offers_descr}
<br />
*}
{if $mode eq ""}
{include file="modules/Special_Offers/offers.tpl"}

{else}
{if $mode ne "create"}
{include file="dialog_tools.tpl"}
<br />
{/if}

<br />

{capture name=dialog}

{if $mode eq "modify"}
{assign var="dialog_title" value=$lng.lbl_sp_offer_details_s|substitute:"name":$offer.name}

{elseif $mode eq "conditions"}
{assign var="dialog_title" value=$lng.lbl_sp_offer_conditions_s|substitute:"name":$offer.name}

{elseif $mode eq "bonuses"}
{assign var="dialog_title" value=$lng.lbl_sp_offer_bonuses_s|substitute:"name":$offer.name}

{elseif $mode eq "promo"}
{assign var="dialog_title" value=$lng.lbl_sp_offer_promos_s|substitute:"name":$offer.name}

{elseif $mode eq "status"}
{assign var="dialog_title" value=$lng.lbl_sp_offer_status_name_s|substitute:"name":$offer.name}

{else}
{assign var="dialog_title" value=$offer.name}

{/if}

{include file="modules/Special_Offers/wizard_step.tpl"}

{/capture}
{include file="dialog.tpl" title=$dialog_title content=$smarty.capture.dialog extra='width="100%"'}
{/if}

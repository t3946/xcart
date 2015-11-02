{* $Id: bonus_shipping.tpl,v 1.3 2005/05/20 11:21:59 mclap Exp $ *}

{if $bonus.params eq ""}
{$lng.txt_sp_empty_params_bonus_shipping}
{else}
{$lng.lbl_sp_bonus_apply_to_list}
{/if}

{include file="modules/Special_Offers/view/product_n_category.tpl" params=$bonus.params mainid=$bonus.bonus_type}

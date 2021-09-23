{* $Id: bonus_noprice.tpl,v 1.2 2005/04/01 05:34:25 mclap Exp $ *}

{if $bonus.params eq ""}
{$lng.txt_sp_empty_params_bonus_generic_edit}
{else}
{$lng.lbl_sp_bonus_apply_to_list}
{/if}
{include file="modules/Special_Offers/view/product_n_category.tpl" params=$bonus.params mainid=$bonus.bonus_type with_qnty="Y"}

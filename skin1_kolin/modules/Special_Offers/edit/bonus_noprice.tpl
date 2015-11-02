{* $Id: bonus_noprice.tpl,v 1.6 2005/11/17 06:55:57 max Exp $ *}
{if $bonus.params eq ""}
{$lng.txt_sp_empty_params_bonus_generic_edit}
{else}
{$lng.lbl_sp_bonus_apply_to_list}
{/if}
<input type="hidden" name="bonus[{$bonus.bonus_type}][amount_min]" value="0" />
{include file="modules/Special_Offers/edit/product_n_category.tpl" params=$bonus.params mainid=$bonus.bonus_type form_name="wizardform" with_qnty="Y"}

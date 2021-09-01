{* $Id: condition_set.tpl,v 1.3 2005/11/17 06:55:57 max Exp $ *}

{if $condition.params ne ""}
{$lng.lbl_sp_condition_set_action}:
{if $condition.amount_type eq "N"}{$lng.lbl_sp_action_copy}{else}{$lng.lbl_sp_action_one}{/if}
<br />
{include file="modules/Special_Offers/view/product_n_category.tpl" params=$condition.params mainid=$condition.condition_type with_qnty="Y" join_type="and"}
{/if}

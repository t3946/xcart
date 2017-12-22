{* $Id: condition_names.tpl,v 1.4 2005/12/07 14:07:30 max Exp $ *}
{if $item_type eq "S"}
{assign var="tmp_title" value=$lng.lbl_sp_condition_set}
{assign var="tmp_file" value="condition_set.tpl"}
{elseif $item_type eq "T"}
{assign var="tmp_title" value=$lng.lbl_sp_condition_total}
{assign var="tmp_file" value="condition_total.tpl"}
{elseif $item_type eq "M"}
{assign var="tmp_title" value=$lng.lbl_sp_condition_membership}
{assign var="tmp_file" value="condition_membership.tpl"}
{elseif $item_type eq "B"}
{assign var="tmp_title" value=$lng.lbl_sp_condition_points}
{assign var="tmp_file" value="condition_points.tpl"}
{elseif $item_type eq "Z"}
{assign var="tmp_title" value=$lng.lbl_sp_condition_zone}
{assign var="tmp_file" value="condition_zone.tpl"}
{/if}
{if $action eq "subheader"}
{include file="main/subheader.tpl" title=$tmp_title class="black"}
{elseif $action eq "subheader2"}
{include file="main/subheader.tpl" title=$tmp_title class="grey"}
{elseif $action eq "include"}
{if ($item_mode ne "edit") and ($item_mode ne "view")}
{assign var="item_mode" value="edit"}
{/if}
{include file="modules/Special_Offers/`$item_mode`/`$tmp_file`" condition=$item}
{else}
{$tmp_title}
{/if}

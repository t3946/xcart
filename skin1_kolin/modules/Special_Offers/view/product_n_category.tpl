{* $Id: product_n_category.tpl,v 1.4 2005/12/07 14:07:32 max Exp $ *}

{if $params ne ""}
<table>
{foreach name=params from=$params item=param}
{if $smarty.foreach.params.first ne 1}
<tr>
	<td align="center" style="TEXT-TRANSFORM: lowercase;">
{if $join_type eq "and"}
{$lng.lbl_sp_and}
{else}
{$lng.lbl_sp_or}
{/if}
	</td>
</tr>
{/if}
<tr>
	<td>
   {if $with_qnty eq "Y"}{$param.param_qnty} x {/if}
   {if $param.param_type eq "P"}
   {$lng.lbl_product}: ({$param.productcode}) {$param.product}
   {elseif $param.param_type eq "C"}
   {$lng.lbl_sp_products_from_cat_s|substitute:"cat":$param.category}
   ({$lng.lbl_sp_with_subcategories}: {if $param.param_arg eq "Y"}{$lng.lbl_yes}{else}{$lng.lbl_no}{/if})
   {/if}
	</td>
</tr>
{/foreach}
</table>
{/if}{* $params ne "" *}

{* $Id: taxed_price.tpl,v 1.10.2.1 2006/06/29 06:34:20 max Exp $ *}
{if $taxes}
{foreach key=tax_name item=tax from=$taxes}
{if $tax.tax_value gt 0}
{if $tax.display_including_tax eq "Y"}
{if $display_info eq ""}{assign var="display_info" value=$tax.display_info}{/if}
{* {if $do_not_insert_nbsp ne "Y"}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{/if} *}

{if $price_in_cart ne "Y" && $product_page_tax ne "Y"}{$lng.lbl_including_tax|substitute:"tax":$tax.tax_display_name}{/if}

{if $display_info eq "V" or ($display_info eq "A" and $tax.rate_type eq "$")} 
	{if !$is_subtax}<span id="tax_{$tax.taxid}">{/if}
	{include file="currency.tpl" value=$tax.tax_value}
	{if !$is_subtax}</span>{/if}
{elseif $display_info eq "R"} 
	{if $tax.rate_type eq "$"}
		{include file="currency.tpl" value=$tax.rate_value}
	{else}
		{$tax.rate_value|formatprice}%
	{/if}
{elseif $display_info eq "A"} 
	{if $tax.rate_type eq "%"}

	  {if $price_in_cart eq "Y"}
		including {$tax.rate_value}% {$tax.tax_display_name}: {include file="currency.tpl" value=$tax.tax_value}
	  {else}

	    {if $product_page_tax eq "Y"}
		<tr>
		<td nowrap="nowrap">
		Includes {$tax.tax_display_name}:&nbsp;
		</td>
		<td>
                {if !$is_subtax}<span id="tax_{$tax.taxid}">{/if}
                {include file="currency.tpl" value=$tax.tax_value}
                {if !$is_subtax}</span>{/if}
                / {$tax.rate_value}%
		</td>
		</tr>
	    {else}
                {if !$is_subtax}<span id="tax_{$tax.taxid}">{/if}
                {include file="currency.tpl" value=$tax.tax_value}
                {if !$is_subtax}</span>{/if}
{*              ({$tax.rate_value|formatprice}%) *}
                ({$tax.rate_value}%)
	    {/if}
	  {/if}
	{/if}
{/if}
{if $product_page_tax ne "Y"}
	<br />
{/if}
{/if}
{/if}
{/foreach}
{/if}


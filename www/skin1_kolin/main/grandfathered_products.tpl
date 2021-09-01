{* $Id: grandfathered_products.tpl,v 1.0 2011/12/06 11:59:38 kate Exp $ *}

{include file="page_title.tpl" title=$lng.lbl_grandfathered_products}

{$lng.txt_grandfathered_products_top_text}

<br /><br />

{capture name=dialog}

{assign var="colspan" value="10"}

<form action="grandfathered_products.php" method="post" name="grandfatheredproductsform">

<input type="hidden" name="mode" value="update" />
<input type="hidden" name="navpage" value="{$navpage}" />

{if $total_items gt "1"}
    {$lng.txt_N_results_found|substitute:"items":$total_items}<br />
    {$lng.txt_displaying_X_Y_results|substitute:"first_item":$first_item:"last_item":$last_item}
{/if}

{if $total_pages gt 2}
    {assign var="navpage" value=$navigation_page}
{/if}

{include file="customer/main/navigation.tpl"}

<table cellpadding="2" cellspacing="1" width="100%">

<tr class="TableHead">
	<td>{$lng.lbl_sku}</td>
	<td width="25%">{$lng.lbl_product_name}</td>
	<td width="25%">{$lng.lbl_google_prod_search_term}</td>
	<td>{$lng.lbl_gp_link}</td>
	<td align="center">{$lng.lbl_cost_to_us}</td>
	<td align="center">{$lng.lbl_min_price}</td>
	<td align="center">{$lng.lbl_rec_price}</td>
	<td align="center">{$lng.lbl_price}</td>
	<td align="center">{$lng.lbl_profit}</td>
	<td align="center">{$lng.lbl_margin}</td>
</tr>

{if $grandfathered_products}
    {foreach from=$grandfathered_products item="gp" key="gpid"}
    <tr{cycle values=", class='TableSubHead'"}>
	    <td>{if $current_membership_flag ne "FS"}<a href="{if $usertype eq 'P'}{$gp.links.provider}{else}{$gp.links.admin}{/if}" target="_blank" title="">{/if}{$gp.productcode}{if $current_membership_flag ne "FS"}</a>{/if}</td>
	    <td><a href="{$gp.links.customer}" target="_blank" title="">{$gp.product}</a></td>
        <td><input type="text" name="update[{$gpid}][google_search_term]" value="{$gp.google_search_term|escape}" size="38%" /></td>
	    <td nowrap="nowrap"><a href="{$config.Product_Page.google_prod_link_pattern|substitute:searchterm:$gp.google_search_link}" target="_blank" title="">{$lng.lbl_gp_link}</a></td>
        <td nowrap="nowrap" align="right">{if $gp.cost_to_us eq 0}<font color="#FF2F2F">{/if}{$gp.cost_to_us|price_format}{if $gp.cost_to_us eq 0}</font>{/if}</td>
        <td nowrap="nowrap" align="right">{$gp.min_price|price_format}</td>
        <td nowrap="nowrap" align="right">{if $gp.cost_to_us ne 0}<font color="#008000">{/if}{$gp.rec_price|price_format}{if $gp.cost_to_us ne 0}</font>{/if}</td>
        <td align="right">
            {if $gp.cost_to_us eq 0}
                {$gp.price|price_format}
                <input type="hidden" name="update[{$gpid}][price]" value="{$gp.price|price_format}" />
            {else}
                <input type="text" name="update[{$gpid}][price]" value="{$gp.price|price_format}" size="6" />
            {/if}
        </td>
        <td nowrap="nowrap" align="right">{$gp.profit|price_format}</td>
        <td nowrap="nowrap" align="right">{$gp.margin|price_format}%</td>
    </tr>
    {/foreach}
    
    <tr>
        <td colspan="{$colspan}">{include file="customer/main/navigation.tpl"}</td>
    </tr>

    <tr>
        <td colspan="{$colspan}"><input type="submit" value="{$lng.lbl_save}" /></td>
    </tr>

{else}
    <tr>
        <td align="center" colspan="{$colspan}">{$lng.lbl_no_grandfathered_products}</td>
    </tr>
{/if}

</table>

{/capture}
{include file="dialog.tpl" content=$smarty.capture.dialog title=$lng.lbl_grandfathered_products extra='width="100%"'}


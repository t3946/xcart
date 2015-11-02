{* $Id: product_report.tpl,v 1.0 2011/06/23 13:36:43 kate Exp $ *}
{config_load file="$skin_config"}

<table cellpadding="2" cellspacing="1">

<tr>
    <td colspan="6">
        <table cellpadding="2" cellspacing="1">
        <tr>
            <td colspan="2" style="font-size: 1.1em"><b>{$lng.lbl_summary|cat:":"}</b></td>
        </tr>

        <tr>
            <td width="40%" nowrap="nowrap">{$lng.lbl_report_date|cat:":"}</td>
            <td nowrap="nowrap">{$start_date|default:$smarty.now|date_format:"%e-%b-%G, %A"}</td>
        </tr>

        {if $providers ne ''}
            {foreach from=$providers item=p key=login}
            <tr>
                <td nowrap="nowrap">{$p.name}&nbsp;({$login}):</td>
                <td nowrap="nowrap">{$p.products_number}&nbsp;{$lng.lbl_products_added}</td>
            </tr>
            {/foreach}
    
            {if $total ne ''}
            <tr>
                <td nowrap="nowrap"><b>{$lng.lbl_total_number_products_added|cat:":"}</b></td>
                <td nowrap="nowrap"><b>{$total}</b></td>
            </tr>
            {/if}
    
        {else}
            <tr>
                <td colspan="2">{$lng.lbl_no_products}</td>
            </tr>
        {/if}
        </table>
    </td>
</tr>

{if $products}
<tr>
    <td colspan="6" style="font-size: 1.1em"><br /><b>{$lng.lbl_details|cat:":"}</b></td>
</tr>

{foreach from=$products item=list key=provider}
<tr>
    {assign value="`$providers[$provider].name` (`$provider`): `$providers[$provider].products_number` `$lng.lbl_products_added`" var="provider_title"}
    <td colspan="6">
        <table cellspacing="0" style="width: 100%; margin-bottom: 10px" >
        <tr>
        	<td><br /><b>{$provider_title}</b></td>
        </tr>
        </table>
    </td>
</tr>
        
<tr>
    <th bgcolor="#FFD44C">{$lng.lbl_sku_p|upper}</td>
    <th bgcolor="#FFD44C">{$lng.lbl_a|upper}</td>
    <th bgcolor="#CCCCCC">{$lng.lbl_product_name|upper}</td>
    <th bgcolor="#CCCCCC">{$lng.lbl_cat_ids}</td>
    <th bgcolor="#FFD44C">{$lng.lbl_distr|upper}</td>
    <th bgcolor="#CCCCCC">{$lng.lbl_brand|upper}</td>
</tr>

{foreach from=$list item=product}
<tr{cycle values=", style='background-color: #EEEEEE'"}>
    <td><a href="{$product.links.provider}" title="">{$product.productcode}</a></td>
    <td><a href="{$product.links.admin}" title="">{$lng.lbl_a}</a></td>
    <td><a href="{$product.links.customer}" title="">{$product.product}</a></td>
    <td>{foreach from=$product.categories item=c name="categories"}{if $c.domain}<a href="http://{$c.domain}/home.php?cat={$c.categoryid}" title="">{$c.categoryid}</a>{else}{$c.categoryid}{/if}{if !$smarty.foreach.categories.last},{/if}{/foreach}</td>
    <td>{$product.code}</td>
    <td>{$product.brand}</td>
</tr>
{/foreach}

{/foreach}

{else}
   
<tr>
    <td colspan="6">{$lng.lbl_no_products_found}</td>
</tr>

{/if}
</table>

{include file="mail/html/signature.tpl"}

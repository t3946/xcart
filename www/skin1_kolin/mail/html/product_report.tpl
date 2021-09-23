{* $Id: product_report.tpl,v 1.0 2011/06/23 13:36:43 kate Exp $ *}
{config_load file="$skin_config"}

<table cellpadding="2" cellspacing="1">

<tr>
    <td colspan="7">
        <table cellpadding="2" cellspacing="1">
        <tr>
            <td colspan="2" style="font-size: 1.1em"><b>{$lng.lbl_summary|cat:":"}</b></td>
        </tr>

        <tr>
            <td width="40%" nowrap="nowrap">{$lng.lbl_report_date|cat:":"}</td>
            <td nowrap="nowrap">{$start_date|default:$smarty.now|date_format:"%e-%b-%G, %A"}</td>
        </tr>

        </table>
    </td>
</tr>

{if $providers ne ''}
            <tr>
            <td colspan="7">
                <table>
                    <tr>
                        <td style="font-weight: bold; text-align: center;" bgcolor="#CCCCCC">{$lng.lbl_operators_login}</td>
                        <td style="font-weight: bold; text-align: center;" bgcolor="#CCCCCC">{$lng.lbl_products_add}</td>
                        <td style="font-weight: bold; text-align: center;" bgcolor="#CCCCCC">{$lng.lbl_products_mod}</td>
                        <td style="font-weight: bold; text-align: center;" bgcolor="#CCCCCC">{$lng.lbl_products_score}</td>
                    </tr>
                    {foreach from=$providers item=p key=login}
                    <tr{cycle values=", style='background-color: #EEEEEE'"}>
                        <td style="text-align: right;">{$p.name}&nbsp;({$login})</td>
                        <td style="text-align: right;">{$p.add_products_number}</td>
                        <td style="text-align: right;">{$p.mod_products_number}</td>
                        <td style="text-align: right;">{$p.products_score}</td>
            </tr>
            {/foreach}
            <tr>
                        <td style="font-weight: bold; text-align: right;" bgcolor="#CCCCCC">{$lng.lbl_totals}</td>
                        <td style="font-weight: bold; text-align: right;" bgcolor="#CCCCCC">{$totals.add_products_number}</td>
                        <td style="font-weight: bold; text-align: right;" bgcolor="#CCCCCC">{$totals.mod_products_number}</td>
                        <td style="font-weight: bold; text-align: right;" bgcolor="#CCCCCC">{$totals.products_score}</td>
                    </tr>
                </table>
            </td>
            </tr>
    
        {else}
            <tr>
                <td colspan="7">{$lng.lbl_no_products}</td>
            </tr>
        {/if}

{if $products}
<tr>
    <td colspan="7" style="font-size: 1.1em"><br /><b>{$lng.lbl_details|cat:":"}</b></td>
</tr>

{foreach from=$products item=list key=provider}
<tr>
    {assign value="`$providers[$provider].name` (`$provider`): `$providers[$provider].add_products_number` `$lng.lbl_products_added` + `$providers[$provider].mod_products_number` `$lng.lbl_products_modified` = `$providers[$provider].products_score` `$lng.lbl_products_score_sm`" var="provider_title"}
    <td colspan="7">
        <table cellspacing="0" style="width: 100%; margin-bottom: 10px" >
        <tr>
        	<td><br /><b>{$provider_title}</b></td>
        </tr>
        </table>
    </td>
</tr>
        
<tr>
    <th bgcolor="#CCCCCC" style="color: #E61AB3">{$lng.lbl_new_product}</td>
    <th bgcolor="#FFD44C" style="width: 140px">{$lng.lbl_sku_p|upper}</td>
    <th bgcolor="#FFD44C">{$lng.lbl_a|upper}</td>
    <th bgcolor="#CCCCCC">{$lng.lbl_product_name|upper}</td>
    <th bgcolor="#CCCCCC" style="width: 40px">{$lng.lbl_cat_ids}</td>
    <th bgcolor="#FFD44C">{$lng.lbl_distr|upper}</td>
    <th bgcolor="#CCCCCC">{$lng.lbl_brand|upper}</td>
</tr>

{cycle name="color" values=", style='background-color: #EEEEEE'" reset=true advance=false}
{foreach from=$list item=product}
<tr{cycle name="color" values=", style='background-color: #EEEEEE'"}>
    <td style="color: #E61AB3">{if $product.is_new eq "Y"}{$lng.lbl_new_product}{else}&nbsp;{/if}</td>
    <td style="width: 140px"><a href="{$product.links.provider}" title="">{$product.productcode}</a></td>
    <td><a href="{$product.links.admin}" title="">{$lng.lbl_a}</a></td>
    <td><a href="{$product.links.customer}" title="">{$product.product}</a></td>
    <td style="width: 40px">{foreach from=$product.categories item=c name="categories"}{if $c.domain}<a href="http://{$c.domain}/home.php?cat={$c.categoryid}" title="">{$c.categoryid}</a>{else}{$c.categoryid}{/if}{if !$smarty.foreach.categories.last}, {/if}{/foreach}</td>
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

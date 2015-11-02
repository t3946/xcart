{* $Id: product_report.tpl,v 1.0 2011/06/20 17:33:43 kate Exp $ *}
{$lng.lbl_summary}
-------------------------------

{$lng.lbl_report_date|cat:":"} {$start_date|default:$smarty.now|date_format:"%e-%b-%G, %A"}
{if $providers ne ''}

{foreach from=$providers item=p}
{$p.name|cat:":"} {$p.products_number} {$lng.lbl_products_added}
{/foreach}
    
{if $total ne ''}
{$lng.lbl_total_number_products_added|cat:":"} {$total}
{/if}
    
{else}
{$lng.lbl_no_products}
{/if}

{if $products}

{$lng.lbl_details}
-------------------------------
{foreach from=$products item=list key=provider}

{assign value="`$providers[$provider].name`: `$providers[$provider].products_number` `$lng.lbl_products_added`" var="provider_title"}
{$provider_title}
        
{foreach from=$list item=product}

{$lng.lbl_sku|upper}: {$product.productcode}
{$lng.lbl_product_name|upper}: {$product.product}
{$lng.lbl_cat_ids|upper}:
{foreach from=$product.categories item=c name="categories"}
{$c.categoryid}
{/foreach}
{$lng.lbl_distr|upper}: {$product.code}
{$lng.lbl_brand|upper}: {$product.brand}
{/foreach}

{/foreach}

{else}
    
{$lng.lbl_no_products_found}

{/if}

{include file="mail/html/signature.tpl"}

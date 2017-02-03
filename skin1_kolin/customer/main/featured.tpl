{* $Id: featured.tpl,v 1.16 2005/11/17 06:55:37 max Exp $ *}
{capture name=dialog}
{if $f_products ne ""}
{*
{if $total_pages gt 2}
<br />
{ include file="customer/main/navigation.tpl" }
{/if}
*}
{include file="customer/main/products.tpl" products=$f_products featured="Y"}
{if $total_pages gt 2}
<br />
{ include file="customer/main/navigation.tpl" featured="Y"}
{/if}
{else}
{$lng.txt_no_featured}
{/if}
{/capture}

{include file="dialog.tpl" title="<h1 style='margin:0;'>"|cat:$config.Categories.seo_featured_products_caption|stripcslashes|default:$lng.lbl_featured_products|cat:"</h1>" content=$smarty.capture.dialog extra='width="100%"' new_design="Y"}

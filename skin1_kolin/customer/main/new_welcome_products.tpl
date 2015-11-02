{* $Id: featured.tpl,v 1.16 2005/11/17 06:55:37 max Exp $ *}
{if $new_products ne ""}
{capture name=dialog}
{include file="customer/main/products.tpl" products=$new_products featured="Y"}
{/capture}
{include file="dialog.tpl" title="New products" content=$smarty.capture.dialog extra='width="100%"' new_href="/new_products.php" new_design="Y"}
<br />
<br />
{/if}

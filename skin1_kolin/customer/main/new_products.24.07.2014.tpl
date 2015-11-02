{if $new_products ne ""}
{capture name=dialog}

<table cellpadding="1" cellspacing="1" style="padding-left: 7px;">
{foreach from=$new_products item=item key=key}
<tr>
<td><a style="font-size: 13px;" href="product.php?productid={$item.productid}">{$item.product}</a></td>
</tr>
{/foreach}
</table>

{/capture}
{include file="dialog.tpl" title="New products" content=$smarty.capture.dialog extra='width="100%"'}
{/if}

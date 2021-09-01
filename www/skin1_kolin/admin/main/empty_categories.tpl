{* --- *}
{if $smarty.get.cat eq "" && $cidev_all_cats ne ""}
<br /><br />
{capture name=dialog}
{if $cidev_all_cats ne ""}
<table border="0" cellspacing="1" cellpadding="3" bgcolor="#cccccc">

{foreach from=$cidev_all_cats item=item key=key}
<tr>
{*
	{foreach from=$item.catids_arr item=v key=k}
		{if $v eq $item.categoryid}
		<a target="_blank" href="../home.php?cat={$v}">{$item.category_arr[$k]}</a><br />
		{else}
		<a target="_blank" href="category_products.php?cat={$v}">{$item.category_arr[$k]}</a> /
		{/if}
	{/foreach}
*}
{*

        {if ($item.cidev_count_products eq "0" || $item.cidev_count_products eq "") && $item.product_count eq "0"}
<a href="category_products.php?cat={$item.categoryid}">{$item.category_path}</a><br />
        {/if}
*}


<td bgcolor="#ffffff">
{$item.category_path}
</td>
<td nowrap="nowrap" bgcolor="#ffffff">
<a target="_blank" href="http://{$current_storefront_info.domain}/home.php?cat={$item.categoryid}">Front-end</a>
</td>
<td nowrap="nowrap" bgcolor="#ffffff">
<a target="_blank" href="categories.php?cat={$item.categoryid}">Subcategories on back-end</a>
</td>
<td nowrap="nowrap" bgcolor="#ffffff">
<a target="_blank" href="category_products.php?cat={$item.categoryid}">Products on back-end</a>
</td>

</tr>
{/foreach}
</table>
{else}
No empty categories
{/if}
{/capture}
{include file="dialog.tpl" title="Empty categories" content=$smarty.capture.dialog extra='width="100%"'}
{/if}
{* --- *}


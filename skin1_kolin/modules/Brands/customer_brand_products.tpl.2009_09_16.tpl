{* customer_brand_products.tpl, random *}
<br>
{capture name=dialog}


{if $brand.is_image eq 'Y' || $brand.descr ne '' || $brand.url ne ''}
<table>
<tr>
{if $brand.is_image eq 'Y'}
	<td valign="top">{if $brand.url ne ''}<a href="{$brand.url}">{/if}<img src="{if $brand.image_path ne ''}{$brand.image_path}{else}{$xcart_web_dir}/image.php?id={$brand.brandid}&amp;type=B{/if}" alt="{$brand.brand|escape}" />{if $brand.url ne ''}</a>{/if}</td>
{elseif $brand.url ne ''}
	<td>{$lng.lbl_url}: <a href="{$brand.url}">{$brand.url}</a></td>
</tr>
<tr>
{/if}
	<td valign="top">{$brand.descr}</td>
</tr>
</table>
<br />
{/if}


{if $products ne ''}
{if $sort_fields}
<div align="right">{include file="main/search_sort_by.tpl" url="brands.php?brandid=`$brand.brandid`&page=`$navigation_page`&" sort_fields=$sort_fields selected=$sort direction=$sort_direction}</div>
<hr size="1" noshade="noshade" />
<br />
{/if}
{ include file="customer/main/navigation.tpl" }
{include file="customer/main/products.tpl" products=$products}
{else}
{$lng.txt_no_products_in_brand}
{/if}
{/capture}
{include file="dialog.tpl" title=$brand.brand content=$smarty.capture.dialog extra='width="100%"'}
{ include file="customer/main/navigation.tpl" }


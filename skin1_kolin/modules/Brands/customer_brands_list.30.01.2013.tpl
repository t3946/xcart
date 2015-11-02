{* customer_brands_list.tpl, random *}
{capture name=dialog}
{ include file="customer/main/navigation.tpl" }
<br />
<table cellspacing="5">
{foreach from=$brands item=v}
<tr>
	<td class="BrandsItem"><a href="brands.php?brandid={$v.brandid}"><font class="ItemsList">{$v.brand|escape}</font></a></td>
</tr>
{/foreach}
</table>
<br /><br />
{ include file="customer/main/navigation.tpl" }
{/capture}
<p />
{include file="dialog.tpl" title=$lng.lbl_brands content=$smarty.capture.dialog extra='width="100%"'}

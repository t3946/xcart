<br />
{capture name=dialog}

<table border="0" width="100%" cellpadding="3" cellspacing="1">

<tr class='TableSubHead' >
<td width="5"><B>R</B></td>
<td><B>Category path</B></td>
{*
<td nowrap="nowrap"><B>Front-end link</B></td>
*}
<td nowrap="nowrap"><B>Back-end link</B></td>
</tr>

{foreach from=$all_categories item=v key=k}
<tr {cycle values=", class='TableSubHead'" name="cycle_totals"}>

<td>{if $v.pc_ready_to_classify eq "Y"}*{/if}</td>

<td>
{if $v.categoryid_path_arr ne ""}
{foreach from=$v.categoryid_path_arr item=vv key=kk}
{if $kk eq ($v.categoryid_path_arr_count - 1)}<a href="http://{$current_storefront_info.domain}/home.php?cat={$v.categoryid}" target="_blank" style="color: blue;">{if $v.product_count gt 0}<B>{/if}{/if}{$vv}{if $kk eq ($v.categoryid_path_arr_count - 1)}{if $v.product_count gt 0}</B>{/if}</a>{/if}{if $kk < ($v.categoryid_path_arr_count - 1)} <B>></B> {/if} {if $v.product_count gt 0 && $kk eq ($v.categoryid_path_arr_count - 1)}({$v.count_pc_products}){/if}
{/foreach}
{/if}
</td>

<td>
<a href="category_modify.php?cat={$v.categoryid}" target="_blank" style="color: blue;">Back-end link</a>
</td>

</tr>
{/foreach}

</table>

{/capture}
{include file="dialog.tpl" title="Category structure" content=$smarty.capture.dialog extra='width="100%"'}

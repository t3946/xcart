<br />
{capture name=dialog}

<table border="0" width="100%" cellpadding="3" cellspacing="1">

<tr class='TableSubHead' >
<td><B>Category path</B></td>
<td nowrap="nowrap"><B>Front-end link</B></td>
<td nowrap="nowrap"><B>Back-end link</B></td>
</tr>

{foreach from=$all_not_pc_cats item=v key=k}
<tr {cycle values=", class='TableSubHead'" name="cycle_totals"}>
<td>
{if $v.categoryid_path_arr ne ""}
{foreach from=$v.categoryid_path_arr item=vv key=kk}
{$vv}{if $kk < ($v.categoryid_path_arr_count - 1)} > {/if}
{/foreach}
{/if}
</td>

<td>
<a href="http://{$current_storefront_info.domain}/home.php?cat={$v.categoryid}" target="_blank" style="color: blue;">Front-end link</a>
</td>

<td>
<a href="admin/category_modify.php?cat={$v.categoryid}" target="_blank" style="color: blue;">Back-end link</a>
</td>

</tr>
{/foreach}

</table>

{/capture}
{include file="dialog.tpl" title="Categories containing no classified products" content=$smarty.capture.dialog extra='width="100%"'}

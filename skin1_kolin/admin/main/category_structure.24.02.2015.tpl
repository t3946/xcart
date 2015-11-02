<br />
{capture name=dialog}

<form name="pc_form2" action="category_structure.php" method="POST">
<input type="hidden" name="mode" value="update">

<table border="0" width="100%" cellpadding="3" cellspacing="1">

<tr class='TableSubHead' >
<td width="5"><B>R</B></td>
<td><B>Category path</B></td>

<td nowrap="nowrap"><B>Inherited taxonomy</B></td>
<td nowrap="nowrap"><B>Google Product Category</B></td>

<td nowrap="nowrap"><B>Back-end link</B></td>
</tr>

{foreach from=$all_categories item=v key=k}
<tr {cycle values=", class='TableSubHead'" name="cycle_totals"}
{if $v.prev_google_product_category ne ""}
style="background: #FFD44A;"
{elseif $v.prev_google_product_category eq "" && $v.google_product_category eq ""}
style="background: #BD4932;"
{/if}
>

<td>{if $v.pc_ready_to_classify eq "Y"}*{/if}</td>

<td nowrap="nowrap">
{if $v.categoryid_path_arr ne ""}
{foreach from=$v.categoryid_path_arr item=vv key=kk}
{if $kk eq ($v.categoryid_path_arr_count - 1)}<a href="http://{$current_storefront_info.domain}/home.php?cat={$v.categoryid}" target="_blank" style="color: blue;">{if $v.product_count gt 0}<B>{/if}{/if}{$vv}{if $kk eq ($v.categoryid_path_arr_count - 1)}{if $v.product_count gt 0}</B>{/if}</a>{/if}{if $kk < ($v.categoryid_path_arr_count - 1)} <B>></B> {/if} {if $v.product_count gt 0 && $kk eq ($v.categoryid_path_arr_count - 1)}({$v.count_pc_products}){/if}
{/foreach}
{/if}
</td>


<td nowrap="nowrap">
{if $v.prev_google_product_category ne ""}
{$v.prev_google_product_category}
{/if}
</td>

<td nowrap="nowrap">
<input id="google_product_category_{$v.categoryid}" type="text" name="google_product_category_arr[{$v.categoryid}]" value="{$v.google_product_category}" />

<input type="button" name="b_t" value="+" onclick="javascript: window.open('popup_taxonomy.php?id=google_product_category_{$v.categoryid}','popup_taxonomy','width=800,height=600,toolbar=no,status=no,scrollbars=yes,menubar=no,location=no,direction=no');" />
</td>


<td>
<a href="category_modify.php?cat={$v.categoryid}" target="_blank" style="color: blue;">Back-end link</a>
</td>

</tr>
{/foreach}

</table>

<input type="submit" name="update" value="update" />
</form>

{/capture}
{include file="dialog.tpl" title="Category structure" content=$smarty.capture.dialog extra='width="100%"'}

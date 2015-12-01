<form name="suppform" action="configuration.php" method="POST">
<input type="hidden" name="option" value="Supplier_feeds">
<input type="hidden" name="mode" value="Update_Supplier_feeds" id="mode_Supplier_feeds">

<table width="100%" cellspacing="1" cellpadding="3">
<tr>
<td class="TableSeparator" colspan="3">
<br>Supplier feeds login options<br><br></td>
</tr>

<tr>
<td width="200" class="TableSubHead" nowrap="nowrap">Feeds storage path
</td>
<td width="*" class="TableSubHead">
<input type="text" style="width: 98%;" name="Feeds_storage_path" value="{$config.Supplier_feeds.Feeds_storage_path}">
</td>
</tr>

<tr>
<td width="200" class="TableSubHead" nowrap="nowrap">Feeds storage login
</td>
<td width="*" class="TableSubHead">
<input type="text" style="width: 98%;" name="Feeds_storage_login" value="{$config.Supplier_feeds.Feeds_storage_login}">
</td>
</tr>

<tr>
<td width="200" class="TableSubHead" nowrap="nowrap">Feeds storage password
</td>
<td width="*" class="TableSubHead">
<input type="text" style="width: 98%;" name="Feeds_storage_password" value="{$config.Supplier_feeds.Feeds_storage_password}">
</td>
</tr>

</table>

<br />
<hr />
<br />

<table width="100%" cellpadding="3">
<tr style="background-color: #EEEEEE;">
        <td valign="top" nowrap="nowrap"><b>feed_id</b></td>
        <td valign="top" nowrap="nowrap"><b>feed_name</b></td>
        <td valign="top" nowrap="nowrap"><b>feed_type</b></td>
        <td valign="top" nowrap="nowrap"><b>manufacturerid</b></td>
        <td valign="top" nowrap="nowrap"><b>storefront_id</b></td>
        <td valign="top" nowrap="nowrap"><b>base_category_id</b></td>
        <td valign="top" nowrap="nowrap"><b>feed_file_name</b></td>
        <td valign="top" nowrap="nowrap"><b>last_update_time</b></td>
        <td valign="top" nowrap="nowrap"><b>average_update_period</b></td>
        <td valign="top" nowrap="nowrap"><b>last_update_items_count</b></td>
        <td valign="top" nowrap="nowrap"><b>threshold</b></td>
        <td valign="top" nowrap="nowrap"><b>add_new_only</b></td>
        <td valign="top" nowrap="nowrap"><b>last_md5</b></td>
        <td valign="top" nowrap="nowrap"><b>Multiple feed<br />destinations</b></td>
        <td valign="top" nowrap="nowrap"><b>Disable search of<br />discontinued items</b></td>
        <td valign="top" nowrap="nowrap"><b>enabled</b></td>
        <td valign="top" nowrap="nowrap"><b>delete</b></td>
</tr>

        {if $Supplier_feeds ne ""}
                {foreach from=$Supplier_feeds item=item key=key }

		<tr {if $item.enabled eq "Y"} style="background-color: {if $item.last_update_late eq "0"}#00ff00{/if}{if $item.last_update_late eq "1"}#fbef7e{/if}{if $item.last_update_late gte "2"}#ff0000{/if} "{/if}{cycle name="embed" values=", class='TableSubHead'"}>
                
<td valign="top" align="center">
{$item.feed_id}
<input type="hidden"  name="Supplier_feeds[{$key}][feed_id]" value="{$item.feed_id}" />
</td>

<td valign="top" align="center">
<input type="text"  name="Supplier_feeds[{$key}][feed_name]" value="{$item.feed_name}" size="20" />
</td>

<td valign="top" align="center" width="10">
<select name="Supplier_feeds[{$key}][feed_type]">
<option value="I"{if $item.feed_type eq "I"} selected="selected"{/if}>inventory</option>
<option value="P"{if $item.feed_type eq "P"} selected="selected"{/if}>product</option>
</select>
</td>

<td valign="top" align="center" width="10">
<input type="text"  name="Supplier_feeds[{$key}][manufacturerid]" value="{$item.manufacturerid}" size="4" />
</td>

<td valign="top" align="center" width="10">
<input type="text"  name="Supplier_feeds[{$key}][storefront_id]" value="{$item.storefront_id}" size="4" />
</td>

<td valign="top" align="center" width="10">
<input type="text"  name="Supplier_feeds[{$key}][base_category_id]" value="{$item.base_category_id}" size="4" />
</td>

<td valign="top" align="center">
<input type="text"  name="Supplier_feeds[{$key}][feed_file_name]" value="{$item.feed_file_name}" style="width: 96%;" />
</td>

<td valign="top" align="center">
{$item.last_update_time|date_format:'%d-%b-%Y %H:%M'}
<input type="hidden"  name="Supplier_feeds[{$key}][last_update_time]" value="{$item.last_update_time|date_format:'%d-%b-%Y %H:%M'}" />
</td>

<td valign="top" align="center">
{$item.average_update_period_str}
<input type="hidden"  name="Supplier_feeds[{$key}][average_update_period]" value="{$item.average_update_period}" />
</td>

<td valign="top" align="center">
{$item.last_update_items_count}
<input type="hidden"  name="Supplier_feeds[{$key}][last_update_items_count]" value="{$item.last_update_items_count}" />
</td>

<td valign="top" align="center" width="10">
<input type="text"  name="Supplier_feeds[{$key}][threshold]" value="{$item.threshold}" size="4" />
</td>

<td valign="top" align="center" width="10">
<input type="checkbox" name="Supplier_feeds[{$key}][add_new_only]" value="Y" {if $item.add_new_only eq "Y"}checked="checked"{/if}/>
</td>

<td valign="top" align="center">
{$item.last_md5}
<input type="hidden"  name="Supplier_feeds[{$key}][last_md5]" value="{$item.last_md5}" />
</td>

<td valign="top" align="center" width="10">
<input type="checkbox" name="Supplier_feeds[{$key}][multiple_feed_destinations]" value="Y" {if $item.multiple_feed_destinations eq "Y"}checked="checked"{/if}/>
</td>

<td valign="top" align="center" width="10">
<input type="checkbox" name="Supplier_feeds[{$key}][disable_search_of_discontinued_items]" value="Y" {if $item.disable_search_of_discontinued_items eq "Y"}checked="checked"{/if}/>
</td>

<td valign="top" align="center" width="10">
<input type="checkbox" name="Supplier_feeds[{$key}][enabled]" value="Y" {if $item.enabled eq "Y"}checked="checked"{/if}/>
</td>

<td valign="top" align="center" width="10">
<input type="checkbox" name="Supplier_feeds[{$key}][delete]" value="Y" />
</td>

                </tr>

                {/foreach}
	{else}
<tr>
<td colspan="15">Empty</td>
</tr>
        {/if}

</table>

{if $Supplier_feeds ne ""}
<br />
<input type="submit" value=" Save ">
{/if}
<br >
<INPUT type="button" value="Add new" onclick="document.suppform.mode.value='Add_Supplier_feed'; document.suppform.submit();">
</form>


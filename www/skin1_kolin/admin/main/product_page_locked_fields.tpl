{if $manufacturer_feed_fields ne ""}

<form action="manufacturers.php" method="post" name="manufacturer_locked_fields">
<input type="hidden" name="mode" value="update" id="mode" />
<input type="hidden" name="manufacturerid" value="{$manufacturer.manufacturerid}" />
<input type="hidden" name="page" value="{$page}" />
<input type="hidden" name="distributor_section" value="{$distributor_section}" />

<table align="center" cellpadding="3" cellspacing="1" align="center">

<tr>
<td><B>Field name</B></td>
<td><B>Lock</B></td>
<td><B>Admin lock</B></td>
<td><B>Feed name</B></td>
</tr>

{foreach from=$manufacturer_feed_fields item=feed_field key=k_feed_field}
<tr>
 <td>{$feed_field.field_name}</td>
 <td align="center">{$feed_field.locked}</td>
 <td align="center">
	<input type="checkbox" name="admin_lock[{$k_feed_field}]" value="Y"{if $feed_field.admin_lock eq "Y"} checked="checked"{/if} />
	<input type="hidden" name="feed_id[{$k_feed_field}]" value="{$feed_field.feed_id}" />
	<input type="hidden" name="field_name[{$k_feed_field}]" value="{$feed_field.field_name}" />
 </td>
 <td>{$feed_field.feed_name}</td>
</tr>
{/foreach}

<tr><td colspan="4" align="center"><input type="submit" value="Apply changes" /></td></tr>

</table>

</form>
{else}
Empty
{/if}

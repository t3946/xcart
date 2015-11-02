{*
{assign var="order_details_name" value="Order # `$order.order_prefix``$order.orderid`"}
{include file="page_title.tpl" title=$order_details_name}
<br />
{capture name=dialog}
*}
<table align="center">
{foreach from=$order_manufacturers item=v key=k}
<tr><td colspan="3" align="center"><h2><B>{$v.manufacturer}</B></h2></td></tr>
<tr>
<td><B>Shipping from</B></td>
<td><B>Shipping to</B></td>
<td>
<form action="order.php" method="post" name="ground_map_form">
<input type="hidden" name="mode" value="calc_shipping" />
<input type="hidden" name="orderid" value="{$order.orderid}" />
<input type="hidden" name="mid" value="{$k}" />
<input type="submit" value="Shipping quote" />
</form>
</td>
</tr>
<tr>
<td valign="top">
{$v.m_city}, {$v.m_state} {$v.m_zipcode}<br />
{$v.m_country}
</td>
<td valign="top">
{$order.s_city}, {$order.s_state} {$order.s_zipcode}<br />
{$order.s_country}
</td>

<td valign="top">
{if $show_intershipper_rates ne ""}
{foreach from=$show_intershipper_rates item=vr key=kr}
{if $kr eq $k}
	{foreach from=$vr item=vvr key=kkr}
		{if $vvr.shipping ne ""}

{assign var="cidev_shipping" value=$vvr.shipping|trademark:"`$insert_trademark`"}
{if $vvr.shipping_time ne ""} 
{assign var="cidev_shipping" value="`$cidev_shipping` - `$vvr.shipping_time`: $`$vvr.rate`"}
{else}
{assign var="cidev_shipping" value="`$cidev_shipping` $`$vvr.rate`"}
{/if}

{$cidev_shipping}<br />

		{/if}
	{/foreach}
{/if}
{/foreach}
{/if}
</td>
</tr>
<tr>
<td colspan="3" align="center">
{if $v.map_url ne ""}
<img src="{$v.map_url}" />
{else}
<B>Map not found</B>
{/if}
</td>
</tr>
<tr>
<td colspan="3" align="center">
<iframe width="600" height="450" frameborder="1" style="border:1"
src="https://www.google.com/maps/embed/v1/directions?mode=flying&center=53.125408,-122.977192&zoom=4&origin={$v.m_zipcode},{$v.m_country_name_for_google}&destination={$order.s_zipcode},+{$order.s_countryname|replace:' ':'+'}&key=AIzaSyCPzjKUu3bYLgseHXRlNR-Cxo0F1IG3v58"></iframe>
</td>
</tr>
<tr><td colspan="3"><hr /><br /></td></tr>
{/foreach}
</table>
{*
{/capture}
{include file="dialog.tpl" title="Ground map" content=$smarty.capture.dialog extra='width="100%"'}
*}

<table align="center" width="100%">
{foreach from=$order_manufacturers item=v key=k}
<tr><td colspan="2" align="center"><h2><B>{$v.manufacturer}</B></h2></td></tr>
<tr>
<td>

<table>
<tr>
<td>
{if $v.map_url ne ""}
<img src="{$v.map_url}" />
{else}
<B>Map not found</B>
{/if}
</td>
</tr>
</table>

</td>

<td valign="top">
<table>

<tr>
<td valign="top">
<B>Shipping from</B><br />
{$v.m_city}, {$v.m_state} {$v.m_zipcode}<br />
{$v.m_country}
<br />
</td>
</tr>
<tr>
<td valign="top">
<B>Shipping to</B><br />
{$order.s_city}, {$order.s_state} {$order.s_zipcode}<br />
{$order.s_country}
<br />
</td>
</tr>
<tr>
<td valign="top">
<form action="order.php" method="post" name="ground_map_form">
<input type="hidden" name="mode" value="calc_shipping" />
<input type="hidden" name="orderid" value="{$order.orderid}" />
<input type="hidden" name="mid" value="{$k}" />
<input type="submit" value="Shipping quote" />
</form>
<br />
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
<br />
</td>
</tr>

</table>
</td>
</tr>



<tr>
<td>
<iframe width="600" height="450" frameborder="1" style="border:1"
src="https://www.google.com/maps/embed/v1/directions?mode=flying&center=53.125408,-122.977192&zoom=4&origin={$v.m_zipcode},{$v.m_country_name_for_google}&destination={$order.s_zipcode},+{$order.s_countryname|replace:' ':'+'}&key=AIzaSyCPzjKUu3bYLgseHXRlNR-Cxo0F1IG3v58"></iframe>
</td>

<td valign="top">
        <table>

          <tr>
            <td>
                <B>Distributor time:</B> {$v.distributor_time|date_format:'%d-%b-%Y&nbsp; %H:%M'}
                <br />
                <B>Distributor phone:</B> {$v.distributor_phone}
            </td>
          </tr>

          <tr>
            <td class="call_btn_distr_{if $v.good_time_to_send_email_to_distributor eq "Y"}a{else}d{/if}" width="219" height="44">
                <a target="_blank" href="tel:{if $v.distributor_phone_phone_normalized ne ""}{$v.distributor_phone_phone_normalized}{else}{$v.distributor_phone}{/if}"><div style="width: 219px; height: 44px;"></div></a>
            </td>
          </tr>

          <tr>
            <td>
                <B>Customer time:</B> {$customer.customer_time|date_format:'%d-%b-%Y&nbsp; %H:%M'}
                <br />
                <B>Customer phone:</B> {$customer.phone}
            </td>
          </tr>

          <tr>
            <td class="call_btn_customer_{if $customer.good_time_to_send_email_to_customer eq "Y"}a{else}d{/if}" width="219" height="44">
                <a target="_blank" href="tel:{if $customer.phone_normalized ne ""}{$customer.phone_normalized}{else}{$customer.phone}{/if}"><div style="width: 219px; height: 44px;"></div></a>
            </td>
          </tr>


        </table>
</td>
</tr>


<tr><td colspan="2"><hr /><br /></td></tr>
{/foreach}
</table>

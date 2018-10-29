<table align="center" width="100%">
    {foreach from=$order_manufacturers item=v key=k}
        <tr>
            <td colspan="2" align="center"><h2><B>{$v.manufacturer}</B></h2></td>
        </tr>
        <tr>
            <td>

                <table>
                    <tr>
                        <td>
                            {if $v.map_url ne ""}
                                <img src="{$v.map_url}"/>
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
                            <B>Shipping from</B><br/>
                            {$v.m_city}, {$v.m_state} {$v.m_zipcode}<br/>
                            {$v.m_country}
                            <br/>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <B>Shipping to</B><br/>
                            {$order.s_city}, {$order.s_state} {$order.s_zipcode}<br/>
                            {$order.s_country}
                            <br/>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <form class="ground_map_form" action="order.php" method="post" name="ground_map_form">
                                <input type="hidden" name="mode" value="calc_shipping"/>
                                <input type="hidden" name="orderid" value="{$order.orderid}"/>
                                <input type="hidden" name="mid" value="{$k}"/>
                                <input name="get_shipping_charge" type="button" value="Get shipping quote"/>
                            </form>
                            <p id="shipping_charge_{$k}"></p>
                            <form action="order.php" method="post" name="ground_map_incorrect_form">
                                <input type="hidden" name="mode" value="map_incorrect"/>
                                <input type="hidden" name="orderid" value="{$order.orderid}"/>
                                <input type="hidden" name="zipcode" value="{$v.m_zipcode}"/>
                                <input type="submit" value='Refresh UPS map'/>
                            </form>

                        </td>
                    </tr>

                </table>
            </td>
        </tr>
        <tr>
            <td>
                <iframe width="600" height="450" frameborder="1" style="border:1"
                        src="https://www.google.com/maps/embed/v1/directions?mode=flying&zoom=4&origin={$v.m_zipcode},{$v.m_country_name_for_google}&destination={$order.s_zipcode},+{$order.s_countryname|replace:' ':'+'}&key=AIzaSyCv9x3eaQ6pmDU6AoffekkTjHOH8QXk7iM"></iframe>
            </td>

            <td valign="top">
                <table>

                    <tr>
                        <td width="320">
                            <B>{* Distributor *}{$v.manufacturer}
                                time:</B> {$v.distributor_time|date_format:'%d-%b-%Y&nbsp; %H:%M'}
                            <br/>
                            <B>{* Distributor *}{$v.manufacturer}
                                phone:</B> {$v.distributor_phone} {if $v.distributor_phone_ext}<b>
                                ext {$v.distributor_phone_ext}</b>{/if}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <div class="call_btn_distr_{if $v.good_time_to_send_email_to_distributor eq "Y"}a{else}d{/if}">
                                <a target="_blank"
                                   href="tel:{if $v.distributor_phone_phone_normalized ne ""}{$v.distributor_phone_phone_normalized}{else}{$v.distributor_phone}{/if}">
                                    <div style="width: 219px; height: 44px;"></div>
                                </a>
                            </div>
                        </td>
                    </tr>

                    {assign var="key_carrier" value=$order.shipping_groups[$k].tracking.0.carrier_id}
                    {if $key_carrier ne ""}
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td><B>Shipper phone:</B> {$tracking_links_carrier[$key_carrier].phone}</td>
                        </tr>
                        <tr>
                            <td>

                                <div class="call_btn call_btn_shipper">
                                    <a target="_blank" href="tel:{$tracking_links_carrier[$key_carrier].phone}">
                                        <div style="width: 219px; height: 44px;"></div>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    {/if}
                    <tr>
                        <td>&nbsp;</td>
                    </tr>

                    <tr>
                        <td width="219">
                            <B>Customer time:</B> {$customer.customer_time|date_format:'%d-%b-%Y&nbsp; %H:%M'}
                            <br/>
                            <B>Customer phone:</B> {$customer.phone}
                        </td>
                    </tr>

                    <tr>
                        <td>

                            <div class="call_btn_customer_{if $customer.good_time_to_send_email_to_customer eq "Y"}a{else}d{/if}">
                                <a target="_blank"
                                   href="tel:{if $customer.phone_normalized ne ""}{$customer.phone_normalized}{else}{$customer.phone}{/if}">
                                    <div style="width: 219px; height: 44px;"></div>
                                </a>
                            </div>
                        </td>
                    </tr>


                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <hr/>
                <br/></td>
        </tr>
    {/foreach}
</table>

{literal}
    <script type="text/javascript">
        $('form.ground_map_form input[name=get_shipping_charge]').click(function () {
            var manid = $(this).siblings('input[name=mid]').val();
            var button = $(this);
            button.prop('disabled', true);
            $.post('ajax_admin.php', {
                    orderid: $(this).siblings('input[name=orderid]').val(),
                    manufacturerid: manid,
                    ajax_action: 'get_order_shipping_charge'
                },
                function (data) {
                    $('#shipping_charge_' + manid).html(data);
                    button.prop('disabled', false);
                })
        });
    </script>
{/literal}
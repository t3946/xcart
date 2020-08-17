<div class="order_maps-container">
    <ul>
        {foreach from=$order_manufacturers item=v key=k}
            <li><a href="#maps-tabs-{$k}">{$v.code}</a></li>
        {/foreach}
    </ul>
{foreach from=$oOrderGroups item=group}
    {assign var=dx value=$group->manufacturer}

    <div id="maps-tabs-{$group->manufacturerid}">
        <table align="center" width="100%">
            <tr>
                <td colspan="2" align="center"><h2><B>{$dx->manufacturer}</B></h2></td>
            </tr>
            <tr>
                <td>
                    <table>
                        <tr>
                            <td>
                                <img style="max-width: 600px" src="/api/upsmap/{$dx->m_zipcode}"/>
                            </td>
                        </tr>
                    </table>
                </td>
                <td valign="top">
                    <table>
                        <tr>
                            <td valign="top">
                                <B>Shipping from</B><br/>
                                {$dx->m_city}, {$dx->m_state} {$dx->m_zipcode}<br/>
                                {$dx->m_country}
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
                                    <input type="hidden" name="mid" value="{$dx->manufacturerid}"/>
                                    <input name="get_shipping_charge" type="button" value="Get shipping quote"/>
                                </form>
                                <p id="shipping_charge_{$dx->manufacturerid}"></p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <iframe width="600" height="450" frameborder="1" style="border:1px"
                            src="https://www.google.com/maps/embed/v1/place?language=en-us&zoom=4&q={$order.s_zipcode},+{$order.s_address},+{$order.s_city},+{$order.s_countryname|replace:' ':'+'}&key=AIzaSyCv9x3eaQ6pmDU6AoffekkTjHOH8QXk7iM"></iframe>
                </td>

                <td valign="top">
                    <table>
                        <tr>
                            <td width="320">
                                <B>{$dx->manufacturer}
                                    time:</B> {$dx->getDistributorTime()|date_format:'%d-%b-%Y&nbsp; %H:%M'}
                                <br/>
                                <B>{$dx->manufacturer} phone:</B> {$dx->getPhone()}
                                {if $dx->getPhoneExt()}<b> ext {$dx->getPhoneExt()}</b>{/if}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="call_btn_distr_{if $dx->isGoodTimeToSendEmail()}a{else}d{/if}">
                                    <a target="_blank" onclick="{literal}$.ajax({{/literal}url: '/admin/order/api/activity/{$group->orderid}/calldx' {literal}}){/literal}"
                                       href="tel:{$dx->getPhoneNormalized()}">
                                        <div style="width: 219px; height: 44px;"></div>
                                    </a>
                                </div>
                            </td>
                        </tr>

                        {if $group->trackings->count()}
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td><B>Shipper phone:</B> {$group->trackings[0]->carrier->phone}</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="call_btn call_btn_shipper">
                                        <a target="_blank" onclick="{literal}$.ajax({{/literal}url: '/admin/order/api/activity/{$group->orderid}/callship' {literal}}){/literal}"
                                           href="tel:{$group->trackings[0]->carrier->phone}">
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
                                <B>Customer time:</B> {$group->order->getCxDateTime()|date_format:'%d-%b-%Y&nbsp; %H:%M'}
                                <br/>
                                <B>Customer phone:</B> {$customer.phone}
                            </td>
                        </tr>

                        <tr>
                            <td>

                                <div class="call_btn_customer_{if $customer.good_time_to_send_email_to_customer eq "Y"}a{else}d{/if}">
                                    <a target="_blank" onclick="{literal}$.ajax({{/literal}url: '/admin/order/api/activity/{$group->orderid}/callcx'{literal}}){/literal}"
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
        </table>
    </div>
{/foreach}
</div>


{literal}
    <script>
        $( function() {
            $( ".order_maps-container" ).tabs();
        } );
    </script>
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